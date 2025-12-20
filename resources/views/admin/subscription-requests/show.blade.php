@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-primary-50 to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12">
    <div class="w-full mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.subscription-requests.index') }}" 
               class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 text-sm mb-4 transition-colors">
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                العودة إلى قائمة الطلبات
            </a>
            <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">تفاصيل طلب الاشتراك</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400">مراجعة تفاصيل طلب الاشتراك واتخاذ الإجراء المناسب</p>
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Request Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Request Info Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-primary-600 to-primary-700 dark:from-primary-700 dark:to-primary-800 p-6">
                        <div class="flex items-center justify-between">
                            <h2 class="text-2xl font-bold text-white">معلومات الطلب</h2>
                            @if($request->status === 'pending')
                                <span class="px-4 py-2 text-sm font-bold rounded-full bg-yellow-500 dark:bg-yellow-600 text-white flex items-center">
                                    <span class="w-2 h-2 bg-white rounded-full ml-2 animate-pulse"></span>
                                    معلق
                                </span>
                            @elseif($request->status === 'approved')
                                <span class="px-4 py-2 text-sm font-bold rounded-full bg-green-500 dark:bg-green-600 text-white flex items-center">
                                    <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    موافق عليه
                                </span>
                            @else
                                <span class="px-4 py-2 text-sm font-bold rounded-full bg-red-500 dark:bg-red-600 text-white flex items-center">
                                    <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    مرفوض
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Owner Info -->
                            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center mb-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white font-bold text-lg ml-3">
                                        {{ strtoupper(substr($request->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">المالك</label>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $request->user->name }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $request->user->email }}
                                </div>
                            </div>

                            <!-- Subscription Info -->
                            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center mb-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-secondary-400 to-secondary-600 rounded-lg flex items-center justify-center ml-3">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">الباقة</label>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $request->subscription->name }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">السعر:</span>
                                    <span class="text-lg font-bold text-primary-600 dark:text-primary-400">{{ number_format($request->subscription->price, 2) }} ر.س</span>
                                </div>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">المدة:</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $request->subscription->duration_text }}</span>
                                </div>
                            </div>

                            <!-- Request Date -->
                            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">تاريخ الطلب</label>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $request->created_at->format('Y-m-d') }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $request->created_at->format('H:i') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Request Number -->
                            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">رقم الطلب</label>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                    </svg>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">#{{ $request->id }}</p>
                                </div>
                            </div>
                        </div>

                        @if($request->admin_notes)
                            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                                <label class="block text-sm font-medium text-blue-800 dark:text-blue-300 mb-2">ملاحظات الإدارة</label>
                                <p class="text-sm text-blue-900 dark:text-blue-200">{{ $request->admin_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions (Only for pending requests) -->
                @if($request->status === 'pending')
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="bg-gradient-to-r from-primary-600 to-primary-700 dark:from-primary-700 dark:to-primary-800 p-6">
                            <h2 class="text-2xl font-bold text-white">إجراءات الطلب</h2>
                        </div>
                        
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Approve Form -->
                                <form action="{{ route('admin.subscription-requests.approve', $request) }}" method="POST" class="approve-form">
                                    @csrf
                                    <div class="p-6 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                                        <div class="flex items-center mb-4">
                                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center ml-3">
                                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-bold text-green-800 dark:text-green-300">قبول الطلب</h3>
                                        </div>
                                        <div class="mb-4">
                                            <label for="approve_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                ملاحظات (اختياري)
                                            </label>
                                            <textarea name="admin_notes" 
                                                      id="approve_notes" 
                                                      rows="4"
                                                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-gray-100 dark:placeholder-gray-400"></textarea>
                                        </div>
                                        <button type="submit" 
                                                class="w-full bg-gradient-to-r from-green-600 to-green-700 dark:from-green-500 dark:to-green-600 hover:from-green-700 hover:to-green-800 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center">
                                            <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            قبول الطلب
                                        </button>
                                    </div>
                                </form>

                                <!-- Reject Form -->
                                <form action="{{ route('admin.subscription-requests.reject', $request) }}" method="POST" class="reject-form">
                                    @csrf
                                    <div class="p-6 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                                        <div class="flex items-center mb-4">
                                            <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center ml-3">
                                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-bold text-red-800 dark:text-red-300">رفض الطلب</h3>
                                        </div>
                                        <div class="mb-4">
                                            <label for="reject_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                سبب الرفض <span class="text-red-500">*</span>
                                            </label>
                                            <textarea name="admin_notes" 
                                                      id="reject_notes" 
                                                      rows="4"
                                                      required
                                                      minlength="10"
                                                      placeholder="يرجى كتابة سبب الرفض..."
                                                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-gray-100 dark:placeholder-gray-400"></textarea>
                                        </div>
                                        <button type="submit" 
                                                class="w-full bg-gradient-to-r from-red-600 to-red-700 dark:from-red-500 dark:to-red-600 hover:from-red-700 hover:to-red-800 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center">
                                            <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            رفض الطلب
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column - Payment Proof -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden sticky top-6">
                    <div class="bg-gradient-to-r from-secondary-600 to-secondary-700 dark:from-secondary-700 dark:to-secondary-800 p-6">
                        <h2 class="text-2xl font-bold text-white">إيصال الدفع</h2>
                    </div>
                    
                    <div class="p-6">
                        @if($request->payment_proof)
                            <div class="mb-4">
                                <div class="relative group cursor-pointer" onclick="openPaymentModal('{{ asset('storage/' . $request->payment_proof) }}', '{{ $request->user->name }}')">
                                    <div class="bg-gray-100 dark:bg-gray-900 rounded-lg p-4 flex items-center justify-center overflow-hidden" style="min-height: 300px; max-height: 500px;">
                                        <img src="{{ asset('storage/' . $request->payment_proof) }}" 
                                             alt="إيصال الدفع" 
                                             class="max-w-full max-h-full object-contain rounded-lg shadow-md group-hover:shadow-xl transition-shadow duration-200">
                                    </div>
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 rounded-lg transition-all duration-200 flex items-center justify-center">
                                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 bg-white dark:bg-gray-800 rounded-full p-3 shadow-lg">
                                            <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex space-x-2 space-x-reverse">
                                <button onclick="openPaymentModal('{{ asset('storage/' . $request->payment_proof) }}', '{{ $request->user->name }}')" 
                                        class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                    </svg>
                                    عرض كامل
                                </button>
                                <a href="{{ asset('storage/' . $request->payment_proof) }}" 
                                   download
                                   class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    تحميل
                                </a>
                            </div>
                        @else
                            <div class="text-center py-16">
                                <svg class="w-20 h-20 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">لا توجد صورة دفع</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Proof Modal -->
<div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-75 hidden overflow-y-auto h-full w-full z-50" style="display: none; align-items: center; justify-content: center;">
    <div class="relative mx-auto p-6 w-full max-w-4xl m-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 bg-gradient-to-r from-primary-600 to-primary-700 dark:from-primary-700 dark:to-primary-800">
                <h3 class="text-xl font-bold text-white" id="modalOwnerName">إيصال الدفع</h3>
                <button onclick="closePaymentModal()" class="text-white hover:text-gray-200 transition-colors p-2 rounded-lg hover:bg-white/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <div class="flex items-center justify-center bg-gray-50 dark:bg-gray-900 rounded-lg p-4 mb-4" style="min-height: 500px; max-height: 70vh;">
                    <img id="paymentImage" src="" alt="إيصال الدفع" class="max-w-full max-h-full object-contain rounded-lg shadow-lg">
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-3 space-x-reverse pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a id="downloadLink" href="" download class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        تحميل الصورة
                    </a>
                    <button onclick="closePaymentModal()" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-semibold rounded-lg transition-all duration-200">
                        إغلاق
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Open Payment Modal
    function openPaymentModal(imageUrl, ownerName) {
        const modal = document.getElementById('paymentModal');
        const paymentImage = document.getElementById('paymentImage');
        const downloadLink = document.getElementById('downloadLink');
        const modalOwnerName = document.getElementById('modalOwnerName');
        
        if (modal && paymentImage && downloadLink) {
            paymentImage.src = imageUrl;
            downloadLink.href = imageUrl;
            modalOwnerName.textContent = 'إيصال الدفع - ' + ownerName;
            
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }
    
    // Close Payment Modal
    function closePaymentModal() {
        const modal = document.getElementById('paymentModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }
    
    // Close modal on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closePaymentModal();
        }
    });
    
    // Close modal on background click
    document.getElementById('paymentModal')?.addEventListener('click', function(event) {
        if (event.target === this) {
            closePaymentModal();
        }
    });
    
    // Handle form submissions with Sweet Alert
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (form.classList.contains('approve-form') || form.classList.contains('reject-form')) {
            e.preventDefault();
            
            const isApprove = form.classList.contains('approve-form');
            const actionText = isApprove ? 'قبول' : 'رفض';
            const actionColor = isApprove ? '#10B981' : '#EF4444';
            const actionIcon = isApprove ? 'success' : 'warning';
            
            swalConfirm({
                text: `هل أنت متأكد من ${actionText} هذا الطلب؟`,
                icon: actionIcon,
                confirmButtonColor: actionColor,
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData(form);
                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            return response.json().catch(() => ({ success: true }));
                        }
                        return response.json().then(data => ({ success: false, message: data.message || 'حدث خطأ' }));
                    })
                    .then(data => {
                        if (data.success) {
                            swalSuccess(data.message || `تم ${actionText} الطلب بنجاح`).then(() => {
                                window.location.reload();
                            });
                        } else {
                            swalError(data.message || 'حدث خطأ أثناء تنفيذ العملية');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        swalError('حدث خطأ أثناء تنفيذ العملية');
                    });
                }
            });
        }
    });
</script>
@endpush
@endsection
