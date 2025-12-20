{{-- 
    Component: رسم بياني توزيع حالات الديون
    Usage: @include('owner.dashboard.partials.status-distribution-chart')
--}}

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3 space-x-reverse">
            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">توزيع حالات الديون</h3>
            <button class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors" title="خيارات">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                </svg>
            </button>
        </div>
    </div>
    <div class="relative" style="height: 400px;">
        <canvas id="statusDistributionChart"></canvas>
    </div>
</div>

