@forelse($messages as $message)
    <tr class="hover:bg-gray-50 transition-colors duration-150">
        <!-- Client -->
        <td class="px-6 py-4">
            <div class="text-sm font-medium text-gray-900">{{ $message->client_name ?? 'غير محدد' }}</div>
            <div class="text-xs text-gray-500">
                @if($message->client_phone)
                    {{ $message->client_phone }}
                @endif
            </div>
        </td>

        <!-- Channel -->
        <td class="px-6 py-4 whitespace-nowrap">
            @if($message->channel == 'sms')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    SMS
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Email
                </span>
            @endif
        </td>

        <!-- Preview -->
        <td class="px-6 py-4">
            <div class="text-sm text-gray-900 max-w-xs">
                {{ Str::limit($message->message_content ?? 'لا يوجد نص', 80) }}
            </div>
        </td>

        <!-- Date -->
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            @if($message->sent_at)
                <div>{{ \Carbon\Carbon::parse($message->sent_at)->format('Y-m-d H:i') }}</div>
            @else
                <div>{{ \Carbon\Carbon::parse($message->campaign_created_at)->format('Y-m-d H:i') }}</div>
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
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    قيد الانتظار
                </span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
            لا توجد رسائل
        </td>
    </tr>
@endforelse

