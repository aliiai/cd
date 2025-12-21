<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'منصة تحصيل الديون الذكية') }}</title>

    <!-- Fonts - Arabic Support -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
            @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900" style="font-family: 'Cairo', sans-serif;">
    
    <!-- ========== Header ========== -->
    <header class="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-50 transition-colors duration-200 py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-2 space-x-reverse">
                       
                          <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-24">
                        
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="hidden md:flex items-center space-x-6 space-x-reverse">
                    <a href="#features" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">المميزات</a>
                    <a href="#services" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">الخدمات</a>
                    <a href="#ai" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">الذكاء الاصطناعي</a>
                    <a href="#about" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">من نحن</a>
                    <a href="#contact" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">تواصل معنا</a>
                </nav>

                <!-- Auth Buttons -->
                <div class="flex items-center space-x-3 space-x-reverse">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                            لوحة التحكم
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                            تسجيل الدخول
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                                إنشاء حساب
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
        </header>

    <!-- ========== Hero Section ========== -->
    <section class="relative bg-gradient-to-br from-primary-50 via-white to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-20 lg:py-32 overflow-hidden">
        <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Content -->
                <div class="text-center lg:text-right">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-gray-100 mb-6 leading-tight">
                        منصة <span class="text-primary-600 dark:text-primary-400">ذكية</span> عشان تحصيل الديون
                    </h1>
                    <p class="text-xl text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                        نؤتمت عمليات التحصيل باستخدام الذكاء الاصطناعي وقنوات التواصل الرقمية. حلول احترافية عشان نسّرع استرداد المستحقات.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('register') }}" class="px-8 py-4 bg-primary-600 hover:bg-primary-700 text-white text-lg font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1">
                            ابدأ الحين مجاناً
                        </a>
                        <a href="#features" class="px-8 py-4 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 text-lg font-semibold rounded-lg shadow-md hover:shadow-lg border border-gray-200 dark:border-gray-700 transition-all duration-200">
                            شوف المميزات
                        </a>
                    </div>
                </div>
                <!-- Illustration -->
                <div class="relative">
                    <div class="relative z-10">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 transform rotate-3 hover:rotate-0 transition-transform duration-300">
                            <div class="space-y-4">
                                <div class="flex items-center space-x-3 space-x-reverse">
                                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">رسالة تلقائية</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">تم الإرسال بنجاح</p>
                                    </div>
                                </div>
                                <div class="h-32 bg-gradient-to-br from-primary-100 to-secondary-100 dark:from-primary-900/30 dark:to-secondary-900/30 rounded-lg"></div>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -bottom-4 -left-4 w-full h-full bg-secondary-200 dark:bg-secondary-900/30 rounded-2xl transform rotate-6"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== من نحن ========== -->
    <section id="about" class="py-20 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-4">من نحن</h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                    منصة رقمية متخصصة في أتمتة عمليات تحصيل الديون باستخدام أحدث تقنيات الذكاء الاصطناعي
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6 rounded-xl bg-gray-50 dark:bg-gray-700/50 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">سرعة فائقة</h3>
                    <p class="text-gray-600 dark:text-gray-400">نرسل آلاف الرسائل في دقائق معدودة</p>
                </div>
                <div class="text-center p-6 rounded-xl bg-gray-50 dark:bg-gray-700/50 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-secondary-100 dark:bg-secondary-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-secondary-600 dark:text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">ذكاء اصطناعي</h3>
                    <p class="text-gray-600 dark:text-gray-400">نولّد رسائل ذكية ومخصصة تلقائياً</p>
                </div>
                <div class="text-center p-6 rounded-xl bg-gray-50 dark:bg-gray-700/50 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">آمن ومحمي</h3>
                    <p class="text-gray-600 dark:text-gray-400">حماية كاملة لبياناتك وعملياتك</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== المميزات ========== -->
    <section id="features" class="py-20 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-4">مميزاتنا</h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                    حلول متكاملة تجعل عملية التحصيل أسهل وأكثر فعالية
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md hover:shadow-xl transition-shadow duration-200">
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">إرسال متعدد القنوات</h3>
                    <p class="text-gray-600 dark:text-gray-400">نرسل رسائل SMS و Email من مكان واحد مع تتبع الحالة</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md hover:shadow-xl transition-shadow duration-200">
                    <div class="w-12 h-12 bg-secondary-100 dark:bg-secondary-900/30 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-secondary-600 dark:text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">تقارير شاملة</h3>
                    <p class="text-gray-600 dark:text-gray-400">تحليلات مفصلة عن أداء التحصيل والرسائل اللي مرسلة</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md hover:shadow-xl transition-shadow duration-200">
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">روابط دفع مدمجة</h3>
                    <p class="text-gray-600 dark:text-gray-400">نضيف روابط الدفع مباشرة في الرسائل عشان نسّرع السداد</p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md hover:shadow-xl transition-shadow duration-200">
                    <div class="w-12 h-12 bg-secondary-100 dark:bg-secondary-900/30 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-secondary-600 dark:text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">إدارة المديونين</h3>
                    <p class="text-gray-600 dark:text-gray-400">نظام متكامل عشان ندير بيانات المديونين وحالات الديون</p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md hover:shadow-xl transition-shadow duration-200">
                    <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">جدولة تلقائية</h3>
                    <p class="text-gray-600 dark:text-gray-400">نجدول الرسائل مسبقاً ونرسلها في الوقت المناسب</p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md hover:shadow-xl transition-shadow duration-200">
                    <div class="w-12 h-12 bg-secondary-100 dark:bg-secondary-900/30 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-secondary-600 dark:text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">سجل كامل</h3>
                    <p class="text-gray-600 dark:text-gray-400">توثيق شامل لجميع العمليات والرسائل اللي مرسلة</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== الخدمات ========== -->
    <section id="services" class="py-20 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-4">خدماتنا</h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                    مجموعة شاملة من الخدمات المصممة عشان نحسّن عملية التحصيل
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Service 1 -->
                <div class="bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-800/20 rounded-xl p-8 border-r-4 border-primary-500">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-3">إرسال الرسائل</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">نرسل رسائل SMS و Email بسهولة مع تتبع حالة الإرسال والاستلام</p>
                    <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-primary-600 dark:text-primary-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            إرسال SMS عبر FourJawaly
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-primary-600 dark:text-primary-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            إرسال Email عبر SMTP
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-primary-600 dark:text-primary-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            تتبع حالة الإرسال
                        </li>
                    </ul>
                </div>

                <!-- Service 2 -->
                <div class="bg-gradient-to-br from-secondary-50 to-secondary-100 dark:from-secondary-900/20 dark:to-secondary-800/20 rounded-xl p-8 border-r-4 border-secondary-500">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-3">إدارة الحملات</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">ننشئ وندير حملات تحصيل متعددة مع إمكانية التخصيص الكامل</p>
                    <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-secondary-600 dark:text-secondary-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            إنشاء حملات متعددة
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-secondary-600 dark:text-secondary-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                            اختيار المديونين المستهدفين
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-secondary-600 dark:text-secondary-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                            معاينة قبل الإرسال
                        </li>
                    </ul>
                </div>

                <!-- Service 3 -->
                <div class="bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-800/20 rounded-xl p-8 border-r-4 border-primary-500">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-3">التقارير والتحليلات</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">تقارير مفصلة ولوحات تحكم شاملة عشان نتتبع الأداء</p>
                    <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-primary-600 dark:text-primary-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            تقارير التحصيل
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-primary-600 dark:text-primary-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            تحليل الدخل
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-primary-600 dark:text-primary-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            توزيع حالات الديون
                        </li>
                    </ul>
                </div>

                <!-- Service 4 -->
                <div class="bg-gradient-to-br from-secondary-50 to-secondary-100 dark:from-secondary-900/20 dark:to-secondary-800/20 rounded-xl p-8 border-r-4 border-secondary-500">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-3">نظام الاشتراكات</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">خطط مرنة تناسب احتياجاتك مع إدارة سهلة للاشتراكات</p>
                    <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-secondary-600 dark:text-secondary-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            خطط متنوعة
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-secondary-600 dark:text-secondary-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            تجديد تلقائي
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-secondary-600 dark:text-secondary-400 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                            إشعارات قبل الانتهاء
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== الذكاء الاصطناعي ========== -->
    <section id="ai" class="py-20 bg-gradient-to-br from-primary-50 via-white to-secondary-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Content -->
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-6">الذكاء الاصطناعي في خدمتك</h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                        نستخدم أحدث تقنيات الذكاء الاصطناعي عشان نولّد رسائل ذكية ومخصصة تلقائياً. كل رسالة تُصمم خصيصاً بناءً على:
                    </p>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-primary-600 dark:text-primary-400 ml-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">اسم المديون</h3>
                                <p class="text-gray-600 dark:text-gray-400">نخصص الرسالة باسم المديون عشان نزيد التأثير</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-primary-600 dark:text-primary-400 ml-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">مبلغ الدين</h3>
                                <p class="text-gray-600 dark:text-gray-400">نضمن المبلغ بشكل واضح ومهني</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-primary-600 dark:text-primary-400 ml-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">تاريخ الاستحقاق</h3>
                                <p class="text-gray-600 dark:text-gray-400">نذكّر بموعد الاستحقاق ودرجة التأخير</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-primary-600 dark:text-primary-400 ml-3 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">لغة محترمة</h3>
                                <p class="text-gray-600 dark:text-gray-400">رسائل مهنية واحترافية بدون تهديد أو ضغط</p>
                            </div>
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="inline-block px-8 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                        جرب الذكاء الاصطناعي الحين
                    </a>
                </div>
                <!-- Illustration -->
                <div class="relative">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8">
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3 space-x-reverse mb-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-gray-100">رسالة ذكية</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">مولّدة بالذكاء الاصطناعي</p>
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed">
                                    "السلام عليكم ورحمة الله وبركاته،<br>
                                    نود تذكيركم بأن عندكم مبلغ مستحق بقيمة <strong>5,000 ر.س</strong> بتاريخ <strong>2024-01-15</strong>.<br>
                                    نرجو منكم تسوية المبلغ في أقرب وقت ممكن..."
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== تواصل معنا ========== -->
    <section id="contact" class="py-20 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-gray-100 mb-4">تواصل معنا</h2>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                    نحن هنا عشان نجيب على استفساراتك ونساعدك في البدء
                </p>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-8">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">أرسل لنا رسالة</h3>
                    <form class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">الاسم</label>
                            <input type="text" id="name" name="name" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100" placeholder="اسمك الكامل">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">البريد الإلكتروني</label>
                            <input type="email" id="email" name="email" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100" placeholder="example@email.com">
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">الرسالة</label>
                            <textarea id="message" name="message" rows="5" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100" placeholder="اكتب رسالتك هنا..."></textarea>
                        </div>
                        <button type="submit" class="w-full px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                            إرسال الرسالة
                        </button>
                    </form>
                </div>

                <!-- Contact Info -->
                <div class="space-y-8">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">معلومات التواصل</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center ml-4 flex-shrink-0">
                                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">البريد الإلكتروني</h4>
                                    <p class="text-gray-600 dark:text-gray-400">info@example.com</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-secondary-100 dark:bg-secondary-900/30 rounded-lg flex items-center justify-center ml-4 flex-shrink-0">
                                    <svg class="w-6 h-6 text-secondary-600 dark:text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">الهاتف</h4>
                                    <p class="text-gray-600 dark:text-gray-400">+966 50 123 4567</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900/30 rounded-lg flex items-center justify-center ml-4 flex-shrink-0">
                                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">الموقع</h4>
                                    <p class="text-gray-600 dark:text-gray-400">المملكة العربية السعودية</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== خاتمة ========== -->
    <section class="py-20 bg-gradient-to-br from-primary-600 to-secondary-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">ابدأ رحلتك معنا الحين</h2>
            <p class="text-xl mb-8 text-primary-100 max-w-2xl mx-auto">
                انضم لمئات الشركات اللي تستخدم منصتنا عشان نحسّن عملية التحصيل ونساعد في زيادة الإيرادات
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-primary-600 text-lg font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1">
                    إنشاء حساب مجاني
                </a>
                <a href="#features" class="px-8 py-4 bg-primary-700 hover:bg-primary-800 text-white text-lg font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 border-2 border-white/20">
                    تعرّف على المزيد
                </a>
            </div>
        </div>
    </section>

    <!-- ========== Footer ========== -->
    <footer class="bg-gray-900 text-gray-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <!-- About -->
                <div>
                    <h3 class="text-white font-bold text-lg mb-4">{{ config('app.name', 'منصة تحصيل الديون') }}</h3>
                    <p class="text-gray-400 text-sm">
                        منصة رقمية متخصصة في أتمتة عمليات تحصيل الديون باستخدام الذكاء الاصطناعي
                    </p>
                </div>
                <!-- Quick Links -->
                <div>
                    <h4 class="text-white font-semibold mb-4">روابط سريعة</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#features" class="hover:text-white transition-colors">المميزات</a></li>
                        <li><a href="#services" class="hover:text-white transition-colors">الخدمات</a></li>
                        <li><a href="#ai" class="hover:text-white transition-colors">الذكاء الاصطناعي</a></li>
                        <li><a href="#about" class="hover:text-white transition-colors">من نحن</a></li>
                    </ul>
                </div>
                <!-- Support -->
                <div>
                    <h4 class="text-white font-semibold mb-4">الدعم</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#contact" class="hover:text-white transition-colors">تواصل معنا</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">الأسئلة الشائعة</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">الدعم الفني</a></li>
                    </ul>
                </div>
                <!-- Legal -->
                <div>
                    <h4 class="text-white font-semibold mb-4">قانوني</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">سياسة الخصوصية</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">شروط الاستخدام</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">سياسة الاسترجاع</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'منصة تحصيل الديون') }}. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </footer>

    <!-- Smooth Scroll Script -->
    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
    </body>
</html>
