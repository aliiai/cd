@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-primary-50 to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12">
    <div class="w-full mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">إدارة الشكاوى</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400">عرض وإدارة جميع الشكاوى من المستخدمين</p>
        </div>

        <!-- Statistics Cards -->
        @php
            $allTickets = \App\Models\Ticket::count();
            $openTickets = \App\Models\Ticket::where('status', 'open')->count();
            $inProgressTickets = \App\Models\Ticket::where('status', 'in_progress')->count();
            $closedTickets = \App\Models\Ticket::where('status', 'closed')->count();
        @endphp
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <!-- Total Tickets -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border-2 border-primary-200 dark:border-primary-800 p-6 hover:shadow-xl transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">إجمالي الشكاوى</p>
                        <p class="text-3xl font-bold text-primary-600 dark:text-primary-400">{{ $allTickets }}</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Open Tickets -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border-2 border-yellow-200 dark:border-yellow-800 p-6 hover:shadow-xl transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">مفتوحة</p>
                        <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ $openTickets }}</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- In Progress Tickets -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border-2 border-blue-200 dark:border-blue-800 p-6 hover:shadow-xl transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">قيد المعالجة</p>
                        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $inProgressTickets }}</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Closed Tickets -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border-2 border-secondary-200 dark:border-secondary-800 p-6 hover:shadow-xl transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">مغلقة</p>
                        <p class="text-3xl font-bold text-secondary-600 dark:text-secondary-400">{{ $closedTickets }}</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-secondary-500 to-secondary-600 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl mb-6 border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <form id="filterForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">البحث</label>
                        <div class="relative">
                            <input type="text" 
                                   id="search" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="ابحث برقم الشكوى، العنوان، أو اسم المستخدم..."
                                   class="w-full pl-12 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">الحالة</label>
                        <select id="status" 
                                name="status" 
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100">
                            <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>جميع الحالات</option>
                            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>مفتوحة</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>قيد المعالجة</option>
                            <option value="waiting_user" {{ request('status') == 'waiting_user' ? 'selected' : '' }}>في انتظار المستخدم</option>
                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>مغلقة</option>
                        </select>
                    </div>
                    
                    <!-- Type Filter -->
                    <div>
                        <label for="type" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">النوع</label>
                        <select id="type" 
                                name="type" 
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100">
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
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div id="ticketsTableContainer">
                    @include('admin.tickets.partials.tickets-table', ['tickets' => $tickets])
                </div>
                
                <!-- Pagination -->
                <div id="paginationContainer" class="mt-6">
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
        
        document.getElementById('ticketsTableContainer').innerHTML = '<div class="text-center py-12"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div><p class="mt-4 text-gray-500 dark:text-gray-400">جاري التحميل...</p></div>';
        
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
            
            document.getElementById('ticketsTableContainer').innerHTML = '<div class="text-center py-12"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div><p class="mt-4 text-gray-500 dark:text-gray-400">جاري التحميل...</p></div>';
            
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
                document.getElementById('ticketsTableContainer').innerHTML = '<div class="text-center py-12 text-red-500 dark:text-red-400">حدث خطأ أثناء تحميل البيانات</div>';
            });
        }
    });
</script>
@endpush
@endsection

