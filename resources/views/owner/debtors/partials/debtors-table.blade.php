@if($debtors->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gradient-to-r from-primary-600 to-primary-700 dark:from-primary-700 dark:to-primary-800">
                <tr>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">{{ __('common.name') }}</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">{{ __('owner.phone_number_label') }}</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">{{ __('owner.email_address_label') }}</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">{{ __('owner.debt_amount') }}</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">
                        {{ __('owner.next_due_date') }}
                    </th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">{{ __('owner.debt_status') }}</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">{{ __('common.actions') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="debtorsTableBody">
                @foreach($debtors as $debtor)
                    <tr class="hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-all duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ $debtor->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                            {{ $debtor->phone }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                            {{ $debtor->email ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-100">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-700">
                                {{ number_format($debtor->debt_amount, 2) }} {{ __('owner.sar') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                            @if($debtor->has_installments && $debtor->relationLoaded('installments'))
                                @php
                                    $nextInstallment = $debtor->installments
                                        ->where('status', '!=', 'paid')
                                        ->where('status', '!=', 'cancelled')
                                        ->sortBy('due_date')
                                        ->first();
                                @endphp
                                @if($nextInstallment)
                                    <div class="flex flex-col">
                                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ $nextInstallment->due_date->format('Y-m-d') }}</span>
                                        <span class="text-xs text-primary-600 dark:text-primary-400">دفعة #{{ $nextInstallment->installment_number }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            @else
                                {{ $debtor->due_date->format('Y-m-d') }}
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm {{ $debtor->status_color }}">
                                {{ $debtor->status_text }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2 space-x-reverse">
                                <a href="{{ route('owner.debtors.show', $debtor) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-all duration-200 shadow-sm hover:shadow-md"
                                   title="{{ __('owner.view_details') }}">
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <span class="text-xs font-medium">{{ __('owner.view_details') }}</span>
                                </a>
                                <button onclick="openDebtorModal({{ $debtor->id }}, '{{ $debtor->name }}', '{{ $debtor->phone }}', '{{ $debtor->email }}', {{ $debtor->debt_amount }}, '{{ $debtor->due_date->format('Y-m-d') }}', '{{ $debtor->payment_link }}', '{{ addslashes($debtor->notes) }}', '{{ $debtor->status }}')" 
                                        class="inline-flex items-center px-3 py-1.5 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 rounded-lg hover:bg-primary-200 dark:hover:bg-primary-900/50 transition-all duration-200 shadow-sm hover:shadow-md"
                                        title="{{ __('common.edit') }}">
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span class="text-xs font-medium">{{ __('common.edit') }}</span>
                                </button>
                                <form action="{{ route('owner.debtors.destroy', $debtor) }}" 
                                      method="POST" 
                                      class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1.5 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-lg hover:bg-red-200 dark:hover:bg-red-900/50 transition-all duration-200 shadow-sm hover:shadow-md"
                                            title="{{ __('common.delete') }}">
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        <span class="text-xs font-medium">{{ __('common.delete') }}</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-center py-16">
        <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
        </div>
        <p class="text-gray-500 dark:text-gray-400 text-lg font-medium mb-2">{{ __('owner.no_debtors') }}</p>
        <p class="text-gray-400 dark:text-gray-500 text-sm mb-6">{{ __('owner.start_managing_debts') }}</p>
        <button onclick="openDebtorModal()" 
                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            {{ __('owner.add_new_debtor') }}
        </button>
    </div>
@endif
