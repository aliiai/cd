@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="w-full mx-auto sm:px-6 lg:px-8 max-w-4xl">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">إضافة مشرف جديد</h1>
                    <p class="mt-2 text-sm text-gray-600">إنشاء حساب مشرف جديد مع الصلاحيات المناسبة</p>
                </div>
                <a href="{{ route('admin.admins.index') }}" 
                   class="text-blue-600 hover:text-blue-800 font-medium">
                    ← العودة للقائمة
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-700 to-gray-600 px-6 py-4">
                <h2 class="text-lg font-bold text-white">بيانات المشرف</h2>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.admins.store') }}" method="POST">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                الاسم <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                البريد الإلكتروني <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                كلمة المرور <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   required
                                   minlength="8"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">الحد الأدنى 8 أحرف</p>
                        </div>

                        <!-- Password Confirmation -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                تأكيد كلمة المرور <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required
                                   minlength="8"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Role -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                الدور <span class="text-red-500">*</span>
                            </label>
                            <select id="role" 
                                    name="role" 
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-500 @enderror">
                                <option value="">اختر الدور</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                        {{ $role->name === 'super_admin' ? 'Super Admin' : 'Admin' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">
                                حالة الحساب
                            </label>
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="is_active" class="mr-2 text-sm text-gray-700">نشط</label>
                            </div>
                        </div>
                    </div>

                    <!-- Permissions -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            الصلاحيات
                        </label>
                        <div class="bg-gray-50 rounded-lg p-4 max-h-96 overflow-y-auto">
                            @foreach($permissions as $category => $categoryPermissions)
                                <div class="mb-4">
                                    <h4 class="text-sm font-semibold text-gray-800 mb-2 capitalize">{{ $category }}</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                                        @foreach($categoryPermissions as $permission)
                                            <label class="flex items-center p-2 bg-white rounded border border-gray-200 hover:bg-blue-50 cursor-pointer">
                                                <input type="checkbox" 
                                                       name="permissions[]" 
                                                       value="{{ $permission->name }}"
                                                       {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}
                                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                                <span class="mr-2 text-sm text-gray-700">{{ $permission->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('permissions.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-end space-x-3 space-x-reverse">
                        <a href="{{ route('admin.admins.index') }}" 
                           class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                            إلغاء
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                            إنشاء المشرف
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

