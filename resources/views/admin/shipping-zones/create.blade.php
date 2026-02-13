@extends('admin.layouts.app')

@section('title', 'إضافة منطقة شحن')
@section('page-title', 'إضافة منطقة شحن جديدة')

@section('content')
<div class="max-w-4xl">
    <form action="{{ route('admin.shipping-zones.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">معلومات المنطقة</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name_ar" class="block text-sm font-medium text-gray-700 mb-1">الاسم العربي *</label>
                    <input type="text" name="name_ar" id="name_ar" value="{{ old('name_ar') }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    @error('name_ar')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="name_en" class="block text-sm font-medium text-gray-700 mb-1">الاسم الإنجليزي</label>
                    <input type="text" name="name_en" id="name_en" value="{{ old('name_en') }}"
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
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">{{ old('description_ar') }}</textarea>
                    @error('description_ar')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="description_en" class="block text-sm font-medium text-gray-700 mb-1">الوصف الإنجليزي</label>
                    <textarea name="description_en" id="description_en" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">{{ old('description_en') }}</textarea>
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
                        <option value="مصر" {{ in_array('مصر', old('countries', [])) ? 'selected' : '' }}>مصر</option>
                        <option value="السعودية" {{ in_array('السعودية', old('countries', [])) ? 'selected' : '' }}>السعودية</option>
                        <option value="الإمارات" {{ in_array('الإمارات', old('countries', [])) ? 'selected' : '' }}>الإمارات</option>
                        <option value="قطر" {{ in_array('قطر', old('countries', [])) ? 'selected' : '' }}>قطر</option>
                        <option value="البحرين" {{ in_array('البحرين', old('countries', [])) ? 'selected' : '' }}>البحرين</option>
                        <option value="الكويت" {{ in_array('الكويت', old('countries', [])) ? 'selected' : '' }}>الكويت</option>
                        <option value="عمان" {{ in_array('عمان', old('countries', [])) ? 'selected' : '' }}>عمان</option>
                        <option value="الأردن" {{ in_array('الأردن', old('countries', [])) ? 'selected' : '' }}>الأردن</option>
                        <option value="لبنان" {{ in_array('لبنان', old('countries', [])) ? 'selected' : '' }}>لبنان</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">اضغط Ctrl للاختيار المتعدد</p>
                    @error('countries')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="cities" class="block text-sm font-medium text-gray-700 mb-1">المدن المشمولة</label>
                    <textarea name="cities_text" id="cities_text" rows="5" placeholder="اكتب أسماء المدن، كل مدينة في سطر منفصل"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">{{ old('cities_text', is_array(old('cities')) ? implode("\n", old('cities')) : '') }}</textarea>
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
                    <input type="number" name="min_order_value" id="min_order_value" value="{{ old('min_order_value', 0) }}" 
                           step="0.01" min="0"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    @error('min_order_value')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">ترتيب العرض</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" 
                           min="0"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    @error('sort_order')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <span class="mr-2 text-sm text-gray-700">منطقة نشطة</span>
                </label>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-save ml-2"></i>حفظ
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