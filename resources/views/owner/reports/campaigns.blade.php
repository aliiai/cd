@extends('layouts.owner')

@section('content')
<div class="py-12">
    <div class="w-full mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">تقرير الحملات</h1>
            <p class="text-gray-600 mt-2">عرض أداء حملات التحصيل</p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <form id="searchForm" method="GET" action="{{ route('owner.reports.campaigns') }}" class="flex flex-wrap items-end gap-4">
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
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>مجدول</option>
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
                        href="{{ route('owner.reports.campaigns') }}" 
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

        <!-- Campaigns Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto" id="tableContainer">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم الحملة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">القناة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عدد المديونين</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">وقت الإرسال</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="bg-white divide-y divide-gray-200">
                        @include('owner.reports.partials.campaigns-table')
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="paginationContainer" class="px-6 py-4 border-t border-gray-200">
                {{ $campaigns->links() }}
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

        const url = new URL('{{ route('owner.reports.campaigns') }}', window.location.origin);
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
            tableBody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">حدث خطأ أثناء البحث.</td></tr>';
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
            tableBody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">حدث خطأ أثناء تحميل الصفحة.</td></tr>';
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

