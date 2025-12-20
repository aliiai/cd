@extends('layouts.owner')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-primary-50 to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- ========== Header Section ========== --}}
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">الباقات المتاحة</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400">اختر الباقة المناسبة لك واشترك فيها</p>
        </div>

        {{-- ========== Success/Error Messages ========== --}}
        @if(session('success'))
            <div class="mb-6 bg-gradient-to-r from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 px-6 py-4 rounded-xl shadow-md flex items-center">
                <svg class="w-5 h-5 ml-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-6 py-4 rounded-xl shadow-md flex items-center">
                <svg class="w-5 h-5 ml-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- ========== Pending Request Alert ========== --}}
        @if($pendingRequest)
            <div class="mb-8 bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 border-r-4 border-yellow-500 dark:border-yellow-400 p-6 rounded-xl shadow-md">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="mr-3 flex-1">
                        <h3 class="text-sm font-semibold text-yellow-800 dark:text-yellow-200 mb-1">طلب اشتراك قيد المراجعة</h3>
                        <p class="text-sm text-yellow-700 dark:text-yellow-300">
                            لديك طلب اشتراك قيد المراجعة للباقة: <strong>{{ $pendingRequest->subscription->name }}</strong>
                        </p>
                        <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-2">سيتم مراجعة طلبك من قبل الإدارة قريباً</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- ========== Subscriptions Grid ========== --}}
        @if($subscriptions->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($subscriptions as $subscription)
                    @php
                        // التحقق من أن هذه الباقة هي المشترك بها حالياً
                        $isCurrentSubscription = $activeSubscription && $activeSubscription->subscription_id === $subscription->id;
                        // التحقق من وجود طلب معلق لهذه الباقة
                        $hasPendingRequest = $pendingRequest && $pendingRequest->subscription_id === $subscription->id;
                    @endphp
                    
                    <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden {{ $isCurrentSubscription ? 'ring-2 ring-emerald-500 dark:ring-emerald-400 border-2 border-emerald-500 dark:border-emerald-400' : '' }} {{ $hasPendingRequest ? 'ring-2 ring-yellow-500 dark:ring-yellow-400 border-2 border-yellow-500 dark:border-yellow-400' : '' }}">
                        
                        {{-- Status Badge - على اليسار --}}
                        @if($isCurrentSubscription)
                            <div class="absolute top-4 left-4 z-10">
                                <span class="px-3 py-1.5 text-xs font-bold rounded-full bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-lg flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    نشط
                                </span>
                            </div>
                        @elseif($hasPendingRequest)
                            <div class="absolute top-4 left-4 z-10">
                                <span class="px-3 py-1.5 text-xs font-bold rounded-full bg-gradient-to-r from-yellow-500 to-yellow-600 text-white shadow-lg flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    قيد المراجعة
                                </span>
                            </div>
                        @endif

                        <div class="p-6">
                            {{-- Subscription Header --}}
                            <div class="mb-6">
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ $subscription->name }}</h3>
                                <div class="flex items-baseline">
                                    <span class="text-4xl font-bold text-primary-600 dark:text-primary-400">
                                        {{ number_format($subscription->price, 2) }}
                                    </span>
                                    <span class="text-lg text-gray-600 dark:text-gray-400 mr-2">ر.س</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-500 mr-2">/ {{ $subscription->duration_text }}</span>
                                </div>
                            </div>

                            {{-- Description --}}
                            @if($subscription->description)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">{{ $subscription->description }}</p>
                            @endif

                            {{-- Features --}}
                            <div class="space-y-3 mb-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-emerald-500 dark:text-emerald-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <div>
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">عدد المديونين:</span>
                                        <span class="text-sm text-gray-700 dark:text-gray-300 mr-2">{{ number_format($subscription->max_debtors) }}</span>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-emerald-500 dark:text-emerald-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <div>
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">عدد الرسائل:</span>
                                        <span class="text-sm text-gray-700 dark:text-gray-300 mr-2">{{ number_format($subscription->max_messages) }}</span>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    @if($subscription->ai_enabled)
                                        <svg class="w-5 h-5 text-emerald-500 dark:text-emerald-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-emerald-600 dark:text-emerald-400">الذكاء الاصطناعي متاح</span>
                                    @else
                                        <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">الذكاء الاصطناعي غير متاح</span>
                                    @endif
                                </div>

                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-primary-500 dark:text-primary-400 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">المدة: {{ $subscription->duration_text }}</span>
                                </div>
                            </div>

                            {{-- Subscribe Button --}}
                            @if($isCurrentSubscription)
                                <button disabled 
                                        class="w-full bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-bold py-3 px-4 rounded-lg cursor-not-allowed opacity-75 shadow-md">
                                    <span class="flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        مشترك حالياً
                                    </span>
                                </button>
                            @elseif($hasPendingRequest)
                                <button disabled 
                                        class="w-full bg-gradient-to-r from-yellow-500 to-yellow-600 text-white font-bold py-3 px-4 rounded-lg cursor-not-allowed opacity-75 shadow-md">
                                    <span class="flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        قيد المراجعة
                                    </span>
                                </button>
                            @else
                                <button onclick="openSubscriptionModal({{ $subscription->id }}, '{{ $subscription->name }}', {{ $subscription->price }}, '{{ $subscription->duration_text }}')" 
                                        class="w-full bg-gradient-to-r from-primary-500 to-secondary-500 hover:from-primary-600 hover:to-secondary-600 text-white font-bold py-3 px-4 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                    تفعيل الاشتراك
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">لا توجد باقات متاحة حالياً.</p>
            </div>
        @endif
    </div>
