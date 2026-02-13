@extends('layouts.storefront')

@section('title', __('العروض والخصومات') . ' - ' . __('مكتبة الصديق'))

@section('content')
<!-- Hero Section with Animated Background -->
<section class="relative bg-gradient-to-br from-primary-red via-red-600 to-red-800 py-20 overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-10 left-10 w-72 h-72 bg-primary-yellow/30 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-white/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        
        <!-- Floating Discount Tags -->
        <div class="hidden md:block">
            @for($i = 0; $i < 8; $i++)
            <div class="absolute animate-bounce opacity-20" 
                 style="top: {{ rand(10, 80) }}%; left: {{ rand(5, 90) }}%; animation-delay: {{ rand(0, 30) / 10 }}s; animation-duration: {{ rand(20, 40) / 10 }}s;">
                <i class="fas fa-tag text-white text-{{ rand(2, 4) }}xl transform rotate-{{ rand(-45, 45) }}"></i>
            </div>
            @endfor
        </div>
    </div>
    
    <div class="container mx-auto px-4 relative z-10">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center justify-center gap-2 text-sm text-white/70">
                <li><a href="{{ route('home') }}" class="hover:text-white transition"><i class="fas fa-home"></i></a></li>
                <li>/</li>
                <li class="text-primary-yellow font-medium">العروض</li>
            </ol>
        </nav>
        
        <div class="text-center">
            <!-- Animated Fire Icon -->
            <div class="inline-block mb-6 relative">
                <div class="absolute inset-0 bg-primary-yellow rounded-full blur-xl animate-ping opacity-50"></div>
                <div class="relative w-20 h-20 bg-primary-yellow rounded-full flex items-center justify-center shadow-2xl">
                    <i class="fas fa-fire-alt text-4xl text-primary-red animate-pulse"></i>
                </div>
            </div>
            
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6">
                العروض 
                <span class="text-primary-yellow">والخصومات</span>
            </h1>
            <p class="text-white/90 text-xl max-w-2xl mx-auto mb-10 leading-relaxed">
                لا تفوت فرصة التوفير! أقوى العروض على الأدوات المدرسية والمكتبية
            </p>
            
            <!-- Offer Badges -->
            <div class="flex flex-wrap justify-center gap-4">
                <div class="group bg-white/10 backdrop-blur-sm px-5 py-3 rounded-full text-white border border-white/20 hover:bg-white hover:text-primary-red transition-all cursor-pointer">
                    <i class="fas fa-percent ml-2 text-primary-yellow group-hover:text-primary-red transition"></i>
                    خصومات تصل إلى 50%
                </div>
                <div class="group bg-white/10 backdrop-blur-sm px-5 py-3 rounded-full text-white border border-white/20 hover:bg-white hover:text-primary-red transition-all cursor-pointer">
                    <i class="fas fa-truck ml-2 text-primary-yellow group-hover:text-primary-red transition"></i>
                    توصيل مجاني +200 ج.م
                </div>
                <div class="group bg-white/10 backdrop-blur-sm px-5 py-3 rounded-full text-white border border-white/20 hover:bg-white hover:text-primary-red transition-all cursor-pointer">
                    <i class="fas fa-clock ml-2 text-primary-yellow group-hover:text-primary-red transition"></i>
                    عروض محدودة
                </div>
            </div>
        </div>
    </div>
    
    <!-- Wave Decoration -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 40C840 50 960 70 1080 75C1200 80 1320 70 1380 65L1440 60V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="white"/>
        </svg>
    </div>
</section>

