@forelse($messages as $message)
    <tr class="hover:bg-primary-50 dark:hover:bg-gray-700 transition-colors duration-200">
        {{-- Client --}}
        <td class="px-6 py-4">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center text-white font-bold text-sm ml-3">
                    {{ mb_substr($message->client_name ?? 'غير محدد', 0, 1) }}
                </div>
                <div>
                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $message->client_name ?? 'غير محدد' }}</div>
                    @if($message->client_phone)
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $message->client_phone }}</div>
                    @endif
                </div>
            </div>
        </td>

        {{-- Channel --}}
        <td class="px-6 py-4 whitespace-nowrap">
            @if($message->channel == 'sms')
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    SMS
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Email
                </span>
            @endif
        </td>

        {{-- Preview --}}
        <td class="px-6 py-4">
            <div class="text-sm text-gray-900 dark:text-gray-100 max-w-xs truncate" title="{{ $message->message_content ?? 'لا يوجد نص' }}">
                {{ Str::limit($message->message_content ?? 'لا يوجد نص', 80) }}
            </div>
        </td>

        {{-- Date --}}
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900 dark:text-gray-100">
                @if($message->sent_at)
                    {{ \Carbon\Carbon::parse($message->sent_at)->format('Y-m-d H:i') }}
                @else
                    {{ \Carbon\Carbon::parse($message->campaign_created_at)->format('Y-m-d H:i') }}
                @endif
            </div>
        </td>

        {{-- Status --}}
        <td class="px-6 py-4 whitespace-nowrap">
            @if($message->status == 'sent')
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    نجح
                </span>
            @elseif($message->status == 'failed')
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-800">
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    فشل
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800">
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    قيد الانتظار
                </span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="px-6 py-12 text-center">
            <div class="flex flex-col items-center justify-center">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <p class="text-gray-500 dark:text-gray-400 font-medium">لا توجد رسائل</p>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">جرب تغيير الفلاتر للعثور على رسائل</p>
            </div>
        </td>
    </tr>
@endforelse
