@extends('admin.layouts.app')

@section('title', 'إضافة صفحة')
@section('page-title', 'إضافة صفحة جديدة')

@section('content')
<div class="max-w-4xl">
    <form action="{{ route('admin.pages.store') }}" method="POST" class="space-y-6">
        @csrf
        <div class="bg-white rounded-lg shadow p-6 space-y-6">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">العنوان *</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 mb-1">المحتوى *</label>
                <textarea name="content" id="content" rows="10" required
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">{{ old('content') }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">عنوان SEO</label>
                    <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">وصف SEO</label>
                    <input type="text" name="meta_description" id="meta_description" value="{{ old('meta_description') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
            <div>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <span class="mr-2 text-sm text-gray-700">نشر الصفحة</span>
                </label>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-save ml-2"></i>حفظ
            </button>
            <a href="{{ route('admin.pages.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">إلغاء</a>
        </div>
    </form>
</div>
@endsection
