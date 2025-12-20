@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-primary-50 to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12">
    <div class="w-full mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">إدارة الباقات</h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400">إدارة وتعديل باقات الاشتراك المتاحة</p>
                    <a href="{{ route('admin.subscription-requests.index') }}" 
                       class="mt-3 inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 text-sm transition-colors">
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        عرض طلبات الاشتراك
                    </a>
                </div>
                <button onclick="openCreateModal()" 
                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 dark:from-primary-500 dark:to-primary-600 hover:from-primary-700 hover:to-primary-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    إنشاء باقة جديدة
                </button>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-300 px-4 py-3 rounded-lg flex items-center">
                <svg class="w-5 h-5 ml-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-300 px-4 py-3 rounded-lg flex items-center">
                <svg class="w-5 h-5 ml-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Subscriptions Grid -->
        @if($subscriptions->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($subscriptions as $subscription)
                    <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-200 dark:border-gray-700 {{ $subscription->is_active ? 'ring-2 ring-primary-200 dark:ring-primary-800' : 'opacity-75' }}">
                        <!-- Gradient Header -->
                        <div class="h-2 {{ $subscription->is_active ? 'bg-gradient-to-r from-primary-500 via-primary-600 to-secondary-500' : 'bg-gradient-to-r from-gray-400 to-gray-500' }}"></div>

                        <div class="p-6">
                            <!-- Subscription Header -->
                            <div class="mb-6">
                                <!-- Status Badge - Above Title -->
                                <div class="flex justify-end mb-2">
                                    @if($subscription->is_active)
                                        <span class="px-3 py-1.5 text-xs font-bold rounded-full bg-green-500 dark:bg-green-600 text-white shadow-lg flex items-center">
                                            <span class="w-2 h-2 bg-white rounded-full ml-2 animate-pulse"></span>
                                            مفعل
                                        </span>
                                    @else
                                        <span class="px-3 py-1.5 text-xs font-bold rounded-full bg-gray-500 dark:bg-gray-600 text-white shadow-lg flex items-center">
                                            <svg class="w-3 h-3 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                            متوقف
                                        </span>
                                    @endif
                                </div>
                                <!-- Title -->
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-3">{{ $subscription->name }}</h3>
                                <div class="flex items-baseline">
                                    <span class="text-5xl font-bold bg-gradient-to-r from-primary-600 to-secondary-600 dark:from-primary-400 dark:to-secondary-400 bg-clip-text text-transparent">
                                        {{ number_format($subscription->price, 2) }}
                                    </span>
                                    <span class="text-xl text-gray-600 dark:text-gray-400 mr-2">ر.س</span>
                                </div>
                                <span class="text-sm text-gray-500 dark:text-gray-500 mt-1 block">/ {{ $subscription->duration_text }}</span>
                            </div>

                            <!-- Description -->
                            @if($subscription->description)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 leading-relaxed line-clamp-2">{{ Str::limit($subscription->description, 100) }}</p>
                            @endif

                            <!-- Features -->
                            <div class="space-y-3 mb-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                                <!-- Max Debtors -->
                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <div class="flex-shrink-0 w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center ml-3">
                                        <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 block">عدد المديونين</span>
                                        <span class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ number_format($subscription->max_debtors) }}</span>
                                    </div>
                                </div>
                                
                                <!-- Max Messages -->
                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <div class="flex-shrink-0 w-10 h-10 bg-secondary-100 dark:bg-secondary-900/30 rounded-lg flex items-center justify-center ml-3">
                                        <svg class="w-5 h-5 text-secondary-600 dark:text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 block">عدد الرسائل</span>
                                        <span class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ number_format($subscription->max_messages) }}</span>
                                    </div>
                                </div>
                                
                                <!-- AI Enabled -->
                                <div class="flex items-center p-3 {{ $subscription->ai_enabled ? 'bg-green-50 dark:bg-green-900/20' : 'bg-gray-50 dark:bg-gray-700/50' }} rounded-lg">
                                    <div class="flex-shrink-0 w-10 h-10 {{ $subscription->ai_enabled ? 'bg-green-100 dark:bg-green-900/30' : 'bg-gray-200 dark:bg-gray-600' }} rounded-lg flex items-center justify-center ml-3">
                                        @if($subscription->ai_enabled)
                                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-sm font-medium {{ $subscription->ai_enabled ? 'text-green-700 dark:text-green-300' : 'text-gray-500 dark:text-gray-400' }} block">
                                            {{ $subscription->ai_enabled ? 'الذكاء الاصطناعي متاح' : 'الذكاء الاصطناعي غير متاح' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Duration -->
                                <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div class="flex-shrink-0 w-10 h-10 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center ml-3">
                                        <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 block">المدة</span>
                                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $subscription->duration_text }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex space-x-2 space-x-reverse border-t border-gray-200 dark:border-gray-700 pt-4">
                                <a href="{{ route('admin.subscriptions.edit', $subscription) }}" 
                                   class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 dark:from-primary-500 dark:to-primary-600 hover:from-primary-700 hover:to-primary-800 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    تعديل
                                </a>
                                <form action="{{ route('admin.subscriptions.destroy', $subscription) }}" 
                                      method="POST" 
                                      class="flex-1 delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-red-600 to-red-700 dark:from-red-500 dark:to-red-600 hover:from-red-700 hover:to-red-800 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        حذف
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="max-w-md mx-auto">
                    <svg class="w-20 h-20 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">لا توجد باقات متاحة</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">ابدأ بإنشاء باقة جديدة لإدارة الاشتراكات</p>
                    <button onclick="openCreateModal()" 
                            class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary-600 to-primary-700 dark:from-primary-500 dark:to-primary-600 hover:from-primary-700 hover:to-primary-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        إنشاء باقة جديدة
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Create Subscription Modal -->
<div id="createSubscriptionModal" class="fixed inset-0 bg-black/75 dark:bg-black/80 backdrop-blur-sm hidden overflow-y-auto h-full w-full z-50" style="display: none;">
    <div class="relative mx-auto p-4 w-full max-w-3xl my-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-primary-600 to-secondary-600 dark:from-primary-700 dark:to-secondary-700 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm ml-4">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white">إنشاء باقة جديدة</h3>
                            <p class="text-sm text-white/80 mt-1">املأ البيانات التالية لإنشاء باقة جديدة</p>
                        </div>
                    </div>
                    <button onclick="closeCreateModal()" class="text-white hover:text-gray-200 transition-colors p-2 rounded-lg hover:bg-white/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 max-h-[calc(100vh-200px)] overflow-y-auto">
                <form id="createSubscriptionForm" action="{{ route('admin.subscriptions.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="md:col-span-2">
                            <label for="modal_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                اسم الباقة <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="name" 
                                id="modal_name" 
                                value="{{ old('name') }}"
                                required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100"
                                placeholder="مثال: الباقة الأساسية"
                            >
                            <div id="name_error" class="mt-1 text-sm text-red-600 hidden"></div>
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="modal_description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                وصف الباقة
                            </label>
                            <textarea 
                                name="description" 
                                id="modal_description" 
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100"
                                placeholder="وصف مختصر للباقة..."
                            >{{ old('description') }}</textarea>
                            <div id="description_error" class="mt-1 text-sm text-red-600 hidden"></div>
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="modal_price" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                السعر (ر.س) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input 
                                    type="number" 
                                    name="price" 
                                    id="modal_price" 
                                    step="0.01"
                                    min="0"
                                    value="{{ old('price') }}"
                                    required
                                    class="w-full px-4 py-3 pr-16 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100"
                                    placeholder="0.00"
                                >
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 font-medium">ر.س</span>
                            </div>
                            <div id="price_error" class="mt-1 text-sm text-red-600 hidden"></div>
                        </div>

                        <!-- Duration Type -->
                        <div>
                            <label for="modal_duration_type" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                مدة الاشتراك <span class="text-red-500">*</span>
                            </label>
                            <select 
                                name="duration_type" 
                                id="modal_duration_type" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100"
                            >
                                <option value="">اختر المدة</option>
                                <option value="month" {{ old('duration_type') === 'month' ? 'selected' : '' }}>شهري</option>
                                <option value="year" {{ old('duration_type') === 'year' ? 'selected' : '' }}>سنوي</option>
                                <option value="lifetime" {{ old('duration_type') === 'lifetime' ? 'selected' : '' }}>دائم</option>
                            </select>
                            <div id="duration_type_error" class="mt-1 text-sm text-red-600 hidden"></div>
                        </div>

                        <!-- Max Debtors -->
                        <div>
                            <label for="modal_max_debtors" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                عدد المديونين <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="number" 
                                name="max_debtors" 
                                id="modal_max_debtors" 
                                min="0"
                                value="{{ old('max_debtors', 0) }}"
                                required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100"
                                placeholder="0"
                            >
                            <div id="max_debtors_error" class="mt-1 text-sm text-red-600 hidden"></div>
                        </div>

                        <!-- Max Messages -->
                        <div>
                            <label for="modal_max_messages" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                عدد الرسائل <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="number" 
                                name="max_messages" 
                                id="modal_max_messages" 
                                min="0"
                                value="{{ old('max_messages', 0) }}"
                                required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100"
                                placeholder="0"
                            >
                            <div id="max_messages_error" class="mt-1 text-sm text-red-600 hidden"></div>
                        </div>

                        <!-- AI Enabled -->
                        <div class="md:col-span-2">
                            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                                <label class="flex items-center cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="ai_enabled" 
                                        id="modal_ai_enabled"
                                        value="1"
                                        {{ old('ai_enabled') ? 'checked' : '' }}
                                        class="w-5 h-5 rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                    >
                                    <div class="mr-3">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">إمكانية استخدام الذكاء الاصطناعي</span>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">تمكين الميزات الذكية للباقة</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Is Active -->
                        <div class="md:col-span-2">
                            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                                <label class="flex items-center cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        name="is_active" 
                                        id="modal_is_active"
                                        value="1"
                                        {{ old('is_active', true) ? 'checked' : '' }}
                                        class="w-5 h-5 rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                    >
                                    <div class="mr-3">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">الباقة نشطة</span>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">إذا لم يتم تحديد هذا الخيار، ستكون الباقة غير نشطة ولن تظهر للمالكين</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end space-x-3 space-x-reverse">
                <button 
                    onclick="closeCreateModal()" 
                    class="px-6 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-200 font-medium"
                >
                    إلغاء
                </button>
                <button 
                    type="button"
                    onclick="submitCreateForm()" 
                    class="px-6 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 dark:from-primary-500 dark:to-primary-600 hover:from-primary-700 hover:to-primary-800 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg flex items-center"
                >
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    إنشاء الباقة
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Open Create Modal
    function openCreateModal() {
        const modal = document.getElementById('createSubscriptionModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            // Focus on first input
            setTimeout(() => {
                document.getElementById('modal_name')?.focus();
            }, 100);
        }
    }
    
    // Close Create Modal
    function closeCreateModal() {
        const modal = document.getElementById('createSubscriptionModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            // Reset form
            document.getElementById('createSubscriptionForm')?.reset();
            // Clear errors
            document.querySelectorAll('[id$="_error"]').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });
        }
    }
    
    // Close modal on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeCreateModal();
        }
    });
    
    // Close modal on background click
    document.getElementById('createSubscriptionModal')?.addEventListener('click', function(event) {
        if (event.target === this) {
            closeCreateModal();
        }
    });
    
    // Submit Create Form
    function submitCreateForm() {
        const form = document.getElementById('createSubscriptionForm');
        if (!form) return;
        
        const formData = new FormData(form);
        const submitButton = document.querySelector('button[onclick="submitCreateForm()"]');
        const originalText = submitButton?.innerHTML;
        
        // Disable button and show loading
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = '<svg class="animate-spin w-5 h-5 ml-2 inline-block" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> جاري الإنشاء...';
        }
        
        // Clear previous errors
        document.querySelectorAll('[id$="_error"]').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
        
        // Remove error styling from inputs
        form.querySelectorAll('input, select, textarea').forEach(input => {
            input.classList.remove('border-red-500', 'ring-red-500');
            input.classList.add('border-gray-300', 'dark:border-gray-600');
        });
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            return response.json().then(data => {
                if (response.ok) {
                    return { success: true, ...data };
                }
                return { success: false, ...data };
            }).catch(() => {
                if (response.ok) {
                    return { success: true, message: 'تم إنشاء الباقة بنجاح' };
                }
                return { success: false, message: 'حدث خطأ أثناء إنشاء الباقة' };
            });
        })
        .then(data => {
            if (data.success) {
                swalSuccess(data.message || 'تم إنشاء الباقة بنجاح').then(() => {
                    window.location.reload();
                });
            } else {
                // Show validation errors
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        // Map field names from backend to modal field IDs
                        const fieldMap = {
                            'name': 'modal_name',
                            'description': 'modal_description',
                            'price': 'modal_price',
                            'duration_type': 'modal_duration_type',
                            'max_debtors': 'modal_max_debtors',
                            'max_messages': 'modal_max_messages',
                            'ai_enabled': 'modal_ai_enabled',
                            'is_active': 'modal_is_active'
                        };
                        
                        const fieldId = fieldMap[field] || 'modal_' + field;
                        const errorElementId = field + '_error';
                        const errorElement = document.getElementById(errorElementId);
                        const inputElement = document.getElementById(fieldId);
                        
                        if (errorElement) {
                            errorElement.textContent = Array.isArray(data.errors[field]) ? data.errors[field][0] : data.errors[field];
                            errorElement.classList.remove('hidden');
                        }
                        
                        if (inputElement) {
                            inputElement.classList.add('border-red-500', 'ring-2', 'ring-red-500');
                            inputElement.classList.remove('border-gray-300', 'dark:border-gray-600');
                        }
                    });
                    
                    // Scroll to first error
                    const firstError = form.querySelector('[id$="_error"]:not(.hidden)');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                } else {
                    swalError(data.message || 'حدث خطأ أثناء إنشاء الباقة');
                }
                
                // Re-enable button
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            swalError('حدث خطأ أثناء إنشاء الباقة');
            
            // Re-enable button
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        });
    }
</script>
@endpush
@endsection

