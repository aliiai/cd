{{-- 
    Component: قائمة أحدث الحملات
    Usage: @include('admin.dashboard.partials.recent-campaigns', ['recentCampaigns' => $recentCampaigns])
--}}
@props(['recentCampaigns'])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">أحدث الحملات</h3>
        <a href="{{ route('admin.ai-reports.campaigns') }}" class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 font-medium">
            عرض الكل
        </a>
    </div>
    <div class="space-y-4">
        @forelse($recentCampaigns as $campaign)
            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-secondary-400 to-secondary-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="mr-4">
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $campaign->campaign_number }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $campaign->owner->name ?? 'غير معروف' }}</p>
                    </div>
                </div>
                <div class="text-left">
                    <span class="inline-block px-2 py-1 text-xs font-medium rounded-full {{ $campaign->status_color }}">
                        {{ $campaign->status_text }}
                    </span>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $campaign->created_at->diffForHumans() }}</p>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-500 dark:text-gray-400 py-8">لا توجد حملات</p>
        @endforelse
    </div>
</div>

