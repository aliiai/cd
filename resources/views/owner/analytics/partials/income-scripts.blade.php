{{-- 
    Component: Income Scripts
    Usage: @include('owner.analytics.partials.income-scripts', [
        'chartData' => $chartData
    ])
--}}
@props(['chartData'])

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // ========== دوال مساعدة للـ Dark Mode ==========
    function isDarkMode() {
        return window.matchMedia('(prefers-color-scheme: dark)').matches || document.documentElement.classList.contains('dark');
    }
    
    function getChartColors() {
        const dark = isDarkMode();
        return {
            legendColor: dark ? '#D1D5DB' : '#374151',
            tickColor: dark ? '#9CA3AF' : '#6B7280',
            gridColor: dark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)',
        };
    }

    // ========== Chart Data ==========
    const chartData = @json($chartData);
    const chartColors = getChartColors();
    
    // ========== Initialize Chart ==========
    const ctx = document.getElementById('incomeChart');
    if (ctx) {
        const incomeChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'الدخل المحصل (ر.س)',
                    data: chartData.amounts,
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: 'rgb(16, 185, 129)',
                    pointBorderWidth: 2,
                    pointHoverBackgroundColor: 'rgb(16, 185, 129)',
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: chartColors.legendColor,
                            font: {
                                size: 13,
                                weight: '500',
                            },
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: 'circle',
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.85)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(16, 185, 129, 0.3)',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true,
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return 'الدخل: ' + context.parsed.y.toLocaleString('ar-SA', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' ر.س';
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
                            color: chartColors.tickColor,
                            font: {
                                size: 12,
                                weight: '500',
                            },
                            padding: 10,
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: chartColors.gridColor,
                            drawBorder: false,
                        },
                        ticks: {
                            color: chartColors.tickColor,
                            font: {
                                size: 12,
                            },
                            padding: 10,
                            callback: function(value) {
                                return value.toLocaleString('ar-SA', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' ر.س';
                            }
                        }
                    }
                }
            }
        });

        // ========== تحديث الرسم البياني عند تغيير Dark Mode ==========
        const darkModeObserver = new MutationObserver(() => {
            if (incomeChart) {
                const newColors = getChartColors();
                incomeChart.options.plugins.legend.labels.color = newColors.legendColor;
                incomeChart.options.scales.x.ticks.color = newColors.tickColor;
                incomeChart.options.scales.y.ticks.color = newColors.tickColor;
                incomeChart.options.scales.y.grid.color = newColors.gridColor;
                incomeChart.update('none');
            }
        });
        
        darkModeObserver.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class']
        });
        
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            const newColors = getChartColors();
            if (incomeChart) {
                incomeChart.options.plugins.legend.labels.color = newColors.legendColor;
                incomeChart.options.scales.x.ticks.color = newColors.tickColor;
                incomeChart.options.scales.y.ticks.color = newColors.tickColor;
                incomeChart.options.scales.y.grid.color = newColors.gridColor;
                incomeChart.update('none');
            }
        });
    }

    // ========== دالة تحديث الفلتر ==========
    function updateIncomeFilter(filter) {
        // تحديث حالة الأزرار
        document.querySelectorAll('.income-filter-btn').forEach(btn => {
            btn.classList.remove('active', 'bg-primary-50', 'dark:bg-primary-900/30', 'text-primary-700', 'dark:text-primary-300', 'border-primary-500', 'dark:border-primary-400');
            btn.classList.add('border-gray-300', 'dark:border-gray-600', 'text-gray-700', 'dark:text-gray-300');
        });
        
        const activeBtn = document.getElementById('income-filter-' + filter);
        if (activeBtn) {
            activeBtn.classList.add('active', 'bg-primary-50', 'dark:bg-primary-900/30', 'text-primary-700', 'dark:text-primary-300', 'border-primary-500', 'dark:border-primary-400');
            activeBtn.classList.remove('border-gray-300', 'dark:border-gray-600', 'text-gray-700', 'dark:text-gray-300');
        }
        
        // تحديث قيمة hidden input وإرسال النموذج
        document.getElementById('income_time_filter').value = filter;
        
        // إذا كان الفلتر custom، لا نرسل النموذج مباشرة (نعرض حقول التاريخ)
        if (filter === 'custom') {
            // إعادة تحميل الصفحة مع time_filter=custom لعرض حقول التاريخ
            const form = document.getElementById('incomeFilterForm');
            const url = new URL(form.action);
            url.searchParams.set('time_filter', 'custom');
            window.location.href = url.toString();
        } else {
            // إرسال النموذج مباشرة
            document.getElementById('incomeFilterForm').submit();
        }
    }
</script>

