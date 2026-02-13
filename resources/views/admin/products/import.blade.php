@extends('admin.layouts.app')

@section('title', 'استيراد المنتجات')
@section('page-title', 'استيراد منتجات من CSV')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.products.index') }}" class="flex items-center gap-2 text-gray-600 hover:text-gray-800 transition">
            <i class="fas fa-arrow-right"></i>
            <span>العودة للمنتجات</span>
        </a>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex items-center gap-3">
            <i class="fas fa-check-circle text-green-600 text-lg"></i>
            <p class="text-green-800 text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center gap-2 mb-2">
                <i class="fas fa-exclamation-circle text-red-600"></i>
                <p class="text-red-800 font-medium text-sm">حدث خطأ</p>
            </div>
            <ul class="text-red-700 text-sm list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Step 1: Download Template --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="bg-gradient-to-l from-blue-600 to-blue-700 px-6 py-4">
            <h3 class="text-white font-bold text-lg flex items-center gap-2">
                <span class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center text-sm">1</span>
                تحميل نموذج القالب
            </h3>
        </div>
        <div class="p-6">
            <p class="text-gray-600 mb-4">
                قم بتحميل القالب الجاهز بناءً على هيكل قاعدة البيانات. سيحتوي الملف على جميع الأعمدة المطلوبة مع صف مثال لتوضيح التنسيق المطلوب.
            </p>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                <h4 class="font-medium text-blue-900 mb-3 flex items-center gap-2">
                    <i class="fas fa-columns"></i>
                    الأعمدة المتاحة في القالب
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="font-semibold text-blue-900 mb-1.5">
                            <i class="fas fa-asterisk text-red-500 text-[10px]"></i> إجبارية
                        </p>
                        <ul class="space-y-1 text-blue-800">
                            <li><code class="bg-blue-100 px-1.5 py-0.5 rounded text-xs">name</code> — اسم المنتج</li>
                            <li><code class="bg-blue-100 px-1.5 py-0.5 rounded text-xs">price</code> — السعر الأساسي</li>
                        </ul>
                    </div>
                    <div>
                        <p class="font-semibold text-blue-900 mb-1.5">اختيارية</p>
                        <ul class="space-y-1 text-blue-800">
                            <li><code class="bg-blue-100 px-1.5 py-0.5 rounded text-xs">description</code> — الوصف</li>
                            <li><code class="bg-blue-100 px-1.5 py-0.5 rounded text-xs">sale_price</code> — سعر التخفيض</li>
                            <li><code class="bg-blue-100 px-1.5 py-0.5 rounded text-xs">stock</code> — الكمية المتاحة</li>
                            <li><code class="bg-blue-100 px-1.5 py-0.5 rounded text-xs">sku</code> — رمز المنتج</li>
                            <li><code class="bg-blue-100 px-1.5 py-0.5 rounded text-xs">barcode</code> — الباركود</li>
                            <li><code class="bg-blue-100 px-1.5 py-0.5 rounded text-xs">category_id</code> — رقم التصنيف</li>
                            <li><code class="bg-blue-100 px-1.5 py-0.5 rounded text-xs">brand_id</code> — رقم العلامة التجارية</li>
                            <li><code class="bg-blue-100 px-1.5 py-0.5 rounded text-xs">is_active</code> — نشط (1/0)</li>
                            <li><code class="bg-blue-100 px-1.5 py-0.5 rounded text-xs">is_featured</code> — مميز (1/0)</li>
                            <li><code class="bg-blue-100 px-1.5 py-0.5 rounded text-xs">type</code> — نوع المنتج</li>
                            <li><code class="bg-blue-100 px-1.5 py-0.5 rounded text-xs">sort_order</code> — الترتيب</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Categories & Brands Reference --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                @if(isset($categories) && $categories->count())
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <h5 class="font-medium text-gray-800 mb-2 flex items-center gap-2 text-sm">
                        <i class="fas fa-folder text-indigo-500"></i>
                        التصنيفات المتاحة (category_id)
                    </h5>
                    <div class="max-h-40 overflow-y-auto text-xs space-y-1">
                        @foreach($categories as $cat)
                            <div class="flex justify-between items-center py-1 border-b border-gray-100 last:border-0">
                                <span class="text-gray-700">{{ $cat->name_ar ?: $cat->name_en }}</span>
                                <span class="font-mono bg-gray-200 px-2 py-0.5 rounded">{{ $cat->id }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(isset($brands) && $brands->count())
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <h5 class="font-medium text-gray-800 mb-2 flex items-center gap-2 text-sm">
                        <i class="fas fa-tag text-indigo-500"></i>
                        العلامات التجارية (brand_id)
                    </h5>
                    <div class="max-h-40 overflow-y-auto text-xs space-y-1">
                        @foreach($brands as $brand)
                            <div class="flex justify-between items-center py-1 border-b border-gray-100 last:border-0">
                                <span class="text-gray-700">{{ $brand->name_ar ?: $brand->name_en ?: $brand->name }}</span>
                                <span class="font-mono bg-gray-200 px-2 py-0.5 rounded">{{ $brand->id }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <a href="{{ route('admin.products.template') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm shadow-sm">
                <i class="fas fa-file-download"></i>
                تحميل قالب CSV
            </a>
        </div>
    </div>

    {{-- Step 2: Upload CSV --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden" x-data="csvUpload()">
        <div class="bg-gradient-to-l from-indigo-600 to-indigo-700 px-6 py-4">
            <h3 class="text-white font-bold text-lg flex items-center gap-2">
                <span class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center text-sm">2</span>
                رفع ملف CSV
            </h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.products.import') }}" 
                  method="POST" 
                  enctype="multipart/form-data"
                  @submit="uploading = true"
                  class="space-y-5">
                @csrf

                {{-- Drop Zone --}}
                <div class="border-2 border-dashed rounded-xl p-8 text-center transition-all cursor-pointer"
                     :class="file ? 'border-green-400 bg-green-50' : (dragover ? 'border-indigo-500 bg-indigo-50' : 'border-gray-300 hover:border-indigo-400 bg-gray-50')"
                     @click="$refs.fileInput.click()"
                     @dragover.prevent="dragover = true"
                     @dragleave.prevent="dragover = false"
                     @drop.prevent="handleDrop($event)">
                    
                    <input type="file" 
                           name="file" 
                           accept=".csv,text/csv" 
                           required
                           x-ref="fileInput"
                           @change="file = $event.target.files[0]"
                           class="hidden">

                    <template x-if="!file">
                        <div class="space-y-3">
                            <div class="w-16 h-16 mx-auto rounded-full bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400"></i>
                            </div>
                            <p class="text-gray-600 font-medium">اسحب ملف CSV هنا أو انقر للاختيار</p>
                            <p class="text-xs text-gray-400">الحد الأقصى لحجم الملف: 5MB</p>
                        </div>
                    </template>

                    <template x-if="file">
                        <div class="space-y-3">
                            <div class="w-16 h-16 mx-auto rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-file-csv text-3xl text-green-600"></i>
                            </div>
                            <p class="text-gray-800 font-bold" x-text="file.name"></p>
                            <p class="text-sm text-gray-500" x-text="`${(file.size / 1024).toFixed(2)} KB`"></p>
                            <button type="button" 
                                    @click.stop="file = null; $refs.fileInput.value = ''"
                                    class="text-red-600 hover:text-red-800 text-sm font-medium">
                                <i class="fas fa-times ml-1"></i> إزالة الملف
                            </button>
                        </div>
                    </template>
                </div>

                {{-- Info --}}
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-lightbulb text-amber-600 mt-0.5"></i>
                        <div class="text-sm text-amber-800 space-y-1">
                            <p><strong>ملاحظات:</strong></p>
                            <ul class="list-disc list-inside space-y-0.5">
                                <li>إذا كان المنتج موجود مسبقاً (بنفس الـ id أو sku) سيتم تحديثه</li>
                                <li>يُقبل الفاصل <code>,</code> أو <code>;</code> تلقائياً</li>
                                <li>الأعمدة الإجبارية فقط: <strong>name</strong> و <strong>price</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.products.index') }}" 
                       class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium">
                        إلغاء
                    </a>
                    <button type="submit" 
                            :disabled="uploading || !file"
                            class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center gap-2 text-sm font-medium shadow-sm">
                        <template x-if="!uploading">
                            <i class="fas fa-upload"></i>
                        </template>
                        <template x-if="uploading">
                            <i class="fas fa-spinner fa-spin"></i>
                        </template>
                        <span x-text="uploading ? 'جاري الاستيراد...' : 'بدء الاستيراد'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function csvUpload() {
    return {
        file: null,
        dragover: false,
        uploading: false,
        handleDrop(event) {
            this.dragover = false;
            const files = event.dataTransfer.files;
            if (files.length > 0 && (files[0].name.endsWith('.csv') || files[0].type === 'text/csv')) {
                this.file = files[0];
                this.$refs.fileInput.files = files;
            }
        }
    }
}
</script>
@endpush
@endsection
