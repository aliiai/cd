@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="w-full mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">إدارة المشرفين</h1>
                <p class="mt-2 text-sm text-gray-600">إدارة المشرفين والصلاحيات</p>
            </div>
            @can('create admins')
                <a href="{{ route('admin.admins.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                    + مشرف جديد
                </a>
            @endcan
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

        <!-- Filters and Search -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <form id="filterForm" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">البحث</label>
                        <input type="text" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="ابحث بالاسم أو البريد الإلكتروني..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <!-- Role Filter -->
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">الدور</label>
                        <select id="role" 
                                name="role" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="all" {{ request('role') == 'all' || !request('role') ? 'selected' : '' }}>جميع الأدوار</option>
                            <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    
                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                        <select id="status" 
                                name="status" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>جميع الحالات</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>موقوف</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <!-- Admins Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div id="adminsTableContainer">
                    @include('admin.admins.partials.admins-table', ['admins' => $admins])
                </div>
                
                <!-- Pagination -->
                <div id="paginationContainer" class="mt-4">
                    {{ $admins->appends(request()->query())->links() }}
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

    // Load admins via AJAX
    function loadAdmins() {
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);
        
        params.append('sort_by', 'created_at');
        params.append('sort_dir', 'desc');
        
        document.getElementById('adminsTableContainer').innerHTML = '<div class="text-center py-12"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div><p class="mt-4 text-gray-500">جاري التحميل...</p></div>';
        
        fetch(`{{ route('admin.admins.index') }}?${params.toString()}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('adminsTableContainer').innerHTML = data.table;
            document.getElementById('paginationContainer').innerHTML = data.pagination;
            
            const newUrl = window.location.pathname + '?' + params.toString();
            window.history.pushState({path: newUrl}, '', newUrl);
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('adminsTableContainer').innerHTML = '<div class="text-center py-12 text-red-500">حدث خطأ أثناء تحميل البيانات</div>';
        });
    }

    const debouncedLoadAdmins = debounce(loadAdmins, 500);

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const roleSelect = document.getElementById('role');
        const statusSelect = document.getElementById('status');
        
        if (searchInput) {
            searchInput.addEventListener('input', debouncedLoadAdmins);
        }
        
        if (roleSelect) {
            roleSelect.addEventListener('change', loadAdmins);
        }
        
        if (statusSelect) {
            statusSelect.addEventListener('change', loadAdmins);
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.pagination a')) {
            e.preventDefault();
            const url = e.target.closest('a').href;
            
            document.getElementById('adminsTableContainer').innerHTML = '<div class="text-center py-12"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div><p class="mt-4 text-gray-500">جاري التحميل...</p></div>';
            
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('adminsTableContainer').innerHTML = data.table;
                document.getElementById('paginationContainer').innerHTML = data.pagination;
                window.history.pushState({path: url}, '', url);
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('adminsTableContainer').innerHTML = '<div class="text-center py-12 text-red-500">حدث خطأ أثناء تحميل البيانات</div>';
            });
        }
    });
</script>
@endpush
@endsection

