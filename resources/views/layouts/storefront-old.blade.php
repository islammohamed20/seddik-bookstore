<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Al-Seddik Library')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen flex flex-col">
    <header class="border-b bg-white/95 backdrop-blur sticky top-0 z-30">
        <div class="max-w-6xl mx-auto px-4 py-3 flex flex-col gap-3">
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('home') }}" class="flex flex-col leading-tight">
                    <span class="text-lg font-bold tracking-tight">مكتبة الصديق</span>
                    <span class="text-[11px] text-slate-500">كتب • قرطاسية • ألعاب تعليمية</span>
                </a>
                <div class="flex items-center gap-3">
                    <a href="{{ route('cart.index') }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full bg-amber-500 text-white text-xs font-medium">
                        <span>السلة</span>
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-white text-amber-600 text-[11px]">
                            {{ session('cart') ? count(session('cart')) : 0 }}
                        </span>
                    </a>
                    @auth
                        <a href="{{ route('orders.index') }}" class="hidden sm:inline-flex text-xs text-slate-600 hover:text-amber-600 transition-colors">
                            طلباتي
                        </a>
                        <a href="{{ route('profile.edit') }}" class="hidden sm:inline-flex text-xs text-slate-600 hover:text-amber-600 transition-colors">
                            حسابي
                        </a>
                    @endauth
                    @guest
                        <a href="{{ route('login') }}" class="hidden sm:inline-flex text-xs text-slate-600 hover:text-amber-600 transition-colors">
                            تسجيل الدخول
                        </a>
                    @endguest
                </div>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                <form action="{{ route('products.search') }}" method="get" class="flex-1">
                    <label class="block relative">
                        <span class="sr-only">ابحث عن منتج</span>
                        <input
                            type="search"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="ابحث عن كتاب، لعبة، أو منتج قرطاسي"
                            class="w-full rounded-full border border-slate-200 bg-slate-50/60 px-4 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-amber-500 focus:border-amber-500"
                        >
                    </label>
                </form>
                <nav class="flex flex-wrap items-center gap-2 text-[11px] sm:text-xs">
                    <a href="{{ route('home') }}" class="px-3 py-1 rounded-full border border-transparent hover:border-amber-500 hover:text-amber-600 transition-colors">
                        الرئيسية
                    </a>
                    <a href="{{ route('products.index') }}" class="px-3 py-1 rounded-full border border-transparent hover:border-amber-500 hover:text-amber-600 transition-colors">
                        كل المنتجات
                    </a>
                    <a href="{{ route('products.index', ['category' => request('category')]) }}#categories" class="px-3 py-1 rounded-full border border-transparent hover:border-amber-500 hover:text-amber-600 transition-colors">
                        تسوق حسب القسم
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="border-t bg-white mt-10">
        <div class="max-w-6xl mx-auto px-4 py-8 text-xs text-slate-500">
            <div class="grid gap-6 sm:grid-cols-3 mb-6">
                <div class="space-y-1">
                    <div class="text-sm font-semibold text-slate-800">مكتبة الصديق</div>
                    <p class="leading-relaxed">
                        متجر متخصص في الكتب، القرطاسية، والألعاب التعليمية للأطفال بمختلف الأعمار.
                    </p>
                </div>
                <div>
                    <div class="text-xs font-semibold text-slate-700 mb-2">خدمة العملاء</div>
                    <ul class="space-y-1">
                        <li>سياسة الاستبدال والاسترجاع</li>
                        <li>مواعيد التوصيل داخل المدينة</li>
                        <li>الدفع عند الاستلام متاح</li>
                    </ul>
                </div>
                <div>
                    <div class="text-xs font-semibold text-slate-700 mb-2">تواصل معنا</div>
                    <ul class="space-y-1">
                        <li>البريد الإلكتروني: info@example.com</li>
                        <li>الهاتف: 0500000000</li>
                    </ul>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-t border-slate-100 pt-4">
                <div>
                    <span>© {{ date('Y') }} مكتبة الصديق</span>
                    <span class="mx-1">•</span>
                    <span>Al-Seddik Library</span>
                </div>
                <div class="flex gap-3">
                    <a href="#" class="hover:text-amber-600 transition-colors">{{ __('Privacy') }}</a>
                    <a href="#" class="hover:text-amber-600 transition-colors">{{ __('Terms') }}</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
