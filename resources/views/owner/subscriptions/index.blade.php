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

        <!-- My Requests -->
        @if($userRequests->count() > 0)
            <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">طلباتي</h2>
                    <div class="space-y-3">
                        @foreach($userRequests as $userRequest)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $userRequest->subscription->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $userRequest->created_at->format('Y-m-d H:i') }}</p>
                                </div>
                                <div>
                                    @if($userRequest->status === 'pending')
                                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">قيد المراجعة</span>
                                    @elseif($userRequest->status === 'approved')
                                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">موافق عليه</span>
                                    @else
                                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">مرفوض</span>
                                        @if($userRequest->admin_notes)
                                            <p class="mt-1 text-xs text-gray-600">السبب: {{ $userRequest->admin_notes }}</p>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Subscriptions Grid -->
        @if($subscriptions->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($subscriptions as $subscription)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-200">
                        <div class="p-6">
                            <!-- Subscription Header -->
                            <div class="mb-4">
                                <h3 class="text-xl font-bold text-gray-900">{{ $subscription->name }}</h3>
                                <p class="text-3xl font-bold text-blue-600 mt-2">
                                    {{ number_format($subscription->price, 2) }} <span class="text-lg text-gray-600">ر.س</span>
                                </p>
                            </div>

                            <!-- Description -->
                            @if($subscription->description)
                                <p class="text-sm text-gray-600 mb-4">{{ $subscription->description }}</p>
                            @endif

                            <!-- Features -->
                            <div class="space-y-2 mb-6">
                                <div class="flex items-center text-sm text-gray-700">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    عدد المديونين: {{ $subscription->max_debtors }}
                                </div>
                                <div class="flex items-center text-sm text-gray-700">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    عدد الرسائل: {{ $subscription->max_messages }}
                                </div>
                                <div class="flex items-center text-sm text-gray-700">
                                    @if($subscription->ai_enabled)
                                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-green-600">الذكاء الاصطناعي متاح</span>
                                    @else
                                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        <span class="text-gray-500">الذكاء الاصطناعي غير متاح</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Subscribe Button -->
                            @php
                                $hasPendingRequest = $userRequests->where('subscription_id', $subscription->id)
                                    ->where('status', 'pending')
                                    ->count() > 0;
                            @endphp
                            
                            @if($hasPendingRequest)
                                <button disabled 
                                        class="w-full bg-gray-400 text-white font-bold py-2 px-4 rounded-lg cursor-not-allowed">
                                    طلب معلق
                                </button>
                            @else
                                <button onclick="openSubscriptionModal({{ $subscription->id }}, '{{ $subscription->name }}', {{ $subscription->price }})" 
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                                    تفعيل الاشتراك
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-lg">
                <p class="text-gray-500 text-lg">لا توجد باقات متاحة حالياً.</p>
            </div>
        @endif
    </div>
</div>

<!-- Subscription Modal -->
<div id="subscriptionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
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
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="font-semibold text-gray-900 mb-2">معلومات الحساب البنكي</h4>
                <div class="space-y-2 text-sm text-gray-700">
                    <p><strong>اسم البنك:</strong> البنك الأهلي السعودي</p>
                    <p><strong>رقم الحساب:</strong> SA1234567890123456789012</p>
                    <p><strong>اسم المستفيد:</strong> شركة XYZ</p>
                    <p><strong>IBAN:</strong> SA1234567890123456789012</p>
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
    function openSubscriptionModal(subscriptionId, subscriptionName, subscriptionPrice) {
        document.getElementById('modalSubscriptionId').value = subscriptionId;
        document.getElementById('modalSubscriptionName').textContent = 'تفعيل الاشتراك - ' + subscriptionName;
        document.getElementById('subscriptionModal').classList.remove('hidden');
    }

    // Close Modal
    function closeSubscriptionModal() {
        document.getElementById('subscriptionModal').classList.add('hidden');
        document.getElementById('subscriptionForm').reset();
        document.getElementById('imagePreview').classList.add('hidden');
    }

    // Image Preview
    document.getElementById('payment_proof').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            document.getElementById('imagePreview').classList.add('hidden');
        }
    });

    // Close modal when clicking outside
    document.getElementById('subscriptionModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeSubscriptionModal();
        }
    });
</script>
@endsection

