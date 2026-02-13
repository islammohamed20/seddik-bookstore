@extends('layouts.storefront')

@section('title', ($product->name_ar ?? $product->name_en) . ' | مكتبة الصديق')

@section('meta_description', Str::limit(strip_tags($product->description_ar ?? $product->description_en ?? ''), 160))

@section('og_title', ($product->name_ar ?? $product->name_en) . ' | مكتبة الصديق')
@section('og_description', Str::limit(strip_tags($product->description_ar ?? $product->description_en ?? ''), 200))
@section('og_image', $product->primary_image?->url ?? asset('images/og-default.jpg'))
@section('og_type', 'product')

@section('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org/",
    "@type": "Product",
    "name": "{{ $product->name_ar ?? $product->name_en }}",
    "image": [
        @if($product->images && $product->images->count() > 0)
            @foreach($product->images as $img)
            "{{ asset('storage/' . $img->path) }}"{{ !$loop->last ? ',' : '' }}
            @endforeach
        @else
            "{{ asset('images/no-product.png') }}"
        @endif
    ],
    "description": "{{ Str::limit(strip_tags($product->description_ar ?? $product->description_en ?? ''), 500) }}",
    "sku": "{{ $product->sku ?? $product->id }}",
    "brand": {
        "@type": "Brand",
        "name": "{{ $product->brand?->name_ar ?? $product->brand?->name_en ?? 'مكتبة الصديق' }}"
    },
    "offers": {
        "@type": "Offer",
        "url": "{{ route('products.show', $product) }}",
        "priceCurrency": "EGP",
        "price": "{{ $product->final_price }}",
        "priceValidUntil": "{{ now()->addMonth()->format('Y-m-d') }}",
        "availability": "{{ $product->stock_quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
        "seller": {
            "@type": "Organization",
            "name": "مكتبة الصديق"
        }
    }
    @if($product->category)
    ,"category": "{{ $product->category->name_ar ?? $product->category->name_en }}"
    @endif
}
</script>
@endsection

@section('content')
<!-- Breadcrumb -->
<nav class="bg-gray-50 py-4">
    <div class="container mx-auto px-4">
        <ol class="flex items-center flex-wrap gap-2 text-sm">
            <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-primary-blue transition"><i class="fas fa-home"></i></a></li>
            <li class="text-gray-400">/</li>
            <li><a href="{{ route('products.index') }}" class="text-gray-500 hover:text-primary-blue transition">المنتجات</a></li>
            @if($product->category)
            <li class="text-gray-400">/</li>
            <li><a href="{{ route('products.category', $product->category->slug) }}" class="text-gray-500 hover:text-primary-blue transition">{{ $product->category->name_ar }}</a></li>
            @endif
            <li class="text-gray-400">/</li>
            <li class="text-primary-blue font-medium truncate max-w-[200px]">{{ $product->name_ar ?? $product->name_en }}</li>
        </ol>
    </div>
</nav>

