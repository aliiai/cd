@if($activities->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
                <tr>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">العملية</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">المستخدم</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">السجل المتعلق</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">الحالة</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">التاريخ والوقت</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="auditTableBody">
                @foreach($activities as $activity)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                        <!-- Operation Type -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $typeConfig = match($activity['type']) {
                                    'add_debtor' => [
                                        'bg' => 'bg-primary-100 dark:bg-primary-900/30',
                                        'text' => 'text-primary-800 dark:text-primary-300',
                                        'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
                                    ],
                                    'create_campaign' => [
                                        'bg' => 'bg-secondary-100 dark:bg-secondary-900/30',
                                        'text' => 'text-secondary-800 dark:text-secondary-300',
                                        'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'
                                    ],
                                    'change_status' => [
                                        'bg' => 'bg-green-100 dark:bg-green-900/30',
                                        'text' => 'text-green-800 dark:text-green-300',
                                        'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'
                                    ],
                                    'send_message' => [
                                        'bg' => 'bg-primary-100 dark:bg-primary-900/30',
                                        'text' => 'text-primary-800 dark:text-primary-300',
                                        'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'
                                    ],
                                    'create_ticket' => [
                                        'bg' => 'bg-yellow-100 dark:bg-yellow-900/30',
                                        'text' => 'text-yellow-800 dark:text-yellow-300',
                                        'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
                                    ],
                                    'subscription_request' => [
                                        'bg' => 'bg-pink-100 dark:bg-pink-900/30',
                                        'text' => 'text-pink-800 dark:text-pink-300',
                                        'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
                                    ],
                                    default => [
                                        'bg' => 'bg-gray-100 dark:bg-gray-700',
                                        'text' => 'text-gray-800 dark:text-gray-300',
                                        'icon' => 'M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4'
                                    ],
                                };
                            @endphp
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 {{ $typeConfig['bg'] }} rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 {{ $typeConfig['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $typeConfig['icon'] }}"></path>
                                    </svg>
                                </div>
                                <span class="px-3 py-1.5 text-xs font-semibold rounded-full {{ $typeConfig['bg'] }} {{ $typeConfig['text'] }}">
                                    {{ $activity['type_text'] }}
                                </span>
                            </div>
                        </td>
                        
                        <!-- User -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white font-bold text-sm ml-3">
                                    {{ strtoupper(substr($activity['user_name'] ?? '?', 0, 1)) }}
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $activity['user_name'] ?? 'غير معروف' }}
                                </span>
                            </div>
                        </td>
                        
                        <!-- Related Record -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $activity['related_name'] ?? '-' }}
                            </span>
                        </td>
                        
                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusConfig = match($activity['status']) {
                                    'success', 'approved' => [
                                        'bg' => 'bg-green-100 dark:bg-green-900/30',
                                        'text' => 'text-green-800 dark:text-green-300',
                                        'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                                    ],
                                    'failed', 'rejected' => [
                                        'bg' => 'bg-red-100 dark:bg-red-900/30',
                                        'text' => 'text-red-800 dark:text-red-300',
                                        'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'
                                    ],
                                    'pending' => [
                                        'bg' => 'bg-yellow-100 dark:bg-yellow-900/30',
                                        'text' => 'text-yellow-800 dark:text-yellow-300',
                                        'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
                                    ],
                                    default => [
                                        'bg' => 'bg-gray-100 dark:bg-gray-700',
                                        'text' => 'text-gray-800 dark:text-gray-300',
                                        'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
                                    ],
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
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 {{ $statusConfig['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusConfig['icon'] }}"></path>
                                </svg>
                                <span class="px-3 py-1.5 text-xs font-semibold rounded-full {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }}">
                                    {{ $statusText }}
                                </span>
                            </div>
                        </td>
                        
                        <!-- Timestamp -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($activity['created_at'])->format('Y-m-d') }}
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($activity['created_at'])->format('H:i') }}
                                </span>
                            </div>
                        </td>
                        
                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap">
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
                                       class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-primary-500 to-primary-600 hover:from-primary-600 hover:to-primary-700 text-white text-xs font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
                                       title="عرض التفاصيل">
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <span>عرض</span>
                                    </a>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            @else
                                <span class="text-gray-400 dark:text-gray-500">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-center py-16">
        <div class="w-20 h-20 bg-gradient-to-br from-primary-100 to-secondary-100 dark:from-primary-900/20 dark:to-secondary-900/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-primary-500 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">لا توجد عمليات مسجلة</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">ستظهر السجلات هنا عند حدوث الأنشطة</p>
    </div>
@endif
