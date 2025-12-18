@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-primary-50 to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">لوحة تحكم المدير</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400">نظرة شاملة على أداء المنصة</p>
        </div>

        <!-- ========== 1. البطاقات الإحصائية الكبيرة ========== -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- بطاقة مقدمي الخدمة -->
            <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl shadow-xl p-6 text-white transform  transition-transform duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white/20 rounded-lg p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium bg-white/20 px-3 py-1 rounded-full">إجمالي</span>
                </div>
                <h3 class="text-3xl font-bold mb-1">{{ number_format($totalOwners) }}</h3>
                <p class="text-primary-100 text-sm mb-3">مقدم خدمة</p>
                <div class="flex items-center gap-4 text-sm">
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-green-300 rounded-full ml-2"></div>
                        <span>نشط: {{ $activeOwners }}</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-red-300 rounded-full ml-2"></div>
                        <span>غير نشط: {{ $inactiveOwners }}</span>
                    </div>
                </div>
            </div>

            <!-- بطاقة المديونين -->
            <div class="bg-gradient-to-br from-secondary-500 to-secondary-600 rounded-2xl shadow-xl p-6 text-white transform  transition-transform duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white/20 rounded-lg p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium bg-white/20 px-3 py-1 rounded-full">إجمالي</span>
                </div>
                <h3 class="text-3xl font-bold mb-1">{{ number_format($totalDebtors) }}</h3>
                <p class="text-secondary-100 text-sm mb-3">مديون</p>
                <div class="text-sm">
                    <span class="font-semibold">{{ number_format($totalDebtAmount, 2) }} ر.س</span>
                    <span class="text-secondary-200"> إجمالي قيمة الديون</span>
                </div>
            </div>

            <!-- بطاقة الرسائل -->
            <div class="bg-gradient-to-br from-primary-400 to-primary-500 rounded-2xl shadow-xl p-6 text-white transform  transition-transform duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white/20 rounded-lg p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium bg-white/20 px-3 py-1 rounded-full">إجمالي</span>
                </div>
                <h3 class="text-3xl font-bold mb-1">{{ number_format($totalMessages) }}</h3>
                <p class="text-primary-100 text-sm mb-3">رسالة مرسلة</p>
                <div class="flex items-center gap-4 text-sm">
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-white/80 rounded-full ml-2"></div>
                        <span>SMS: {{ number_format($smsCount) }}</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-2 h-2 bg-secondary-300 rounded-full ml-2"></div>
                        <span>Email: {{ number_format($emailCount) }}</span>
                    </div>
                </div>
            </div>

            <!-- بطاقة الاشتراكات -->
            <div class="bg-gradient-to-br from-secondary-400 to-secondary-500 rounded-2xl shadow-xl p-6 text-white transform  transition-transform duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-white/20 rounded-lg p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium bg-white/20 px-3 py-1 rounded-full">نشط</span>
                </div>
                <h3 class="text-3xl font-bold mb-1">{{ number_format($activeSubscriptions) }}</h3>
                <p class="text-secondary-100 text-sm mb-3">اشتراك نشط</p>
                <div class="text-sm">
                    <span class="font-semibold">{{ number_format($totalSubscriptions) }}</span>
                    <span class="text-secondary-200"> إجمالي الاشتراكات</span>
                </div>
            </div>
        </div>

        <!-- ========== 2. البطاقات الإحصائية الثانوية ========== -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- بطاقة المبالغ المحصلة -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-r-4 border-primary-500 dark:border-primary-400">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">المبالغ المحصلة</h3>
                    <div class="bg-primary-100 dark:bg-primary-900/30 rounded-lg p-2">
                        <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ number_format($totalCollected, 2) }} <span class="text-lg text-gray-500 dark:text-gray-400">ر.س</span></p>
                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                    <span>نسبة التحصيل:</span>
                    <span class="font-bold text-primary-600 dark:text-primary-400 mr-2">{{ number_format($collectionRate, 1) }}%</span>
                </div>
            </div>

            <!-- بطاقة استخدام AI -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-r-4 border-secondary-500 dark:border-secondary-400">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">استخدام الذكاء الاصطناعي</h3>
                    <div class="bg-secondary-100 dark:bg-secondary-900/30 rounded-lg p-2">
                        <svg class="w-6 h-6 text-secondary-600 dark:text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ number_format($aiUsageCount) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">حملة استخدمت AI</p>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div class="bg-gradient-to-r from-primary-500 to-secondary-500 h-2 rounded-full" style="width: {{ min($aiUsagePercentage, 100) }}%"></div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">{{ number_format($aiUsagePercentage, 1) }}% من إجمالي الحملات</p>
            </div>

            <!-- بطاقة نشاط اليوم -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-r-4 border-primary-400 dark:border-primary-500">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">نشاط اليوم</h3>
                    <div class="bg-primary-100 dark:bg-primary-900/30 rounded-lg p-2">
                        <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 dark:text-gray-400">رسائل مرسلة</span>
                        <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($todayMessages) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 dark:text-gray-400">مديونين جدد</span>
                        <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($todayDebtors) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 dark:text-gray-400">مستخدمين منضمين</span>
                        <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($todayUsers) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========== 3. الرسوم البيانية والجداول ========== -->
        <div class="grid grid-cols-2 gap-6 mb-8 ">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- رسم بياني: توزيع حالات الديون -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">توزيع حالات الديون</h3>
                            <!-- Menu Icon -->
                            <button class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors" title="خيارات">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="relative" style="height: 400px;">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
                <!-- رسم بياني: نشاط الرسائل -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">نشاط الرسائل</h3>
                            <!-- Menu Icon -->
                            <button class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors" title="خيارات">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                </svg>
                            </button>
                        </div>
                        <!-- Filter Buttons -->
                        <div class="flex items-center space-x-2 space-x-reverse">
                            <button onclick="updateChart('daily')" id="filter-daily" class="px-3 py-1.5 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors filter-btn">
                                يومي
                            </button>
                            <button onclick="updateChart('weekly')" id="filter-weekly" class="px-3 py-1.5 text-sm font-medium rounded-lg border border-primary-500 dark:border-primary-400 bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 filter-btn active">
                                أسبوعي
                            </button>
                            <button onclick="updateChart('monthly')" id="filter-monthly" class="px-3 py-1.5 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors filter-btn">
                                شهري
                            </button>
                            <button onclick="updateChart('yearly')" id="filter-yearly" class="px-3 py-1.5 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors filter-btn">
                                سنوي
                            </button>
                        </div>
                    </div>
                    <div class="relative" style="height: 400px;">
                        <canvas id="messagesChart"></canvas>
                    </div>
                </div>
            </div>
            <!-- ========== 4. أحدث الأنشطة ========== -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- أحدث مقدمي الخدمة -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">أحدث مقدمي الخدمة</h3>
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 font-medium">عرض الكل</a>
                </div>
                <div class="space-y-4">
                    @forelse($recentOwners as $owner)
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                {{ strtoupper(substr($owner->name, 0, 1)) }}
                            </div>
                            <div class="mr-4">
                                <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $owner->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $owner->email }}</p>
                            </div>
                        </div>
                        <div class="text-left">
                            <span class="text-xs text-gray-500 dark:text-gray-400">تاريخ التسجيل</span>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $owner->created_at->format('Y-m-d') }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-500 dark:text-gray-400 py-8">لا يوجد مقدمي خدمة</p>
                        @endforelse
                    </div>
                </div>
    
                <!-- أحدث الحملات -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">أحدث الحملات</h3>
                        <a href="{{ route('admin.ai-reports.campaigns') }}" class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 font-medium">عرض الكل</a>
                    </div>
                    <div class="space-y-4">
                        @forelse($recentCampaigns as $campaign)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-br from-secondary-400 to-secondary-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <div class="mr-4">
                                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $campaign->campaign_number }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $campaign->owner->name ?? 'غير معروف' }}</p>
                                </div>
                            </div>
                            <div class="text-left">
                                <span class="inline-block px-2 py-1 text-xs font-medium rounded-full {{ $campaign->status_color }}">
                                    {{ $campaign->status_text }}
                                </span>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $campaign->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">لا توجد حملات</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        











        
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // دالة للتحقق من Dark Mode
    function isDarkMode() {
        return window.matchMedia('(prefers-color-scheme: dark)').matches || document.documentElement.classList.contains('dark');
    }
    
    // الحصول على ألوان حسب الوضع
    function getChartColors() {
        const dark = isDarkMode();
        return {
            legendColor: dark ? '#D1D5DB' : '#374151',
            tickColor: dark ? '#9CA3AF' : '#6B7280',
            gridColor: dark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)',
        };
    }
    
    // توزيع حالات الديون - Doughnut Chart محسّن
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusData = @json(array_column($statusDistribution, 'count'));
    const statusLabels = @json(array_column($statusDistribution, 'status_text'));
    const chartColors = getChartColors();
    
    const statusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusData,
                backgroundColor: [
                    'rgb(92, 112, 224)',   // primary - new
                    'rgb(129, 95, 228)',   // secondary - contacted
                    'rgb(92, 112, 224)',   // primary - promise_to_pay
                    'rgb(34, 197, 94)',    // green - paid
                    '#EF4444',             // red - overdue
                    '#6B7280',             // gray - failed
                ],
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            size: 12,
                            weight: '500',
                        },
                        color: chartColors.legendColor,
                        generateLabels: function(chart) {
                            const data = chart.data;
                            if (data.labels.length && data.datasets.length) {
                                const dataset = data.datasets[0];
                                return data.labels.map((label, i) => {
                                    const value = dataset.data[i];
                                    const total = dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return {
                                        text: label + ' (' + percentage + '%)',
                                        fillStyle: dataset.backgroundColor[i],
                                        strokeStyle: dataset.borderColor,
                                        lineWidth: dataset.borderWidth,
                                        hidden: false,
                                        index: i
                                    };
                                });
                            }
                            return [];
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.85)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: 'rgba(92, 112, 224, 0.3)',
                    borderWidth: 1,
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return label + ': ' + value + ' مديون (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // نشاط الرسائل - Bar Chart
    let messagesChart = null;
    const messagesCtx = document.getElementById('messagesChart').getContext('2d');
    
    function initMessagesChart(labels, counts) {
        if (messagesChart) {
            messagesChart.destroy();
        }
        
        // حساب الحد الأقصى للقيم لتحديد Y-axis
        const maxValue = Math.max(...counts);
        const yAxisMax = maxValue > 0 ? Math.ceil(maxValue * 1.1) : 10;
        
        // تحديث الألوان حسب الوضع الحالي
        const currentColors = getChartColors();
        
        messagesChart = new Chart(messagesCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'عدد الرسائل',
                    data: counts,
                    backgroundColor: 'rgb(92, 112, 224)',
                    borderColor: 'rgb(92, 112, 224)',
                    borderWidth: 0,
                    borderRadius: 6,
                    barThickness: 'flex',
                    maxBarThickness: 60,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.85)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(92, 112, 224, 0.3)',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: false,
                        callbacks: {
                            title: function(context) {
                                return context[0].label;
                            },
                            label: function(context) {
                                return context.parsed.y + ' رسالة';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            color: currentColors.tickColor,
                            font: {
                                size: 13,
                                weight: '500',
                            },
                            padding: 10,
                        }
                    },
                    y: {
                        beginAtZero: true,
                        max: yAxisMax,
                        grid: {
                            color: currentColors.gridColor,
                            drawBorder: false,
                        },
                        ticks: {
                            color: currentColors.tickColor,
                            font: {
                                size: 12,
                            },
                            stepSize: Math.ceil(yAxisMax / 10),
                            padding: 10,
                            callback: function(value) {
                                return value;
                            }
                        }
                    }
                }
            }
        });
    }
    
    // تهيئة الرسم البياني بالبيانات الأولية
    initMessagesChart(@json($messagesActivity['labels']), @json($messagesActivity['counts']));
    
    // دالة تحديث الرسم البياني حسب الفلتر
    function updateChart(filter) {
        // تحديث حالة الأزرار
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active', 'bg-primary-50', 'dark:bg-primary-900/30', 'text-primary-700', 'dark:text-primary-300', 'border-primary-500', 'dark:border-primary-400');
            btn.classList.add('border-gray-300', 'dark:border-gray-600', 'text-gray-700', 'dark:text-gray-300');
        });
        
        const activeBtn = document.getElementById('filter-' + filter);
        if (activeBtn) {
            activeBtn.classList.add('active', 'bg-primary-50', 'dark:bg-primary-900/30', 'text-primary-700', 'dark:text-primary-300', 'border-primary-500', 'dark:border-primary-400');
            activeBtn.classList.remove('border-gray-300', 'dark:border-gray-600', 'text-gray-700', 'dark:text-gray-300');
        }
        
        // جلب البيانات من السيرفر
        fetch('{{ route("admin.dashboard.messages-data") }}?filter=' + filter, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            initMessagesChart(data.labels, data.counts);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    
    // تحديث الرسوم البيانية عند تغيير Dark Mode
    const darkModeObserver = new MutationObserver(() => {
        // تحديث ألوان statusChart
        if (statusChart) {
            const newColors = getChartColors();
            statusChart.options.plugins.legend.labels.color = newColors.legendColor;
            statusChart.update('none');
        }
        
        // تحديث ألوان messagesChart
        if (messagesChart) {
            const newColors = getChartColors();
            messagesChart.options.scales.x.ticks.color = newColors.tickColor;
            messagesChart.options.scales.y.ticks.color = newColors.tickColor;
            messagesChart.options.scales.y.grid.color = newColors.gridColor;
            messagesChart.update('none');
        }
    });
    
    // مراقبة تغيير class على html element
    darkModeObserver.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class']
    });
    
    // مراقبة تغيير تفضيلات النظام
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        const newColors = getChartColors();
        if (statusChart) {
            statusChart.options.plugins.legend.labels.color = newColors.legendColor;
            statusChart.update('none');
        }
        if (messagesChart) {
            messagesChart.options.scales.x.ticks.color = newColors.tickColor;
            messagesChart.options.scales.y.ticks.color = newColors.tickColor;
            messagesChart.options.scales.y.grid.color = newColors.gridColor;
            messagesChart.update('none');
        }
    });
</script>
@endpush
@endsection
