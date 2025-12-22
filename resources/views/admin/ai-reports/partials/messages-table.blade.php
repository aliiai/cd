@forelse($messages as $message)
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
        <!-- Provider -->
        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
            <div class="text-xs sm:text-sm font-medium text-gray-900 dark:text-gray-100">{{ $message->provider_name ?? 'غير محدد' }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $message->provider_email ?? '' }}</div>
        </td>

        <!-- Recipient -->
        <td class="px-3 sm:px-6 py-3 sm:py-4">
            <div class="text-xs sm:text-sm text-gray-900 dark:text-gray-100">{{ $message->client_name ?? 'غير محدد' }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">
                @if($message->client_phone)
                    {{ $message->client_phone }}
                @endif
                @if($message->client_email)
                    <br>{{ $message->client_email }}
                @endif
            </div>
        </td>

        <!-- Channel -->
        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
            @if($message->channel == 'sms')
                <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 dark:bg-primary-900/30 text-primary-800 dark:text-primary-300">
                    SMS
                </span>
            @else
                <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                    Email
                </span>
            @endif
        </td>

        <!-- Message Preview -->
        <td class="px-3 sm:px-6 py-4">
            <div class="text-xs sm:text-sm text-gray-900 max-w-xs sm:max-w-md lg:max-w-lg break-words whitespace-pre-wrap">
                {{ $message->message_content ?? 'لا يوجد نص' }}
            </div>
        </td>

        <!-- Send Time -->
        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 dark:text-gray-400">
            @if($message->sent_at)
                <div>{{ \Carbon\Carbon::parse($message->sent_at)->format('Y-m-d H:i') }}</div>
            @else
                <div>{{ \Carbon\Carbon::parse($message->campaign_created_at)->format('Y-m-d H:i') }}</div>
                <div class="text-xs text-gray-400 dark:text-gray-500">قيد الانتظار</div>
            @endif
        </td>

        <!-- Status -->
        <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
            @if($message->status == 'sent')
                <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                    نجح
                </span>
            @elseif($message->status == 'failed')
                <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                    فشل
                </span>
                @if($message->error_message)
                    <div class="text-xs text-red-600 dark:text-red-400 mt-1">{{ Str::limit($message->error_message, 50) }}</div>
                @endif
            @else
                <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">
                    قيد الانتظار
                </span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="px-3 sm:px-6 py-4 text-center text-sm sm:text-base text-gray-500 dark:text-gray-400">
            لا توجد رسائل
        </td>
    </tr>
@endforelse

