{{-- 
    Component: البطاقات الإحصائية الثانوية
    Usage: @include('owner.dashboard.partials.secondary-stats', [
        'totalDebtAmount' => $totalDebtAmount,
        'totalCollected' => $totalCollected,
        'aiUsageCount' => $aiUsageCount,
        'aiUsagePercentage' => $aiUsagePercentage,
        'maxMessages' => $maxMessages,
        'todayMessages' => $todayMessages,
        'todayDebtors' => $todayDebtors,
        'todayCollected' => $todayCollected
    ])
--}}
@props([
    'totalDebtAmount',
    'totalCollected',
    'aiUsageCount',
    'aiUsagePercentage',
    'maxMessages',
    'todayMessages',
    'todayDebtors',
    'todayCollected',
    'autoMessagesCount' => 0,
    'autoMessagesToday' => 0,
    'autoMessagesPercentage' => 0
])

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6 mb-4 sm:mb-6 md:mb-8">
    
    {{-- بطاقة نسبة التحصيل --}}
    @include('owner.dashboard.partials.stat-card-secondary', [
        'title' => 'نسبة التحصيل',
        'value' => ($totalDebtAmount > 0 ? number_format(($totalCollected / $totalDebtAmount) * 100, 1) : 0) . ' <span class="text-lg text-gray-500 dark:text-gray-400">%</span>',
        'borderColor' => 'border-emerald-500 dark:border-emerald-400',
        'icon' => '<svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>',
        'footer' => '<div class="text-sm text-gray-600 dark:text-gray-400"><span>' . number_format($totalCollected, 2) . ' ر.س من ' . number_format($totalDebtAmount, 2) . ' ر.س</span></div>',
        'progress' => $totalDebtAmount > 0 ? min(($totalCollected / $totalDebtAmount) * 100, 100) : 0
    ])

    {{-- بطاقة استخدام الذكاء الاصطناعي --}}
    @include('owner.dashboard.partials.stat-card-secondary', [
        'title' => 'استخدام الذكاء الاصطناعي',
        'value' => number_format($aiUsageCount),
        'subtitle' => 'عدد مرات توليد الرسائل',
        'borderColor' => 'border-secondary-500 dark:border-secondary-400',
        'icon' => '<svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-secondary-600 dark:text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>',
        'progress' => min($aiUsagePercentage, 100),
        'footer' => $maxMessages > 0 ? '<p class="text-xs text-gray-500 dark:text-gray-400 mt-2">' . number_format($aiUsagePercentage, 1) . '% من الحد الأقصى (' . number_format($maxMessages) . ')</p>' : null
    ])

    {{-- بطاقة نشاط اليوم --}}
    @include('owner.dashboard.partials.today-activity', [
        'todayMessages' => $todayMessages,
        'todayDebtors' => $todayDebtors,
        'todayCollected' => $todayCollected
    ])

    {{-- بطاقة الإرسال التلقائي --}}
    @include('owner.dashboard.partials.stat-card-secondary', [
        'title' => 'الإرسال التلقائي',
        'value' => number_format($autoMessagesCount),
        'subtitle' => 'رسالة تلقائية مرسلة',
        'borderColor' => 'border-purple-500 dark:border-purple-400',
        'icon' => '<svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>',
        'progress' => min($autoMessagesPercentage, 100),
        'footer' => '<div class="text-sm text-gray-600 dark:text-gray-400"><span>' . number_format($autoMessagesToday) . ' رسالة اليوم</span></div><p class="text-xs text-gray-500 dark:text-gray-400 mt-1">' . number_format($autoMessagesPercentage, 1) . '% من إجمالي الرسائل</p>'
    ])

</div>