<!-- Product Details -->
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
            <!-- Product Images -->
            <div class="space-y-4" x-data="{ activeImage: 0 }">
                <!-- Main Image -->
                <div class="relative rounded-2xl bg-gray-100 overflow-hidden shadow-lg group">
                    <div class="aspect-square">
                        @if($product->images && $product->images->count() > 0)
                        @foreach($product->images as $index => $image)
                        <img x-show="activeImage === {{ $index }}" 
                             src="{{ asset('storage/' . $image->path) }}" 
                             alt="{{ $product->name_ar }}"
                             class="w-full h-full object-cover transition-opacity duration-300"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100">
                        @endforeach
                        @elseif($product->primary_image)
                        <img src="{{ $product->primary_image->url }}" 
                             alt="{{ $product->name_ar }}" 
                             class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-image text-gray-300 text-6xl"></i>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Badges -->
                    <div class="absolute top-4 right-4 flex flex-col gap-2">
                        @if($product->sale_price && $product->sale_price < $product->price)
                        <span class="px-3 py-1.5 rounded-full bg-primary-red text-white text-sm font-bold shadow-lg">
                            خصم {{ $product->price > 0 ? number_format((($product->price - $product->sale_price) / $product->price) * 100) : 0 }}%
                        </span>
                        @endif
                        @if($product->is_featured)
                        <span class="px-3 py-1.5 rounded-full bg-primary-yellow text-primary-blue text-sm font-bold shadow-lg">
                            <i class="fas fa-star ml-1"></i> مميز
                        </span>
                        @endif
                        @if($product->is_bingo)
                        <span class="px-3 py-1.5 rounded-full bg-amber-500 text-white text-sm font-bold shadow-lg">
                            Bingo
                        </span>
                        @endif
                    </div>
                    
                    <!-- Zoom Button -->
                    <button class="absolute bottom-4 left-4 w-10 h-10 bg-white/90 rounded-full flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-opacity hover:bg-white">
                        <i class="fas fa-search-plus text-gray-700"></i>
                    </button>
                </div>
                
                <!-- Thumbnails -->
                @if($product->images && $product->images->count() > 1)
                <div class="flex gap-3 overflow-x-auto pb-2">
                    @foreach($product->images as $index => $image)
                    <button @click="activeImage = {{ $index }}"
                            :class="activeImage === {{ $index }} ? 'ring-2 ring-primary-blue' : 'ring-1 ring-gray-200'"
                            class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden transition-all hover:ring-primary-blue">
                        <img src="{{ asset('storage/' . $image->path) }}" 
                             alt="صورة {{ $index + 1 }}"
                             class="w-full h-full object-cover">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>
            
            <!-- Product Info -->
            <div class="space-y-6">
                <!-- Category & Brand -->
                <div class="flex items-center gap-3 flex-wrap">
                    @if($product->category)
                    <a href="{{ route('products.category', $product->category->slug) }}" 
                       class="text-sm text-gray-500 hover:text-primary-blue transition">
                        <i class="fas fa-folder-open ml-1"></i>
                        {{ $product->category->name_ar }}
                    </a>
                    @endif
                    @if($product->brand)
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('products.brand', $product->brand->slug) }}" 
                       class="text-sm text-gray-500 hover:text-primary-blue transition">
                        <i class="fas fa-tag ml-1"></i>
                        {{ $product->brand->name_ar ?? $product->brand->name }}
                    </a>
                    @endif
                </div>
                
                <!-- Title -->
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                    {{ $product->name_ar ?? $product->name_en }}
                </h1>
                
                <!-- Subtitle -->
                @if($product->subtitle_ar || $product->subtitle_en)
                <p class="text-gray-600 text-lg">
                    {{ $product->subtitle_ar ?? $product->subtitle_en }}
                </p>
                @endif
                
                <!-- Rating -->
                <div class="flex items-center gap-3">
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                        @endfor
                    </div>
                    <span class="text-gray-500 text-sm">({{ rand(15, 150) }} تقييم)</span>
                </div>
                
                <!-- Price -->
                <div class="bg-gray-50 rounded-2xl p-6">
                    @php $price = $product->final_price; @endphp
                    <div class="flex items-baseline gap-3 flex-wrap">
                        <span class="text-3xl font-bold text-primary-red">
                            {{ number_format($price, 2) }} ج.م
                        </span>
                        @if($product->sale_price && $product->sale_price < $product->price)
                        <span class="text-lg text-gray-400 line-through">
                            {{ number_format($product->price, 2) }} ج.م
                        </span>
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                            وفر {{ number_format($product->price - $price, 2) }} ج.م
                        </span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500 mt-2">شامل جميع الضرائب</p>
                </div>
                
                <!-- Stock Status -->
                <div class="flex items-center gap-4 flex-wrap">
                    @if($product->stock_status === 'out_of_stock' || (!$product->is_available ?? false))
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-100 text-red-700 font-medium">
                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                        غير متوفر في المخزون
                    </span>
                    @elseif($product->stock_status === 'low_stock')
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-100 text-amber-700 font-medium">
                        <span class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></span>
                        الكمية قليلة - اطلب الآن
                    </span>
                    @else
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-green-100 text-green-700 font-medium">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                        متوفر في المخزون
                    </span>
                    @endif
                    
                    @if($product->sku)
                    <span class="text-sm text-gray-500">
                        <i class="fas fa-barcode ml-1"></i>
                        {{ $product->sku }}
                    </span>
                    @endif
                </div>
                
                <!-- Description -->
                @if($product->short_description_ar || $product->short_description_en)
                <div class="prose prose-sm text-gray-600">
                    <p>{{ $product->short_description_ar ?? $product->short_description_en }}</p>
                </div>
                @endif
                
                <!-- Add to Cart -->
                @if($product->is_available ?? true)
                <form method="post" action="{{ route('cart.store', $product) }}" class="space-y-4" x-data>
                    @csrf
                    <div class="flex items-center gap-4">
                        <!-- Quantity -->
                        <div class="flex items-center border-2 border-gray-200 rounded-xl overflow-hidden" x-data="{ qty: 1 }">
                            <button type="button" @click="qty = Math.max(1, qty - 1)" 
                                    class="w-12 h-12 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" name="quantity" x-model="qty" min="1" max="100"
                                   class="w-16 h-12 text-center border-0 focus:ring-0 font-semibold text-lg">
                            <button type="button" @click="qty = Math.min(100, qty + 1)"
                                    class="w-12 h-12 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        
                        <!-- Add to Cart Button -->
                        <button type="submit" 
                                class="flex-1 h-12 bg-primary-blue text-white font-bold rounded-xl flex items-center justify-center gap-2 hover:bg-primary-blue/90 transition transform hover:scale-[1.02] shadow-lg">
                            <i class="fas fa-shopping-cart"></i>
                            أضف للسلة
                        </button>
                    </div>
                    
                    <!-- Secondary Actions -->
                    <div class="flex gap-3">
                        <button type="button"
                                class="flex-1 h-12 border-2 border-gray-200 text-gray-700 font-semibold rounded-xl flex items-center justify-center gap-2 hover:border-pink-500 hover:text-pink-500 transition">
                            <i class="far fa-heart"></i>
                            أضف للمفضلة
                        </button>
                        <button type="button" @click="shareProduct()"
                                class="w-12 h-12 border-2 border-gray-200 text-gray-700 rounded-xl flex items-center justify-center hover:border-primary-blue hover:text-primary-blue transition">
                            <i class="fas fa-share-alt"></i>
                        </button>
                    </div>
                </form>
                @else
                <div class="bg-red-50 rounded-xl p-4 text-center">
                    <p class="text-red-700 font-medium mb-3">هذا المنتج غير متوفر حالياً</p>
                    <button class="bg-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-700 transition">
                        <i class="fas fa-bell ml-2"></i>
                        أعلمني عند التوفر
                    </button>
                </div>
                @endif
                
                <!-- Trust Badges -->
                <div class="grid grid-cols-3 gap-4 pt-4 border-t">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-truck text-primary-blue"></i>
                        </div>
                        <p class="text-xs text-gray-600">شحن سريع</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-shield-check text-green-600"></i>
                        </div>
                        <p class="text-xs text-gray-600">ضمان الجودة</p>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-undo text-amber-600"></i>
                        </div>
                        <p class="text-xs text-gray-600">إرجاع سهل</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Product Description & Details Tabs -->
