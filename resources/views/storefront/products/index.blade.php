@extends('layouts.storefront')

@section('title')
    @if(isset($currentCategory))
        {{ $currentCategory->name_ar }} - 
    @elseif(isset($currentBrand))
        {{ $currentBrand->name_ar }} - 
    @elseif(isset($searchQuery) && $searchQuery)
        نتائج البحث: {{ $searchQuery }} - 
    @endif
    {{ __('المنتجات') }} - {{ __('مكتبة الصديق') }}
@endsection

@section('content')
<!-- Breadcrumb -->
<div class="bg-gray-100 py-4">
    <div class="container mx-auto px-4">
        <nav class="flex items-center gap-2 text-sm">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-primary-blue transition">
                <i class="fas fa-home"></i>
            </a>
            <i class="fas fa-chevron-left text-gray-400 text-xs"></i>
            @if(isset($currentCategory))
                <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-primary-blue transition">المنتجات</a>
                <i class="fas fa-chevron-left text-gray-400 text-xs"></i>
                <span class="text-primary-blue font-semibold">{{ $currentCategory->name_ar }}</span>
            @elseif(isset($currentBrand))
                <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-primary-blue transition">المنتجات</a>
                <i class="fas fa-chevron-left text-gray-400 text-xs"></i>
                <span class="text-primary-blue font-semibold">{{ $currentBrand->name_ar }}</span>
            @elseif(isset($searchQuery) && $searchQuery)
                <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-primary-blue transition">المنتجات</a>
                <i class="fas fa-chevron-left text-gray-400 text-xs"></i>
                <span class="text-primary-blue font-semibold">نتائج البحث</span>
            @else
                <span class="text-primary-blue font-semibold">المنتجات</span>
            @endif
        </nav>
    </div>
</div>

<!-- Page Header -->
<section class="bg-gradient-to-br from-primary-blue to-blue-800 py-10">
    <div class="container mx-auto px-4">
        <div class="text-center">
            @if(isset($currentCategory))
                <div class="inline-flex items-center gap-2 bg-white/20 text-white px-4 py-2 rounded-full text-sm mb-4">
                    <i class="fas fa-folder"></i>
                    قسم
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">{{ $currentCategory->name_ar }}</h1>
            @elseif(isset($currentBrand))
                <div class="inline-flex items-center gap-2 bg-white/20 text-white px-4 py-2 rounded-full text-sm mb-4">
                    <i class="fas fa-tag"></i>
                    ماركة
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">{{ $currentBrand->name_ar }}</h1>
            @elseif(isset($searchQuery) && $searchQuery)
                <div class="inline-flex items-center gap-2 bg-white/20 text-white px-4 py-2 rounded-full text-sm mb-4">
                    <i class="fas fa-search"></i>
                    نتائج البحث
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">"{{ $searchQuery }}"</h1>
            @elseif(isset($notFound) && $notFound)
                <div class="inline-flex items-center gap-2 bg-red-500/50 text-white px-4 py-2 rounded-full text-sm mb-4">
                    <i class="fas fa-exclamation-triangle"></i>
                    غير موجود
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">
                    {{ $notFoundType == 'category' ? 'القسم غير موجود' : 'الماركة غير موجودة' }}
                </h1>
            @else
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">جميع المنتجات</h1>
                <p class="text-white/80">اكتشف تشكيلتنا الواسعة من المنتجات</p>
            @endif
        </div>
    </div>
</section>