<!-- Countdown Timer Section -->
<section class="py-8 bg-gradient-to-r from-primary-yellow to-amber-400">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center justify-center gap-6 text-primary-blue">
            <div class="flex items-center gap-2 font-bold text-lg">
                <i class="fas fa-bolt"></i>
                <span>ينتهي العرض خلال:</span>
            </div>
            <div class="flex gap-3" x-data="countdown()" x-init="startCountdown()">
                <div class="bg-primary-blue text-white rounded-xl p-3 text-center min-w-[70px]">
                    <div class="text-2xl font-bold" x-text="days">00</div>
                    <div class="text-xs">يوم</div>
                </div>
                <div class="bg-primary-blue text-white rounded-xl p-3 text-center min-w-[70px]">
                    <div class="text-2xl font-bold" x-text="hours">00</div>
                    <div class="text-xs">ساعة</div>
                </div>
                <div class="bg-primary-blue text-white rounded-xl p-3 text-center min-w-[70px]">
                    <div class="text-2xl font-bold" x-text="minutes">00</div>
                    <div class="text-xs">دقيقة</div>
                </div>
                <div class="bg-primary-blue text-white rounded-xl p-3 text-center min-w-[70px]">
                    <div class="text-2xl font-bold" x-text="seconds">00</div>
                    <div class="text-xs">ثانية</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Active Offers -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        @if(isset($offers) && $offers->count() > 0)
        <div class="text-center mb-12">
            <span class="inline-block bg-primary-red/10 text-primary-red px-4 py-2 rounded-full text-sm font-bold mb-4">
                <i class="fas fa-fire ml-1"></i>
                عروض نشطة
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-primary-blue">العروض الحالية</h2>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($offers as $offer)
            <div class="group bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                <div class="relative">
                    @if($offer->banner_image)
                    <img src="{{ Storage::url($offer->banner_image) }}" 
                         alt="{{ $offer->name_ar ?? $offer->name_en }}"
                         class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                    <div class="w-full h-48 bg-gradient-to-br from-primary-red to-red-600 flex items-center justify-center">
                        <i class="fas fa-tags text-6xl text-white/30"></i>
                    </div>
                    @endif
                    
                    <!-- Discount Badge -->
                    <div class="absolute top-4 right-4">
                        <div class="bg-primary-red text-white px-4 py-2 rounded-full font-bold shadow-lg flex items-center gap-2">
                            <i class="fas fa-percent"></i>
                            <span>
                                @if($offer->discount_type === 'percent')
                                    {{ (int) $offer->discount_value }}% خصم
                                @elseif($offer->discount_type === 'fixed')
                                    خصم {{ number_format((float) ($offer->discount_value ?? 0), 2) }} ج.م
                                @else
                                    شحن مجاني
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <h3 class="text-xl font-bold text-primary-blue mb-3 group-hover:text-primary-red transition-colors">
                        {{ $offer->name_ar ?? $offer->name_en }}
                    </h3>
                    <p class="text-gray-600 mb-4 line-clamp-2">{{ $offer->description_ar ?? $offer->description_en }}</p>
                    
                    <!-- Timer -->
                    <div class="flex items-center justify-between mb-4 p-3 bg-gray-50 rounded-xl">
                        <span class="text-sm text-gray-500">
                            <i class="fas fa-clock ml-1 text-primary-red"></i>
                            ينتهي:
                        </span>
                        <span class="text-sm font-semibold text-primary-blue">
                            {{ $offer->ends_at ? $offer->ends_at->format('Y/m/d') : '—' }}
                        </span>
                    </div>
                    
                    <a href="{{ route('offers.show', $offer) }}" 
                       class="block w-full text-center bg-gradient-to-r from-primary-yellow to-amber-400 text-primary-blue py-3 rounded-xl font-bold hover:shadow-lg transition transform hover:scale-[1.02]">
                        <i class="fas fa-shopping-cart ml-2"></i>
                        تسوق الآن
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <!-- No Active Offers - Show Categories with Discounts -->
        <div class="text-center mb-12">
            <span class="inline-block bg-primary-yellow/20 text-primary-yellow px-4 py-2 rounded-full text-sm font-bold mb-4">
                <i class="fas fa-star ml-1"></i>
                عروض حصرية
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-primary-blue mb-4">استمتع بأفضل الأسعار</h2>
            <p class="text-gray-600 max-w-xl mx-auto">عروض مميزة على مدار العام لجميع الفئات</p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
            $categories = [
                ['emoji' => '🎒', 'title' => 'عروض المدارس', 'desc' => 'خصومات خاصة على الأدوات المدرسية', 'discount' => '30', 'color' => 'blue', 'link' => route('products.index', ['category' => 'school'])],
                ['emoji' => '🎨', 'title' => 'أدوات الرسم', 'desc' => 'كل ما تحتاجه للإبداع والفن', 'discount' => '25', 'color' => 'purple', 'link' => route('products.index', ['category' => 'art'])],
                ['emoji' => 'BINGO', 'title' => 'منتجات Bingo', 'desc' => 'أسعار الوكيل الحصرية', 'discount' => 'أفضل سعر', 'color' => 'yellow', 'link' => route('bingo')],
                ['emoji' => '💼', 'title' => 'مستلزمات المكاتب', 'desc' => 'لبيئة عمل منظمة ومنتجة', 'discount' => '20', 'color' => 'green', 'link' => route('products.index', ['category' => 'office'])],
                ['emoji' => '📦', 'title' => 'طلبات الجملة', 'desc' => 'أسعار خاصة للكميات الكبيرة', 'discount' => '40', 'color' => 'orange', 'link' => route('contact')],
                ['emoji' => '🚚', 'title' => 'توصيل مجاني', 'desc' => 'على الطلبات فوق 200 ج.م', 'discount' => 'توفير', 'color' => 'pink', 'link' => route('products.index')],
            ];
            
            $colorClasses = [
                'blue' => ['from' => 'from-blue-500', 'to' => 'to-blue-700', 'text' => 'text-blue-600', 'bg' => 'bg-white/20'],
                'purple' => ['from' => 'from-purple-500', 'to' => 'to-purple-700', 'text' => 'text-purple-600', 'bg' => 'bg-white/20'],
                'yellow' => ['from' => 'from-primary-yellow', 'to' => 'to-amber-500', 'text' => 'text-primary-blue', 'bg' => 'bg-primary-blue/20'],
                'green' => ['from' => 'from-green-500', 'to' => 'to-green-700', 'text' => 'text-green-600', 'bg' => 'bg-white/20'],
                'orange' => ['from' => 'from-orange-500', 'to' => 'to-orange-700', 'text' => 'text-orange-600', 'bg' => 'bg-white/20'],
                'pink' => ['from' => 'from-pink-500', 'to' => 'to-pink-700', 'text' => 'text-pink-600', 'bg' => 'bg-white/20'],
            ];
            @endphp
            
            @foreach($categories as $cat)
            @php $cc = $colorClasses[$cat['color']]; @endphp
            <div class="group bg-gradient-to-br {{ $cc['from'] }} {{ $cc['to'] }} rounded-3xl p-6 {{ $cat['color'] === 'yellow' ? 'text-primary-blue' : 'text-white' }} relative overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 hover:scale-[1.02]">
                <!-- Background Decoration -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
                
                <div class="relative z-10">
                    <!-- Emoji/Logo -->
                    <div class="text-5xl mb-4 {{ strlen($cat['emoji']) > 2 ? 'font-black' : '' }}">
                        {{ $cat['emoji'] }}
                    </div>
                    
                    <!-- Title -->
                    <h3 class="text-2xl font-bold mb-2">{{ $cat['title'] }}</h3>
                    
                    <!-- Description -->
                    <p class="{{ $cat['color'] === 'yellow' ? 'text-primary-blue/70' : 'text-white/80' }} mb-4">{{ $cat['desc'] }}</p>
                    
                    <!-- Discount Badge -->
                    <div class="{{ $cat['color'] === 'yellow' ? 'bg-primary-blue text-white' : 'bg-white ' . $cc['text'] }} px-4 py-1.5 rounded-full inline-block font-bold mb-5 shadow-lg">
                        @if(is_numeric($cat['discount']))
                        خصم حتى {{ $cat['discount'] }}%
                        @else
                        {{ $cat['discount'] }}
                        @endif
                    </div>
                    
                    <!-- CTA Button -->
                    <a href="{{ $cat['link'] }}" 
                       class="block {{ $cc['bg'] }} hover:bg-white/30 text-center py-3 rounded-xl font-semibold transition-all transform group-hover:scale-105">
                        تسوق الآن
                        <i class="fas fa-arrow-left mr-2 transform group-hover:-translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>

