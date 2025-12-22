{{-- 
    Component: Income Details Table
    Usage: @include('owner.analytics.partials.income-details-table', [
        'incomeDetails' => $incomeDetails,
        'timeFilter' => $timeFilter,
        'dateFrom' => $dateFrom,
        'dateTo' => $dateTo,
        'search' => $search,
        'minAmount' => $minAmount,
        'maxAmount' => $maxAmount
    ])
--}}
@props([
    'incomeDetails',
    'timeFilter',
    'dateFrom',
    'dateTo',
    'search',
    'minAmount',
    'maxAmount'
])

<div class="lg:col-span-2">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="bg-gradient-to-r from-primary-500 to-secondary-500 px-6 py-4">
            <h3 class="text-xl font-bold text-white flex items-center">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                تفاصيل الدخل
            </h3>
        </div>
        <div class="p-6">
            {{-- Search and Filters --}}
            <form method="GET" action="{{ route('owner.analytics.income') }}" class="mb-6 space-y-4">
                <input type="hidden" name="time_filter" value="{{ $timeFilter }}">
                @if($timeFilter === 'custom')
                    <input type="hidden" name="date_from" value="{{ $dateFrom ? $dateFrom->format('Y-m-d') : '' }}">
                    <input type="hidden" name="date_to" value="{{ $dateTo ? $dateTo->format('Y-m-d') : '' }}">
                @endif
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <input type="text" name="search" value="{{ $search }}" placeholder="بحث بالاسم أو الهاتف أو البريد..." class="px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm transition-colors">
                    <input type="number" name="min_amount" value="{{ $minAmount }}" placeholder="الحد الأدنى" step="0.01" class="px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm transition-colors">
                    <input type="number" name="max_amount" value="{{ $maxAmount }}" placeholder="الحد الأقصى" step="0.01" class="px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm transition-colors">
                </div>
                <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-gradient-to-r from-primary-500 to-secondary-500 hover:from-primary-600 hover:to-secondary-600 text-white rounded-lg text-sm shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                    بحث
                </button>
            </form>

            {{-- Table --}}
            <div class="overflow-x-auto rounded-lg">
                <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">المديون</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">قيمة الدين</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">تاريخ التحصيل</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">رابط الدفع</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($incomeDetails as $debtor)
                            <tr class="hover:bg-primary-50 dark:hover:bg-gray-700 transition-all duration-200">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $debtor->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $debtor->phone }}</p>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($debtor->debt_amount, 2) }} <span class="text-xs">ر.س</span></span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($debtor->payment_date)->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if($debtor->payment_link)
                                        <a href="{{ $debtor->payment_link }}" target="_blank" class="text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 text-sm font-medium transition-colors">عرض الرابط</a>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <a href="{{ route('owner.debtors.index') }}?search={{ $debtor->name }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 text-sm font-medium transition-colors">عرض</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-400 font-medium">لا توجد بيانات</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($incomeDetails->hasPages())
                <div class="mt-6">
                    {{ $incomeDetails->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

