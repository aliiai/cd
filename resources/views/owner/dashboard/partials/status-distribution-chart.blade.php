{{-- 
    Component: رسم بياني توزيع حالات الديون
    Usage: @include('owner.dashboard.partials.status-distribution-chart')
--}}

<div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-5 md:p-6">
    <div class="flex items-center justify-between mb-4 sm:mb-6 lg:mb-6">
        <div class="flex items-center space-x-2 sm:space-x-3 space-x-reverse flex-1 min-w-0">
            <h3 class="text-base sm:text-lg lg:text-xl font-bold text-gray-900 dark:text-gray-100">توزيع حالات الديون</h3>
            <button class="p-1 sm:p-1.5 lg:p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors flex-shrink-0" title="خيارات">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                </svg>
            </button>
        </div>
    </div>
    <div class="relative h-[250px] lg:h-[400px]">
        <canvas id="statusDistributionChart"></canvas>
    </div>
</div>