<section class="py-8">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Sidebar Filters -->
            <aside class="lg:w-72 flex-shrink-0">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-4">
                    <h3 class="font-bold text-lg text-primary-blue mb-4 flex items-center gap-2">
                        <i class="fas fa-filter"></i>
                        تصفية النتائج
                    </h3>
                    
                    <form action="{{ route('products.index') }}" method="GET" id="filterForm">
                        <!-- Search -->
                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">بحث</label>
                            <div class="relative">
                                <input type="text" 
                                       name="q" 
                                       value="{{ $filters['q'] ?? '' }}"
                                       placeholder="ابحث عن منتج..."
                                       class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-primary-yellow focus:border-transparent text-sm">
                                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Categories -->
                        @if(isset($categories) && $categories->isNotEmpty())
                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">الأقسام</label>
                            <div class="space-y-2 max-h-48 overflow-y-auto">
                                @foreach($categories as $category)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="radio" 
                                           name="category" 
                                           value="{{ $category->slug }}"
                                           {{ ($filters['category'] ?? '') == $category->slug ? 'checked' : '' }}
                                           class="w-4 h-4 text-primary-blue focus:ring-primary-yellow">
                                    <span class="text-gray-600 group-hover:text-primary-blue transition text-sm">{{ $category->name_ar }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Brands -->
                        @if(isset($brands) && $brands->isNotEmpty())
                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">الماركات</label>
                            <div class="space-y-2 max-h-48 overflow-y-auto">
                                @foreach($brands as $brand)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="radio" 
                                           name="brand" 
                                           value="{{ $brand->slug }}"
                                           {{ ($filters['brand'] ?? '') == $brand->slug ? 'checked' : '' }}
                                           class="w-4 h-4 text-primary-blue focus:ring-primary-yellow">
                                    <span class="text-gray-600 group-hover:text-primary-blue transition text-sm">{{ $brand->name_ar ?? $brand->name_en }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Sort -->
                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">ترتيب حسب</label>
                            <select name="sort" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-yellow">
                                <option value="">الأكثر صلة</option>
                                <option value="latest" {{ ($filters['sort'] ?? '') == 'latest' ? 'selected' : '' }}>الأحدث</option>
                                <option value="price_asc" {{ ($filters['sort'] ?? '') == 'price_asc' ? 'selected' : '' }}>السعر: من الأقل</option>
                                <option value="price_desc" {{ ($filters['sort'] ?? '') == 'price_desc' ? 'selected' : '' }}>السعر: من الأعلى</option>
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 bg-primary-blue hover:bg-primary-blue/90 text-white font-semibold py-2.5 rounded-lg transition">
                                <i class="fas fa-filter ml-1"></i>
                                تطبيق
                            </button>
                            <a href="{{ route('products.index') }}" class="px-4 py-2.5 border border-gray-300 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </aside>

            <!-- Products Grid -->
            <div class="flex-1">
                @if(isset($notFound) && $notFound)
                    <!-- Not Found State -->
                    <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                        <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-search text-4xl text-red-400"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">
                            {{ $notFoundType == 'category' ? 'القسم' : 'الماركة' }} "{{ $notFoundSlug }}" {{ $notFoundType == 'category' ? 'غير موجود' : 'غير موجودة' }}
                        </h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto">
                            عذراً، لم نتمكن من العثور على ما تبحث عنه. جرب البحث في الأقسام المتاحة أو تصفح جميع المنتجات.
                        </p>
                        <div class="flex flex-wrap justify-center gap-4">
                            <a href="{{ route('products.index') }}" class="inline-flex items-center bg-primary-blue text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-blue/90 transition">
                                <i class="fas fa-store ml-2"></i>
                                تصفح جميع المنتجات
                            </a>
                            <a href="{{ route('home') }}" class="inline-flex items-center border-2 border-primary-blue text-primary-blue px-6 py-3 rounded-lg font-semibold hover:bg-primary-blue hover:text-white transition">
                                <i class="fas fa-home ml-2"></i>
                                الصفحة الرئيسية
                            </a>
                        </div>
                    </div>
                @elseif($products->isEmpty())
                    <!-- Empty State -->
                    <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-box-open text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">لا توجد منتجات</h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto">
                            @if(isset($searchQuery) && $searchQuery)
                                لم نجد أي نتائج لـ "{{ $searchQuery }}". جرب كلمات بحث مختلفة.
                            @else
                                لم يتم إضافة منتجات بعد. تفقد هذه الصفحة لاحقاً!
                            @endif
                        </p>
                        <a href="{{ route('home') }}" class="inline-flex items-center bg-primary-blue text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-blue/90 transition">
                            <i class="fas fa-home ml-2"></i>
                            الصفحة الرئيسية
                        </a>
                    </div>
                @else
                    <!-- Results Count -->
                    <div class="flex items-center justify-between mb-6 bg-white rounded-lg px-4 py-3 shadow">
                        <p class="text-gray-600">
                            <span class="font-bold text-primary-blue">{{ $products->total() }}</span> منتج
                        </p>
                    </div>

                    <!-- Products Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                        @foreach($products as $product)
                        <article class="bg-white rounded-xl shadow-lg overflow-hidden group hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <a href="{{ route('products.show', $product) }}" class="block relative aspect-square overflow-hidden bg-gray-100">
                                @if($product->primary_image)
                                    <img src="{{ $product->primary_image->url }}" 
                                         alt="{{ $product->name_ar }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                         loading="lazy">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                        <i class="fas fa-image text-4xl text-gray-300"></i>
                                    </div>
                                @endif
                                
                                <!-- Badges -->
                                <div class="absolute top-3 right-3 flex flex-col gap-2">
                                    @if($product->is_bingo)
                                        <span class="bg-primary-blue text-white text-xs px-2 py-1 rounded-full font-bold shadow">BINGO</span>
                                    @endif
                                    @if($product->sale_price && $product->sale_price < $product->price)
                                        <span class="bg-primary-red text-white text-xs px-2 py-1 rounded-full font-bold shadow">
                                            -{{ $product->price > 0 ? number_format((($product->price - $product->sale_price) / $product->price) * 100) : 0 }}%
                                        </span>
                                    @endif
                                </div>

                                @if(!$product->is_available)
                                    <div class="absolute inset-0 bg-black/60 flex items-center justify-center">
                                        <span class="bg-white text-gray-800 px-4 py-2 rounded-full font-bold text-sm">نفذت الكمية</span>
                                    </div>
                                @endif
                            </a>

                            <div class="p-4">
                                @if($product->category)
                                    <span class="text-xs text-primary-yellow font-semibold">{{ $product->category->name_ar }}</span>
                                @endif

                                <h3 class="font-bold text-gray-900 mt-1 mb-2 line-clamp-2 min-h-[2.5rem]">
                                    <a href="{{ route('products.show', $product) }}" class="hover:text-primary-blue transition">
                                        {{ $product->name_ar ?? $product->name_en }}
                                    </a>
                                </h3>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-lg font-bold text-primary-blue">{{ number_format($product->final_price, 2) }} ج.م</span>
                                        @if($product->sale_price && $product->sale_price < $product->price)
                                            <span class="text-sm text-gray-400 line-through block">{{ number_format($product->price, 2) }} ج.م</span>
                                        @endif
                                    </div>
                                    @if($product->is_available)
                                        <form method="post" action="{{ route('cart.store', $product) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="w-11 h-11 bg-primary-yellow hover:bg-yellow-400 rounded-full flex items-center justify-center transition transform hover:scale-110 shadow-lg">
                                                <i class="fas fa-cart-plus text-primary-blue"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </article>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                        <div class="mt-8">
                            {{ $products->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-clamp: 2;
    }
</style>
@endpush

@push('scripts')
@endpush
@endsection
