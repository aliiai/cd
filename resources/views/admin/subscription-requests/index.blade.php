@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-primary-50 to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12">
    <div class="w-full mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">طلبات الاشتراك</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400">مراجعة وإدارة طلبات الاشتراك من المالكين</p>
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

        <!-- Requests Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6">
                @if($requests->count() > 0)
                    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 shadow-lg">
                        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gradient-to-r from-primary-600 to-primary-600 dark:from-primary-700 dark:to-primary-800">
                                <tr>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">المالك</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">الباقة</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">صورة الدفع</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">الحالة</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-white uppercase tracking-wider">التاريخ</th>
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($requests as $request)
                                    <tr class="hover:bg-primary-50 dark:hover:bg-gray-700/50 transition-all duration-200 hover:shadow-md">
                                        <!-- Owner -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white font-bold text-sm ml-3">
                                                    {{ strtoupper(substr($request->user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $request->user->name }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $request->user->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <!-- Subscription -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-gradient-to-br from-secondary-400 to-secondary-600 rounded-lg flex items-center justify-center ml-3">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $request->subscription->name }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($request->subscription->price, 2) }} ر.س</p>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <!-- Payment Proof -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($request->payment_proof)
                                                <button onclick="openPaymentModal('{{ asset('storage/' . $request->payment_proof) }}', '{{ $request->user->name }}')" 
                                                        class="inline-flex items-center px-3 py-1.5 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 rounded-lg hover:bg-primary-200 dark:hover:bg-primary-900/50 transition-all duration-200 shadow-sm hover:shadow-md group">
                                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    عرض الصورة
                                                </button>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500">-</span>
                                            @endif
                                        </td>
                                        
                                        <!-- Status -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($request->status === 'pending')
                                                <span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">
                                                    <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    معلق
                                                </span>
                                            @elseif($request->status === 'approved')
                                                <span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                                    <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    موافق عليه
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-full shadow-sm bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                                    <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    مرفوض
                                                </span>
                                            @endif
                                        </td>
                                        
                                        <!-- Date -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 ml-2 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ $request->created_at->format('Y-m-d') }}
                                                <span class="text-xs text-gray-400 dark:text-gray-500 mr-2">{{ $request->created_at->format('H:i') }}</span>
                                            </div>
                                        </td>
                                        
                                        <!-- Actions -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center justify-center space-x-2 space-x-reverse gap-2">
                                                @if($request->status === 'pending')
                                                    <a href="{{ route('admin.subscription-requests.show', $request) }}" 
                                                       class="inline-flex items-center justify-center w-10 h-10 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 rounded-lg hover:bg-primary-200 dark:hover:bg-primary-900/50 transition-all duration-200 shadow-sm hover:shadow-md group"
                                                       title="عرض التفاصيل">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                    </a>
                                                @else
                                                    <span class="text-gray-400 dark:text-gray-500">-</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if(method_exists($requests, 'links'))
                        <div class="mt-6">
                            {{ $requests->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-16">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                            <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-lg font-medium">لا توجد طلبات اشتراك حالياً.</p>
                    </div>
                @endif
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
</script>
@endpush
@endsection
