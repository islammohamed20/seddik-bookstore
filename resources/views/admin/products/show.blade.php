@extends('admin.layouts.app')

@section('title', 'تفاصيل المنتج')
@section('page-title', $product->name_ar ?: $product->name_en)

@section('content')
<div class="max-w-5xl space-y-6">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.products.index') }}" class="text-gray-600 hover:text-gray-800 transition">
            <i class="fas fa-arrow-right ml-1"></i> العودة للمنتجات
        </a>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.edit', $product) }}" 
               class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-edit ml-1"></i> تعديل
            </a>
            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline"
                  onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                    <i class="fas fa-trash ml-1"></i> حذف
                </button>
            </form>
        </div>
    </div>

    <!-- Product Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-start justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-800">{{ $product->name_ar ?: $product->name_en }}</h3>
                    <div class="flex gap-2">
                        @if($product->is_active)
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full">نشط</span>
                        @else
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-3 py-1 rounded-full">غير نشط</span>
                        @endif
                        @if($product->is_featured)
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-3 py-1 rounded-full">مميز</span>
                        @endif
                    </div>
                </div>

                @if($product->name_en)
                    <p class="text-gray-500 text-sm mb-4">{{ $product->name_en }}</p>
                @endif

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">SKU:</span>
                        <span class="font-medium text-gray-800 mr-1">{{ $product->sku ?? 'غير محدد' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">الباركود:</span>
                        <span class="font-medium text-gray-800 mr-1">{{ $product->barcode ?? 'غير محدد' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">التصنيف:</span>
                        <span class="font-medium text-gray-800 mr-1">{{ $product->category->name_ar ?? $product->category->name_en ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">العلامة التجارية:</span>
                        <span class="font-medium text-gray-800 mr-1">{{ $product->brand->name_ar ?? $product->brand->name_en ?? 'غير محدد' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">النوع:</span>
                        <span class="font-medium text-gray-800 mr-1">{{ $product->type ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">الترتيب:</span>
                        <span class="font-medium text-gray-800 mr-1">{{ $product->sort_order ?? 0 }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">تاريخ الإنشاء:</span>
                        <span class="font-medium text-gray-800 mr-1">{{ $product->created_at->format('Y/m/d H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">آخر تحديث:</span>
                        <span class="font-medium text-gray-800 mr-1">{{ $product->updated_at->format('Y/m/d H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Descriptions -->
            @if($product->short_description_ar || $product->short_description_en)
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="font-semibold text-gray-800 mb-3">الوصف المختصر</h4>
                @if($product->short_description_ar)
                    <p class="text-gray-700 mb-2">{{ $product->short_description_ar }}</p>
                @endif
                @if($product->short_description_en)
                    <p class="text-gray-500 text-sm">{{ $product->short_description_en }}</p>
                @endif
            </div>
            @endif

            @if($product->description_ar || $product->description_en)
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="font-semibold text-gray-800 mb-3">الوصف الكامل</h4>
                @if($product->description_ar)
                    <div class="text-gray-700 mb-3 prose prose-sm max-w-none">{!! nl2br(e($product->description_ar)) !!}</div>
                @endif
                @if($product->description_en)
                    <div class="text-gray-500 text-sm prose prose-sm max-w-none">{!! nl2br(e($product->description_en)) !!}</div>
                @endif
            </div>
            @endif

            <!-- Images -->
            @if($product->images->count())
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="font-semibold text-gray-800 mb-3">صور المنتج ({{ $product->images->count() }})</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($product->images->sortBy('sort_order') as $image)
                        <div class="relative group rounded-lg overflow-hidden bg-gray-100">
                            <img src="{{ asset('storage/' . $image->path) }}" 
                                 alt="{{ $product->name_ar }}"
                                 class="w-full h-32 object-cover">
                            @if($image->is_primary)
                                <span class="absolute top-1 right-1 bg-indigo-600 text-white text-[10px] px-2 py-0.5 rounded-full">رئيسية</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Orders containing this product -->
            @if($product->orderItems && $product->orderItems->count())
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="font-semibold text-gray-800 mb-3">الطلبات ({{ $product->orderItems->count() }})</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-right py-2 px-3 text-gray-600">رقم الطلب</th>
                                <th class="text-right py-2 px-3 text-gray-600">الكمية</th>
                                <th class="text-right py-2 px-3 text-gray-600">السعر</th>
                                <th class="text-right py-2 px-3 text-gray-600">التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->orderItems->take(10) as $item)
                                <tr class="border-b border-gray-100">
                                    <td class="py-2 px-3">
                                        <a href="{{ route('admin.orders.show', $item->order_id) }}" class="text-indigo-600 hover:underline">
                                            #{{ $item->order_id }}
                                        </a>
                                    </td>
                                    <td class="py-2 px-3">{{ $item->quantity }}</td>
                                    <td class="py-2 px-3">{{ number_format($item->price, 2) }} ج.م</td>
                                    <td class="py-2 px-3">{{ $item->created_at->format('Y/m/d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Pricing -->
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="font-semibold text-gray-800 mb-4">الأسعار</h4>
                <div class="space-y-3 text-sm">
                    @if($product->price_inside_assiut)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">داخل أسيوط</span>
                        <span class="font-medium">{{ number_format($product->price_inside_assiut, 2) }} ج.م</span>
                    </div>
                    @endif
                    @if($product->sale_price_inside_assiut)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">تخفيض داخل أسيوط</span>
                        <span class="font-medium text-green-600">{{ number_format($product->sale_price_inside_assiut, 2) }} ج.م</span>
                    </div>
                    @endif
                    @if($product->price_outside_assiut)
                    <hr class="border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">خارج أسيوط</span>
                        <span class="font-medium">{{ number_format($product->price_outside_assiut, 2) }} ج.م</span>
                    </div>
                    @endif
                    @if($product->sale_price_outside_assiut)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">تخفيض خارج أسيوط</span>
                        <span class="font-medium text-green-600">{{ number_format($product->sale_price_outside_assiut, 2) }} ج.م</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Stock -->
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="font-semibold text-gray-800 mb-4">المخزون</h4>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">الكمية</span>
                        <span class="font-bold text-lg {{ $product->stock_quantity > 10 ? 'text-green-600' : ($product->stock_quantity > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ $product->stock_quantity }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">الحالة</span>
                        @php
                            $statusColors = [
                                'in_stock' => 'bg-green-100 text-green-800',
                                'low_stock' => 'bg-yellow-100 text-yellow-800',
                                'out_of_stock' => 'bg-red-100 text-red-800',
                            ];
                            $statusLabels = [
                                'in_stock' => 'متوفر',
                                'low_stock' => 'كمية قليلة',
                                'out_of_stock' => 'نفذت الكمية',
                            ];
                        @endphp
                        <span class="text-xs font-medium px-2.5 py-0.5 rounded-full {{ $statusColors[$product->stock_status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusLabels[$product->stock_status] ?? $product->stock_status }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">حد التنبيه</span>
                        <span class="font-medium">{{ $product->low_stock_threshold ?? 5 }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Status Toggle -->
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="font-semibold text-gray-800 mb-4">إجراءات سريعة</h4>
                <div class="space-y-3">
                    <form action="{{ route('admin.products.toggle-status', $product) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-center px-4 py-2 rounded-lg transition text-sm font-medium
                            {{ $product->is_active ? 'bg-red-50 text-red-700 hover:bg-red-100' : 'bg-green-50 text-green-700 hover:bg-green-100' }}">
                            <i class="fas {{ $product->is_active ? 'fa-eye-slash' : 'fa-eye' }} ml-1"></i>
                            {{ $product->is_active ? 'إلغاء التنشيط' : 'تنشيط المنتج' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
