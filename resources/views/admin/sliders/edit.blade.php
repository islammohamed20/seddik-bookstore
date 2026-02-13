@extends('admin.layouts.app')

@section('title', 'تعديل سلايد')
@section('page-title', 'تعديل السلايد')

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('admin.sliders.update', $slider) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-lg shadow p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الصورة الحالية</label>
                <img src="{{ asset('storage/' . $slider->image) }}" alt="{{ $slider->title_ar ?: $slider->title_en }}" class="h-32 rounded-lg object-cover mb-2">
                <input type="file" name="image" accept="image/*"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2">
                @error('image')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">صورة الموبايل</label>
                @if($slider->mobile_image)
                    <img src="{{ asset('storage/' . $slider->mobile_image) }}" alt="Mobile" class="h-20 rounded-lg object-cover mb-2">
                @endif
                <input type="file" name="mobile_image" accept="image/*"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2">
                @error('mobile_image')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="title_ar" class="block text-sm font-medium text-gray-700 mb-1">العنوان العربي</label>
                    <input type="text" name="title_ar" id="title_ar" value="{{ old('title_ar', $slider->title_ar) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    @error('title_ar')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="title_en" class="block text-sm font-medium text-gray-700 mb-1">العنوان الإنجليزي</label>
                    <input type="text" name="title_en" id="title_en" value="{{ old('title_en', $slider->title_en) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    @error('title_en')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="subtitle_ar" class="block text-sm font-medium text-gray-700 mb-1">العنوان الفرعي العربي</label>
                    <input type="text" name="subtitle_ar" id="subtitle_ar" value="{{ old('subtitle_ar', $slider->subtitle_ar) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    @error('subtitle_ar')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="subtitle_en" class="block text-sm font-medium text-gray-700 mb-1">العنوان الفرعي الإنجليزي</label>
                    <input type="text" name="subtitle_en" id="subtitle_en" value="{{ old('subtitle_en', $slider->subtitle_en) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    @error('subtitle_en')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div>
                <label for="button_url" class="block text-sm font-medium text-gray-700 mb-1">رابط الزر</label>
                <input type="url" name="button_url" id="button_url" value="{{ old('button_url', $slider->button_url) }}"
                       placeholder="https://example.com"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                @error('button_url')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="button_text_ar" class="block text-sm font-medium text-gray-700 mb-1">نص الزر العربي</label>
                    <input type="text" name="button_text_ar" id="button_text_ar" value="{{ old('button_text_ar', $slider->button_text_ar) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    @error('button_text_ar')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="button_text_en" class="block text-sm font-medium text-gray-700 mb-1">نص الزر الإنجليزي</label>
                    <input type="text" name="button_text_en" id="button_text_en" value="{{ old('button_text_en', $slider->button_text_en) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    @error('button_text_en')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">الترتيب</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $slider->sort_order) }}" min="0"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                @error('sort_order')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="space-y-3">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $slider->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <span class="mr-2 text-sm text-gray-700">نشط</span>
                </label>
                
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="open_in_new_tab" value="1" {{ old('open_in_new_tab', $slider->open_in_new_tab) ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <span class="mr-2 text-sm text-gray-700">فتح في تبويب جديد</span>
                </label>
            </div>
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $slider->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <span class="mr-2 text-sm text-gray-700">نشط</span>
                </label>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-save ml-2"></i>تحديث
            </button>
            <a href="{{ route('admin.sliders.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">إلغاء</a>
        </div>
    </form>
</div>
@endsection
