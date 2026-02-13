@extends('layouts.storefront')

@section('title', 'المفضلة - مكتبة الصديق')

@section('content')
<!-- Breadcrumb -->
<div class="bg-gray-100 py-4">
    <div class="container mx-auto px-4">
        <nav class="flex items-center gap-2 text-sm">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-primary-blue transition">
                <i class="fas fa-home"></i>
            </a>
            <i class="fas fa-chevron-left text-gray-400 text-xs"></i>
            <span class="text-primary-blue font-semibold">المفضلة</span>
        </nav>
    </div>
</div>

<section class="py-8">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-primary-blue mb-8 flex items-center gap-3">
            <i class="fas fa-heart text-pink-500"></i>
            المفضلة
            @if($products->count() > 0)
                <span class="text-lg font-normal text-gray-500">({{ $products->count() }} منتج)</span>
            @endif
        </h1>

        @if($products->isEmpty())
            <!-- Empty Wishlist State -->
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center max-w-2xl mx-auto">
                <div class="w-32 h-32 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-heart text-5xl text-pink-300"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-3">المفضلة فارغة!</h2>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    لم تقم بإضافة أي منتجات للمفضلة بعد. تصفح منتجاتنا وأضف ما يعجبك.
                </p>
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center bg-primary-blue text-white px-8 py-4 rounded-lg font-bold hover:bg-primary-blue/90 transition transform hover:scale-105">
                    <i class="fas fa-store ml-2"></i>
                    تصفح المنتجات
                </a>
            </div>
        @else
            <!-- Wishlist Actions -->
            <div class="flex justify-between items-center mb-6">
                <p class="text-gray-600">المنتجات المحفوظة في قائمة أمنياتك</p>
                <form action="{{ route('wishlist.clear') }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            onclick="return confirm('هل أنت متأكد من مسح جميع المفضلة؟')"
                            class="text-red-500 hover:text-red-700 text-sm font-medium flex items-center gap-1">
                        <i class="fas fa-trash-alt"></i>
                        مسح الكل
                    </button>
                </form>
            </div>

            <!-- Wishlist Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-2">
                    <!-- Product Image -->
                    <div class="relative overflow-hidden aspect-square bg-gray-100">
                        @if($product->primary_image)
                        <img src="{{ $product->primary_image->url }}" 
                             alt="{{ $product->name_ar }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @elseif($product->images->first())
                        <img src="{{ $product->images->first()->url }}" 
                             alt="{{ $product->name_ar }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-image text-gray-300 text-5xl"></i>
                        </div>
                        @endif
                        
                        <!-- Badges -->
                        <div class="absolute top-3 right-3 flex flex-col gap-2">
                            @if($product->sale_price && $product->sale_price < $product->price)
                            <span class="bg-primary-red text-white text-xs font-bold px-2 py-1 rounded-full shadow">
                                خصم {{ number_format((($product->price - $product->sale_price) / $product->price) * 100) }}%
                            </span>
                            @endif
                        </div>
                        
                        <!-- Remove from Wishlist Button -->
                        <form action="{{ route('wishlist.destroy', $product) }}" method="POST" 
                              class="absolute top-3 left-3">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-pink-500 hover:bg-pink-500 hover:text-white transition shadow-lg"
                                    title="إزالة من المفضلة">
                                <i class="fas fa-heart-broken"></i>
                            </button>
                        </form>
                        
                        <!-- Quick Actions Overlay -->
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                            <a href="{{ route('products.show', $product) }}" 
                               class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-primary-blue hover:bg-primary-blue hover:text-white transition transform scale-75 group-hover:scale-100"
                               title="عرض التفاصيل">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('cart.store', $product) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-primary-yellow hover:bg-primary-yellow hover:text-primary-blue transition transform scale-75 group-hover:scale-100"
                                        title="أضف للسلة">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Product Info -->
                    <div class="p-5">
                        <!-- Category -->
                        @if($product->category)
                        <span class="text-xs text-gray-500 font-medium">{{ $product->category->name_ar }}</span>
                        @endif
                        
                        <!-- Title -->
                        <h3 class="font-bold text-gray-900 mt-1 mb-3 line-clamp-2 group-hover:text-primary-blue transition-colors">
                            <a href="{{ route('products.show', $product) }}">{{ $product->name_ar }}</a>
                        </h3>
                        
                        <!-- Price & Add to Cart -->
                        <div class="flex items-center justify-between">
                            <div>
                                @if($product->sale_price && $product->sale_price < $product->price)
                                <span class="text-primary-red font-bold text-lg">{{ number_format($product->sale_price, 2) }} ج.م</span>
                                <span class="text-gray-400 line-through text-sm mr-1">{{ number_format($product->price, 2) }}</span>
                                @else
                                <span class="text-primary-blue font-bold text-lg">{{ number_format($product->price, 2) }} ج.م</span>
                                @endif
                            </div>
                            <form action="{{ route('cart.store', $product) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="w-10 h-10 bg-primary-yellow hover:bg-primary-yellow-dark text-primary-blue rounded-lg flex items-center justify-center transition">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
