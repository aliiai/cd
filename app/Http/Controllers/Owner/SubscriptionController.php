<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Subscription Controller for Owner
 * 
 * يدير عرض الباقات وطلبات الاشتراك للمالك
 */
class SubscriptionController extends Controller
{
    /**
     * عرض جميع الباقات المتاحة
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // جلب جميع الباقات النشطة
        $subscriptions = Subscription::active()->get();
        
        // جلب طلبات الاشتراك للمستخدم الحالي
        $userRequests = SubscriptionRequest::where('user_id', Auth::id())
            ->with('subscription')
            ->latest()
            ->get();
        
        return view('owner.subscriptions.index', compact('subscriptions', 'userRequests'));
    }

    /**
     * إرسال طلب اشتراك جديد
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $validated = $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // التحقق من أن الباقة موجودة ونشطة
        $subscription = Subscription::active()->findOrFail($validated['subscription_id']);

        // التحقق من عدم وجود طلب معلق سابق
        $existingRequest = SubscriptionRequest::where('user_id', Auth::id())
            ->where('subscription_id', $validated['subscription_id'])
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return back()->with('error', 'لديك طلب معلق بالفعل لهذه الباقة.');
        }

        // رفع صورة الدفع
        $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');

        // إنشاء طلب اشتراك جديد
        SubscriptionRequest::create([
            'user_id' => Auth::id(),
            'subscription_id' => $validated['subscription_id'],
            'payment_proof' => $paymentProofPath,
            'status' => 'pending',
        ]);

        return back()->with('success', 'تم إرسال طلب الاشتراك بنجاح. سيتم مراجعته من قبل الإدارة.');
    }
}

