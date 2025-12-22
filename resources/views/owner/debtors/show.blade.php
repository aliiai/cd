@extends('layouts.owner')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-primary-50 to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- ========== Header Section ========== --}}
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <a href="{{ route('owner.debtors.index') }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100">{{ __('owner.debtor_details') }}</h1>
                    </div>
                    <p class="text-lg text-gray-600 dark:text-gray-400">{{ $debtor->name }}</p>
                </div>
            </div>
        </div>

        {{-- ========== Debtor Info Card ========== --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">معلومات المديون</h2>
                <div class="flex items-center gap-3">
                    <a href="{{ route('owner.debtors.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        العودة للقائمة
                    </a>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">{{ __('owner.full_name') }}</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $debtor->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">{{ __('owner.phone_number_label') }}</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        <a href="tel:{{ $debtor->phone }}" class="text-primary-600 dark:text-primary-400 hover:underline">
                            {{ $debtor->phone }}
                        </a>
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">{{ __('owner.email_address_label') }}</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        @if($debtor->email)
                            <a href="mailto:{{ $debtor->email }}" class="text-primary-600 dark:text-primary-400 hover:underline">
                                {{ $debtor->email }}
                            </a>
                        @else
                            <span class="text-gray-400 dark:text-gray-500">غير محدد</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">{{ __('owner.debt_status_label') }}</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $debtor->status_color }}">
                        {{ $debtor->status_text }}
                    </span>
                </div>
            </div>
            
            @if($debtor->payment_link)
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ __('owner.payment_link') }}</p>
                    <a href="{{ $debtor->payment_link }}" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-lg hover:bg-green-200 dark:hover:bg-green-900/50 transition-colors duration-200">
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        فتح رابط الدفع
                    </a>
                </div>
            @endif
        </div>

        {{-- ========== Installments Section ========== --}}
        @if($debtor->has_installments)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">جدول الدفعات</h3>
                    <div class="flex items-center gap-6">
                        <div class="text-right">
                            <p class="text-sm text-gray-600 dark:text-gray-400">المدفوع</p>
                            <p class="text-lg font-semibold text-green-600 dark:text-green-400">{{ number_format($debtor->paid_amount, 2) }} ر.س</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600 dark:text-gray-400">المتبقي</p>
                            <p class="text-lg font-semibold text-red-600 dark:text-red-400">{{ number_format($debtor->remaining_amount, 2) }} ر.س</p>
                        </div>
                        <div class="w-32">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">نسبة السداد</p>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                                <div class="bg-gradient-to-r from-green-500 to-green-600 h-3 rounded-full transition-all duration-300" 
                                     style="width: {{ $debtor->payment_progress }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ number_format($debtor->payment_progress, 1) }}%</p>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap">رقم الدفعة</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap">المبلغ</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap">تاريخ الاستحقاق</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap">المبلغ المدفوع</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap">الحالة</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($debtor->installments as $installment)
                                <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-100">#{{ $installment->installment_number }}</td>
                                    <td class="px-4 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ number_format($installment->amount, 2) }} ر.س</td>
                                    <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $installment->due_date->format('Y-m-d') }}</td>
                                    <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300">{{ number_format($installment->paid_amount, 2) }} ر.س</td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $installment->status_color }}">
                                            {{ $installment->status_text }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($installment->status !== 'paid' && $installment->status !== 'cancelled')
                                            <button onclick="openPaymentModal({{ $installment->id }}, {{ $installment->amount }}, {{ $installment->paid_amount }})" 
                                                    class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 text-sm font-medium">
                                                تسجيل دفعة
                                            </button>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500 text-sm">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">لا توجد دفعات</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            {{-- ========== Regular Debt Info ========== --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">معلومات الدين</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">مبلغ الدين</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($debtor->debt_amount, 2) }} ر.س</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">تاريخ الاستحقاق</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $debtor->due_date->format('Y-m-d') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">المبلغ المتبقي</p>
                        <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($debtor->remaining_amount, 2) }} ر.س</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- ========== Additional Info Section ========== --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Notes Section --}}
            @if($debtor->notes)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                        <svg class="w-5 h-5 ml-2 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        ملاحظات
                    </h3>
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $debtor->notes }}</p>
                </div>
            @endif
            
            {{-- Debt Summary --}}
            <div class="bg-gradient-to-br from-primary-50 to-secondary-50 dark:from-primary-900/20 dark:to-secondary-900/20 rounded-xl shadow-lg p-6 border border-primary-200 dark:border-primary-800">
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                    <svg class="w-5 h-5 ml-2 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    ملخص الدين
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">مبلغ الدين الإجمالي</span>
                        <span class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ number_format($debtor->debt_amount, 2) }} ر.س</span>
                    </div>
                    @if($debtor->has_installments)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">عدد الدفعات</span>
                            <span class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $debtor->total_installments }} دفعة</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">الدفعات المدفوعة</span>
                            <span class="text-lg font-semibold text-green-600 dark:text-green-400">{{ $debtor->paid_installments_count }} / {{ $debtor->total_installments }}</span>
                        </div>
                        @if($debtor->overdue_installments_count > 0)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">الدفعات المتأخرة</span>
                                <span class="text-lg font-semibold text-red-600 dark:text-red-400">{{ $debtor->overdue_installments_count }}</span>
                            </div>
                        @endif
                    @else
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">تاريخ الاستحقاق</span>
                            <span class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $debtor->due_date->format('Y-m-d') }}</span>
                        </div>
                    @endif
                    <div class="pt-4 border-t border-primary-200 dark:border-primary-700">
                        <div class="flex items-center justify-between">
                            <span class="text-base font-semibold text-gray-900 dark:text-gray-100">المبلغ المتبقي</span>
                            <span class="text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($debtor->remaining_amount, 2) }} ر.س</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 dark:bg-opacity-75 hidden overflow-y-auto h-full w-full z-50 backdrop-blur-sm" style="display: none; align-items: flex-start; justify-content: center;">
    <div class="relative top-10 mx-auto p-0 border-0 w-full max-w-md shadow-2xl rounded-xl bg-white dark:bg-gray-800 m-4 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">تسجيل دفعة</h3>
                <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="paymentForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="installment_id" name="installment_id">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            المبلغ المستحق <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="installment_amount_display" readonly 
                               class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    </div>

                    <div>
                        <label for="payment_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            المبلغ المدفوع <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="payment_amount" 
                               name="amount" 
                               step="0.01"
                               min="0.01"
                               required
                               class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    </div>

                    <div>
                        <label for="paid_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            تاريخ الدفع
                        </label>
                        <input type="date" 
                               id="paid_date" 
                               name="paid_date"
                               class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    </div>

                    <div>
                        <label for="payment_proof" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            إثبات الدفع (صورة)
                        </label>
                        <input type="file" 
                               id="payment_proof" 
                               name="payment_proof"
                               accept="image/*"
                               class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    </div>

                    <div>
                        <label for="payment_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            ملاحظات
                        </label>
                        <textarea id="payment_notes" 
                                  name="notes" 
                                  rows="3"
                                  class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 space-x-reverse mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" 
                            onclick="closePaymentModal()"
                            class="px-6 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200">
                        إلغاء
                    </button>
                    <button type="submit" 
                            class="px-6 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 transition-all duration-200">
                        تسجيل
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openPaymentModal(installmentId, installmentAmount, paidAmount) {
        const modal = document.getElementById('paymentModal');
        const form = document.getElementById('paymentForm');
        const remaining = installmentAmount - paidAmount;
        
        document.getElementById('installment_id').value = installmentId;
        document.getElementById('installment_amount_display').value = remaining.toFixed(2) + ' ر.س';
        document.getElementById('payment_amount').max = remaining;
        document.getElementById('payment_amount').value = remaining.toFixed(2);
        document.getElementById('paid_date').value = new Date().toISOString().split('T')[0];
        
        form.action = '{{ route("owner.debtors.installments.payment", [":debtor", ":installment"]) }}'
            .replace(':debtor', {{ $debtor->id }})
            .replace(':installment', installmentId);
        
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closePaymentModal() {
        const modal = document.getElementById('paymentModal');
        const form = document.getElementById('paymentForm');
        
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        if (form) {
            form.reset();
        }
    }

    document.getElementById('paymentModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closePaymentModal();
        }
    });

    // Handle form submission
    document.getElementById('paymentForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const installmentId = document.getElementById('installment_id').value;
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'نجح!',
                    text: data.message || 'تم تسجيل الدفعة بنجاح.',
                    confirmButtonText: 'حسناً',
                    confirmButtonColor: '#10b981'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ!',
                    text: data.message || 'حدث خطأ أثناء تسجيل الدفعة.',
                    confirmButtonText: 'حسناً',
                    confirmButtonColor: '#ef4444'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'خطأ!',
                text: 'حدث خطأ أثناء تسجيل الدفعة.',
                confirmButtonText: 'حسناً',
                confirmButtonColor: '#ef4444'
            });
        });
    });
</script>
@endsection

