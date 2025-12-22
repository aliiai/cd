{{-- 
    Component: البطاقات الإحصائية الرئيسية
    Usage: @include('owner.dashboard.partials.primary-stats', [
        'totalDebtors' => $totalDebtors,
        'debtorsChange' => $debtorsChange,
        'totalDebtAmount' => $totalDebtAmount,
        'debtAmountChange' => $debtAmountChange,
        'totalMessages' => $totalMessages,
        'smsCount' => $smsCount,
        'emailCount' => $emailCount,
        'totalCollected' => $totalCollected,
        'collectedChange' => $collectedChange
    ])
--}}
@props([
    'totalDebtors',
    'debtorsChange',
    'totalDebtAmount',
    'debtAmountChange',
    'totalMessages',
    'smsCount',
    'emailCount',
    'totalCollected',
    'collectedChange'
])

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6 mb-4 sm:mb-6 md:mb-8">
    
    {{-- بطاقة إجمالي المديونين --}}
    @include('owner.dashboard.partials.stat-card-primary', [
        'value' => $totalDebtors,
        'subtitle' => 'مديون',
        'gradientFrom' => 'from-primary-500',
        'gradientTo' => 'to-primary-600',
        'badge' => 'إجمالي',
        'icon' => '<svg class="w-5 h-5 sm:w-6 sm:h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>',
        'footer' => $debtorsChange != 0 ? '<div class="flex items-center text-sm"><span class="' . ($debtorsChange > 0 ? 'text-green-300' : 'text-red-300') . '">' . ($debtorsChange > 0 ? '↑' : '↓') . ' ' . number_format(abs($debtorsChange), 1) . '%</span><span class="opacity-80 mr-2">من الشهر السابق</span></div>' : null
    ])

    {{-- بطاقة إجمالي قيمة الديون --}}
    @include('owner.dashboard.partials.stat-card-primary', [
        'value' => number_format($totalDebtAmount, 2),
        'subtitle' => 'ر.س إجمالي قيمة الديون',
        'gradientFrom' => 'from-secondary-500',
        'gradientTo' => 'to-secondary-600',
        'badge' => 'إجمالي',
        'icon' => '<svg class="w-5 h-5 sm:w-6 sm:h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        'footer' => $debtAmountChange != 0 ? '<div class="flex items-center text-sm"><span class="' . ($debtAmountChange > 0 ? 'text-green-300' : 'text-red-300') . '">' . ($debtAmountChange > 0 ? '↑' : '↓') . ' ' . number_format(abs($debtAmountChange), 1) . '%</span><span class="opacity-80 mr-2">من الشهر السابق</span></div>' : null
    ])

    {{-- بطاقة إجمالي الرسائل المرسلة --}}
    @include('owner.dashboard.partials.stat-card-primary', [
        'value' => $totalMessages,
        'subtitle' => 'رسالة مرسلة',
        'gradientFrom' => 'from-primary-400',
        'gradientTo' => 'to-primary-500',
        'badge' => 'إجمالي',
        'icon' => '<svg class="w-5 h-5 sm:w-6 sm:h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>',
        'footer' => '<div class="flex items-center gap-4 text-sm"><div class="flex items-center"><div class="w-2 h-2 bg-white/80 rounded-full ml-2"></div><span>SMS: ' . number_format($smsCount) . '</span></div><div class="flex items-center"><div class="w-2 h-2 bg-secondary-300 rounded-full ml-2"></div><span>Email: ' . number_format($emailCount) . '</span></div></div>'
    ])

    {{-- بطاقة إجمالي المبالغ المحصلة --}}
    @include('owner.dashboard.partials.stat-card-primary', [
        'value' => number_format($totalCollected, 2),
        'subtitle' => 'ر.س محصلة',
        'gradientFrom' => 'from-secondary-400',
        'gradientTo' => 'to-secondary-500',
        'badge' => 'إجمالي',
        'icon' => '<svg class="w-5 h-5 sm:w-6 sm:h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        'footer' => $collectedChange != 0 ? '<div class="flex items-center text-sm"><span class="' . ($collectedChange > 0 ? 'text-green-300' : 'text-red-300') . '">' . ($collectedChange > 0 ? '↑' : '↓') . ' ' . number_format(abs($collectedChange), 1) . '%</span><span class="opacity-80 mr-2">من الشهر السابق</span></div>' : null
    ])

</div>

