@extends('admin.layouts.app')

@section('title', 'تعديل المنتج')
@section('page-title', 'تعديل المنتج: ' . ($product->name_ar ?: $product->name_en))

@section('content')
<div x-data="{ 
    activeTab: 'general',
    productType: '{{ old('product_type', $product->product_type) }}'
}">

    <!-- Tabs Header -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8 space-x-reverse overflow-x-auto" aria-label="Tabs">
            <button @click.prevent="activeTab = 'general'" 
                    :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'general', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'general' }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none">
                معلومات عامة
            </button>

            <button @click.prevent="activeTab = 'data'" 
                    :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'data', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'data' }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none">
                البيانات والمخزون
            </button>

            <button @click.prevent="activeTab = 'media'" 
                    :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'media', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'media' }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none">
                الصور والفيديو
            </button>

            <button x-show="productType === 'variable'"
                    @click.prevent="activeTab = 'variants'" 
                    :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'variants', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'variants' }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none">
                المتغيرات
            </button>

            <button @click.prevent="activeTab = 'reviews'" 
                    :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'reviews', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'reviews' }"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none">
                المراجعات
            </button>
        </nav>
    </div>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="main-form" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- General Tab -->
        <div x-show="activeTab === 'general'" class="space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">معلومات أساسية</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name_ar" class="block text-sm font-medium text-gray-700 mb-1">اسم المنتج عربي *</label>
                        <input type="text" name="name_ar" id="name_ar" value="{{ old('name_ar', $product->name_ar) }}" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('name_ar')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="name_en" class="block text-sm font-medium text-gray-700 mb-1">اسم المنتج إنجليزي</label>
                        <input type="text" name="name_en" id="name_en" value="{{ old('name_en', $product->name_en) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @error('name_en')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="short_description_ar" class="block text-sm font-medium text-gray-700 mb-1">الوصف المختصر عربي</label>
                        <textarea name="short_description_ar" id="short_description_ar" rows="2"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('short_description_ar', $product->short_description_ar) }}</textarea>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="short_description_en" class="block text-sm font-medium text-gray-700 mb-1">الوصف المختصر إنجليزي</label>
                        <textarea name="short_description_en" id="short_description_en" rows="2"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('short_description_en', $product->short_description_en) }}</textarea>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="description_ar" class="block text-sm font-medium text-gray-700 mb-1">الوصف الكامل عربي</label>
                        <textarea name="description_ar" id="description_ar" rows="4"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('description_ar', $product->description_ar) }}</textarea>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="description_en" class="block text-sm font-medium text-gray-700 mb-1">الوصف الكامل إنجليزي</label>
                        <textarea name="description_en" id="description_en" rows="4"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('description_en', $product->description_en) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Tab -->
        <div x-show="activeTab === 'data'" class="space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">التصنيف والنوع</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="product_type" class="block text-sm font-medium text-gray-700 mb-1">نوع المنتج *</label>
                        <select name="product_type" id="product_type" x-model="productType" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                            <option value="simple">منتج بسيط</option>
                            <option value="variable">منتج متغير</option>
                        </select>
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">التصنيف *</label>
                        <select name="category_id" id="category_id" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                            <option value="">اختر التصنيف</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name_ar ?: $category->name_en }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="brand_id" class="block text-sm font-medium text-gray-700 mb-1">العلامة التجارية</label>
                        <select name="brand_id" id="brand_id"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                            <option value="">اختر العلامة التجارية</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name_ar ?: $brand->name_en ?: $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tags Section -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                        @if(isset($tagGroups) && $tagGroups->count())
                            <div class="space-y-4 border rounded-lg p-4 bg-gray-50">
                                @foreach($tagGroups as $group)
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 uppercase mb-2">{{ $group->name_ar ?: $group->name_en }}</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($group->options as $opt)
                                                <label class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-gray-300 bg-white text-sm cursor-pointer hover:border-indigo-400">
                                                    <input type="checkbox" name="tag_options[]" value="{{ $opt->id }}"
                                                           class="rounded text-indigo-600 focus:ring-indigo-500"
                                                           {{ $product->tagOptions->contains($opt->id) ? 'checked' : '' }}>
                                                    <span class="text-gray-700">{{ $opt->name_ar ?: $opt->name_en }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">لا توجد Tags متاحة.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">السعر والمخزون</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">السعر الأساسي *</label>
                        <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="sale_price" class="block text-sm font-medium text-gray-700 mb-1">سعر التخفيض</label>
                        <input type="number" name="sale_price" id="sale_price" value="{{ old('sale_price', $product->sale_price) }}" step="0.01" min="0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-1">الكمية المتاحة *</label>
                        <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                        <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label for="low_stock_threshold" class="block text-sm font-medium text-gray-700 mb-1">حد التنبيه للكمية المنخفضة</label>
                        <input type="number" name="low_stock_threshold" id="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold ?? 5) }}" min="0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">الإعدادات</h3>
                <div class="flex items-center gap-6">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <span class="mr-2 text-sm text-gray-700">نشط</span>
                    </label>
                    
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                               class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <span class="mr-2 text-sm text-gray-700">مميز</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Media Tab -->
        <div x-show="activeTab === 'media'" class="space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">صور المنتج</h3>
                
                <!-- Existing Images -->
                @if($product->images->count() > 0)
                    <div class="mb-6">
                        <p class="text-sm font-medium text-gray-700 mb-3">الصور الحالية</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($product->images->sortBy('sort_order') as $image)
                                <div class="relative group rounded-lg overflow-hidden border border-gray-200">
                                    <img src="{{ asset('storage/' . $image->path) }}" class="w-full h-32 object-cover">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition flex items-center justify-center opacity-0 group-hover:opacity-100">
                                        <button type="submit" form="delete-image-{{ $image->id }}" 
                                                class="bg-red-500 text-white rounded-full p-2 hover:bg-red-600 transition"
                                                onclick="return confirm('هل أنت متأكد من حذف هذه الصورة؟')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    @if($image->is_primary)
                                        <span class="absolute top-1 right-1 bg-indigo-600 text-white text-[10px] px-2 py-0.5 rounded-full">رئيسية</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- New Images Upload -->
                <div x-data="imageUpload()" class="space-y-4">
                    <label class="block text-sm font-medium text-gray-700">إضافة صور جديدة</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-right hover:border-indigo-500 transition cursor-pointer"
                         @click="$refs.fileInput.click()"
                         @dragover.prevent="dragover = true"
                         @dragleave.prevent="dragover = false"
                         @drop.prevent="handleDrop($event)"
                         :class="{ 'border-indigo-500 bg-indigo-50': dragover }">
                        <input type="file" name="images[]" multiple accept="image/*" x-ref="fileInput" @change="handleFiles($event)" class="hidden">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-600">اسحب الصور هنا أو انقر للاختيار</p>
                        <p class="text-sm text-gray-400 mt-1">PNG, JPG, GIF حتى 2MB</p>
                    </div>
                    
                    <div x-show="previews.length > 0" class="grid grid-cols-4 gap-4">
                        <template x-for="(preview, index) in previews" :key="index">
                            <div class="relative">
                                <img :src="preview" class="w-full h-24 object-cover rounded-lg">
                                <button type="button" @click="removeImage(index)" 
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
                                    &times;
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">فيديو المنتج</h3>
                <div>
                    <label for="video" class="block text-sm font-medium text-gray-700 mb-1">رفع فيديو جديد</label>
                    <input type="file" name="video" id="video" accept="video/mp4,video/webm,video/ogg"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    <p class="text-xs text-gray-500 mt-1">الحد الأقصى 10MB، يفضل MP4 قصير</p>
                </div>
            </div>
        </div>

        <!-- Variants Tab (INSIDE the form to keep Alpine scope) -->
        <div x-show="activeTab === 'variants'" class="space-y-6">
            <div class="bg-white rounded-lg shadow p-6" id="variants-section">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">متغيرات المنتج</h3>
                    <button type="button" id="btn-add-variant"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700">
                        <i class="fas fa-plus"></i> إضافة متغير
                    </button>
                </div>

                @php
                    $attrs = $attributes->filter(fn($a) => $a->is_active)->values();
                @endphp

                @if($attrs->isEmpty())
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-sm text-yellow-800">
                        لا توجد خصائص نشطة. يمكنك إنشاء خصائص من قسم "خصائص المنتجات".
                    </div>
                @else

                <!-- Existing Variants List -->
                <div id="variants-list" class="space-y-3 mb-4">
                    @forelse($product->variants as $variant)
                    <div class="variant-row rounded-xl border border-gray-200" data-id="{{ $variant->id }}">
                        <div class="px-4 py-3 bg-gray-50 flex items-center justify-between rounded-t-xl">
                            <div class="text-sm font-medium text-gray-700">
                                @foreach($variant->attributeValues as $av)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-indigo-100 text-indigo-800 ml-1">
                                        {{ $av->attribute->name_ar ?? $av->attribute->name ?? '' }}: {{ $av->value }}
                                    </span>
                                @endforeach
                                @if($variant->sku)
                                    <span class="text-gray-400 text-xs mr-2">SKU: {{ $variant->sku }}</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" class="btn-edit-variant text-indigo-600 hover:text-indigo-700 text-sm"
                                        data-id="{{ $variant->id }}"
                                        data-sku="{{ $variant->sku }}"
                                        data-price="{{ $variant->price }}"
                                        data-sale_price="{{ $variant->sale_price }}"
                                        data-stock="{{ $variant->stock_quantity }}"
                                        data-is_active="{{ $variant->is_active ? '1' : '0' }}"
                                        data-image="{{ $variant->image ? asset('storage/'.$variant->image) : '' }}"
                                        data-image-raw="{{ $variant->image ?? '' }}"
                                        data-attributes='{{ json_encode($variant->attributeValues->mapWithKeys(fn($av) => [$av->product_attribute_id => $av->value])) }}'>
                                    <i class="fas fa-edit ml-1"></i> تعديل
                                </button>
                                <button type="button" class="btn-delete-variant text-red-600 hover:text-red-700 text-sm"
                                        data-id="{{ $variant->id }}">
                                    <i class="fas fa-trash ml-1"></i> حذف
                                </button>
                            </div>
                        </div>
                        <div class="px-4 py-2 grid grid-cols-4 gap-4 text-sm text-gray-600">
                            @if($variant->image)
                            <div>
                                <img src="{{ asset('storage/'.$variant->image) }}" class="w-12 h-12 object-cover rounded-lg border border-gray-200">
                            </div>
                            @endif
                            <div>السعر: <strong>{{ $variant->price ? number_format($variant->price, 2) : '—' }}</strong></div>
                            <div>المخزون: <strong class="{{ $variant->stock_quantity > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $variant->stock_quantity }}</strong></div>
                            <div>
                                <span class="px-2 py-0.5 rounded-full text-xs {{ $variant->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $variant->is_active ? 'نشط' : 'معطّل' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div id="no-variants-msg" class="border border-dashed border-gray-300 rounded-xl p-6 text-center text-gray-500">
                        لا توجد متغيرات بعد. اضغط "إضافة متغير".
                    </div>
                    @endforelse
                </div>

                <!-- Add/Edit Variant Form (hidden by default) -->
                <div id="variant-form-container" class="hidden rounded-xl border-2 border-indigo-200 bg-indigo-50 p-5">
                    <h4 class="font-semibold text-gray-800 mb-4" id="variant-form-title">إضافة متغير جديد</h4>
                    <input type="hidden" id="variant-id" value="">
                    <input type="hidden" id="variant-existing-image" value="">

                    <!-- Variant Image -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">صورة المتغير (اختياري)</label>
                        <div class="flex items-start gap-4">
                            <div id="v-image-preview-wrap" class="w-24 h-24 rounded-xl border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden bg-gray-50 flex-shrink-0 cursor-pointer select-none">
                                <img id="v-image-preview" src="" alt="" class="hidden w-full h-full object-cover rounded-xl">
                                <i id="v-image-placeholder" class="fas fa-image text-gray-300 text-3xl"></i>
                            </div>
                            <div class="flex-1">
                                <input type="file" id="v-image" accept="image/*" class="w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 cursor-pointer">
                                <p class="text-xs text-gray-400 mt-1">PNG, JPG, WEBP حتى 2MB</p>
                                <button type="button" id="btn-remove-variant-image" class="hidden mt-2 text-xs text-red-500 hover:text-red-700">
                                    <i class="fas fa-times ml-1"></i>حذف الصورة
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                            <input type="text" id="v-sku" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="اختياري">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">السعر *</label>
                            <input type="number" id="v-price" step="0.01" min="0" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">سعر التخفيض</label>
                            <input type="number" id="v-sale-price" step="0.01" min="0" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="اختياري">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الكمية *</label>
                            <input type="number" id="v-stock" min="0" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="0" value="0">
                        </div>
                        <div class="flex items-end">
                            <label class="flex items-center cursor-pointer gap-2">
                                <input type="checkbox" id="v-is-active" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" checked>
                                <span class="text-sm text-gray-700">نشط</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">الخصائص *</p>
                        <div class="grid grid-cols-1 md:grid-cols-{{ max(1, min(3, $attrs->count())) }} gap-3" id="variant-attributes-fields">
                            @foreach($attrs as $attr)
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">{{ $attr->name_ar ?? $attr->name }}</label>
                                @php $options = is_array($attr->options) ? $attr->options : []; @endphp
                                @if(!empty($options))
                                <select class="v-attr w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                        data-attr-id="{{ $attr->id }}">
                                    <option value="">— اختر —</option>
                                    @foreach($options as $opt)
                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                    @endforeach
                                </select>
                                @else
                                <input type="text" class="v-attr w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                       data-attr-id="{{ $attr->id }}" placeholder="أدخل قيمة">
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div id="variant-form-error" class="hidden mb-3 p-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg"></div>

                    <div class="flex items-center gap-3">
                        <button type="button" id="btn-save-variant"
                                class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium">
                            <i class="fas fa-save ml-1"></i> حفظ المتغير
                        </button>
                        <button type="button" id="btn-cancel-variant"
                                class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm">
                            إلغاء
                        </button>
                    </div>
                </div>

                @endif
            </div>

            <!-- Reviews Tab -->
            <div x-show="activeTab === 'reviews'" class="space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">مراجعات العملاء</h3>
                    
                    @if($product->approvedReviews()->count() > 0)
                    <div class="space-y-4">
                        @foreach($product->approvedReviews()->with('user')->get() as $review)
                        <div class="border rounded-lg p-4 {{ $review->is_approved ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }}">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="font-medium text-gray-900">{{ $review->user->name }}</span>
                                        <div class="flex">
                                            @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                                            @endfor
                                        </div>
                                        <span class="text-xs text-gray-500">{{ $review->created_at->format('Y-m-d H:i') }}</span>
                                    </div>
                                    @if($review->comment)
                                    <p class="text-gray-700 text-sm">{{ $review->comment }}</p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($review->is_approved)
                                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">موافق</span>
                                    @else
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full">في انتظار الموافقة</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-star text-4xl mb-3"></i>
                        <p>لا توجد مراجعات لهذا المنتج بعد</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="mt-8 flex items-center gap-4 border-t pt-6">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-save ml-2"></i> حفظ التغييرات
            </button>
            <a href="{{ route('admin.products.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                إلغاء
            </a>
        </div>
    </form>

</div>

<!-- Hidden forms for image deletion -->
@foreach($product->images as $image)
    <form id="delete-image-{{ $image->id }}" action="{{ route('admin.products.delete-image', ['product' => $product->id, 'image' => $image->id]) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
@endforeach

@push('scripts')
<script>
function imageUpload() {
    return {
        dragover: false,
        previews: [],
        files: [],
        handleFiles(event) {
            this.addFiles(Array.from(event.target.files));
        },
        handleDrop(event) {
            this.dragover = false;
            this.addFiles(Array.from(event.dataTransfer.files).filter(f => f.type.startsWith('image/')));
        },
        addFiles(newFiles) {
            newFiles.forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => { this.previews.push(e.target.result); };
                reader.readAsDataURL(file);
                this.files.push(file);
            });
            this.syncInputFiles();
        },
        removeImage(index) {
            this.previews.splice(index, 1);
            this.files.splice(index, 1);
            this.syncInputFiles();
        },
        syncInputFiles() {
            if (!this.$refs.fileInput) return;
            const dt = new DataTransfer();
            this.files.forEach(f => dt.items.add(f));
            this.$refs.fileInput.files = dt.files;
        }
    }
}

