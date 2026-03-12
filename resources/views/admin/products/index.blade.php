@extends('admin.layouts.app')

@section('title', isset($teckToysMode) && $teckToysMode ? 'منتجات تيك تويز' : 'المنتجات')
@section('page-title', isset($teckToysMode) && $teckToysMode ? 'منتجات تيك تويز' : 'إدارة المنتجات')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-admin.page-header 
        :title="(isset($teckToysMode) && $teckToysMode) ? 'منتجات تيك تويز' : 'إدارة المنتجات'"
        :breadcrumbs="[['title' => (isset($teckToysMode) && $teckToysMode) ? 'منتجات تيك تويز' : 'المنتجات']]">
        <x-slot name="actions">
            <div x-data="{ showTemplateInfo: false }" class="inline">
                <a href="{{ route('admin.products.template') }}"
                   @click.prevent="showTemplateInfo = true"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition">
                    <i class="fas fa-file-excel text-green-600"></i>
                    نموذج Excel/CSV
                </a>

                <div x-show="showTemplateInfo" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
                    <div class="absolute inset-0 bg-black/50" @click="showTemplateInfo = false"></div>
                    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-gray-800">تنويه قبل تنزيل القالب</h3>
                            <button class="w-10 h-10 rounded-lg hover:bg-gray-100 flex items-center justify-center text-gray-600"
                                    @click="showTemplateInfo = false">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="p-6 space-y-4 text-sm text-gray-700">
                            <p class="text-gray-700">
                                لاستخدام عمود <span class="font-semibold">category_id</span> داخل القالب بشكل صحيح،
                                اختر رقم التصنيف من القائمة التالية.
                            </p>
                            @if(isset($categories) && $categories->count())
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <h4 class="font-medium text-gray-800 mb-2 flex items-center gap-2">
                                    <i class="fas fa-folder text-indigo-500"></i>
                                    التصنيفات وأرقامها
                                </h4>
                                <div class="max-h-56 overflow-y-auto space-y-1">
                                    @foreach($categories as $cat)
                                        <div class="flex justify-between items-center py-1 border-b border-gray-100 last:border-0">
                                            <span class="text-gray-700">{{ $cat->name_ar ?: $cat->name_en }}</span>
                                            <span class="font-mono bg-gray-200 px-2 py-0.5 rounded">{{ $cat->id }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            <p class="text-gray-700">
                                يمكنك ترك <span class="font-semibold">category_id</span> فارغًا إذا لم ترغب في تعيين تصنيف.
                                كذلك <span class="font-semibold">brand_id</span> اختياري ويرتبط بأرقام العلامات التجارية.
                            </p>
                        </div>
                        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-2">
                            <button class="px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                                    @click="showTemplateInfo = false">
                                فهمت
                            </button>
                            <button class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                                    @click="window.location.href='{{ route('admin.products.template') }}'; showTemplateInfo = false">
                                تنزيل القالب الآن
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <form action="{{ route('admin.products.export') }}" method="GET" class="inline">
                @foreach(request()->except('page') as $key => $value)
                    @if(!is_array($value))
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <x-admin.button type="submit" variant="secondary" icon="fas fa-download">
                    تصدير CSV
                </x-admin.button>
            </form>

            <x-admin.button href="{{ route('admin.products.import-page') }}" variant="secondary" icon="fas fa-upload">
                استيراد CSV
            </x-admin.button>

            <x-admin.button 
                href="{{ isset($teckToysMode) && $teckToysMode && isset($teckToysCategory) && $teckToysCategory ? route('admin.products.create', ['category_id' => $teckToysCategory->id]) : route('admin.products.create') }}"
                variant="primary" 
                icon="fas fa-plus">
                {{ isset($teckToysMode) && $teckToysMode ? 'إضافة منتج تيك تويز' : 'إضافة منتج' }}
            </x-admin.button>
        </x-slot>
    </x-admin.page-header>
    
    <div class="text-sm text-gray-600 bg-white rounded-lg shadow px-4 py-3">
        <i class="fas fa-info-circle ml-1"></i>
        إجمالي <strong>{{ $products->total() }}</strong> منتج
    </div>
    
    <!-- Filters -->
    <x-admin.card title="البحث والفلترة">
        <form action="{{ isset($teckToysMode) && $teckToysMode ? route('admin.products.teck-toys') : route('admin.products.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="بحث بالاسم أو SKU..."
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            <div>
                <select name="category_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    <option value="">كل التصنيفات</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name_ar ?: $category->name_en }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="brand_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    <option value="">كل العلامات التجارية</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name_ar ?: $brand->name_en ?: $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="is_active" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    <option value="">كل الحالات</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>نشط</option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>غير نشط</option>
                </select>
            </div>
            <div class="flex gap-2">
                <x-admin.button type="submit" variant="secondary" class="flex-1">
                    <i class="fas fa-search ml-1"></i> بحث
                </x-admin.button>
                <x-admin.button 
                    variant="ghost"
                    href="{{ isset($teckToysMode) && $teckToysMode ? route('admin.products.teck-toys') : route('admin.products.index') }}">
                    <i class="fas fa-times"></i>
                </x-admin.button>
            </div>
        </form>
    </x-admin.card>

    
    <!-- Products Table (Desktop) -->
    <x-admin.card :padding="false" class="hidden md:block">
        <x-admin.table 
            :headers="['المنتج', 'التصنيف', 'السعر', 'سعر التخفيض', 'المخزون', 'الحالة', 'إجراءات']" 
            striped>
            @forelse($products as $product)
                <x-admin.table.row>
                    <x-admin.table.cell>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                @if($product->images->first())
                                    <img src="{{ asset('storage/' . $product->images->first()->path) }}" 
                                         alt="{{ $product->name_ar ?: $product->name_en }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $product->name_ar ?: $product->name_en }}</p>
                                <p class="text-xs text-gray-500">SKU: {{ $product->sku ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </x-admin.table.cell>
                    <x-admin.table.cell class="text-gray-600">
                        {{ $product->category?->name_ar ?? $product->category?->name_en ?? '-' }}
                    </x-admin.table.cell>
                    <x-admin.table.cell>
                        @if($product->price !== null)
                            <p class="font-medium">{{ number_format($product->price, 2) }} ج.م</p>
                        @else
                            <p class="text-sm text-gray-400">غير محدد</p>
                        @endif
                    </x-admin.table.cell>
                    <x-admin.table.cell>
                        @if($product->sale_price)
                            <p class="font-medium text-green-600">{{ number_format($product->sale_price, 2) }} ج.م</p>
                        @else
                            <p class="text-sm text-gray-400">—</p>
                        @endif
                    </x-admin.table.cell>
                    <x-admin.table.cell>
                        @php
                            $stockValue = $product->total_stock_quantity;
                        @endphp
                        @if($stockValue > 10)
                            <x-admin.badge variant="success">{{ $stockValue }}</x-admin.badge>
                        @elseif($stockValue > 0)
                            <x-admin.badge variant="warning">{{ $stockValue }}</x-admin.badge>
                        @else
                            <x-admin.badge variant="danger">نفذت الكمية</x-admin.badge>
                        @endif
                    </x-admin.table.cell>
                    <x-admin.table.cell>
                        <form action="{{ route('admin.products.toggle-status', $product) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit">
                                <x-admin.badge :variant="$product->is_active ? 'success' : 'danger'">
                                    {{ $product->is_active ? 'نشط' : 'غير نشط' }}
                                </x-admin.badge>
                            </button>
                        </form>
                    </x-admin.table.cell>
                    <x-admin.table.cell>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.products.edit', $product) }}" 
                               class="text-indigo-600 hover:text-indigo-800 transition"
                               title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline"
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-800 transition"
                                        title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </x-admin.table.cell>
                </x-admin.table.row>
            @empty
                <tr>
                    <td colspan="7">
                        <x-admin.empty-state 
                            title="لا توجد منتجات"
                            description="لم يتم إضافة أي منتجات بعد. ابدأ بإضافة منتج جديد"
                            icon="fas fa-box-open">
                            <x-slot name="action">
                                <x-admin.button 
                                    href="{{ route('admin.products.create') }}"
                                    variant="primary"
                                    icon="fas fa-plus">
                                    إضافة أول منتج
                                </x-admin.button>
                            </x-slot>
                        </x-admin.empty-state>
                    </td>
                </tr>
            @endforelse
        </x-admin.table>
        
        @if($products->hasPages())
            <x-slot name="footer">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        عرض {{ $products->firstItem() }} - {{ $products->lastItem() }} من {{ $products->total() }}
                    </div>
                    {{ $products->links() }}
                </div>
            </x-slot>
        @endif
    </x-admin.card>

    <!-- Products Cards (Mobile - Pro Slider) -->
    <div class="md:hidden">
        @if($products->count() === 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <x-admin.empty-state 
                    title="لا توجد منتجات"
                    description="لم يتم إضافة أي منتجات بعد. ابدأ بإضافة منتج جديد"
                    icon="fas fa-box-open">
                    <x-slot name="action">
                        <x-admin.button 
                            href="{{ route('admin.products.create') }}"
                            variant="primary"
                            icon="fas fa-plus">
                            إضافة أول منتج
                        </x-admin.button>
                    </x-slot>
                </x-admin.empty-state>
            </div>
        @else
            <div class="relative">
                <div class="pointer-events-none absolute left-0 top-0 bottom-0 w-6 bg-gradient-to-r from-gray-100 to-transparent z-10"></div>
                <div class="pointer-events-none absolute right-0 top-0 bottom-0 w-6 bg-gradient-to-l from-gray-100 to-transparent z-10"></div>
                <div class="flex gap-4 overflow-x-auto snap-x snap-mandatory -mx-4 px-4 pb-5" style="scrollbar-width: none; -webkit-overflow-scrolling: touch;">
                    @foreach($products as $product)
                        @php
                            $stockValue = $product->total_stock_quantity;
                        @endphp
                        <div class="min-w-[82%] snap-start">
                            <div class="bg-white rounded-2xl border border-indigo-100 shadow-lg overflow-hidden">
                                <div class="p-4 bg-gradient-to-l from-indigo-50 via-white to-white">
                                    <div class="flex items-start gap-3">
                                        <div class="w-16 h-16 bg-white rounded-xl overflow-hidden flex-shrink-0 border border-indigo-100 shadow-sm">
                                            @if($product->images->first())
                                                <img src="{{ asset('storage/' . $product->images->first()->path) }}" 
                                                     alt="{{ $product->name_ar ?: $product->name_en }}"
                                                     class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-indigo-300">
                                                    <i class="fas fa-image"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="font-bold text-gray-800 leading-5 line-clamp-2">
                                                {{ $product->name_ar ?: $product->name_en }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">SKU: {{ $product->sku ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ $product->category?->name_ar ?? $product->category?->name_en ?? '-' }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            @if($product->sale_price)
                                                <p class="text-sm font-bold text-green-600">
                                                    {{ number_format($product->sale_price, 2) }} ج.م
                                                </p>
                                                @if($product->price !== null)
                                                    <p class="text-xs text-gray-400 line-through">
                                                        {{ number_format($product->price, 2) }} ج.م
                                                    </p>
                                                @endif
                                            @elseif($product->price !== null)
                                                <p class="text-sm font-bold text-indigo-800">
                                                    {{ number_format($product->price, 2) }} ج.م
                                                </p>
                                            @else
                                                <p class="text-xs text-gray-400">غير محدد</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="px-4 pb-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            @if($stockValue > 10)
                                                <x-admin.badge variant="success">{{ $stockValue }}</x-admin.badge>
                                            @elseif($stockValue > 0)
                                                <x-admin.badge variant="warning">{{ $stockValue }}</x-admin.badge>
                                            @else
                                                <x-admin.badge variant="danger">نفذت الكمية</x-admin.badge>
                                            @endif
                                            <form action="{{ route('admin.products.toggle-status', $product) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit">
                                                    <x-admin.badge :variant="$product->is_active ? 'success' : 'danger'">
                                                        {{ $product->is_active ? 'نشط' : 'غير نشط' }}
                                                    </x-admin.badge>
                                                </button>
                                            </form>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.products.edit', $product) }}" 
                                               class="w-9 h-9 rounded-full bg-indigo-50 text-indigo-700 flex items-center justify-center shadow-sm hover:bg-indigo-100 transition"
                                               title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="w-9 h-9 rounded-full bg-red-50 text-red-700 flex items-center justify-center shadow-sm hover:bg-red-100 transition"
                                                        title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if($products->hasPages())
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <div class="flex flex-col gap-2 text-sm text-gray-600">
                        <div>
                            عرض {{ $products->firstItem() }} - {{ $products->lastItem() }} من {{ $products->total() }}
                        </div>
                        <div>
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>

@endsection
