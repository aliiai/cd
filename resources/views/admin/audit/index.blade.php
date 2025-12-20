@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-primary-50 to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12">
    <div class="w-full mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">سجلات التدقيق والمراجعة</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400">عرض جميع الأنشطة والعمليات داخل المنصة</p>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="mb-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                    <svg class="w-5 h-5 ml-2 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    الفلاتر والبحث
                </h2>
            </div>
            <form id="filterForm" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="sm:col-span-2 lg:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">البحث</label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="search" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="ابحث بالمستخدم، العملية، أو السجل..."
                            class="w-full px-4 py-2.5 pl-12 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100 dark:placeholder-gray-400"
                        >
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Action Type Filter -->
                <div>
                    <label for="action_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">نوع العملية</label>
                    <select 
                        id="action_type" 
                        name="action_type" 
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100"
                    >
                        <option value="all" {{ request('action_type') == 'all' || !request('action_type') ? 'selected' : '' }}>جميع الأنواع</option>
                        <option value="add_debtor" {{ request('action_type') == 'add_debtor' ? 'selected' : '' }}>إضافة مديون</option>
                        <option value="create_campaign" {{ request('action_type') == 'create_campaign' ? 'selected' : '' }}>إنشاء حملة</option>
                        <option value="change_status" {{ request('action_type') == 'change_status' ? 'selected' : '' }}>تغيير حالة</option>
                        <option value="send_message" {{ request('action_type') == 'send_message' ? 'selected' : '' }}>إرسال رسالة</option>
                        <option value="create_ticket" {{ request('action_type') == 'create_ticket' ? 'selected' : '' }}>إنشاء شكوى</option>
                        <option value="subscription_request" {{ request('action_type') == 'subscription_request' ? 'selected' : '' }}>طلب اشتراك</option>
                    </select>
                </div>
                
                <!-- User Filter -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">المستخدم</label>
                    <select 
                        id="user_id" 
                        name="user_id" 
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100"
                    >
                        <option value="all" {{ request('user_id') == 'all' || !request('user_id') ? 'selected' : '' }}>جميع المستخدمين</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">الحالة</label>
                    <select 
                        id="status" 
                        name="status" 
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100"
                    >
                        <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>جميع الحالات</option>
                        <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>نجح</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>فشل</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>موافق عليه</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                    </select>
                </div>
                
                <!-- Date From -->
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">من تاريخ</label>
                    <input 
                        type="date" 
                        id="date_from" 
                        name="date_from" 
                        value="{{ request('date_from') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100"
                    >
                </div>
                
                <!-- Date To -->
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">إلى تاريخ</label>
                    <input 
                        type="date" 
                        id="date_to" 
                        name="date_to" 
                        value="{{ request('date_to') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100"
                    >
                </div>

                <!-- Reset Button -->
                <div class="flex items-end">
                    <a 
                        href="{{ route('admin.audit.index') }}" 
                        class="w-full px-6 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-200 font-medium text-center"
                    >
                        إعادة تعيين
                    </a>
                </div>
            </form>
        </div>

        <!-- Loading Indicator -->
        <div id="loadingIndicator" class="hidden fixed inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-8 flex items-center space-x-3 shadow-2xl">
                <svg class="animate-spin h-6 w-6 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-700 dark:text-gray-300 font-medium">جاري التحميل...</span>
            </div>
        </div>

        <!-- Audit Log Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-primary-600 to-secondary-600 dark:from-primary-700 dark:to-secondary-700 px-6 py-4">
                <h2 class="text-xl font-bold text-white">سجلات الأنشطة</h2>
            </div>
            <div id="auditTableContainer" class="p-6">
                @include('admin.audit.partials.audit-table', ['activities' => $activities])
            </div>
            
            <!-- Pagination -->
            <div id="paginationContainer" class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
                {{ $activities->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Load audit logs via AJAX
    function loadAuditLogs() {
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        const dateFrom = document.getElementById('date_from').value;
        const dateTo = document.getElementById('date_to').value;
        
        const params = new URLSearchParams(formData);
        if (dateFrom) params.append('date_from', dateFrom);
        if (dateTo) params.append('date_to', dateTo);
        
        // Show loading
        const loadingIndicator = document.getElementById('loadingIndicator');
        const tableContainer = document.getElementById('auditTableContainer');
        
        if (loadingIndicator) loadingIndicator.classList.remove('hidden');
        if (tableContainer) {
            tableContainer.innerHTML = '<div class="text-center py-12"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div><p class="mt-4 text-gray-500 dark:text-gray-400">جاري التحميل...</p></div>';
        }
        
        fetch(`{{ route('admin.audit.index') }}?${params.toString()}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (tableContainer) tableContainer.innerHTML = data.table;
            const paginationContainer = document.getElementById('paginationContainer');
            if (paginationContainer) paginationContainer.innerHTML = data.pagination;
            
            // Update URL
            const newUrl = window.location.pathname + '?' + params.toString();
            window.history.pushState({path: newUrl}, '', newUrl);
            
            if (loadingIndicator) loadingIndicator.classList.add('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            if (tableContainer) {
                tableContainer.innerHTML = '<div class="text-center py-12 text-red-500 dark:text-red-400">حدث خطأ أثناء تحميل البيانات</div>';
            }
            if (loadingIndicator) loadingIndicator.classList.add('hidden');
        });
    }

    // Debounced load function
    const debouncedLoadAuditLogs = debounce(loadAuditLogs, 500);

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const actionTypeSelect = document.getElementById('action_type');
        const userIdSelect = document.getElementById('user_id');
        const statusSelect = document.getElementById('status');
        const dateFromInput = document.getElementById('date_from');
        const dateToInput = document.getElementById('date_to');
        
        if (searchInput) {
            searchInput.addEventListener('input', debouncedLoadAuditLogs);
        }
        
        if (actionTypeSelect) {
            actionTypeSelect.addEventListener('change', loadAuditLogs);
        }
        
        if (userIdSelect) {
            userIdSelect.addEventListener('change', loadAuditLogs);
        }
        
        if (statusSelect) {
            statusSelect.addEventListener('change', loadAuditLogs);
        }
        
        if (dateFromInput) {
            dateFromInput.addEventListener('change', loadAuditLogs);
        }
        
        if (dateToInput) {
            dateToInput.addEventListener('change', loadAuditLogs);
        }
    });

    // Handle pagination clicks
    document.addEventListener('click', function(e) {
        const paginationLink = e.target.closest('.pagination a, a[href*="page="]');
        if (paginationLink && document.getElementById('paginationContainer')?.contains(paginationLink)) {
            e.preventDefault();
            const url = paginationLink.getAttribute('href');
            
            const loadingIndicator = document.getElementById('loadingIndicator');
            const tableContainer = document.getElementById('auditTableContainer');
            
            if (loadingIndicator) loadingIndicator.classList.remove('hidden');
            if (tableContainer) {
                tableContainer.innerHTML = '<div class="text-center py-12"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div><p class="mt-4 text-gray-500 dark:text-gray-400">جاري التحميل...</p></div>';
            }
            
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (tableContainer) tableContainer.innerHTML = data.table;
                const paginationContainer = document.getElementById('paginationContainer');
                if (paginationContainer) paginationContainer.innerHTML = data.pagination;
                window.history.pushState({path: url}, '', url);
                
                // Scroll to top
                tableContainer?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                
                if (loadingIndicator) loadingIndicator.classList.add('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                if (tableContainer) {
                    tableContainer.innerHTML = '<div class="text-center py-12 text-red-500 dark:text-red-400">حدث خطأ أثناء تحميل البيانات</div>';
                }
                if (loadingIndicator) loadingIndicator.classList.add('hidden');
            });
        }
    });
</script>
@endpush
@endsection
