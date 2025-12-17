@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="w-full mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">إدارة الشكاوى</h1>
            <p class="mt-2 text-sm text-gray-600">عرض وإدارة جميع الشكاوى من المستخدمين</p>
        </div>

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
                               placeholder="ابحث برقم الشكوى، العنوان، أو اسم المستخدم..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                        <select id="status" 
                                name="status" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>جميع الحالات</option>
                            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>مفتوحة</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>قيد المعالجة</option>
                            <option value="waiting_user" {{ request('status') == 'waiting_user' ? 'selected' : '' }}>في انتظار المستخدم</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>مغلقة</option>
                        </select>
                    </div>
                    
                    <!-- Type Filter -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">النوع</label>
                        <select id="type" 
                                name="type" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="all" {{ request('type') == 'all' || !request('type') ? 'selected' : '' }}>جميع الأنواع</option>
                            <option value="technical" {{ request('type') == 'technical' ? 'selected' : '' }}>مشكلة تقنية</option>
                            <option value="subscription" {{ request('type') == 'subscription' ? 'selected' : '' }}>مشكلة اشتراك</option>
                            <option value="messages" {{ request('type') == 'messages' ? 'selected' : '' }}>مشكلة رسائل</option>
                            <option value="general" {{ request('type') == 'general' ? 'selected' : '' }}>استفسار عام</option>
                            <option value="suggestion" {{ request('type') == 'suggestion' ? 'selected' : '' }}>اقتراح</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tickets Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div id="ticketsTableContainer">
                    @include('admin.tickets.partials.tickets-table', ['tickets' => $tickets])
                </div>
                
                <!-- Pagination -->
                <div id="paginationContainer" class="mt-4">
                    {{ $tickets->appends(request()->query())->links() }}
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

    // Load tickets via AJAX
    function loadTickets() {
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);
        
        params.append('sort_by', 'created_at');
        params.append('sort_dir', 'desc');
        
        document.getElementById('ticketsTableContainer').innerHTML = '<div class="text-center py-12"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div><p class="mt-4 text-gray-500">جاري التحميل...</p></div>';
        
        fetch(`{{ route('admin.tickets.index') }}?${params.toString()}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('ticketsTableContainer').innerHTML = data.table;
            document.getElementById('paginationContainer').innerHTML = data.pagination;
            
            const newUrl = window.location.pathname + '?' + params.toString();
            window.history.pushState({path: newUrl}, '', newUrl);
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('ticketsTableContainer').innerHTML = '<div class="text-center py-12 text-red-500">حدث خطأ أثناء تحميل البيانات</div>';
        });
    }

    const debouncedLoadTickets = debounce(loadTickets, 500);

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const statusSelect = document.getElementById('status');
        const typeSelect = document.getElementById('type');
        
        if (searchInput) {
            searchInput.addEventListener('input', debouncedLoadTickets);
        }
        
        if (statusSelect) {
            statusSelect.addEventListener('change', loadTickets);
        }
        
        if (typeSelect) {
            typeSelect.addEventListener('change', loadTickets);
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.pagination a')) {
            e.preventDefault();
            const url = e.target.closest('a').href;
            
            document.getElementById('ticketsTableContainer').innerHTML = '<div class="text-center py-12"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div><p class="mt-4 text-gray-500">جاري التحميل...</p></div>';
            
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('ticketsTableContainer').innerHTML = data.table;
                document.getElementById('paginationContainer').innerHTML = data.pagination;
                window.history.pushState({path: url}, '', url);
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('ticketsTableContainer').innerHTML = '<div class="text-center py-12 text-red-500">حدث خطأ أثناء تحميل البيانات</div>';
            });
        }
    });
</script>
@endpush
@endsection

