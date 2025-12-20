@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-primary-50 to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12">
    <div class="w-full mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.tickets.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 hover:bg-primary-50 dark:hover:bg-gray-700">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        العودة للقائمة
                    </a>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">الشكوى #{{ $ticket->ticket_number }}</h1>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $ticket->subject }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border-2 border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 px-4 py-3 rounded-lg shadow-md">
                <div class="flex items-center">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border-2 border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 px-4 py-3 rounded-lg shadow-md">
                <div class="flex items-center">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Ticket Info Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-primary-600 via-primary-500 to-secondary-600 px-6 py-4">
                <h2 class="text-lg font-bold text-white">معلومات الشكوى</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- User Info -->
                    <div class="bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-800/20 rounded-lg p-4 border border-primary-200 dark:border-primary-800">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center text-white font-semibold">
                                {{ substr($ticket->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">المستخدم</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $ticket->user->name }}</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $ticket->user->email }}</p>
                    </div>

                    <!-- Type -->
                    <div class="bg-gradient-to-br from-secondary-50 to-secondary-100 dark:from-secondary-900/20 dark:to-secondary-800/20 rounded-lg p-4 border border-secondary-200 dark:border-secondary-800">
                        <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">النوع</p>
                        <span class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm {{ $ticket->type_color }}">
                            {{ $ticket->type_text }}
                        </span>
                    </div>

                    <!-- Status -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                        <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">الحالة</p>
                        <span class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm {{ $ticket->status_color }}">
                            {{ $ticket->status_text }}
                        </span>
                    </div>

                    <!-- Created Date -->
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700/50 dark:to-gray-600/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">تاريخ الإنشاء</p>
                        </div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $ticket->created_at->format('Y-m-d') }}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ $ticket->created_at->format('H:i') }}</p>
                    </div>
                </div>

                <!-- Description -->
                @if($ticket->description)
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">الوصف</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 whitespace-pre-wrap bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">{{ $ticket->description }}</p>
                    </div>
                @endif

                <!-- Attachment -->
                @if($ticket->attachment)
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">المرفق</p>
                        <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank" class="inline-block group">
                            <div class="relative overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-shadow duration-200">
                                <img src="{{ asset('storage/' . $ticket->attachment) }}" alt="Attachment" class="max-w-md rounded-lg">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-opacity duration-200 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Status Update Form -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-primary-500/10 to-secondary-500/10 dark:from-primary-900/20 dark:to-secondary-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">تحديث الحالة</h2>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.tickets.update-status', $ticket) }}" method="POST" class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    @csrf
                    <label for="status" class="text-sm font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap">تغيير الحالة:</label>
                    <select name="status" 
                            id="status"
                            class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100">
                        <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>مفتوحة</option>
                        <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>قيد المعالجة</option>
                        <option value="waiting_user" {{ $ticket->status == 'waiting_user' ? 'selected' : '' }}>في انتظار المستخدم</option>
                        <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>مغلقة</option>
                    </select>
                    <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 font-semibold">
                        تحديث الحالة
                    </button>
                </form>
            </div>
        </div>

        <!-- Messages Chat -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-primary-600 via-primary-500 to-secondary-600 px-6 py-4">
                <h2 class="text-lg font-bold text-white">المحادثة</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4 max-h-96 overflow-y-auto mb-6 pr-2" id="messagesContainer">
                    @foreach($ticket->messages as $message)
                        <div class="flex {{ $message->is_admin ? 'justify-start' : 'justify-end' }}">
                            <div class="max-w-2xl rounded-xl p-4 shadow-md {{ $message->is_admin ? 'bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/30 dark:to-primary-800/30 border border-primary-200 dark:border-primary-800' : 'bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 border border-gray-200 dark:border-gray-600' }}">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full {{ $message->is_admin ? 'bg-gradient-to-br from-primary-500 to-primary-600' : 'bg-gradient-to-br from-gray-400 to-gray-500' }} flex items-center justify-center text-white text-xs font-bold">
                                            {{ $message->is_admin ? 'أ' : substr($message->user->name, 0, 1) }}
                                        </div>
                                        <span class="text-xs font-semibold {{ $message->is_admin ? 'text-primary-700 dark:text-primary-300' : 'text-gray-700 dark:text-gray-300' }}">
                                            {{ $message->is_admin ? 'الأدمن' : $message->user->name }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $message->created_at->format('Y-m-d H:i') }}</span>
                                </div>
                                <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $message->message }}</p>
                                @if($message->attachment)
                                    <div class="mt-3">
                                        <a href="{{ asset('storage/' . $message->attachment) }}" target="_blank" class="inline-block group">
                                            <div class="relative overflow-hidden rounded-lg shadow-md hover:shadow-xl transition-shadow duration-200">
                                                <img src="{{ asset('storage/' . $message->attachment) }}" alt="Attachment" class="max-w-xs rounded-lg">
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-opacity duration-200 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Reply Form -->
                @if($ticket->status !== 'closed')
                    <form action="{{ route('admin.tickets.reply', $ticket) }}" method="POST" enctype="multipart/form-data" class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        @csrf
                        <div class="mb-4">
                            <label for="message" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">الرد</label>
                            <textarea name="message" 
                                      id="message"
                                      rows="4"
                                      required
                                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100"
                                      placeholder="اكتب ردك هنا..."></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="attachment" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">مرفق (اختياري)</label>
                            <input type="file" 
                                   name="attachment" 
                                   id="attachment"
                                   accept="image/*"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">الحد الأقصى 2MB - الصور فقط</p>
                        </div>
                        <div class="flex items-center justify-end">
                            <button type="submit" 
                                    class="px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 font-semibold flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                إرسال الرد
                            </button>
                        </div>
                    </form>
                @else
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 text-center">
                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-gray-600 dark:text-gray-400 font-medium">هذه الشكوى مغلقة ولا يمكن إضافة ردود جديدة.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('messagesContainer');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    });
</script>
@endpush
@endsection

