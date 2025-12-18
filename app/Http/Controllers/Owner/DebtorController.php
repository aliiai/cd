<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Debtor;
use App\Notifications\DebtorAddedNotification;
use App\Notifications\DebtorStatusChangedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Debtor Controller for Owner
 * 
 * هذا Controller يدير صفحة Debtors في لوحة تحكم Owner
 */
class DebtorController extends Controller
{
    /**
     * عرض قائمة المديونين
     * 
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // بناء الاستعلام
        $query = Debtor::where('owner_id', Auth::id());
        
        // البحث بالاسم أو البريد الإلكتروني أو رقم الهاتف
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // فلترة حسب الحالة
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // ترتيب النتائج
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if (in_array($sortBy, ['name', 'debt_amount', 'due_date', 'status', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }
        
        // Pagination
        $debtors = $query->paginate(10)->appends($request->query());
        
        // معلومات الاشتراك والاستهلاك (حساب العدد الكلي)
        $user = Auth::user();
        $activeSubscription = $user->getActiveSubscription();
        $subscriptionInfo = null;
        
        if ($activeSubscription) {
            $subscription = $activeSubscription->subscription;
            $currentDebtorsCount = Debtor::where('owner_id', Auth::id())->count();
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
        
        // إذا كان الطلب AJAX، إرجاع JSON
        if ($request->ajax()) {
            return response()->json([
                'html' => view('owner.debtors.partials.debtors-table', compact('debtors'))->render(),
                'pagination' => $debtors->links()->render(),
            ]);
        }
        
        return view('owner.debtors.index', compact('debtors', 'subscriptionInfo'));
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
        $currentDebtorsCount = Debtor::where('owner_id', Auth::id())->count();
        
        // التحقق من تجاوز الحد
        if ($maxDebtors > 0 && $currentDebtorsCount >= $maxDebtors) {
            return back()->with('error', "لقد وصلت للحد الأقصى المسموح للمديونين! الحد المسموح: {$maxDebtors} مديون، الحالي: {$currentDebtorsCount}. يرجى ترقية اشتراكك لإضافة المزيد من المديونين.");
        }

        // إضافة owner_id تلقائياً
        $validated['owner_id'] = Auth::id();

        // إنشاء المديون
        $debtor = Debtor::create($validated);

        // إرسال إشعار إضافة مديون جديد
        try {
            Auth::user()->notify(new DebtorAddedNotification($debtor));
        } catch (\Exception $e) {
            \Log::error('Failed to send notification: ' . $e->getMessage());
        }

        return redirect()->route('owner.debtors.index')
            ->with('success', 'تم إضافة المديون بنجاح.');
    }

    /**
     * تحديث بيانات مديون
     * 
     * @param Request $request
     * @param Debtor $debtor
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Debtor $debtor)
    {
        // التحقق من أن المديون يخص المالك الحالي
        if ($debtor->owner_id !== Auth::id()) {
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
        $oldStatus = $debtor->status;

        // تحديث بيانات المديون
        $debtor->update($validated);

        // إرسال إشعار تغيير الحالة إذا تغيرت
        if ($oldStatus !== $debtor->status) {
            Auth::user()->notify(new DebtorStatusChangedNotification($debtor, $oldStatus, $debtor->status));
        }

        return redirect()->route('owner.debtors.index')
            ->with('success', 'تم تحديث بيانات المديون بنجاح.');
    }

    /**
     * حذف مديون
     * 
     * @param Request $request
     * @param Debtor $debtor
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Debtor $debtor)
    {
        // التحقق من أن المديون يخص المالك الحالي
        if ($debtor->owner_id !== Auth::id()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مصرح لك بحذف هذا المديون.'
                ], 403);
            }
            abort(403, 'غير مصرح لك بحذف هذا المديون.');
        }

        // حذف المديون
        $debtor->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف المديون بنجاح.'
            ]);
        }

        return redirect()->route('owner.debtors.index')
            ->with('success', 'تم حذف المديون بنجاح.');
    }
}

