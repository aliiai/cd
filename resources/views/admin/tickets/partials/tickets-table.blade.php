@if($tickets->count() > 0)
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gradient-to-r from-primary-600 via-primary-500 to-secondary-600">
                <tr>
                    <th class="px-6 py-4 text-right text-xs font-bold text-white uppercase tracking-wider">رقم الشكوى</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-white uppercase tracking-wider">المستخدم</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-white uppercase tracking-wider">العنوان</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-white uppercase tracking-wider">النوع</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-white uppercase tracking-wider">الحالة</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-white uppercase tracking-wider">آخر رد</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-white uppercase tracking-wider">تاريخ الإنشاء</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-white uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="ticketsTableBody">
                @foreach($tickets as $ticket)
                    <tr class="hover:bg-primary-50 dark:hover:bg-gray-700 transition-all duration-200 hover:shadow-md">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg flex items-center justify-center">
                                    <span class="text-white text-xs font-bold">#{{ substr($ticket->ticket_number, -4) }}</span>
                                </div>
                                <div class="mr-3">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $ticket->ticket_number }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-secondary-400 to-secondary-600 flex items-center justify-center text-white font-semibold text-sm">
                                        {{ substr($ticket->user->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="mr-3">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $ticket->user->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $ticket->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="max-w-xs">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate" title="{{ $ticket->subject }}">
                                    {{ $ticket->subject }}
                                </div>
                                @if($ticket->description)
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 truncate">
                                        {{ Str::limit($ticket->description, 50) }}
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm {{ $ticket->type_color }}">
                                {{ $ticket->type_text }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm {{ $ticket->status_color }}">
                                {{ $ticket->status_text }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                            @if($ticket->messages->count() > 0)
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $ticket->messages->first()->created_at->diffForHumans() }}
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $ticket->created_at->format('Y-m-d') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.tickets.show', $ticket) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary-500 to-primary-600 text-white rounded-lg hover:from-primary-600 hover:to-primary-700 transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5"
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
    <div class="text-center py-16">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">لا توجد شكاوى</h3>
        <p class="text-gray-500 dark:text-gray-400">لم يتم العثور على أي شكاوى مطابقة للبحث</p>
    </div>
@endif

