@extends('admin.layouts.app')

@section('title', 'تعديل منطقة شحن')
@section('page-title', 'تعديل منطقة الشحن')

@section('content')
<div class="max-w-4xl">
    <form action="{{ route('admin.shipping-zones.update', $shippingZone) }}" method="POST" class="space-y-6" id="main-form">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">معلومات المنطقة</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name_ar" class="block text-sm font-medium text-gray-700 mb-1">الاسم العربي *</label>
                    <input type="text" name="name_ar" id="name_ar" value="{{ old('name_ar', $shippingZone->name_ar) }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    @error('name_ar')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="name_en" class="block text-sm font-medium text-gray-700 mb-1">الاسم الإنجليزي</label>
                    <input type="text" name="name_en" id="name_en" value="{{ old('name_en', $shippingZone->name_en) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    @error('name_en')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label for="description_ar" class="block text-sm font-medium text-gray-700 mb-1">الوصف العربي</label>
                    <textarea name="description_ar" id="description_ar" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">{{ old('description_ar', $shippingZone->description_ar) }}</textarea>
                    @error('description_ar')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="description_en" class="block text-sm font-medium text-gray-700 mb-1">الوصف الإنجليزي</label>
                    <textarea name="description_en" id="description_en" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">{{ old('description_en', $shippingZone->description_en) }}</textarea>
                    @error('description_en')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">التغطية الجغرافية</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="countries" class="block text-sm font-medium text-gray-700 mb-1">الدول المشمولة</label>
                    <select name="countries[]" id="countries" multiple 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500"
                            style="min-height: 120px;">
                        @php
                            $selectedCountries = old('countries', $shippingZone->countries ?? []);
                        @endphp
                        <option value="مصر" {{ in_array('مصر', $selectedCountries) ? 'selected' : '' }}>مصر</option>
                        <option value="السعودية" {{ in_array('السعودية', $selectedCountries) ? 'selected' : '' }}>السعودية</option>
                        <option value="الإمارات" {{ in_array('الإمارات', $selectedCountries) ? 'selected' : '' }}>الإمارات</option>
                        <option value="قطر" {{ in_array('قطر', $selectedCountries) ? 'selected' : '' }}>قطر</option>
                        <option value="البحرين" {{ in_array('البحرين', $selectedCountries) ? 'selected' : '' }}>البحرين</option>
                        <option value="الكويت" {{ in_array('الكويت', $selectedCountries) ? 'selected' : '' }}>الكويت</option>
                        <option value="عمان" {{ in_array('عمان', $selectedCountries) ? 'selected' : '' }}>عمان</option>
                        <option value="الأردن" {{ in_array('الأردن', $selectedCountries) ? 'selected' : '' }}>الأردن</option>
                        <option value="لبنان" {{ in_array('لبنان', $selectedCountries) ? 'selected' : '' }}>لبنان</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">اضغط Ctrl للاختيار المتعدد</p>
                    @error('countries')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="cities" class="block text-sm font-medium text-gray-700 mb-1">المدن المشمولة</label>
                    <textarea name="cities_text" id="cities_text" rows="5" placeholder="اكتب أسماء المدن، كل مدينة في سطر منفصل"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">{{ old('cities_text', is_array($shippingZone->cities) ? implode("\n", $shippingZone->cities) : '') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">مدينة واحدة في كل سطر</p>
                    @error('cities')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">إعدادات الطلب</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="min_order_value" class="block text-sm font-medium text-gray-700 mb-1">الحد الأدنى لقيمة الطلب (ج.م)</label>
                    <input type="number" name="min_order_value" id="min_order_value" value="{{ old('min_order_value', $shippingZone->min_order_value) }}" 
                           step="0.01" min="0"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    @error('min_order_value')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">ترتيب العرض</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $shippingZone->sort_order) }}" 
                           min="0"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    @error('sort_order')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $shippingZone->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <span class="mr-2 text-sm text-gray-700">منطقة نشطة</span>
                </label>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-save ml-2"></i>حفظ التعديلات
            </button>
            <a href="{{ route('admin.shipping-zones.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                إلغاء
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // تحويل النص إلى array عند الإرسال
    document.querySelector('form').addEventListener('submit', function(e) {
        // حذف أي inputs خفية قديمة إذا وجدت (لتجنب التكرار في حالة الإرسال المتعدد عبر AJAX إذا كان مستخدماً، أو فقط للنظافة)
        // ولكن هنا الصفحة عادية، لذا لا مشكلة.
        
        const citiesText = document.getElementById('cities_text').value;
        const citiesArray = citiesText.split('\n').filter(city => city.trim() !== '');
        
        // إنشاء input خفي لكل مدينة
        citiesArray.forEach(city => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'cities[]';
            input.value = city.trim();
            this.appendChild(input);
        });
    });
});
</script>
@endsection