@if($tickets->count() > 0)
    <div class="overflow-x-auto rounded-lg">
        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gradient-to-r from-primary-500 to-secondary-500">
                <tr>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap">{{ __('tickets.ticket_number_header') }}</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap">{{ __('tickets.subject') }}</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap">{{ __('tickets.type_header') }}</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap">{{ __('tickets.status_header') }}</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap">{{ __('tickets.last_reply') }}</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap">{{ __('tickets.created_date') }}</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap">{{ __('common.actions') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="ticketsTableBody">
                @foreach($tickets as $ticket)
                    <tr class="hover:bg-primary-50 dark:hover:bg-gray-700 transition-all duration-200">
                        {{-- Ticket Number --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center text-white font-bold text-sm ml-3">
                                    #
                                </div>
                                <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $ticket->ticket_number }}</span>
                            </div>
                        </td>
                        
                        {{-- Subject --}}
                        <td class="px-6 py-4">
                            <div class="max-w-xs truncate text-sm font-medium text-gray-900 dark:text-gray-100" title="{{ $ticket->subject }}">
                                {{ $ticket->subject }}
                            </div>
                        </td>
                        
                        {{-- Type --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm border {{ $ticket->type_color }}">
                                {{ $ticket->type_text }}
                            </span>
                        </td>
                        
                        {{-- Status --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm border {{ $ticket->status_color }}">
                                {{ $ticket->status_text }}
                            </span>
                        </td>
                        
                        {{-- Last Reply --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($ticket->messages->count() > 0)
                                <div class="flex items-center text-sm text-gray-900 dark:text-gray-100">
                                    <svg class="w-4 h-4 ml-2 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $ticket->messages->first()->created_at->diffForHumans() }}
                                </div>
                            @else
                                <span class="text-gray-400 dark:text-gray-500 text-sm">-</span>
                            @endif
                        </td>
                        
                        {{-- Created At --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                            {{ $ticket->created_at->format('Y-m-d') }}
                        </td>
                        
                        {{-- Actions --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('owner.tickets.show', $ticket) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary-500 to-secondary-500 hover:from-primary-600 hover:to-secondary-600 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5"
                               title="{{ __('common.view_details') }}">
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{ __('tickets.view') }}
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-center py-12">
        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
        <p class="text-gray-500 dark:text-gray-400 font-medium text-lg mb-4">{{ __('tickets.no_tickets') }}</p>
        <button onclick="openCreateTicketModal()" 
           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-500 to-secondary-500 hover:from-primary-600 hover:to-secondary-600 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('tickets.create_first_ticket') }}
        </button>
    </div>
@endif
