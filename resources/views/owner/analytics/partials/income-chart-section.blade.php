{{-- 
    Component: Income Chart Section
    Usage: @include('owner.analytics.partials.income-chart-section', [
        'timeFilter' => $timeFilter,
        'dateFrom' => $dateFrom,
        'dateTo' => $dateTo
    ])
--}}
@props([
    'timeFilter',
    'dateFrom',
    'dateTo'
])

<div class="mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-3 space-x-reverse">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">الدخل عبر الزمن</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">المبالغ المحصلة خلال الفترة المحددة</p>
                </div>
                <button class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors" title="خيارات">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                    </svg>
                </button>
            </div>
            {{-- Filter Buttons --}}
            <form method="GET" action="{{ route('owner.analytics.income') }}" id="incomeFilterForm" class="flex items-center space-x-2 space-x-reverse">
                <input type="hidden" name="time_filter" id="income_time_filter" value="{{ $timeFilter }}">
                @if($timeFilter === 'custom')
                    <input type="hidden" name="date_from" value="{{ $dateFrom ? $dateFrom->format('Y-m-d') : '' }}">
                    <input type="hidden" name="date_to" value="{{ $dateTo ? $dateTo->format('Y-m-d') : '' }}">
                @endif
                <button type="button" onclick="updateIncomeFilter('today')" id="income-filter-today" class="px-3 py-1.5 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors income-filter-btn {{ $timeFilter === 'today' ? 'active border-primary-500 dark:border-primary-400 bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : '' }}">
                    يومي
                </button>
                <button type="button" onclick="updateIncomeFilter('7')" id="income-filter-7" class="px-3 py-1.5 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors income-filter-btn {{ $timeFilter === '7' ? 'active border-primary-500 dark:border-primary-400 bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : '' }}">
                    أسبوعي
                </button>
                <button type="button" onclick="updateIncomeFilter('30')" id="income-filter-30" class="px-3 py-1.5 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors income-filter-btn {{ $timeFilter === '30' ? 'active border-primary-500 dark:border-primary-400 bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : '' }}">
                    شهري
                </button>
                <button type="button" onclick="updateIncomeFilter('custom')" id="income-filter-custom" class="px-3 py-1.5 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors income-filter-btn {{ $timeFilter === 'custom' ? 'active border-primary-500 dark:border-primary-400 bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300' : '' }}">
                    مخصص
                </button>
            </form>
        </div>
        @if($timeFilter === 'custom')
            <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                <form method="GET" action="{{ route('owner.analytics.income') }}" class="flex items-end gap-3 flex-wrap">
                    <input type="hidden" name="time_filter" value="custom">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">من</label>
                        <input type="date" name="date_from" value="{{ $dateFrom ? $dateFrom->format('Y-m-d') : '' }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">إلى</label>
                        <input type="date" name="date_to" value="{{ $dateTo ? $dateTo->format('Y-m-d') : '' }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                    </div>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-primary-500 to-secondary-500 hover:from-primary-600 hover:to-secondary-600 text-white rounded-lg text-sm shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                        تطبيق
                    </button>
                </form>
            </div>
        @endif
        <div class="relative" style="height: 400px;">
            <canvas id="incomeChart"></canvas>
        </div>
    </div>
</div>

