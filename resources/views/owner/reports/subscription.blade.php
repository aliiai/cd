@extends('layouts.owner')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-primary-50 to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- ========== Header Section ========== --}}
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">تقرير الاشتراك والاستهلاك</h1>
                <p class="text-sm sm:text-base lg:text-lg text-gray-600 dark:text-gray-400">معلومات الاشتراك الحالي ومؤشرات الاستهلاك</p>
            </div>
            <div>
                <a href="{{ route('owner.reports.subscription.export') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-sm sm:text-base">تحميل PDF</span>
                </a>
            </div>
        </div>

        {{-- ========== Warnings ========== --}}
        @if(count($warnings) > 0)
            <div class="mb-8 space-y-3">
                @foreach($warnings as $warning)
                    <div class="bg-gradient-to-r {{ 
                        $warning['color'] === 'red' ? 'from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 border-red-500 dark:border-red-400' : 
                        ($warning['color'] === 'orange' ? 'from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 border-orange-500 dark:border-orange-400' : 
                        'from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 border-yellow-500 dark:border-yellow-400')
                    }} border-l-4 rounded-lg p-4 flex items-start shadow-md">
                        <svg class="w-6 h-6 {{ 
                            $warning['color'] === 'red' ? 'text-red-600 dark:text-red-400' : 
                            ($warning['color'] === 'orange' ? 'text-orange-600 dark:text-orange-400' : 
                            'text-yellow-600 dark:text-yellow-400')
                        }} ml-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p class="text-sm font-medium {{ 
                            $warning['color'] === 'red' ? 'text-red-800 dark:text-red-300' : 
                            ($warning['color'] === 'orange' ? 'text-orange-800 dark:text-orange-300' : 
                            'text-yellow-800 dark:text-yellow-300')
                        }}">{{ $warning['message'] }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- ========== Subscription Card ========== --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center">
                    <svg class="w-6 h-6 ml-2 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    الاشتراك الحالي
                </h2>
            </div>
            @if($activeSubscription)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-800/20 rounded-lg p-4 border border-primary-200 dark:border-primary-800">
                        <p class="text-xs font-medium text-primary-600 dark:text-primary-400 mb-1">اسم الباقة</p>
                        <p class="text-lg font-bold text-primary-900 dark:text-primary-100">{{ $activeSubscription->subscription->name ?? 'غير محدد' }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 rounded-lg p-4 border border-emerald-200 dark:border-emerald-800">
                        <p class="text-xs font-medium text-emerald-600 dark:text-emerald-400 mb-1">الحالة</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            نشط
                        </span>
                    </div>
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                        <p class="text-xs font-medium text-blue-600 dark:text-blue-400 mb-1">تاريخ البدء</p>
                        <p class="text-sm font-semibold text-blue-900 dark:text-blue-100">{{ $activeSubscription->started_at ? $activeSubscription->started_at->format('Y-m-d') : 'غير محدد' }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-secondary-50 to-secondary-100 dark:from-secondary-900/20 dark:to-secondary-800/20 rounded-lg p-4 border border-secondary-200 dark:border-secondary-800">
                        <p class="text-xs font-medium text-secondary-600 dark:text-secondary-400 mb-1">تاريخ الانتهاء</p>
                        <p class="text-sm font-semibold text-secondary-900 dark:text-secondary-100">
                            @if($activeSubscription->expires_at)
                                {{ $activeSubscription->expires_at->format('Y-m-d') }}
                            @else
                                <span class="text-gray-500 dark:text-gray-400">دائم</span>
                            @endif
                        </p>
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 font-medium mb-4">لا يوجد اشتراك نشط حالياً</p>
                    <a href="{{ route('owner.subscriptions.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-500 to-secondary-500 hover:from-primary-600 hover:to-secondary-600 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        عرض الباقات
                    </a>
                </div>
            @endif
        </div>

        {{-- ========== Usage Indicators ========== --}}
        @if($activeSubscription)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                {{-- Debtors Usage Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 flex items-center">
                            <svg class="w-5 h-5 ml-2 text-secondary-600 dark:text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            عدد المديونين
                        </h3>
                        <span class="text-2xl font-bold {{ $debtorsUsage >= 90 ? 'text-red-600 dark:text-red-400' : ($debtorsUsage >= 70 ? 'text-yellow-600 dark:text-yellow-400' : 'text-emerald-600 dark:text-emerald-400') }}">
                            {{ $debtorsCount }} / {{ $maxDebtors }}
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 mb-2">
                        <div 
                            class="h-3 rounded-full transition-all duration-500 {{ $debtorsUsage >= 90 ? 'bg-gradient-to-r from-red-500 to-red-600' : ($debtorsUsage >= 70 ? 'bg-gradient-to-r from-yellow-500 to-yellow-600' : 'bg-gradient-to-r from-emerald-500 to-emerald-600') }}" 
                            style="width: {{ min($debtorsUsage, 100) }}%"
                        ></div>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-gray-500 dark:text-gray-400">الاستهلاك: {{ number_format($debtorsUsage, 1) }}%</p>
                        @if($maxDebtors > 0)
                            <p class="text-xs font-semibold {{ $debtorsUsage >= 90 ? 'text-red-600 dark:text-red-400' : ($debtorsUsage >= 70 ? 'text-yellow-600 dark:text-yellow-400' : 'text-emerald-600 dark:text-emerald-400') }}">
                                متبقي: {{ max(0, $maxDebtors - $debtorsCount) }}
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Messages Usage Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 flex items-center">
                            <svg class="w-5 h-5 ml-2 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            عدد الرسائل
                        </h3>
                        <span class="text-2xl font-bold {{ $messagesUsage >= 90 ? 'text-red-600 dark:text-red-400' : ($messagesUsage >= 70 ? 'text-yellow-600 dark:text-yellow-400' : 'text-emerald-600 dark:text-emerald-400') }}">
                            {{ $messagesSent }} / {{ $maxMessages }}
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 mb-2">
                        <div 
                            class="h-3 rounded-full transition-all duration-500 {{ $messagesUsage >= 90 ? 'bg-gradient-to-r from-red-500 to-red-600' : ($messagesUsage >= 70 ? 'bg-gradient-to-r from-yellow-500 to-yellow-600' : 'bg-gradient-to-r from-emerald-500 to-emerald-600') }}" 
                            style="width: {{ min($messagesUsage, 100) }}%"
                        ></div>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-gray-500 dark:text-gray-400">الاستهلاك: {{ number_format($messagesUsage, 1) }}%</p>
                        @if($maxMessages > 0)
                            <p class="text-xs font-semibold {{ $messagesUsage >= 90 ? 'text-red-600 dark:text-red-400' : ($messagesUsage >= 70 ? 'text-yellow-600 dark:text-yellow-400' : 'text-emerald-600 dark:text-emerald-400') }}">
                                متبقي: {{ max(0, $maxMessages - $messagesSent) }}
                            </p>
                        @endif
                    </div>
                </div>

                {{-- AI Usage Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 flex items-center">
                            <svg class="w-5 h-5 ml-2 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                            استهلاك الذكاء الاصطناعي
                        </h3>
                        <span class="text-2xl font-bold text-gray-600 dark:text-gray-400">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 mb-2">
                        <div class="h-3 rounded-full bg-gray-400 dark:bg-gray-600" style="width: 0%"></div>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-gray-500 dark:text-gray-400">سيتم تفعيله قريباً</p>
                    </div>
                </div>

            </div>
        @endif
    </div>
</div>
@endsection
