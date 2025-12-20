{{-- 
    Component: Top Debtors Section
    Usage: @include('owner.analytics.partials.top-debtors-section', [
        'topDebtors' => $topDebtors
    ])
--}}
@props(['topDebtors'])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="bg-gradient-to-r from-primary-500 to-secondary-500 px-6 py-4">
        <h3 class="text-xl font-bold text-white flex items-center">
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
            </svg>
            أعلى المديونين دخلاً
        </h3>
    </div>
    <div class="p-6">
        @if($topDebtors->count() > 0)
            <div class="space-y-3">
                @foreach($topDebtors as $index => $debtor)
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-lg border border-gray-200 dark:border-gray-600 shadow-sm hover:shadow-md transition-all duration-200">
                        <div class="flex items-center">
                            <div class="bg-gradient-to-br from-primary-500 to-secondary-500 text-white rounded-full w-10 h-10 flex items-center justify-center font-bold text-sm ml-3 shadow-md">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $debtor->name }}</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $debtor->phone }}</p>
                            </div>
                        </div>
                        <div class="text-left">
                            <p class="font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($debtor->total_paid, 2) }} <span class="text-xs">ر.س</span></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $debtor->debts_count }} دين</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-12 text-center">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                </div>
                <p class="text-gray-500 dark:text-gray-400 font-medium">لا توجد بيانات</p>
            </div>
        @endif
    </div>
</div>

