@extends('layouts.owner')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-primary-50 to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- ========== Header Section ========== --}}
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">تقرير الحملات</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400">عرض أداء حملات التحصيل</p>
        </div>

        {{-- ========== Statistics Cards ========== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            {{-- بطاقة إجمالي الحملات --}}
            @include('owner.dashboard.partials.stat-card-primary', [
                'value' => number_format($totalCampaigns),
                'subtitle' => 'حملة',
                'gradientFrom' => 'from-primary-500',
                'gradientTo' => 'to-primary-600',
                'badge' => 'إجمالي',
                'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>'
            ])

            {{-- بطاقة حملات SMS --}}
            @include('owner.dashboard.partials.stat-card-primary', [
                'value' => number_format($smsCount),
                'subtitle' => 'حملة SMS',
                'gradientFrom' => 'from-blue-500',
                'gradientTo' => 'to-blue-600',
                'badge' => 'SMS',
                'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>'
            ])

            {{-- بطاقة حملات Email --}}
            @include('owner.dashboard.partials.stat-card-primary', [
                'value' => number_format($emailCount),
                'subtitle' => 'حملة Email',
                'gradientFrom' => 'from-emerald-500',
                'gradientTo' => 'to-emerald-600',
                'badge' => 'Email',
                'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>'
            ])

            {{-- بطاقة حملات ناجحة --}}
            @include('owner.dashboard.partials.stat-card-primary', [
                'value' => number_format($sentCount),
                'subtitle' => 'حملة ناجحة',
                'gradientFrom' => 'from-green-500',
                'gradientTo' => 'to-green-600',
                'badge' => 'نجحت',
                'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
            ])

        </div>

        {{-- ========== Filters ========== --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8 border border-gray-200 dark:border-gray-700">
            <form id="searchForm" method="GET" action="{{ route('owner.reports.campaigns') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                {{-- Channel Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">قناة التواصل</label>
                    <select 
                        id="channelInput"
                        name="channel" 
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm transition-colors"
                    >
                        <option value="">جميع القنوات</option>
                        <option value="sms" {{ request('channel') == 'sms' ? 'selected' : '' }}>SMS</option>
                        <option value="email" {{ request('channel') == 'email' ? 'selected' : '' }}>Email</option>
                    </select>
                </div>

                {{-- Status Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">الحالة</label>
                    <select 
                        id="statusInput"
                        name="status" 
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm transition-colors"
                    >
                        <option value="">جميع الحالات</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                        <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>تم الإرسال</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>مجدول</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>فشل</option>
                    </select>
                </div>

                {{-- Date From --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">من تاريخ</label>
                    <input 
                        type="date" 
                        id="dateFromInput"
                        name="date_from" 
                        value="{{ request('date_from') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm transition-colors"
                    >
                </div>

                {{-- Date To --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">إلى تاريخ</label>
                    <input 
                        type="date" 
                        id="dateToInput"
                        name="date_to" 
                        value="{{ request('date_to') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm transition-colors"
                    >
                </div>

                {{-- Reset Button --}}
                <div class="flex items-end">
                    <a 
                        href="{{ route('owner.reports.campaigns') }}" 
                        class="w-full px-6 py-2.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors duration-200 text-center font-medium shadow-sm hover:shadow-md"
                    >
                        إعادة تعيين
                    </a>
                </div>
            </form>
        </div>

        {{-- ========== Campaigns Table ========== --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="overflow-x-auto" id="tableContainer">
                <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gradient-to-r from-primary-500 to-secondary-500">
                        <tr>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">اسم الحملة</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">القناة</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">عدد المديونين</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">وقت الإرسال</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">الحالة</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @include('owner.reports.partials.campaigns-table')
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div id="paginationContainer" class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $campaigns->links() }}
            </div>
        </div>

        {{-- ========== Loading Indicator ========== --}}
        <div id="loadingIndicator" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-8 flex items-center space-x-4 shadow-2xl">
                <svg class="animate-spin h-8 w-8 text-primary-600 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-700 dark:text-gray-300 font-medium">جاري البحث...</span>
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
            tableBody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-red-500 dark:text-red-400">حدث خطأ أثناء البحث.</td></tr>';
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
            tableBody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-red-500 dark:text-red-400">حدث خطأ أثناء تحميل الصفحة.</td></tr>';
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