<!-- Why Shop Our Offers Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <span class="inline-block bg-primary-blue/10 text-primary-blue px-4 py-2 rounded-full text-sm font-bold mb-4">
                <i class="fas fa-gift ml-1"></i>
                لماذا عروضنا مميزة؟
            </span>
            <h2 class="text-3xl font-bold text-primary-blue">مميزات التسوق معنا</h2>
        </div>
        
        <div class="grid md:grid-cols-4 gap-6">
            @php
            $features = [
                ['icon' => 'fa-piggy-bank', 'title' => 'توفير حقيقي', 'desc' => 'خصومات فعلية وليست وهمية', 'color' => 'green'],
                ['icon' => 'fa-shield-check', 'title' => 'منتجات أصلية', 'desc' => 'ضمان الجودة على جميع المنتجات', 'color' => 'blue'],
                ['icon' => 'fa-truck-fast', 'title' => 'توصيل سريع', 'desc' => 'نوصل لجميع أنحاء مصر', 'color' => 'purple'],
                ['icon' => 'fa-redo', 'title' => 'استرجاع سهل', 'desc' => 'سياسة إرجاع مرنة 14 يوم', 'color' => 'orange'],
            ];
            @endphp
            
            @foreach($features as $feature)
            <div class="bg-white rounded-2xl p-6 text-center shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                <div class="w-16 h-16 bg-{{ $feature['color'] }}-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas {{ $feature['icon'] }} text-2xl text-{{ $feature['color'] }}-600"></i>
                </div>
                <h4 class="font-bold text-gray-900 mb-2">{{ $feature['title'] }}</h4>
                <p class="text-gray-600 text-sm">{{ $feature['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Newsletter for Offers -->
<section class="py-20 bg-gradient-to-br from-primary-blue via-blue-800 to-primary-blue relative overflow-hidden">
    <!-- Background Decorations -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 right-10 w-64 h-64 bg-primary-yellow rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 left-10 w-80 h-80 bg-white rounded-full blur-3xl"></div>
    </div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-2xl mx-auto text-center">
            <div class="inline-block mb-6 relative">
                <div class="absolute inset-0 bg-primary-yellow rounded-full blur-lg animate-ping opacity-50"></div>
                <div class="relative w-20 h-20 bg-primary-yellow rounded-full flex items-center justify-center shadow-xl">
                    <i class="fas fa-bell text-3xl text-primary-blue animate-swing"></i>
                </div>
            </div>
            
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">لا تفوت أي عرض!</h2>
            <p class="text-white/80 mb-8 text-lg">اشترك في نشرتنا البريدية لتصلك العروض الحصرية قبل الجميع</p>
            
            <form class="flex flex-col sm:flex-row gap-4 max-w-lg mx-auto">
                <div class="flex-1 relative">
                    <input type="email" placeholder="بريدك الإلكتروني" 
                           class="w-full bg-white/10 backdrop-blur border-2 border-white/20 rounded-xl px-5 py-4 text-white placeholder-white/60 focus:ring-2 focus:ring-primary-yellow focus:border-transparent">
                    <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-white/50"></i>
                </div>
                <button type="submit" 
                        class="bg-gradient-to-r from-primary-yellow to-amber-400 text-primary-blue px-8 py-4 rounded-xl font-bold hover:shadow-xl transition transform hover:scale-105 flex items-center justify-center gap-2">
                    <i class="fas fa-paper-plane"></i>
                    اشتراك
                </button>
            </form>
            
            <p class="text-white/50 text-sm mt-4">
                <i class="fas fa-lock ml-1"></i>
                نحترم خصوصيتك ولن نشارك بريدك مع أي طرف ثالث
            </p>
        </div>
    </div>
</section>

<script>
function countdown() {
    return {
        days: '00',
        hours: '00', 
        minutes: '00',
        seconds: '00',
        startCountdown() {
            // Set end date to end of current month
            const now = new Date();
            const endDate = new Date(now.getFullYear(), now.getMonth() + 1, 0, 23, 59, 59);
            
            const updateTimer = () => {
                const now = new Date().getTime();
                const distance = endDate.getTime() - now;
                
                this.days = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
                this.hours = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                this.minutes = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                this.seconds = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');
            };
            
            updateTimer();
            setInterval(updateTimer, 1000);
        }
    }
}
</script>

<style>
@keyframes swing {
    0%, 100% { transform: rotate(-10deg); }
    50% { transform: rotate(10deg); }
}
.animate-swing {
    animation: swing 1s ease-in-out infinite;
}
</style>
@endsection
