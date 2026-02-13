@extends('layouts.storefront')

@section('title', __('منتجات Bingo') . ' - ' . __('مكتبة الصديق'))

@section('content')
<!-- Hero Section with Premium Design -->
<section class="relative bg-gradient-to-br from-primary-blue via-blue-900 to-primary-blue py-24 overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-10 left-10 w-72 h-72 bg-primary-yellow/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-white/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        
        <!-- Floating Stars -->
        @for($i = 0; $i < 15; $i++)
        <?php
            $top = rand(5, 90);
            $left = rand(5, 90);
            $delay = rand(0, 20) / 10;
        ?>
        <div class="absolute animate-pulse opacity-20" 
             style="top: <?= $top ?>%; left: <?= $left ?>%; animation-delay: <?= $delay ?>s;">
            <i class="fas fa-star text-primary-yellow"></i>
        </div>
        @endfor
    </div>
    
    <div class="container mx-auto px-4 relative z-10">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center justify-center gap-2 text-sm text-white/70">
                <li><a href="{{ route('home') }}" class="hover:text-white transition"><i class="fas fa-home"></i></a></li>
                <li>/</li>
                <li class="text-primary-yellow font-medium">منتجات Bingo</li>
            </ol>
        </nav>
        
        <div class="flex flex-col lg:flex-row items-center justify-center gap-12">
            <!-- Logo Badge -->
            <div class="relative">
                <div class="absolute inset-0 bg-primary-yellow/30 rounded-full blur-2xl animate-pulse"></div>
                <div class="relative bg-white rounded-3xl p-8 shadow-2xl transform hover:scale-105 transition-transform duration-500">
                    <div class="text-5xl md:text-6xl font-black text-primary-blue text-center">BINGO</div>
                    <div class="flex justify-center gap-1 mt-2">
                        @for($i = 0; $i < 5; $i++)
                        <i class="fas fa-star text-primary-yellow"></i>
                        @endfor
                    </div>
                    <div class="text-center text-sm text-gray-500 mt-1 font-semibold">OFFICIAL DEALER</div>
                    
                    <!-- Verified Badge -->
                    <div class="absolute -top-3 -right-3 w-12 h-12 bg-green-500 rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-check text-white text-xl"></i>
                    </div>
                </div>
            </div>
            
            <!-- Text Content -->
            <div class="text-center lg:text-right max-w-xl">
                <span class="inline-flex items-center gap-2 bg-primary-yellow text-primary-blue px-4 py-2 rounded-full text-sm font-bold mb-6">
                    <i class="fas fa-award"></i>
                    الوكيل الحصري المعتمد
                    <i class="fas fa-check-circle text-green-600"></i>
                </span>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6 leading-tight">
                    اكتشف عالم
                    <span class="text-primary-yellow">Bingo</span>
                    <br>الأصلي
                </h1>
                <p class="text-white/80 text-lg mb-8 leading-relaxed">
                    تشكيلة واسعة من منتجات Bingo الأصلية بضمان الجودة وأسعار الوكيل الحصرية
                </p>
                
                <!-- Trust Badges -->
                <div class="flex flex-wrap justify-center lg:justify-start gap-3">
                    <div class="bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full text-white text-sm border border-white/20">
                        <i class="fas fa-shield-check text-primary-yellow ml-1"></i>
                        منتجات أصلية 100%
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full text-white text-sm border border-white/20">
                        <i class="fas fa-certificate text-primary-yellow ml-1"></i>
                        ضمان الجودة
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full text-white text-sm border border-white/20">
                        <i class="fas fa-tags text-primary-yellow ml-1"></i>
                        أسعار الوكيل
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Wave Decoration -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="#f9fafb"/>
        </svg>
    </div>
</section>

