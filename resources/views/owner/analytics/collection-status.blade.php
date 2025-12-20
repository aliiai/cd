@extends('layouts.owner')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-primary-50 to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- ========== Header Section ========== --}}
        @include('owner.analytics.partials.header-section', [
            'title' => 'تحليلات حالة التحصيل',
            'description' => 'فهم توزيع المديونيات واتخاذ قرارات أفضل'
        ])

        {{-- ========== Summary Cards ========== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            @include('owner.dashboard.partials.stat-card-primary', [
                'value' => number_format($totalDebtors),
                'subtitle' => 'مديون',
                'gradientFrom' => 'from-primary-500',
                'gradientTo' => 'to-primary-600',
                'badge' => 'إجمالي',
                'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>'
            ])

            @include('owner.dashboard.partials.stat-card-primary', [
                'value' => number_format($totalDebtAmount, 2),
                'subtitle' => 'ر.س إجمالي قيمة الديون',
                'gradientFrom' => 'from-secondary-500',
                'gradientTo' => 'to-secondary-600',
                'badge' => 'إجمالي',
                'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
            ])

            @include('owner.dashboard.partials.stat-card-primary', [
                'value' => number_format($paidAmount, 2),
                'subtitle' => 'ر.س محصلة',
                'gradientFrom' => 'from-emerald-500',
                'gradientTo' => 'to-emerald-600',
                'badge' => 'محصلة',
                'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
            ])

            @include('owner.dashboard.partials.stat-card-primary', [
                'value' => number_format($collectionRate, 1) . ' <span class="text-2xl">%</span>',
                'subtitle' => 'نسبة التحصيل',
                'gradientFrom' => 'from-blue-500',
                'gradientTo' => 'to-blue-600',
                'badge' => 'نسبة',
                'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>'
            ])

        </div>

        {{-- ========== Chart + Table ========== --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            
            @include('owner.analytics.partials.status-distribution-chart')
            @include('owner.analytics.partials.status-distribution-table', [
                'statusDistribution' => $statusDistribution
            ])

        </div>

        {{-- ========== Time Evolution + Insights ========== --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            @include('owner.analytics.partials.time-evolution-section', [
                'timeFilter' => $timeFilter,
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'overdueToPaid' => $overdueToPaid,
                'promiseToPaid' => $promiseToPaid,
                'route' => 'owner.analytics.collection-status'
            ])

            @include('owner.analytics.partials.smart-insights-section', [
                'insights' => $insights
            ])

        </div>

    </div>
</div>

@push('scripts')
    @include('owner.analytics.partials.collection-status-scripts', [
        'chartData' => $chartData
    ])
@endpush
@endsection
