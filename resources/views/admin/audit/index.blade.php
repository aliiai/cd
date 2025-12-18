@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="w-full mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Audit & Logs</h1>
            <p class="mt-2 text-sm text-gray-600">عرض جميع الأنشطة والعمليات داخل المنصة</p>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <form id="filterForm" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-7 gap-4">
                    <!-- Search -->
                    <div class="sm:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">البحث</label>
                        <input type="text" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="ابحث بالمستخدم، العملية، أو السجل..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    
                    <!-- Action Type Filter -->
                    <div>
                        <label for="action_type" class="block text-sm font-medium text-gray-700 mb-2">نوع العملية</label>
                        <select id="action_type" 
                                name="action_type" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
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
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">المستخدم</label>
                        <select id="user_id" 
                                name="user_id" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            <option value="all" {{ request('user_id') == 'all' || !request('user_id') ? 'selected' : '' }}>جميع المستخدمين</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                        <select id="status" 
                                name="status" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
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
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">من تاريخ</label>
                        <input type="date" 
                               id="date_from" 
                               name="date_from" 
                               value="{{ request('date_from') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    
                    <!-- Date To -->
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">إلى تاريخ</label>
                        <input type="date" 
                               id="date_to" 
                               name="date_to" 
                               value="{{ request('date_to') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </form>
            </div>
        </div>

        <!-- Audit Log Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div id="auditTableContainer">
                    @include('admin.audit.partials.audit-table', ['activities' => $activities])
                </div>
                
                <!-- Pagination -->
                <div id="paginationContainer" class="mt-4">
                    {{ $activities->appends(request()->query())->links() }}
                </div>
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
        document.getElementById('auditTableContainer').innerHTML = '<div class="text-center py-12"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div><p class="mt-4 text-gray-500">جاري التحميل...</p></div>';
        
        fetch(`{{ route('admin.audit.index') }}?${params.toString()}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('auditTableContainer').innerHTML = data.table;
            document.getElementById('paginationContainer').innerHTML = data.pagination;
            
            // Update URL
            const newUrl = window.location.pathname + '?' + params.toString();
            window.history.pushState({path: newUrl}, '', newUrl);
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('auditTableContainer').innerHTML = '<div class="text-center py-12 text-red-500">حدث خطأ أثناء تحميل البيانات</div>';
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
        if (e.target.closest('.pagination a')) {
            e.preventDefault();
            const url = e.target.closest('a').href;
            
            document.getElementById('auditTableContainer').innerHTML = '<div class="text-center py-12"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div><p class="mt-4 text-gray-500">جاري التحميل...</p></div>';
            
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('auditTableContainer').innerHTML = data.table;
                document.getElementById('paginationContainer').innerHTML = data.pagination;
                window.history.pushState({path: url}, '', url);
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('auditTableContainer').innerHTML = '<div class="text-center py-12 text-red-500">حدث خطأ أثناء تحميل البيانات</div>';
            });
        }
    });
</script>
@endpush
@endsection
