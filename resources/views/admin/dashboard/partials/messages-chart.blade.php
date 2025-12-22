{{-- 
    Component: رسم بياني لنشاط الرسائل
    Usage: @include('admin.dashboard.partials.messages-chart')
--}}

<div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-5 md:p-6">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 lg:gap-4 mb-4 sm:mb-6 lg:mb-6 min-w-0">
        <div class="flex items-center justify-between lg:flex-shrink-0 lg:mr-4 min-w-0">
            <h3 class="text-base sm:text-lg lg:text-xl font-bold text-gray-900 dark:text-gray-100 lg:whitespace-nowrap truncate">نشاط الرسائل</h3>
            <button class="p-1 sm:p-1.5 lg:p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors flex-shrink-0 ml-2 lg:hidden" title="خيارات">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                </svg>
            </button>
        </div>
        <div class="flex items-center lg:flex-shrink-0 min-w-0 gap-2 lg:gap-2">
            <!-- Filter Buttons -->
            <div class="flex items-center space-x-1 sm:space-x-2 space-x-reverse flex-wrap gap-2 min-w-0">
                <button onclick="updateChart('daily')" id="filter-daily" class="px-2.5 py-1.5 sm:px-3 sm:py-1.5 lg:px-3 lg:py-1.5 text-xs sm:text-sm lg:text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors filter-btn whitespace-nowrap flex-shrink-0">
                    يومي
                </button>
                <button onclick="updateChart('weekly')" id="filter-weekly" class="px-2.5 py-1.5 sm:px-3 sm:py-1.5 lg:px-3 lg:py-1.5 text-xs sm:text-sm lg:text-sm font-medium rounded-lg border border-primary-500 dark:border-primary-400 bg-primary-50 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 filter-btn active whitespace-nowrap flex-shrink-0">
                    أسبوعي
                </button>
                <button onclick="updateChart('monthly')" id="filter-monthly" class="px-2.5 py-1.5 sm:px-3 sm:py-1.5 lg:px-3 lg:py-1.5 text-xs sm:text-sm lg:text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors filter-btn whitespace-nowrap flex-shrink-0">
                    شهري
                </button>
                <button onclick="updateChart('yearly')" id="filter-yearly" class="px-2.5 py-1.5 sm:px-3 sm:py-1.5 lg:px-3 lg:py-1.5 text-xs sm:text-sm lg:text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors filter-btn whitespace-nowrap flex-shrink-0">
                    سنوي
                </button>
            </div>
            <!-- <button class="hidden lg:block p-1 sm:p-1.5 lg:p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors flex-shrink-0" title="خيارات">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                </svg>
            </button> -->
        </div>
    </div>
    <div class="relative h-[250px] lg:h-[400px]">
        <canvas id="messagesChart"></canvas>
    </div>
</div>