<!-- Categories Filter -->
<section class="py-8 bg-gray-50 sticky top-0 z-40 shadow-sm">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap justify-center gap-3" x-data="{ activeCategory: 'all' }">
            @php
            $categories = [
                ['key' => 'all', 'label' => 'جميع المنتجات', 'icon' => 'fa-th-large'],
                ['key' => 'pens', 'label' => 'أقلام', 'icon' => 'fa-pen'],
                ['key' => 'notebooks', 'label' => 'كراسات', 'icon' => 'fa-book'],
                ['key' => 'colors', 'label' => 'ألوان', 'icon' => 'fa-palette'],
                ['key' => 'school', 'label' => 'مستلزمات مدرسية', 'icon' => 'fa-school'],
            ];
            @endphp
            
            @foreach($categories as $cat)
            <button @click="activeCategory = '{{ $cat['key'] }}'"
                    :class="activeCategory === '{{ $cat['key'] }}' ? 'bg-primary-blue text-white border-primary-blue' : 'bg-white text-gray-700 border-gray-200 hover:border-primary-blue hover:text-primary-blue'"
                    class="px-5 py-2.5 rounded-full font-semibold transition-all border-2 flex items-center gap-2">
                <i class="fas {{ $cat['icon'] }} text-sm"></i>
                {{ $cat['label'] }}
            </button>
            @endforeach
        </div>
    </div>
</section>

