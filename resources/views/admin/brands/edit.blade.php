@extends('admin.layouts.app')

@section('title', 'تعديل علامة تجارية')
@section('page-title', 'تعديل: ' . $brand->name)

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        <div class="bg-white rounded-lg shadow p-6 space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">الاسم *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $brand->name) }}" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">الوصف</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">{{ old('description', $brand->description) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الشعار</label>
                @if($brand->logo)
                    <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="w-20 h-20 rounded-lg object-cover mb-2">
                @endif
                <input type="file" name="logo" accept="image/*" class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>
            <div>
                <label for="website" class="block text-sm font-medium text-gray-700 mb-1">الموقع الإلكتروني</label>
                <input type="url" name="website" id="website" value="{{ old('website', $brand->website) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $brand->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <span class="mr-2 text-sm text-gray-700">نشط</span>
                </label>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-save ml-2"></i>تحديث
            </button>
            <a href="{{ route('admin.brands.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">إلغاء</a>
        </div>
    </form>
</div>
@endsection
