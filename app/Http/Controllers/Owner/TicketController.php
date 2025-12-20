<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Ticket Controller for Owner
 * 
 * يدير شكاوى المالك
 */
class TicketController extends Controller
{
    /**
     * عرض قائمة شكاوى المالك
     * 
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Ticket::where('user_id', Auth::id())
            ->with(['messages' => function($q) {
                $q->latest()->limit(1);
            }, 'user']);
        
        // البحث
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('ticket_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // فلترة حسب الحالة
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // فلترة حسب النوع
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }
        
        // الترتيب
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);
        
        $tickets = $query->paginate(10);
        
        // إذا كان الطلب AJAX
        if ($request->ajax()) {
            return response()->json([
                'table' => view('owner.tickets.partials.tickets-table', compact('tickets'))->render(),
                'pagination' => $tickets->appends($request->query())->links()->render(),
            ]);
        }
        
        return view('owner.tickets.index', compact('tickets'));
    }

    /**
     * عرض صفحة إنشاء شكوى جديدة
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('owner.tickets.create');
    }

    /**
     * حفظ شكوى جديدة
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'subject' => 'required|string|max:255',
                'type' => 'required|in:technical,subscription,messages,general,suggestion',
                'description' => 'required|string|min:10',
                'attachment' => 'nullable|image|max:2048',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'يرجى التحقق من البيانات المدخلة',
                    'errors' => $e->errors(),
                ], 422);
            }
            throw $e;
        }

        // رفع المرفق إن وجد
        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('tickets', 'public');
        }

        $validated['user_id'] = Auth::id();
        
        $ticket = Ticket::create($validated);

        // إنشاء رسالة أولى
        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $validated['description'],
            'attachment' => $validated['attachment'] ?? null,
            'is_admin' => false,
        ]);

        // إرسال إشعار للأدمن
        try {
            $admins = \App\Models\User::role('admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\TicketCreatedNotification($ticket));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send ticket notification: ' . $e->getMessage());
        }

        // إذا كان الطلب AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الشكوى بنجاح. رقم الشكوى: ' . $ticket->ticket_number,
                'ticket_id' => $ticket->id,
            ]);
        }

        return redirect()->route('owner.tickets.show', $ticket)
            ->with('success', 'تم إنشاء الشكوى بنجاح. رقم الشكوى: ' . $ticket->ticket_number);
    }

    /**
     * عرض تفاصيل الشكوى
     * 
     * @param Ticket $ticket
     * @return \Illuminate\View\View
     */
    public function show(Ticket $ticket)
    {
        // التحقق من أن الشكوى تخص المالك الحالي
        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بعرض هذه الشكوى.');
        }

        $ticket->load(['messages' => function($query) {
            $query->orderBy('created_at', 'asc');
        }, 'messages.user', 'user']);
        
        return view('owner.tickets.show', compact('ticket'));
    }

    /**
     * إرسال رد على الشكوى
     * 
     * @param Request $request
     * @param Ticket $ticket
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reply(Request $request, Ticket $ticket)
    {
        // التحقق من أن الشكوى تخص المالك الحالي
        if ($ticket->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالرد على هذه الشكوى.');
        }

        // التحقق من أن الشكوى غير مغلقة
        if ($ticket->status === 'closed') {
            return back()->with('error', 'لا يمكن الرد على شكوى مغلقة.');
        }

        $validated = $request->validate([
            'message' => 'required|string|min:5',
            'attachment' => 'nullable|image|max:2048',
        ]);

        // رفع المرفق إن وجد
        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('tickets', 'public');
        }

        $validated['ticket_id'] = $ticket->id;
        $validated['user_id'] = Auth::id();
        $validated['is_admin'] = false;

        $message = TicketMessage::create($validated);

        // تحديث حالة الشكوى
        if ($ticket->status === 'waiting_user') {
            $ticket->update(['status' => 'open']);
        }

        // إرسال إشعار للأدمن
        try {
            $admins = \App\Models\User::role('admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\TicketRepliedNotification($ticket, $message));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send ticket reply notification: ' . $e->getMessage());
        }

        return back()->with('success', 'تم إرسال الرد بنجاح.');
    }

    /**
     * إغلاق الشكوى
     * 
     * @param Request $request
     * @param Ticket $ticket
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function close(Request $request, Ticket $ticket)
    {
        // التحقق من أن الشكوى تخص المالك الحالي
        if ($ticket->user_id !== Auth::id()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مصرح لك بإغلاق هذه الشكوى.'
                ], 403);
            }
            abort(403, 'غير مصرح لك بإغلاق هذه الشكوى.');
        }

        if (!$ticket->canBeClosed()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'الشكوى مغلقة بالفعل.'
                ], 400);
            }
            return back()->with('error', 'الشكوى مغلقة بالفعل.');
        }

        $ticket->close();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم إغلاق الشكوى بنجاح.'
            ]);
        }

        return back()->with('success', 'تم إغلاق الشكوى بنجاح.');
    }
}

