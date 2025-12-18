@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="w-full mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">تقارير مقدمي الخدمة</h1>
            <p class="text-gray-600 mt-2">مراقبة وتحليل أداء مقدمي الخدمة</p>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <form id="searchForm" method="GET" action="{{ route('admin.ai-reports.service-providers') }}" class="flex flex-wrap gap-4">
                <!-- Search -->
                <div class="flex-1 min-w-[200px]">
                    <input 
                        type="text" 
                        id="searchInput"
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="البحث بالاسم أو البريد الإلكتروني..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        autocomplete="off"
                    >
                </div>

                <!-- Sort By -->
                <div class="min-w-[150px]">
                    <select 
                        id="sortByInput"
                        name="sort_by" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>الاسم</option>
                        <option value="ai_usage" {{ request('sort_by') == 'ai_usage' ? 'selected' : '' }}>استهلاك AI</option>
                        <option value="collection_rate" {{ request('sort_by') == 'collection_rate' ? 'selected' : '' }}>نسبة التحصيل</option>
                    </select>
                </div>

                <!-- Sort Order -->
                <div class="min-w-[120px]">
                    <select 
                        id="sortOrderInput"
                        name="sort_order" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    >
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>تصاعدي</option>
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>تنازلي</option>
                    </select>
                </div>

                <!-- Reset Button -->
                <a 
                    href="{{ route('admin.ai-reports.service-providers') }}" 
                    class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-200"
                >
                    إعادة تعيين
                </a>
            </form>
        </div>

        <!-- Loading Indicator -->
        <div id="loadingIndicator" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
                <svg class="animate-spin h-5 w-5 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-700">جاري البحث...</span>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto" id="tableContainer">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">مقدم الخدمة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الباقة الحالية</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عدد المديونين</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الرسائل المرسلة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نسبة التحصيل</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">AI Usage</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="bg-white divide-y divide-gray-200">
                        @include('admin.ai-reports.partials.providers-table')
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="paginationContainer" class="px-6 py-4 border-t border-gray-200">
                {{ $providers->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const sortByInput = document.getElementById('sortByInput');
    const sortOrderInput = document.getElementById('sortOrderInput');
    const tableBody = document.getElementById('tableBody');
    const paginationContainer = document.getElementById('paginationContainer');
    const loadingIndicator = document.getElementById('loadingIndicator');
    
    let searchTimeout;
    const searchDelay = 500; // تأخير 500ms قبل البحث

    // دالة لإرسال طلب AJAX
    function performSearch() {
        const search = searchInput.value;
        const sortBy = sortByInput.value;
        const sortOrder = sortOrderInput.value;

        // إظهار مؤشر التحميل
        loadingIndicator.classList.remove('hidden');

        // بناء URL مع المعاملات
        const url = new URL('{{ route('admin.ai-reports.service-providers') }}', window.location.origin);
        if (search) url.searchParams.set('search', search);
        url.searchParams.set('sort_by', sortBy);
        url.searchParams.set('sort_order', sortOrder);

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
            // إخفاء مؤشر التحميل
            loadingIndicator.classList.add('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            loadingIndicator.classList.add('hidden');
            // إظهار رسالة خطأ للمستخدم
            tableBody.innerHTML = '<tr><td colspan="8" class="px-6 py-4 text-center text-red-500">حدث خطأ أثناء البحث. يرجى المحاولة مرة أخرى.</td></tr>';
        });
    }

    // البحث عند الكتابة في حقل البحث
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, searchDelay);
    });

    // البحث عند تغيير الترتيب
    sortByInput.addEventListener('change', performSearch);
    sortOrderInput.addEventListener('change', performSearch);

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
            // تحديث قيمة البحث والترتيب من الـ URL
            const urlObj = new URL(url, window.location.origin);
            if (urlObj.searchParams.has('search')) {
                searchInput.value = urlObj.searchParams.get('search') || '';
            }
            if (urlObj.searchParams.has('sort_by')) {
                sortByInput.value = urlObj.searchParams.get('sort_by') || 'name';
            }
            if (urlObj.searchParams.has('sort_order')) {
                sortOrderInput.value = urlObj.searchParams.get('sort_order') || 'asc';
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
            tableBody.innerHTML = '<tr><td colspan="8" class="px-6 py-4 text-center text-red-500">حدث خطأ أثناء تحميل الصفحة. يرجى المحاولة مرة أخرى.</td></tr>';
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

