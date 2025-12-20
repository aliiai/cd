<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Ticket Controller for Admin
 * 
 * يدير شكاوى جميع المستخدمين
 */
class TicketController extends Controller
{
    /**
     * عرض قائمة جميع الشكاوى
     * 
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = auth()->user();
        if (!$user->hasRole('super_admin') && !$user->can('view support tickets')) {
            abort(403, 'غير مصرح لك بعرض التذاكر.');
        }
        $query = Ticket::with(['messages' => function($q) {
            $q->latest()->limit(1);
        }, 'user']);
        
        // البحث
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('ticket_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
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
                'table' => view('admin.tickets.partials.tickets-table', compact('tickets'))->render(),
                'pagination' => $tickets->appends($request->query())->links()->render(),
            ]);
        }
        
        return view('admin.tickets.index', compact('tickets'));
    }

    /**
     * عرض تفاصيل الشكوى
     * 
     * @param Ticket $ticket
     * @return \Illuminate\View\View
     */
    public function show(Ticket $ticket)
    {
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = auth()->user();
        if (!$user->hasRole('super_admin') && !$user->can('view support tickets')) {
            abort(403, 'غير مصرح لك بعرض التذاكر.');
        }
        $ticket->load(['messages' => function($query) {
            $query->orderBy('created_at', 'asc');
        }, 'messages.user', 'user']);
        
        return view('admin.tickets.show', compact('ticket'));
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
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = auth()->user();
        if (!$user->hasRole('super_admin') && !$user->can('manage support tickets')) {
            abort(403, 'غير مصرح لك بإدارة التذاكر.');
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
        $validated['is_admin'] = true;

        $message = TicketMessage::create($validated);

        // تحديث حالة الشكوى
        if ($request->has('status')) {
            $ticket->update(['status' => $request->status]);
        } elseif ($ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        // إرسال إشعار للمالك
        try {
            $ticket->user->notify(new \App\Notifications\TicketRepliedNotification($ticket, $message));
        } catch (\Exception $e) {
            \Log::error('Failed to send ticket reply notification: ' . $e->getMessage());
        }

        return back()->with('success', 'تم إرسال الرد بنجاح.');
    }

    /**
     * تحديث حالة الشكوى
     * 
     * @param Request $request
     * @param Ticket $ticket
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Ticket $ticket)
    {
        // التحقق من الصلاحية (Super Admin لديه جميع الصلاحيات)
        $user = auth()->user();
        if (!$user->hasRole('super_admin') && !$user->can('manage support tickets')) {
            abort(403, 'غير مصرح لك بإدارة التذاكر.');
        }
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,waiting_user,closed',
        ]);

        $oldStatus = $ticket->status;
        $ticket->update($validated);

        // إذا تم الإغلاق
        if ($validated['status'] === 'closed' && $oldStatus !== 'closed') {
            $ticket->close();
        } elseif ($validated['status'] !== 'closed' && $oldStatus === 'closed') {
            $ticket->reopen();
        }

        // إرسال إشعار للمالك
        try {
            $ticket->user->notify(new \App\Notifications\TicketStatusChangedNotification($ticket, $oldStatus, $validated['status']));
        } catch (\Exception $e) {
            \Log::error('Failed to send ticket status notification: ' . $e->getMessage());
        }

        return back()->with('success', 'تم تحديث حالة الشكوى بنجاح.');
    }
}
