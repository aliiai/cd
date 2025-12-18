@extends('layouts.owner')

@section('content')
<div class="py-12">
    <div class="w-full mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">تقرير الاشتراك والاستهلاك</h1>
            <p class="text-gray-600 mt-2">معلومات الاشتراك الحالي ومؤشرات الاستهلاك</p>
        </div>

        <!-- Warnings -->
        @if(count($warnings) > 0)
            <div class="mb-6 space-y-3">
                @foreach($warnings as $warning)
                    <div class="bg-{{ $warning['color'] }}-50 border-l-4 border-{{ $warning['color'] }}-500 p-4 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-{{ $warning['color'] }}-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <p class="text-sm font-medium text-{{ $warning['color'] }}-800">{{ $warning['message'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Subscription Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border-l-4 border-primary-500">
            <h2 class="text-xl font-bold text-gray-900 mb-4">الاشتراك الحالي</h2>
            @if($activeSubscription)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">اسم الباقة</p>
                        <p class="text-lg font-bold text-gray-900">{{ $activeSubscription->subscription->name ?? 'غير محدد' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">الحالة</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            نشط
                        </span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">تاريخ البدء</p>
                        <p class="text-sm text-gray-900">{{ $activeSubscription->started_at ? $activeSubscription->started_at->format('Y-m-d') : 'غير محدد' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">تاريخ الانتهاء</p>
                        <p class="text-sm text-gray-900">
                            @if($activeSubscription->expires_at)
                                {{ $activeSubscription->expires_at->format('Y-m-d') }}
                            @else
                                <span class="text-gray-400">دائم</span>
                            @endif
                        </p>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500">لا يوجد اشتراك نشط حالياً</p>
                    <a href="{{ route('owner.subscriptions.index') }}" class="mt-4 inline-block px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors duration-200">
                        عرض الباقات
                    </a>
                </div>
            @endif
        </div>

        <!-- Usage Indicators -->
        @if($activeSubscription)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Debtors Usage -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-secondary-500">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">عدد المديونين</h3>
                        <span class="text-2xl font-bold {{ $debtorsUsage >= 90 ? 'text-red-600' : ($debtorsUsage >= 70 ? 'text-yellow-600' : 'text-green-600') }}">
                            {{ $debtorsCount }} / {{ $maxDebtors }}
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div 
                            class="h-3 rounded-full transition-all duration-300 {{ $debtorsUsage >= 90 ? 'bg-red-600' : ($debtorsUsage >= 70 ? 'bg-yellow-600' : 'bg-green-600') }}" 
                            style="width: {{ min($debtorsUsage, 100) }}%"
                        ></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">الاستهلاك: {{ number_format($debtorsUsage, 1) }}%</p>
                </div>

                <!-- Messages Usage -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-primary-500">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">عدد الرسائل</h3>
                        <span class="text-2xl font-bold {{ $messagesUsage >= 90 ? 'text-red-600' : ($messagesUsage >= 70 ? 'text-yellow-600' : 'text-green-600') }}">
                            {{ $messagesSent }} / {{ $maxMessages }}
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div 
                            class="h-3 rounded-full transition-all duration-300 {{ $messagesUsage >= 90 ? 'bg-red-600' : ($messagesUsage >= 70 ? 'bg-yellow-600' : 'bg-green-600') }}" 
                            style="width: {{ min($messagesUsage, 100) }}%"
                        ></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">الاستهلاك: {{ number_format($messagesUsage, 1) }}%</p>
                </div>

                <!-- AI Usage (Static) -->
                <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-teal-500">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900">استهلاك الذكاء الاصطناعي</h3>
                        <span class="text-2xl font-bold text-gray-600">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="h-3 rounded-full bg-gray-400" style="width: 0%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">سيتم تفعيله قريباً</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

