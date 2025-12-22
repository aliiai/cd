{{-- 
    Component: بطاقة نشاط اليوم
    Usage: @include('owner.dashboard.partials.today-activity', [
        'todayMessages' => $todayMessages,
        'todayDebtors' => $todayDebtors,
        'todayCollected' => $todayCollected
    ])
--}}
@props([
    'todayMessages',
    'todayDebtors',
    'todayCollected'
])

<div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-5 md:p-6 border-r-4 border-primary-400 dark:border-primary-500">
    <div class="flex items-center justify-between mb-3 sm:mb-4 lg:mb-4">
        <h3 class="text-sm sm:text-base lg:text-lg font-semibold text-gray-700 dark:text-gray-300">نشاط اليوم</h3>
        <div class="bg-primary-100 dark:bg-primary-900/30 rounded-lg p-1.5 sm:p-2 lg:p-2">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    </div>
    <div class="space-y-2 sm:space-y-3 lg:space-y-3">
        <div class="flex items-center justify-between">
            <span class="text-xs sm:text-sm lg:text-sm text-gray-600 dark:text-gray-400">رسائل مرسلة</span>
            <span class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 dark:text-gray-100">
                {{ number_format($todayMessages) }}
            </span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-xs sm:text-sm lg:text-sm text-gray-600 dark:text-gray-400">مديونين جدد</span>
            <span class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 dark:text-gray-100">
                {{ number_format($todayDebtors) }}
            </span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-xs sm:text-sm lg:text-sm text-gray-600 dark:text-gray-400">مبالغ محصلة</span>
            <span class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 dark:text-gray-100">
                {{ number_format($todayCollected, 2) }} <span class="text-xs sm:text-sm lg:text-sm text-gray-500 dark:text-gray-400">ر.س</span>
            </span>
        </div>
    </div>
</div>

