@if($tickets->count() > 0)
    <div class="overflow-x-auto rounded-lg shadow-lg border border-gray-200">
        <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-700 to-gray-600">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">رقم الشكوى</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">العنوان</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">النوع</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">الحالة</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">آخر رد</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">تاريخ الإنشاء</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="ticketsTableBody">
                @foreach($tickets as $ticket)
                    <tr class="hover:bg-blue-50 transition-all duration-200 hover:shadow-md">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            {{ $ticket->ticket_number }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="max-w-xs truncate" title="{{ $ticket->subject }}">
                                {{ $ticket->subject }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm {{ $ticket->type_color }}">
                                {{ $ticket->type_text }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm {{ $ticket->status_color }}">
                                {{ $ticket->status_text }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            @if($ticket->messages->count() > 0)
                                {{ $ticket->messages->first()->created_at->diffForHumans() }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $ticket->created_at->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('owner.tickets.show', $ticket) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors duration-200 shadow-sm hover:shadow-md"
                               title="عرض التفاصيل">
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span class="text-xs font-medium">عرض</span>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <p class="mt-4 text-gray-500 text-lg">لا توجد شكاوى حالياً.</p>
        <a href="{{ route('owner.tickets.create') }}" 
           class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
            إنشاء شكوى جديدة
        </a>
    </div>
@endif

