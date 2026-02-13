@extends('admin.layouts.app')

@section('title', 'إضافة عرض')
@section('page-title', 'إضافة عرض جديد')

@section('content')
<div class="max-w-3xl">
    <form action="{{ route('admin.offers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div class="bg-white rounded-lg shadow p-6 space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">اسم العرض *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">الوصف</label>
                <textarea name="description" id="description" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">{{ old('description') }}</textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="discount_type" class="block text-sm font-medium text-gray-700 mb-1">نوع الخصم *</label>
                    <select name="discount_type" id="discount_type" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                        <option value="fixed" {{ old('discount_type') === 'fixed' ? 'selected' : '' }}>مبلغ ثابت</option>
                        <option value="percent" {{ old('discount_type') === 'percent' ? 'selected' : '' }}>نسبة مئوية</option>
                        <option value="free_shipping" {{ old('discount_type') === 'free_shipping' ? 'selected' : '' }}>شحن مجاني</option>
                    </select>
                </div>
                <div>
                    <label for="discount_value" class="block text-sm font-medium text-gray-700 mb-1">قيمة الخصم *</label>
                    <input type="number" name="discount_value" id="discount_value" value="{{ old('discount_value') }}" step="0.01" min="0"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="starts_at" class="block text-sm font-medium text-gray-700 mb-1">تاريخ البدء *</label>
                    <input type="date" name="starts_at" id="starts_at" value="{{ old('starts_at') }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="ends_at" class="block text-sm font-medium text-gray-700 mb-1">تاريخ الانتهاء *</label>
                    <input type="date" name="ends_at" id="ends_at" value="{{ old('ends_at') }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">صورة العرض</label>
                <input type="file" name="image" id="image" accept="image/*"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">المنتجات المشمولة</label>
                <div class="max-h-60 overflow-y-auto border border-gray-300 rounded-lg p-3 space-y-2">
                    @foreach($products as $product)
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="products[]" value="{{ $product->id }}"
                                   {{ in_array($product->id, old('products', [])) ? 'checked' : '' }}
                                   class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <span class="mr-2 text-sm text-gray-700">{{ $product->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <span class="mr-2 text-sm text-gray-700">نشط</span>
                </label>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-save ml-2"></i>حفظ
            </button>
            <a href="{{ route('admin.offers.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">إلغاء</a>
        </div>
    </form>
</div>
@endsection
