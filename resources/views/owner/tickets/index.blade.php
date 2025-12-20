@extends('layouts.owner')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-primary-50 to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- ========== Header Section ========== --}}
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">الشكاوى والدعم</h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">إدارة شكاويك ومتابعة الردود</p>
            </div>
            <a href="{{ route('owner.tickets.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-500 to-secondary-500 hover:from-primary-600 hover:to-secondary-600 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                شكوى جديدة
            </a>
        </div>

        {{-- ========== Success/Error Messages ========== --}}
        @if(session('success'))
            <div class="mb-6 bg-gradient-to-r from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 border-l-4 border-emerald-500 dark:border-emerald-400 rounded-lg p-4 flex items-center shadow-md">
                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-emerald-800 dark:text-emerald-300 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 border-l-4 border-red-500 dark:border-red-400 rounded-lg p-4 flex items-center shadow-md">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <p class="text-red-800 dark:text-red-300 font-medium">{{ session('error') }}</p>
            </div>
        @endif

        {{-- ========== Filters and Search ========== --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8 border border-gray-200 dark:border-gray-700">
            <form id="filterForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Search --}}
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">البحث</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="ابحث برقم الشكوى، العنوان، أو الوصف..."
                               class="w-full pl-12 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm transition-colors">
                    </div>
                </div>
                
                {{-- Status Filter --}}
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">الحالة</label>
                    <select id="status" 
                            name="status" 
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm transition-colors">
                        <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>جميع الحالات</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>مفتوحة</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>قيد المعالجة</option>
                        <option value="waiting_user" {{ request('status') == 'waiting_user' ? 'selected' : '' }}>في انتظار المستخدم</option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>مغلقة</option>
                    </select>
                </div>
                
                {{-- Type Filter --}}
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">النوع</label>
                    <select id="type" 
                            name="type" 
                            class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm transition-colors">
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

        {{-- ========== Tickets Table ========== --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div id="ticketsTableContainer">
                    @include('owner.tickets.partials.tickets-table', ['tickets' => $tickets])
                </div>
                
                {{-- Pagination --}}
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
        
        // Add sort
        params.append('sort_by', 'created_at');
        params.append('sort_dir', 'desc');
        
        // Show loading
        document.getElementById('ticketsTableContainer').innerHTML = '<div class="text-center py-12"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600 dark:border-primary-400"></div><p class="mt-4 text-gray-500 dark:text-gray-400">جاري التحميل...</p></div>';
        
        fetch(`{{ route('owner.tickets.index') }}?${params.toString()}`, {
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
            
            // Update URL
            const newUrl = window.location.pathname + '?' + params.toString();
            window.history.pushState({path: newUrl}, '', newUrl);
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('ticketsTableContainer').innerHTML = '<div class="text-center py-12 text-red-500 dark:text-red-400">حدث خطأ أثناء تحميل البيانات</div>';
        });
    }

    // Debounced load function
    const debouncedLoadTickets = debounce(loadTickets, 500);

    // Event listeners
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

    // Handle pagination clicks
    document.addEventListener('click', function(e) {
        if (e.target.closest('.pagination a')) {
            e.preventDefault();
            const url = e.target.closest('a').href;
            
            document.getElementById('ticketsTableContainer').innerHTML = '<div class="text-center py-12"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600 dark:border-primary-400"></div><p class="mt-4 text-gray-500 dark:text-gray-400">جاري التحميل...</p></div>';
            
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
