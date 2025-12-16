@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">إدارة الباقات</h1>
                <a href="{{ route('admin.subscription-requests.index') }}" 
                   class="mt-2 inline-block text-sm text-blue-600 hover:text-blue-900">
                    عرض طلبات الاشتراك →
                </a>
            </div>
            <a href="{{ route('admin.subscriptions.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                + إنشاء باقة جديدة
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Subscriptions Grid -->
        @if($subscriptions->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($subscriptions as $subscription)
                    <div class="relative bg-white overflow-hidden rounded-xl shadow-md hover:shadow-xl transition-all duration-300 {{ $subscription->is_active ? 'border-2 border-green-200' : 'border-2 border-gray-200 opacity-75' }}">
                        <!-- Status Badge -->
                        <div class="absolute top-4 right-4 z-10">
                            @if($subscription->is_active)
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-green-500 text-white shadow-lg">
                                    ✓ مفعل
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-gray-500 text-white shadow-lg">
                                    ✗ متوقف
                                </span>
                            @endif
                        </div>

                        <div class="p-6">
                            <!-- Subscription Header -->
                            <div class="mb-4">
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $subscription->name }}</h3>
                                <div class="flex items-baseline">
                                    <span class="text-4xl font-bold text-blue-600">
                                        {{ number_format($subscription->price, 2) }}
                                    </span>
                                    <span class="text-lg text-gray-600 mr-2">ر.س</span>
                                    <span class="text-sm text-gray-500 mr-2">/ {{ $subscription->duration_text }}</span>
                                </div>
                            </div>

                            <!-- Description -->
                            @if($subscription->description)
                                <p class="text-sm text-gray-600 mb-6 leading-relaxed">{{ Str::limit($subscription->description, 100) }}</p>
                            @endif

                            <!-- Features -->
                            <div class="space-y-3 mb-6 border-t border-gray-200 pt-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <div>
                                        <span class="text-sm font-medium text-gray-900">عدد المديونين:</span>
                                        <span class="text-sm text-gray-700 mr-2">{{ number_format($subscription->max_debtors) }}</span>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <span class="text-sm font-medium text-gray-900">عدد الرسائل:</span>
                                        <span class="text-sm text-gray-700 mr-2">{{ number_format($subscription->max_messages) }}</span>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    @if($subscription->ai_enabled)
                                        <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-green-600">الذكاء الاصطناعي متاح</span>
                                    @else
                                        <svg class="w-5 h-5 text-gray-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        <span class="text-sm text-gray-500">الذكاء الاصطناعي غير متاح</span>
                                    @endif
                                </div>

                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-purple-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-900">المدة: {{ $subscription->duration_text }}</span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex space-x-2 space-x-reverse border-t border-gray-200 pt-4">
                                <a href="{{ route('admin.subscriptions.edit', $subscription) }}" 
                                   class="flex-1 text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                                    تعديل
                                </a>
                                <form action="{{ route('admin.subscriptions.destroy', $subscription) }}" 
                                      method="POST" 
                                      class="flex-1"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذه الباقة؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                                        حذف
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-lg shadow-sm">
                <p class="text-gray-500 text-lg">لا توجد باقات متاحة حالياً.</p>
                <a href="{{ route('admin.subscriptions.create') }}" 
                   class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                    إنشاء باقة جديدة
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
