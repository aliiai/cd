{{-- 
    Component: Time Evolution Section
    Usage: @include('owner.analytics.partials.time-evolution-section', [
        'timeFilter' => $timeFilter,
        'dateFrom' => $dateFrom,
        'dateTo' => $dateTo,
        'overdueToPaid' => $overdueToPaid,
        'promiseToPaid' => $promiseToPaid,
        'route' => 'owner.analytics.collection-status'
    ])
--}}
@props([
    'timeFilter',
    'dateFrom',
    'dateTo',
    'overdueToPaid',
    'promiseToPaid',
    'route'
])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="bg-gradient-to-r from-primary-500 to-secondary-500 px-6 py-4">
        <h3 class="text-xl font-bold text-white flex items-center">
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            تطور التحصيل
        </h3>
    </div>
    <div class="p-6">
        {{-- Time Filter --}}
        <form method="GET" action="{{ route($route) }}" class="mb-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">الفترة الزمنية</label>
                    <select name="time_filter" id="time_filter" onchange="this.form.submit()" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm transition-colors">
                        <option value="today" {{ $timeFilter === 'today' ? 'selected' : '' }}>اليوم</option>
                        <option value="7" {{ $timeFilter === '7' ? 'selected' : '' }}>آخر 7 أيام</option>
                        <option value="30" {{ $timeFilter === '30' ? 'selected' : '' }}>آخر 30 يوم</option>
                        <option value="custom" {{ $timeFilter === 'custom' ? 'selected' : '' }}>فترة مخصصة</option>
                    </select>
                </div>
                
                @if($timeFilter === 'custom')
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">من</label>
                            <input type="date" name="date_from" value="{{ $dateFrom ? $dateFrom->format('Y-m-d') : '' }}" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">إلى</label>
                            <input type="date" name="date_to" value="{{ $dateTo ? $dateTo->format('Y-m-d') : '' }}" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-primary-500 to-secondary-500 hover:from-primary-600 hover:to-secondary-600 text-white font-medium py-2.5 px-4 rounded-lg transition-all duration-200 text-sm shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        تطبيق الفلتر
                    </button>
                @endif
            </div>
        </form>

        {{-- Evolution Stats Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 rounded-xl p-4 border border-emerald-200 dark:border-emerald-800 shadow-md hover:shadow-lg transition-all duration-200">
                <div class="text-center">
                    <p class="text-xs font-medium text-emerald-600 dark:text-emerald-400 mb-1">من متأخر → مدفوع</p>
                    <p class="text-2xl font-bold text-emerald-900 dark:text-emerald-100">{{ $overdueToPaid }}</p>
                </div>
            </div>

            <div class="bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-800/20 rounded-xl p-4 border border-primary-200 dark:border-primary-800 shadow-md hover:shadow-lg transition-all duration-200">
                <div class="text-center">
                    <p class="text-xs font-medium text-primary-600 dark:text-primary-400 mb-1">من وعد بالدفع → مدفوع</p>
                    <p class="text-2xl font-bold text-primary-900 dark:text-primary-100">{{ $promiseToPaid }}</p>
                </div>
            </div>

            <div class="bg-gradient-to-br from-secondary-50 to-secondary-100 dark:from-secondary-900/20 dark:to-secondary-800/20 rounded-xl p-4 border border-secondary-200 dark:border-secondary-800 shadow-md hover:shadow-lg transition-all duration-200">
                <div class="text-center">
                    <p class="text-xs font-medium text-secondary-600 dark:text-secondary-400 mb-1">إجمالي التحولات</p>
                    <p class="text-2xl font-bold text-secondary-900 dark:text-secondary-100">{{ $overdueToPaid + $promiseToPaid }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

