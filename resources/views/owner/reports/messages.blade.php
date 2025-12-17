@extends('layouts.owner')

@section('content')
<div class="py-12">
    <div class="w-full mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">تقرير الرسائل</h1>
            <p class="text-gray-600 mt-2">ملخص شامل لجميع الرسائل المرسلة</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Messages -->
            <div class="bg-gradient-to-br from-white to-blue-50 rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 mb-1">إجمالي الرسائل</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalMessages }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl p-3 shadow-sm">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- SMS Count -->
            <div class="bg-gradient-to-br from-white to-green-50 rounded-xl shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 mb-1">رسائل SMS</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $smsCount }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-green-100 to-green-200 rounded-xl p-3 shadow-sm">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Success Rate -->
            <div class="bg-gradient-to-br from-white to-emerald-50 rounded-xl shadow-md p-6 border-l-4 border-emerald-500">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 mb-1">نسبة النجاح</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($successRate, 1) }}%</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $successCount }} رسالة</p>
                    </div>
                    <div class="bg-gradient-to-br from-emerald-100 to-emerald-200 rounded-xl p-3 shadow-sm">
                        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Failed Rate -->
            <div class="bg-gradient-to-br from-white to-red-50 rounded-xl shadow-md p-6 border-l-4 border-red-500">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 mb-1">نسبة الفشل</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($failedRate, 1) }}%</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $failedCount }} رسالة</p>
                    </div>
                    <div class="bg-gradient-to-br from-red-100 to-red-200 rounded-xl p-3 shadow-sm">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <form id="searchForm" method="GET" action="{{ route('owner.reports.messages') }}" class="flex flex-wrap items-end gap-4">
                <!-- Channel Filter -->
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">قناة التواصل</label>
                    <select 
                        id="channelInput"
                        name="channel" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="">جميع القنوات</option>
                        <option value="sms" {{ request('channel') == 'sms' ? 'selected' : '' }}>SMS</option>
                        <option value="email" {{ request('channel') == 'email' ? 'selected' : '' }}>Email</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                    <select 
                        id="statusInput"
                        name="status" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="">جميع الحالات</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                        <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>تم الإرسال</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>فشل</option>
                    </select>
                </div>

                <!-- Date From -->
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">من تاريخ</label>
                    <input 
                        type="date" 
                        id="dateFromInput"
                        name="date_from" 
                        value="{{ request('date_from') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                </div>

                <!-- Date To -->
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">إلى تاريخ</label>
                    <input 
                        type="date" 
                        id="dateToInput"
                        name="date_to" 
                        value="{{ request('date_to') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                </div>

                <!-- Reset Button -->
                <div>
                    <a 
                        href="{{ route('owner.reports.messages') }}" 
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-200 inline-block"
                    >
                        إعادة تعيين
                    </a>
                </div>
            </form>
        </div>

        <!-- Loading Indicator -->
        <div id="loadingIndicator" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
                <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-700">جاري البحث...</span>
            </div>
        </div>

        <!-- Messages Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto" id="tableContainer">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المديون</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">القناة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Preview</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="bg-white divide-y divide-gray-200">
                        @include('owner.reports.partials.messages-table')
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="paginationContainer" class="px-6 py-4 border-t border-gray-200">
                {{ $messages->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const channelInput = document.getElementById('channelInput');
    const statusInput = document.getElementById('statusInput');
    const dateFromInput = document.getElementById('dateFromInput');
    const dateToInput = document.getElementById('dateToInput');
    const tableBody = document.getElementById('tableBody');
    const paginationContainer = document.getElementById('paginationContainer');
    const loadingIndicator = document.getElementById('loadingIndicator');
    
    let filterTimeout;
    const filterDelay = 300;

    function performSearch() {
        const channel = channelInput.value;
        const status = statusInput.value;
        const dateFrom = dateFromInput.value;
        const dateTo = dateToInput.value;

        loadingIndicator.classList.remove('hidden');

        const url = new URL('{{ route('owner.reports.messages') }}', window.location.origin);
        if (channel) url.searchParams.set('channel', channel);
        if (status) url.searchParams.set('status', status);
        if (dateFrom) url.searchParams.set('date_from', dateFrom);
        if (dateTo) url.searchParams.set('date_to', dateTo);

        fetch(url.toString(), {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
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
            tableBody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-red-500">حدث خطأ أثناء البحث.</td></tr>';
        });
    }

    [channelInput, statusInput, dateFromInput, dateToInput].forEach(input => {
        input.addEventListener('change', function() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(performSearch, filterDelay);
        });
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
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            tableBody.innerHTML = data.html;
            if (data.pagination) {
                paginationContainer.innerHTML = data.pagination;
            }
            const urlObj = new URL(url, window.location.origin);
            if (urlObj.searchParams.has('channel')) channelInput.value = urlObj.searchParams.get('channel') || '';
            if (urlObj.searchParams.has('status')) statusInput.value = urlObj.searchParams.get('status') || '';
            if (urlObj.searchParams.has('date_from')) dateFromInput.value = urlObj.searchParams.get('date_from') || '';
            if (urlObj.searchParams.has('date_to')) dateToInput.value = urlObj.searchParams.get('date_to') || '';
            window.history.pushState({}, '', url);
            document.getElementById('tableContainer').scrollIntoView({ behavior: 'smooth', block: 'start' });
            loadingIndicator.classList.add('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            loadingIndicator.classList.add('hidden');
            tableBody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-red-500">حدث خطأ أثناء تحميل الصفحة.</td></tr>';
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