<!-- Products Grid -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        @if(isset($products) && $products->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            @foreach($products as $product)
            <div class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <!-- Product Image -->
                <div class="relative aspect-square bg-gray-100 overflow-hidden">
                    @if($product->images && $product->images->isNotEmpty())
                    <img src="{{ $product->primary_image ? $product->primary_image->url : asset('storage/' . $product->images->first()->path) }}" 
                         alt="{{ $product->name_ar }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                        <span class="text-4xl font-black text-gray-300">BINGO</span>
                    </div>
                    @endif
                    
                    <!-- Badges -->
                    <div class="absolute top-3 right-3 flex flex-col gap-2">
                        <span class="bg-primary-blue text-white text-xs px-3 py-1 rounded-full font-bold shadow">
                            BINGO
                        </span>
                        @if($product->sale_price)
                        <span class="bg-primary-red text-white text-xs px-3 py-1 rounded-full font-bold shadow">
                            -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                        </span>
                        @endif
                    </div>
                    
                    <!-- Quick Actions Overlay -->
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                        <a href="{{ route('products.show', $product) }}" 
                           class="w-11 h-11 bg-white rounded-full flex items-center justify-center text-primary-blue hover:bg-primary-blue hover:text-white transition transform scale-75 group-hover:scale-100">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form action="{{ route('cart.store', $product) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="w-11 h-11 bg-white rounded-full flex items-center justify-center text-primary-red hover:bg-primary-red hover:text-white transition transform scale-75 group-hover:scale-100">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        </form>
                        <button type="button"
                                class="w-11 h-11 bg-white rounded-full flex items-center justify-center text-pink-500 hover:bg-pink-500 hover:text-white transition transform scale-75 group-hover:scale-100">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Product Info -->
                <div class="p-4">
                    <h3 class="font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-primary-blue transition-colors">
                        {{ $product->name_ar }}
                    </h3>
                    
                    <!-- Rating -->
                    <div class="flex items-center gap-1 mb-3">
                        @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star text-xs {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                        @endfor
                        <span class="text-xs text-gray-500 mr-1">({{ rand(10, 100) }})</span>
                    </div>
                    
                    <!-- Price & Cart -->
                    <div class="flex items-center justify-between">
                        <div>
                            @if($product->sale_price)
                            <span class="text-lg font-bold text-primary-red">{{ number_format($product->sale_price, 2) }} ج.م</span>
                            <span class="text-sm text-gray-400 line-through mr-1">{{ number_format($product->price, 2) }}</span>
                            @else
                            <span class="text-lg font-bold text-primary-blue">{{ number_format($product->price, 2) }} ج.م</span>
                            @endif
                        </div>
                        <form action="{{ route('cart.store', $product) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="w-10 h-10 bg-primary-yellow hover:bg-yellow-400 rounded-full flex items-center justify-center transition transform hover:scale-110 shadow-lg">
                                <i class="fas fa-plus text-primary-blue"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($products->hasPages())
        <div class="mt-12 flex justify-center">
            {{ $products->links() }}
        </div>
        @endif
        @else
        <!-- Empty State - Premium Design -->
        <div class="text-center py-20">
            <div class="relative inline-block mb-8">
                <div class="absolute inset-0 bg-primary-yellow/20 rounded-full blur-xl"></div>
                <div class="relative w-32 h-32 bg-gradient-to-br from-primary-yellow to-amber-400 rounded-full flex items-center justify-center shadow-xl">
                    <span class="text-3xl font-black text-primary-blue">BINGO</span>
                </div>
            </div>
            <h3 class="text-3xl font-bold text-gray-900 mb-4">منتجات Bingo قادمة قريباً</h3>
            <p class="text-gray-600 mb-8 max-w-md mx-auto">نعمل على إضافة تشكيلة واسعة من منتجات Bingo الأصلية. ترقبوا الجديد!</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center justify-center bg-primary-blue text-white px-8 py-4 rounded-xl font-bold hover:bg-primary-blue/90 transition transform hover:scale-105 shadow-lg">
                    <i class="fas fa-store ml-2"></i>
                    تصفح جميع المنتجات
                </a>
                <a href="{{ route('contact') }}" 
                   class="inline-flex items-center justify-center border-2 border-primary-blue text-primary-blue px-8 py-4 rounded-xl font-bold hover:bg-primary-blue hover:text-white transition">
                    <i class="fas fa-bell ml-2"></i>
                    أعلمني عند التوفر
                </a>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Why Bingo Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <span class="inline-block bg-primary-blue/10 text-primary-blue px-4 py-2 rounded-full text-sm font-bold mb-4">
                <i class="fas fa-star ml-1"></i>
                مميزات Bingo
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-primary-blue mb-4">لماذا منتجات Bingo؟</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">تتميز منتجات Bingo بالجودة العالية والتصميم العصري الذي يناسب جميع الأعمار</p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
            $features = [
                ['icon' => 'fa-award', 'title' => 'جودة معتمدة', 'desc' => 'منتجات حاصلة على شهادات الجودة العالمية ISO', 'color' => 'blue'],
                ['icon' => 'fa-leaf', 'title' => 'صديقة للبيئة', 'desc' => 'مواد آمنة وصديقة للبيئة ومعتمدة عالمياً', 'color' => 'green'],
                ['icon' => 'fa-palette', 'title' => 'تصاميم مبتكرة', 'desc' => 'تصاميم عصرية تناسب جميع الأذواق والأعمار', 'color' => 'purple'],
                ['icon' => 'fa-tags', 'title' => 'أسعار منافسة', 'desc' => 'أفضل الأسعار كوكيل معتمد ومباشر', 'color' => 'yellow'],
            ];
            @endphp
            
            @foreach($features as $feature)
            <div class="group bg-white rounded-2xl p-6 text-center shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                <div class="w-20 h-20 bg-{{ $feature['color'] }}-100 rounded-2xl flex items-center justify-center mx-auto mb-5 group-hover:scale-110 transition-transform">
                    <i class="fas {{ $feature['icon'] }} text-3xl text-{{ $feature['color'] }}-600"></i>
                </div>
                <h4 class="font-bold text-gray-900 text-lg mb-3">{{ $feature['title'] }}</h4>
                <p class="text-gray-600 text-sm leading-relaxed">{{ $feature['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-primary-yellow via-yellow-400 to-amber-500">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="text-center md:text-right">
                <h2 class="text-3xl font-bold text-primary-blue mb-2">هل تبحث عن منتج معين؟</h2>
                <p class="text-primary-blue/80">تواصل معنا وسنساعدك في الحصول على ما تحتاجه</p>
            </div>
            <div class="flex gap-4">
                <a href="https://wa.me/201223694848" 
                   class="inline-flex items-center bg-green-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-green-700 transition transform hover:scale-105 shadow-lg">
                    <i class="fab fa-whatsapp ml-2 text-xl"></i>
                    واتساب
                </a>
                <a href="{{ route('contact') }}" 
                   class="inline-flex items-center bg-primary-blue text-white px-6 py-3 rounded-xl font-bold hover:bg-primary-blue/90 transition transform hover:scale-105 shadow-lg">
                    <i class="fas fa-envelope ml-2"></i>
                    راسلنا
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
