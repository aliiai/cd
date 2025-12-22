{{-- 
    Component: قائمة أحدث الحملات
    Usage: @include('admin.dashboard.partials.recent-campaigns', ['recentCampaigns' => $recentCampaigns])
--}}
@props(['recentCampaigns'])

<div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-5 md:p-6">
    <div class="flex items-center justify-between mb-4 sm:mb-6 lg:mb-6">
        <h3 class="text-base sm:text-lg lg:text-xl font-bold text-gray-900 dark:text-gray-100">أحدث الحملات</h3>
        <a href="{{ route('admin.ai-reports.campaigns') }}" class="text-xs sm:text-sm lg:text-sm text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 font-medium">
            عرض الكل
        </a>
    </div>
    <div class="space-y-3 sm:space-y-4 lg:space-y-4">
        @forelse($recentCampaigns as $campaign)
            <div class="flex items-center justify-between p-3 sm:p-4 lg:p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <div class="flex items-center flex-1 min-w-0">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-12 lg:h-12 bg-gradient-to-br from-secondary-400 to-secondary-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 lg:w-6 lg:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="mr-3 sm:mr-4 lg:mr-4 min-w-0 flex-1">
                        <p class="font-semibold text-sm sm:text-base lg:text-base text-gray-900 dark:text-gray-100 truncate">{{ $campaign->campaign_number }}</p>
                        <p class="text-xs sm:text-sm lg:text-sm text-gray-500 dark:text-gray-400 truncate">{{ $campaign->owner->name ?? 'غير معروف' }}</p>
                    </div>
                </div>
                <div class="text-left flex-shrink-0 mr-2 sm:mr-0">
                    <span class="inline-block px-2 py-1 text-xs font-medium rounded-full {{ $campaign->status_color }}">
                        {{ $campaign->status_text }}
                    </span>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 hidden sm:block">{{ $campaign->created_at->diffForHumans() }}</p>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-500 dark:text-gray-400 py-6 sm:py-8 lg:py-8 text-sm sm:text-base">لا توجد حملات</p>
        @endforelse
    </div>
</div>

