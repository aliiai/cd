@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="w-full mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">الإشعارات</h1>
                <p class="text-gray-600 mt-2">جميع الإشعارات والتنبيهات</p>
            </div>
            @if($notifications->filter(fn($n) => is_null($n->read_at))->count() > 0)
                <form method="POST" action="{{ route('admin.notifications.read-all') }}">
                    @csrf
                    <button 
                        type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200"
                    >
                        تحديد الكل كمقروء
                    </button>
                </form>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            @forelse($notifications as $notification)
                @php
                    $data = $notification->data;
                    $isUnread = is_null($notification->read_at);
                @endphp
                <div class="px-6 py-4 border-b border-gray-200 hover:bg-gray-50 transition-colors duration-150 {{ $isUnread ? 'bg-blue-50' : '' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <a 
                                href="{{ $data['url'] ?? '#' }}"
                                class="block"
                            >
                                <div class="flex items-start">
                                    @if($isUnread)
                                        <span class="h-2 w-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                    @endif
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 {{ $isUnread ? 'font-bold' : '' }}">
                                            {{ $data['title'] ?? 'إشعار' }}
                                        </p>
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ $data['message'] ?? '' }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-2">
                                            {{ $notification->created_at->format('Y-m-d H:i') }} 
                                            ({{ $notification->created_at->diffForHumans() }})
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="flex items-center space-x-2 mr-4">
                            @if($isUnread)
                                <form method="POST" action="{{ route('admin.notifications.read', $notification->id) }}">
                                    @csrf
                                    <button 
                                        type="submit"
                                        class="text-xs text-blue-600 hover:text-blue-800 font-medium"
                                        title="تحديد كمقروء"
                                    >
                                        تحديد كمقروء
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">لا توجد إشعارات</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
