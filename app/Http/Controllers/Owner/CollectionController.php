<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\CollectionCampaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Collection Controller for Owner
 * 
 * يدير حملات التحصيل (Collection Campaigns)
 */
class CollectionController extends Controller
{
    /**
     * عرض صفحة حملات التحصيل
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // جلب جميع المديونين للمالك الحالي
        $clients = Client::where('owner_id', Auth::id())->get();
        
        // جلب جميع الحملات السابقة للمالك الحالي
        $campaigns = CollectionCampaign::where('owner_id', Auth::id())
            ->with('clients')
            ->latest()
            ->get();
        
        return view('owner.collections.index', compact('clients', 'campaigns'));
    }

    /**
     * إنشاء حملة تحصيل جديدة
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $validated = $request->validate([
            'client_ids' => 'required|array|min:1',
            'client_ids.*' => 'exists:clients,id',
            'channel' => 'required|in:sms,email',
            'template' => 'nullable|string',
            'message' => 'required|string|min:10',
            'send_type' => 'required|in:now,scheduled',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        // التحقق من أن المديونين يخصون المالك الحالي
        $clients = Client::where('owner_id', Auth::id())
            ->whereIn('id', $validated['client_ids'])
            ->get();

        if ($clients->count() !== count($validated['client_ids'])) {
            return back()->with('error', 'بعض المديونين المحددين غير موجودين أو لا يخصونك.');
        }

        // تحديد حالة الحملة
        $status = $validated['send_type'] === 'now' ? 'sent' : 'scheduled';

        // إنشاء الحملة
        $campaign = CollectionCampaign::create([
            'owner_id' => Auth::id(),
            'channel' => $validated['channel'],
            'template' => $validated['template'] ?? null,
            'message' => $validated['message'],
            'send_type' => $validated['send_type'],
            'scheduled_at' => $validated['send_type'] === 'scheduled' ? $validated['scheduled_at'] : null,
            'status' => $status,
            'total_recipients' => $clients->count(),
            'sent_count' => $status === 'sent' ? $clients->count() : 0,
        ]);

        // ربط المديونين بالحملة
        $campaign->clients()->attach($validated['client_ids']);

        // في حالة الإرسال الفوري، تحديث حالة المديونين في pivot
        if ($status === 'sent') {
            foreach ($validated['client_ids'] as $clientId) {
                $campaign->clients()->updateExistingPivot($clientId, [
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
            }
        }

        $message = $status === 'sent' ? 'تم إرسال الحملة بنجاح.' : 'تم جدولة الحملة بنجاح.';
        
        return redirect()->route('owner.collections.index')
            ->with('success', $message);
    }

    /**
     * عرض تفاصيل حملة معينة
     * 
     * @param CollectionCampaign $campaign
     * @return \Illuminate\View\View
     */
    public function show(CollectionCampaign $campaign)
    {
        // التحقق من أن الحملة تخص المالك الحالي
        if ($campaign->owner_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بعرض هذه الحملة.');
        }

        $campaign->load('clients');
        
        return view('owner.collections.show', compact('campaign'));
    }
}

