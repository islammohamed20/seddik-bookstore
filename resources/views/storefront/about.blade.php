@extends('layouts.storefront')

@section('title', __('من نحن') . ' - ' . __('مكتبة الصديق'))

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-primary-blue via-blue-800 to-primary-blue py-24 overflow-hidden">
    <!-- Background Decorations -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 right-20 w-72 h-72 bg-primary-yellow rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-10 left-20 w-96 h-96 bg-white rounded-full blur-3xl"></div>
    </div>
    
    <!-- Pattern Overlay -->
    <div class="absolute inset-0 opacity-5" style="background-image: url(&quot;data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23fff' fill-opacity='0.4'%3E%3Cpath d='M0 0h20v20H0V0zm20 20h20v20H20V20z'/%3E%3C/g%3E%3C/svg%3E&quot;);"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center justify-center gap-2 text-sm text-white/70">
                <li><a href="{{ route('home') }}" class="hover:text-white transition"><i class="fas fa-home"></i></a></li>
                <li>/</li>
                <li class="text-primary-yellow font-medium">من نحن</li>
            </ol>
        </nav>
        
        <div class="text-center">
            <span class="inline-block bg-primary-yellow text-primary-blue px-4 py-2 rounded-full text-sm font-bold mb-6">
                <i class="fas fa-book-open ml-1"></i>
                تعرف علينا
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6">
                مكتبة الصديق
            </h1>
            <p class="text-white/80 text-xl max-w-3xl mx-auto leading-relaxed">
                شريكك في رحلة التعليم والإبداع منذ أكثر من 15 عاماً
                <br>
                <span class="text-primary-yellow">الجودة • الثقة • التميز</span>
            </p>
        </div>
    </div>
    
    <!-- Wave Decoration -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="white"/>
        </svg>
    </div>
</section>

