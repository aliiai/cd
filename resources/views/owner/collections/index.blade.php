@extends('layouts.owner')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-primary-50 to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- ========== Header Section ========== --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6 sm:mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-1 sm:mb-2">حملات التحصيل</h1>
                <p class="text-sm sm:text-base lg:text-lg text-gray-600 dark:text-gray-400">إنشاء وإدارة حملات التحصيل للمديونين</p>
            </div>
            <button onclick="openCampaignModal()" 
                    class="inline-flex items-center justify-center px-4 py-2 sm:px-6 sm:py-3 bg-gradient-to-r from-primary-500 to-secondary-500 hover:from-primary-600 hover:to-secondary-600 text-white text-sm sm:text-base font-semibold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 w-full sm:w-auto">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="hidden sm:inline">إنشاء حملة تحصيل</span>
                <span class="sm:hidden">إنشاء حملة</span>
            </button>
        </div>

        {{-- ========== Success/Error Messages ========== --}}
        @if(session('success'))
            <div class="mb-4 sm:mb-6 bg-gradient-to-r from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 border-l-4 border-emerald-500 dark:border-emerald-400 rounded-lg p-3 sm:p-4 flex items-center shadow-md">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-emerald-600 dark:text-emerald-400 ml-2 sm:ml-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-xs sm:text-sm md:text-base text-emerald-800 dark:text-emerald-300 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 sm:mb-6 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 border-l-4 border-red-500 dark:border-red-400 rounded-lg p-3 sm:p-4 flex items-center shadow-md">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-red-600 dark:text-red-400 ml-2 sm:ml-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <p class="text-xs sm:text-sm md:text-base text-red-800 dark:text-red-300 font-medium">{{ session('error') }}</p>
            </div>
        @endif

        @if(session('warning'))
            <div class="mb-4 sm:mb-6 bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 border-l-4 border-yellow-500 dark:border-yellow-400 rounded-lg p-3 sm:p-4 flex items-center shadow-md">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-yellow-600 dark:text-yellow-400 ml-2 sm:ml-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <p class="text-xs sm:text-sm md:text-base text-yellow-800 dark:text-yellow-300 font-medium">{{ session('warning') }}</p>
            </div>
        @endif

        {{-- ========== Subscription Usage Info ========== --}}
        @if($subscriptionInfo)
            <div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-5 md:p-6 mb-4 sm:mb-6 md:mb-8 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <h3 class="text-base sm:text-lg md:text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 ml-2 text-primary-600 dark:text-primary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span class="hidden sm:inline">معلومات الاشتراك والاستهلاك</span>
                        <span class="sm:hidden">الاشتراك</span>
                    </h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 mb-1 sm:mb-2">الباقة الحالية</p>
                        <p class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $subscriptionInfo['subscription_name'] }}</p>
                    </div>
                    <div>
                        <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 mb-1 sm:mb-2">استهلاك الرسائل</p>
                        <div class="flex items-center space-x-2 sm:space-x-3 space-x-reverse">
                            <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2 sm:h-3 overflow-hidden">
                                <div 
                                    class="h-2 sm:h-3 rounded-full transition-all duration-500 {{ $subscriptionInfo['messages_usage'] >= 90 ? 'bg-gradient-to-r from-red-500 to-red-600' : ($subscriptionInfo['messages_usage'] >= 70 ? 'bg-gradient-to-r from-yellow-500 to-yellow-600' : 'bg-gradient-to-r from-emerald-500 to-emerald-600') }}" 
                                    style="width: {{ min($subscriptionInfo['messages_usage'], 100) }}%"
                                ></div>
                            </div>
                            <span class="text-xs sm:text-sm font-bold {{ $subscriptionInfo['messages_usage'] >= 90 ? 'text-red-600 dark:text-red-400' : ($subscriptionInfo['messages_usage'] >= 70 ? 'text-yellow-600 dark:text-yellow-400' : 'text-emerald-600 dark:text-emerald-400') }}">
                                {{ $subscriptionInfo['messages_sent'] }} / {{ $subscriptionInfo['max_messages'] }}
                            </span>
                        </div>
                        @if($subscriptionInfo['messages_remaining'] !== null)
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 sm:mt-2">
                                المتبقي: <span class="font-semibold {{ $subscriptionInfo['messages_remaining'] <= 5 ? 'text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-300' }}">{{ $subscriptionInfo['messages_remaining'] }}</span> رسالة
                            </p>
                        @endif
                    </div>
                </div>
                @if($subscriptionInfo['messages_remaining'] !== null && $subscriptionInfo['messages_remaining'] <= 5 && $subscriptionInfo['messages_remaining'] > 0)
                    <div class="mt-3 sm:mt-4 bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 border-l-4 border-yellow-500 dark:border-yellow-400 rounded-lg p-3 sm:p-4 flex items-start">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-600 dark:text-yellow-400 ml-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p class="text-xs sm:text-sm text-yellow-800 dark:text-yellow-300 font-medium">
                            تحذير: لديك {{ $subscriptionInfo['messages_remaining'] }} رسالة متبقية فقط. يرجى ترقية اشتراكك لإرسال المزيد.
                        </p>
                    </div>
                @elseif($subscriptionInfo['messages_remaining'] !== null && $subscriptionInfo['messages_remaining'] == 0)
                    <div class="mt-3 sm:mt-4 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 border-l-4 border-red-500 dark:border-red-400 rounded-lg p-3 sm:p-4 flex items-start">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-600 dark:text-red-400 ml-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <p class="text-xs sm:text-sm text-red-800 dark:text-red-300 font-medium">
                            لقد استنفدت جميع الرسائل المسموحة! يرجى ترقية اشتراكك لإرسال المزيد.
                        </p>
                    </div>
                @endif
            </div>
        @else
            <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 border-l-4 border-yellow-500 dark:border-yellow-400 rounded-lg p-3 sm:p-4 mb-4 sm:mb-6 md:mb-8 flex items-center shadow-md">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 text-yellow-600 dark:text-yellow-400 ml-2 sm:ml-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <p class="text-xs sm:text-sm text-yellow-800 dark:text-yellow-300 font-medium">
                    لا يوجد اشتراك نشط. يرجى الاشتراك في إحدى الباقات لإرسال الرسائل.
                </p>
            </div>
        @endif

        {{-- ========== Campaigns Table ========== --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="bg-gradient-to-r from-primary-500 to-secondary-500 px-3 sm:px-4 md:px-6 py-3 sm:py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-base sm:text-lg md:text-xl font-bold text-white flex items-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                        <span class="hidden sm:inline">الحملات السابقة</span>
                        <span class="sm:hidden">الحملات</span>
                    </h2>
                </div>
            </div>
            
            {{-- Filters --}}
            <div class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                <form method="GET" action="{{ route('owner.collections.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                    {{-- Filter by Send Type --}}
                    <div>
                        <label for="send_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">نوع الإرسال</label>
                        <select name="send_type" id="send_type" onchange="this.form.submit()" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="all" {{ request('send_type') == 'all' || !request('send_type') ? 'selected' : '' }}>جميع الأنواع</option>
                            <option value="now" {{ request('send_type') == 'now' ? 'selected' : '' }}>فوري</option>
                            <option value="scheduled" {{ request('send_type') == 'scheduled' ? 'selected' : '' }}>مجدول</option>
                            <option value="auto" {{ request('send_type') == 'auto' ? 'selected' : '' }}>تلقائي</option>
                        </select>
                    </div>
                    
                    {{-- Filter by Channel --}}
                    <div>
                        <label for="channel" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">قناة الإرسال</label>
                        <select name="channel" id="channel" onchange="this.form.submit()" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="all" {{ request('channel') == 'all' || !request('channel') ? 'selected' : '' }}>جميع القنوات</option>
                            <option value="sms" {{ request('channel') == 'sms' ? 'selected' : '' }}>SMS</option>
                            <option value="email" {{ request('channel') == 'email' ? 'selected' : '' }}>Email</option>
                        </select>
                    </div>
                    
                    {{-- Filter by Status --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">حالة الإرسال</label>
                        <select name="status" id="status" onchange="this.form.submit()" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>جميع الحالات</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                            <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>تم الإرسال</option>
                            <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>مجدول</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>فشل</option>
                        </select>
                    </div>
                    
                    {{-- Clear Filters --}}
                    <div class="flex items-end">
                        <a href="{{ route('owner.collections.index') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200 text-center">
                            إعادة تعيين
                        </a>
                    </div>
                </form>
            </div>
            
            <div class="p-3 sm:p-4 lg:p-6">
                @if($campaigns->count() > 0)
                    <div class="overflow-x-auto -mx-3 sm:-mx-4 lg:-mx-6 rounded-lg">
                        <div class="inline-block min-w-full align-middle">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                                <tr>
                                    <th class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">رقم الحملة</th>
                                    <th class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap hidden sm:table-cell">نوع الإرسال</th>
                                    <th class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">عدد المستلمين</th>
                                    <th class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap hidden md:table-cell">قناة الإرسال</th>
                                    <th class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">حالة الإرسال</th>
                                    <th class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap hidden lg:table-cell">وقت الإرسال</th>
                                    <th class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($campaigns as $campaign)
                                    <tr class="hover:bg-primary-50 dark:hover:bg-gray-700 transition-all duration-200">
                                        <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center text-white font-bold text-sm ml-3">
                                                    {{ mb_substr($campaign->campaign_number ?? 'C', 0, 1) }}
                                                </div>
                                                <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $campaign->campaign_number }}</div>
                                            </div>
                                        </td>
                                        <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 whitespace-nowrap hidden sm:table-cell">
                                            <span class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-1.5 rounded-full text-xs font-semibold {{ $campaign->send_type_color }}">
                                                @if($campaign->send_type === 'auto')
                                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                @endif
                                                {{ $campaign->send_type_text }}
                                            </span>
                                        </td>
                                        <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-1.5 rounded-full text-xs font-semibold bg-primary-100 dark:bg-primary-900/30 text-primary-800 dark:text-primary-300 border border-primary-200 dark:border-primary-800">
                                                {{ $campaign->total_recipients }}
                                            </span>
                                        </td>
                                        <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 whitespace-nowrap hidden md:table-cell">
                                            @if($campaign->channel == 'sms')
                                                <span class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-1.5 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                    </svg>
                                                    SMS
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-1.5 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                    Email
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 sm:px-3 sm:py-1.5 text-xs font-semibold rounded-full shadow-sm {{ $campaign->status_color }}">
                                                {{ $campaign->status_text }}
                                            </span>
                                        </td>
                                        <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900 dark:text-gray-100 hidden lg:table-cell">
                                            @if($campaign->send_type === 'scheduled' && $campaign->scheduled_at)
                                                {{ $campaign->scheduled_at->format('Y-m-d H:i') }}
                                            @else
                                                {{ $campaign->created_at->format('Y-m-d H:i') }}
                                            @endif
                                        </td>
                                        <td class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 whitespace-nowrap">
                                            <a href="{{ route('owner.collections.show', $campaign) }}" 
                                               class="inline-flex items-center px-2 py-1.5 sm:px-4 sm:py-2 bg-gradient-to-r from-primary-500 to-secondary-500 hover:from-primary-600 hover:to-secondary-600 text-white text-xs sm:text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                <span class="hidden sm:inline">عرض التفاصيل</span>
                                                <span class="sm:hidden">عرض</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                    
                    {{-- Pagination --}}
                    @if($campaigns->hasPages())
                        <div class="mt-6 flex items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-4">
                            <div class="flex-1 flex justify-between sm:hidden">
                                @if($campaigns->onFirstPage())
                                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-700 cursor-not-allowed">
                                        السابق
                                    </span>
                                @else
                                    <a href="{{ $campaigns->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        السابق
                                    </a>
                                @endif
                                
                                @if($campaigns->hasMorePages())
                                    <a href="{{ $campaigns->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        التالي
                                    </a>
                                @else
                                    <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-700 cursor-not-allowed">
                                        التالي
                                    </span>
                                @endif
                            </div>
                            
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                        عرض
                                        <span class="font-medium">{{ $campaigns->firstItem() }}</span>
                                        إلى
                                        <span class="font-medium">{{ $campaigns->lastItem() }}</span>
                                        من
                                        <span class="font-medium">{{ $campaigns->total() }}</span>
                                        حملة
                                    </p>
                                </div>
                                <div>
                                    {{ $campaigns->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 font-medium text-lg mb-4">لا توجد حملات حالياً.</p>
                        <button onclick="openCampaignModal()" 
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-500 to-secondary-500 hover:from-primary-600 hover:to-secondary-600 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            إنشاء حملة تحصيل
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ========== Campaign Modal ========== --}}
<div id="campaignModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden overflow-y-auto h-full w-full z-50" style="display: none; align-items: flex-start; justify-content: center; padding: 1rem;">
    <div class="relative w-full max-w-6xl bg-white dark:bg-gray-800 rounded-xl shadow-2xl transform transition-all opacity-0 scale-95 my-4 sm:my-8" style="max-height: calc(100vh - 2rem); overflow-y: auto;">
        {{-- Modal Header --}}
        <div class="sticky top-0 bg-gradient-to-r from-primary-500 to-secondary-500 text-white px-4 sm:px-6 py-3 sm:py-4 rounded-t-xl flex items-center justify-between z-10 shadow-md">
            <h3 class="text-lg sm:text-xl font-bold flex items-center">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span class="hidden sm:inline">إنشاء حملة تحصيل جديدة</span>
                <span class="sm:hidden">حملة جديدة</span>
            </h3>
            <button onclick="closeCampaignModal()" class="text-white hover:text-gray-200 transition-colors duration-200 p-1 rounded-full hover:bg-white/20">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Form --}}
        <form id="campaignForm" action="{{ route('owner.collections.store') }}" method="POST" class="p-4 sm:p-6" onsubmit="return validateCampaignSubmission(event)">
            @csrf

            {{-- Grid Layout --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                {{-- Left Column --}}
                <div class="space-y-6">
                    {{-- Select Clients --}}
                    <div>
                        <label for="client_selection" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            اختيار المديونين <span class="text-red-500">*</span>
                        </label>
                        <select id="client_selection" 
                                onchange="handleClientSelection()"
                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                            <option value="">اختر طريقة الاختيار</option>
                            <option value="single">مدين واحد</option>
                            <option value="multiple">أكثر من مدين</option>
                            <option value="all">جميع المديونين</option>
                        </select>
                    </div>

                    {{-- Clients Multi-Select with Checkboxes --}}
                    <div id="multipleClientsDiv" class="hidden mb-4">
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                اختر المديونين <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center gap-3">
                                <span id="selectedCount" class="text-sm font-semibold text-primary-600 dark:text-primary-400">0 محدد</span>
                                <button type="button" 
                                        onclick="toggleSelectAll()" 
                                        class="text-xs px-3 py-1.5 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-300 rounded-lg hover:bg-primary-200 dark:hover:bg-primary-900/50 transition-colors duration-200 font-medium">
                                    <span id="selectAllText">تحديد الكل</span>
                                </button>
                            </div>
                        </div>
                        
                        {{-- Search Box --}}
                        <div class="mb-3">
                            <div class="relative">
                                <input type="text" 
                                       id="debtorSearch" 
                                       placeholder="ابحث عن مديون..." 
                                       onkeyup="filterDebtors()"
                                       class="w-full px-4 py-2.5 pr-10 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                                <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                        
                        {{-- Debtors List with Checkboxes --}}
                        <div class="border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 shadow-sm overflow-hidden" style="max-height: 300px; overflow-y: auto;">
                            <div id="debtorsList" class="divide-y divide-gray-200 dark:divide-gray-600">
                                @foreach($debtors as $debtor)
                                    <div class="flex items-center p-3 hover:bg-gray-50 dark:hover:bg-gray-600/50 transition-colors duration-150 debtor-item" 
                                         data-name="{{ strtolower($debtor->name) }}" 
                                         data-phone="{{ $debtor->phone }}">
                                        <input type="checkbox" 
                                               name="client_ids[]" 
                                               value="{{ $debtor->id }}" 
                                               class="debtor-checkbox rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 w-5 h-5 ml-3 flex-shrink-0"
                                               onchange="updateSelectedCount()">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                                        {{ $debtor->name }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                        {{ $debtor->phone }}
                                                    </p>
                                                </div>
                                                <div class="ml-3 flex items-center gap-2">
                                                    <p class="text-sm font-semibold text-primary-600 dark:text-primary-400">
                                                        {{ number_format($debtor->has_installments ? $debtor->remaining_amount : $debtor->debt_amount, 2) }} ر.س
                                                    </p>
                                                    @if(($debtor->has_installments ? $debtor->remaining_amount : $debtor->debt_amount) > 0)
                                                        <a href="{{ route('owner.payments.iframe', $debtor) }}" 
                                                           target="_blank"
                                                           class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition-colors"
                                                           title="فتح صفحة الدفع">
                                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                            </svg>
                                                            دفع
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if($debtors->count() === 0)
                                <div class="p-8 text-center">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">لا يوجد مديونين متاحين</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Single Client Select --}}
                    <div id="singleClientDiv" class="hidden">
                        <label for="single_client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            اختر المديون <span class="text-red-500">*</span>
                        </label>
                        <select name="single_client_id" 
                                id="single_client_id"
                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                            <option value="">اختر المديون</option>
                            @foreach($debtors as $debtor)
                                <option value="{{ $debtor->id }}">{{ $debtor->name }} - {{ $debtor->phone }} ({{ number_format($debtor->debt_amount, 2) }} ر.س)</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Channel & Template Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Channel --}}
                        <div>
                            <label for="channel" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                قناة التواصل <span class="text-red-500">*</span>
                            </label>
                            <select name="channel" 
                                    id="channel" 
                                    required
                                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                                <option value="">اختر القناة</option>
                                <option value="sms">SMS</option>
                                <option value="email">Email</option>
                            </select>
                            @error('channel')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Template --}}
                        <div>
                            <label for="template" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                قالب الرسالة
                            </label>
                            <select name="template" 
                                    id="template" 
                                    onchange="loadTemplate()"
                                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                                <option value="">اختر قالب (اختياري)</option>
                                <option value="reminder">تذكير بالدفع</option>
                                <option value="overdue">تذكير بالمتأخرات</option>
                                <option value="payment_link">إرسال رابط الدفع</option>
                                <option value="custom">مخصص</option>
                            </select>
                        </div>
                    </div>

                    {{-- Send Type --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            وقت الإرسال <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="flex items-center p-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer hover:border-primary-400 dark:hover:border-primary-500 transition-all duration-200 has-[:checked]:border-primary-500 dark:has-[:checked]:border-primary-400 has-[:checked]:bg-primary-50 dark:has-[:checked]:bg-primary-900/20">
                                <input type="radio" 
                                       name="send_type" 
                                       value="now"
                                       checked
                                       onchange="toggleScheduleInput()"
                                       class="rounded border-gray-300 dark:border-gray-600 text-primary-600 dark:text-primary-400 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                <span class="mr-2 text-sm font-medium text-gray-700 dark:text-gray-300">إرسال فوري</span>
                            </label>
                            <label class="flex items-center p-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer hover:border-primary-400 dark:hover:border-primary-500 transition-all duration-200 has-[:checked]:border-primary-500 dark:has-[:checked]:border-primary-400 has-[:checked]:bg-primary-50 dark:has-[:checked]:bg-primary-900/20">
                                <input type="radio" 
                                       name="send_type" 
                                       value="scheduled"
                                       onchange="toggleScheduleInput()"
                                       class="rounded border-gray-300 dark:border-gray-600 text-primary-600 dark:text-primary-400 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                <span class="mr-2 text-sm font-medium text-gray-700 dark:text-gray-300">جدولة الإرسال</span>
                            </label>
                        </div>
                    </div>

                    {{-- Scheduled At --}}
                    <div id="scheduledAtDiv" class="hidden">
                        <label for="scheduled_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            تاريخ ووقت الإرسال <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" 
                               name="scheduled_at" 
                               id="scheduled_at"
                               min="{{ now()->format('Y-m-d\TH:i') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                        @error('scheduled_at')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="space-y-6">
                    {{-- Message --}}
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            نص الرسالة <span class="text-red-500">*</span>
                        </label>
                        <textarea name="message" 
                                  id="message" 
                                  rows="8"
                                  required
                                  oninput="updatePreview()"
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200 resize-none"></textarea>
                        @error('message')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Preview --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            معاينة الرسالة
                        </label>
                        <div id="messagePreview" class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 rounded-lg bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap overflow-y-auto" style="min-height: 150px; max-height: 150px;">
                            ستظهر معاينة الرسالة هنا...
                        </div>
                    </div>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end space-x-3 space-x-reverse mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <button type="button" 
                        onclick="closeCampaignModal()"
                        class="px-6 py-2.5 border-2 border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 hover:border-gray-400 dark:hover:border-gray-500 transition-all duration-200">
                    إلغاء
                </button>
                <button type="submit" 
                        class="px-6 py-2.5 border border-transparent rounded-lg shadow-md text-sm font-medium text-white bg-gradient-to-r from-primary-500 to-secondary-500 hover:from-primary-600 hover:to-secondary-600 transition-all duration-200 transform hover:scale-105">
                    إرسال الحملة
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // قوالب الرسائل الجاهزة
    const templates = {
        reminder: 'مرحباً @{{name}}، نود تذكيرك بأن لديك مبلغ مستحق للدفع بقيمة @{{amount}} ر.س. يرجى تسوية المبلغ في أقرب وقت ممكن. شكراً لتعاونك.',
        overdue: 'مرحباً @{{name}}، نود إعلامك بأن مبلغ @{{amount}} ر.س قد تجاوز تاريخ الاستحقاق. يرجى التواصل معنا لتسوية المبلغ. شكراً.',
        payment_link: 'مرحباً @{{name}}، يمكنك تسوية مبلغ @{{amount}} ر.س من خلال الرابط التالي: @{{link}} شكراً.',
        custom: ''
    };

    // Open Modal
    function openCampaignModal() {
        const modal = document.getElementById('campaignModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            // إضافة animation
            setTimeout(() => {
                const modalContent = modal.querySelector('.relative');
                if (modalContent) {
                    modalContent.style.transform = 'scale(1)';
                    modalContent.style.opacity = '1';
                }
            }, 10);
        }
    }

    // Close Modal
    function closeCampaignModal() {
        const modal = document.getElementById('campaignModal');
        const form = document.getElementById('campaignForm');
        const modalContent = modal?.querySelector('.relative');
        
        if (modalContent) {
            modalContent.style.transform = 'scale(0.95)';
            modalContent.style.opacity = '0';
        }
        
        setTimeout(() => {
            if (modal) {
                modal.classList.add('hidden');
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
            
            if (form) {
                form.reset();
                document.getElementById('client_selection').value = '';
                document.getElementById('multipleClientsDiv').classList.add('hidden');
                document.getElementById('singleClientDiv').classList.add('hidden');
                document.getElementById('scheduledAtDiv').classList.add('hidden');
                document.getElementById('messagePreview').textContent = 'ستظهر معاينة الرسالة هنا...';
                
                // إزالة أي hidden inputs
                const existingInputs = document.querySelectorAll('input[name="client_ids[]"][type="hidden"]');
                existingInputs.forEach(input => input.remove());
            }
        }, 200);
    }

    // Handle Client Selection
    function handleClientSelection() {
        const selection = document.getElementById('client_selection').value;
        const multipleDiv = document.getElementById('multipleClientsDiv');
        const singleDiv = document.getElementById('singleClientDiv');
        
        // إخفاء جميع الخيارات أولاً
        multipleDiv.classList.add('hidden');
        singleDiv.classList.add('hidden');
        
        // إزالة required من جميع الحقول
        document.getElementById('single_client_id').removeAttribute('required');
        
        // إزالة أي hidden inputs سابقة
        const existingInputs = document.querySelectorAll('input[name="client_ids[]"][type="hidden"]');
        existingInputs.forEach(input => input.remove());
        
        if (selection === 'multiple') {
            multipleDiv.classList.remove('hidden');
            updateSelectedCount();
        } else if (selection === 'single') {
            singleDiv.classList.remove('hidden');
            document.getElementById('single_client_id').setAttribute('required', 'required');
        } else if (selection === 'all') {
            // إضافة hidden inputs لجميع المديونين
            @if($debtors->count() > 0)
                @foreach($debtors as $debtor)
                    const input{{ $debtor->id }} = document.createElement('input');
                    input{{ $debtor->id }}.type = 'hidden';
                    input{{ $debtor->id }}.name = 'client_ids[]';
                    input{{ $debtor->id }}.value = '{{ $debtor->id }}';
                    document.getElementById('campaignForm').appendChild(input{{ $debtor->id }});
                @endforeach
            @endif
        }
    }

    // Update Selected Count
    function updateSelectedCount() {
        const checkboxes = document.querySelectorAll('.debtor-checkbox:checked');
        const countSpan = document.getElementById('selectedCount');
        const selectAllText = document.getElementById('selectAllText');
        
        if (countSpan) {
            const selectedCount = checkboxes.length;
            countSpan.textContent = selectedCount + ' محدد';
            
            if (selectedCount > 0) {
                countSpan.classList.add('text-primary-600', 'dark:text-primary-400');
                countSpan.classList.remove('text-gray-500', 'dark:text-gray-400');
            } else {
                countSpan.classList.add('text-gray-500', 'dark:text-gray-400');
                countSpan.classList.remove('text-primary-600', 'dark:text-primary-400');
            }
        }
        
        // Update Select All button text
        const allCheckboxes = document.querySelectorAll('.debtor-checkbox:not([style*="display: none"])');
        const allChecked = allCheckboxes.length > 0 && Array.from(allCheckboxes).every(cb => cb.checked);
        if (selectAllText) {
            selectAllText.textContent = allChecked ? 'إلغاء التحديد' : 'تحديد الكل';
        }
    }

    // Toggle Select All
    function toggleSelectAll() {
        const visibleCheckboxes = Array.from(document.querySelectorAll('.debtor-item:not([style*="display: none"]) .debtor-checkbox'));
        const allChecked = visibleCheckboxes.length > 0 && visibleCheckboxes.every(cb => cb.checked);
        
        visibleCheckboxes.forEach(checkbox => {
            checkbox.checked = !allChecked;
        });
        
        updateSelectedCount();
    }

    // Filter Debtors
    function filterDebtors() {
        const searchTerm = document.getElementById('debtorSearch').value.toLowerCase();
        const items = document.querySelectorAll('.debtor-item');
        
        items.forEach(item => {
            const name = item.getAttribute('data-name') || '';
            const phone = item.getAttribute('data-phone') || '';
            const matches = name.includes(searchTerm) || phone.includes(searchTerm);
            
            if (matches) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
        
        updateSelectedCount();
    }

    // Load Template
    function loadTemplate() {
        const template = document.getElementById('template').value;
        const messageField = document.getElementById('message');
        
        if (template && templates[template]) {
            messageField.value = templates[template];
            updatePreview();
        } else if (template === 'custom') {
            messageField.value = '';
            updatePreview();
        }
    }

    // Update Preview
    function updatePreview() {
        const message = document.getElementById('message').value;
        const preview = document.getElementById('messagePreview');
        
        if (message) {
            preview.textContent = message;
        } else {
            preview.textContent = 'ستظهر معاينة الرسالة هنا...';
        }
    }

    // Toggle Schedule Input
    function toggleScheduleInput() {
        const sendType = document.querySelector('input[name="send_type"]:checked').value;
        const scheduledDiv = document.getElementById('scheduledAtDiv');
        const scheduledInput = document.getElementById('scheduled_at');
        
        if (sendType === 'scheduled') {
            scheduledDiv.classList.remove('hidden');
            scheduledInput.setAttribute('required', 'required');
        } else {
            scheduledDiv.classList.add('hidden');
            scheduledInput.removeAttribute('required');
        }
    }

    // Validate Campaign Submission
    function validateCampaignSubmission(e) {
        const selection = document.getElementById('client_selection').value;
        let selectedClientsCount = 0;
        
        // التحقق من اختيار المديونين
        if (selection === 'single') {
            const singleClientId = document.getElementById('single_client_id').value;
            if (!singleClientId) {
                e.preventDefault();
                swalError('يرجى اختيار المديون', 'تنبيه');
                return false;
            }
            selectedClientsCount = 1;
            // إضافة hidden input
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'client_ids[]';
            hiddenInput.value = singleClientId;
            document.getElementById('campaignForm').appendChild(hiddenInput);
        } else if (selection === 'all') {
            // التحقق من وجود مديونين
            const allDebtorsCount = {{ $debtors->count() }};
            if (allDebtorsCount === 0) {
                e.preventDefault();
                swalError('لا يوجد مديونين متاحين', 'تنبيه');
                return false;
            }
            selectedClientsCount = allDebtorsCount;
        } else if (selection === 'multiple') {
            const selectedCheckboxes = Array.from(document.querySelectorAll('.debtor-checkbox:checked'));
            if (selectedCheckboxes.length === 0) {
                e.preventDefault();
                swalError('يرجى اختيار مديون واحد على الأقل', 'تنبيه');
                return false;
            }
            selectedClientsCount = selectedCheckboxes.length;
        } else {
            e.preventDefault();
            swalError('يرجى اختيار طريقة اختيار المديونين', 'تنبيه');
            return false;
        }

        // التحقق من حدود الاشتراك - عدد الرسائل
        @if($subscriptionInfo)
            const maxMessages = {{ $subscriptionInfo['max_messages'] ?? 0 }};
            const messagesSent = {{ $subscriptionInfo['messages_sent'] ?? 0 }};
            const messagesRemaining = {{ $subscriptionInfo['messages_remaining'] ?? 0 }};
            
            if (maxMessages > 0) {
                if (messagesRemaining === 0) {
                    e.preventDefault();
                    swalError('لقد استنفدت جميع الرسائل المسموحة! الحد المسموح: ' + maxMessages + ' رسالة. سيتم توجيهك إلى صفحة الاشتراكات لترقية اشتراكك.', 'حد الرسائل').then(() => {
                        window.location.href = '{{ route("owner.subscriptions.index") }}';
                    });
                    return false;
                }
                
                if (selectedClientsCount > messagesRemaining) {
                    e.preventDefault();
                    swalError('لا يمكنك إرسال ' + selectedClientsCount + ' رسالة! لديك ' + messagesRemaining + ' رسالة متبقية فقط من الحد المسموح (' + maxMessages + '). سيتم توجيهك إلى صفحة الاشتراكات لترقية اشتراكك.', 'حد الرسائل').then(() => {
                        window.location.href = '{{ route("owner.subscriptions.index") }}';
                    });
                    return false;
                }
            }
        @else
            e.preventDefault();
            swalError('لا يوجد اشتراك نشط. يرجى الاشتراك في إحدى الباقات أولاً. سيتم توجيهك إلى صفحة الاشتراكات.', 'لا يوجد اشتراك').then(() => {
                window.location.href = '{{ route("owner.subscriptions.index") }}';
            });
            return false;
        @endif

        return true;
    }

    // Close modal when clicking outside
    document.getElementById('campaignModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeCampaignModal();
        }
    });
</script>
@endsection
