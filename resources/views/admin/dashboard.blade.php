@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-primary-50 to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- ========== Header Section ========== --}}
        <div class="mb-4 sm:mb-6 md:mb-8">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-1 sm:mb-2">{{ __('common.admin_dashboard_title') }}</h1>
            <p class="text-sm sm:text-base lg:text-lg text-gray-600 dark:text-gray-400">{{ __('common.admin_dashboard_description') }}</p>
        </div>

        {{-- ========== 1. البطاقات الإحصائية الكبيرة ========== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-6 mb-6 sm:mb-8 lg:mb-8">
            
            {{-- بطاقة مقدمي الخدمة --}}
            @include('admin.dashboard.partials.stat-card-primary', [
                'value' => $totalOwners,
                'subtitle' => __('common.service_provider'),
                'gradientFrom' => 'from-primary-500',
                'gradientTo' => 'to-primary-600',
                'badge' => __('common.total'),
                'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>',
                'footer' => '<div class="flex items-center gap-4 text-sm"><div class="flex items-center"><div class="w-2 h-2 bg-green-300 rounded-full ml-2"></div><span>' . __('common.active') . ': ' . $activeOwners . '</span></div><div class="flex items-center"><div class="w-2 h-2 bg-red-300 rounded-full ml-2"></div><span>' . __('common.inactive') . ': ' . $inactiveOwners . '</span></div></div>'
            ])

            {{-- بطاقة المديونين --}}
            @include('admin.dashboard.partials.stat-card-primary', [
                'value' => $totalDebtors,
                'subtitle' => __('common.debtor'),
                'gradientFrom' => 'from-secondary-500',
                'gradientTo' => 'to-secondary-600',
                'badge' => __('common.total'),
                'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
                'footer' => '<div class="text-sm"><span class="font-semibold">' . number_format($totalDebtAmount, 2) . ' ر.س</span><span class="opacity-80"> ' . __('common.total_debt_value') . '</span></div>'
            ])

            {{-- بطاقة الرسائل --}}
            @include('admin.dashboard.partials.stat-card-primary', [
                'value' => $totalMessages,
                'subtitle' => __('common.sent_message'),
                'gradientFrom' => 'from-primary-400',
                'gradientTo' => 'to-primary-500',
                'badge' => __('common.total'),
                'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>',
                'footer' => '<div class="flex items-center gap-4 text-sm"><div class="flex items-center"><div class="w-2 h-2 bg-white/80 rounded-full ml-2"></div><span>SMS: ' . number_format($smsCount) . '</span></div><div class="flex items-center"><div class="w-2 h-2 bg-secondary-300 rounded-full ml-2"></div><span>Email: ' . number_format($emailCount) . '</span></div></div>'
            ])

            {{-- بطاقة الاشتراكات --}}
            @include('admin.dashboard.partials.stat-card-primary', [
                'value' => $activeSubscriptions,
                'subtitle' => __('common.active_subscription'),
                'gradientFrom' => 'from-secondary-400',
                'gradientTo' => 'to-secondary-500',
                'badge' => __('common.active'),
                'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>',
                'footer' => '<div class="text-sm"><span class="font-semibold">' . number_format($totalSubscriptions) . '</span><span class="opacity-80"> ' . __('common.total_subscriptions') . '</span></div>'
            ])

        </div>

        {{-- ========== 2. البطاقات الإحصائية الثانوية ========== --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 lg:gap-6 mb-6 sm:mb-8 lg:mb-8">
            
            {{-- بطاقة المبالغ المحصلة --}}
            @include('admin.dashboard.partials.stat-card-secondary', [
                'title' => __('common.collected_amounts'),
                'value' => number_format($totalCollected, 2) . ' <span class="text-lg text-gray-500 dark:text-gray-400">ر.س</span>',
                'borderColor' => 'border-primary-500 dark:border-primary-400',
                'icon' => '<svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                'footer' => '<div class="flex items-center text-sm text-gray-600 dark:text-gray-400"><span>' . __('common.collection_rate') . ':</span><span class="font-bold text-primary-600 dark:text-primary-400 mr-2">' . number_format($collectionRate, 1) . '%</span></div>'
            ])

            {{-- بطاقة استخدام AI --}}
            @include('admin.dashboard.partials.stat-card-secondary', [
                'title' => __('common.ai_usage'),
                'value' => number_format($aiUsageCount),
                'subtitle' => __('common.campaign_used_ai'),
                'borderColor' => 'border-secondary-500 dark:border-secondary-400',
                'icon' => '<svg class="w-6 h-6 text-secondary-600 dark:text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>',
                'progress' => min($aiUsagePercentage, 100),
                'footer' => '<p class="text-xs text-gray-500 dark:text-gray-400 mt-2">' . number_format($aiUsagePercentage, 1) . '% ' . __('common.of_total_campaigns') . '</p>'
            ])

            {{-- بطاقة نشاط اليوم --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-5 md:p-6 border-r-4 border-primary-400 dark:border-primary-500">
                <div class="flex items-center justify-between mb-3 sm:mb-4 lg:mb-4">
                    <h3 class="text-sm sm:text-base lg:text-lg font-semibold text-gray-700 dark:text-gray-300">{{ __('common.today_activity') }}</h3>
                    <div class="bg-primary-100 dark:bg-primary-900/30 rounded-lg p-1.5 sm:p-2 lg:p-2">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="space-y-2 sm:space-y-3 lg:space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs sm:text-sm lg:text-sm text-gray-600 dark:text-gray-400">{{ __('common.messages_sent') }}</span>
                        <span class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($todayMessages) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs sm:text-sm lg:text-sm text-gray-600 dark:text-gray-400">{{ __('common.new_debtors') }}</span>
                        <span class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($todayDebtors) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs sm:text-sm lg:text-sm text-gray-600 dark:text-gray-400">{{ __('common.joined_users') }}</span>
                        <span class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($todayUsers) }}</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- ========== 3. الرسوم البيانية والجداول ========== --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-6 mb-6 sm:mb-8 lg:mb-8">
            
            {{-- الرسوم البيانية --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-6">
                @include('admin.dashboard.partials.status-chart', ['statusDistribution' => $statusDistribution])
                @include('admin.dashboard.partials.messages-chart')
            </div>

            {{-- أحدث الأنشطة --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-6">
                @include('admin.dashboard.partials.recent-owners', ['recentOwners' => $recentOwners])
                @include('admin.dashboard.partials.recent-campaigns', ['recentCampaigns' => $recentCampaigns])
            </div>

        </div>

    </div>
</div>

{{-- ========== Scripts Section ========== --}}
@push('scripts')
    @include('admin.dashboard.partials.dashboard-scripts', [
        'statusDistribution' => $statusDistribution,
        'messagesActivity' => $messagesActivity
    ])
@endpush
@endsection
