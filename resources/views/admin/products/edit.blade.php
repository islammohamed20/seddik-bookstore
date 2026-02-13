@extends('admin.layouts.app')

@section('title', 'تعديل منتج')
@section('page-title', 'تعديل المنتج')

@section('content')
<div class="max-w-6xl mx-auto" x-data="{
    tab: 'basic',
    loading: false,
    submitForm() {
        this.loading = true;
        // اختر الـ form الذي يحتوي على @submit.prevent (الـ form الرئيسي)
        setTimeout(() => {
            const forms = document.querySelectorAll('form');
            let mainForm = null;
            
            // ابحث عن form بـ action يحتوي على 'update'
            for (let form of forms) {
                if (form.action.includes('update') || form.action.includes('store')) {
                    mainForm = form;
                    break;
                }
            }
            
            // إذا لم تجد، استخدم آخر form
            if (!mainForm && forms.length > 0) {
                mainForm = forms[forms.length - 1];
            }
            
            if (mainForm) {
                mainForm.submit();
            }
        }, 50);
    }
}">

    {{-- Header --}}
    <div class="bg-white rounded-xl shadow-sm mb-6 p-5">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.products.index') }}"
                   class="w-10 h-10 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors"
                   title="العودة للقائمة">
                    <i class="fas fa-arrow-right text-gray-600"></i>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $product->name_ar }}</h2>
                    <div class="flex items-center gap-3 mt-1 text-sm">
                        <span class="text-gray-500">SKU: {{ $product->sku ?? '—' }}</span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            <i class="fas fa-circle text-[6px]"></i>
                            {{ $product->is_active ? 'نشط' : 'معطّل' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <a href="{{ route('products.show', $product->slug) }}" target="_blank"
                   class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                    <i class="fas fa-external-link-alt"></i>
                    <span>معاينة</span>
                </a>

                <form action="{{ route('admin.products.toggle-status', $product) }}" method="POST" class="flex-1 sm:flex-none">
                    @csrf
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors
                                {{ $product->is_active
                                    ? 'bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100'
                                    : 'bg-green-50 text-green-700 border border-green-200 hover:bg-green-100' }}">
                        <i class="fas fa-{{ $product->is_active ? 'eye-slash' : 'eye' }}"></i>
                        <span>{{ $product->is_active ? 'تعطيل' : 'تنشيط' }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
            <div class="flex items-center gap-2 mb-2">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
                <p class="text-red-800 font-semibold text-sm">يوجد أخطاء في البيانات المدخلة:</p>
            </div>
            <ul class="text-red-700 text-sm list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Main Form --}}
    <form action="{{ route('admin.products.update', $product) }}"
          method="POST"
          enctype="multipart/form-data"
          @submit.prevent="submitForm()">
        @csrf
        @method('PUT')

        {{-- Tabs --}}
        <div class="bg-white rounded-xl shadow-sm mb-6">
            <div class="border-b border-gray-200 px-2">
                <nav class="flex gap-1 overflow-x-auto" aria-label="Tabs">
                    @php
                        $tabs = [
                            'basic'    => ['icon' => 'fas fa-box',        'label' => 'المعلومات الأساسية'],
                            'details'  => ['icon' => 'fas fa-align-left', 'label' => 'الوصف والتفاصيل'],
                            'images'   => ['icon' => 'fas fa-images',     'label' => 'الصور'],
                            'settings' => ['icon' => 'fas fa-sliders-h',  'label' => 'الإعدادات'],
                        ];
                    @endphp
                    @foreach($tabs as $key => $t)
                        <button type="button"
                                @click="tab = '{{ $key }}'"
                                :class="tab === '{{ $key }}'
                                    ? 'border-indigo-600 text-indigo-600 bg-indigo-50/60'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                                class="flex items-center gap-2 px-4 py-3.5 border-b-2 text-sm font-medium whitespace-nowrap transition-colors">
                            <i class="{{ $t['icon'] }}"></i>
                            <span>{{ $t['label'] }}</span>
                            @if($key === 'images' && $product->images->count())
                                <span class="px-1.5 py-0.5 text-[10px] rounded-full bg-indigo-100 text-indigo-700">{{ $product->images->count() }}</span>
                            @endif
                        </button>
                    @endforeach
                </nav>
            </div>

            {{-- ===== Tab: Basic Info ===== --}}
            <div x-show="tab === 'basic'" class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">

                    {{-- Name AR --}}
                    <div>
                        <label for="name_ar" class="block text-sm font-medium text-gray-700 mb-1.5">اسم المنتج (عربي) <span class="text-red-500">*</span></label>
                        <input type="text" name="name_ar" id="name_ar"
                               value="{{ old('name_ar', $product->name_ar) }}" required
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="أدخل اسم المنتج بالعربية">
                        @error('name_ar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Name EN --}}
                    <div>
                        <label for="name_en" class="block text-sm font-medium text-gray-700 mb-1.5">اسم المنتج (إنجليزي)</label>
                        <input type="text" name="name_en" id="name_en"
                               value="{{ old('name_en', $product->name_en) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="Product name in English">
                        @error('name_en')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Category --}}
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1.5">التصنيف <span class="text-red-500">*</span></label>
                        <select name="category_id" id="category_id" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">— اختر التصنيف —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name_ar ?? $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Brand --}}
                    <div>
                        <label for="brand_id" class="block text-sm font-medium text-gray-700 mb-1.5">العلامة التجارية</label>
                        <select name="brand_id" id="brand_id"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">— بدون —</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name_ar ?? $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- SKU --}}
                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700 mb-1.5">رمز SKU</label>
                        <input type="text" name="sku" id="sku"
                               value="{{ old('sku', $product->sku) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="PRD-001">
                        @error('sku')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Stock --}}
                    <div>
                        <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-1.5">الكمية المتاحة <span class="text-red-500">*</span></label>
                        <input type="number" name="stock_quantity" id="stock_quantity" min="0" required
                               value="{{ old('stock_quantity', $product->stock_quantity) }}"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('stock_quantity')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                </div>

                {{-- Regional Pricing --}}
                <div class="border border-gray-200 rounded-xl p-5 bg-gray-50/50">
                    <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-indigo-600"></i>
                        أسعار حسب الموقع الجغرافي
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                        {{-- Price Inside Assiut --}}
                        <div>
                            <label for="price_inside_assiut" class="block text-sm font-medium text-gray-700 mb-1.5">
                                <i class="fas fa-city text-green-600 text-xs ml-1"></i> السعر داخل أسيوط <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="price_inside_assiut" id="price_inside_assiut" step="0.01" min="0" required
                                       value="{{ old('price_inside_assiut', $product->price_inside_assiut) }}"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 pl-14">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 pointer-events-none">ج.م</span>
                            </div>
                            @error('price_inside_assiut')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Price Outside Assiut --}}
                        <div>
                            <label for="price_outside_assiut" class="block text-sm font-medium text-gray-700 mb-1.5">
                                <i class="fas fa-globe-africa text-blue-600 text-xs ml-1"></i> السعر خارج أسيوط <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="price_outside_assiut" id="price_outside_assiut" step="0.01" min="0" required
                                       value="{{ old('price_outside_assiut', $product->price_outside_assiut) }}"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 pl-14">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 pointer-events-none">ج.م</span>
                            </div>
                            @error('price_outside_assiut')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Sale Price Inside Assiut --}}
                        <div>
                            <label for="sale_price_inside_assiut" class="block text-sm font-medium text-gray-700 mb-1.5">
                                <i class="fas fa-city text-green-600 text-xs ml-1"></i> سعر التخفيض داخل أسيوط
                            </label>
                            <div class="relative">
                                <input type="number" name="sale_price_inside_assiut" id="sale_price_inside_assiut" step="0.01" min="0"
                                       value="{{ old('sale_price_inside_assiut', $product->sale_price_inside_assiut) }}"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 pl-14"
                                       placeholder="فارغ = بدون تخفيض">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 pointer-events-none">ج.م</span>
                            </div>
                            @error('sale_price_inside_assiut')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Sale Price Outside Assiut --}}
                        <div>
                            <label for="sale_price_outside_assiut" class="block text-sm font-medium text-gray-700 mb-1.5">
                                <i class="fas fa-globe-africa text-blue-600 text-xs ml-1"></i> سعر التخفيض خارج أسيوط
                            </label>
                            <div class="relative">
                                <input type="number" name="sale_price_outside_assiut" id="sale_price_outside_assiut" step="0.01" min="0"
                                       value="{{ old('sale_price_outside_assiut', $product->sale_price_outside_assiut) }}"
                                       class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 pl-14"
                                       placeholder="فارغ = بدون تخفيض">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 pointer-events-none">ج.م</span>
                            </div>
                            @error('sale_price_outside_assiut')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== Tab: Details ===== --}}
            <div x-show="tab === 'details'" x-cloak class="p-6 space-y-5">
                <div>
                    <label for="short_description_ar" class="block text-sm font-medium text-gray-700 mb-1.5">وصف مختصر (عربي)</label>
                    <textarea name="short_description_ar" id="short_description_ar" rows="3"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                              placeholder="وصف قصير يظهر في قوائم المنتجات">{{ old('short_description_ar', $product->short_description_ar) }}</textarea>
                    @error('short_description_ar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="short_description_en" class="block text-sm font-medium text-gray-700 mb-1.5">وصف مختصر (إنجليزي)</label>
                    <textarea name="short_description_en" id="short_description_en" rows="3"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                              placeholder="Short description shown in listings">{{ old('short_description_en', $product->short_description_en) }}</textarea>
                    @error('short_description_en')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <hr class="border-gray-200">

                <div>
                    <label for="description_ar" class="block text-sm font-medium text-gray-700 mb-1.5">الوصف الكامل (عربي)</label>
                    <textarea name="description_ar" id="description_ar" rows="6"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                              placeholder="وصف تفصيلي كامل للمنتج">{{ old('description_ar', $product->description_ar) }}</textarea>
                    @error('description_ar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="description_en" class="block text-sm font-medium text-gray-700 mb-1.5">الوصف الكامل (إنجليزي)</label>
                    <textarea name="description_en" id="description_en" rows="6"
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                              placeholder="Full detailed product description">{{ old('description_en', $product->description_en) }}</textarea>
                    @error('description_en')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- ===== Tab: Images ===== --}}
            <div x-show="tab === 'images'" x-cloak class="p-6 space-y-6">

                {{-- Current images --}}
                @if($product->images->count() > 0)
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <i class="fas fa-image text-indigo-600"></i>
                            الصور الحالية
                        </h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                            @foreach($product->images as $image)
                                <div class="relative group rounded-xl overflow-hidden bg-gray-100 aspect-square border border-gray-200">
                                    <img src="{{ asset('storage/' . $image->path) }}"
                                         alt="{{ $product->name_ar }}"
                                         class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">

                                    @if($loop->first)
                                        <span class="absolute top-2 right-2 px-2 py-0.5 bg-green-600 text-white text-[10px] font-bold rounded-full shadow">
                                            <i class="fas fa-star ml-0.5"></i> رئيسية
                                        </span>
                                    @endif

                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center p-3">
                                        <form action="{{ route('admin.products.delete-image', [$product, $image]) }}"
                                              method="POST"
                                              onsubmit="return confirm('هل تريد حذف هذه الصورة؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs rounded-lg transition-colors flex items-center gap-1.5 shadow-lg">
                                                <i class="fas fa-trash-alt"></i>
                                                حذف
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <hr class="border-gray-200">
                @endif

                {{-- Upload area --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-cloud-upload-alt text-indigo-600"></i>
                        إضافة صور جديدة
                    </h4>
                    <label for="images"
                           class="group border-2 border-dashed border-gray-300 rounded-xl p-10 flex flex-col items-center justify-center gap-3 cursor-pointer transition-all hover:border-indigo-400 hover:bg-indigo-50/50 bg-gray-50">
                        <input type="file" name="images[]" id="images" multiple accept="image/*" class="hidden">
                        <div class="w-14 h-14 rounded-full bg-indigo-100 group-hover:bg-indigo-200 transition-colors flex items-center justify-center">
                            <i class="fas fa-cloud-upload-alt text-2xl text-indigo-600"></i>
                        </div>
                        <p class="text-gray-700 font-medium">اسحب الصور هنا أو انقر للاختيار</p>
                        <p class="text-xs text-gray-400">PNG, JPG, GIF, WebP — حتى 2MB لكل صورة</p>
                    </label>
                </div>
            </div>

            {{-- ===== Tab: Settings ===== --}}
            <div x-show="tab === 'settings'" x-cloak class="p-6 space-y-6">

                {{-- Toggles --}}
                <div class="space-y-3">
                    <h4 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                        <i class="fas fa-toggle-on text-indigo-600"></i>
                        حالة المنتج
                    </h4>

                    <label class="flex items-center justify-between p-4 bg-white rounded-xl border border-gray-200 cursor-pointer hover:border-green-300 transition-colors group">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-green-100 group-hover:bg-green-200 transition-colors flex items-center justify-center">
                                <i class="fas fa-eye text-green-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 text-sm">نشط ومرئي</p>
                                <p class="text-xs text-gray-500">يظهر في المتجر للعملاء</p>
                            </div>
                        </div>
                        <input type="checkbox" name="is_active" value="1"
                               {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                               class="w-5 h-5 rounded text-green-600 border-gray-300 focus:ring-green-500 cursor-pointer">
                    </label>

                    <label class="flex items-center justify-between p-4 bg-white rounded-xl border border-gray-200 cursor-pointer hover:border-yellow-300 transition-colors group">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-yellow-100 group-hover:bg-yellow-200 transition-colors flex items-center justify-center">
                                <i class="fas fa-star text-yellow-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 text-sm">منتج مميز</p>
                                <p class="text-xs text-gray-500">يظهر في قسم المنتجات المميزة</p>
                            </div>
                        </div>
                        <input type="checkbox" name="is_featured" value="1"
                               {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                               class="w-5 h-5 rounded text-yellow-500 border-gray-300 focus:ring-yellow-500 cursor-pointer">
                    </label>
                </div>

                {{-- Stock info --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-boxes text-indigo-600"></i>
                        حالة المخزون
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @php
                            $statusMap = [
                                'in_stock'    => ['label' => 'متوفر',       'color' => 'green',  'icon' => 'check-circle'],
                                'low_stock'   => ['label' => 'مخزون منخفض', 'color' => 'yellow', 'icon' => 'exclamation-triangle'],
                                'out_of_stock'=> ['label' => 'غير متوفر',  'color' => 'red',    'icon' => 'times-circle'],
                            ];
                            $st = $statusMap[$product->stock_status] ?? $statusMap['out_of_stock'];
                        @endphp
                        <div class="p-4 rounded-xl border-r-4 border-{{ $st['color'] }}-500 bg-{{ $st['color'] }}-50">
                            <p class="text-xs text-gray-500 mb-1">الحالة</p>
                            <p class="font-bold text-{{ $st['color'] }}-700 flex items-center gap-1">
                                <i class="fas fa-{{ $st['icon'] }}"></i>
                                {{ $st['label'] }}
                            </p>
                        </div>
                        <div class="p-4 rounded-xl bg-gray-50">
                            <p class="text-xs text-gray-500 mb-1">الكمية</p>
                            <p class="font-bold text-gray-800">{{ $product->stock_quantity }} وحدة</p>
                        </div>
                        <div class="p-4 rounded-xl bg-gray-50">
                            <p class="text-xs text-gray-500 mb-1">حدّ التنبيه</p>
                            <p class="font-bold text-gray-800">{{ $product->low_stock_threshold ?? 5 }} وحدة</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== Sticky Action Bar ===== --}}
        <div class="sticky bottom-4 z-20">
            <div class="bg-white rounded-xl shadow-xl ring-1 ring-gray-200 px-6 py-4 flex items-center justify-between gap-4">
                <p class="hidden sm:flex items-center gap-2 text-sm text-gray-500">
                    <i class="fas fa-info-circle text-indigo-500"></i>
                    تذكّر حفظ التغييرات قبل المغادرة
                </p>

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <a href="{{ route('admin.products.index') }}"
                       class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition-colors text-sm font-medium">
                        <i class="fas fa-arrow-right"></i>
                        <span>رجوع</span>
                    </a>

                    <button type="submit"
                            :disabled="loading"
                            class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-7 py-2.5 rounded-lg text-white text-sm font-medium shadow-lg transition-all
                                   bg-gradient-to-l from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 disabled:opacity-60 disabled:cursor-not-allowed">
                        <template x-if="!loading">
                            <i class="fas fa-save"></i>
                        </template>
                        <template x-if="loading">
                            <i class="fas fa-spinner fa-spin"></i>
                        </template>
                        <span x-text="loading ? 'جاري الحفظ...' : 'حفظ التعديلات'"></span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
