@forelse($campaigns as $campaign)
    <tr class="hover:bg-gray-50 transition-colors duration-150">
        <!-- Campaign Name -->
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-medium text-gray-900">{{ $campaign->campaign_number }}</div>
            <div class="text-xs text-gray-500">{{ Str::limit($campaign->message ?? '', 40) }}</div>
        </td>

        <!-- Channel -->
        <td class="px-6 py-4 whitespace-nowrap">
            @if($campaign->channel == 'sms')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                    SMS
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Email
                </span>
            @endif
        </td>

        <!-- Recipients Count -->
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            <span class="font-medium">{{ $campaign->clients->count() }}</span>
        </td>

        <!-- Send Time -->
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            @if($campaign->send_type == 'now')
                <div>{{ $campaign->created_at->format('Y-m-d H:i') }}</div>
                <div class="text-xs text-gray-400">إرسال فوري</div>
            @else
                <div>{{ $campaign->scheduled_at ? $campaign->scheduled_at->format('Y-m-d H:i') : 'غير محدد' }}</div>
                <div class="text-xs text-gray-400">مجدول</div>
            @endif
        </td>

        <!-- Status -->
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $campaign->status_color }}">
                {{ $campaign->status_text }}
            </span>
        </td>

        <!-- Actions -->
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <a 
                href="{{ route('owner.collections.show', $campaign->id) }}" 
                class="text-primary-600 hover:text-primary-900 transition-colors duration-200"
            >
                عرض التفاصيل
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
            لا توجد حملات
        </td>
    </tr>
@endforelse

