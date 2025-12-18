@extends('layouts.owner')

@section('content')
<div class="py-12">
    <div class="w-full mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Income Analytics</h1>
            <p class="mt-2 text-sm text-gray-600">تحليلات الدخل والتحصيل المالي - تتبع الدخل المحصل من المديونيات</p>
        </div>

        <!-- Row 1: Income Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Income Card -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-xl border border-green-200 p-6 hover:shadow-2xl transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-600 mb-1">إجمالي الدخل المحصل</p>
                        <p class="text-3xl font-bold text-green-900">{{ number_format($totalIncome, 2) }} ر.س</p>
                    </div>
                    <div class="bg-green-500 rounded-full p-3 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Paid Debtors Card -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-xl border border-blue-200 p-6 hover:shadow-2xl transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-primary-600 mb-1">عدد الديون المحصلة</p>
                        <p class="text-3xl font-bold text-primary-900">{{ number_format($totalPaidDebtors) }}</p>
                    </div>
                    <div class="bg-primary-500 rounded-full p-3 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Average Debt Amount Card -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-xl border border-secondary-200 p-6 hover:shadow-2xl transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-secondary-600 mb-1">متوسط قيمة الدين</p>
                        <p class="text-3xl font-bold text-secondary-900">{{ number_format($averageDebtAmount, 2) }} ر.س</p>
                    </div>
                    <div class="bg-secondary-500 rounded-full p-3 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Max Debt Amount Card -->
            <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-xl shadow-xl border border-primary-200 p-6 hover:shadow-2xl transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-primary-600 mb-1">أعلى مبلغ محصل</p>
                        <p class="text-3xl font-bold text-primary-900">{{ number_format($maxDebtAmount, 2) }} ر.س</p>
                    </div>
                    <div class="bg-indigo-500 rounded-full p-3 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Chart -->
        <div class="mb-8">
            <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-700 to-gray-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-white flex items-center">
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            الدخل عبر الزمن
                        </h2>
                        <!-- Time Filter -->
                        <form method="GET" action="{{ route('owner.analytics.income') }}" class="flex items-center gap-2">
                            <select name="time_filter" id="time_filter" onchange="this.form.submit()" class="px-3 py-1.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 text-sm bg-white text-gray-700">
                                <option value="today" {{ $timeFilter === 'today' ? 'selected' : '' }}>اليوم</option>
                                <option value="7" {{ $timeFilter === '7' ? 'selected' : '' }}>آخر 7 أيام</option>
                                <option value="30" {{ $timeFilter === '30' ? 'selected' : '' }}>آخر 30 يوم</option>
                                <option value="custom" {{ $timeFilter === 'custom' ? 'selected' : '' }}>فترة مخصصة</option>
                            </select>
                            @if($timeFilter === 'custom')
                                <input type="date" name="date_from" value="{{ $dateFrom ? $dateFrom->format('Y-m-d') : '' }}" class="px-3 py-1.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 text-sm bg-white text-gray-700">
                                <input type="date" name="date_to" value="{{ $dateTo ? $dateTo->format('Y-m-d') : '' }}" class="px-3 py-1.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 text-sm bg-white text-gray-700">
                                <button type="submit" class="px-4 py-1.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm shadow-md">تطبيق</button>
                            @endif
                        </form>
                    </div>
                </div>
                <div class="p-6">
                    <div class="w-full" style="height: 350px;">
                        <canvas id="incomeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 3: Period Comparison + Top Debtors -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Period Comparison -->
            <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-700 to-gray-600 px-6 py-4">
                    <h2 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        مقارنة الفترات
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- This Month vs Last Month -->
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200 shadow-md">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-primary-700">هذا الشهر</span>
                                <span class="text-lg font-bold text-primary-900">{{ number_format($periodComparison['this_month'], 2) }} ر.س</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-primary-600">الشهر السابق</span>
                                <span class="text-sm font-semibold text-primary-800">{{ number_format($periodComparison['last_month'], 2) }} ر.س</span>
                            </div>
                            <div class="mt-2 flex items-center">
                                @if($periodComparison['month_change'] > 0)
                                    <span class="text-xs font-medium text-green-600 flex items-center">
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                        +{{ number_format($periodComparison['month_change'], 1) }}%
                                    </span>
                                @elseif($periodComparison['month_change'] < 0)
                                    <span class="text-xs font-medium text-red-600 flex items-center">
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                        </svg>
                                        {{ number_format($periodComparison['month_change'], 1) }}%
                                    </span>
                                @else
                                    <span class="text-xs font-medium text-gray-600">لا تغيير</span>
                                @endif
                            </div>
                        </div>

                        <!-- This Week vs Last Week -->
                        <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg p-4 border border-secondary-200 shadow-md">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-secondary-700">هذا الأسبوع</span>
                                <span class="text-lg font-bold text-secondary-900">{{ number_format($periodComparison['this_week'], 2) }} ر.س</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-secondary-600">الأسبوع السابق</span>
                                <span class="text-sm font-semibold text-secondary-800">{{ number_format($periodComparison['last_week'], 2) }} ر.س</span>
                            </div>
                            <div class="mt-2 flex items-center">
                                @if($periodComparison['week_change'] > 0)
                                    <span class="text-xs font-medium text-green-600 flex items-center">
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                        +{{ number_format($periodComparison['week_change'], 1) }}%
                                    </span>
                                @elseif($periodComparison['week_change'] < 0)
                                    <span class="text-xs font-medium text-red-600 flex items-center">
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                        </svg>
                                        {{ number_format($periodComparison['week_change'], 1) }}%
                                    </span>
                                @else
                                    <span class="text-xs font-medium text-gray-600">لا تغيير</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Debtors -->
            <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-700 to-gray-600 px-6 py-4">
                    <h2 class="text-lg font-bold text-white flex items-center">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                        أعلى المديونين دخلاً
                    </h2>
                </div>
                <div class="p-6">
                    @if($topDebtors->count() > 0)
                        <div class="space-y-3">
                            @foreach($topDebtors as $index => $debtor)
                                <div class="flex items-center justify-between p-3 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex items-center">
                                        <div class="bg-indigo-500 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold text-sm ml-3">
                                            {{ $index + 1 }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $debtor->name }}</p>
                                            <p class="text-xs text-gray-600">{{ $debtor->phone }}</p>
                                        </div>
                                    </div>
                                    <div class="text-left">
                                        <p class="font-bold text-green-600">{{ number_format($debtor->total_paid, 2) }} ر.س</p>
                                        <p class="text-xs text-gray-500">{{ $debtor->debts_count }} دين</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 text-center py-4">لا توجد بيانات</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Row 4: Income Details Table + Insights -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Income Details Table -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-700 to-gray-600 px-6 py-4">
                        <h2 class="text-lg font-bold text-white flex items-center">
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            تفاصيل الدخل
                        </h2>
                    </div>
                    <div class="p-6">
                        <!-- Search and Filters -->
                        <form method="GET" action="{{ route('owner.analytics.income') }}" class="mb-4 space-y-3">
                            <input type="hidden" name="time_filter" value="{{ $timeFilter }}">
                            @if($timeFilter === 'custom')
                                <input type="hidden" name="date_from" value="{{ $dateFrom ? $dateFrom->format('Y-m-d') : '' }}">
                                <input type="hidden" name="date_to" value="{{ $dateTo ? $dateTo->format('Y-m-d') : '' }}">
                            @endif
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <input type="text" name="search" value="{{ $search }}" placeholder="بحث بالاسم أو الهاتف أو البريد..." class="px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 text-sm">
                                <input type="number" name="min_amount" value="{{ $minAmount }}" placeholder="الحد الأدنى" step="0.01" class="px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 text-sm">
                                <input type="number" name="max_amount" value="{{ $maxAmount }}" placeholder="الحد الأقصى" step="0.01" class="px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 text-sm">
                            </div>
                            <button type="submit" class="w-full md:w-auto px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm shadow-md">بحث</button>
                        </form>

                        <!-- Table -->
                        <div class="overflow-x-auto rounded-lg shadow-inner">
                            <table class="w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-gray-700 to-gray-600">
                                    <tr>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-white uppercase tracking-wider">المديون</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-white uppercase tracking-wider">قيمة الدين</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-white uppercase tracking-wider">تاريخ التحصيل</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-white uppercase tracking-wider">رابط الدفع</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-white uppercase tracking-wider">إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($incomeDetails as $debtor)
                                        <tr class="hover:bg-primary-50 transition-all duration-200">
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-900">{{ $debtor->name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $debtor->phone }}</p>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <span class="text-sm font-bold text-green-600">{{ number_format($debtor->debt_amount, 2) }} ر.س</span>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ \Carbon\Carbon::parse($debtor->payment_date)->format('Y-m-d H:i') }}
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                @if($debtor->payment_link)
                                                    <a href="{{ $debtor->payment_link }}" target="_blank" class="text-primary-600 hover:text-primary-800 text-sm">عرض الرابط</a>
                                                @else
                                                    <span class="text-gray-400 text-sm">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <a href="{{ route('owner.debtors.index') }}?search={{ $debtor->name }}" class="text-primary-600 hover:text-primary-800 text-sm font-medium">عرض</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">لا توجد بيانات</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $incomeDetails->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Insights -->
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
                                <div class="flex items-start p-3 rounded-lg border shadow-sm hover:shadow-md transition-shadow {{ $insight['type'] === 'success' ? 'bg-green-50 border-green-200' : ($insight['type'] === 'warning' ? 'bg-yellow-50 border-yellow-200' : 'bg-primary-50 border-blue-200') }}">
                                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5 {{ $insight['type'] === 'success' ? 'text-green-600' : ($insight['type'] === 'warning' ? 'text-yellow-600' : 'text-primary-600') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $insight['icon'] }}"></path>
                                    </svg>
                                    <p class="text-sm font-medium {{ $insight['type'] === 'success' ? 'text-green-800' : ($insight['type'] === 'warning' ? 'text-yellow-800' : 'text-primary-800') }} mr-2">
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
    const ctx = document.getElementById('incomeChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'الدخل المحصل (ر.س)',
                    data: chartData.amounts,
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#10B981',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
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
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'الدخل: ' + context.parsed.y.toFixed(2) + ' ر.س';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toFixed(2) + ' ر.س';
                            }
                        }
                    }
                }
            }
        });
    }
</script>
@endpush
@endsection
