@extends('layouts.owner')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-primary-50 to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="w-full mx-auto px-2 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-6 md:py-8">
        {{-- Header --}}
        <div class="mb-4 sm:mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 mb-3 sm:mb-4">
                <div>
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100 mb-1 sm:mb-2">صفحة الدفع</h1>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">المديون: <span class="font-semibold">{{ $debtor->name }}</span> - المبلغ: <span class="font-semibold">{{ number_format($amount, 2) }} ر.س</span></p>
                </div>
                <a href="{{ route('owner.collections.index') }}" 
                   class="inline-flex items-center justify-center px-3 py-2 sm:px-4 sm:py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm sm:text-base font-medium rounded-lg transition-colors w-full sm:w-auto">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    إغلاق
                </a>
            </div>
        </div>

        {{-- Payment Iframe Container --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-xl overflow-hidden">
            <div class="p-4 sm:p-5 md:p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-gray-100">إتمام عملية الدفع</h2>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">يرجى إكمال عملية الدفع في النموذج أدناه</p>
            </div>
            <div class="p-2 sm:p-4 md:p-6">
                <div class="relative" style="min-height: 500px;">
                    <iframe 
                        src="{{ $paymentUrl }}" 
                        class="w-full border-0 rounded-lg"
                        style="min-height: 500px; height: 100%;"
                        frameborder="0"
                        allowtransparency="true"
                        id="paymob-iframe">
                    </iframe>
                </div>
            </div>
        </div>

        {{-- Info Box --}}
        <div class="mt-4 sm:mt-6 bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 dark:border-blue-400 rounded-lg p-3 sm:p-4">
            <div class="flex items-start">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400 ml-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="text-xs sm:text-sm text-blue-800 dark:text-blue-300 font-medium mb-1">ملاحظة:</p>
                    <p class="text-xs sm:text-sm text-blue-700 dark:text-blue-400">
                        بعد إتمام عملية الدفع بنجاح، سيتم تحديث حالة الدين تلقائياً. يمكنك إغلاق هذه الصفحة بعد إتمام الدفع.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Listen for messages from Paymob iframe
    window.addEventListener('message', function(event) {
        // Paymob sends messages when payment is completed
        if (event.data && event.data.type === 'payment_completed') {
            // Redirect to collections page with success message
            window.location.href = '{{ route('owner.collections.index') }}?payment=success';
        }
    });

    // Check if payment was successful (from callback)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('payment') === 'success') {
        alert('تم الدفع بنجاح! سيتم تحديث حالة الدين قريباً.');
        window.location.href = '{{ route('owner.collections.index') }}';
    }
</script>
@endpush
@endsection

