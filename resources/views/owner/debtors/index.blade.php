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
            <button onclick="openDebtorModal()" 
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

        <!-- Filters and Search -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <form id="filterForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">البحث</label>
                        <input type="text" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="ابحث بالاسم، البريد الإلكتروني، أو رقم الهاتف..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    
                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">حالة الدين</label>
                        <select id="status" 
                                name="status" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>جميع الحالات</option>
                            <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>جديد</option>
                            <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>تم التواصل</option>
                            <option value="promise_to_pay" {{ request('status') == 'promise_to_pay' ? 'selected' : '' }}>وعد بالدفع</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوع</option>
                            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>متأخر</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>فشل</option>
                        </select>
                    </div>
                    
                    <!-- Sort By -->
                    <div>
                        <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-2">ترتيب حسب</label>
                        <select id="sort_by" 
                                name="sort_by" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            <option value="created_at" {{ request('sort_by') == 'created_at' || !request('sort_by') ? 'selected' : '' }}>تاريخ الإضافة</option>
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>الاسم</option>
                            <option value="debt_amount" {{ request('sort_by') == 'debt_amount' ? 'selected' : '' }}>قيمة الدين</option>
                            <option value="due_date" {{ request('sort_by') == 'due_date' ? 'selected' : '' }}>تاريخ الاستحقاق</option>
                            <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>الحالة</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <!-- Debtors Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div id="debtorsTableContainer">
                    @include('owner.debtors.partials.debtors-table', ['debtors' => $debtors])
                </div>
                
                <!-- Pagination -->
                <div id="paginationContainer" class="mt-4">
                    {{ $debtors->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Debtor Modal -->
<div id="debtorModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50" style="display: none; align-items: flex-start; justify-content: center;">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white m-4">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900" id="modalTitle">إضافة مديون جديد</h3>
                <button onclick="closeDebtorModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form id="debtorForm" action="" method="POST" onsubmit="return validateDebtorSubmission(event)">
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
                            onclick="closeDebtorModal()"
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
    function openDebtorModal(debtorId = null, name = '', phone = '', email = '', debtAmount = '', dueDate = '', paymentLink = '', notes = '', status = 'new') {
        const modal = document.getElementById('debtorModal');
        const form = document.getElementById('debtorForm');
        const modalTitle = document.getElementById('modalTitle');
        
        if (debtorId) {
            // Edit Mode
            modalTitle.textContent = 'تعديل بيانات المديون';
            form.action = '{{ route("owner.debtors.update", ":id") }}'.replace(':id', debtorId);
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
            form.action = '{{ route("owner.debtors.store") }}';
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
    function closeDebtorModal() {
        const modal = document.getElementById('debtorModal');
        const form = document.getElementById('debtorForm');
        
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
    document.getElementById('debtorModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDebtorModal();
        }
    });

    // Validate Debtor Submission
    function validateDebtorSubmission(e) {
        const form = document.getElementById('debtorForm');
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
                        alert('❌ لقد وصلت للحد الأقصى المسموح للمديونين! الحد المسموح: ' + maxDebtors + ' مديون، الحالي: ' + currentDebtors + '. يرجى ترقية اشتراكك لإضافة المزيد من المديونين.');
                        return false;
                    }
                }
            @else
                e.preventDefault();
                alert('❌ لا يوجد اشتراك نشط. يرجى الاشتراك في إحدى الباقات أولاً.');
                return false;
            @endif
        }

        return true;
    }

    // AJAX Filtering and Search
    let searchTimeout;
    const searchInput = document.getElementById('search');
    const statusSelect = document.getElementById('status');
    const sortBySelect = document.getElementById('sort_by');

    // Function to load debtors via AJAX
    function loadDebtors() {
        const search = searchInput.value;
        const status = statusSelect.value;
        const sortBy = sortBySelect.value;
        
        // Build query string
        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (status && status !== 'all') params.append('status', status);
        if (sortBy) params.append('sort_by', sortBy);
        params.append('ajax', '1');
        
        // Show loading state
        document.getElementById('debtorsTableContainer').innerHTML = '<div class="text-center py-12"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div><p class="mt-2 text-gray-500">جاري التحميل...</p></div>';
        document.getElementById('paginationContainer').innerHTML = '';
        
        // Make AJAX request
        fetch(`{{ route('owner.debtors.index') }}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update table
            document.getElementById('debtorsTableContainer').innerHTML = data.html;
            
            // Update pagination
            document.getElementById('paginationContainer').innerHTML = data.pagination;
            
            // Update URL without reload
            const newUrl = `{{ route('owner.debtors.index') }}?${params.toString()}`;
            window.history.pushState({path: newUrl}, '', newUrl);
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('debtorsTableContainer').innerHTML = '<div class="text-center py-12 text-red-600">حدث خطأ أثناء تحميل البيانات. يرجى المحاولة مرة أخرى.</div>';
        });
    }

    // Debounced search
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            loadDebtors();
        }, 500); // Wait 500ms after user stops typing
    });

    // Status filter change
    statusSelect.addEventListener('change', function() {
        loadDebtors();
    });

    // Sort by change
    sortBySelect.addEventListener('change', function() {
        loadDebtors();
    });

    // Handle pagination links (delegate event)
    document.addEventListener('click', function(e) {
        if (e.target.closest('.pagination a')) {
            e.preventDefault();
            const url = e.target.closest('a').href;
            
            // Show loading state
            document.getElementById('debtorsTableContainer').innerHTML = '<div class="text-center py-12"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div><p class="mt-2 text-gray-500">جاري التحميل...</p></div>';
            document.getElementById('paginationContainer').innerHTML = '';
            
            // Make AJAX request
            fetch(url + '&ajax=1', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                // Update table
                document.getElementById('debtorsTableContainer').innerHTML = data.html;
                
                // Update pagination
                document.getElementById('paginationContainer').innerHTML = data.pagination;
                
                // Update URL
                window.history.pushState({path: url}, '', url);
                
                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('debtorsTableContainer').innerHTML = '<div class="text-center py-12 text-red-600">حدث خطأ أثناء تحميل البيانات. يرجى المحاولة مرة أخرى.</div>';
            });
        }
    });
</script>
@endsection