@if($product->description_ar || $product->description_en || $product->specifications)
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div x-data="{ activeTab: 'description' }" class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Tabs Header -->
            <div class="flex border-b">
                <button @click="activeTab = 'description'"
                        :class="activeTab === 'description' ? 'border-b-2 border-primary-blue text-primary-blue bg-blue-50' : 'text-gray-600'"
                        class="flex-1 py-4 px-6 font-semibold transition">
                    <i class="fas fa-file-alt ml-2"></i>
                    الوصف
                </button>
                <button @click="activeTab = 'specs'"
                        :class="activeTab === 'specs' ? 'border-b-2 border-primary-blue text-primary-blue bg-blue-50' : 'text-gray-600'"
                        class="flex-1 py-4 px-6 font-semibold transition">
                    <i class="fas fa-list-ul ml-2"></i>
                    المواصفات
                </button>
                <button @click="activeTab = 'reviews'"
                        :class="activeTab === 'reviews' ? 'border-b-2 border-primary-blue text-primary-blue bg-blue-50' : 'text-gray-600'"
                        class="flex-1 py-4 px-6 font-semibold transition">
                    <i class="fas fa-star ml-2"></i>
                    التقييمات
                </button>
            </div>
            
            <!-- Tabs Content -->
            <div class="p-6">
                <!-- Description Tab -->
                <div x-show="activeTab === 'description'" class="prose max-w-none">
                    {!! $product->description_ar ?? $product->description_en ?? '<p class="text-gray-500">لا يوجد وصف متاح لهذا المنتج.</p>' !!}
                </div>
                
                <!-- Specs Tab -->
                <div x-show="activeTab === 'specs'" x-cloak>
                    @if($product->specifications)
                    <table class="w-full">
                        <tbody class="divide-y">
                            @foreach(json_decode($product->specifications, true) ?? [] as $key => $value)
                            <tr>
                                <td class="py-3 px-4 bg-gray-50 font-medium text-gray-700 w-1/3">{{ $key }}</td>
                                <td class="py-3 px-4">{{ $value }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p class="text-gray-500 text-center py-8">لا توجد مواصفات متاحة.</p>
                    @endif
                </div>
                
                <!-- Reviews Tab -->
                <div x-show="activeTab === 'reviews'" x-cloak>
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-star text-2xl text-gray-400"></i>
                        </div>
                        <p class="text-gray-500 mb-4">لا توجد تقييمات حتى الآن</p>
                        <button class="bg-primary-blue text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-blue/90 transition">
                            كن أول من يقيم هذا المنتج
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Related Products -->
@if($related->isNotEmpty())
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-primary-blue">
                <i class="fas fa-lightbulb text-primary-yellow ml-2"></i>
                قد يعجبك أيضاً
            </h2>
            <a href="{{ route('products.index') }}" class="text-primary-blue hover:underline font-medium">
                عرض المزيد
                <i class="fas fa-arrow-left mr-1"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            @foreach($related as $item)
            <a href="{{ route('products.show', $item) }}" 
               class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all transform hover:-translate-y-1">
                <div class="relative aspect-square bg-gray-100 overflow-hidden">
                    @if($item->images && $item->images->first())
                    <img src="{{ asset('storage/' . $item->images->first()->path) }}" 
                         alt="{{ $item->name_ar }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @elseif($item->primary_image)
                    <img src="{{ $item->primary_image->url }}" 
                         alt="{{ $item->name_ar }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="fas fa-image text-gray-300 text-4xl"></i>
                    </div>
                    @endif
                    
                    @if($item->sale_price && $item->sale_price < $item->price)
                    <span class="absolute top-2 right-2 px-2 py-1 rounded-full bg-primary-red text-white text-xs font-bold">
                        -{{ $item->price > 0 ? number_format((($item->price - $item->sale_price) / $item->price) * 100) : 0 }}%
                    </span>
                    @endif
                </div>
                <div class="p-4">
                    <p class="text-xs text-gray-500 mb-1">{{ $item->category?->name_ar }}</p>
                    <h3 class="font-semibold text-gray-900 line-clamp-2 mb-2 group-hover:text-primary-blue transition-colors">
                        {{ $item->name_ar ?? $item->name_en }}
                    </h3>
                    @php $itemPrice = $item->final_price; @endphp
                    <div class="flex items-baseline gap-2">
                        <span class="font-bold text-primary-red">{{ number_format($itemPrice, 2) }} ج.م</span>
                        @if($item->sale_price && $item->sale_price < $item->price)
                        <span class="text-xs text-gray-400 line-through">{{ number_format($item->price, 2) }}</span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<script>
function shareProduct() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $product->name_ar ?? $product->name_en }}',
            url: window.location.href
        });
    } else {
        navigator.clipboard.writeText(window.location.href);
        alert('تم نسخ رابط المنتج');
    }
}
</script>
@endsection
