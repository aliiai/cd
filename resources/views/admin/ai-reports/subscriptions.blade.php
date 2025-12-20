@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-primary-50 to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12">
    <div class="w-full mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.ai-reports.index') }}" 
               class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 text-sm mb-4 transition-colors">
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                العودة إلى التقارير
            </a>
            <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">تقارير الاشتراكات</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400">تحليل شامل لأداء الاشتراكات والإحصائيات</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Active Subscriptions -->
            <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg border-2 border-primary-200 dark:border-primary-800 hover:border-primary-400 dark:hover:border-primary-600 overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">الاشتراكات النشطة</p>
                            <p class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ $activeSubscriptions }}</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-primary-500 to-primary-600 dark:from-primary-600 dark:to-primary-700 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                        <div class="w-2 h-2 bg-primary-500 rounded-full ml-2"></div>
                        <span>اشتراك نشط حالياً</span>
                    </div>
                </div>
                <div class="h-1 bg-gradient-to-r from-primary-500 to-primary-600"></div>
            </div>

            <!-- Most Used Subscription -->
            <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg border-2 border-secondary-200 dark:border-secondary-800 hover:border-secondary-400 dark:hover:border-secondary-600 overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">أكثر باقة استخدامًا</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2 line-clamp-1">{{ $mostUsedSubscription->name ?? 'غير محدد' }}</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-secondary-500 to-secondary-600 dark:from-secondary-600 dark:to-secondary-700 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                            <div class="w-2 h-2 bg-secondary-500 rounded-full ml-2"></div>
                            <span>{{ $mostUsedSubscription->user_subscriptions_count ?? 0 }} اشتراك</span>
                        </div>
                    </div>
                </div>
                <div class="h-1 bg-gradient-to-r from-secondary-500 to-secondary-600"></div>
            </div>

            <!-- Renewal Rate -->
            <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg border-2 border-primary-300 dark:border-primary-700 hover:border-primary-500 dark:hover:border-primary-500 overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">معدل التجديد</p>
                            <p class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ number_format($renewalRate, 1) }}%</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-primary-400 to-secondary-500 dark:from-primary-500 dark:to-secondary-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                        <div class="w-2 h-2 bg-gradient-to-r from-primary-500 to-secondary-500 rounded-full ml-2"></div>
                        <span>من إجمالي الاشتراكات</span>
                    </div>
                </div>
                <div class="h-1 bg-gradient-to-r from-primary-400 via-primary-500 to-secondary-500"></div>
            </div>

            <!-- Rejected Requests -->
            <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg border-2 border-secondary-300 dark:border-secondary-700 hover:border-secondary-500 dark:hover:border-secondary-500 overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">الطلبات المرفوضة</p>
                            <p class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ $rejectedRequests }}</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-secondary-400 to-primary-500 dark:from-secondary-500 dark:to-primary-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                        <div class="w-2 h-2 bg-gradient-to-r from-secondary-500 to-primary-500 rounded-full ml-2"></div>
                        <span>طلب مرفوض</span>
                    </div>
                </div>
                <div class="h-1 bg-gradient-to-r from-secondary-400 via-secondary-500 to-primary-500"></div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <form id="searchForm" method="GET" action="{{ route('admin.ai-reports.subscriptions') }}" class="flex flex-wrap items-end gap-4">
                <!-- Subscription Filter -->
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">الباقة</label>
                    <select 
                        id="subscriptionInput"
                        name="subscription_id" 
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-secondary-500 focus:border-secondary-500 dark:bg-gray-700 dark:text-gray-100"
                    >
                        <option value="">جميع الباقات</option>
                        @foreach($allSubscriptions as $sub)
                            <option value="{{ $sub->id }}" {{ request('subscription_id') == $sub->id ? 'selected' : '' }}>
                                {{ $sub->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">الحالة</label>
                    <select 
                        id="statusInput"
                        name="status" 
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-secondary-500 focus:border-secondary-500 dark:bg-gray-700 dark:text-gray-100"
                    >
                        <option value="">جميع الحالات</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>منتهي</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                    </select>
                </div>

                <!-- Date From -->
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">من تاريخ</label>
                    <input 
                        type="date" 
                        id="dateFromInput"
                        name="date_from" 
                        value="{{ request('date_from') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-secondary-500 focus:border-secondary-500 dark:bg-gray-700 dark:text-gray-100"
                    >
                </div>

                <!-- Date To -->
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">إلى تاريخ</label>
                    <input 
                        type="date" 
                        id="dateToInput"
                        name="date_to" 
                        value="{{ request('date_to') }}"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-secondary-500 focus:border-secondary-500 dark:bg-gray-700 dark:text-gray-100"
                    >
                </div>

                <!-- Reset Button -->
                <div class="flex items-end">
                    <a 
                        href="{{ route('admin.ai-reports.subscriptions') }}" 
                        class="px-6 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-200 font-medium"
                    >
                        إعادة تعيين
                    </a>
                </div>
            </form>
        </div>

        <!-- Loading Indicator -->
        <div id="loadingIndicator" class="hidden fixed inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-8 flex items-center space-x-3 shadow-2xl">
                <svg class="animate-spin h-6 w-6 text-secondary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-700 dark:text-gray-300 font-medium">جاري البحث...</span>
            </div>
        </div>

        <!-- Subscriptions Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-secondary-600 to-primary-600 dark:from-secondary-700 dark:to-primary-700 px-6 py-4">
                <h2 class="text-xl font-bold text-white">قائمة الاشتراكات</h2>
            </div>
            <div class="overflow-x-auto" id="tableContainer">
                <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">المستخدم</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">الباقة</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">الحالة</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">تاريخ البدء</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">تاريخ الانتهاء</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">السعر</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @include('admin.ai-reports.partials.subscriptions-table')
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="paginationContainer" class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
                {{ $subscriptions->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const subscriptionInput = document.getElementById('subscriptionInput');
    const statusInput = document.getElementById('statusInput');
    const dateFromInput = document.getElementById('dateFromInput');
    const dateToInput = document.getElementById('dateToInput');
    const tableBody = document.getElementById('tableBody');
    const paginationContainer = document.getElementById('paginationContainer');
    const loadingIndicator = document.getElementById('loadingIndicator');
    
    let filterTimeout;
    const filterDelay = 300;

    function performSearch() {
        const subscriptionId = subscriptionInput.value;
        const status = statusInput.value;
        const dateFrom = dateFromInput.value;
        const dateTo = dateToInput.value;

        loadingIndicator.classList.remove('hidden');

        const url = new URL('{{ route('admin.ai-reports.subscriptions') }}', window.location.origin);
        if (subscriptionId) url.searchParams.set('subscription_id', subscriptionId);
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

    [subscriptionInput, statusInput, dateFromInput, dateToInput].forEach(input => {
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
            if (urlObj.searchParams.has('subscription_id')) {
                subscriptionInput.value = urlObj.searchParams.get('subscription_id') || '';
            }
            if (urlObj.searchParams.has('status')) {
                statusInput.value = urlObj.searchParams.get('status') || '';
            }
            if (urlObj.searchParams.has('date_from')) {
                dateFromInput.value = urlObj.searchParams.get('date_from') || '';
            }
            if (urlObj.searchParams.has('date_to')) {
                dateToInput.value = urlObj.searchParams.get('date_to') || '';
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
