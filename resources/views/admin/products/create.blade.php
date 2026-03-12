@extends('admin.layouts.app')

@section('title', 'إضافة منتج')
@section('page-title', 'إضافة منتج جديد')

@section('content')
<div class="max-w-4xl" x-data="{ productType: '{{ old('product_type', 'simple') }}' }">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" data-shortcut-save="true" id="main-form">
        @csrf
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">معلومات المنتج</h3>
            
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
                    <label for="name_ar" class="block text-sm font-medium text-gray-700 mb-1">اسم المنتج عربي *</label>
                    <input type="text" name="name_ar" id="name_ar" value="{{ old('name_ar') }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('name_ar')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="name_en" class="block text-sm font-medium text-gray-700 mb-1">اسم المنتج إنجليزي</label>
                    <input type="text" name="name_en" id="name_en" value="{{ old('name_en') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('name_en')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                    <input type="text" name="sku" id="sku" value="{{ old('sku') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">التصنيف *</label>
                    @php
                        $selectedCategoryId = old('category_id', request('category_id'));
                    @endphp
                    <select name="category_id" id="category_id" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                        <option value="">اختر التصنيف</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $selectedCategoryId == $category->id ? 'selected' : '' }}>
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
                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name_ar ?: $brand->name_en ?: $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">السعر الأساسي *</label>
                    <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                
                <div>
                    <label for="sale_price" class="block text-sm font-medium text-gray-700 mb-1">سعر التخفيض</label>
                    <input type="number" name="sale_price" id="sale_price" value="{{ old('sale_price') }}" step="0.01" min="0"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           placeholder="فارغ = بدون تخفيض">
                </div>
                
                <div>
                    <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-1">الكمية المتاحة *</label>
                    <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity', 1) }}" min="1" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('stock_quantity')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="low_stock_threshold" class="block text-sm font-medium text-gray-700 mb-1">حد التنبيه للكمية المنخفضة</label>
                    <input type="number" name="low_stock_threshold" id="low_stock_threshold" value="{{ old('low_stock_threshold', 5) }}" min="0"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">القيمة الافتراضية 5</p>
                </div>
                
                <div>
                    <label for="short_description_ar" class="block text-sm font-medium text-gray-700 mb-1">الوصف المختصر عربي</label>
                    <textarea name="short_description_ar" id="short_description_ar" rows="2"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('short_description_ar') }}</textarea>
                    @error('short_description_ar')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="short_description_en" class="block text-sm font-medium text-gray-700 mb-1">الوصف المختصر إنجليزي</label>
                    <textarea name="short_description_en" id="short_description_en" rows="2"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('short_description_en') }}</textarea>
                    @error('short_description_en')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="description_ar" class="block text-sm font-medium text-gray-700 mb-1">الوصف عربي</label>
                    <textarea name="description_ar" id="description_ar" rows="4"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('description_ar') }}</textarea>
                    @error('description_ar')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="description_en" class="block text-sm font-medium text-gray-700 mb-1">الوصف إنجليزي</label>
                    <textarea name="description_en" id="description_en" rows="4"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('description_en') }}</textarea>
                    @error('description_en')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        @if(view()->exists('admin.products.partials.variants-improved'))
            <div x-show="productType === 'variable'">
                @include('admin.products.partials.variants-improved')
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Tags</h3>
            @php
                $selectedTagOptions = old('tag_options', []);
            @endphp
            @if(isset($tagGroups) && $tagGroups->count())
                <div class="space-y-5">
                    @foreach($tagGroups as $group)
                        <div>
                            <p class="text-sm font-semibold text-gray-700 mb-2">{{ $group->name_ar ?: $group->name_en }}</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($group->options as $opt)
                                    <label class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-gray-300 text-sm cursor-pointer hover:border-indigo-400">
                                        <input type="checkbox" name="tag_options[]" value="{{ $opt->id }}"
                                               class="rounded text-indigo-600"
                                               {{ in_array($opt->id, $selectedTagOptions) ? 'checked' : '' }}>
                                        <span class="text-gray-700">{{ $opt->name_ar ?: $opt->name_en }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500">لا توجد Tags متاحة حالياً.</p>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">وسائط المنتج</h3>
            
            <div x-data="imageUpload()" class="space-y-4">
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

                <div class="mt-6">
                    <label for="video" class="block text-sm font-medium text-gray-700 mb-1">فيديو المنتج (اختياري)</label>
                    <input type="file" name="video" id="video" accept="video/mp4,video/webm,video/ogg"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    <p class="text-xs text-gray-500 mt-1">الحد الأقصى 10MB، يفضل MP4 قصير</p>
                    @error('video')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">الإعدادات</h3>
            
            <div class="flex items-center gap-6">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <span class="mr-2 text-sm text-gray-700">نشط</span>
                </label>
                
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <span class="mr-2 text-sm text-gray-700">مميز</span>
                </label>
            </div>
        </div>
        
        <div class="flex items-center gap-4">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-save ml-2"></i>حفظ المنتج
            </button>
            <a href="{{ route('admin.products.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                إلغاء
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function imageUpload() {
    return {
        dragover: false,
        previews: [],
        files: [],
        
        handleFiles(event) {
            const newFiles = Array.from(event.target.files);
            this.addFiles(newFiles);
        },
        
        handleDrop(event) {
            this.dragover = false;
            const newFiles = Array.from(event.dataTransfer.files).filter(f => f.type.startsWith('image/'));
            this.addFiles(newFiles);
        },
        
        addFiles(newFiles) {
            newFiles.forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previews.push(e.target.result);
                };
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

            const dataTransfer = new DataTransfer();
            this.files.forEach(file => dataTransfer.items.add(file));
            this.$refs.fileInput.files = dataTransfer.files;
        }
    }
}
</script>
@endpush
@endsection
