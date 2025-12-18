@extends('layouts.owner')

@section('content')
<div class="py-12">
    <div class="w-full mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">إدارة المديونين</h1>
                <p class="mt-2 text-sm text-gray-600">إدارة جميع المديونين وعرض حالتهم</p>
            </div>
            <button onclick="openClientModal()" 
                    class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                + إضافة مديون جديد
            </button>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Subscription Usage Info -->
        @if($subscriptionInfo)
            <div class="bg-gradient-to-r from-primary-50 to-secondary-50 border-l-4 border-primary-500 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">معلومات الاشتراك والاستهلاك</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">الباقة الحالية</p>
                                <p class="text-base font-bold text-gray-900">{{ $subscriptionInfo['subscription_name'] }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">عدد المديونين</p>
                                <div class="flex items-center space-x-2">
                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                        <div 
                                            class="h-2 rounded-full transition-all duration-300 {{ $subscriptionInfo['debtors_usage'] >= 90 ? 'bg-red-600' : ($subscriptionInfo['debtors_usage'] >= 70 ? 'bg-yellow-600' : 'bg-green-600') }}" 
                                            style="width: {{ min($subscriptionInfo['debtors_usage'], 100) }}%"
                                        ></div>
                                    </div>
                                    <span class="text-sm font-bold {{ $subscriptionInfo['debtors_usage'] >= 90 ? 'text-red-600' : ($subscriptionInfo['debtors_usage'] >= 70 ? 'text-yellow-600' : 'text-green-600') }}">
                                        {{ $subscriptionInfo['current_debtors'] }} / {{ $subscriptionInfo['max_debtors'] }}
                                    </span>
                                </div>
                                @if($subscriptionInfo['debtors_remaining'] !== null)
                                    <p class="text-xs text-gray-500 mt-1">
                                        المتبقي: <span class="font-semibold {{ $subscriptionInfo['debtors_remaining'] <= 2 ? 'text-red-600' : 'text-gray-700' }}">{{ $subscriptionInfo['debtors_remaining'] }}</span> مديون
                                    </p>
                                @endif
                            </div>
                        </div>
                        @if($subscriptionInfo['debtors_remaining'] !== null && $subscriptionInfo['debtors_remaining'] <= 2 && $subscriptionInfo['debtors_remaining'] > 0)
                            <div class="mt-3 bg-yellow-100 border border-yellow-400 text-yellow-800 px-3 py-2 rounded text-sm">
                                ⚠️ تحذير: لديك {{ $subscriptionInfo['debtors_remaining'] }} مديون متبقي فقط. يرجى ترقية اشتراكك لإضافة المزيد.
                            </div>
                        @elseif($subscriptionInfo['debtors_remaining'] !== null && $subscriptionInfo['debtors_remaining'] == 0)
                            <div class="mt-3 bg-red-100 border border-red-400 text-red-800 px-3 py-2 rounded text-sm">
                                ❌ لقد وصلت للحد الأقصى المسموح للمديونين! يرجى ترقية اشتراكك لإضافة المزيد.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="text-sm text-yellow-800">
                        لا يوجد اشتراك نشط. يرجى الاشتراك في إحدى الباقات لإضافة المديونين.
                    </p>
                </div>
            </div>
        @endif

        <!-- Clients Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6" >
                @if($clients->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الهاتف</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">البريد الإلكتروني</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">قيمة الدين</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الاستحقاق</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">حالة الدين</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($clients as $client)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $client->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $client->phone }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $client->email ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                            {{ number_format($client->debt_amount, 2) }} ر.س
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $client->due_date->format('Y-m-d') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $client->status_color }}">
                                                {{ $client->status_text }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2 space-x-reverse">
                                                <button onclick="openClientModal({{ $client->id }}, '{{ $client->name }}', '{{ $client->phone }}', '{{ $client->email }}', {{ $client->debt_amount }}, '{{ $client->due_date->format('Y-m-d') }}', '{{ $client->payment_link }}', '{{ addslashes($client->notes) }}', '{{ $client->status }}')" 
                                                        class="text-primary-600 hover:text-primary-900 font-medium">
                                                    تعديل
                                                </button>
                                                <form action="{{ route('owner.clients.destroy', $client) }}" 
                                                      method="POST" 
                                                      class="inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium">حذف</button>
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
                        <button onclick="openClientModal()" 
                                class="mt-4 inline-block bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                            إضافة مديون جديد
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Client Modal -->
<div id="clientModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50" style="display: none; align-items: flex-start; justify-content: center;">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white m-4">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900" id="modalTitle">إضافة مديون جديد</h3>
                <button onclick="closeClientModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form id="clientForm" action="" method="POST" onsubmit="return validateClientSubmission(event)">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            الاسم الكامل <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            رقم الهاتف <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="phone" 
                               id="phone" 
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            البريد الإلكتروني
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Debt Amount -->
                    <div>
                        <label for="debt_amount" class="block text-sm font-medium text-gray-700 mb-2">
                            قيمة الدين <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="debt_amount" 
                               id="debt_amount" 
                               step="0.01"
                               min="0"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                        @error('debt_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                            تاريخ الاستحقاق <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="due_date" 
                               id="due_date" 
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                        @error('due_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Link -->
                    <div class="md:col-span-2">
                        <label for="payment_link" class="block text-sm font-medium text-gray-700 mb-2">
                            رابط الدفع
                        </label>
                        <input type="url" 
                               name="payment_link" 
                               id="payment_link"
                               placeholder="https://..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                        @error('payment_link')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            حالة الدين <span class="text-red-500">*</span>
                        </label>
                        <select name="status" 
                                id="status" 
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            <option value="new">جديد</option>
                            <option value="contacted">تم التواصل</option>
                            <option value="promise_to_pay">وعد بالدفع</option>
                            <option value="paid">مدفوع</option>
                            <option value="overdue">متأخر</option>
                            <option value="failed">فشل</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            ملاحظات
                        </label>
                        <textarea name="notes" 
                                  id="notes" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"></textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3 space-x-reverse mt-6">
                    <button type="button" 
                            onclick="closeClientModal()"
                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        إلغاء
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700">
                        حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Open Modal for Add
    function openClientModal(clientId = null, name = '', phone = '', email = '', debtAmount = '', dueDate = '', paymentLink = '', notes = '', status = 'new') {
        const modal = document.getElementById('clientModal');
        const form = document.getElementById('clientForm');
        const modalTitle = document.getElementById('modalTitle');
        const methodField = document.getElementById('methodField');
        
        if (clientId) {
            // Edit Mode
            modalTitle.textContent = 'تعديل بيانات المديون';
            form.action = '{{ route("owner.clients.update", ":id") }}'.replace(':id', clientId);
            document.getElementById('formMethod').value = 'PUT';
            
            // Fill form fields
            document.getElementById('name').value = name;
            document.getElementById('phone').value = phone;
            document.getElementById('email').value = email;
            document.getElementById('debt_amount').value = debtAmount;
            document.getElementById('due_date').value = dueDate;
            document.getElementById('payment_link').value = paymentLink;
            document.getElementById('notes').value = notes;
            document.getElementById('status').value = status;
        } else {
            // Add Mode
            modalTitle.textContent = 'إضافة مديون جديد';
            form.action = '{{ route("owner.clients.store") }}';
            document.getElementById('formMethod').value = 'POST';
            
            // Reset form
            form.reset();
        }
        
        // Show modal
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    // Close Modal
    function closeClientModal() {
        const modal = document.getElementById('clientModal');
        const form = document.getElementById('clientForm');
        
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        
        if (form) {
            form.reset();
        }
    }

    // Close modal when clicking outside
    document.getElementById('clientModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeClientModal();
        }
    });

    // Validate Client Submission
    function validateClientSubmission(e) {
        const form = document.getElementById('clientForm');
        const method = document.getElementById('formMethod').value;
        
        // التحقق من الحدود فقط عند إضافة مديون جديد (ليس عند التعديل)
        if (method === 'POST') {
            @if($subscriptionInfo)
                const maxDebtors = {{ $subscriptionInfo['max_debtors'] ?? 0 }};
                const currentDebtors = {{ $subscriptionInfo['current_debtors'] ?? 0 }};
                const debtorsRemaining = {{ $subscriptionInfo['debtors_remaining'] ?? 0 }};
                
                if (maxDebtors > 0) {
                    if (debtorsRemaining === 0) {
                        e.preventDefault();
                        swalError('لقد وصلت للحد الأقصى المسموح للمديونين! الحد المسموح: ' + maxDebtors + ' مديون، الحالي: ' + currentDebtors + '. يرجى ترقية اشتراكك لإضافة المزيد من المديونين.', 'حد المديونين');
                        return false;
                    }
                }
            @else
                e.preventDefault();
                swalError('لا يوجد اشتراك نشط. يرجى الاشتراك في إحدى الباقات أولاً.', 'لا يوجد اشتراك');
                return false;
            @endif
        }

        return true;
    }
</script>
@endsection
