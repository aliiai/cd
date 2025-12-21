<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Debtor;
use App\Models\DebtInstallment;
use App\Notifications\DebtorAddedNotification;
use App\Notifications\DebtorStatusChangedNotification;
use App\Services\InstallmentService;
use Carbon\Carbon;
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
        $query = Debtor::where('owner_id', Auth::id())
            ->with(['installments' => function($q) {
                $q->where('status', '!=', 'paid')
                  ->where('status', '!=', 'cancelled')
                  ->orderBy('due_date', 'asc');
            }]);
        
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
     * عرض تفاصيل مديون
     * 
     * @param Debtor $debtor
     * @return \Illuminate\View\View
     */
    public function show(Debtor $debtor)
    {
        // التحقق من أن المديون يخص المالك الحالي
        if ($debtor->owner_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بعرض هذا المديون.');
        }

        // تحميل الدفعات إذا كان لديه دفعات
        if ($debtor->has_installments) {
            $debtor->load('installments');
        }

        return view('owner.debtors.show', compact('debtor'));
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
            'email' => 'required|email|max:255',
            'debt_amount' => 'required|numeric|min:0',
            'payment_type' => 'required|in:single,installments',
            'due_date' => 'required_if:payment_type,single|date|nullable',
            'number_of_installments' => 'required_if:payment_type,installments|integer|min:2|max:24|nullable',
            'installment_frequency' => 'required_if:payment_type,installments|in:monthly,every_3_months,every_6_months,yearly|nullable',
            'first_installment_date' => 'required_if:payment_type,installments|date|nullable',
            'payment_link' => 'nullable|url|max:500',
            'notes' => 'nullable|string',
            'status' => 'required|in:new,contacted,promise_to_pay,failed',
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

        // تحديد نوع الدفع
        $paymentType = $validated['payment_type'];
        $hasInstallments = $paymentType === 'installments';
        
        // إذا كان دفعة واحدة، نستخدم due_date
        // إذا كان دفعات، نحتاج first_installment_date
        if ($hasInstallments) {
            // للدفعات، نستخدم first_installment_date كـ due_date مؤقت
            $validated['due_date'] = $validated['first_installment_date'];
        }
        
        // التحقق من تاريخ الاستحقاق وتحديث الحالة تلقائياً إلى overdue إذا لزم الأمر
        $dueDate = $hasInstallments 
            ? Carbon::parse($validated['first_installment_date'])
            : Carbon::parse($validated['due_date']);
        
        if ($dueDate < now() && $validated['status'] !== 'failed') {
            // إذا تجاوز تاريخ الاستحقاق، نترك الحالة كما اختارها المستخدم
            // يمكن تغييرها لاحقاً تلقائياً إذا لزم الأمر
        }

        // إنشاء المديون
        $debtorData = $validated;
        unset($debtorData['payment_type'], $debtorData['number_of_installments'], $debtorData['installment_frequency'], $debtorData['first_installment_date']);
        
        $debtor = Debtor::create($debtorData);

        // إنشاء الدفعات إذا كان مطلوباً
        if ($hasInstallments) {
            $installmentService = new InstallmentService();
            $startDate = Carbon::parse($validated['first_installment_date']);
            
            $installmentService->createInstallments(
                debtor: $debtor,
                totalAmount: $validated['debt_amount'],
                numberOfInstallments: $validated['number_of_installments'],
                frequency: $validated['installment_frequency'],
                startDate: $startDate
            );
        } else {
            // الدين العادي - تعيين المبلغ المتبقي
            $debtor->update([
                'remaining_amount' => $validated['debt_amount'],
                'paid_amount' => 0,
            ]);
        }

        // إرسال إشعار إضافة مديون جديد
        try {
            Auth::user()->notify(new DebtorAddedNotification($debtor));
        } catch (\Exception $e) {
            \Log::error('Failed to send notification: ' . $e->getMessage());
        }

        $message = $hasInstallments 
            ? 'تم إضافة المديون بنجاح مع إنشاء ' . $request->number_of_installments . ' دفعة.'
            : 'تم إضافة المديون بنجاح.';

        return redirect()->route('owner.debtors.index')
            ->with('success', $message);
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

    /**
     * تسجيل دفعة
     * 
     * @param Request $request
     * @param Debtor $debtor
     * @param DebtInstallment $installment
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function recordPayment(Request $request, Debtor $debtor, DebtInstallment $installment)
    {
        // التحقق من أن المديون يخص المالك الحالي
        if ($debtor->owner_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بتسجيل دفعة لهذا المديون.');
        }

        // التحقق من أن الدفعة تخص المديون
        if ($installment->debtor_id !== $debtor->id) {
            abort(403, 'الدفعة لا تخص هذا المديون.');
        }

        // التحقق من صحة البيانات
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'notes' => 'nullable|string|max:1000',
            'paid_date' => 'nullable|date',
        ]);

        // رفع ملف إثبات الدفع إن وجد
        $paymentProofPath = null;
        if ($request->hasFile('payment_proof')) {
            $paymentProofPath = $request->file('payment_proof')->store('payment-proofs', 'public');
        }

        // تسجيل الدفعة
        $installmentService = new InstallmentService();
        $paidDate = $request->filled('paid_date') ? Carbon::parse($request->paid_date) : null;
        
        $installmentService->recordPayment(
            installment: $installment,
            amount: $validated['amount'],
            paymentProof: $paymentProofPath,
            notes: $validated['notes'] ?? null,
            paidDate: $paidDate
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الدفعة بنجاح.',
                'installment' => $installment->fresh(),
                'debtor' => $debtor->fresh(),
            ]);
        }

        return redirect()->route('owner.debtors.show', $debtor)
            ->with('success', 'تم تسجيل الدفعة بنجاح.');
    }

    /**
     * تأجيل دفعة
     * 
     * @param Request $request
     * @param Debtor $debtor
     * @param DebtInstallment $installment
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function postponeInstallment(Request $request, Debtor $debtor, DebtInstallment $installment)
    {
        // التحقق من أن المديون يخص المالك الحالي
        if ($debtor->owner_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بتأجيل دفعة لهذا المديون.');
        }

        // التحقق من أن الدفعة تخص المديون
        if ($installment->debtor_id !== $debtor->id) {
            abort(403, 'الدفعة لا تخص هذا المديون.');
        }

        // التحقق من صحة البيانات
        $validated = $request->validate([
            'new_due_date' => 'required|date|after_or_equal:today',
            'update_next' => 'nullable|boolean',
        ]);

        // تأجيل الدفعة
        $installmentService = new InstallmentService();
        $installmentService->postponeInstallment(
            installment: $installment,
            newDueDate: Carbon::parse($validated['new_due_date']),
            updateNext: $validated['update_next'] ?? true
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تأجيل الدفعة بنجاح.',
                'installment' => $installment->fresh(),
            ]);
        }

        return redirect()->route('owner.debtors.show', $debtor)
            ->with('success', 'تم تأجيل الدفعة بنجاح.');
    }

    /**
     * إلغاء دفعة
     * 
     * @param Request $request
     * @param Debtor $debtor
     * @param DebtInstallment $installment
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function cancelInstallment(Request $request, Debtor $debtor, DebtInstallment $installment)
    {
        // التحقق من أن المديون يخص المالك الحالي
        if ($debtor->owner_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بإلغاء دفعة لهذا المديون.');
        }

        // التحقق من أن الدفعة تخص المديون
        if ($installment->debtor_id !== $debtor->id) {
            abort(403, 'الدفعة لا تخص هذا المديون.');
        }

        // التحقق من صحة البيانات
        $validated = $request->validate([
            'redistribute' => 'nullable|boolean',
        ]);

        // إلغاء الدفعة
        $installmentService = new InstallmentService();
        $installmentService->cancelInstallment(
            installment: $installment,
            redistribute: $validated['redistribute'] ?? true
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم إلغاء الدفعة بنجاح.',
                'debtor' => $debtor->fresh(),
            ]);
        }

        return redirect()->route('owner.debtors.show', $debtor)
            ->with('success', 'تم إلغاء الدفعة بنجاح.');
    }
}

