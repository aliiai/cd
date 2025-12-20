@extends('layouts.owner')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-primary-50 to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- ========== Header Section ========== --}}
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">حملات التحصيل</h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">إنشاء وإدارة حملات التحصيل للمديونين</p>
            </div>
            <button onclick="openCampaignModal()" 
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-primary-500 to-secondary-500 hover:from-primary-600 hover:to-secondary-600 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                إنشاء حملة تحصيل
            </button>
        </div>

        {{-- ========== Success/Error Messages ========== --}}
        @if(session('success'))
            <div class="mb-6 bg-gradient-to-r from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 border-l-4 border-emerald-500 dark:border-emerald-400 rounded-lg p-4 flex items-center shadow-md">
                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-emerald-800 dark:text-emerald-300 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 border-l-4 border-red-500 dark:border-red-400 rounded-lg p-4 flex items-center shadow-md">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <p class="text-red-800 dark:text-red-300 font-medium">{{ session('error') }}</p>
            </div>
        @endif

        {{-- ========== Subscription Usage Info ========== --}}
        @if($subscriptionInfo)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center">
                        <svg class="w-6 h-6 ml-2 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        معلومات الاشتراك والاستهلاك
                    </h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">الباقة الحالية</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $subscriptionInfo['subscription_name'] }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">استهلاك الرسائل</p>
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                                <div 
                                    class="h-3 rounded-full transition-all duration-500 {{ $subscriptionInfo['messages_usage'] >= 90 ? 'bg-gradient-to-r from-red-500 to-red-600' : ($subscriptionInfo['messages_usage'] >= 70 ? 'bg-gradient-to-r from-yellow-500 to-yellow-600' : 'bg-gradient-to-r from-emerald-500 to-emerald-600') }}" 
                                    style="width: {{ min($subscriptionInfo['messages_usage'], 100) }}%"
                                ></div>
                            </div>
                            <span class="text-sm font-bold {{ $subscriptionInfo['messages_usage'] >= 90 ? 'text-red-600 dark:text-red-400' : ($subscriptionInfo['messages_usage'] >= 70 ? 'text-yellow-600 dark:text-yellow-400' : 'text-emerald-600 dark:text-emerald-400') }}">
                                {{ $subscriptionInfo['messages_sent'] }} / {{ $subscriptionInfo['max_messages'] }}
                            </span>
                        </div>
                        @if($subscriptionInfo['messages_remaining'] !== null)
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                المتبقي: <span class="font-semibold {{ $subscriptionInfo['messages_remaining'] <= 5 ? 'text-red-600 dark:text-red-400' : 'text-gray-700 dark:text-gray-300' }}">{{ $subscriptionInfo['messages_remaining'] }}</span> رسالة
                            </p>
                        @endif
                    </div>
                </div>
                @if($subscriptionInfo['messages_remaining'] !== null && $subscriptionInfo['messages_remaining'] <= 5 && $subscriptionInfo['messages_remaining'] > 0)
                    <div class="mt-4 bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 border-l-4 border-yellow-500 dark:border-yellow-400 rounded-lg p-4 flex items-start">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 ml-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p class="text-sm text-yellow-800 dark:text-yellow-300 font-medium">
                            تحذير: لديك {{ $subscriptionInfo['messages_remaining'] }} رسالة متبقية فقط. يرجى ترقية اشتراكك لإرسال المزيد.
                        </p>
                    </div>
                @elseif($subscriptionInfo['messages_remaining'] !== null && $subscriptionInfo['messages_remaining'] == 0)
                    <div class="mt-4 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 border-l-4 border-red-500 dark:border-red-400 rounded-lg p-4 flex items-start">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400 ml-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <p class="text-sm text-red-800 dark:text-red-300 font-medium">
                            لقد استنفدت جميع الرسائل المسموحة! يرجى ترقية اشتراكك لإرسال المزيد.
                        </p>
                    </div>
                @endif
            </div>
        @else
            <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 border-l-4 border-yellow-500 dark:border-yellow-400 rounded-lg p-4 mb-8 flex items-center shadow-md">
                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <p class="text-sm text-yellow-800 dark:text-yellow-300 font-medium">
                    لا يوجد اشتراك نشط. يرجى الاشتراك في إحدى الباقات لإرسال الرسائل.
                </p>
            </div>
        @endif

        {{-- ========== Campaigns Table ========== --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="bg-gradient-to-r from-primary-500 to-secondary-500 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                    الحملات السابقة
                </h2>
            </div>
            <div class="p-6">
                @if($campaigns->count() > 0)
                    <div class="overflow-x-auto rounded-lg">
                        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                                <tr>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">رقم الحملة</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">عدد المستلمين</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">قناة الإرسال</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">حالة الإرسال</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">وقت الإرسال</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($campaigns as $campaign)
                                    <tr class="hover:bg-primary-50 dark:hover:bg-gray-700 transition-all duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center text-white font-bold text-sm ml-3">
                                                    {{ mb_substr($campaign->campaign_number ?? 'C', 0, 1) }}
                                                </div>
                                                <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $campaign->campaign_number }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-primary-100 dark:bg-primary-900/30 text-primary-800 dark:text-primary-300 border border-primary-200 dark:border-primary-800">
                                                {{ $campaign->total_recipients }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($campaign->channel == 'sms')
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                    </svg>
                                                    SMS
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                    Email
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm {{ $campaign->status_color }}">
                                                {{ $campaign->status_text }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            @if($campaign->send_type === 'scheduled' && $campaign->scheduled_at)
                                                {{ $campaign->scheduled_at->format('Y-m-d H:i') }}
                                            @else
                                                {{ $campaign->created_at->format('Y-m-d H:i') }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('owner.collections.show', $campaign) }}" 
                                               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary-500 to-secondary-500 hover:from-primary-600 hover:to-secondary-600 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                عرض التفاصيل
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
<div id="campaignModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden overflow-y-auto h-full w-full z-50" style="display: none; align-items: center; justify-content: center; padding: 2rem;">
    <div class="relative w-full max-w-6xl bg-white dark:bg-gray-800 rounded-xl shadow-2xl transform transition-all opacity-0 scale-95" style="max-height: 90vh; overflow-y: auto;">
        {{-- Modal Header --}}
        <div class="sticky top-0 bg-gradient-to-r from-primary-500 to-secondary-500 text-white px-6 py-4 rounded-t-xl flex items-center justify-between z-10 shadow-md">
            <h3 class="text-xl font-bold flex items-center">
                <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                إنشاء حملة تحصيل جديدة
            </h3>
            <button onclick="closeCampaignModal()" class="text-white hover:text-gray-200 transition-colors duration-200 p-1 rounded-full hover:bg-white/20">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Form --}}
        <form id="campaignForm" action="{{ route('owner.collections.store') }}" method="POST" class="p-6" onsubmit="return validateCampaignSubmission(event)">
            @csrf

            {{-- Grid Layout --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
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

                    {{-- Clients Multi-Select --}}
                    <div id="multipleClientsDiv" class="hidden">
                        <label for="client_ids" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            اختر المديونين <span class="text-red-500">*</span>
                        </label>
                        <select name="client_ids[]" 
                                id="client_ids" 
                                multiple
                                size="6"
                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200">
                            @foreach($debtors as $debtor)
                                <option value="{{ $debtor->id }}">{{ $debtor->name }} - {{ $debtor->phone }} ({{ number_format($debtor->debt_amount, 2) }} ر.س)</option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">اضغط Ctrl (أو Cmd على Mac) لاختيار أكثر من مدين</p>
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
        document.getElementById('client_ids').removeAttribute('required');
        document.getElementById('single_client_id').removeAttribute('required');
        
        // إزالة أي hidden inputs سابقة
        const existingInputs = document.querySelectorAll('input[name="client_ids[]"][type="hidden"]');
        existingInputs.forEach(input => input.remove());
        
        if (selection === 'multiple') {
            multipleDiv.classList.remove('hidden');
            document.getElementById('client_ids').setAttribute('required', 'required');
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
            const selectedClients = Array.from(document.getElementById('client_ids').selectedOptions);
            if (selectedClients.length === 0) {
                e.preventDefault();
                swalError('يرجى اختيار مديون واحد على الأقل', 'تنبيه');
                return false;
            }
            selectedClientsCount = selectedClients.length;
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
