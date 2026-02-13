<!-- Bingo Authorized Dealer Section -->
<section class="py-20 bg-gradient-to-br from-primary-blue via-blue-900 to-primary-blue text-white relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-0 left-0 w-96 h-96 bg-primary-yellow/10 rounded-full blur-3xl transform -translate-x-1/2 -translate-y-1/2 animate-pulse"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-primary-yellow/10 rounded-full blur-3xl transform translate-x-1/2 translate-y-1/2 animate-pulse" style="animation-delay: 1s;"></div>
        <!-- Stars Pattern -->
        <div class="absolute inset-0 opacity-10">
            @for($i = 0; $i < 20; $i++)
            <div class="absolute w-2 h-2 bg-white rounded-full animate-pulse" 
                 style="top: {{ rand(5, 95) }}%; left: {{ rand(5, 95) }}%; animation-delay: {{ rand(0, 20) / 10 }}s;"></div>
            @endfor
        </div>
    </div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-12">
            <!-- Content -->
            <div class="flex-1 text-center lg:text-right">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-primary-yellow to-yellow-400 text-primary-blue rounded-full font-bold mb-6 shadow-lg">
                    <i class="fas fa-award"></i>
                    <span>وكيل معتمد</span>
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
                
                <!-- Title -->
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                    نحن وكيل معتمد
                    <span class="block text-primary-yellow mt-2">من شركة Bingo</span>
                </h2>
                
                <!-- Description -->
                <p class="text-xl text-white/80 mb-8 max-w-xl mx-auto lg:mx-0">
                    احصل على منتجات Bingo الأصلية بضمان الجودة وأفضل الأسعار
                </p>
                
                <!-- Features -->
                <div class="flex flex-wrap justify-center lg:justify-start gap-4 mb-8">
                    <div class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-full">
                        <i class="fas fa-shield-check text-primary-yellow"></i>
                        <span>منتجات أصلية</span>
                    </div>
                    <div class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-full">
                        <i class="fas fa-tags text-primary-yellow"></i>
                        <span>أسعار منافسة</span>
                    </div>
                    <div class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-full">
                        <i class="fas fa-certificate text-primary-yellow"></i>
                        <span>ضمان الجودة</span>
                    </div>
                </div>
                
                <!-- CTA Button -->
                <a href="{{ route('bingo') }}" 
                   class="group inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-primary-yellow to-yellow-400 text-primary-blue font-bold rounded-full shadow-xl hover:shadow-2xl transition-all transform hover:-translate-y-1 hover:scale-105">
                    <span>تسوق منتجات Bingo</span>
                    <i class="fas fa-arrow-left transform group-hover:-translate-x-2 transition-transform"></i>
                </a>
            </div>
            
            <!-- Visual -->
            <div class="flex-1 flex justify-center">
                <div class="relative">
                    <!-- Glowing Ring -->
                    <div class="absolute inset-0 bg-primary-yellow/30 rounded-full blur-2xl animate-pulse"></div>
                    
                    <!-- Main Badge -->
                    <div class="relative w-72 h-72 md:w-80 md:h-80 bg-gradient-to-br from-primary-yellow via-yellow-400 to-amber-500 rounded-full flex items-center justify-center shadow-2xl transform hover:scale-105 transition-transform duration-500">
                        <!-- Inner Circle -->
                        <div class="w-56 h-56 md:w-64 md:h-64 bg-white rounded-full flex flex-col items-center justify-center shadow-inner">
                            <span class="text-6xl md:text-7xl font-black text-primary-blue tracking-tight">BINGO</span>
                            <div class="flex items-center gap-1 mt-2">
                                @for($i = 0; $i < 5; $i++)
                                <i class="fas fa-star text-primary-yellow text-lg"></i>
                                @endfor
                            </div>
                            <span class="text-sm text-gray-600 mt-1 font-semibold">AUTHORIZED DEALER</span>
                        </div>
                    </div>
                    
                    <!-- Floating Elements -->
                    <div class="absolute -top-4 -right-4 w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-xl animate-bounce" style="animation-duration: 2s;">
                        <i class="fas fa-check-double text-2xl text-green-500"></i>
                    </div>
                    <div class="absolute -bottom-4 -left-4 w-14 h-14 bg-primary-yellow rounded-full flex items-center justify-center shadow-xl animate-bounce" style="animation-duration: 2.5s;">
                        <i class="fas fa-medal text-xl text-primary-blue"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
