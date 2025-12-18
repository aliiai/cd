{{-- 
    Component: قائمة أحدث مقدمي الخدمة
    Usage: @include('admin.dashboard.partials.recent-owners', ['recentOwners' => $recentOwners])
--}}
@props(['recentOwners'])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">أحدث مقدمي الخدمة</h3>
        <a href="{{ route('admin.users.index') }}" class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 font-medium">
            عرض الكل
        </a>
    </div>
    <div class="space-y-4">
        @forelse($recentOwners as $owner)
            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                        {{ strtoupper(substr($owner->name, 0, 1)) }}
                    </div>
                    <div class="mr-4">
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $owner->name }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $owner->email }}</p>
                    </div>
                </div>
                <div class="text-left">
                    <span class="text-xs text-gray-500 dark:text-gray-400">تاريخ التسجيل</span>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $owner->created_at->format('Y-m-d') }}</p>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-500 dark:text-gray-400 py-8">لا يوجد مقدمي خدمة</p>
        @endforelse
    </div>
</div>

