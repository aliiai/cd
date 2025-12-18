@extends('layouts.admin')

@section('content')
<div class="py-6">
    <div class="w-full mx-auto sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">الإعدادات</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">إدارة معلومات حسابك وإعدادات الأمان والتفضيلات</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="mb-6 p-4 bg-primary-50 dark:bg-primary-900/20 border border-primary-200 dark:border-primary-800 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-primary-600 dark:text-primary-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm font-medium text-primary-800 dark:text-primary-300">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Error Message -->
        @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-300">يرجى تصحيح الأخطاء التالية:</h3>
                    <ul class="mt-2 text-sm text-red-700 dark:text-red-400 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <!-- Tabs Navigation -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex space-x-8 space-x-reverse px-6" aria-label="Tabs">
                    <button onclick="showTab('profile')" id="tab-profile" class="tab-button active py-4 px-1 border-b-2 border-primary-500 font-medium text-sm text-primary-600 dark:text-primary-400">
                        معلومات الحساب
                    </button>
                    <button onclick="showTab('password')" id="tab-password" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600">
                        كلمة المرور
                    </button>
                    <button onclick="showTab('preferences')" id="tab-preferences" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600">
                        التفضيلات
                    </button>
                    <button onclick="showTab('security')" id="tab-security" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600">
                        الأمان والجلسات
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Profile Information Tab -->
                <div id="tab-content-profile" class="tab-content">
                    <form method="POST" action="{{ route('admin.settings.profile') }}" enctype="multipart/form-data" x-data="{ photoPreview: '{{ $user->profile_photo_url ?? '' }}' }">
                        @csrf
                        @method('POST')

                        <div class="space-y-6">
                            <!-- Profile Photo -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    الصورة الشخصية <span class="text-gray-400 dark:text-gray-500 text-xs">(اختياري)</span>
                                </label>
                                <div class="flex items-center space-x-4 space-x-reverse">
                                    <!-- Current Photo -->
                                    <div class="flex-shrink-0">
                                        <img x-show="photoPreview" :src="photoPreview" alt="Profile Photo" class="h-20 w-20 rounded-full object-cover border-2 border-primary-500 shadow-md">
                                        <div x-show="!photoPreview" class="h-20 w-20 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center border-2 border-dashed border-gray-300 dark:border-gray-600">
                                            <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <!-- Upload Button -->
                                    <div>
                                        <label for="photo" class="cursor-pointer inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                            </svg>
                                            اختر صورة
                                        </label>
                                        <input type="file" id="photo" name="photo" accept="image/*" class="hidden" @change="photoPreview = URL.createObjectURL($event.target.files[0])">
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">JPG, PNG أو GIF. الحد الأقصى 2MB</p>
                                    </div>
                                </div>
                                @error('photo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Full Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    الاسم الكامل <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}" 
                                       required
                                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    البريد الإلكتروني <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}" 
                                       required
                                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    رقم الهاتف <span class="text-gray-400 dark:text-gray-500 text-xs">(اختياري)</span>
                                </label>
                                <input type="tel" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $user->phone) }}" 
                                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 @error('phone') border-red-500 @enderror"
                                       placeholder="05xxxxxxxx">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end">
                                <button type="submit" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                    حفظ التغييرات
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Password Tab -->
                <div id="tab-content-password" class="tab-content hidden">
                    <form method="POST" action="{{ route('admin.settings.password') }}">
                        @csrf
                        @method('POST')

                        <div class="space-y-6">
                            <!-- Current Password -->
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    كلمة المرور الحالية <span class="text-red-500">*</span>
                                </label>
                                <input type="password" 
                                       id="current_password" 
                                       name="current_password" 
                                       required
                                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('current_password', 'updatePassword') border-red-500 @enderror">
                                @error('current_password', 'updatePassword')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    كلمة المرور الجديدة <span class="text-red-500">*</span>
                                </label>
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       required
                                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('password', 'updatePassword') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">8 أحرف على الأقل، حرف كبير وصغير، رقم وعلامة خاصة</p>
                                @error('password', 'updatePassword')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    تأكيد كلمة المرور الجديدة <span class="text-red-500">*</span>
                                </label>
                                <input type="password" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required
                                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('password_confirmation', 'updatePassword') border-red-500 @enderror">
                                @error('password_confirmation', 'updatePassword')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end">
                                <button type="submit" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                    تحديث كلمة المرور
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Preferences Tab -->
                <div id="tab-content-preferences" class="tab-content hidden">
                    <form method="POST" action="{{ route('admin.settings.preferences') }}">
                        @csrf
                        @method('POST')

                        <div class="space-y-6">
                            <!-- Dark Mode Preference -->
                            <div>
                                <label for="dark_mode" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    الوضع الافتراضي للمظهر
                                </label>
                                <select id="dark_mode" 
                                        name="dark_mode" 
                                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('dark_mode') border-red-500 @enderror">
                                    <option value="system" {{ $darkModePreference === 'system' ? 'selected' : '' }}>تلقائي (حسب النظام)</option>
                                    <option value="light" {{ $darkModePreference === 'light' ? 'selected' : '' }}>فاتح</option>
                                    <option value="dark" {{ $darkModePreference === 'dark' ? 'selected' : '' }}>داكن</option>
                                </select>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">اختر الوضع الافتراضي للمظهر (يمكن تغييره من أيقونة الوضع الداكن في الـ Header)</p>
                                @error('dark_mode')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Language Preference -->
                            <div>
                                <label for="language" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    اللغة الافتراضية للواجهة
                                </label>
                                <select id="language" 
                                        name="language" 
                                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('language') border-red-500 @enderror">
                                    <option value="ar" {{ session('locale', config('app.locale')) === 'ar' ? 'selected' : '' }}>العربية</option>
                                    <option value="en" {{ session('locale', config('app.locale')) === 'en' ? 'selected' : '' }}>English</option>
                                </select>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">اختر اللغة الافتراضية لعرض الواجهة (Static - للعرض فقط حالياً)</p>
                                @error('language')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Info Box -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <h4 class="text-sm font-medium text-blue-800 dark:text-blue-300">ملاحظة</h4>
                                        <p class="mt-1 text-xs text-blue-700 dark:text-blue-400">هذه التفضيلات خاصة بحسابك فقط ولا تؤثر على إعدادات النظام العامة أو المستخدمين الآخرين.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end">
                                <button type="submit" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                    حفظ التفضيلات
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Security Tab -->
                <div id="tab-content-security" class="tab-content hidden">
                    <div class="space-y-6">
                        <!-- Active Sessions -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">الجلسات النشطة</h3>
                            
                            @if($sessions->isEmpty())
                                <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 text-center">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">لا توجد جلسات نشطة</p>
                                </div>
                            @else
                                <div class="space-y-3">
                                    @foreach($sessions as $session)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg {{ $session->is_current ? 'ring-2 ring-primary-500' : '' }}">
                                        <div class="flex items-center space-x-3 space-x-reverse">
                                            <div class="flex-shrink-0">
                                                @if($session->is_current)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                                        الجلسة الحالية
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                                        جلسة أخرى
                                                    </span>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $session->user_agent }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ $session->ip_address }} • آخر نشاط: {{ $session->last_activity->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <!-- Logout Other Sessions -->
                                <div class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                                    <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-300 mb-2">تسجيل الخروج من جميع الجلسات الأخرى</h4>
                                    <p class="text-xs text-yellow-700 dark:text-yellow-400 mb-4">سيتم تسجيل الخروج من جميع الأجهزة الأخرى باستثناء هذا الجهاز.</p>
                                    
                                    <form method="POST" action="{{ route('admin.settings.logout-other-sessions') }}">
                                        @csrf
                                        @method('POST')
                                        
                                        <div class="mb-4">
                                            <label for="logout_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                كلمة المرور <span class="text-red-500">*</span>
                                            </label>
                                            <input type="password" 
                                                   id="logout_password" 
                                                   name="password" 
                                                   required
                                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 @error('password') border-red-500 @enderror">
                                            @error('password')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <button type="submit" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">
                                            تسجيل الخروج من جميع الجلسات الأخرى
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>

                        <!-- Last Login Info -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">معلومات تسجيل الدخول</h3>
                            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">تاريخ التسجيل</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->created_at->format('Y-m-d H:i') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">آخر تحديث للحساب</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->updated_at->format('Y-m-d H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab switching function
        window.showTab = function(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'border-primary-500', 'text-primary-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Show selected tab content
            const contentElement = document.getElementById('tab-content-' + tabName);
            if (contentElement) {
                contentElement.classList.remove('hidden');
            }
            
            // Add active class to selected tab
            const selectedTab = document.getElementById('tab-' + tabName);
            if (selectedTab) {
                selectedTab.classList.add('active', 'border-primary-500', 'text-primary-600');
                selectedTab.classList.remove('border-transparent', 'text-gray-500');
            }
        };
    });
</script>
@endpush
@endsection
