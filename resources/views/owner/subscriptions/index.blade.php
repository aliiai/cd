@extends('layouts.owner')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">الباقات المتاحة</h1>
            <p class="mt-2 text-sm text-gray-600">اختر الباقة المناسبة لك واشترك فيها</p>
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

        <!-- Pending Request Alert -->
        @if($pendingRequest)
            <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-yellow-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-yellow-800">
                            لديك طلب اشتراك قيد المراجعة للباقة: <strong>{{ $pendingRequest->subscription->name }}</strong>
                        </p>
                        <p class="text-xs text-yellow-600 mt-1">سيتم مراجعة طلبك من قبل الإدارة قريباً</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Subscriptions Grid -->
        @if($subscriptions->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($subscriptions as $subscription)
                    @php
                        // التحقق من أن هذه الباقة هي المشترك بها حالياً
                        $isCurrentSubscription = $activeSubscription && $activeSubscription->subscription_id === $subscription->id;
                        // التحقق من وجود طلب معلق لهذه الباقة
                        $hasPendingRequest = $pendingRequest && $pendingRequest->subscription_id === $subscription->id;
                    @endphp
                    
                    <div class="relative bg-white overflow-hidden rounded-xl shadow-md hover:shadow-xl transition-all duration-300 {{ $isCurrentSubscription ? 'ring-4 ring-green-500 ring-opacity-50 border-2 border-green-500' : '' }} {{ $hasPendingRequest ? 'ring-4 ring-yellow-500 ring-opacity-50 border-2 border-yellow-500' : '' }}">
                        <!-- Status Badge -->
                        @if($isCurrentSubscription)
                            <div class="absolute top-4 right-4 z-10">
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-green-500 text-white shadow-lg">
                                    ✓ نشط
                                </span>
                            </div>
                        @elseif($hasPendingRequest)
                            <div class="absolute top-4 right-4 z-10">
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-yellow-500 text-white shadow-lg">
                                    ⏳ قيد المراجعة
                                </span>
                            </div>
                        @endif

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
                                <p class="text-sm text-gray-600 mb-6 leading-relaxed">{{ $subscription->description }}</p>
                            @endif

                            <!-- Features -->
                            <div class="space-y-3 mb-6 border-t border-gray-200 pt-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <div>
                                        <span class="text-sm font-medium text-gray-900">عدد المديونين:</span>
                                        <span class="text-sm text-gray-700 mr-2">{{ number_format($subscription->max_debtors) }}</span>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <div>
                                        <span class="text-sm font-medium text-gray-900">عدد الرسائل:</span>
                                        <span class="text-sm text-gray-700 mr-2">{{ number_format($subscription->max_messages) }}</span>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    @if($subscription->ai_enabled)
                                        <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
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
                                    <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-900">المدة: {{ $subscription->duration_text }}</span>
                                </div>
                            </div>

                            <!-- Subscribe Button -->
                            @if($isCurrentSubscription)
                                <button disabled 
                                        class="w-full bg-green-500 text-white font-bold py-3 px-4 rounded-lg cursor-not-allowed opacity-75">
                                    ✓ مشترك حالياً
                                </button>
                            @elseif($hasPendingRequest)
                                <button disabled 
                                        class="w-full bg-yellow-500 text-white font-bold py-3 px-4 rounded-lg cursor-not-allowed opacity-75">
                                    ⏳ قيد المراجعة
                                </button>
                            @else
                                <button onclick="openSubscriptionModal({{ $subscription->id }}, '{{ $subscription->name }}', {{ $subscription->price }}, '{{ $subscription->duration_text }}')" 
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                                    تفعيل الاشتراك
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-lg shadow-sm">
                <p class="text-gray-500 text-lg">لا توجد باقات متاحة حالياً.</p>
            </div>
        @endif
    </div>
</div>

<!-- Subscription Modal -->
<div id="subscriptionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50" style="display: none; align-items: flex-start; justify-content: center;">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white m-4">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900" id="modalSubscriptionName">تفعيل الاشتراك</h3>
                <button onclick="closeSubscriptionModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Bank Account Info -->
            <div class="mb-6 p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border border-blue-200">
                <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    معلومات الحساب البنكي
                </h4>
                <div class="space-y-2 text-sm text-gray-700">
                    <p><strong class="text-gray-900">اسم البنك:</strong> البنك الأهلي السعودي</p>
                    <p><strong class="text-gray-900">رقم الحساب:</strong> SA1234567890123456789012</p>
                    <p><strong class="text-gray-900">اسم المستفيد:</strong> شركة XYZ</p>
                    <p><strong class="text-gray-900">IBAN:</strong> SA1234567890123456789012</p>
                </div>
            </div>

            <!-- Form -->
            <form id="subscriptionForm" action="{{ route('owner.subscriptions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="subscription_id" id="modalSubscriptionId">

                <!-- Payment Proof Upload -->
                <div class="mb-4">
                    <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-2">
                        صورة الدفع <span class="text-red-500">*</span>
                    </label>
                    <input type="file" 
                           name="payment_proof" 
                           id="payment_proof" 
                           accept="image/*"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @error('payment_proof')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preview Image -->
                <div id="imagePreview" class="mb-4 hidden">
                    <img id="previewImg" src="" alt="Preview" class="w-full rounded-lg shadow-md">
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3 space-x-reverse mt-6">
                    <button type="button" 
                            onclick="closeSubscriptionModal()"
                            class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        إلغاء
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
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

    // Image Preview - يجب أن يكون داخل Modal أو يتم إضافته ديناميكياً
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
