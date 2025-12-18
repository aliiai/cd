@forelse($messages as $message)
    <tr class="hover:bg-gray-50 transition-colors duration-150">
        <!-- Provider -->
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-medium text-gray-900">{{ $message->provider_name ?? 'غير محدد' }}</div>
            <div class="text-xs text-gray-500">{{ $message->provider_email ?? '' }}</div>
        </td>

        <!-- Recipient -->
        <td class="px-6 py-4">
            <div class="text-sm text-gray-900">{{ $message->client_name ?? 'غير محدد' }}</div>
            <div class="text-xs text-gray-500">
                @if($message->client_phone)
                    {{ $message->client_phone }}
                @endif
                @if($message->client_email)
                    <br>{{ $message->client_email }}
                @endif
            </div>
        </td>

        <!-- Channel -->
        <td class="px-6 py-4 whitespace-nowrap">
            @if($message->channel == 'sms')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                    SMS
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Email
                </span>
            @endif
        </td>

        <!-- Message Preview -->
        <td class="px-6 py-4">
            <div class="text-sm text-gray-900 max-w-xs">
                {{ Str::limit($message->message_content ?? 'لا يوجد نص', 100) }}
            </div>
        </td>

        <!-- Send Time -->
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            @if($message->sent_at)
                <div>{{ \Carbon\Carbon::parse($message->sent_at)->format('Y-m-d H:i') }}</div>
            @else
                <div>{{ \Carbon\Carbon::parse($message->campaign_created_at)->format('Y-m-d H:i') }}</div>
                <div class="text-xs text-gray-400">قيد الانتظار</div>
            @endif
        </td>

        <!-- Status -->
        <td class="px-6 py-4 whitespace-nowrap">
            @if($message->status == 'sent')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    نجح
                </span>
            @elseif($message->status == 'failed')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    فشل
                </span>
                @if($message->error_message)
                    <div class="text-xs text-red-600 mt-1">{{ Str::limit($message->error_message, 50) }}</div>
                @endif
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    قيد الانتظار
                </span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
            لا توجد رسائل
        </td>
    </tr>
@endforelse

