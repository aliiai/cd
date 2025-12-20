{{-- 
    Component: استخدام قنوات التواصل
    Usage: @include('owner.dashboard.partials.channel-usage-chart', [
        'channelUsageData' => $channelUsageData
    ])
--}}
@props(['channelUsageData'])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">استخدام قنوات التواصل</h3>
        <button class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors" title="خيارات">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
            </svg>
        </button>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="relative" style="height: 250px;">
            <canvas id="channelUsageChart"></canvas>
        </div>
        <div class="flex flex-col justify-center">
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-primary-50 dark:bg-primary-900/30 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/40 transition-colors">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-primary-500 rounded-full ml-3"></div>
                        <span class="font-medium text-gray-900 dark:text-gray-100">SMS</span>
                    </div>
                    <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($channelUsageData['counts'][0]) }}</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-secondary-50 dark:bg-secondary-900/30 rounded-lg hover:bg-secondary-100 dark:hover:bg-secondary-900/40 transition-colors">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-secondary-500 rounded-full ml-3"></div>
                        <span class="font-medium text-gray-900 dark:text-gray-100">Email</span>
                    </div>
                    <span class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($channelUsageData['counts'][1]) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

