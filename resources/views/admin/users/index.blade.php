@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-primary-50 to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12">
    <div class="w-full mx-auto sm:px-6 lg:px-8">
        
        {{-- ========== Header Section ========== --}}
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">إدارة المستخدمين</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">إدارة جميع المستخدمين في النظام</p>
        </div>

        {{-- ========== Success/Error Messages ========== --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        {{-- ========== Filters and Search ========== --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 mb-6">
            <div class="p-6">
                <form id="filterForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    {{-- Search --}}
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 ml-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                البحث
                            </div>
                        </label>
                        <input type="text" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="ابحث بالاسم أو البريد الإلكتروني..."
                               class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-primary-400 dark:focus:border-primary-400 transition-colors">
                    </div>
                    
                    {{-- Status Filter --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 ml-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                                حالة الحساب
                            </div>
                        </label>
                        <select id="status" 
                                name="status" 
                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-primary-400 dark:focus:border-primary-400 appearance-none cursor-pointer transition-colors">
                            <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>لجميع الحالات</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>موقوف</option>
                        </select>
                    </div>
                    
                    {{-- Sort By --}}
                    <div>
                        <label for="sort_by" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 ml-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>
                                </svg>
                                ترتيب حسب
                            </div>
                        </label>
                        <select id="sort_by" 
                                name="sort_by" 
                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:focus:ring-primary-400 dark:focus:border-primary-400 appearance-none cursor-pointer transition-colors">
                            <option value="created_at" {{ request('sort_by') == 'created_at' || !request('sort_by') ? 'selected' : '' }}>تاريخ التسجيل</option>
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>الاسم</option>
                            <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>البريد الإلكتروني</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        {{-- ========== Users Table ========== --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div id="usersTableContainer">
                    @include('admin.users.partials.users-table', ['users' => $users])
                </div>
                
                {{-- Pagination --}}
                <div id="paginationContainer" class="mt-6">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ========== Scripts Section ========== --}}
@push('styles')
<style>
    /* Custom Select Arrow for RTL */
    select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: left 0.75rem center;
        background-size: 1.25rem;
        padding-right: 2.5rem;
    }
    
    select:focus {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%235C70E0'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
    }
    
    .dark select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%29CA3AF'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
    }
    
    .dark select:focus {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23928AFF'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
    }
</style>
@endpush

@push('scripts')

<script>
    // ========== Debounce function ==========
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

    // ========== Load users via AJAX ==========
    function loadUsers() {
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);
        
        const sortBy = document.getElementById('sort_by').value;
        params.append('sort_by', sortBy);
        params.append('sort_dir', 'desc');
        
        // Show loading
        document.getElementById('usersTableContainer').innerHTML = '<div class="text-center py-12"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div><p class="mt-4 text-gray-500 dark:text-gray-400">جاري التحميل...</p></div>';
        
        fetch(`{{ route('admin.users.index') }}?${params.toString()}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('usersTableContainer').innerHTML = data.table;
            document.getElementById('paginationContainer').innerHTML = data.pagination;
            
            const newUrl = window.location.pathname + '?' + params.toString();
            window.history.pushState({path: newUrl}, '', newUrl);
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('usersTableContainer').innerHTML = '<div class="text-center py-12 text-red-500 dark:text-red-400">حدث خطأ أثناء تحميل البيانات</div>';
        });
    }

    const debouncedLoadUsers = debounce(loadUsers, 500);

    // ========== Event listeners ==========
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const statusSelect = document.getElementById('status');
        const sortBySelect = document.getElementById('sort_by');
        
        if (searchInput) {
            searchInput.addEventListener('input', debouncedLoadUsers);
        }
        
        if (statusSelect) {
            statusSelect.addEventListener('change', loadUsers);
        }
        
        if (sortBySelect) {
            sortBySelect.addEventListener('change', loadUsers);
        }
    });

    // ========== Handle pagination clicks ==========
    document.addEventListener('click', function(e) {
        if (e.target.closest('.pagination a')) {
            e.preventDefault();
            const url = e.target.closest('a').href;
            
            document.getElementById('usersTableContainer').innerHTML = '<div class="text-center py-12"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div><p class="mt-4 text-gray-500 dark:text-gray-400">جاري التحميل...</p></div>';
            
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('usersTableContainer').innerHTML = data.table;
                document.getElementById('paginationContainer').innerHTML = data.pagination;
                window.history.pushState({path: url}, '', url);
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('usersTableContainer').innerHTML = '<div class="text-center py-12 text-red-500 dark:text-red-400">حدث خطأ أثناء تحميل البيانات</div>';
            });
        }
    });

    // ========== Handle form submissions with Sweet Alert ==========
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (form.closest('#usersTableContainer') && form.method === 'POST') {
            e.preventDefault();
            
            const action = form.action;
            const isToggleStatus = action.includes('toggle-status');
            const button = form.querySelector('button[type="submit"]');
            const userIsActive = button?.classList.contains('bg-red-100');
            const actionText = userIsActive ? 'إيقاف' : 'تفعيل';
            
            swalConfirm({
                text: `هل تريد ${actionText} هذا الحساب؟`,
                confirmButtonColor: userIsActive ? '#EF4444' : '#10B981',
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData(form);
                    
                    fetch(action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            swalSuccess(data.message || `تم ${actionText} الحساب بنجاح`).then(() => {
                                loadUsers();
                            });
                        } else {
                            swalError(data.message || 'حدث خطأ أثناء تنفيذ العملية');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        swalError('حدث خطأ أثناء تنفيذ العملية');
                    });
                }
            });
        }
    });
</script>
@endpush
@endsection
