@extends('layouts.owner')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-primary-50 to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- ========== Header Section ========== --}}
        @include('owner.dashboard.partials.header-section')

        {{-- ========== 1. البطاقات الإحصائية الكبيرة ========== --}}
        @include('owner.dashboard.partials.primary-stats', [
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

        {{-- ========== 2. البطاقات الإحصائية الثانوية ========== --}}
        @include('owner.dashboard.partials.secondary-stats', [
            'totalDebtAmount' => $totalDebtAmount,
            'totalCollected' => $totalCollected,
            'aiUsageCount' => $aiUsageCount,
            'aiUsagePercentage' => $aiUsagePercentage,
            'maxMessages' => $maxMessages,
            'todayMessages' => $todayMessages,
            'todayDebtors' => $todayDebtors,
            'todayCollected' => $todayCollected
        ])

        {{-- ========== 3. الرسوم البيانية ========== --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            
            {{-- رسم بياني: تطور التحصيل عبر الزمن --}}
            @include('owner.dashboard.partials.collection-trend-chart', [
                'collectionTrendData' => $collectionTrendData
            ])

            {{-- رسم بياني: توزيع حالات الديون --}}
            @include('owner.dashboard.partials.status-distribution-chart')

        </div>

        {{-- ========== 4. استخدام قنوات التواصل والتنبيهات الذكية ========== --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            
            {{-- استخدام قنوات التواصل --}}
            @include('owner.dashboard.partials.channel-usage-chart', [
                'channelUsageData' => $channelUsageData
            ])

            {{-- التنبيهات الذكية --}}
            @include('owner.dashboard.partials.smart-alerts', [
                'alerts' => $alerts
            ])

        </div>

    </div>
</div>

{{-- ========== Scripts Section ========== --}}
@push('scripts')
    @include('owner.dashboard.partials.dashboard-scripts', [
        'collectionTrendData' => $collectionTrendData,
        'statusDistributionData' => $statusDistributionData,
        'channelUsageData' => $channelUsageData
    ])
@endpush
@endsection
