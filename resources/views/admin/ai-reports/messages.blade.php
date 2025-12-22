@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-primary-50 to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12">
    <div class="w-full mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-4 sm:mb-6 md:mb-8">
            <a href="{{ route('admin.ai-reports.index') }}" 
               class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 text-xs sm:text-sm mb-3 sm:mb-4 transition-colors">
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                العودة إلى التقارير
            </a>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-1 sm:mb-2">تقارير الرسائل</h1>
                    <p class="text-xs sm:text-sm lg:text-base text-gray-600 dark:text-gray-400">عرض شامل لجميع الرسائل المرسلة - مرجع رسمي للنزاعات والمراجعات القانونية</p>
                </div>
                <a href="{{ route('admin.ai-reports.messages.export', request()->query()) }}" 
                   class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-900 focus:outline-none focus:border-primary-900 focus:ring focus:ring-primary-300 disabled:opacity-25 transition">
                    <i class="fas fa-download ml-2"></i>
                    تحميل PDF
                </a>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-4 sm:p-6 mb-4 sm:mb-6">
            <form id="searchForm" method="GET" action="{{ route('admin.ai-reports.messages') }}" class="flex flex-wrap items-end gap-3 sm:gap-4">
                <!-- Search -->
                <div class="flex-1 min-w-full sm:min-w-[250px]">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 sm:mb-2">البحث</label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="searchInput"
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="البحث بمقدم الخدمة، المديون، أو نص الرسالة..."
                            class="w-full px-3 py-2 sm:px-4 sm:py-2.5 pl-10 sm:pl-12 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100 dark:placeholder-gray-400"
                            autocomplete="off"
                        >
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Channel Filter -->
                <div class="flex-1 min-w-full sm:min-w-[150px]">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 sm:mb-2">قناة التواصل</label>
                    <select 
                        id="channelInput"
                        name="channel" 
                        class="w-full px-3 py-2 sm:px-4 sm:py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100"
                    >
                        <option value="">جميع القنوات</option>
                        <option value="sms" {{ request('channel') == 'sms' ? 'selected' : '' }}>SMS</option>
                        <option value="email" {{ request('channel') == 'email' ? 'selected' : '' }}>Email</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="flex-1 min-w-full sm:min-w-[150px]">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 sm:mb-2">الحالة</label>
                    <select 
                        id="statusInput"
                        name="status" 
                        class="w-full px-3 py-2 sm:px-4 sm:py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100"
                    >
                        <option value="">جميع الحالات</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                        <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>تم الإرسال</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>فشل</option>
                    </select>
                </div>

                <!-- Reset Button -->
                <div class="flex items-end w-full sm:w-auto">
                    <a 
                        href="{{ route('admin.ai-reports.messages') }}" 
                        class="w-full sm:w-auto text-center px-4 sm:px-6 py-2 sm:py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-200 font-medium text-sm sm:text-base"
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
                <span class="text-gray-700 dark:text-gray-300 font-medium">جاري البحث...</span>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-primary-600 to-secondary-600 dark:from-primary-700 dark:to-secondary-700 px-4 sm:px-6 py-3 sm:py-4">
                <h2 class="text-base sm:text-lg lg:text-xl font-bold text-white">قائمة الرسائل</h2>
            </div>
            <div class="overflow-x-auto" id="tableContainer">
                <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">مقدم الخدمة</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">المستلم</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">قناة الإرسال</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">نص الرسالة</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">وقت الإرسال</th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">حالة الإرسال</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @include('admin.ai-reports.partials.messages-table')
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="paginationContainer" class="px-3 sm:px-6 py-3 sm:py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
                {{ $messages->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const channelInput = document.getElementById('channelInput');
    const statusInput = document.getElementById('statusInput');
    const tableBody = document.getElementById('tableBody');
    const paginationContainer = document.getElementById('paginationContainer');
    const loadingIndicator = document.getElementById('loadingIndicator');
    
    let searchTimeout;
    const searchDelay = 500;

    function performSearch() {
        const search = searchInput.value;
        const channel = channelInput.value;
        const status = statusInput.value;

        loadingIndicator.classList.remove('hidden');

        const url = new URL('{{ route('admin.ai-reports.messages') }}', window.location.origin);
        if (search) url.searchParams.set('search', search);
        if (channel) url.searchParams.set('channel', channel);
        if (status) url.searchParams.set('status', status);

        fetch(url.toString(), {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            tableBody.innerHTML = data.html;
            if (data.pagination) {
                paginationContainer.innerHTML = data.pagination;
            }
            window.history.pushState({}, '', url.toString());
            loadingIndicator.classList.add('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            loadingIndicator.classList.add('hidden');
            tableBody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">حدث خطأ أثناء البحث. يرجى المحاولة مرة أخرى.</td></tr>';
        });
    }

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, searchDelay);
    });

    [channelInput, statusInput].forEach(input => {
        input.addEventListener('change', performSearch);
    });

    function loadPage(url) {
        if (!url || url === '#' || url === 'javascript:void(0)') return;

        loadingIndicator.classList.remove('hidden');

        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            tableBody.innerHTML = data.html;
            if (data.pagination) {
                paginationContainer.innerHTML = data.pagination;
            }
            const urlObj = new URL(url, window.location.origin);
            if (urlObj.searchParams.has('search')) {
                searchInput.value = urlObj.searchParams.get('search') || '';
            }
            if (urlObj.searchParams.has('channel')) {
                channelInput.value = urlObj.searchParams.get('channel') || '';
            }
            if (urlObj.searchParams.has('status')) {
                statusInput.value = urlObj.searchParams.get('status') || '';
            }
            window.history.pushState({}, '', url);
            document.getElementById('tableContainer').scrollIntoView({ behavior: 'smooth', block: 'start' });
            loadingIndicator.classList.add('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            loadingIndicator.classList.add('hidden');
            tableBody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">حدث خطأ أثناء تحميل الصفحة. يرجى المحاولة مرة أخرى.</td></tr>';
        });
    }

    document.addEventListener('click', function(e) {
        const paginationLink = e.target.closest('a[href*="page="]');
        if (paginationLink && paginationContainer.contains(paginationLink)) {
            e.preventDefault();
            const url = paginationLink.getAttribute('href');
            if (url && url !== '#' && url !== 'javascript:void(0)') {
                loadPage(url);
            }
        }
    });

    window.addEventListener('popstate', function(e) {
        window.location.reload();
    });
});
</script>
@endpush
@endsection
