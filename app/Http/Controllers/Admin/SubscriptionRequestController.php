<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * SubscriptionRequest Controller for Admin
 * 
 * يدير طلبات الاشتراك (Subscription Requests)
 */
class SubscriptionRequestController extends Controller
{
    /**
     * عرض قائمة جميع طلبات الاشتراك
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $requests = SubscriptionRequest::with(['user', 'subscription', 'approver'])
            ->latest()
            ->get();
        
        return view('admin.subscription-requests.index', compact('requests'));
    }

    /**
     * عرض تفاصيل طلب اشتراك معين
     * 
     * @param SubscriptionRequest $request
     * @return \Illuminate\View\View
     */
    public function show(SubscriptionRequest $request)
    {
        $request->load(['user', 'subscription', 'approver']);
        return view('admin.subscription-requests.show', compact('request'));
    }

    /**
     * قبول طلب اشتراك
     * 
     * @param Request $httpRequest
     * @param SubscriptionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Request $httpRequest, SubscriptionRequest $request)
    {
        // التحقق من أن الطلب في حالة معلقة
        if ($request->status !== 'pending') {
            return back()->with('error', 'لا يمكن الموافقة على هذا الطلب.');
        }

        // تحديث حالة الطلب
        $request->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'admin_notes' => $httpRequest->input('admin_notes'),
        ]);

        return back()->with('success', 'تم قبول طلب الاشتراك بنجاح.');
    }

    /**
     * رفض طلب اشتراك
     * 
     * @param Request $httpRequest
     * @param SubscriptionRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $httpRequest, SubscriptionRequest $request)
    {
        // التحقق من أن الطلب في حالة معلقة
        if ($request->status !== 'pending') {
            return back()->with('error', 'لا يمكن رفض هذا الطلب.');
        }

        // التحقق من وجود ملاحظات
        $validated = $httpRequest->validate([
            'admin_notes' => 'required|string|min:10',
        ]);

        // تحديث حالة الطلب
        $request->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'approved_by' => Auth::id(),
            'admin_notes' => $validated['admin_notes'],
        ]);

        return back()->with('success', 'تم رفض طلب الاشتراك.');
    }
}
