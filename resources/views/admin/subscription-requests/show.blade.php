@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">تفاصيل طلب الاشتراك</h1>
            <a href="{{ route('admin.subscription-requests.index') }}" 
               class="mt-2 inline-block text-sm text-blue-600 hover:text-blue-900">
                ← العودة إلى قائمة الطلبات
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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Request Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">معلومات الطلب</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">المالك</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $request->user->email }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">الباقة</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->subscription->name }}</p>
                            <p class="text-xs text-gray-500">{{ number_format($request->subscription->price, 2) }} ر.س</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">الحالة</label>
                            <p class="mt-1">
                                @if($request->status === 'pending')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">معلق</span>
                                @elseif($request->status === 'approved')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">موافق عليه</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">مرفوض</span>
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">تاريخ الطلب</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->created_at->format('Y-m-d H:i') }}</p>
                        </div>

                        @if($request->admin_notes)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">ملاحظات الإدارة</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $request->admin_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payment Proof -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">صورة الدفع</h2>
                    
                    @if($request->payment_proof)
                        <div class="mb-4">
                            <img src="{{ asset('storage/' . $request->payment_proof) }}" 
                                 alt="Payment Proof" 
                                 class="w-full rounded-lg shadow-md">
                        </div>
                        <a href="{{ asset('storage/' . $request->payment_proof) }}" 
                           target="_blank"
                           class="inline-block text-sm text-blue-600 hover:text-blue-900">
                            فتح الصورة في نافذة جديدة
                        </a>
                    @else
                        <p class="text-gray-500">لا توجد صورة دفع</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions (Only for pending requests) -->
        @if($request->status === 'pending')
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">إجراءات</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Approve Form -->
                        <form action="{{ route('admin.subscription-requests.approve', $request) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="approve_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    ملاحظات (اختياري)
                                </label>
                                <textarea name="admin_notes" 
                                          id="approve_notes" 
                                          rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                            <button type="submit" 
                                    class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                                ✓ قبول الطلب
                            </button>
                        </form>

                        <!-- Reject Form -->
                        <form action="{{ route('admin.subscription-requests.reject', $request) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="reject_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    سبب الرفض <span class="text-red-500">*</span>
                                </label>
                                <textarea name="admin_notes" 
                                          id="reject_notes" 
                                          rows="3"
                                          required
                                          minlength="10"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                            <button type="submit" 
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-200">
                                ✗ رفض الطلب
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

