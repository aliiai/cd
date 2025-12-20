{{-- 
    Component: JavaScript للرسوم البيانية والوظائف التفاعلية
    Usage: @include('owner.dashboard.partials.dashboard-scripts', [
        'collectionTrendData' => $collectionTrendData,
        'statusDistributionData' => $statusDistributionData,
        'channelUsageData' => $channelUsageData
    ])
--}}
@props([
    'collectionTrendData',
    'statusDistributionData',
    'channelUsageData'
])

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

    // ========== رسم بياني: تطور التحصيل عبر الزمن ==========
    let collectionTrendChart = null;
    const collectionTrendCtx = document.getElementById('collectionTrendChart').getContext('2d');
    const chartColors = getChartColors();
    
    function initCollectionChart(labels, amounts) {
        if (collectionTrendChart) {
            collectionTrendChart.destroy();
        }
        
        const maxValue = Math.max(...amounts, 0);
        const yAxisMax = maxValue > 0 ? Math.ceil(maxValue * 1.15) : 1000;
        
        // إنشاء gradient للـ fill
        const gradient = collectionTrendCtx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(92, 112, 224, 0.3)');
        gradient.addColorStop(1, 'rgba(92, 112, 224, 0.05)');
        
        collectionTrendChart = new Chart(collectionTrendCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'المبالغ المحصلة (ر.س)',
                    data: amounts,
                    borderColor: 'rgb(92, 112, 224)',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: 'rgb(92, 112, 224)',
                    pointBorderWidth: 2,
                    pointHoverBackgroundColor: 'rgb(92, 112, 224)',
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
                        borderColor: 'rgba(92, 112, 224, 0.3)',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true,
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y.toLocaleString('ar-SA') + ' ر.س';
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
                        max: yAxisMax,
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
                                return value.toLocaleString('ar-SA') + ' ر.س';
                            }
                        }
                    }
                }
            }
        });
    }
    
    // تهيئة الرسم البياني بالبيانات الأولية
    initCollectionChart(@json($collectionTrendData['labels']), @json($collectionTrendData['amounts']));
    
    // ========== دالة تحديث الرسم البياني حسب الفلتر ==========
    function updateCollectionChart(filter) {
        // تحديث حالة الأزرار
        document.querySelectorAll('.collection-filter-btn').forEach(btn => {
            btn.classList.remove('active', 'bg-primary-50', 'dark:bg-primary-900/30', 'text-primary-700', 'dark:text-primary-300', 'border-primary-500', 'dark:border-primary-400');
            btn.classList.add('border-gray-300', 'dark:border-gray-600', 'text-gray-700', 'dark:text-gray-300');
        });
        
        const activeBtn = document.getElementById('collection-filter-' + filter);
        if (activeBtn) {
            activeBtn.classList.add('active', 'bg-primary-50', 'dark:bg-primary-900/30', 'text-primary-700', 'dark:text-primary-300', 'border-primary-500', 'dark:border-primary-400');
            activeBtn.classList.remove('border-gray-300', 'dark:border-gray-600', 'text-gray-700', 'dark:text-gray-300');
        }
        
        // جلب البيانات من السيرفر
        fetch('{{ route("owner.dashboard.collection-data") }}?filter=' + filter, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            initCollectionChart(data.labels, data.amounts);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // ========== رسم بياني: توزيع حالات الديون ==========
    const statusDistributionCtx = document.getElementById('statusDistributionChart').getContext('2d');
    const statusChart = new Chart(statusDistributionCtx, {
        type: 'doughnut',
        data: {
            labels: @json($statusDistributionData['labels']),
            datasets: [{
                data: @json($statusDistributionData['counts']),
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

    // ========== رسم بياني: استخدام قنوات التواصل ==========
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
            maintainAspectRatio: false,
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
                x: {
                    ticks: {
                        color: chartColors.tickColor
                    },
                    grid: {
                        color: chartColors.gridColor
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: chartColors.tickColor,
                        stepSize: 1,
                    },
                    grid: {
                        color: chartColors.gridColor
                    }
                }
            }
        }
    });

    // ========== تحديث الرسوم البيانية عند تغيير Dark Mode ==========
    const darkModeObserver = new MutationObserver(() => {
        if (statusChart) {
            const newColors = getChartColors();
            statusChart.options.plugins.legend.labels.color = newColors.legendColor;
            statusChart.update('none');
        }
        if (collectionTrendChart) {
            const newColors = getChartColors();
            collectionTrendChart.options.plugins.legend.labels.color = newColors.legendColor;
            collectionTrendChart.options.scales.x.ticks.color = newColors.tickColor;
            collectionTrendChart.options.scales.y.ticks.color = newColors.tickColor;
            collectionTrendChart.options.scales.y.grid.color = newColors.gridColor;
            collectionTrendChart.update('none');
        }
    });
    
    darkModeObserver.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class']
    });
    
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        const newColors = getChartColors();
        if (statusChart) {
            statusChart.options.plugins.legend.labels.color = newColors.legendColor;
            statusChart.update('none');
        }
        if (collectionTrendChart) {
            collectionTrendChart.options.plugins.legend.labels.color = newColors.legendColor;
            collectionTrendChart.options.scales.x.ticks.color = newColors.tickColor;
            collectionTrendChart.options.scales.y.ticks.color = newColors.tickColor;
            collectionTrendChart.options.scales.y.grid.color = newColors.gridColor;
            collectionTrendChart.update('none');
        }
    });
</script>

