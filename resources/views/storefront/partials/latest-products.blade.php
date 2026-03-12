<!-- Latest Products Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row items-center justify-between mb-12 gap-4">
            <div class="text-center md:text-right">
                <span class="inline-block bg-green-100 text-green-600 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                    <i class="fas fa-sparkles ml-1"></i>
                    وصل حديثاً
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-primary-blue">أحدث المنتجات</h2>
                <p class="text-gray-500 mt-2">تصفح أحدث ما وصلنا من منتجات</p>
            </div>
            <a href="{{ route('products.index') }}?sort=newest" 
               class="inline-flex items-center border-2 border-primary-blue text-primary-blue px-6 py-3 rounded-full font-semibold hover:bg-primary-blue hover:text-white transition group shadow-lg hover:shadow-xl">
                عرض الكل
                <i class="fas fa-arrow-left mr-2 transform group-hover:-translate-x-1 transition-transform"></i>
            </a>
        </div>
        
        @if(isset($latestProducts) && $latestProducts->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
            @foreach($latestProducts->take(8) as $p)
            <x-storefront.product-card :product="$p" badge="جديد" badgeColor="green" />
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
