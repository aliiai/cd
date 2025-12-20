{{-- 
    Component: Collection Status Scripts
    Usage: @include('owner.analytics.partials.collection-status-scripts', [
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
    const ctx = document.getElementById('statusChart');
    if (ctx) {
        const statusChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'عدد المديونين',
                    data: chartData.counts,
                    backgroundColor: chartData.colors,
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

        // ========== تحديث الرسم البياني عند تغيير Dark Mode ==========
        const darkModeObserver = new MutationObserver(() => {
            if (statusChart) {
                const newColors = getChartColors();
                statusChart.options.plugins.legend.labels.color = newColors.legendColor;
                statusChart.update('none');
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
        });
    }

    // ========== Filter Debtors by Status ==========
    function filterDebtors(status) {
        window.location.href = '{{ route("owner.debtors.index") }}?status=' + status;
    }

    // ========== Sort Table ==========
    let sortDirection = {};
    function sortTable(column) {
        const tbody = document.getElementById('statusTableBody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        sortDirection[column] = !sortDirection[column];
        const direction = sortDirection[column] ? 1 : -1;
        
        rows.sort((a, b) => {
            let aVal, bVal;
            
            if (column === 'status') {
                aVal = a.querySelector('span').textContent.trim();
                bVal = b.querySelector('span').textContent.trim();
            } else if (column === 'count') {
                aVal = parseInt(a.cells[1].textContent.replace(/,/g, ''));
                bVal = parseInt(b.cells[1].textContent.replace(/,/g, ''));
            } else if (column === 'amount') {
                aVal = parseFloat(a.cells[2].textContent.replace(/[^\d.]/g, ''));
                bVal = parseFloat(b.cells[2].textContent.replace(/[^\d.]/g, ''));
            }
            
            if (typeof aVal === 'string') {
                return direction * aVal.localeCompare(bVal);
            }
            return direction * (aVal - bVal);
        });
        
        rows.forEach(row => tbody.appendChild(row));
    }
</script>

