@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">تعديل الباقة</h1>
            <p class="mt-2 text-sm text-gray-600">قم بتعديل بيانات الباقة</p>
        </div>

        <!-- Form -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form action="{{ route('admin.subscriptions.update', $subscription) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            اسم الباقة <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $subscription->name) }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            وصف الباقة
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">{{ old('description', $subscription->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div class="mb-4">
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                            السعر (ر.س) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="price" 
                               id="price" 
                               step="0.01"
                               min="0"
                               value="{{ old('price', $subscription->price) }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Duration Type -->
                    <div class="mb-4">
                        <label for="duration_type" class="block text-sm font-medium text-gray-700 mb-2">
                            مدة الاشتراك <span class="text-red-500">*</span>
                        </label>
                        <select name="duration_type" 
                                id="duration_type" 
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            <option value="">اختر المدة</option>
                            <option value="month" {{ old('duration_type', $subscription->duration_type) === 'month' ? 'selected' : '' }}>شهري</option>
                            <option value="year" {{ old('duration_type', $subscription->duration_type) === 'year' ? 'selected' : '' }}>سنوي</option>
                            <option value="lifetime" {{ old('duration_type', $subscription->duration_type) === 'lifetime' ? 'selected' : '' }}>دائم</option>
                        </select>
                        @error('duration_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Max Debtors -->
                    <div class="mb-4">
                        <label for="max_debtors" class="block text-sm font-medium text-gray-700 mb-2">
                            عدد المديونين المسموح به <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="max_debtors" 
                               id="max_debtors" 
                               min="0"
                               value="{{ old('max_debtors', $subscription->max_debtors) }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                        @error('max_debtors')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Max Messages -->
                    <div class="mb-4">
                        <label for="max_messages" class="block text-sm font-medium text-gray-700 mb-2">
                            عدد الرسائل المسموح بها <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="max_messages" 
                               id="max_messages" 
                               min="0"
                               value="{{ old('max_messages', $subscription->max_messages) }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                        @error('max_messages')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- AI Enabled -->
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="ai_enabled" 
                                   value="1"
                                   {{ old('ai_enabled', $subscription->ai_enabled) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">إمكانية استخدام الذكاء الاصطناعي</span>
                        </label>
                    </div>

                    <!-- Is Active -->
                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', $subscription->is_active) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">الباقة نشطة</span>
                        </label>
                        <p class="mt-1 text-xs text-gray-500">إذا لم يتم تحديد هذا الخيار، ستكون الباقة غير نشطة ولن تظهر للمالكين</p>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3 space-x-reverse">
                        <a href="{{ route('admin.subscriptions.index') }}" 
                           class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            إلغاء
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

