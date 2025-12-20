{{-- 
    Component: Period Comparison Section
    Usage: @include('owner.analytics.partials.period-comparison-section', [
        'periodComparison' => $periodComparison
    ])
--}}
@props(['periodComparison'])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="bg-gradient-to-r from-primary-500 to-secondary-500 px-6 py-4">
        <h3 class="text-xl font-bold text-white flex items-center">
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            مقارنة الفترات
        </h3>
    </div>
    <div class="p-6">
        <div class="space-y-4">
            {{-- This Month vs Last Month --}}
            <div class="bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-800/20 rounded-xl p-4 border border-primary-200 dark:border-primary-800 shadow-md hover:shadow-lg transition-all duration-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-primary-700 dark:text-primary-300">هذا الشهر</span>
                    <span class="text-lg font-bold text-primary-900 dark:text-primary-100">{{ number_format($periodComparison['this_month'], 2) }} <span class="text-sm">ر.س</span></span>
                </div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-primary-600 dark:text-primary-400">الشهر السابق</span>
                    <span class="text-sm font-semibold text-primary-800 dark:text-primary-200">{{ number_format($periodComparison['last_month'], 2) }} <span class="text-xs">ر.س</span></span>
                </div>
                <div class="mt-2 flex items-center">
                    @if($periodComparison['month_change'] > 0)
                        <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400 flex items-center">
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            +{{ number_format($periodComparison['month_change'], 1) }}%
                        </span>
                    @elseif($periodComparison['month_change'] < 0)
                        <span class="text-xs font-medium text-red-600 dark:text-red-400 flex items-center">
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6 6"></path>
                            </svg>
                            {{ number_format($periodComparison['month_change'], 1) }}%
                        </span>
                    @else
                        <span class="text-xs font-medium text-gray-600 dark:text-gray-400">لا تغيير</span>
                    @endif
                </div>
            </div>

            {{-- This Week vs Last Week --}}
            <div class="bg-gradient-to-br from-secondary-50 to-secondary-100 dark:from-secondary-900/20 dark:to-secondary-800/20 rounded-xl p-4 border border-secondary-200 dark:border-secondary-800 shadow-md hover:shadow-lg transition-all duration-200">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-secondary-700 dark:text-secondary-300">هذا الأسبوع</span>
                    <span class="text-lg font-bold text-secondary-900 dark:text-secondary-100">{{ number_format($periodComparison['this_week'], 2) }} <span class="text-sm">ر.س</span></span>
                </div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-secondary-600 dark:text-secondary-400">الأسبوع السابق</span>
                    <span class="text-sm font-semibold text-secondary-800 dark:text-secondary-200">{{ number_format($periodComparison['last_week'], 2) }} <span class="text-xs">ر.س</span></span>
                </div>
                <div class="mt-2 flex items-center">
                    @if($periodComparison['week_change'] > 0)
                        <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400 flex items-center">
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            +{{ number_format($periodComparison['week_change'], 1) }}%
                        </span>
                    @elseif($periodComparison['week_change'] < 0)
                        <span class="text-xs font-medium text-red-600 dark:text-red-400 flex items-center">
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6 6"></path>
                            </svg>
                            {{ number_format($periodComparison['week_change'], 1) }}%
                        </span>
                    @else
                        <span class="text-xs font-medium text-gray-600 dark:text-gray-400">لا تغيير</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

