@forelse($activities as $activity)
    <tr class="hover:bg-gray-50 transition-colors duration-150">
        <!-- Operation Type -->
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                @if($activity['type'] == 'add_client') bg-primary-100 text-primary-800
                @elseif($activity['type'] == 'create_campaign') bg-secondary-100 text-secondary-800
                @elseif($activity['type'] == 'change_status') bg-green-100 text-green-800
                @else bg-gray-100 text-gray-800
                @endif">
                {{ $activity['type_text'] }}
            </span>
        </td>

        <!-- Client/Campaign Name -->
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">
                @if(isset($activity['client_name']))
                    {{ $activity['client_name'] }}
                @elseif(isset($activity['campaign_name']))
                    {{ $activity['campaign_name'] }}
                @else
                    -
                @endif
            </div>
        </td>

        <!-- Time -->
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ \Carbon\Carbon::parse($activity['created_at'])->format('Y-m-d H:i') }}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="3" class="px-6 py-4 text-center text-gray-500">
            لا توجد عمليات مسجلة
        </td>
    </tr>
@endforelse

