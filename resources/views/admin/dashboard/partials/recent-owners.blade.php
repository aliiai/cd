{{-- 
    Component: قائمة أحدث مقدمي الخدمة
    Usage: @include('admin.dashboard.partials.recent-owners', ['recentOwners' => $recentOwners])
--}}
@props(['recentOwners'])

<div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-5 md:p-6">
    <div class="flex items-center justify-between mb-4 sm:mb-6 lg:mb-6">
        <h3 class="text-base sm:text-lg lg:text-xl font-bold text-gray-900 dark:text-gray-100">أحدث مقدمي الخدمة</h3>
        <a href="{{ route('admin.users.index') }}" class="text-xs sm:text-sm lg:text-sm text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 font-medium">
            عرض الكل
        </a>
    </div>
    <div class="space-y-3 sm:space-y-4 lg:space-y-4">
        @forelse($recentOwners as $owner)
            <div class="flex items-center justify-between p-3 sm:p-4 lg:p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <div class="flex items-center flex-1 min-w-0">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-12 lg:h-12 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white font-bold text-base sm:text-lg lg:text-lg flex-shrink-0">
                        {{ strtoupper(substr($owner->name, 0, 1)) }}
                    </div>
                    <div class="mr-3 sm:mr-4 lg:mr-4 min-w-0 flex-1">
                        <p class="font-semibold text-sm sm:text-base lg:text-base text-gray-900 dark:text-gray-100 truncate">{{ $owner->name }}</p>
                        <p class="text-xs sm:text-sm lg:text-sm text-gray-500 dark:text-gray-400 truncate">{{ $owner->email }}</p>
                    </div>
                </div>
                <div class="text-left flex-shrink-0 mr-2 sm:mr-0">
                    <span class="text-xs text-gray-500 dark:text-gray-400 hidden sm:block">تاريخ التسجيل</span>
                    <p class="text-xs sm:text-sm lg:text-sm font-medium text-gray-700 dark:text-gray-300">{{ $owner->created_at->format('Y-m-d') }}</p>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-500 dark:text-gray-400 py-6 sm:py-8 lg:py-8 text-sm sm:text-base">لا يوجد مقدمي خدمة</p>
        @endforelse
    </div>
</div>

