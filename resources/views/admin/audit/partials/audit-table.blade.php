@if($activities->count() > 0)
    <div class="overflow-x-auto rounded-lg shadow-lg border border-gray-200">
        <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-700 to-gray-600">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">العملية</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">المستخدم</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">السجل المتعلق</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">الحالة</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">التاريخ والوقت</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="auditTableBody">
                @foreach($activities as $activity)
                    <tr class="hover:bg-primary-50 transition-all duration-200 hover:shadow-md">
                        <!-- Operation Type -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @php
                                $typeColor = match($activity['type']) {
                                    'add_debtor' => 'bg-primary-100 text-primary-800',
                                    'create_campaign' => 'bg-secondary-100 text-secondary-800',
                                    'change_status' => 'bg-green-100 text-green-800',
                                    'send_message' => 'bg-primary-100 text-primary-800',
                                    'create_ticket' => 'bg-yellow-100 text-yellow-800',
                                    'subscription_request' => 'bg-pink-100 text-pink-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <span class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm {{ $typeColor }}">
                                {{ $activity['type_text'] }}
                            </span>
                        </td>
                        
                        <!-- User -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $activity['user_name'] ?? 'غير معروف' }}
                        </td>
                        
                        <!-- Related Record -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $activity['related_name'] ?? '-' }}
                        </td>
                        
                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @php
                                $statusColor = match($activity['status']) {
                                    'success', 'approved' => 'bg-green-100 text-green-800',
                                    'failed', 'rejected' => 'bg-red-100 text-red-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                                $statusText = match($activity['status']) {
                                    'success' => 'نجح',
                                    'failed' => 'فشل',
                                    'pending' => 'قيد الانتظار',
                                    'approved' => 'موافق عليه',
                                    'rejected' => 'مرفوض',
                                    default => $activity['status'],
                                };
                            @endphp
                            <span class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm {{ $statusColor }}">
                                {{ $statusText }}
                            </span>
                        </td>
                        
                        <!-- Timestamp -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($activity['created_at'])->format('Y-m-d H:i') }}
                        </td>
                        
                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if(isset($activity['related_id']) && isset($activity['related_type']))
                                @php
                                    $route = match($activity['related_type']) {
                                        'debtor' => route('admin.users.show', $activity['user_id']) . '?debtor=' . $activity['related_id'],
                                        'campaign' => route('admin.users.show', $activity['user_id']) . '?campaign=' . $activity['related_id'],
                                        'ticket' => route('admin.tickets.show', $activity['related_id']),
                                        'subscription_request' => route('admin.subscription-requests.show', $activity['related_id']),
                                        default => '#',
                                    };
                                @endphp
                                @if($route !== '#')
                                    <a href="{{ $route }}" 
                                       class="inline-flex items-center px-3 py-1.5 bg-primary-100 text-primary-700 rounded-lg hover:bg-primary-200 transition-colors duration-200 shadow-sm hover:shadow-md"
                                       title="عرض التفاصيل">
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <span class="text-xs font-medium">عرض</span>
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
        </svg>
        <p class="mt-4 text-gray-500 text-lg">لا توجد عمليات مسجلة.</p>
    </div>
@endif

