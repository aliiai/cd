@extends('layouts.owner')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-primary-50 to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- ========== Header Section ========== --}}
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">تقرير حالات الديون</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400">عرض توزيع المديونين حسب الحالة مع إجمالي المبلغ</p>
        </div>

        {{-- ========== Debt Status Cards ========== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($debtStatusReport as $report)
                @php
                    $gradientFrom = match($report['status']) {
                        'new' => 'from-blue-500',
                        'contacted' => 'from-yellow-500',
                        'promise_to_pay' => 'from-purple-500',
                        'paid' => 'from-emerald-500',
                        'overdue' => 'from-red-500',
                        'failed' => 'from-gray-500',
                        default => 'from-gray-500',
                    };
                    $gradientTo = match($report['status']) {
                        'new' => 'to-blue-600',
                        'contacted' => 'to-yellow-600',
                        'promise_to_pay' => 'to-purple-600',
                        'paid' => 'to-emerald-600',
                        'overdue' => 'to-red-600',
                        'failed' => 'to-gray-600',
                        default => 'to-gray-600',
                    };
                    $icon = match($report['status']) {
                        'new' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>',
                        'contacted' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>',
                        'promise_to_pay' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                        'paid' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                        'overdue' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>',
                        'failed' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>',
                        default => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>',
                    };
                @endphp
                
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-200 dark:border-gray-700">
                    {{-- Header with Gradient --}}
                    <div class="bg-gradient-to-r {{ $gradientFrom }} {{ $gradientTo }} px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3 space-x-reverse">
                                <div class="bg-white/20 rounded-lg p-2">
                                    {!! $icon !!}
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">{{ $report['status_text'] }}</h3>
                                </div>
                            </div>
                            <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-semibold text-white">
                                {{ $report['count'] }} مديون
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        {{-- Count --}}
                        <div class="mb-6">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">عدد المديونين</p>
                            <p class="text-4xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($report['count']) }}</p>
                        </div>

                        {{-- Total Amount --}}
                        <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">إجمالي المبلغ</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($report['total_amount'], 2) }} <span class="text-lg text-gray-500 dark:text-gray-400">ر.س</span></p>
                        </div>

                        {{-- Action Button --}}
                        <a 
                            href="{{ route('owner.debtors.index', ['status' => $report['status']]) }}" 
                            class="block w-full text-center px-4 py-3 bg-gradient-to-r {{ $gradientFrom }} {{ $gradientTo }} hover:opacity-90 text-white rounded-lg transition-all duration-200 font-semibold shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                        >
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                عرض المديونين
                            </span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
