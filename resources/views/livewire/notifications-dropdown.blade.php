<div class="relative" x-data="{ open: false }" wire:init="loadNotifications" wire:poll.5s="loadNotifications" x-init="$watch('$wire.unreadCount', value => console.log('Unread count:', value))">
    <!-- Notification Button -->
    <button 
        type="button"
        @click="open = !open"
        class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-full transition-colors duration-200"
        title="Notifications"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        
        <!-- Notification Badge -->
        @if($unreadCount > 0)
            <span class="absolute top-1 right-1 flex items-center justify-center h-5 w-5 text-xs font-bold text-white bg-red-500 rounded-full">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown Menu -->
    <div 
        x-show="open"
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg z-50 border border-gray-200"
        style="display: none;"
    >
        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-gray-900">الإشعارات</h3>
            @if($unreadCount > 0)
                <button 
                    wire:click="markAllAsRead"
                    class="text-xs text-blue-600 hover:text-blue-800 font-medium"
                >
                    تحديد الكل كمقروء
                </button>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="max-h-96 overflow-y-auto">
            @forelse($notifications as $notification)
                @php
                    $data = $notification->data;
                    $isUnread = is_null($notification->read_at);
                @endphp
                <div 
                    class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-150 {{ $isUnread ? 'bg-blue-50' : '' }}"
                    wire:key="notification-{{ $notification->id }}"
                >
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <a 
                                href="{{ $data['url'] ?? '#' }}"
                                wire:click="markAsRead('{{ $notification->id }}')"
                                class="block"
                            >
                                <p class="text-sm font-medium text-gray-900 {{ $isUnread ? 'font-bold' : '' }}">
                                    {{ $data['title'] ?? 'إشعار' }}
                                </p>
                                <p class="text-xs text-gray-600 mt-1 line-clamp-2">
                                    {{ $data['message'] ?? '' }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </a>
                        </div>
                        <div class="flex items-center space-x-2 mr-2">
                            @if($isUnread)
                                <span class="h-2 w-2 bg-blue-500 rounded-full"></span>
                            @endif
                            <button 
                                wire:click="deleteNotification('{{ $notification->id }}')"
                                class="text-gray-400 hover:text-red-600 transition-colors duration-200"
                                title="حذف"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-4 py-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">لا توجد إشعارات</p>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        @if($notifications->count() > 0)
            <div class="px-4 py-3 border-t border-gray-200 text-center">
                <a 
                    href="{{ Auth::user()->hasRole('admin') ? route('admin.notifications.index') : route('owner.notifications.index') }}"
                    class="text-sm text-blue-600 hover:text-blue-800 font-medium"
                >
                    عرض جميع الإشعارات
                </a>
            </div>
        @endif
    </div>
</div>
