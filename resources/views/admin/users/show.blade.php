@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <a href="{{ route('admin.users.index') }}" 
                       class="text-blue-600 hover:text-blue-900 text-sm mb-2 inline-block">
                        ← العودة إلى قائمة المستخدمين
                    </a>
                    <h1 class="text-3xl font-bold text-gray-900">تفاصيل المستخدم</h1>
                </div>
            </div>
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - User Info & Subscription -->
            <div class="lg:col-span-1 space-y-6">
                <!-- User Basic Info Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">البيانات الأساسية</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">الاسم</label>
                                <p class="mt-1 text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">البريد الإلكتروني</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">حالة الحساب</label>
                                <p class="mt-1">
                                    @if($user->is_active)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            نشط
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            موقوف
                                        </span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">تاريخ التسجيل</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                            <div class="pt-4 border-t border-gray-200">
                                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full px-4 py-2 text-sm font-medium text-white rounded-lg {{ $user->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} transition-colors duration-200"
                                            onclick="return confirm('هل أنت متأكد من {{ $user->is_active ? 'إيقاف' : 'تفعيل' }} هذا الحساب؟');">
                                        {{ $user->is_active ? 'إيقاف الحساب' : 'تفعيل الحساب' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subscription Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">الاشتراك الحالي</h2>
                        @if($activeSubscription)
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">اسم الباقة</label>
                                    <p class="mt-1 text-sm font-semibold text-gray-900">{{ $activeSubscription->subscription->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">حالة الاشتراك</label>
                                    <p class="mt-1">
                                        @if($activeSubscription->isActive())
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                نشط
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                منتهي
                                            </span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">تاريخ البداية</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $activeSubscription->started_at->format('Y-m-d') }}</p>
                                </div>
                                @if($activeSubscription->expires_at)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">تاريخ الانتهاء</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $activeSubscription->expires_at->format('Y-m-d') }}</p>
                                    </div>
                                @else
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">نوع الاشتراك</label>
                                        <p class="mt-1 text-sm text-gray-900">دائم</p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <p class="text-sm text-gray-500">لا يوجد اشتراك نشط</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column - Statistics & Activities -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Total Debtors -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div class="mr-4">
                                    <p class="text-sm font-medium text-gray-500">عدد المديونين</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalDebtors) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Paid Debtors -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="mr-4">
                                    <p class="text-sm font-medium text-gray-500">المدفوع</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ number_format($paidDebtors) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Overdue Debtors -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-red-100 rounded-lg p-3">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>
                                <div class="mr-4">
                                    <p class="text-sm font-medium text-gray-500">المتأخر</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ number_format($overdueDebtors) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Collection Rate -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                                <div class="mr-4">
                                    <p class="text-sm font-medium text-gray-500">نسبة التحصيل</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ number_format($collectionRate, 1) }}%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Debt Amount -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">إجمالي قيمة الديون</h2>
                        <div class="flex items-baseline">
                            <span class="text-4xl font-bold text-gray-900">{{ number_format($totalDebtAmount, 2) }}</span>
                            <span class="text-lg text-gray-600 mr-2">ر.س</span>
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">المدفوع:</span>
                                <span class="font-semibold text-green-600">{{ number_format($paidAmount, 2) }} ر.س</span>
                            </div>
                            <div class="flex justify-between text-sm mt-2">
                                <span class="text-gray-600">المتبقي:</span>
                                <span class="font-semibold text-red-600">{{ number_format($totalDebtAmount - $paidAmount, 2) }} ر.س</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Recent Clients -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">آخر المديونين</h2>
                            @if($recentClients->count() > 0)
                                <div class="space-y-3">
                                    @foreach($recentClients as $client)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $client->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $client->created_at->format('Y-m-d') }}</p>
                                            </div>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $client->status_color }}">
                                                {{ $client->status_text }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500">لا توجد مديونين</p>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Paid Clients -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">آخر التحصيلات</h2>
                            @if($recentPaidClients->count() > 0)
                                <div class="space-y-3">
                                    @foreach($recentPaidClients as $client)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $client->name }}</p>
                                                <p class="text-xs text-gray-500">{{ number_format($client->debt_amount, 2) }} ر.س</p>
                                            </div>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                مدفوع
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500">لا توجد تحصيلات</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

