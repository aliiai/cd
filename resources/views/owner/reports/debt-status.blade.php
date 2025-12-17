@extends('layouts.owner')

@section('content')
<div class="py-12">
    <div class="w-full mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">تقرير حالات الديون</h1>
            <p class="text-gray-600 mt-2">عرض توزيع المديونين حسب الحالة مع إجمالي المبلغ</p>
        </div>

        <!-- Debt Status Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($debtStatusReport as $report)
                @php
                    $borderColor = match($report['status']) {
                        'new' => 'border-blue-500',
                        'contacted' => 'border-yellow-500',
                        'promise_to_pay' => 'border-purple-500',
                        'paid' => 'border-green-500',
                        'overdue' => 'border-red-500',
                        default => 'border-gray-500',
                    };
                @endphp
                <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden border-l-4 {{ $borderColor }}">
                    <div class="p-6">
                        <!-- Status Badge -->
                        <div class="flex items-center justify-between mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $report['status_color'] }}">
                                {{ $report['status_text'] }}
                            </span>
                            @if($report['status'] == 'new')
                                <div class="bg-blue-100 rounded-full p-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                            @elseif($report['status'] == 'contacted')
                                <div class="bg-yellow-100 rounded-full p-2">
                                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                </div>
                            @elseif($report['status'] == 'promise_to_pay')
                                <div class="bg-purple-100 rounded-full p-2">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            @elseif($report['status'] == 'paid')
                                <div class="bg-green-100 rounded-full p-2">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            @elseif($report['status'] == 'overdue')
                                <div class="bg-red-100 rounded-full p-2">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                            @else
                                <div class="bg-gray-100 rounded-full p-2">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Count -->
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-600 mb-1">عدد المديونين</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $report['count'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">مديون</p>
                        </div>

                        <!-- Total Amount -->
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-600 mb-1">إجمالي المبلغ</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($report['total_amount'], 2) }} ر.س</p>
                        </div>

                        <!-- Action Button -->
                        <a 
                            href="{{ route('owner.debtors.index', ['status' => $report['status']]) }}" 
                            class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium"
                        >
                            عرض المديونين
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

