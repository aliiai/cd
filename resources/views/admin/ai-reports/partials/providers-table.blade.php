@forelse($providers as $provider)
    <tr class="hover:bg-gray-50 transition-colors duration-150">
        <!-- Provider Name -->
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div>
                    <div class="text-sm font-medium text-gray-900">{{ $provider->name }}</div>
                    <div class="text-sm text-gray-500">{{ $provider->email }}</div>
                </div>
            </div>
        </td>

        <!-- Status -->
        <td class="px-6 py-4 whitespace-nowrap">
            @if($provider->is_active)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    نشط
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    متوقف
                </span>
            @endif
        </td>

        <!-- Current Subscription -->
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            @if($provider->activeSubscription)
                <div>
                    <div class="font-medium">{{ $provider->activeSubscription->subscription->name ?? 'غير محدد' }}</div>
                    <div class="text-xs text-gray-500">{{ number_format($provider->activeSubscription->subscription->price ?? 0, 2) }} ر.س</div>
                </div>
            @else
                <span class="text-gray-400">لا يوجد اشتراك</span>
            @endif
        </td>

        <!-- Debtors Count -->
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            <span class="font-medium">{{ $provider->debtors_count }}</span>
        </td>

        <!-- Messages Sent -->
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            <span class="font-medium">{{ $provider->messages_sent }}</span>
        </td>

        <!-- Collection Rate -->
        <td class="px-6 py-4 whitespace-nowrap">
            @php
                $rate = round($provider->collection_rate, 1);
                $colorClass = $rate >= 70 ? 'text-green-600' : ($rate >= 40 ? 'text-yellow-600' : 'text-red-600');
            @endphp
            <div class="flex items-center">
                <span class="text-sm font-medium {{ $colorClass }}">{{ $rate }}%</span>
                <div class="mr-2 w-16 bg-gray-200 rounded-full h-2">
                    <div class="bg-{{ $rate >= 70 ? 'green' : ($rate >= 40 ? 'yellow' : 'red') }}-600 h-2 rounded-full" style="width: {{ min($rate, 100) }}%"></div>
                </div>
            </div>
        </td>

        <!-- AI Usage -->
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ $provider->ai_usage }}
        </td>

        <!-- Actions -->
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <a 
                href="{{ route('admin.users.show', $provider->id) }}" 
                class="text-primary-600 hover:text-primary-900 transition-colors duration-200"
            >
                عرض التفاصيل
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
            لا توجد نتائج
        </td>
    </tr>
@endforelse

