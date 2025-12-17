@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="w-full mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">تقارير الاشتراكات</h1>
            <p class="text-gray-600 mt-2">تحليل شامل لأداء الاشتراكات والإحصائيات</p>
        </div>

        <!-- Statistics Cards -->
        <div class="flex flex-wrap justify-between gap-4 mb-6">
            <!-- Active Subscriptions -->
            <div class="w-1/4 bg-gradient-to-br from-white to-green-50 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 p-6 border-l-4 border-green-500 hover:border-green-600">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 mb-1">الاشتراكات النشطة</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $activeSubscriptions }}</p>
                        <p class="text-xs text-gray-500 mt-1">اشتراك نشط</p>
                    </div>
                    <div class="bg-gradient-to-br from-green-100 to-green-200 rounded-xl p-3 shadow-sm">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Most Used Subscription -->
            <div class="w-1/4 bg-gradient-to-br from-white to-blue-50 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 p-6 border-l-4 border-blue-500 hover:border-blue-600">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 mb-1">أكثر باقة استخدامًا</p>
                        <p class="text-xl font-bold text-gray-900 mt-2 line-clamp-1">{{ $mostUsedSubscription->name ?? 'غير محدد' }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            <span class="font-semibold text-blue-600">{{ $mostUsedSubscription->user_subscriptions_count ?? 0 }}</span> اشتراك
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl p-3 shadow-sm">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Renewal Rate -->
            <div class="w-1/4 bg-gradient-to-br from-white to-yellow-50 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 p-6 border-l-4 border-yellow-500 hover:border-yellow-600">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 mb-1">معدل التجديد</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($renewalRate, 1) }}%</p>
                        <p class="text-xs text-gray-500 mt-1">من إجمالي الاشتراكات</p>
                    </div>
                    <div class="bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-xl p-3 shadow-sm">
                        <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Rejected Requests -->
            <div class="w-1/4 bg-gradient-to-br from-white to-red-50 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 p-6 border-l-4 border-red-500 hover:border-red-600">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 mb-1">الطلبات المرفوضة</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $rejectedRequests }}</p>
                        <p class="text-xs text-gray-500 mt-1">طلب مرفوض</p>
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
            <form id="searchForm" method="GET" action="{{ route('admin.ai-reports.subscriptions') }}" class="flex flex-wrap items-end gap-4">
                <!-- Subscription Filter -->
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">الباقة</label>
                    <select 
                        id="subscriptionInput"
                        name="subscription_id" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                    <select 
                        id="statusInput"
                        name="status" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="">جميع الحالات</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>منتهي</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
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
                        href="{{ route('admin.ai-reports.subscriptions') }}" 
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

        <!-- Subscriptions Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto" id="tableContainer">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المستخدم</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الباقة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ البدء</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الانتهاء</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">السعر</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="bg-white divide-y divide-gray-200">
                        @include('admin.ai-reports.partials.subscriptions-table')
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="paginationContainer" class="px-6 py-4 border-t border-gray-200">
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
    const filterDelay = 300; // تأخير 300ms قبل البحث

    // دالة لإرسال طلب AJAX
    function performSearch() {
        const subscriptionId = subscriptionInput.value;
        const status = statusInput.value;
        const dateFrom = dateFromInput.value;
        const dateTo = dateToInput.value;

        // إظهار مؤشر التحميل
        loadingIndicator.classList.remove('hidden');

        // بناء URL مع المعاملات
        const url = new URL('{{ route('admin.ai-reports.subscriptions') }}', window.location.origin);
        if (subscriptionId) url.searchParams.set('subscription_id', subscriptionId);
        if (status) url.searchParams.set('status', status);
        if (dateFrom) url.searchParams.set('date_from', dateFrom);
        if (dateTo) url.searchParams.set('date_to', dateTo);

        // إرسال طلب AJAX
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
            // تحديث الجدول
            tableBody.innerHTML = data.html;
            // تحديث Pagination
            if (data.pagination) {
                paginationContainer.innerHTML = data.pagination;
            }
            // تحديث URL في المتصفح بدون إعادة تحميل
            window.history.pushState({}, '', url.toString());
            // إخفاء مؤشر التحميل
            loadingIndicator.classList.add('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            loadingIndicator.classList.add('hidden');
            // إظهار رسالة خطأ للمستخدم
            tableBody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">حدث خطأ أثناء البحث. يرجى المحاولة مرة أخرى.</td></tr>';
        });
    }

    // البحث عند تغيير الفلاتر
    [subscriptionInput, statusInput, dateFromInput, dateToInput].forEach(input => {
        input.addEventListener('change', function() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(performSearch, filterDelay);
        });
    });

    // دالة لتحميل صفحة معينة من pagination
    function loadPage(url) {
        if (!url || url === '#' || url === 'javascript:void(0)') return;

        // إظهار مؤشر التحميل
        loadingIndicator.classList.remove('hidden');

        // إرسال طلب AJAX
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
            // تحديث الجدول
            tableBody.innerHTML = data.html;
            // تحديث Pagination
            if (data.pagination) {
                paginationContainer.innerHTML = data.pagination;
            }
            // تحديث قيمة الفلاتر من الـ URL
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
            // تحديث URL في المتصفح بدون إعادة تحميل
            window.history.pushState({}, '', url);
            // التمرير لأعلى الجدول
            document.getElementById('tableContainer').scrollIntoView({ behavior: 'smooth', block: 'start' });
            // إخفاء مؤشر التحميل
            loadingIndicator.classList.add('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            loadingIndicator.classList.add('hidden');
            // إظهار رسالة خطأ للمستخدم
            tableBody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">حدث خطأ أثناء تحميل الصفحة. يرجى المحاولة مرة أخرى.</td></tr>';
        });
    }

    // معالجة النقر على pagination links
    document.addEventListener('click', function(e) {
        // التحقق من أن النقر كان على pagination link
        const paginationLink = e.target.closest('a[href*="page="]');
        if (paginationLink && paginationContainer.contains(paginationLink)) {
            e.preventDefault();
            
            // الحصول على URL من الرابط
            const url = paginationLink.getAttribute('href');
            if (url && url !== '#' && url !== 'javascript:void(0)') {
                loadPage(url);
            }
        }
    });

    // معالجة زر الرجوع/الأمام في المتصفح
    window.addEventListener('popstate', function(e) {
        // إعادة تحميل الصفحة عند استخدام زر الرجوع
        window.location.reload();
    });
});
</script>
@endpush
@endsection

