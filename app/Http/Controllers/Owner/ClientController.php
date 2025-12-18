<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Notifications\ClientAddedNotification;
use App\Notifications\ClientStatusChangedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Client Controller for Owner
 * 
 * هذا Controller يدير صفحة Clients في لوحة تحكم Owner
 */
class ClientController extends Controller
{
    /**
     * عرض قائمة المديونين
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // جلب جميع المديونين للمالك الحالي فقط
        $clients = Client::where('owner_id', Auth::id())
            ->latest()
            ->get();
        
        // معلومات الاشتراك والاستهلاك
        $user = Auth::user();
        $activeSubscription = $user->getActiveSubscription();
        $subscriptionInfo = null;
        
        if ($activeSubscription) {
            $subscription = $activeSubscription->subscription;
            $currentDebtorsCount = $clients->count();
            $maxDebtors = $subscription->max_debtors ?? 0;
            $debtorsRemaining = $maxDebtors > 0 ? max(0, $maxDebtors - $currentDebtorsCount) : null;
            $debtorsUsage = $maxDebtors > 0 ? ($currentDebtorsCount / $maxDebtors) * 100 : 0;
            
            $subscriptionInfo = [
                'subscription_name' => $subscription->name,
                'max_debtors' => $maxDebtors,
                'current_debtors' => $currentDebtorsCount,
                'debtors_remaining' => $debtorsRemaining,
                'debtors_usage' => $debtorsUsage,
            ];
        }
        
        return view('owner.clients.index', compact('clients', 'subscriptionInfo'));
    }

    /**
     * حفظ مديون جديد
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'debt_amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'payment_link' => 'nullable|url|max:500',
            'notes' => 'nullable|string',
            'status' => 'required|in:new,contacted,promise_to_pay,paid,overdue,failed',
        ]);

        // التحقق من حدود الاشتراك - عدد المديونين
        $user = Auth::user();
        $activeSubscription = $user->getActiveSubscription();
        
        if (!$activeSubscription) {
            return back()->with('error', 'لا يوجد اشتراك نشط. يرجى الاشتراك في إحدى الباقات أولاً.');
        }

        $subscription = $activeSubscription->subscription;
        $maxDebtors = $subscription->max_debtors ?? 0;
        
        // حساب عدد المديونين الحالي
        $currentDebtorsCount = Client::where('owner_id', Auth::id())->count();
        
        // التحقق من تجاوز الحد
        if ($maxDebtors > 0 && $currentDebtorsCount >= $maxDebtors) {
            return back()->with('error', "لقد وصلت للحد الأقصى المسموح للمديونين! الحد المسموح: {$maxDebtors} مديون، الحالي: {$currentDebtorsCount}. يرجى ترقية اشتراكك لإضافة المزيد من المديونين.");
        }

        // إضافة owner_id تلقائياً
        $validated['owner_id'] = Auth::id();

        // إنشاء المديون
        $client = Client::create($validated);

        // إرسال إشعار إضافة مديون جديد
        try {
            Auth::user()->notify(new ClientAddedNotification($client));
        } catch (\Exception $e) {
            \Log::error('Failed to send notification: ' . $e->getMessage());
        }

        return redirect()->route('owner.clients.index')
            ->with('success', 'تم إضافة المديون بنجاح.');
    }

    /**
     * تحديث بيانات مديون
     * 
     * @param Request $request
     * @param Client $client
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Client $client)
    {
        // التحقق من أن المديون يخص المالك الحالي
        if ($client->owner_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بتعديل هذا المديون.');
        }

        // التحقق من صحة البيانات
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'debt_amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'payment_link' => 'nullable|url|max:500',
            'notes' => 'nullable|string',
            'status' => 'required|in:new,contacted,promise_to_pay,paid,overdue,failed',
        ]);

        // حفظ الحالة القديمة
        $oldStatus = $client->status;

        // تحديث بيانات المديون
        $client->update($validated);

        // إرسال إشعار تغيير الحالة إذا تغيرت
        if ($oldStatus !== $client->status) {
            Auth::user()->notify(new ClientStatusChangedNotification($client, $oldStatus, $client->status));
        }

        return redirect()->route('owner.clients.index')
            ->with('success', 'تم تحديث بيانات المديون بنجاح.');
    }

    /**
     * حذف مديون
     * 
     * @param Request $request
     * @param Client $client
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Client $client)
    {
        // التحقق من أن المديون يخص المالك الحالي
        if ($client->owner_id !== Auth::id()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مصرح لك بحذف هذا المديون.'
                ], 403);
            }
            abort(403, 'غير مصرح لك بحذف هذا المديون.');
        }

        // حذف المديون
        $client->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف المديون بنجاح.'
            ]);
        }

        return redirect()->route('owner.clients.index')
            ->with('success', 'تم حذف المديون بنجاح.');
    }
}

