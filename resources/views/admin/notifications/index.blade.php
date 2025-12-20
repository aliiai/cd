@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-primary-50 to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12">
    <div class="w-full mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">الإشعارات والتنبيهات</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400">جميع الإشعارات والتنبيهات الخاصة بك</p>
        </div>

        <!-- Stats and Actions -->
        @php
            $unreadCount = $notifications->filter(fn($n) => is_null($n->read_at))->count();
            $readCount = $notifications->count() - $unreadCount;
        @endphp
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Unread Notifications Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border-2 border-primary-200 dark:border-primary-800 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">غير المقروءة</p>
                        <p class="text-3xl font-bold text-primary-600 dark:text-primary-400">{{ $unreadCount }}</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Read Notifications Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border-2 border-secondary-200 dark:border-secondary-800 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">المقروءة</p>
                        <p class="text-3xl font-bold text-secondary-600 dark:text-secondary-400">{{ $readCount }}</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-secondary-500 to-secondary-600 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Notifications Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border-2 border-primary-300 dark:border-primary-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">الإجمالي</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $notifications->total() }}</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-primary-400 to-secondary-500 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h10m-7 4h7M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        @if($unreadCount > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-primary-500 rounded-full ml-3 animate-pulse"></div>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            لديك <span class="font-bold text-primary-600 dark:text-primary-400">{{ $unreadCount }}</span> إشعار غير مقروء
                        </p>
                    </div>
                    <form method="POST" action="{{ route('admin.notifications.read-all') }}">
                        @csrf
                        <button 
                            type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 dark:from-primary-500 dark:to-primary-600 text-white font-semibold rounded-lg hover:from-primary-700 hover:to-primary-800 dark:hover:from-primary-600 dark:hover:to-primary-700 transition-all duration-200 shadow-md hover:shadow-lg flex items-center"
                        >
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            تحديد الكل كمقروء
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <!-- Notifications List -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-primary-600 to-primary-700 dark:from-primary-700 dark:to-primary-800 px-6 py-4">
                <h2 class="text-xl font-bold text-white">قائمة الإشعارات</h2>
            </div>
            
            @forelse($notifications as $notification)
                @php
                    $data = $notification->data;
                    $isUnread = is_null($notification->read_at);
                    $icon = $data['icon'] ?? 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9';
                @endphp
                
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 {{ $isUnread ? 'bg-primary-50/50 dark:bg-primary-900/10 border-r-4 border-primary-500' : '' }}">
                    <div class="flex items-start gap-4">
                        <!-- Icon -->
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center {{ $isUnread ? 'bg-gradient-to-br from-primary-500 to-primary-600' : 'bg-gray-100 dark:bg-gray-700' }}">
                                <svg class="w-6 h-6 {{ $isUnread ? 'text-white' : 'text-gray-400 dark:text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <a 
                                href="{{ $data['url'] ?? '#' }}"
                                class="block group"
                            >
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            @if($isUnread)
                                                <span class="h-2 w-2 bg-primary-500 rounded-full flex-shrink-0 animate-pulse"></span>
                                            @endif
                                            <p class="text-base font-semibold text-gray-900 dark:text-gray-100 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors {{ $isUnread ? '' : 'opacity-75' }}">
                                                {{ $data['title'] ?? 'إشعار' }}
                                            </p>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">
                                            {{ $data['message'] ?? '' }}
                                        </p>
                                        <div class="flex items-center gap-4 mt-3">
                                            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>{{ $notification->created_at->format('Y-m-d') }}</span>
                                            </div>
                                            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>{{ $notification->created_at->format('H:i') }}</span>
                                            </div>
                                            <span class="text-xs text-gray-400 dark:text-gray-500">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Actions -->
                        <div class="flex-shrink-0 flex items-start gap-2">
                            @if($isUnread)
                                <form method="POST" action="{{ route('admin.notifications.read', $notification->id) }}" class="inline">
                                    @csrf
                                    <button 
                                        type="submit"
                                        class="px-4 py-2 text-xs font-medium text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg transition-all duration-200 flex items-center"
                                        title="تحديد كمقروء"
                                    >
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        تحديد كمقروء
                                    </button>
                                </form>
                            @else
                                <div class="px-4 py-2 text-xs font-medium text-gray-400 dark:text-gray-500 flex items-center">
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    مقروء
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-16 text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-primary-100 to-secondary-100 dark:from-primary-900/20 dark:to-secondary-900/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-primary-500 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">لا توجد إشعارات</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">ستظهر الإشعارات هنا عند وصولها</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="mt-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-4">
                    {{ $notifications->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
