<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionRequest;
use App\Notifications\SubscriptionRequestStatusNotification;
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
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function approve(Request $httpRequest, SubscriptionRequest $request)
    {
        // التحقق من أن الطلب في حالة معلقة
        if ($request->status !== 'pending') {
            if ($httpRequest->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن الموافقة على هذا الطلب.'
                ], 400);
            }
            return back()->with('error', 'لا يمكن الموافقة على هذا الطلب.');
        }

        // تحديث حالة الطلب
        $request->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'admin_notes' => $httpRequest->input('admin_notes'),
        ]);

        // إنشاء اشتراك نشط للمستخدم
        $subscription = $request->subscription;
        $expiresAt = null;

        // حساب تاريخ الانتهاء بناءً على نوع المدة
        if ($subscription->duration_type === 'month') {
            $expiresAt = now()->addMonth();
        } elseif ($subscription->duration_type === 'year') {
            $expiresAt = now()->addYear();
        }
        // إذا كان lifetime، يبقى expires_at = null

        // إلغاء أي اشتراكات نشطة سابقة للمستخدم
        \App\Models\UserSubscription::where('user_id', $request->user_id)
            ->where('status', 'active')
            ->update(['status' => 'cancelled']);

        // إنشاء اشتراك جديد
        \App\Models\UserSubscription::create([
            'user_id' => $request->user_id,
            'subscription_id' => $subscription->id,
            'subscription_request_id' => $request->id,
            'status' => 'active',
            'started_at' => now(),
            'expires_at' => $expiresAt,
        ]);

        // إرسال إشعار للمالك
        $request->user->notify(new SubscriptionRequestStatusNotification($request->refresh()));

        if ($httpRequest->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم قبول طلب الاشتراك بنجاح.'
            ]);
        }

        return back()->with('success', 'تم قبول طلب الاشتراك بنجاح.');
    }

    /**
     * رفض طلب اشتراك
     * 
     * @param Request $httpRequest
     * @param SubscriptionRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reject(Request $httpRequest, SubscriptionRequest $request)
    {
        // التحقق من أن الطلب في حالة معلقة
        if ($request->status !== 'pending') {
            if ($httpRequest->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن رفض هذا الطلب.'
                ], 400);
            }
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

        // إرسال إشعار للمالك
        $request->user->notify(new SubscriptionRequestStatusNotification($request->refresh()));

        if ($httpRequest->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم رفض طلب الاشتراك.'
            ]);
        }

        return back()->with('success', 'تم رفض طلب الاشتراك.');
    }
}
