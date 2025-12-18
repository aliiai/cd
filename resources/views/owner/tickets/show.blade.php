@extends('layouts.owner')

@section('content')
<div class="py-12">
    <div class="w-full mx-auto sm:px-6 lg:px-8 max-w-5xl">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">الشكوى #{{ $ticket->ticket_number }}</h1>
                    <p class="mt-2 text-sm text-gray-600">{{ $ticket->subject }}</p>
                </div>
                <a href="{{ route('owner.tickets.index') }}" 
                   class="text-primary-600 hover:text-primary-800 font-medium">
                    ← العودة للقائمة
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Ticket Info Card -->
        <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-gray-700 to-gray-600 px-6 py-4">
                <h2 class="text-lg font-bold text-white">معلومات الشكوى</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">النوع</p>
                        <span class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm {{ $ticket->type_color }}">
                            {{ $ticket->type_text }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">الحالة</p>
                        <span class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm {{ $ticket->status_color }}">
                            {{ $ticket->status_text }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">تاريخ الإنشاء</p>
                        <p class="text-sm text-gray-900">{{ $ticket->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
                @if($ticket->attachment)
                    <div class="mt-4">
                        <p class="text-sm font-medium text-gray-600 mb-2">المرفق</p>
                        <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank" class="text-primary-600 hover:text-primary-800">
                            <img src="{{ asset('storage/' . $ticket->attachment) }}" alt="Attachment" class="max-w-xs rounded-lg shadow-md">
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Messages Chat -->
        <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-gray-700 to-gray-600 px-6 py-4">
                <h2 class="text-lg font-bold text-white">المحادثة</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4 max-h-96 overflow-y-auto mb-6" id="messagesContainer">
                    @foreach($ticket->messages as $message)
                        <div class="flex {{ $message->is_admin ? 'justify-start' : 'justify-end' }}">
                            <div class="max-w-2xl {{ $message->is_admin ? 'bg-gray-100' : 'bg-primary-100' }} rounded-lg p-4 shadow-sm">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-semibold {{ $message->is_admin ? 'text-gray-700' : 'text-primary-700' }}">
                                        {{ $message->is_admin ? 'الأدمن' : $message->user->name }}
                                    </span>
                                    <span class="text-xs text-gray-500">{{ $message->created_at->format('Y-m-d H:i') }}</span>
                                </div>
                                <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $message->message }}</p>
                                @if($message->attachment)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $message->attachment) }}" target="_blank" class="text-primary-600 hover:text-primary-800">
                                            <img src="{{ asset('storage/' . $message->attachment) }}" alt="Attachment" class="max-w-xs rounded-lg shadow-md">
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Reply Form -->
                @if($ticket->status !== 'closed')
                    <form action="{{ route('owner.tickets.reply', $ticket) }}" method="POST" enctype="multipart/form-data" class="border-t pt-4">
                        @csrf
                        <div class="mb-4">
                            <textarea name="message" 
                                      rows="3"
                                      required
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                      placeholder="اكتب ردك هنا..."></textarea>
                        </div>
                        <div class="mb-4">
                            <input type="file" 
                                   name="attachment" 
                                   accept="image/*"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            <p class="mt-1 text-xs text-gray-500">الحد الأقصى 2MB - الصور فقط</p>
                        </div>
                        <div class="flex items-center justify-end">
                            <button type="submit" 
                                    class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                                إرسال الرد
                            </button>
                        </div>
                    </form>
                @else
                    <div class="border-t pt-4 text-center text-gray-500">
                        <p>هذه الشكوى مغلقة ولا يمكن إضافة ردود جديدة.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        @if($ticket->status !== 'closed')
            <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="p-6">
                    <form action="{{ route('owner.tickets.close', $ticket) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من إغلاق هذه الشكوى؟');">
                        @csrf
                        <button type="submit" 
                                class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                            إغلاق الشكوى
                        </button>
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

