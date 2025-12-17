@extends('layouts.owner')

@section('content')
<div class="py-12">
    <div class="w-full mx-auto sm:px-6 lg:px-8 max-w-3xl">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">إنشاء شكوى جديدة</h1>
            <p class="mt-2 text-sm text-gray-600">أرسل شكواك أو استفسارك وسنقوم بالرد في أقرب وقت</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-700 to-gray-600 px-6 py-4">
                <h2 class="text-lg font-bold text-white">بيانات الشكوى</h2>
            </div>
            <div class="p-6">
                <form action="{{ route('owner.tickets.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Subject -->
                    <div class="mb-6">
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                            عنوان الشكوى <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="subject" 
                               name="subject" 
                               value="{{ old('subject') }}"
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('subject') border-red-500 @enderror">
                        @error('subject')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div class="mb-6">
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                            نوع الشكوى <span class="text-red-500">*</span>
                        </label>
                        <select id="type" 
                                name="type" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-500 @enderror">
                            <option value="">اختر النوع</option>
                            <option value="technical" {{ old('type') == 'technical' ? 'selected' : '' }}>مشكلة تقنية</option>
                            <option value="subscription" {{ old('type') == 'subscription' ? 'selected' : '' }}>مشكلة اشتراك</option>
                            <option value="messages" {{ old('type') == 'messages' ? 'selected' : '' }}>مشكلة رسائل</option>
                            <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>استفسار عام</option>
                            <option value="suggestion" {{ old('type') == 'suggestion' ? 'selected' : '' }}>اقتراح</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            وصف الشكوى <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="6"
                                  required
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                  placeholder="اكتب تفاصيل شكواك هنا...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">الحد الأدنى 10 أحرف</p>
                    </div>

                    <!-- Attachment -->
                    <div class="mb-6">
                        <label for="attachment" class="block text-sm font-medium text-gray-700 mb-2">
                            مرفق (صورة) - اختياري
                        </label>
                        <input type="file" 
                               id="attachment" 
                               name="attachment" 
                               accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('attachment') border-red-500 @enderror">
                        @error('attachment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">الحد الأقصى 2MB - الصور فقط</p>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-end space-x-3 space-x-reverse">
                        <a href="{{ route('owner.tickets.index') }}" 
                           class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                            إلغاء
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                            إرسال الشكوى
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