<!-- About Content -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-2 gap-12 items-center mb-20">
            <!-- Text Content -->
            <div>
                <span class="inline-block bg-primary-yellow/20 text-primary-yellow px-4 py-2 rounded-full text-sm font-bold mb-6">
                    <i class="fas fa-history ml-2"></i>
                    قصتنا
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-primary-blue mb-6 leading-tight">
                    رحلة 15 عاماً 
                    <span class="text-primary-yellow">من التميز والإبداع</span>
                </h2>
                <div class="space-y-4 text-gray-600 leading-relaxed">
                    <p>
                        بدأت مكتبة الصديق رحلتها في قلب أسيوط كمكتبة صغيرة بحلم كبير - أن تكون الوجهة الأولى لكل طالب ومعلم وفنان في المنطقة.
                    </p>
                    <p>
                        على مدار السنوات، نمت المكتبة لتصبح واحدة من أكبر المكتبات في صعيد مصر، حيث نوفر أكثر من 10,000 منتج من الأدوات المدرسية والمكتبية ومستلزمات الرسم والأعمال اليدوية.
                    </p>
                    <p>
                        اليوم، نفتخر بخدمة آلاف العملاء سنوياً، ونستمر في توسيع تشكيلتنا لتلبية احتياجات مجتمعنا المتنامي.
                    </p>
                </div>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-3 gap-4 mt-8">
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <div class="text-2xl font-bold text-primary-blue">+15</div>
                        <div class="text-sm text-gray-500">سنة خبرة</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <div class="text-2xl font-bold text-primary-yellow">+10K</div>
                        <div class="text-sm text-gray-500">منتج</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <div class="text-2xl font-bold text-green-600">+50K</div>
                        <div class="text-sm text-gray-500">عميل سعيد</div>
                    </div>
                </div>
            </div>
            
            <!-- Visual -->
            <div class="relative">
                <div class="relative z-10">
                    <!-- Main Card -->
                    <div class="bg-gradient-to-br from-primary-yellow via-yellow-400 to-amber-500 rounded-3xl p-10 text-center shadow-2xl transform rotate-2 hover:rotate-0 transition-transform duration-500">
                        <div class="text-8xl font-black text-primary-blue mb-2">+15</div>
                        <div class="text-2xl font-bold text-primary-blue">عاماً من الخبرة</div>
                        <div class="mt-4 flex justify-center gap-1">
                            @for($i = 0; $i < 5; $i++)
                            <i class="fas fa-star text-primary-blue text-xl"></i>
                            @endfor
                        </div>
                    </div>
                    
                    <!-- Floating Cards -->
                    <div class="absolute -bottom-8 -left-8 bg-white rounded-2xl shadow-2xl p-5 transform -rotate-6 hover:rotate-0 transition-transform duration-500 z-20">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-users text-green-600 text-xl"></i>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900">+50K</div>
                                <div class="text-gray-500 text-sm">عميل سعيد</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="absolute -top-8 -right-8 bg-white rounded-2xl shadow-2xl p-5 transform rotate-6 hover:rotate-0 transition-transform duration-500 z-20">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-box text-primary-blue text-xl"></i>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900">+10K</div>
                                <div class="text-gray-500 text-sm">منتج متنوع</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mission & Vision -->
        <div class="grid md:grid-cols-2 gap-8 mb-20">
            <div class="group bg-white rounded-3xl shadow-xl p-8 border-b-4 border-primary-yellow hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="w-20 h-20 bg-gradient-to-br from-primary-yellow to-amber-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-bullseye text-3xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-primary-blue mb-4">رسالتنا</h3>
                <p class="text-gray-600 leading-relaxed">
                    توفير أفضل الأدوات التعليمية والإبداعية بأسعار مناسبة، مع ضمان جودة المنتجات وتميز الخدمة، لنكون شريكاً حقيقياً في نجاح عملائنا.
                </p>
            </div>
            <div class="group bg-white rounded-3xl shadow-xl p-8 border-b-4 border-primary-blue hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="w-20 h-20 bg-gradient-to-br from-primary-blue to-blue-700 rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-eye text-3xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-primary-blue mb-4">رؤيتنا</h3>
                <p class="text-gray-600 leading-relaxed">
                    أن نكون المكتبة الرائدة في مصر، معروفين بتنوع منتجاتنا، وجودة خدماتنا، وثقة عملائنا، مع التوسع المستمر لخدمة مجتمعات جديدة.
                </p>
            </div>
        </div>

        <!-- Values -->
        <div class="text-center mb-12">
            <span class="inline-block bg-primary-blue/10 text-primary-blue px-4 py-2 rounded-full text-sm font-bold mb-4">
                <i class="fas fa-heart ml-2"></i>
                قيمنا
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-primary-blue mb-4">ما يميزنا عن الآخرين</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">نلتزم بمجموعة من القيم التي تشكل هويتنا وتوجه عملنا اليومي</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-20">
            @php
            $values = [
                ['icon' => 'fa-medal', 'title' => 'الجودة', 'desc' => 'نختار منتجاتنا بعناية لضمان أعلى جودة لعملائنا', 'color' => 'green'],
                ['icon' => 'fa-handshake', 'title' => 'الثقة', 'desc' => 'علاقات طويلة الأمد مبنية على الصدق والشفافية', 'color' => 'blue'],
                ['icon' => 'fa-smile', 'title' => 'رضا العميل', 'desc' => 'العميل في قلب كل قرار نتخذه وهدفنا إسعاده', 'color' => 'yellow'],
                ['icon' => 'fa-lightbulb', 'title' => 'الابتكار', 'desc' => 'نسعى دائماً لتقديم كل جديد ومبتكر في السوق', 'color' => 'purple'],
            ];
            @endphp
            
            @foreach($values as $index => $value)
            <div class="group bg-white rounded-2xl shadow-lg p-6 text-center hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                <div class="w-16 h-16 bg-{{ $value['color'] }}-100 rounded-2xl flex items-center justify-center mx-auto mb-5 group-hover:scale-110 transition-transform">
                    <i class="fas {{ $value['icon'] }} text-2xl text-{{ $value['color'] }}-600"></i>
                </div>
                <h4 class="font-bold text-gray-900 text-lg mb-3">{{ $value['title'] }}</h4>
                <p class="text-gray-600 text-sm leading-relaxed">{{ $value['desc'] }}</p>
            </div>
            @endforeach
        </div>

        <!-- Bingo Partnership -->
        <div class="relative bg-gradient-to-br from-primary-blue via-blue-800 to-primary-blue rounded-3xl p-8 md:p-12 text-white overflow-hidden">
            <!-- Background Decorations -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary-yellow rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-80 h-80 bg-white rounded-full blur-3xl"></div>
            </div>
            
            <div class="relative z-10 grid md:grid-cols-2 gap-8 items-center">
                <div>
                    <span class="inline-flex items-center gap-2 bg-primary-yellow text-primary-blue px-4 py-2 rounded-full text-sm font-bold mb-6">
                        <i class="fas fa-award"></i>
                        وكيل معتمد
                        <i class="fas fa-check-circle text-green-600"></i>
                    </span>
                    <h2 class="text-3xl md:text-4xl font-bold mb-6">
                        الوكيل الحصري لمنتجات 
                        <span class="text-primary-yellow">Bingo</span>
                    </h2>
                    <p class="text-white/80 leading-relaxed mb-8">
                        نفتخر بكوننا الوكيل المعتمد والحصري لمنتجات Bingo في أسيوط والمناطق المجاورة. منتجات Bingo معروفة بجودتها العالية وتصاميمها المبتكرة التي تناسب جميع الأعمار.
                    </p>
                    
                    <div class="flex flex-wrap gap-3 mb-8">
                        <span class="bg-white/10 px-4 py-2 rounded-full text-sm backdrop-blur">
                            <i class="fas fa-shield-check text-primary-yellow ml-1"></i>
                            منتجات أصلية
                        </span>
                        <span class="bg-white/10 px-4 py-2 rounded-full text-sm backdrop-blur">
                            <i class="fas fa-certificate text-primary-yellow ml-1"></i>
                            ضمان الجودة
                        </span>
                        <span class="bg-white/10 px-4 py-2 rounded-full text-sm backdrop-blur">
                            <i class="fas fa-tags text-primary-yellow ml-1"></i>
                            أسعار تنافسية
                        </span>
                    </div>
                    
                    <a href="{{ route('bingo') }}" 
                       class="group inline-flex items-center gap-3 bg-gradient-to-r from-primary-yellow to-yellow-400 text-primary-blue px-8 py-4 rounded-xl font-bold hover:shadow-xl transition transform hover:scale-105">
                        تصفح منتجات Bingo
                        <i class="fas fa-arrow-left transform group-hover:-translate-x-2 transition-transform"></i>
                    </a>
                </div>
                <div class="flex justify-center">
                    <div class="relative">
                        <div class="absolute inset-0 bg-primary-yellow/30 rounded-full blur-2xl animate-pulse"></div>
                        <div class="relative bg-white rounded-3xl p-10 shadow-2xl">
                            <div class="text-6xl font-black text-primary-blue text-center mb-2">BINGO</div>
                            <div class="flex justify-center gap-1 mb-2">
                                @for($i = 0; $i < 5; $i++)
                                <i class="fas fa-star text-primary-yellow"></i>
                                @endfor
                            </div>
                            <div class="text-center text-gray-600 font-semibold">Official Dealer</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <span class="inline-block bg-primary-yellow/20 text-primary-yellow px-4 py-2 rounded-full text-sm font-bold mb-4">
                <i class="fas fa-users ml-1"></i>
                فريقنا
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-primary-blue mb-4">فريق العمل</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">فريق متخصص ومدرب لخدمتكم بأفضل طريقة ممكنة</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8 max-w-4xl mx-auto">
            @php
            $team = [
                ['name' => 'خدمة العملاء', 'role' => 'متاحين 7 أيام', 'icon' => 'fa-headset', 'color' => 'blue'],
                ['name' => 'فريق المبيعات', 'role' => 'خبرة +10 سنوات', 'icon' => 'fa-user-tie', 'color' => 'green'],
                ['name' => 'فريق التوصيل', 'role' => 'سريع وآمن', 'icon' => 'fa-truck', 'color' => 'yellow'],
            ];
            @endphp
            
            @foreach($team as $member)
            <div class="bg-white rounded-2xl shadow-lg p-8 text-center hover:shadow-xl transition transform hover:-translate-y-2">
                <div class="w-24 h-24 bg-{{ $member['color'] }}-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas {{ $member['icon'] }} text-4xl text-{{ $member['color'] }}-600"></i>
                </div>
                <h4 class="text-xl font-bold text-gray-900 mb-2">{{ $member['name'] }}</h4>
                <p class="text-gray-500">{{ $member['role'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-20 bg-gradient-to-r from-primary-yellow via-yellow-400 to-amber-500">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-primary-blue mb-4">هل لديك استفسار؟</h2>
        <p class="text-primary-blue/80 mb-8 max-w-xl mx-auto text-lg">نحن هنا لمساعدتك. تواصل معنا الآن وسنرد عليك في أقرب وقت</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('contact') }}" 
               class="inline-flex items-center justify-center bg-primary-blue text-white px-8 py-4 rounded-xl font-bold hover:bg-primary-blue/90 transition transform hover:scale-105 shadow-lg">
                <i class="fas fa-envelope ml-2"></i>
                تواصل معنا
            </a>
            <a href="https://wa.me/201223694848" 
               class="inline-flex items-center justify-center bg-green-600 text-white px-8 py-4 rounded-xl font-bold hover:bg-green-700 transition transform hover:scale-105 shadow-lg">
                <i class="fab fa-whatsapp ml-2"></i>
                واتساب
            </a>
        </div>
    </div>
</section>
@endsection