</div>

{{-- ========== Subscription Modal ========== --}}
<div id="subscriptionModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden overflow-y-auto h-full w-full z-50" style="display: none; align-items: flex-start; justify-content: center;">
    <div class="relative top-20 mx-auto p-6 border w-full max-w-lg shadow-2xl rounded-2xl bg-white dark:bg-gray-800 m-4">
        <div class="mt-3">
            {{-- Modal Header --}}
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100" id="modalSubscriptionName">تفعيل الاشتراك</h3>
                <button onclick="closeSubscriptionModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            {{-- Bank Account Info --}}
            <div class="mb-6 p-5 bg-gradient-to-br from-primary-50 to-secondary-50 dark:from-primary-900/20 dark:to-secondary-900/20 rounded-xl border border-primary-200 dark:border-primary-800">
                <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                    <svg class="w-5 h-5 text-primary-600 dark:text-primary-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    معلومات الحساب البنكي
                </h4>
                <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                    <p><strong class="text-gray-900 dark:text-gray-100">اسم البنك:</strong> البنك الأهلي السعودي</p>
                    <p><strong class="text-gray-900 dark:text-gray-100">رقم الحساب:</strong> SA1234567890123456789012</p>
                    <p><strong class="text-gray-900 dark:text-gray-100">اسم المستفيد:</strong> شركة XYZ</p>
                    <p><strong class="text-gray-900 dark:text-gray-100">IBAN:</strong> SA1234567890123456789012</p>
                </div>
            </div>

            {{-- Form --}}
            <form id="subscriptionForm" action="{{ route('owner.subscriptions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="subscription_id" id="modalSubscriptionId">

                {{-- Payment Proof Upload --}}
                <div class="mb-6">
                    <label for="payment_proof" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        صورة الدفع <span class="text-red-500">*</span>
                    </label>
                    <input type="file" 
                           name="payment_proof" 
                           id="payment_proof" 
                           accept="image/*"
                           required
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                    @error('payment_proof')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Preview Image --}}
                <div id="imagePreview" class="mb-6 hidden">
                    <img id="previewImg" src="" alt="Preview" class="w-full rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                </div>

                {{-- Buttons --}}
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" 
                            onclick="closeSubscriptionModal()"
                            class="px-6 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        إلغاء
                    </button>
                    <button type="submit" 
                            class="px-6 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-primary-500 to-secondary-500 hover:from-primary-600 hover:to-secondary-600 transition-all duration-200 transform hover:-translate-y-0.5">
                        إرسال الطلب
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Open Modal
    function openSubscriptionModal(subscriptionId, subscriptionName, subscriptionPrice, durationText) {
        const modal = document.getElementById('subscriptionModal');
        const modalSubscriptionId = document.getElementById('modalSubscriptionId');
        const modalSubscriptionName = document.getElementById('modalSubscriptionName');
        
        if (modal && modalSubscriptionId && modalSubscriptionName) {
            modalSubscriptionId.value = subscriptionId;
            modalSubscriptionName.textContent = 'تفعيل الاشتراك - ' + subscriptionName;
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            // منع التمرير في الخلفية
            document.body.style.overflow = 'hidden';
        }
    }

    // Close Modal
    function closeSubscriptionModal() {
        const modal = document.getElementById('subscriptionModal');
        const form = document.getElementById('subscriptionForm');
        const imagePreview = document.getElementById('imagePreview');
        
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
            // إعادة التمرير
            document.body.style.overflow = 'auto';
        }
        
        if (form) {
            form.reset();
        }
        
        if (imagePreview) {
            imagePreview.classList.add('hidden');
        }
    }

    // Image Preview
    document.addEventListener('DOMContentLoaded', function() {
        const paymentProofInput = document.getElementById('payment_proof');
        if (paymentProofInput) {
            paymentProofInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewImg = document.getElementById('previewImg');
                        const imagePreview = document.getElementById('imagePreview');
                        if (previewImg && imagePreview) {
                            previewImg.src = e.target.result;
                            imagePreview.classList.remove('hidden');
                        }
                    }
                    reader.readAsDataURL(file);
                } else {
                    const imagePreview = document.getElementById('imagePreview');
                    if (imagePreview) {
                        imagePreview.classList.add('hidden');
                    }
                }
            });
        }

        // Close modal when clicking outside
        const subscriptionModal = document.getElementById('subscriptionModal');
        if (subscriptionModal) {
            subscriptionModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeSubscriptionModal();
                }
            });
        }
    });
</script>
@endsection
