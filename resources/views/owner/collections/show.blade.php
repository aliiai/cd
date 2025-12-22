@extends('layouts.owner')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-primary-50 to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="w-full mx-auto px-2 sm:px-4 md:px-6 lg:px-8 py-4 sm:py-6 md:py-8">
        
        {{-- ========== Header Section ========== --}}
        <div class="mb-4 sm:mb-6 md:mb-8">
            <a href="{{ route('owner.collections.index') }}" 
               class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 text-xs sm:text-sm mb-3 sm:mb-4 transition-colors duration-200 font-medium">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="hidden sm:inline">العودة إلى قائمة الحملات</span>
                <span class="sm:hidden">رجوع</span>
            </a>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-1 sm:mb-2">تفاصيل الحملة</h1>
                    <p class="text-sm sm:text-base lg:text-lg text-gray-600 dark:text-gray-400">رقم الحملة: <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $campaign->campaign_number }}</span></p>
                </div>
                <span class="px-3 py-1.5 sm:px-4 sm:py-2.5 text-xs sm:text-sm font-semibold rounded-full shadow-md {{ $campaign->status_color }} self-start sm:self-auto">
                    {{ $campaign->status_text }}
                </span>
            </div>
        </div>

        {{-- ========== Statistics Cards ========== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4 md:gap-6 mb-4 sm:mb-6 md:mb-8">
            
            {{-- بطاقة إجمالي المستلمين --}}
            @include('owner.dashboard.partials.stat-card-primary', [
                'value' => number_format($campaign->total_recipients),
                'subtitle' => 'مستلم',
                'gradientFrom' => 'from-primary-500',
                'gradientTo' => 'to-primary-600',
                'badge' => 'إجمالي',
                'icon' => '<svg class="w-5 h-5 sm:w-6 sm:h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>'
            ])

            {{-- بطاقة تم الإرسال --}}
            @include('owner.dashboard.partials.stat-card-primary', [
                'value' => number_format($campaign->sent_count ?? 0),
                'subtitle' => 'رسالة مرسلة',
                'gradientFrom' => 'from-emerald-500',
                'gradientTo' => 'to-emerald-600',
                'badge' => 'نجحت',
                'icon' => '<svg class="w-5 h-5 sm:w-6 sm:h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
            ])

            {{-- بطاقة فشل الإرسال --}}
            @include('owner.dashboard.partials.stat-card-primary', [
                'value' => number_format($campaign->failed_count ?? 0),
                'subtitle' => 'رسالة فاشلة',
                'gradientFrom' => 'from-red-500',
                'gradientTo' => 'to-red-600',
                'badge' => 'فشل',
                'icon' => '<svg class="w-5 h-5 sm:w-6 sm:h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>'
            ])

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-4 md:gap-6">
            {{-- ========== Left Column - Campaign Info ========== --}}
            <div class="lg:col-span-1 space-y-4 sm:space-y-6">
                {{-- Campaign Basic Info Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-primary-500 to-secondary-500 px-4 sm:px-5 md:px-6 py-3 sm:py-4">
                        <h2 class="text-base sm:text-lg font-bold text-white flex items-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            معلومات الحملة
                        </h2>
                    </div>
                    <div class="p-4 sm:p-5 md:p-6">
                        <div class="space-y-3 sm:space-y-4">
                            <div class="pb-4 border-b border-gray-200 dark:border-gray-700">
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">قناة الإرسال</label>
                                <div class="mt-2">
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
                                </div>
                            </div>
                            <div class="pb-4 border-b border-gray-200 dark:border-gray-700">
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">نوع الإرسال</label>
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold {{ $campaign->send_type_color }}">
                                        @if($campaign->send_type === 'auto')
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                        @elseif($campaign->send_type === 'now')
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @endif
                                        {{ $campaign->send_type_text }}
                                    </span>
                                    @if($campaign->send_type === 'auto')
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">تم إنشاء هذه الحملة تلقائياً في تاريخ استحقاق الدفع</p>
                                    @endif
                                </div>
                            </div>
                            @if($campaign->scheduled_at)
                                <div class="pb-4 border-b border-gray-200 dark:border-gray-700">
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">وقت الجدولة</label>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 mt-2">{{ $campaign->scheduled_at->format('Y-m-d H:i') }}</p>
                                </div>
                            @endif
                            <div>
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">تاريخ الإنشاء</label>
                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 mt-2">{{ $campaign->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========== Right Column - Message & Recipients ========== --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Message Content Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-primary-500 to-secondary-500 px-4 sm:px-5 md:px-6 py-3 sm:py-4">
                        <h2 class="text-base sm:text-lg font-bold text-white flex items-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            محتوى الرسالة
                        </h2>
                    </div>
                    <div class="p-4 sm:p-5 md:p-6">
                        @if($campaign->template)
                            <div class="mb-4">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-secondary-100 dark:bg-secondary-900/30 text-secondary-800 dark:text-secondary-300 border border-secondary-200 dark:border-secondary-800">
                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                                    </svg>
                                    القالب: {{ $campaign->template }}
                                </span>
                            </div>
                        @endif
                        <div class="p-5 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 shadow-inner">
                            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap leading-relaxed">{{ $campaign->message }}</p>
                        </div>
                    </div>
                </div>

                {{-- Recipients List Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-primary-500 to-secondary-500 px-4 sm:px-5 md:px-6 py-3 sm:py-4">
                        <h2 class="text-base sm:text-lg font-bold text-white flex items-center justify-between">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <span class="hidden sm:inline">قائمة المستلمين</span>
                                <span class="sm:hidden">المستلمين</span>
                            </span>
                            <span class="text-xs sm:text-sm font-normal bg-white/20 px-2 py-0.5 sm:px-3 sm:py-1 rounded-full">
                                {{ $campaign->debtors->count() }} مستلم
                            </span>
                        </h2>
                    </div>
                    <div class="p-4 sm:p-5 md:p-6">
                        @if($campaign->debtors->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                @foreach($campaign->debtors as $debtor)
                                    @php
                                        $pivotStatus = $debtor->pivot->status ?? 'pending';
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 border-yellow-200 dark:border-yellow-800',
                                            'sent' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                                            'failed' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 border-red-200 dark:border-red-800',
                                        ];
                                        $statusTexts = [
                                            'pending' => 'قيد الانتظار',
                                            'sent' => 'تم الإرسال',
                                            'failed' => 'فشل',
                                        ];
                                        $statusIcons = [
                                            'pending' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                            'sent' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                            'failed' => 'M6 18L18 6M6 6l12 12',
                                        ];
                                    @endphp
                                    <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-700 dark:to-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 p-3 sm:p-4 hover:shadow-md transition-all duration-200 hover:border-primary-300 dark:hover:border-primary-600">
                                        <div class="flex items-start justify-between mb-2 sm:mb-3">
                                            <div class="flex items-center flex-1 min-w-0">
                                                <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center text-white font-bold text-xs sm:text-sm ml-2 sm:ml-3">
                                                    {{ mb_substr($debtor->name ?? 'M', 0, 1) }}
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h3 class="text-xs sm:text-sm font-bold text-gray-900 dark:text-gray-100 mb-1 truncate">{{ $debtor->name }}</h3>
                                                    <div class="space-y-1">
                                                        <p class="text-xs text-gray-600 dark:text-gray-400 flex items-center">
                                                            <svg class="w-3 h-3 ml-1 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                            </svg>
                                                            {{ $debtor->phone }}
                                                        </p>
                                                        @if($debtor->email)
                                                            <p class="text-xs text-gray-600 dark:text-gray-400 flex items-center">
                                                                <svg class="w-3 h-3 ml-1 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                                </svg>
                                                                {{ $debtor->email }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="inline-flex items-center px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-full text-xs font-semibold border {{ $statusColors[$pivotStatus] ?? 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border-gray-200 dark:border-gray-600' }} flex-shrink-0">
                                                <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $statusIcons[$pivotStatus] ?? 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z' }}"></path>
                                                </svg>
                                                <span class="hidden sm:inline">{{ $statusTexts[$pivotStatus] ?? 'غير محدد' }}</span>
                                            </span>
                                        </div>
                                        @if($debtor->pivot->sent_at)
                                            <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                                <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    تم الإرسال: {{ \Carbon\Carbon::parse($debtor->pivot->sent_at)->format('Y-m-d H:i') }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400 font-medium">لا يوجد مستلمين</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
