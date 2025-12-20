@forelse($activities as $activity)
    <tr class="hover:bg-primary-50 dark:hover:bg-gray-700 transition-colors duration-200">
        {{-- Operation Type --}}
        <td class="px-6 py-4 whitespace-nowrap">
            @php
                $typeConfig = [
                    'add_debtor' => [
                        'color' => 'bg-primary-100 dark:bg-primary-900/30 text-primary-800 dark:text-primary-300 border-primary-200 dark:border-primary-800',
                        'icon' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z'
                    ],
                    'create_campaign' => [
                        'color' => 'bg-secondary-100 dark:bg-secondary-900/30 text-secondary-800 dark:text-secondary-300 border-secondary-200 dark:border-secondary-800',
                        'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z'
                    ],
                    'change_status' => [
                        'color' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                        'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                    ],
                ];
                $config = $typeConfig[$activity['type']] ?? [
                    'color' => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border-gray-200 dark:border-gray-600',
                    'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
                ];
            @endphp
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold border {{ $config['color'] }}">
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"></path>
                </svg>
                {{ $activity['type_text'] }}
            </span>
        </td>

        {{-- Client/Campaign Name --}}
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center text-white font-bold text-sm ml-3">
                    @if(isset($activity['client_name']))
                        {{ mb_substr($activity['client_name'], 0, 1) }}
                    @elseif(isset($activity['campaign_name']))
                        {{ mb_substr($activity['campaign_name'], 0, 1) }}
                    @elseif(isset($activity['debtor_name']))
                        {{ mb_substr($activity['debtor_name'], 0, 1) }}
                    @else
                        -
                    @endif
                </div>
                <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                    @if(isset($activity['client_name']))
                        {{ $activity['client_name'] }}
                    @elseif(isset($activity['campaign_name']))
                        {{ $activity['campaign_name'] }}
                    @elseif(isset($activity['debtor_name']))
                        {{ $activity['debtor_name'] }}
                    @else
                        -
                    @endif
                </div>
            </div>
        </td>

        {{-- Time --}}
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center text-sm text-gray-900 dark:text-gray-100">
                <svg class="w-4 h-4 ml-2 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ \Carbon\Carbon::parse($activity['created_at'])->format('Y-m-d H:i') }}
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="3" class="px-6 py-12 text-center">
            <div class="flex flex-col items-center justify-center">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <p class="text-gray-500 dark:text-gray-400 font-medium">لا توجد عمليات مسجلة</p>
                <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">ستظهر الأنشطة هنا عند قيامك بعمليات</p>
            </div>
        </td>
    </tr>
@endforelse