// ========== Variants CRUD ==========
(function () {
    const productId = Number('{{ $product->id }}');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const baseUrl = `/admin/products/${productId}/variants`;

    const formContainer = document.getElementById('variant-form-container');
    const formTitle     = document.getElementById('variant-form-title');
    const variantIdEl   = document.getElementById('variant-id');
    const skuEl         = document.getElementById('v-sku');
    const priceEl       = document.getElementById('v-price');
    const salePriceEl   = document.getElementById('v-sale-price');
    const stockEl       = document.getElementById('v-stock');
    const isActiveEl    = document.getElementById('v-is-active');
    const errorBox      = document.getElementById('variant-form-error');
    const list          = document.getElementById('variants-list');

    function showError(msg) {
        errorBox.textContent = msg;
        errorBox.classList.remove('hidden');
    }
    function hideError() { errorBox.classList.add('hidden'); }

    const imageEl          = document.getElementById('v-image');
    const imagePreview     = document.getElementById('v-image-preview');
    const imagePlaceholder = document.getElementById('v-image-placeholder');
    const removeImageBtn   = document.getElementById('btn-remove-variant-image');
    const existingImageEl  = document.getElementById('variant-existing-image');
    const imageDropzone    = document.getElementById('v-image-preview-wrap');

    function setFileToInput(file) {
        if (!file || !imageEl) return;
        const dt = new DataTransfer();
        dt.items.add(file);
        imageEl.files = dt.files;
        imageEl.dispatchEvent(new Event('change'));
    }

    function setDropzoneActive(isActive) {
        if (!imageDropzone) return;
        imageDropzone.classList.toggle('border-indigo-400', isActive);
        imageDropzone.classList.toggle('bg-indigo-50', isActive);
    }

    // Dropzone click-to-upload
    imageDropzone?.addEventListener('click', () => imageEl?.click());

    // Drag & Drop
    imageDropzone?.addEventListener('dragover', (e) => {
        e.preventDefault();
        setDropzoneActive(true);
    });
    imageDropzone?.addEventListener('dragleave', (e) => {
        e.preventDefault();
        setDropzoneActive(false);
    });
    imageDropzone?.addEventListener('dragend', (e) => {
        e.preventDefault();
        setDropzoneActive(false);
    });
    imageDropzone?.addEventListener('drop', (e) => {
        e.preventDefault();
        setDropzoneActive(false);
        const file = e.dataTransfer?.files?.[0];
        if (file && file.type && file.type.startsWith('image/')) {
            setFileToInput(file);
        }
    });

    // Image preview
    imageEl?.addEventListener('change', () => {
        const file = imageEl.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            imagePreview.src = e.target.result;
            imagePreview.classList.remove('hidden');
            imagePlaceholder.classList.add('hidden');
            removeImageBtn.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    });

    // Remove image
    removeImageBtn?.addEventListener('click', () => {
        imageEl.value = '';
        imagePreview.src = '';
        imagePreview.classList.add('hidden');
        imagePlaceholder.classList.remove('hidden');
        removeImageBtn.classList.add('hidden');
        existingImageEl.value = '';
    });

    function setImagePreview(url, raw) {
        existingImageEl.value = raw || '';
        if (url) {
            imagePreview.src = url;
            imagePreview.classList.remove('hidden');
            imagePlaceholder.classList.add('hidden');
            removeImageBtn.classList.remove('hidden');
        } else {
            imagePreview.src = '';
            imagePreview.classList.add('hidden');
            imagePlaceholder.classList.remove('hidden');
            removeImageBtn.classList.add('hidden');
        }
    }

    function resetForm() {
        variantIdEl.value = '';
        skuEl.value = '';
        priceEl.value = '';
        salePriceEl.value = '';
        stockEl.value = 0;
        isActiveEl.checked = true;
        imageEl.value = '';
        setImagePreview('', '');
        document.querySelectorAll('.v-attr').forEach(el => el.value = '');
        hideError();
        formTitle.textContent = 'إضافة متغير جديد';
    }

    function openForm() {
        formContainer.classList.remove('hidden');
        formContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // Add variant button
    document.getElementById('btn-add-variant')?.addEventListener('click', () => {
        resetForm();
        openForm();
    });

    // Cancel button
    document.getElementById('btn-cancel-variant')?.addEventListener('click', () => {
        formContainer.classList.add('hidden');
        resetForm();
    });

    // Edit variant buttons (delegated)
    document.addEventListener('click', function (e) {
        const editBtn = e.target.closest('.btn-edit-variant');
        if (!editBtn) return;
        const d = editBtn.dataset;
        variantIdEl.value = d.id;
        skuEl.value = d.sku || '';
        priceEl.value = d.price || '';
        salePriceEl.value = d.sale_price || '';
        stockEl.value = d.stock || 0;
        isActiveEl.checked = d.is_active === '1';
        imageEl.value = '';
        setImagePreview(d.image || '', d['image-raw'] || '');
        const attrs = JSON.parse(d.attributes || '{}');
        document.querySelectorAll('.v-attr').forEach(el => {
            const attrId = el.dataset.attrId;
            el.value = attrs[attrId] ?? '';
        });
        formTitle.textContent = 'تعديل المتغير';
        hideError();
        openForm();
    });

    // Delete variant buttons (delegated)
    document.addEventListener('click', async function (e) {
        const delBtn = e.target.closest('.btn-delete-variant');
        if (!delBtn) return;
        if (!confirm('هل أنت متأكد من حذف هذا المتغير؟')) return;
        const id = delBtn.dataset.id;
        try {
            const res = await fetch(`${baseUrl}/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (data.status === 'success') {
                const row = document.querySelector(`.variant-row[data-id="${id}"]`);
                row?.remove();
                if (!list.querySelector('.variant-row')) {
                    list.innerHTML = '<div id="no-variants-msg" class="border border-dashed border-gray-300 rounded-xl p-6 text-center text-gray-500">لا توجد متغيرات بعد. اضغط "إضافة متغير".</div>';
                }
            } else {
                alert('حدث خطأ أثناء الحذف');
            }
        } catch (err) {
            alert('حدث خطأ في الاتصال');
        }
    });

    // Save variant
    document.getElementById('btn-save-variant')?.addEventListener('click', async () => {
        hideError();
        const id = variantIdEl.value;
        const attributes = {};
        document.querySelectorAll('.v-attr').forEach(el => {
            if (el.value.trim()) attributes[el.dataset.attrId] = el.value.trim();
        });

        if (!priceEl.value) { showError('السعر مطلوب'); return; }
        if (Object.keys(attributes).length === 0) { showError('يجب اختيار خاصية واحدة على الأقل'); return; }

        // Use FormData to support file upload
        const fd = new FormData();
        fd.append('sku', skuEl.value || '');
        fd.append('price', priceEl.value);
        fd.append('sale_price', salePriceEl.value || '');
        fd.append('stock_quantity', stockEl.value || 0);
        fd.append('is_active', isActiveEl.checked ? 1 : 0);
        Object.entries(attributes).forEach(([k, v]) => fd.append(`attributes[${k}]`, v));
        if (imageEl.files[0]) {
            fd.append('image', imageEl.files[0]);
        }
        // If editing and no new image, keep existing
        if (id && existingImageEl.value && !imageEl.files[0]) {
            fd.append('existing_image', existingImageEl.value);
        }

        const url = id ? `${baseUrl}/${id}` : baseUrl;
        // PUT doesn't support FormData in some browsers, use POST + _method
        if (id) fd.append('_method', 'PUT');

        try {
            const res = await fetch(id ? `${baseUrl}/${id}` : baseUrl, {
                method: id ? 'POST' : 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: fd
            });
            const data = await res.json();
            if (data.status === 'success') {
                window.location.reload();
            } else {
                const msgs = data.errors ? Object.values(data.errors).flat().join(', ') : (data.message || 'حدث خطأ');
                showError(msgs);
            }
        } catch (err) {
            showError('حدث خطأ في الاتصال بالخادم');
        }
    });
})();
</script>
@endpush
@endsection
