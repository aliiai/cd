@extends('layouts.owner')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-primary-50 to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- ========== Header Section ========== --}}
        @include('owner.analytics.partials.header-section', [
            'title' => 'تحليلات الدخل',
            'description' => 'تتبع الدخل المحصل من المديونيات'
        ])

        {{-- ========== Income Summary Cards ========== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            @include('owner.dashboard.partials.stat-card-primary', [
                'value' => number_format($totalIncome, 2),
                'subtitle' => 'ر.س إجمالي الدخل المحصل',
                'gradientFrom' => 'from-emerald-500',
                'gradientTo' => 'to-emerald-600',
                'badge' => 'إجمالي',
                'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
            ])

            @include('owner.dashboard.partials.stat-card-primary', [
                'value' => number_format($totalPaidDebtors),
                'subtitle' => 'ديون محصلة',
                'gradientFrom' => 'from-primary-500',
                'gradientTo' => 'to-primary-600',
                'badge' => 'عدد',
                'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
            ])

            @include('owner.dashboard.partials.stat-card-primary', [
                'value' => number_format($averageDebtAmount, 2),
                'subtitle' => 'ر.س متوسط قيمة الدين',
                'gradientFrom' => 'from-secondary-500',
                'gradientTo' => 'to-secondary-600',
                'badge' => 'متوسط',
                'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>'
            ])

            @include('owner.dashboard.partials.stat-card-primary', [
                'value' => number_format($maxDebtAmount, 2),
                'subtitle' => 'ر.س أعلى مبلغ محصل',
                'gradientFrom' => 'from-blue-500',
                'gradientTo' => 'to-blue-600',
                'badge' => 'أعلى',
                'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>'
            ])

        </div>

        {{-- ========== Income Chart ========== --}}
        <!-- @include('owner.analytics.partials.income-chart-section', [
            'timeFilter' => $timeFilter,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo
        ]) -->

        {{-- ========== Period Comparison + Top Debtors ========== --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            
            @include('owner.analytics.partials.period-comparison-section', [
                'periodComparison' => $periodComparison
            ])

            @include('owner.analytics.partials.top-debtors-section', [
                'topDebtors' => $topDebtors
            ])

        </div>

        {{-- ========== Income Details Table + Insights ========== --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            @include('owner.analytics.partials.income-details-table', [
                'incomeDetails' => $incomeDetails,
                'timeFilter' => $timeFilter,
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'search' => $search,
                'minAmount' => $minAmount,
                'maxAmount' => $maxAmount
            ])

            @include('owner.analytics.partials.smart-insights-section', [
                'insights' => $insights
            ])

        </div>

    </div>
</div>

@push('scripts')
    @include('owner.analytics.partials.income-scripts', [
        'chartData' => $chartData
    ])
@endpush
@endsection
