<!-- Latest Products Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="flex flex-col sm:flex-row items-center justify-between mb-12">
            <div class="text-center sm:text-right mb-4 sm:mb-0">
                <span class="inline-block bg-green-100 text-green-600 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                    <i class="fas fa-sparkles ml-1"></i>
                    وصل حديثاً
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-primary-blue">أحدث المنتجات</h2>
            </div>
            <a href="{{ route('products.index') }}?sort=newest" 
               class="inline-flex items-center border-2 border-primary-blue text-primary-blue px-6 py-3 rounded-full font-semibold hover:bg-primary-blue hover:text-white transition group">
                عرض الكل
                <i class="fas fa-arrow-left mr-2 transform group-hover:-translate-x-1 transition-transform"></i>
            </a>
        </div>
        
        @if(isset($latestProducts) && $latestProducts->count() > 0)
        <!-- Products Slider/Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($latestProducts->take(8) as $p)
            <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-2">
                <!-- Product Image -->
                <div class="relative overflow-hidden aspect-square bg-gray-100">
                    @if($p->primary_image)
                    <img src="{{ $p->primary_image->url }}" 
                         alt="{{ $p->name_ar }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @elseif($p->images->first())
                    <img src="{{ $p->images->first()->url }}" 
                         alt="{{ $p->name_ar }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="fas fa-image text-gray-300 text-5xl"></i>
                    </div>
                    @endif
                    
                    <!-- New Badge -->
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex items-center bg-green-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg animate-pulse">
                            <i class="fas fa-certificate ml-1 text-[10px]"></i>
                            جديد
                        </span>
                    </div>
                    
                    <!-- Quick View on Hover -->
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                        <a href="{{ route('products.show', $p) }}" 
                           class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-primary-blue hover:bg-primary-blue hover:text-white transition transform scale-75 group-hover:scale-100"
                           title="عرض التفاصيل">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form action="{{ route('cart.store', $p) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-primary-red hover:bg-primary-red hover:text-white transition transform scale-75 group-hover:scale-100"
                                    title="أضف للسلة">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        </form>
                        <button type="button"
                                class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-pink-500 hover:bg-pink-500 hover:text-white transition transform scale-75 group-hover:scale-100"
                                title="أضف للمفضلة">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Product Info -->
                <div class="p-5">
                    <!-- Category -->
                    @if($p->category)
                    <span class="text-xs text-gray-500 font-medium">{{ $p->category->name_ar }}</span>
                    @endif
                    
                    <!-- Title -->
                    <h3 class="font-bold text-gray-900 mt-1 mb-3 line-clamp-2 group-hover:text-primary-blue transition-colors">
                        <a href="{{ route('products.show', $p) }}">{{ $p->name_ar }}</a>
                    </h3>
                    
                    <!-- Stock Status -->
                    <div class="flex items-center gap-2 mb-3">
                        @if($p->stock > 0)
                        <span class="inline-flex items-center text-xs text-green-600">
                            <span class="w-2 h-2 bg-green-500 rounded-full ml-1 animate-pulse"></span>
                            متوفر
                        </span>
                        @else
                        <span class="inline-flex items-center text-xs text-red-600">
                            <span class="w-2 h-2 bg-red-500 rounded-full ml-1"></span>
                            غير متوفر
                        </span>
                        @endif
                    </div>
                    
                    <!-- Price & Add to Cart -->
                    <div class="flex items-center justify-between">
                        <div>
                            @if($p->sale_price && $p->sale_price < $p->price)
                            <span class="text-lg font-bold text-primary-red">{{ number_format($p->sale_price, 2) }} ج.م</span>
                            <span class="text-sm text-gray-400 line-through mr-1">{{ number_format($p->price, 2) }}</span>
                            @else
                            <span class="text-lg font-bold text-primary-red">{{ number_format($p->price, 2) }} ج.م</span>
                            @endif
                        </div>
                        <form action="{{ route('cart.store', $p) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center hover:bg-green-600 hover:text-white transition">
                                <i class="fas fa-plus"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-box-open text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-700 mb-2">لا توجد منتجات حديثة</h3>
            <p class="text-gray-500 mb-6">سيتم إضافة منتجات جديدة قريباً</p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center bg-primary-blue text-white px-6 py-3 rounded-full font-semibold hover:bg-primary-blue/90 transition">
                تصفح جميع المنتجات
                <i class="fas fa-arrow-left mr-2"></i>
            </a>
        </div>
        @endif
    </div>
</section>
