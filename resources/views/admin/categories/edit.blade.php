@extends('admin.layouts.app')

@section('title', 'تعديل تصنيف')
@section('page-title', 'تعديل: ' . ($category->name_ar ?: $category->name_en ?: $category->name))

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-lg shadow p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name_ar" class="block text-sm font-medium text-gray-700 mb-1">الاسم العربي *</label>
                    <input type="text" name="name_ar" id="name_ar" value="{{ old('name_ar', $category->name_ar) }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('name_ar')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="name_en" class="block text-sm font-medium text-gray-700 mb-1">الاسم الإنجليزي</label>
                    <input type="text" name="name_en" id="name_en" value="{{ old('name_en', $category->name_en) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('name_en')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div>
                <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-1">التصنيف الأب</label>
                <select name="parent_id" id="parent_id"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    <option value="">بدون (تصنيف رئيسي)</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                            {{ $parent->name_ar ?: $parent->name_en }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="description_ar" class="block text-sm font-medium text-gray-700 mb-1">الوصف العربي</label>
                    <textarea name="description_ar" id="description_ar" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('description_ar', $category->description_ar) }}</textarea>
                    @error('description_ar')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="description_en" class="block text-sm font-medium text-gray-700 mb-1">الوصف الإنجليزي</label>
                    <textarea name="description_en" id="description_en" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('description_en', $category->description_en) }}</textarea>
                    @error('description_en')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">بانر سطح المكتب</label>
                    @if($category->banner_desktop)
                        <div class="mb-2 relative inline-block">
                            <img src="{{ asset('storage/' . $category->banner_desktop) }}" alt="Banner Desktop" class="w-full max-w-xs h-24 rounded-lg object-cover">
                            <form action="{{ route('admin.categories.delete-banner', [$category, 'desktop']) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف صورة البانر؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="absolute top-1 left-1 bg-red-600 text-white rounded-full p-1 hover:bg-red-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endif
                    <input type="file" name="banner_desktop" id="banner_desktop" accept="image/*"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">الحد الأقصى: 4MB - يعرض في Page Header</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">بانر الموبايل</label>
                    @if($category->banner_mobile)
                        <div class="mb-2 relative inline-block">
                            <img src="{{ asset('storage/' . $category->banner_mobile) }}" alt="Banner Mobile" class="w-full max-w-xs h-24 rounded-lg object-cover">
                            <form action="{{ route('admin.categories.delete-banner', [$category, 'mobile']) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف صورة البانر؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="absolute top-1 left-1 bg-red-600 text-white rounded-full p-1 hover:bg-red-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endif
                    <input type="file" name="banner_mobile" id="banner_mobile" accept="image/*"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">الحد الأقصى: 2MB - يعرض في Page Header</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="color_start" class="block text-sm font-medium text-gray-700 mb-1">لون البداية (الخلفية)</label>
                    <div class="flex items-center gap-3">
                        <input type="color" id="color_start_picker" value="{{ old('color_start', $category->color_start ?? '#3b82f6') }}"
                               class="w-12 h-10 rounded border border-gray-300" onchange="document.getElementById('color_start').value=this.value">
                        <input type="text" name="color_start" id="color_start" value="{{ old('color_start', $category->color_start) }}"
                               placeholder="#3b82f6"
                               class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    @error('color_start')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="color_end" class="block text-sm font-medium text-gray-700 mb-1">لون النهاية (الخلفية)</label>
                    <div class="flex items-center gap-3">
                        <input type="color" id="color_end_picker" value="{{ old('color_end', $category->color_end ?? '#1d4ed8') }}"
                               class="w-12 h-10 rounded border border-gray-300" onchange="document.getElementById('color_end').value=this.value">
                        <input type="text" name="color_end" id="color_end" value="{{ old('color_end', $category->color_end) }}"
                               placeholder="#1d4ed8"
                               class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    @error('color_end')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="icon" class="block text-sm font-medium text-gray-700 mb-1">أيقونة Font Awesome</label>
                <input type="text" name="icon" id="icon" value="{{ old('icon', $category->icon) }}" placeholder="fa-book-open"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">مثال: fa-book-open, fa-star, fa-puzzle-piece. <a href="https://fontawesome.com/icons" target="_blank" class="text-indigo-600 hover:underline">تصفح الأيقونات</a></p>
            </div>

            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">الترتيب</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <span class="mr-2 text-sm text-gray-700">نشط</span>
                </label>
            </div>
            
            <div>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $category->is_featured) ? 'checked' : '' }}
                           class="w-4 h-4 text-yellow-600 border-gray-300 rounded focus:ring-yellow-500">
                    <span class="mr-2 text-sm text-gray-700">⭐ تصنيف مميز (يظهر في الصفحة الرئيسية)</span>
                </label>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-save ml-2"></i>تحديث
            </button>
            <a href="{{ route('admin.categories.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection
