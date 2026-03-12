<!-- Featured Products Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row items-center justify-between mb-12 gap-4">
            <div class="text-center md:text-right">
                <span class="inline-block bg-primary-red/10 text-primary-red px-4 py-2 rounded-full text-sm font-semibold mb-4">
                    <i class="fas fa-fire ml-1"></i>
                    الأكثر مبيعاً
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-primary-blue">المنتجات المميزة</h2>
                <p class="text-gray-500 mt-2">اكتشف أفضل منتجاتنا المختارة بعناية</p>
            </div>
            <a href="{{ route('products.index') }}?featured=1" 
               class="inline-flex items-center bg-primary-blue text-white px-6 py-3 rounded-full font-semibold hover:bg-primary-blue/90 transition group shadow-lg hover:shadow-xl">
                عرض الكل
                <i class="fas fa-arrow-left mr-2 transform group-hover:-translate-x-1 transition-transform"></i>
            </a>
        </div>
        
        @if(isset($featuredProducts) && $featuredProducts->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
            @foreach($featuredProducts->take(8) as $p)
            <x-storefront.product-card :product="$p" />
            @endforeach
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-box-open text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-700 mb-2">لا توجد منتجات مميزة</h3>
            <p class="text-gray-500 mb-6">سيتم إضافة منتجات مميزة قريباً</p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center bg-primary-blue text-white px-6 py-3 rounded-full font-semibold hover:bg-primary-blue/90 transition">
                تصفح جميع المنتجات
                <i class="fas fa-arrow-left mr-2"></i>
            </a>
        </div>
        @endif
    </div>
</section>
