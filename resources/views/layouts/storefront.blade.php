<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- SEO Meta Tags --}}
    @php
        use App\Models\Setting;
        $siteName = Setting::getValue('site_name', 'مكتبة الصديق');
        $metaTitle = Setting::getValue('meta_title', $siteName);
        $metaDescription = Setting::getValue('meta_description', Setting::getValue('site_description', 'مكتبة الصديق - متجرك المفضل للأدوات المدرسية والمكتبية وألعاب الأطفال التعليمية منذ 1987. توصيل لجميع محافظات مصر.'));
        $metaKeywords = Setting::getValue('meta_keywords', 'مكتبة, أدوات مدرسية, ألعاب تعليمية, منتسوري, أسيوط, مصر, كتب, قرطاسية');
    @endphp
    <title>@yield('title', $metaTitle)</title>
    <meta name="description" content="@yield('meta_description', $metaDescription)">
    <meta name="keywords" content="@yield('meta_keywords', $metaKeywords)">
    <meta name="author" content="{{ $siteName }}">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
    
    {{-- Open Graph --}}
    @php
        $siteLogoPathForOg = Setting::getValue('site_logo');
        $ogDefaultImage = $siteLogoPathForOg ? asset('storage/'.$siteLogoPathForOg) : asset('images/og-default.jpg');
    @endphp
    <meta property="og:title" content="@yield('og_title', $metaTitle)">
    <meta property="og:description" content="@yield('og_description', $metaDescription)">
    <meta property="og:image" content="@yield('og_image', $ogDefaultImage)">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="ar_EG">
    
    {{-- Twitter Cards --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', $metaTitle)">
    <meta name="twitter:description" content="@yield('og_description', 'متجرك المفضل للأدوات المدرسية والمكتبية وألعاب الأطفال التعليمية')">
    <meta name="twitter:image" content="@yield('og_image', $ogDefaultImage)">
    
    {{-- Favicon --}}
    @php
        $faviconPath = Setting::getValue('site_favicon');
        $faviconUrl = $faviconPath ? asset('storage/'.$faviconPath) : asset('favicon.ico');
    @endphp
    <link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
    
    {{-- Cookie Consent Styles --}}
    <link href="{{ asset('css/cookie-consent.css') }}" rel="stylesheet">
    
    {{-- Schema.org JSON-LD --}}
    @yield('schema')
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "{{ Setting::getValue('site_name', 'مكتبة الصديق') }}",
        "alternateName": "{{ Setting::getValue('site_name_en', 'El-Sedeek Store') }}",
        "image": "{{ $siteLogo = Setting::getValue('site_logo') ? asset('storage/'.Setting::getValue('site_logo')) : asset('images/logo.png') }}",
        "description": "متجر متخصص في بيع ألعاب الأطفال التعليمية والمستلزمات المدرسية منذ 1987",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "{{ Setting::getValue('site_address', 'شارع الجمهورية، بجوار الوطنية مول') }}",
            "addressLocality": "أسيوط",
            "addressCountry": "EG"
        },
        "telephone": "{{ Setting::getValue('site_phone', '+201223694848') }}",
        "url": "{{ config('app.url') }}",
        "openingHoursSpecification": [
            {
                "@type": "OpeningHoursSpecification",
                "dayOfWeek": ["Saturday", "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday"],
                "opens": "09:00",
                "closes": "23:00"
            },
            {
                "@type": "OpeningHoursSpecification",
                "dayOfWeek": "Friday",
                "opens": "16:00",
                "closes": "23:00"
            }
        ],
        "priceRange": "$$"
    }
    </script>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&family=Tajawal:wght@400;500;600;700&family=Poppins:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Assets -->
    @php
        $viteHotFile = public_path('hot');
        $viteManifestFile = public_path('build/manifest.json');
    @endphp

    @if (file_exists($viteHotFile) || file_exists($viteManifestFile))
        <!-- Vite Assets -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Fallback (no Vite manifest/hot file available) -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            'primary-yellow': '#FFD700',
                            'primary-yellow-dark': '#FFC43F',
                            'primary-blue': '#003399',
                            'primary-blue-dark': '#003D7A',
                            'primary-red': '#FF3333',
                            'primary-red-dark': '#E53935',
                        },
                        fontFamily: {
                            sans: ['Cairo', 'Tajawal', 'Poppins', 'Inter', 'ui-sans-serif', 'system-ui'],
                        },
                    }
                }
            }
        </script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endif
    
    <style>
        body {
            font-family: 'Cairo', 'Tajawal', 'Poppins', 'Inter', sans-serif;
        }

        [x-cloak] { display: none !important; }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900 antialiased min-h-screen flex flex-col" dir="rtl" x-data="{ mobileMenuOpen: false, searchOpen: false }">
    
    <!-- Top Bar -->
    <div class="bg-gradient-to-r from-primary-blue to-blue-800 text-white py-2 text-sm">
        <div class="container mx-auto px-4 flex flex-wrap items-center justify-between gap-2">
            <div class="flex items-center gap-6">
                @php
                    $sitePhone = Setting::getValue('site_phone', '01223694848');
                    $siteEmail = Setting::getValue('site_email', 'info@seddik-library.com');
                    $siteAddress = Setting::getValue('site_address', 'أسيوط، مصر');
                    $facebookUrl = Setting::getValue('facebook_url');
                    $instagramUrl = Setting::getValue('instagram_url');
                    $whatsappNumber = Setting::getValue('whatsapp_number');
                    $whatsappUrl = $whatsappNumber ? ('https://wa.me/'.$whatsappNumber) : null;
                @endphp
                <a href="tel:{{ $sitePhone }}" class="hover:text-primary-yellow transition-all duration-200 flex items-center gap-2 font-medium">
                    <i class="fas fa-phone animate-pulse"></i>
                    <span class="hidden sm:inline" dir="ltr">{{ $sitePhone }}</span>
                    <span class="sm:hidden">اتصل بنا</span>
                </a>
                <a href="mailto:{{ $siteEmail }}" class="hover:text-primary-yellow transition-all duration-200 hidden md:flex items-center gap-2">
                    <i class="fas fa-envelope"></i>
                    <span class="hidden lg:inline">{{ $siteEmail }}</span>
                    <span class="lg:hidden">البريد</span>
                </a>
                <span class="hidden lg:inline-flex items-center gap-2 text-white/80 text-xs">
                    <i class="fas fa-map-marker-alt text-primary-yellow"></i>
                    <span class="hidden xl:inline">{{ $siteAddress }}</span>
                    <span class="xl:hidden">الموقع</span>
                </span>
            </div>
            <div class="flex items-center gap-4">
                @if($facebookUrl)
                    <a href="{{ $facebookUrl }}" target="_blank" class="hover:text-primary-yellow transition-all duration-200 hover:scale-110">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                @endif
                @if($instagramUrl)
                    <a href="{{ $instagramUrl }}" target="_blank" class="hover:text-primary-yellow transition-all duration-200 hover:scale-110">
                        <i class="fab fa-instagram"></i>
                    </a>
                @endif
                @php
                    $headerTiktokUrl = \App\Models\Setting::getValue('tiktok_url');
                @endphp
                @if($headerTiktokUrl)
                    <a href="{{ $headerTiktokUrl }}" target="_blank" class="hover:text-primary-yellow transition-all duration-200 hover:scale-110">
                        <i class="fab fa-tiktok"></i>
                    </a>
                @endif
                @if($whatsappUrl)
                    <a href="{{ $whatsappUrl }}" target="_blank" class="hover:text-primary-yellow transition-all duration-200 hover:scale-110">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                @endif
                @if($facebookUrl || $instagramUrl || $headerTiktokUrl || $whatsappUrl)
                    <span class="text-white/40 mx-1 hidden sm:inline">|</span>
                @endif
                @auth
                    <a href="{{ route('profile.edit') }}" class="hover:text-primary-yellow transition-all duration-200 flex items-center gap-1.5">
                        <i class="fas fa-user"></i>
                        <span class="hidden sm:inline">{{ __('حسابي') }}</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="hover:text-primary-yellow transition-all duration-200 flex items-center gap-1.5">
                        <i class="fas fa-sign-in-alt"></i>
                        <span class="hidden sm:inline">دخول</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Main Header / Navigation -->
    <header class="bg-white shadow-lg sticky top-0 z-30 border-b border-gray-100">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-3 md:py-4">
                <!-- Logo -->
                @php
                    $siteLogoPath = Setting::getValue('site_logo');
                    $siteLogoUrl = $siteLogoPath ? asset('storage/'.$siteLogoPath) : null;
                    $teckToysPage = \App\Models\Page::published()
                        ->where(function ($q) {
                            $q->where('slug', 'teck-toys-products')
                                ->orWhere('seo_keywords', 'like', '%teck toys%')
                                ->orWhere('seo_keywords', 'like', '%ألعاب تقنية%');
                        })
                        ->first();
                @endphp
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    @if($siteLogoUrl)
                        <div class="w-12 h-12 md:w-14 md:h-14 rounded-xl overflow-hidden bg-white flex items-center justify-center shadow-md group-hover:shadow-xl transition-all duration-300 group-hover:scale-105">
                            <img src="{{ $siteLogoUrl }}" alt="{{ Setting::getValue('site_name', 'مكتبة الصديق') }}" class="w-full h-full object-contain">
                        </div>
                    @else
                        <div class="w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-primary-yellow to-amber-400 rounded-xl flex items-center justify-center shadow-md group-hover:shadow-xl transition-all duration-300 group-hover:scale-105">
                            <i class="fas fa-book text-primary-blue text-2xl md:text-3xl"></i>
                        </div>
                    @endif
                    <div class="hidden sm:flex flex-col leading-tight">
                        <span class="text-xl md:text-2xl font-bold text-primary-blue" style="font-family: 'Cairo', sans-serif;">مكتبة الصديق</span>
                        <span class="text-xs text-gray-500 font-medium" style="font-family: 'Poppins', sans-serif;">El-Sedeek Store</span>
                    </div>
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden lg:flex items-center gap-1 font-medium">
                    <a href="{{ route('home') }}" class="px-4 py-2 rounded-lg text-gray-700 hover:text-primary-blue hover:bg-blue-50 transition-all duration-200 {{ request()->routeIs('home') ? 'bg-blue-50 text-primary-blue font-bold' : '' }}">
                        <i class="fas fa-home ml-1"></i> الرئيسية
                    </a>
                    <a href="{{ route('products.index') }}" class="px-4 py-2 rounded-lg text-gray-700 hover:text-primary-blue hover:bg-blue-50 transition-all duration-200 {{ request()->routeIs('products.*') ? 'bg-blue-50 text-primary-blue font-bold' : '' }}">
                        <i class="fas fa-shopping-bag ml-1"></i> المنتجات
                    </a>
                    @if($teckToysPage)
                        <a href="{{ route('page.show', $teckToysPage) }}" class="px-4 py-2 rounded-lg text-gray-700 hover:text-primary-blue hover:bg-indigo-50 transition-all duration-200 {{ request()->is('page/'.$teckToysPage->slug.'*') ? 'bg-indigo-50 text-primary-blue font-bold' : '' }}">
                            <i class="fas fa-robot ml-1"></i> {{ $teckToysPage->title_ar ?: $teckToysPage->title }}
                        </a>
                    @endif
                    <a href="/offers" class="px-4 py-2 rounded-lg text-gray-700 hover:text-primary-blue hover:bg-amber-50 transition-all duration-200 {{ request()->is('offers*') ? 'bg-amber-50 text-amber-700 font-bold' : '' }}">
                        <i class="fas fa-tag ml-1"></i> العروض
                    </a>
                    <a href="{{ route('contact') }}" class="px-4 py-2 rounded-lg text-gray-700 hover:text-primary-blue hover:bg-green-50 transition-all duration-200 {{ request()->routeIs('contact') ? 'bg-green-50 text-green-700 font-bold' : '' }}">
                        <i class="fas fa-phone ml-1"></i> اتصل بنا
                    </a>
                </nav>

                <!-- Cart & Actions -->
                <div class="flex items-center gap-2 md:gap-3">
                    <!-- Search Icon -->
                    <button @click="searchOpen = !searchOpen" class="p-2 md:p-2.5 w-11 h-11 rounded-lg flex items-center justify-center text-gray-600 hover:text-primary-blue hover:bg-gray-100 transition-all duration-200">
                        <i class="fas fa-search text-lg md:text-xl"></i>
                    </button>

                    <!-- Wishlist Button -->
                    <a href="{{ route('wishlist.index') }}" class="relative p-2 md:p-2.5 w-11 h-11 rounded-lg flex items-center justify-center text-gray-600 hover:text-pink-500 hover:bg-pink-50 transition-all duration-200">
                        <i class="fas fa-heart text-lg md:text-xl"></i>
                        @if(session('wishlist') && count(session('wishlist')) > 0)
                            <span class="absolute -top-1 -right-1 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-pink-500 rounded-full">
                                {{ count(session('wishlist')) }}
                            </span>
                        @endif
                    </a>

                    <!-- Cart Button -->
                    <a href="{{ route('cart.index') }}" class="relative inline-flex items-center gap-2 px-3 md:px-5 py-2 md:py-2.5 rounded-lg bg-gradient-to-r from-primary-yellow to-amber-400 hover:from-amber-400 hover:to-primary-yellow text-primary-blue font-bold shadow-md hover:shadow-lg transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-shopping-cart text-base md:text-lg"></i>
                        <span class="hidden sm:inline">السلة</span>
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="desktop-cart-count absolute -top-2 -right-2 inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-primary-red rounded-full animate-bounce">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                    </a>

                    <!-- Mobile Menu Toggle -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 w-11 h-11 rounded-lg flex items-center justify-center text-gray-700 hover:text-primary-blue hover:bg-gray-100 transition-all duration-200">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Menu Backdrop -->
    <div x-show="mobileMenuOpen" x-cloak
         class="fixed inset-0 bg-black bg-opacity-60 z-40"
         @click="mobileMenuOpen = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>

    <!-- Mobile Menu Panel -->
    <div x-show="mobileMenuOpen" x-cloak
         @click.stop
         class="fixed top-0 left-0 h-full w-72 sm:w-80 bg-white shadow-2xl z-50 overflow-y-auto p-6"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full">
            
            <!-- Close Button -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    @php
                        $mobileLogoPath = \App\Models\Setting::getValue('site_logo');
                        $mobileLogoUrl = $mobileLogoPath ? asset('storage/'.$mobileLogoPath) : null;
                    @endphp
                    @if($mobileLogoUrl)
                        <img src="{{ $mobileLogoUrl }}" alt="{{ \App\Models\Setting::getValue('site_name', 'متجر الصديق') }}" class="w-10 h-10 rounded-lg bg-white p-1 border border-gray-100">
                    @else
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-yellow to-amber-400 rounded-lg flex items-center justify-center">
                            <i class="fas fa-book text-primary-blue text-lg"></i>
                        </div>
                    @endif
                    <span class="font-bold text-gray-800">القائمة</span>
                </div>
                <button @click="mobileMenuOpen = false" class="p-2 hover:bg-gray-100 rounded-lg transition">
                    <i class="fas fa-times text-xl text-gray-600"></i>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex flex-col gap-2 font-medium">
                <a href="{{ route('home') }}" class="group flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-50 transition-all {{ request()->routeIs('home') ? 'bg-blue-50 text-primary-blue font-bold' : 'text-gray-700' }}">
                    <span>الرئيسية</span>
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg {{ request()->routeIs('home') ? 'bg-primary-blue text-white' : 'bg-gray-100 text-gray-600 group-hover:bg-primary-blue group-hover:text-white' }} transition-all">
                        <i class="fas fa-home"></i>
                    </div>
                </a>
                
                <a href="{{ route('products.index') }}" class="group flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-50 transition-all {{ request()->routeIs('products.*') ? 'bg-blue-50 text-primary-blue font-bold' : 'text-gray-700' }}">
                    <span>المنتجات</span>
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg {{ request()->routeIs('products.*') ? 'bg-primary-blue text-white' : 'bg-gray-100 text-gray-600 group-hover:bg-primary-blue group-hover:text-white' }} transition-all">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                </a>
                
                @if($teckToysPage)
                    <a href="{{ route('page.show', $teckToysPage) }}" class="group flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-indigo-50 transition-all {{ request()->is('page/'.$teckToysPage->slug.'*') ? 'bg-indigo-50 text-primary-blue font-bold' : 'text-gray-700' }}">
                        <span>{{ $teckToysPage->title }}</span>
                        <div class="w-8 h-8 flex items-center justify-center rounded-lg {{ request()->is('page/'.$teckToysPage->slug.'*') ? 'bg-primary-blue text-white' : 'bg-gray-100 text-gray-600 group-hover:bg-primary-blue group-hover:text-white' }} transition-all">
                            <i class="fas fa-robot"></i>
                        </div>
                    </a>
                @endif
                
                <a href="/offers" class="group flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-amber-50 transition-all {{ request()->is('offers*') ? 'bg-amber-50 text-amber-700 font-bold' : 'text-gray-700' }}">
                    <span>العروض</span>
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg {{ request()->is('offers*') ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-600 group-hover:bg-amber-600 group-hover:text-white' }} transition-all">
                        <i class="fas fa-tag"></i>
                    </div>
                </a>
                
                <a href="{{ route('contact') }}" class="group flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-green-50 transition-all {{ request()->routeIs('contact') ? 'bg-green-50 text-green-700 font-bold' : 'text-gray-700' }}">
                    <span>تواصل معنا</span>
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg {{ request()->routeIs('contact') ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600 group-hover:bg-green-600 group-hover:text-white' }} transition-all">
                        <i class="fas fa-phone"></i>
                    </div>
                </a>
            </nav>

            <!-- Divider -->
            <div class="border-t border-gray-200 my-6"></div>

            <!-- User Section -->
            <div class="space-y-2">
                @auth
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-50 text-gray-700 transition-all">
                        <div class="flex flex-col text-right">
                            <span class="text-sm font-medium">{{ Auth::user()->name }}</span>
                            <span class="text-xs text-gray-500">حسابي</span>
                        </div>
                        <div class="w-8 h-8 bg-gradient-to-br from-primary-blue to-blue-600 rounded-full flex items-center justify-center text-white">
                            <i class="fas fa-user text-sm"></i>
                        </div>
                    </a>
                    <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-50 text-gray-700 transition-all">
                        <span>طلباتي</span>
                        <i class="fas fa-box text-gray-600"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-primary-blue hover:bg-blue-700 text-white font-medium transition-all">
                        <span>تسجيل الدخول</span>
                        <i class="fas fa-sign-in-alt"></i>
                    </a>
                @endauth
            </div>

            <!-- Contact Info -->
            <div class="border-t border-gray-200 mt-6 pt-6">
                <p class="text-xs text-gray-500 mb-3">تواصل معنا</p>
                <div class="space-y-2 text-sm">
                    @php
                        $mobileSitePhone = $sitePhone ?? \App\Models\Setting::getValue('site_phone', '01223694848');
                        $mobileWhatsappNumber = $whatsappNumber ?? \App\Models\Setting::getValue('whatsapp_number');
                        $mobileWhatsappUrl = $mobileWhatsappNumber ? ('https://wa.me/'.$mobileWhatsappNumber) : null;
                        $mobileFacebookUrl = $facebookUrl ?? \App\Models\Setting::getValue('facebook_url');
                        $mobileInstagramUrl = $instagramUrl ?? \App\Models\Setting::getValue('instagram_url');
                    @endphp
                    <a href="tel:{{ $mobileSitePhone }}" class="flex items-center gap-2 text-gray-600 hover:text-primary-blue transition">
                        <span>{{ $mobileSitePhone }}</span>
                        <i class="fas fa-phone text-xs"></i>
                    </a>
                    <div class="flex items-center gap-3 pt-2 justify-start">
                        @if($mobileFacebookUrl)
                            <a href="{{ $mobileFacebookUrl }}" class="w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-full flex items-center justify-center transition">
                                <i class="fab fa-facebook-f text-xs"></i>
                            </a>
                        @endif
                        @if($mobileInstagramUrl)
                            <a href="{{ $mobileInstagramUrl }}" class="w-8 h-8 bg-pink-600 hover:bg-pink-700 text-white rounded-full flex items-center justify-center transition">
                                <i class="fab fa-instagram text-xs"></i>
                            </a>
                        @endif
                        @if($mobileWhatsappUrl)
                            <a href="{{ $mobileWhatsappUrl }}" class="w-8 h-8 bg-green-600 hover:bg-green-700 text-white rounded-full flex items-center justify-center transition">
                                <i class="fab fa-whatsapp text-xs"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Modal with Live Search -->
    <div x-data="liveSearch()" x-show="searchOpen" x-cloak
         class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-start justify-center pt-10 md:pt-20" @click="searchOpen = false">
        <div @click.stop class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden relative" x-show="searchOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100">
            
            <button @click="close()" class="absolute top-3 right-3 w-11 h-11 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-600 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
            
            <!-- Search Input -->
            <div class="p-4 border-b border-gray-100">
                <div class="relative">
                    <input type="text" 
                           x-ref="searchInput"
                           x-model="query"
                           @input.debounce.300ms="search()"
                           @keydown.escape="close()"
                           @keydown.enter="goToSearch()"
                           placeholder="ابحث عن منتج، قسم، أو ماركة..."
                           class="w-full px-5 py-4 pl-12 text-lg border-2 border-gray-200 rounded-xl focus:outline-none focus:border-primary-yellow focus:ring-2 focus:ring-primary-yellow/20 transition text-right">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl"></i>
                    <button x-show="query.length > 0" @click="query = ''; results = null" 
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <!-- Loading State -->
            <div x-show="loading" class="p-8 text-right">
                <i class="fas fa-spinner fa-spin text-3xl text-primary-yellow"></i>
                <p class="text-gray-500 mt-2">جاري البحث...</p>
            </div>
            
            <!-- Results -->
            <div x-show="!loading && results" class="max-h-[60vh] overflow-y-auto">
                <!-- Categories -->
                <template x-if="results && results.categories && results.categories.length > 0">
                    <div class="p-4 border-b border-gray-100">
                        <h4 class="text-sm font-semibold text-gray-500 mb-3">
                            <i class="fas fa-folder mr-1"></i> الأقسام
                        </h4>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="cat in results.categories" :key="cat.id">
                                <a :href="cat.url" 
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-primary-blue hover:text-white rounded-full text-sm transition">
                                    <span x-text="cat.name"></span>
                                </a>
                            </template>
                        </div>
                    </div>
                </template>
                
                <!-- Products -->
                <template x-if="results && results.products && results.products.length > 0">
                    <div class="p-4">
                        <h4 class="text-sm font-semibold text-gray-500 mb-3 flex justify-between">
                            <span><i class="fas fa-box mr-1"></i> المنتجات</span>
                            <span class="text-primary-blue" x-text="'(' + results.total + ' نتيجة)'"></span>
                        </h4>
                        <div class="space-y-2">
                            <template x-for="product in results.products" :key="product.id">
                                <a :href="product.url" 
                                   class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 transition group">
                                    <div class="w-16 h-16 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0">
                                        <template x-if="product.image">
                                            <img :src="product.image" :alt="product.name" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!product.image">
                                            <div class="w-full h-full flex items-center justify-center">
                                                <i class="fas fa-image text-gray-300"></i>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h5 class="font-semibold text-gray-900 group-hover:text-primary-blue truncate" x-text="product.name"></h5>
                                        <p class="text-sm text-gray-500" x-text="product.category"></p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="font-bold text-primary-red" x-text="product.price.toLocaleString('ar-EG') + ' ج.م'"></span>
                                            <template x-if="product.has_discount">
                                                <span class="text-sm text-gray-400 line-through" x-text="product.original_price.toLocaleString('ar-EG')"></span>
                                            </template>
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-left text-gray-300 group-hover:text-primary-blue"></i>
                                </a>
                            </template>
                        </div>
                    </div>
                </template>
                
                <!-- No Results -->
                <template x-if="results && results.products && results.products.length === 0 && query.length >= 2">
                    <div class="p-8 text-right">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-search text-2xl text-gray-300"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1">لا توجد نتائج</h4>
                        <p class="text-gray-500 text-sm">جرب البحث بكلمات مختلفة</p>
                    </div>
                </template>
                
                <!-- View All Results -->
                <template x-if="results && results.total > 8">
                    <div class="p-4 border-t border-gray-100 bg-gray-50">
                        <a :href="results.search_url" 
                           class="flex items-center justify-center gap-2 w-full py-3 bg-primary-blue text-white font-semibold rounded-xl hover:bg-primary-blue-dark transition">
                            <span>عرض جميع النتائج</span>
                            <span x-text="'(' + results.total + ')'"></span>
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                </template>
            </div>
            
            <!-- Quick Links (when no search) -->
            <div x-show="!loading && !results && query.length < 2" class="p-4">
                <p class="text-sm text-gray-500 mb-3">بحث سريع</p>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('products.index', ['type' => 'toy']) }}" class="px-4 py-2 bg-purple-100 text-purple-700 rounded-full text-sm hover:bg-purple-200 transition">
                        <i class="fas fa-puzzle-piece mr-1"></i> ألعاب
                    </a>
                    <a href="{{ route('offers') }}" class="px-4 py-2 bg-amber-100 text-amber-700 rounded-full text-sm hover:bg-amber-200 transition">
                        <i class="fas fa-tag mr-1"></i> العروض
                    </a>
                    <a href="{{ route('products.index') }}" class="px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm hover:bg-blue-200 transition">
                        <i class="fas fa-th-large mr-1"></i> جميع المنتجات
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function liveSearch() {
            return {
                query: '',
                results: null,
                loading: false,
                
                init() {
                    this.$watch('searchOpen', (value) => {
                        if (value) {
                            this.$nextTick(() => this.$refs.searchInput.focus());
                        } else {
                            this.query = '';
                            this.results = null;
                        }
                    });
                },
                
                async search() {
                    if (this.query.length < 2) {
                        this.results = null;
                        return;
                    }
                    
                    this.loading = true;
                    
                    try {
                        const response = await fetch('/api/search?q=' + encodeURIComponent(this.query));
                        this.results = await response.json();
                    } catch (error) {
                        console.error('Search error:', error);
                    } finally {
                        this.loading = false;
                    }
                },
                
                goToSearch() {
                    if (this.query.length >= 2) {
                        window.location.href = '/search?q=' + encodeURIComponent(this.query);
                    }
                }
            }
        }
    </script>

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-primary-blue-dark text-white mt-16">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Column 1: About -->
                <div>
                    <h3 class="text-xl font-bold mb-4 text-primary-yellow">من نحن</h3>
                    @php
                        $footerText = \App\Models\Setting::getValue('footer_text', 'مكتبة الصديق - متجر متخصص في بيع ألعاب الأطفال التعليمية والمستلزمات المدرسية منذ 1987');
                        $footerTextEn = \App\Models\Setting::getValue('footer_text_en', 'El-Sedeek Store - Your trusted shop for educational toys & school supplies since 1987');
                    @endphp
                    <p class="text-gray-300 text-sm leading-relaxed">
                        {{ $footerText }}
                    </p>
                </div>

                <!-- Column 2: Quick Links -->
                <div>
                    <h3 class="text-xl font-bold mb-4 text-primary-yellow">روابط سريعة</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('products.index') }}" class="text-gray-300 hover:text-primary-yellow transition-smooth">المنتجات</a></li>
                        <li><a href="/offers" class="text-gray-300 hover:text-primary-yellow transition-smooth">العروض</a></li>
                        <li><a href="/about" class="text-gray-300 hover:text-primary-yellow transition-smooth">من نحن</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-300 hover:text-primary-yellow transition-smooth">تواصل معنا</a></li>
                        <li><a href="{{ Auth::check() ? route('orders.index') : route('login') }}" class="text-gray-300 hover:text-primary-yellow transition-smooth">تتبع الطلب</a></li>
                    </ul>
                </div>

                <!-- Column 3: Contact -->
                <div>
                    <h3 class="text-xl font-bold mb-4 text-primary-yellow">تواصل معنا</h3>
                    <ul class="space-y-3 text-sm text-gray-300">
                        <li class="flex items-start gap-2">
                            <i class="fas fa-map-marker-alt text-primary-yellow mt-1"></i>
                            <span>{{ $siteAddress ?? \App\Models\Setting::getValue('site_address', 'شارع الجمهورية، بجوار الوطنية مول، أسيوط، مصر') }}</span>
                        </li>
                        @php
                            $footerMapUrl = \App\Models\Setting::getValue('google_maps_url', 'https://www.google.com/maps/place/%D9%85%D9%83%D8%AA%D8%A8%D8%A9+%D8%A7%D9%84%D8%B5%D8%AF%D9%8A%D9%82%E2%80%AD/@27.1831474,31.1803477,246m/data=!3m1!1e3!4m6!3m5!1s0x14450b3115db4073:0x7d8035da02779e18!8m2!3d27.1831605!4d31.1801331!16s%2Fg%2F11h7ks27cr?entry=ttu');
                        @endphp
                        @if($footerMapUrl)
                            <li class="flex items-center gap-2">
                                <i class="fas fa-map text-primary-yellow"></i>
                                <a href="{{ $footerMapUrl }}" target="_blank" class="hover:text-primary-yellow transition-smooth">
                                    عرض الموقع على الخريطة
                                </a>
                            </li>
                        @endif
                        <li class="flex items-center gap-2">
                            <i class="fas fa-phone text-primary-yellow"></i>
                            <a href="tel:{{ $sitePhone ?? \App\Models\Setting::getValue('site_phone', '01223694848') }}" class="hover:text-primary-yellow transition-smooth">
                                {{ $sitePhone ?? \App\Models\Setting::getValue('site_phone', '01223694848') }}
                            </a>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-phone text-primary-yellow"></i>
                            @php
                                $footerAltPhone = $siteAltPhone ?? \App\Models\Setting::getValue('site_phone_alt', '01022221892');
                                $footerAltPhone2 = \App\Models\Setting::getValue('site_phone_alt_2', '01001646056');
                            @endphp
                            @if($footerAltPhone)
                                <a href="tel:{{ $footerAltPhone }}" class="hover:text-primary-yellow transition-smooth">
                                    {{ $footerAltPhone }}
                                </a>
                            @endif
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-phone text-primary-yellow"></i>
                            @if($footerAltPhone2)
                                <a href="tel:{{ $footerAltPhone2 }}" class="hover:text-primary-yellow transition-smooth">
                                    {{ $footerAltPhone2 }}
                                </a>
                            @endif
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fab fa-whatsapp text-primary-yellow"></i>
                            <a href="{{ $whatsappUrl ?? ('https://wa.me/'.\App\Models\Setting::getValue('whatsapp_number', '201223694848')) }}" class="hover:text-primary-yellow transition-smooth">واتساب</a>
                        </li>
                    </ul>
                </div>

                <!-- Column 4: Newsletter -->
                <div>
                    <h3 class="text-xl font-bold mb-4 text-primary-yellow">
                        <i class="fas fa-envelope ml-1"></i>
                        النشرة البريدية
                    </h3>
                    <p class="text-gray-300 text-sm mb-4">
                        اشترك للحصول على آخر العروض والمنتجات الجديدة
                    </p>
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <input type="email" 
                                   name="email" 
                                   placeholder="بريدك الإلكتروني"
                                   required
                                   class="w-full px-4 py-2.5 rounded-lg bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-yellow focus:border-transparent transition">
                        </div>
                        <button type="submit" 
                                class="w-full px-4 py-2.5 bg-primary-yellow hover:bg-yellow-400 text-primary-blue font-bold rounded-lg transition-all duration-200 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-paper-plane ml-1"></i>
                            اشترك الآن
                        </button>
                    </form>
                    
                    <!-- Social Media Links -->
                    @php
                        $footerFacebookUrl = \App\Models\Setting::getValue('facebook_url');
                        $footerInstagramUrl = \App\Models\Setting::getValue('instagram_url');
                        $footerTiktokUrl = \App\Models\Setting::getValue('tiktok_url');
                        $footerWhatsappNumber = \App\Models\Setting::getValue('whatsapp_number');
                        $footerWhatsappUrl = $footerWhatsappNumber ? ('https://wa.me/'.$footerWhatsappNumber) : null;
                    @endphp
                    @if($footerFacebookUrl || $footerInstagramUrl || $footerTiktokUrl || $footerWhatsappUrl)
                        <div class="mt-6 pt-6 border-t border-white/10">
                            <p class="text-gray-400 text-xs mb-3">تابعنا على</p>
                            <div class="flex gap-3">
                                @if($footerFacebookUrl)
                                    <a href="{{ $footerFacebookUrl }}" target="_blank" 
                                       class="w-9 h-9 rounded-lg bg-white/10 hover:bg-primary-yellow hover:text-primary-blue flex items-center justify-center transition-all duration-200">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                @endif
                                @if($footerInstagramUrl)
                                    <a href="{{ $footerInstagramUrl }}" target="_blank" 
                                       class="w-9 h-9 rounded-lg bg-white/10 hover:bg-primary-yellow hover:text-primary-blue flex items-center justify-center transition-all duration-200">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                @endif
                                @if($footerTiktokUrl)
                                    <a href="{{ $footerTiktokUrl }}" target="_blank" 
                                       class="w-9 h-9 rounded-lg bg-white/10 hover:bg-primary-yellow hover:text-primary-blue flex items-center justify-center transition-all duration-200">
                                        <i class="fab fa-tiktok"></i>
                                    </a>
                                @endif
                                @if($footerWhatsappUrl)
                                    <a href="{{ $footerWhatsappUrl }}" target="_blank" 
                                       class="w-9 h-9 rounded-lg bg-white/10 hover:bg-primary-yellow hover:text-primary-blue flex items-center justify-center transition-all duration-200">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="border-t border-gray-700 mt-8 pt-6 flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-gray-400">
                <div>
                    <span>© {{ date('Y') }} {{ \App\Models\Setting::getValue('site_name', 'مكتبة الصديق') }}. جميع الحقوق محفوظة.</span>
                </div>
                <div class="flex gap-4">
                    <a href="/privacy" class="hover:text-primary-yellow transition-smooth">الخصوصية</a>
                    <a href="/terms" class="hover:text-primary-yellow transition-smooth">الشروط والأحكام</a>
                    <a href="/shipping" class="hover:text-primary-yellow transition-smooth">الشحن</a>
                    <a href="/returns" class="hover:text-primary-yellow transition-smooth">الإرجاع</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')

    <!-- Mobile Tab Bar (Bottom) -->
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-30 pb-[env(safe-area-inset-bottom)]" x-data="{ mobileSearchOpen: false }">
        <div class="mx-3 mb-3 rounded-2xl bg-white/95 backdrop-blur border border-gray-200 shadow-[0_-6px_20px_rgba(0,0,0,0.08)]">
            <div class="grid grid-cols-5 items-end h-[76px] px-2 gap-1">
                <a href="{{ route('products.index') }}" class="flex flex-col items-center justify-center rounded-xl py-2 transition {{ request()->routeIs('products.*') ? 'text-primary-blue bg-primary-blue/10' : 'text-gray-600 hover:text-primary-blue' }}">
                    <i class="fas fa-th-large text-xl mb-1"></i>
                    <span class="text-[10px] font-medium">الأقسام</span>
                </a>

                <button type="button" @click="mobileSearchOpen = !mobileSearchOpen" class="flex flex-col items-center justify-center rounded-xl py-2 transition {{ request()->routeIs('products.search') ? 'text-primary-blue bg-primary-blue/10' : 'text-gray-600 hover:text-primary-blue' }}">
                    <i class="fas fa-search text-xl mb-1"></i>
                    <span class="text-[10px] font-medium">بحث</span>
                </button>

                <a href="{{ route('home') }}" class="flex flex-col items-center justify-center -mt-6 relative z-10">
                    <div class="w-14 h-14 rounded-2xl bg-primary-blue text-white shadow-lg flex items-center justify-center border-4 border-white">
                        <i class="fas fa-book text-2xl"></i>
                    </div>
                    <span class="text-[10px] font-semibold mt-1 text-gray-900">الرئيسية</span>
                </a>

                <a href="{{ route('cart.index') }}" class="relative flex flex-col items-center justify-center rounded-xl py-2 transition {{ request()->routeIs('cart.*') ? 'text-primary-blue bg-primary-blue/10' : 'text-gray-600 hover:text-primary-blue' }}">
                    <i class="fas fa-shopping-cart text-xl mb-1"></i>
                    <span class="text-[10px] font-medium">السلة</span>
                    <span class="tab-cart-count absolute -top-1 right-3 w-4 h-4 bg-red-500 text-white text-[10px] flex items-center justify-center rounded-full {{ (session('cart') && count(session('cart')) > 0) ? '' : 'hidden' }}">
                        {{ session('cart') ? count(session('cart')) : 0 }}
                    </span>
                </a>

                <a href="{{ Auth::check() ? route('profile.edit') : route('login') }}" class="flex flex-col items-center justify-center rounded-xl py-2 transition {{ request()->routeIs('profile.*') || request()->routeIs('login') ? 'text-primary-blue bg-primary-blue/10' : 'text-gray-600 hover:text-primary-blue' }}">
                    <i class="fas fa-user text-xl mb-1"></i>
                    <span class="text-[10px] font-medium">{{ Auth::check() ? 'حسابي' : 'دخول' }}</span>
                </a>
            </div>
        </div>
        
        <!-- Mobile Search Bar (Hidden by default) -->
        <div x-show="mobileSearchOpen" x-cloak x-transition class="absolute bottom-[92px] left-0 right-0 mx-3 bg-white rounded-2xl p-4 border border-gray-200 shadow-lg">
            <form action="{{ route('products.search') }}" method="GET" class="flex gap-2">
                <input type="text" name="q" placeholder="ابحث عن كتاب، لعبة..." class="w-full rounded-lg border-gray-300 focus:border-primary-blue focus:ring focus:ring-primary-blue/20">
                <button type="submit" class="bg-primary-blue text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </nav>
    
    <!-- Add padding to bottom of body to prevent content from being hidden by tab bar on mobile -->
    <style>
        @media (max-width: 1024px) {
            body {
                padding-bottom: 6rem;
            }
        }
    </style>

    <!-- Visit Tracking Script -->
    <script src="{{ asset('js/visit-tracker.js') }}"></script>

    <!-- Cookie Consent Script -->
    <script src="{{ asset('js/cookie-consent.js') }}"></script>
</body>
</html>
