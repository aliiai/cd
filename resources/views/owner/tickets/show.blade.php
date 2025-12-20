@extends('layouts.owner')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-primary-50 to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 max-w-5xl">
        
        {{-- ========== Header Section ========== --}}
        <div class="mb-8">
            <a href="{{ route('owner.tickets.index') }}" 
               class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 text-sm mb-4 transition-colors duration-200 font-medium">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                العودة للقائمة
            </a>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">الشكوى #{{ $ticket->ticket_number }}</h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400">{{ $ticket->subject }}</p>
                </div>
                <span class="px-4 py-2.5 text-sm font-semibold rounded-full shadow-md {{ $ticket->status_color }}">
                    {{ $ticket->status_text }}
                </span>
            </div>
        </div>

        {{-- ========== Success/Error Messages ========== --}}
        @if(session('success'))
            <div class="mb-6 bg-gradient-to-r from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 border-l-4 border-emerald-500 dark:border-emerald-400 rounded-lg p-4 flex items-center shadow-md">
                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-emerald-800 dark:text-emerald-300 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 border-l-4 border-red-500 dark:border-red-400 rounded-lg p-4 flex items-center shadow-md">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <p class="text-red-800 dark:text-red-300 font-medium">{{ session('error') }}</p>
            </div>
        @endif

        {{-- ========== Ticket Info Card ========== --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-primary-500 to-secondary-500 px-6 py-4">
                <h2 class="text-lg font-bold text-white flex items-center">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    معلومات الشكوى
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-800/20 rounded-lg p-4 border border-primary-200 dark:border-primary-800">
                        <p class="text-xs font-medium text-primary-600 dark:text-primary-400 mb-2">النوع</p>
                        <span class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm border {{ $ticket->type_color }}">
                            {{ $ticket->type_text }}
                        </span>
                    </div>
                    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 rounded-lg p-4 border border-emerald-200 dark:border-emerald-800">
                        <p class="text-xs font-medium text-emerald-600 dark:text-emerald-400 mb-2">الحالة</p>
                        <span class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm border {{ $ticket->status_color }}">
                            {{ $ticket->status_text }}
                        </span>
                    </div>
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                        <p class="text-xs font-medium text-blue-600 dark:text-blue-400 mb-2">تاريخ الإنشاء</p>
                        <p class="text-sm font-semibold text-blue-900 dark:text-blue-100">{{ $ticket->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
                @if($ticket->attachment)
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">المرفق</p>
                        <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank" class="inline-block group">
                            <img src="{{ asset('storage/' . $ticket->attachment) }}" alt="Attachment" class="max-w-xs rounded-lg shadow-md hover:shadow-xl transition-shadow duration-200 group-hover:scale-105 transform transition-transform">
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- ========== Messages Chat ========== --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-primary-500 to-secondary-500 px-6 py-4">
                <h2 class="text-lg font-bold text-white flex items-center">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    المحادثة
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-4 max-h-[500px] overflow-y-auto mb-6 pr-2" id="messagesContainer" style="scrollbar-width: thin; scrollbar-color: rgba(156, 163, 175, 0.5) transparent;">
                    @foreach($ticket->messages as $message)
                        <div class="flex {{ $message->is_admin ? 'justify-start' : 'justify-end' }}">
                            <div class="max-w-2xl {{ $message->is_admin ? 'bg-gray-100 dark:bg-gray-700' : 'bg-gradient-to-br from-primary-500 to-secondary-500 text-white' }} rounded-2xl p-4 shadow-md">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 {{ $message->is_admin ? 'bg-gray-300 dark:bg-gray-600' : 'bg-white/20' }} rounded-full flex items-center justify-center ml-2">
                                            <span class="text-xs font-bold {{ $message->is_admin ? 'text-gray-700 dark:text-gray-300' : 'text-white' }}">
                                                {{ mb_substr($message->is_admin ? 'أ' : $message->user->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <span class="text-xs font-semibold {{ $message->is_admin ? 'text-gray-700 dark:text-gray-300' : 'text-white' }}">
                                            {{ $message->is_admin ? 'الأدمن' : $message->user->name }}
                                        </span>
                                    </div>
                                    <span class="text-xs {{ $message->is_admin ? 'text-gray-500 dark:text-gray-400' : 'text-white/80' }}">{{ $message->created_at->format('Y-m-d H:i') }}</span>
                                </div>
                                <p class="text-sm {{ $message->is_admin ? 'text-gray-900 dark:text-gray-100' : 'text-white' }} whitespace-pre-wrap leading-relaxed">{{ $message->message }}</p>
                                @if($message->attachment)
                                    <div class="mt-3">
                                        <a href="{{ asset('storage/' . $message->attachment) }}" target="_blank" class="inline-block group">
                                            <img src="{{ asset('storage/' . $message->attachment) }}" alt="Attachment" class="max-w-xs rounded-lg shadow-md hover:shadow-xl transition-shadow duration-200 group-hover:scale-105 transform transition-transform">
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Reply Form --}}
                @if($ticket->status !== 'closed')
                    <form action="{{ route('owner.tickets.reply', $ticket) }}" method="POST" enctype="multipart/form-data" class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        @csrf
                        <div class="mb-4">
                            <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">اكتب ردك</label>
                            <textarea name="message" 
                                      id="message"
                                      rows="4"
                                      required
                                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors resize-none"
                                      placeholder="اكتب ردك هنا..."></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="attachment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">مرفق (اختياري)</label>
                            <input type="file" 
                                   id="attachment"
                                   name="attachment" 
                                   accept="image/*"
                                   class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm transition-colors">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">الحد الأقصى 2MB - الصور فقط</p>
                        </div>
                        <div class="flex items-center justify-end">
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-500 to-secondary-500 hover:from-primary-600 hover:to-secondary-600 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                إرسال الرد
                            </button>
                        </div>
                    </form>
                @else
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6 text-center">
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 font-medium">هذه الشكوى مغلقة ولا يمكن إضافة ردود جديدة.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- ========== Actions ========== --}}
        @if($ticket->status !== 'closed')
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6">
                    <form action="{{ route('owner.tickets.close', $ticket) }}" method="POST" class="close-ticket-form">
                        @csrf
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">إغلاق الشكوى</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">يمكنك إغلاق الشكوى إذا تم حل المشكلة</p>
                            </div>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                إغلاق الشكوى
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Scroll to bottom of messages
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('messagesContainer');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    });
</script>
@endpush
@endsection
