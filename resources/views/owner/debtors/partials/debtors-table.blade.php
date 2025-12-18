@if($debtors->count() > 0)
    <div class="overflow-x-auto rounded-lg shadow-lg border border-gray-200">
        <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-700 to-gray-600">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">الاسم</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">رقم الهاتف</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">البريد الإلكتروني</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">قيمة الدين</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">تاريخ الاستحقاق</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">حالة الدين</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="debtorsTableBody">
                @foreach($debtors as $debtor)
                    <tr class="hover:bg-primary-50 transition-all duration-200 hover:shadow-md">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $debtor->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $debtor->phone }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $debtor->email ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ number_format($debtor->debt_amount, 2) }} ر.س
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $debtor->due_date->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm {{ $debtor->status_color }}">
                                {{ $debtor->status_text }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-3 space-x-reverse gap-2">
                                <button onclick="openDebtorModal({{ $debtor->id }}, '{{ $debtor->name }}', '{{ $debtor->phone }}', '{{ $debtor->email }}', {{ $debtor->debt_amount }}, '{{ $debtor->due_date->format('Y-m-d') }}', '{{ $debtor->payment_link }}', '{{ addslashes($debtor->notes) }}', '{{ $debtor->status }}')" 
                                        class="inline-flex items-center px-3 py-1.5 bg-primary-100 text-primary-700 rounded-lg hover:bg-primary-200 transition-colors duration-200 shadow-sm hover:shadow-md"
                                        title="تعديل">
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span class="text-xs font-medium">تعديل</span>
                                </button>
                                <form action="{{ route('owner.debtors.destroy', $debtor) }}" 
                                      method="POST" 
                                      class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex  items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors duration-200 shadow-sm hover:shadow-md"
                                            title="حذف">
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        <span class="text-xs font-medium">حذف</span>
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
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        <p class="mt-4 text-gray-500 text-lg">لا توجد مديونين حالياً.</p>
        <button onclick="openDebtorModal()" 
                class="mt-4 inline-block bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
            إضافة مديون جديد
        </button>
    </div>
@endif
