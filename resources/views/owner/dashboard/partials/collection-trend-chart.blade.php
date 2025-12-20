{{-- 
    Component: رسم بياني تطور التحصيل عبر الزمن
    Usage: @include('owner.dashboard.partials.collection-trend-chart', [
        'collectionTrendData' => $collectionTrendData
    ])
--}}
@props(['collectionTrendData'])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 lg:col-span-2">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3 space-x-reverse">
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">تطور التحصيل عبر الزمن</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">المبالغ المحصلة خلال الفترة المحددة</p>
            </div>
            <button class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors" title="خيارات">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                </svg>
            </button>
        </div>
        <!-- Filter Buttons -->
        <div class="flex items-center space-x-2 space-x-reverse">
            <button onclick="updateCollectionChart('daily')" id="collection-filter-daily" class="px-3 py-1.5 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors collection-filter-btn">
                يومي
            </button>
            <button onclick="updateCollectionChart('weekly')" id="collection-filter-weekly" class="px-3 py-1.5 text-sm font-medium rounded-lg border border-primary-500 dark:border-primary-400 bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 collection-filter-btn active">
                أسبوعي
            </button>
            <button onclick="updateCollectionChart('monthly')" id="collection-filter-monthly" class="px-3 py-1.5 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors collection-filter-btn">
                شهري
            </button>
            <button onclick="updateCollectionChart('yearly')" id="collection-filter-yearly" class="px-3 py-1.5 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors collection-filter-btn">
                سنوي
            </button>
        </div>
    </div>
    <div class="relative" style="height: 400px;">
        <canvas id="collectionTrendChart"></canvas>
    </div>
</div>

