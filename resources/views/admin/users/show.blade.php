@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-primary-50 to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12">
    <div class="w-full mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('admin.users.index') }}" 
               class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 text-sm mb-4 transition-colors">
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                العودة إلى قائمة المستخدمين
            </a>
            <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">تفاصيل المستخدم</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400">معلومات شاملة عن المستخدم وأنشطته</p>
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
            <!-- Left Column - User Info & Subscription -->
            <div class="lg:col-span-1 space-y-6">
                <!-- User Profile Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <!-- Profile Header with Photo -->
                    <div class="bg-gradient-to-br from-primary-500 to-primary-600 dark:from-primary-700 dark:to-primary-800 p-6">
                        <div class="flex flex-col items-center">
                            <!-- Profile Photo -->
                            <div class="relative mb-4">
                                @if($user->profile_photo_path)
                                    <img src="{{ $user->profile_photo_url }}" 
                                         alt="{{ $user->name }}" 
                                         class="h-24 w-24 rounded-full border-4 border-white dark:border-gray-800 shadow-xl object-cover">
                                @else
                                    <div class="h-24 w-24 rounded-full bg-white dark:bg-gray-700 flex items-center justify-center border-4 border-white dark:border-gray-800 shadow-xl">
                                        <span class="text-3xl font-bold text-primary-600 dark:text-primary-400">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    </div>
                                @endif
                                <!-- Status Indicator -->
                                <div class="absolute bottom-0 right-0 h-6 w-6 rounded-full border-4 border-white dark:border-gray-800 {{ $user->is_active ? 'bg-green-500' : 'bg-red-500' }}"></div>
                            </div>
                            <h2 class="text-2xl font-bold text-white mb-1">{{ $user->name }}</h2>
                            <p class="text-primary-100 text-sm">{{ $user->email }}</p>
                        </div>
                    </div>

                    <!-- User Details -->
                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Account Status -->
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">حالة الحساب</span>
                                </div>
                                @if($user->is_active)
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 flex items-center">
                                        <span class="w-2 h-2 bg-green-500 rounded-full ml-2"></span>
                                        نشط
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 flex items-center">
                                        <span class="w-2 h-2 bg-red-500 rounded-full ml-2"></span>
                                        موقوف
                                    </span>
                                @endif
                            </div>

                            <!-- Registration Date -->
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">تاريخ التسجيل</span>
                                </div>
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $user->created_at->format('Y-m-d') }}</span>
                            </div>

                            <!-- Phone Number -->
                            @if($user->phone)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">رقم الهاتف</span>
                                </div>
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $user->phone }}</span>
                            </div>
                            @endif
                        </div>

                        <!-- Toggle Status Button -->
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="toggle-status-form">
                                @csrf
                                <button type="submit" 
                                        class="w-full px-4 py-3 text-sm font-medium text-white rounded-lg {{ $user->is_active ? 'bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600' : 'bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600' }} transition-colors duration-200 shadow-md hover:shadow-lg flex items-center justify-center">
                                    @if($user->is_active)
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                        </svg>
                                        إيقاف الحساب
                                    @else
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        تفعيل الحساب
                                    @endif
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Subscription Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">الاشتراك الحالي</h2>
                            <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        @if($activeSubscription)
                            <div class="space-y-4">
                                <div class="p-4 bg-gradient-to-r from-primary-50 to-primary-100 dark:from-primary-900/30 dark:to-primary-800/30 rounded-lg border border-primary-200 dark:border-primary-700">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">اسم الباقة</span>
                                        <span class="text-lg font-bold text-primary-700 dark:text-primary-300">{{ $activeSubscription->subscription->name }}</span>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">حالة الاشتراك</span>
                                    @if($activeSubscription->isActive())
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 flex items-center">
                                            <span class="w-2 h-2 bg-green-500 rounded-full ml-2"></span>
                                            نشط
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                            منتهي
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                        <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1">تاريخ البداية</span>
                                        <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $activeSubscription->started_at->format('Y-m-d') }}</span>
                                    </div>
                                    @if($activeSubscription->expires_at)
                                        <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                            <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1">تاريخ الانتهاء</span>
                                            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $activeSubscription->expires_at->format('Y-m-d') }}</span>
                                        </div>
                                    @else
                                        <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                            <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1">نوع الاشتراك</span>
                                            <span class="text-sm font-semibold text-primary-600 dark:text-primary-400">دائم</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-sm text-gray-500 dark:text-gray-400">لا يوجد اشتراك نشط</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column - Statistics & Activities -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Total Debtors -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-r-4 border-primary-500 dark:border-primary-400">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">عدد المديونين</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($totalDebtors) }}</p>
                            </div>
                            <div class="bg-primary-100 dark:bg-primary-900/30 rounded-lg p-3">
                                <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Paid Debtors -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-r-4 border-green-500 dark:border-green-400">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">المدفوع</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($paidDebtors) }}</p>
                            </div>
                            <div class="bg-green-100 dark:bg-green-900/30 rounded-lg p-3">
                                <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Overdue Debtors -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-r-4 border-red-500 dark:border-red-400">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">المتأخر</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($overdueDebtors) }}</p>
                            </div>
                            <div class="bg-red-100 dark:bg-red-900/30 rounded-lg p-3">
                                <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Collection Rate -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-r-4 border-secondary-500 dark:border-secondary-400">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">نسبة التحصيل</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($collectionRate, 1) }}%</p>
                            </div>
                            <div class="bg-secondary-100 dark:bg-secondary-900/30 rounded-lg p-3">
                                <svg class="w-8 h-8 text-secondary-600 dark:text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Debt Amount -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">إجمالي قيمة الديون</h2>
                        <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex items-baseline mb-6">
                        <span class="text-5xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($totalDebtAmount, 2) }}</span>
                        <span class="text-xl text-gray-600 dark:text-gray-400 mr-3">ر.س</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <span class="text-sm text-gray-600 dark:text-gray-400 block mb-1">المدفوع</span>
                            <span class="text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($paidAmount, 2) }}</span>
                            <span class="text-sm text-gray-500 dark:text-gray-500 mr-1">ر.س</span>
                        </div>
                        <div class="p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                            <span class="text-sm text-gray-600 dark:text-gray-400 block mb-1">المتبقي</span>
                            <span class="text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($totalDebtAmount - $paidAmount, 2) }}</span>
                            <span class="text-sm text-gray-500 dark:text-gray-500 mr-1">ر.س</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Recent Debtors -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">آخر المديونين</h2>
                            <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        @if($recentDebtors->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentDebtors as $client)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white font-bold text-sm ml-3">
                                                {{ strtoupper(substr($client->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $client->name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $client->created_at->format('Y-m-d') }}</p>
                                            </div>
                                        </div>
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $client->status_color }}">
                                            {{ $client->status_text }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p class="text-sm text-gray-500 dark:text-gray-400">لا توجد مديونين</p>
                            </div>
                        @endif
                    </div>

                    <!-- Recent Paid Debtors -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">آخر التحصيلات</h2>
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        @if($recentPaidDebtors->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentPaidDebtors as $client)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-sm ml-3">
                                                {{ strtoupper(substr($client->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $client->name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($client->debt_amount, 2) }} ر.س</p>
                                            </div>
                                        </div>
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 flex items-center">
                                            <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            مدفوع
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm text-gray-500 dark:text-gray-400">لا توجد تحصيلات</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
