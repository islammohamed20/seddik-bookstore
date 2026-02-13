@extends('admin.layouts.app')

@section('title', 'المنتجات')
@section('page-title', 'إدارة المنتجات')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-admin.page-header 
        title="إدارة المنتجات"
        :breadcrumbs="[['title' => 'المنتجات']]">
        <x-slot name="actions">
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
                href="{{ route('admin.products.create') }}"
                variant="primary" 
                icon="fas fa-plus">
                إضافة منتج
            </x-admin.button>
        </x-slot>
    </x-admin.page-header>
    
    <div class="text-sm text-gray-600 bg-white rounded-lg shadow px-4 py-3">
        <i class="fas fa-info-circle ml-1"></i>
        إجمالي <strong>{{ $products->total() }}</strong> منتج
    </div>
    
    <!-- Filters -->
    <x-admin.card title="البحث والفلترة">
        <form action="{{ route('admin.products.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
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
                    href="{{ route('admin.products.index') }}">
                    <i class="fas fa-times"></i>
                </x-admin.button>
            </div>
        </form>
    </x-admin.card>

    
    <!-- Products Table -->
    <x-admin.card :padding="false">
        <x-admin.table 
            :headers="['المنتج', 'التصنيف', 'سعر داخل أسيوط', 'سعر خارج أسيوط', 'المخزون', 'الحالة', 'إجراءات']" 
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
                        {{ $product->category->name_ar ?? $product->category->name_en ?? '-' }}
                    </x-admin.table.cell>
                    <x-admin.table.cell>
                        @if($product->sale_price_inside_assiut)
                            <p class="font-medium text-green-600">{{ number_format($product->sale_price_inside_assiut, 2) }} ج.م</p>
                            <p class="text-xs text-gray-400 line-through">{{ number_format($product->price_inside_assiut, 2) }} ج.م</p>
                        @else
                            <p class="font-medium">{{ number_format($product->price_inside_assiut, 2) }} ج.م</p>
                        @endif
                    </x-admin.table.cell>
                    <x-admin.table.cell>
                        @if($product->sale_price_outside_assiut)
                            <p class="font-medium text-green-600">{{ number_format($product->sale_price_outside_assiut, 2) }} ج.م</p>
                            <p class="text-xs text-gray-400 line-through">{{ number_format($product->price_outside_assiut, 2) }} ج.م</p>
                        @else
                            <p class="font-medium">{{ number_format($product->price_outside_assiut, 2) }} ج.م</p>
                        @endif
                    </x-admin.table.cell>
                    <x-admin.table.cell>
                        @if($product->stock > 10)
                            <x-admin.badge variant="success">{{ $product->stock }}</x-admin.badge>
                        @elseif($product->stock > 0)
                            <x-admin.badge variant="warning">{{ $product->stock }}</x-admin.badge>
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
                    <td colspan="6">
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
</div>

@endsection
