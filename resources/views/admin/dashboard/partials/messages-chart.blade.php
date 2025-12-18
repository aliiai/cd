{{-- 
    Component: رسم بياني لنشاط الرسائل
    Usage: @include('admin.dashboard.partials.messages-chart')
--}}

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3 space-x-reverse">
            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">نشاط الرسائل</h3>
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

