{{-- 
    Component: JavaScript للرسوم البيانية والوظائف التفاعلية
    Usage: @include('admin.dashboard.partials.dashboard-scripts', [
        'statusDistribution' => $statusDistribution,
        'messagesActivity' => $messagesActivity
    ])
--}}
@props(['statusDistribution', 'messagesActivity'])

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
    
    // ========== رسم بياني: توزيع حالات الديون ==========
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
                        padding: window.innerWidth >= 1024 ? 15 : 10,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            size: window.innerWidth >= 1024 ? 12 : window.innerWidth >= 640 ? 11 : 10,
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

    // ========== رسم بياني: نشاط الرسائل ==========
    let messagesChart = null;
    const messagesCtx = document.getElementById('messagesChart').getContext('2d');
    
    function initMessagesChart(labels, counts) {
        if (messagesChart) {
            messagesChart.destroy();
        }
        
        const maxValue = Math.max(...counts);
        const yAxisMax = maxValue > 0 ? Math.ceil(maxValue * 1.1) : 10;
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
                                size: window.innerWidth >= 1024 ? 13 : window.innerWidth >= 640 ? 12 : 11,
                                weight: '500',
                            },
                            padding: window.innerWidth >= 1024 ? 10 : 8,
                            maxRotation: window.innerWidth < 640 ? 45 : 0,
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
                                size: window.innerWidth >= 1024 ? 12 : window.innerWidth >= 640 ? 11 : 10,
                            },
                            stepSize: Math.ceil(yAxisMax / 10),
                            padding: window.innerWidth >= 1024 ? 10 : 8,
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
    
    // ========== دالة تحديث الرسم البياني حسب الفلتر ==========
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
    
    // ========== تحديث الرسوم البيانية عند تغيير Dark Mode ==========
    const darkModeObserver = new MutationObserver(() => {
        if (statusChart) {
            const newColors = getChartColors();
            statusChart.options.plugins.legend.labels.color = newColors.legendColor;
            statusChart.update('none');
        }
        
        if (messagesChart) {
            const newColors = getChartColors();
            messagesChart.options.scales.x.ticks.color = newColors.tickColor;
            messagesChart.options.scales.y.ticks.color = newColors.tickColor;
            messagesChart.options.scales.y.grid.color = newColors.gridColor;
            messagesChart.update('none');
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
        if (messagesChart) {
            messagesChart.options.scales.x.ticks.color = newColors.tickColor;
            messagesChart.options.scales.y.ticks.color = newColors.tickColor;
            messagesChart.options.scales.y.grid.color = newColors.gridColor;
            messagesChart.update('none');
        }
    });
</script>

