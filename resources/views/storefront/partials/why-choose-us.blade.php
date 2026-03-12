<!-- Why Choose Us Section -->
<section class="py-20 bg-gradient-to-br from-primary-blue via-primary-blue to-blue-900 relative overflow-hidden">
    <!-- Background Decorations -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 right-10 w-72 h-72 bg-primary-yellow rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 left-10 w-96 h-96 bg-white rounded-full blur-3xl"></div>
    </div>
    
    <div class="container mx-auto px-4 relative z-10">
        <!-- Section Header -->
        <div class="text-center mb-10 md:mb-16">
            <span class="inline-block bg-primary-yellow text-primary-blue px-4 py-2 rounded-full text-sm font-bold mb-4">
                <i class="fas fa-star ml-1"></i>
                مميزاتنا
            </span>
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-4">لماذا تختار مكتبة الصديق؟</h2>
            <p class="text-white/70 max-w-2xl mx-auto">نسعى دائماً لتقديم أفضل تجربة تسوق لعملائنا الكرام</p>
        </div>

        <div class="md:hidden text-right">
            <div class="grid grid-cols-2 gap-4">
                @php 
                $benefits = [
                    ['icon' => 'fa-shield-check', 'title' => 'منتجات أصلية 100%', 'desc' => 'جميع منتجاتنا أصلية ومضمونة من أفضل العلامات التجارية', 'color' => 'from-green-400 to-emerald-500'],
                    ['icon' => 'fa-truck-fast', 'title' => 'شحن سريع وآمن', 'desc' => 'توصيل سريع لجميع محافظات مصر خلال 2-5 أيام عمل', 'color' => 'from-blue-400 to-cyan-500'],
                    ['icon' => 'fa-tags', 'title' => 'أسعار تنافسية', 'desc' => 'أفضل الأسعار في السوق مع عروض وخصومات مستمرة', 'color' => 'from-yellow-400 to-orange-500'],
                    ['icon' => 'fa-headset', 'title' => 'دعم فني متميز', 'desc' => 'فريق خدمة عملاء محترف جاهز لمساعدتك على مدار الساعة', 'color' => 'from-purple-400 to-violet-500'],
                    ['icon' => 'fa-rotate-left', 'title' => 'إرجاع سهل', 'desc' => 'سياسة إرجاع مرنة خلال 14 يوم بدون أي تعقيدات', 'color' => 'from-pink-400 to-rose-500'],
                    ['icon' => 'fa-gift', 'title' => 'عروض حصرية', 'desc' => 'خصومات خاصة للأعضاء وعروض موسمية لا تُفوت', 'color' => 'from-red-400 to-red-500'],
                ];
                @endphp
                
                @foreach($benefits as $index => $b)
                <div class="group relative bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                    <div class="relative mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br {{ $b['color'] }} rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="fas {{ $b['icon'] }} text-xl text-white"></i>
                        </div>
                        <span class="absolute -top-2 -right-2 w-6 h-6 bg-primary-yellow text-primary-blue text-xs font-bold rounded-full flex items-center justify-center shadow">
                            {{ $index + 1 }}
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-white mb-2">{{ $b['title'] }}</h3>
                    <p class="text-white/70 text-sm leading-relaxed">{{ $b['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <div class="hidden md:grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php 
            $benefits = [
                ['icon' => 'fa-shield-check', 'title' => 'منتجات أصلية 100%', 'desc' => 'جميع منتجاتنا أصلية ومضمونة من أفضل العلامات التجارية', 'color' => 'from-green-400 to-emerald-500'],
                ['icon' => 'fa-truck-fast', 'title' => 'شحن سريع وآمن', 'desc' => 'توصيل سريع لجميع محافظات مصر خلال 2-5 أيام عمل', 'color' => 'from-blue-400 to-cyan-500'],
                ['icon' => 'fa-tags', 'title' => 'أسعار تنافسية', 'desc' => 'أفضل الأسعار في السوق مع عروض وخصومات مستمرة', 'color' => 'from-yellow-400 to-orange-500'],
                ['icon' => 'fa-headset', 'title' => 'دعم فني متميز', 'desc' => 'فريق خدمة عملاء محترف جاهز لمساعدتك على مدار الساعة', 'color' => 'from-purple-400 to-violet-500'],
                ['icon' => 'fa-rotate-left', 'title' => 'إرجاع سهل', 'desc' => 'سياسة إرجاع مرنة خلال 14 يوم بدون أي تعقيدات', 'color' => 'from-pink-400 to-rose-500'],
                ['icon' => 'fa-gift', 'title' => 'عروض حصرية', 'desc' => 'خصومات خاصة للأعضاء وعروض موسمية لا تُفوت', 'color' => 'from-red-400 to-red-500'],
            ];
            @endphp
            
            @foreach($benefits as $index => $b)
            <div class="group relative bg-white/10 backdrop-blur-sm rounded-2xl p-6 hover:bg-white transition-all duration-500 transform hover:-translate-y-2 border border-white/20 hover:border-transparent hover:shadow-2xl">
                <!-- Icon -->
                <div class="relative mb-5">
                    <div class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br {{ $b['color'] }} rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas {{ $b['icon'] }} text-xl md:text-2xl text-white"></i>
                    </div>
                    <!-- Number Badge -->
                    <span class="absolute -top-2 -right-2 w-7 h-7 bg-primary-yellow text-primary-blue text-xs md:text-sm font-bold rounded-full flex items-center justify-center shadow">
                        {{ $index + 1 }}
                    </span>
                </div>
                
                <!-- Title -->
                <h3 class="text-lg md:text-xl font-bold text-white group-hover:text-primary-blue mb-3 transition-colors">
                    {{ $b['title'] }}
                </h3>
                
                <!-- Description -->
                <p class="text-white/70 group-hover:text-gray-600 leading-relaxed transition-colors text-sm md:text-base">
                    {{ $b['desc'] }}
                </p>
                
                <!-- Hover Arrow -->
                <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="text-primary-blue text-sm font-semibold flex items-center">
                        معرفة المزيد
                        <i class="fas fa-arrow-left mr-2 text-xs"></i>
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Stats Row -->
        <div class="mt-12 md:mt-16 grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            @php
            $stats = [
                ['number' => '10K+', 'label' => 'عميل سعيد', 'icon' => 'fa-users'],
                ['number' => '5K+', 'label' => 'منتج متوفر', 'icon' => 'fa-box'],
                ['number' => '27', 'label' => 'محافظة نخدمها', 'icon' => 'fa-map-marker-alt'],
                ['number' => '99%', 'label' => 'رضا العملاء', 'icon' => 'fa-smile'],
            ];
            @endphp
            
            @foreach($stats as $stat)
            <div class="text-center p-4 md:p-6 bg-white/5 rounded-2xl border border-white/10">
                <div class="text-primary-yellow mb-2">
                    <i class="fas {{ $stat['icon'] }} text-xl md:text-2xl"></i>
                </div>
                <div class="text-2xl md:text-4xl font-bold text-white mb-1">{{ $stat['number'] }}</div>
                <div class="text-white/60 text-xs md:text-sm">{{ $stat['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>
