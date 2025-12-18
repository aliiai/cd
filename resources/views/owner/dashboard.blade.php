@extends('layouts.owner')

@section('content')
<div class="py-6">
    <div class="w-full mx-auto sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">لوحة التحكم</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">نظرة شاملة على أداء منصتك</p>
        </div>

        <!-- ========== 1. البطاقات الإحصائية العلوية ========== -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- بطاقة إجمالي المديونين -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">إجمالي المديونين</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($totalDebtors) }}</p>
                        @if($debtorsChange != 0)
                        <p class="text-sm mt-2 {{ $debtorsChange > 0 ? 'text-green-600' : 'text-red-600' }}">
                            <span class="inline-flex items-center">
                                @if($debtorsChange > 0)
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                    </svg>
                                @endif
                                {{ number_format(abs($debtorsChange), 1) }}%
                            </span>
                            <span class="text-gray-500 dark:text-gray-400 text-xs">من الشهر السابق</span>
                        </p>
                        @endif
                    </div>
                    <div class="bg-primary-100 rounded-lg p-3">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- بطاقة إجمالي قيمة الديون -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">إجمالي قيمة الديون</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($totalDebtAmount, 2) }} <span class="text-lg text-gray-500 dark:text-gray-400">ر.س</span></p>
                        @if($debtAmountChange != 0)
                        <p class="text-sm mt-2 {{ $debtAmountChange > 0 ? 'text-green-600' : 'text-red-600' }}">
                            <span class="inline-flex items-center">
                                @if($debtAmountChange > 0)
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                    </svg>
                                @endif
                                {{ number_format(abs($debtAmountChange), 1) }}%
                            </span>
                            <span class="text-gray-500 dark:text-gray-400 text-xs">من الشهر السابق</span>
                        </p>
                        @endif
                    </div>
                    <div class="bg-secondary-100 rounded-lg p-3">
                        <svg class="w-8 h-8 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- بطاقة إجمالي المبالغ المحصلة -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">إجمالي المبالغ المحصلة</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($totalCollected, 2) }} <span class="text-lg text-gray-500 dark:text-gray-400">ر.س</span></p>
                        @if($collectedChange != 0)
                        <p class="text-sm mt-2 {{ $collectedChange > 0 ? 'text-green-600' : 'text-red-600' }}">
                            <span class="inline-flex items-center">
                                @if($collectedChange > 0)
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                    </svg>
                                @endif
                                {{ number_format(abs($collectedChange), 1) }}%
                            </span>
                            <span class="text-gray-500 dark:text-gray-400 text-xs">من الشهر السابق</span>
                        </p>
                        @endif
                    </div>
                    <div class="bg-primary-100 rounded-lg p-3">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- بطاقة إجمالي الرسائل المرسلة -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">إجمالي الرسائل المرسلة</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($totalMessages) }}</p>
                        <div class="flex items-center gap-4 mt-2">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-primary-500 rounded-full ml-2"></div>
                                <span class="text-sm text-gray-600 dark:text-gray-400">SMS: {{ number_format($smsCount) }}</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-secondary-500 rounded-full ml-2"></div>
                                <span class="text-sm text-gray-600 dark:text-gray-400">Email: {{ number_format($emailCount) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-primary-100 rounded-lg p-3">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- بطاقة استخدام الذكاء الاصطناعي -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">استخدام الذكاء الاصطناعي</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($aiUsageCount) }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">عدد مرات توليد الرسائل</p>
                        @if($maxMessages > 0)
                        <div class="mt-3">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs text-gray-600 dark:text-gray-400">نسبة الاستهلاك</span>
                                <span class="text-xs font-medium text-gray-900 dark:text-gray-100">{{ number_format($aiUsagePercentage, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-gradient-to-r from-primary-500 to-secondary-500 h-2 rounded-full" style="width: {{ min($aiUsagePercentage, 100) }}%"></div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="bg-secondary-100 rounded-lg p-3">
                        <svg class="w-8 h-8 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- بطاقة نسبة التحصيل -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">نسبة التحصيل</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                            {{ $totalDebtAmount > 0 ? number_format(($totalCollected / $totalDebtAmount) * 100, 1) : 0 }}%
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                            {{ number_format($totalCollected, 2) }} ر.س من {{ number_format($totalDebtAmount, 2) }} ر.س
                        </p>
                        <div class="mt-3">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-primary-500 to-secondary-500 h-2 rounded-full" 
                                     style="width: {{ $totalDebtAmount > 0 ? min(($totalCollected / $totalDebtAmount) * 100, 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-emerald-100 rounded-lg p-3">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========== 2. الرسوم البيانية ========== -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- رسم بياني: تطور التحصيل عبر الزمن -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">تطور التحصيل عبر الزمن</h3>
                <div class="relative" style="height: 300px;">
                    <canvas id="collectionTrendChart"></canvas>
                </div>
            </div>

            <!-- رسم بياني: توزيع حالات الديون -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">توزيع حالات الديون</h3>
                <div class="relative" style="height: 300px;">
                    <canvas id="statusDistributionChart"></canvas>
                </div>
            </div>

            <!-- رسم بياني: استخدام قنوات التواصل -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:col-span-2">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">استخدام قنوات التواصل</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="relative" style="height: 250px;">
                        <canvas id="channelUsageChart"></canvas>
                    </div>
                    <div class="flex flex-col justify-center">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-primary-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-primary-500 rounded-full ml-3"></div>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">SMS</span>
                                </div>
                                <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($channelUsageData['counts'][0]) }}</span>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-secondary-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-secondary-500 rounded-full ml-3"></div>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">Email</span>
                                </div>
                                <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($channelUsageData['counts'][1]) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========== 3. التنبيهات الذكية ========== -->
        @if(count($alerts) > 0)
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">التنبيهات الذكية</h3>
            <div class="space-y-3">
                @foreach($alerts as $alert)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 flex items-start {{ 
                    $alert['type'] === 'danger' ? 'border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20' : 
                    ($alert['type'] === 'warning' ? 'border-yellow-200 dark:border-yellow-800 bg-yellow-50 dark:bg-yellow-900/20' : 
                    ($alert['type'] === 'success' ? 'border-primary-200 dark:border-primary-800 bg-primary-50 dark:bg-primary-900/20' : 'border-primary-200 dark:border-primary-800 bg-primary-50 dark:bg-primary-900/20'))
                }}">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 {{ 
                            $alert['type'] === 'danger' ? 'text-red-600' : 
                            ($alert['type'] === 'warning' ? 'text-yellow-600' : 
                            ($alert['type'] === 'success' ? 'text-green-600' : 'text-blue-600'))
                        }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $alert['icon'] }}"></path>
                        </svg>
                    </div>
                    <div class="mr-3 flex-1">
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">{{ $alert['title'] }}</h4>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $alert['message'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // تطور التحصيل عبر الزمن
    const collectionTrendCtx = document.getElementById('collectionTrendChart').getContext('2d');
    new Chart(collectionTrendCtx, {
        type: 'line',
        data: {
            labels: @json($collectionTrendData['labels']),
            datasets: [{
                label: 'المبالغ المحصلة (ر.س)',
                data: @json($collectionTrendData['amounts']),
                borderColor: 'rgb(92, 112, 224)',
                backgroundColor: 'rgba(92, 112, 224, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                pointHoverRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('ar-SA') + ' ر.س';
                        }
                    }
                }
            }
        }
    });

    // توزيع حالات الديون
    const statusDistributionCtx = document.getElementById('statusDistributionChart').getContext('2d');
    new Chart(statusDistributionCtx, {
        type: 'doughnut',
        data: {
            labels: @json($statusDistributionData['labels']),
            datasets: [{
                data: @json($statusDistributionData['counts']),
                backgroundColor: @json($statusDistributionData['colors']),
                borderWidth: 2,
                borderColor: '#ffffff',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 1.5,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.parsed + ' مديون';
                            return label;
                        }
                    }
                }
            }
        }
    });

    // استخدام قنوات التواصل
    const channelUsageCtx = document.getElementById('channelUsageChart').getContext('2d');
    new Chart(channelUsageCtx, {
        type: 'bar',
        data: {
            labels: @json($channelUsageData['labels']),
            datasets: [{
                label: 'عدد الرسائل',
                data: @json($channelUsageData['counts']),
                backgroundColor: @json($channelUsageData['colors']),
                borderWidth: 0,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2,
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' رسالة';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection
