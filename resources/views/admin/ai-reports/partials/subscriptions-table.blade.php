@forelse($subscriptions as $subscription)
    <tr class="hover:bg-gray-50 transition-colors duration-150">
        <!-- User -->
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm font-medium text-gray-900">{{ $subscription->user->name ?? 'غير محدد' }}</div>
            <div class="text-xs text-gray-500">{{ $subscription->user->email ?? '' }}</div>
        </td>

        <!-- Subscription -->
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="text-sm text-gray-900">{{ $subscription->subscription->name ?? 'غير محدد' }}</div>
            <div class="text-xs text-gray-500">{{ Str::limit($subscription->subscription->description ?? '', 40) }}</div>
        </td>

        <!-- Status -->
        <td class="px-6 py-4 whitespace-nowrap">
            @if($subscription->status == 'active')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    نشط
                </span>
            @elseif($subscription->status == 'expired')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    منتهي
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    ملغي
                </span>
            @endif
        </td>

        <!-- Start Date -->
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ $subscription->started_at ? $subscription->started_at->format('Y-m-d') : 'غير محدد' }}
        </td>

        <!-- End Date -->
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            @if($subscription->expires_at)
                {{ $subscription->expires_at->format('Y-m-d') }}
            @else
                <span class="text-gray-400">دائم</span>
            @endif
        </td>

        <!-- Price -->
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
            {{ number_format($subscription->subscription->price ?? 0, 2) }} ر.س
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
            لا توجد اشتراكات
        </td>
    </tr>
@endforelse

