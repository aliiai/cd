@extends('layouts.owner')

@section('content')
<div class="py-12">
    <div class="w-full mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Collection Status Analytics</h1>
            <p class="mt-2 text-sm text-gray-600">تحليلات حالة التحصيل - فهم توزيع المديونيات واتخاذ قرارات أفضل</p>
        </div>

        <!-- Row 1: Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Debtors Card -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-xl border border-blue-200 p-6 hover:shadow-2xl transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-600 mb-1">إجمالي المديونين</p>
                        <p class="text-3xl font-bold text-blue-900">{{ number_format($totalDebtors) }}</p>
                    </div>
                    <div class="bg-blue-500 rounded-full p-3 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Debt Amount Card -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-xl border border-purple-200 p-6 hover:shadow-2xl transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-600 mb-1">إجمالي قيمة الديون</p>
                        <p class="text-3xl font-bold text-purple-900">{{ number_format($totalDebtAmount, 2) }} ر.س</p>
                    </div>
                    <div class="bg-purple-500 rounded-full p-3 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Paid Amount Card -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-xl border border-green-200 p-6 hover:shadow-2xl transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-600 mb-1">المبالغ المحصلة</p>
                        <p class="text-3xl font-bold text-green-900">{{ number_format($paidAmount, 2) }} ر.س</p>
                    </div>
                    <div class="bg-green-500 rounded-full p-3 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Collection Rate Card -->
            <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl shadow-xl border border-indigo-200 p-6 hover:shadow-2xl transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-indigo-600 mb-1">نسبة التحصيل</p>
                        <p class="text-3xl font-bold text-indigo-900">{{ number_format($collectionRate, 1) }}%</p>
                    </div>
                    <div class="bg-indigo-500 rounded-full p-3 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Chart + Table -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Chart - Left Side (1/3) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden h-full">
                    <div class="bg-gradient-to-r from-gray-700 to-gray-600 px-6 py-4">
                        <h2 class="text-lg font-bold text-white flex items-center">
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            توزيع الحالات
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="w-full" style="height: 300px;">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table - Right Side (2/3) -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden h-full">
                    <div class="bg-gradient-to-r from-gray-700 to-gray-600 px-6 py-4">
                        <h2 class="text-lg font-bold text-white flex items-center">
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            توزيع حالات التحصيل
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto rounded-lg shadow-inner">
                            <table class="w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-gray-700 to-gray-600">
                                    <tr>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-white uppercase tracking-wider cursor-pointer hover:bg-gray-600 transition-colors" onclick="sortTable('status')">
                                            حالة الدين
                                        </th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-white uppercase tracking-wider cursor-pointer hover:bg-gray-600 transition-colors" onclick="sortTable('count')">
                                            عدد المديونين
                                        </th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-white uppercase tracking-wider cursor-pointer hover:bg-gray-600 transition-colors" onclick="sortTable('amount')">
                                            إجمالي المبلغ
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="statusTableBody">
                                    @foreach($statusDistribution as $status)
                                        <tr 
                                            class="hover:bg-blue-50 transition-all duration-200 hover:shadow-md cursor-pointer"
                                            onclick="filterDebtors('{{ $status['status'] }}')"
                                        >
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <span class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm {{ $status['status_color'] }}">
                                                    {{ $status['status_text'] }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                                {{ number_format($status['count']) }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                                {{ number_format($status['total_amount'], 2) }} ر.س
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 3: Time Evolution + Insights -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Time Evolution Card -->
            <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden hover:shadow-2xl transition-shadow duration-200">
                <div class="bg-gradient-to-r from-gray-700 to-gray-600 px-6 py-4">
                    <h2 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        تطور التحصيل
                    </h2>
                </div>
                <div class="p-6">
                    <!-- Time Filter -->
                    <form method="GET" action="{{ route('owner.analytics.collection-status') }}" class="mb-4">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">الفترة الزمنية</label>
                                <select name="time_filter" id="time_filter" onchange="this.form.submit()" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option value="today" {{ $timeFilter === 'today' ? 'selected' : '' }}>اليوم</option>
                                    <option value="7" {{ $timeFilter === '7' ? 'selected' : '' }}>آخر 7 أيام</option>
                                    <option value="30" {{ $timeFilter === '30' ? 'selected' : '' }}>آخر 30 يوم</option>
                                    <option value="custom" {{ $timeFilter === 'custom' ? 'selected' : '' }}>فترة مخصصة</option>
                                </select>
                            </div>
                            
                            @if($timeFilter === 'custom')
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">من</label>
                                    <input type="date" name="date_from" value="{{ $dateFrom ? $dateFrom->format('Y-m-d') : '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">إلى</label>
                                    <input type="date" name="date_to" value="{{ $dateTo ? $dateTo->format('Y-m-d') : '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm">
                                </div>
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 text-sm shadow-md hover:shadow-lg">
                                    تطبيق
                                </button>
                            @endif
                        </div>
                    </form>

                    <!-- Evolution Stats Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-4 border border-green-200 shadow-md hover:shadow-lg transition-shadow">
                            <div class="text-center">
                                <p class="text-xs font-medium text-green-600 mb-1">من متأخر → مدفوع</p>
                                <p class="text-2xl font-bold text-green-900">{{ $overdueToPaid }}</p>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200 shadow-md hover:shadow-lg transition-shadow">
                            <div class="text-center">
                                <p class="text-xs font-medium text-blue-600 mb-1">من وعد بالدفع → مدفوع</p>
                                <p class="text-2xl font-bold text-blue-900">{{ $promiseToPaid }}</p>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200 shadow-md hover:shadow-lg transition-shadow">
                            <div class="text-center">
                                <p class="text-xs font-medium text-purple-600 mb-1">إجمالي التحولات</p>
                                <p class="text-2xl font-bold text-purple-900">{{ $overdueToPaid + $promiseToPaid }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Smart Insights Card -->
            <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden hover:shadow-2xl transition-shadow duration-200">
                <div class="bg-gradient-to-r from-gray-700 to-gray-600 px-6 py-4">
                    <h2 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                        مؤشرات ذكية
                    </h2>
                </div>
                <div class="p-6">
                    @if(count($insights) > 0)
                        <div class="space-y-3">
                            @foreach($insights as $insight)
                                <div class="flex items-start p-3 rounded-lg border shadow-sm hover:shadow-md transition-shadow {{ $insight['type'] === 'success' ? 'bg-green-50 border-green-200' : ($insight['type'] === 'warning' ? 'bg-yellow-50 border-yellow-200' : 'bg-blue-50 border-blue-200') }}">
                                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5 {{ $insight['type'] === 'success' ? 'text-green-600' : ($insight['type'] === 'warning' ? 'text-yellow-600' : 'text-blue-600') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $insight['icon'] }}"></path>
                                    </svg>
                                    <p class="text-sm font-medium {{ $insight['type'] === 'success' ? 'text-green-800' : ($insight['type'] === 'warning' ? 'text-yellow-800' : 'text-blue-800') }} mr-2">
                                        {{ $insight['message'] }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 text-center py-4">لا توجد مؤشرات حالياً</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    // Chart Data
    const chartData = @json($chartData);
    
    // Initialize Chart
    const ctx = document.getElementById('statusChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'عدد المديونين',
                    data: chartData.counts,
                    backgroundColor: chartData.colors,
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 10,
                            font: {
                                size: 10
                            },
                            boxWidth: 10,
                            boxHeight: 10
                        }
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
                        },
                        bodyFont: {
                            size: 11
                        }
                    }
                }
            }
        });
    }

    // Filter Debtors by Status
    function filterDebtors(status) {
        window.location.href = '{{ route("owner.debtors.index") }}?status=' + status;
    }

    // Sort Table (simple client-side sorting)
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
@endpush
@endsection
