{{-- 
    Component: استخدام قنوات التواصل
    Usage: @include('owner.dashboard.partials.channel-usage-chart', [
        'channelUsageData' => $channelUsageData
    ])
--}}
@props(['channelUsageData'])

<div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-5 md:p-6">
    <div class="flex items-center justify-between mb-4 sm:mb-6 lg:mb-6">
        <h3 class="text-base sm:text-lg lg:text-xl font-bold text-gray-900 dark:text-gray-100 flex-1 min-w-0">استخدام قنوات التواصل</h3>
        <button class="p-1 sm:p-1.5 lg:p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors flex-shrink-0 ml-2" title="خيارات">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
            </svg>
        </button>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 lg:gap-6">
        <div class="relative h-[200px] lg:h-[250px]">
            <canvas id="channelUsageChart"></canvas>
        </div>
        <div class="flex flex-col justify-center">
            <div class="space-y-3 sm:space-y-4 lg:space-y-4">
                <div class="flex items-center justify-between p-3 sm:p-4 lg:p-4 bg-primary-50 dark:bg-primary-900/30 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/40 transition-colors">
                    <div class="flex items-center">
                        <div class="w-3 h-3 sm:w-4 sm:h-4 lg:w-4 lg:h-4 bg-primary-500 rounded-full ml-2 sm:ml-3 lg:ml-3"></div>
                        <span class="text-sm sm:text-base lg:text-base font-medium text-gray-900 dark:text-gray-100">SMS</span>
                    </div>
                    <span class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($channelUsageData['counts'][0]) }}</span>
                </div>
                <div class="flex items-center justify-between p-3 sm:p-4 lg:p-4 bg-secondary-50 dark:bg-secondary-900/30 rounded-lg hover:bg-secondary-100 dark:hover:bg-secondary-900/40 transition-colors">
                    <div class="flex items-center">
                        <div class="w-3 h-3 sm:w-4 sm:h-4 lg:w-4 lg:h-4 bg-secondary-500 rounded-full ml-2 sm:ml-3 lg:ml-3"></div>
                        <span class="text-sm sm:text-base lg:text-base font-medium text-gray-900 dark:text-gray-100">Email</span>
                    </div>
                    <span class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($channelUsageData['counts'][1]) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

