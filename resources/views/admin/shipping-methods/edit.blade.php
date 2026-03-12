@extends('admin.layouts.app')

@section('title', 'تعديل طريقة شحن')
@section('page-title', 'تعديل طريقة شحن: ' . $shippingMethod->name_ar)

@section('content')
<div class="max-w-4xl" x-data="{ type: '{{ old('type', $shippingMethod->type) }}' }">
    <form action="{{ route('admin.shipping-methods.update', $shippingMethod) }}" method="POST" class="space-y-6" id="main-form">
        @csrf
        @method('PUT')

        <!-- Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name AR -->
                <div>
                    <label for="name_ar" class="block text-sm font-medium text-gray-700 mb-1">الاسم (بالعربية) <span class="text-red-500">*</span></label>
                    <input type="text" name="name_ar" id="name_ar" value="{{ old('name_ar', $shippingMethod->name_ar) }}" required
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('name_ar')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name EN -->
                <div>
                    <label for="name_en" class="block text-sm font-medium text-gray-700 mb-1">الاسم (باللغة الإنجليزية)</label>
                    <input type="text" name="name_en" id="name_en" value="{{ old('name_en', $shippingMethod->name_en) }}"
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    @error('name_en')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type -->
                <div class="md:col-span-2">
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">نوع طريقة الشحن <span class="text-red-500">*</span></label>
                    <select name="type" id="type" x-model="type" required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="flat_rate" {{ old('type', $shippingMethod->type) == 'flat_rate' ? 'selected' : '' }}>سعر ثابت</option>
                        <option value="weight_based" {{ old('type', $shippingMethod->type) == 'weight_based' ? 'selected' : '' }}>حسب الوزن</option>
                        <option value="free_shipping" {{ old('type', $shippingMethod->type) == 'free_shipping' ? 'selected' : '' }}>شحن مجاني</option>
                        <option value="pickup" {{ old('type', $shippingMethod->type) == 'pickup' ? 'selected' : '' }}>الاستلام من المتجر</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cost -->
                <div x-show="['flat_rate', 'weight_based'].includes(type)">
                    <label for="cost" class="block text-sm font-medium text-gray-700 mb-1">تلفة الشحن <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="number" step="0.01" name="cost" id="cost" value="{{ old('cost', $shippingMethod->cost) }}"
                               :required="['flat_rate', 'weight_based'].includes(type)"
                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 pl-12">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">ج.م</span>
                        </div>
                    </div>
                    @error('cost')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Free Shipping Threshold -->
                <div x-show="['flat_rate', 'weight_based'].includes(type)">
                    <label for="free_shipping_threshold" class="block text-sm font-medium text-gray-700 mb-1">الحد الأدنى للشحن المجاني</label>
                    <div class="relative">
                        <input type="number" step="0.01" name="free_shipping_threshold" id="free_shipping_threshold" value="{{ old('free_shipping_threshold', $shippingMethod->free_shipping_threshold) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 pl-12">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">ج.م</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">اتركه فارغاً إذا لم يكن هناك شحن مجاني لهذا النوع</p>
                    @error('free_shipping_threshold')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Weight Limits -->
                <div x-show="type === 'weight_based'" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div>
                        <label for="min_weight" class="block text-sm font-medium text-gray-700 mb-1">أقل وزن (كجم)</label>
                        <input type="number" step="0.01" name="min_weight" id="min_weight" value="{{ old('min_weight', $shippingMethod->min_weight) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="max_weight" class="block text-sm font-medium text-gray-700 mb-1">أقصى وزن (كجم)</label>
                        <input type="number" step="0.01" name="max_weight" id="max_weight" value="{{ old('max_weight', $shippingMethod->max_weight) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <!-- Delivery Time -->
                <div>
                    <label for="delivery_time_min" class="block text-sm font-medium text-gray-700 mb-1">وقت التوصيل (من)</label>
                    <div class="relative">
                        <input type="number" name="delivery_time_min" id="delivery_time_min" value="{{ old('delivery_time_min', $shippingMethod->delivery_time_min) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 pl-12">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">يوم</span>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="delivery_time_max" class="block text-sm font-medium text-gray-700 mb-1">وقت التوصيل (إلى)</label>
                    <div class="relative">
                        <input type="number" name="delivery_time_max" id="delivery_time_max" value="{{ old('delivery_time_max', $shippingMethod->delivery_time_max) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 pl-12">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">يوم</span>
                        </div>
                    </div>
                </div>

                <!-- Sort Order -->
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">ترتيب العرض</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $shippingMethod->sort_order) }}"
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <!-- Status -->
                <div class="flex items-center pt-6">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $shippingMethod->is_active) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        <span class="mr-3 text-sm font-medium text-gray-700">مفعل</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('admin.shipping-zones.show', $shippingMethod->shipping_zone_id) }}" class="text-gray-600 hover:text-gray-900">إلغاء</a>
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                حفظ التعديلات
            </button>
        </div>
    </form>
</div>
@endsection
