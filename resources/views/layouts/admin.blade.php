<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'لوحة التحكم') - {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endif

    <style>
        [x-cloak] { display: none !important; }
    </style>

    @stack('styles')
</head>
<body class="h-full bg-gray-100 text-gray-900 dark:bg-slate-950 dark:text-slate-100" x-data="{ sidebarOpen: false, darkMode: false }" x-init="
    darkMode = localStorage.getItem('admin_dark') === '1';
    document.documentElement.classList.toggle('dark', darkMode);
    $watch('darkMode', value => {
        localStorage.setItem('admin_dark', value ? '1' : '0');
        document.documentElement.classList.toggle('dark', value);
    });
">
    <div class="min-h-screen flex flex-col lg:flex-row">
        <div class="lg:hidden flex items-center justify-between px-4 h-14 bg-white dark:bg-slate-900 border-b border-gray-200 dark:border-slate-800">
            <a href="{{ route('admin.dashboard') }}" class="font-bold flex items-center gap-2">
                <i class="fas fa-book-open text-indigo-600"></i>
                <span>{{ config('app.name') }}</span>
            </a>
            <div class="flex items-center gap-2">
                <button type="button" @click="darkMode = !darkMode" class="w-9 h-9 inline-flex items-center justify-center rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800">
                    <i class="fas" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                </button>
                <button type="button" @click="sidebarOpen = true" class="w-9 h-9 inline-flex items-center justify-center rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>

        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 bg-black/40 z-40 lg:hidden"></div>

        <aside class="fixed lg:static inset-y-0 z-50 w-72 bg-slate-900 text-slate-100 transform transition-transform duration-200 ease-out flex flex-col"
               :class="sidebarOpen ? 'translate-x-0' : (document.dir === 'rtl' ? 'translate-x-full lg:translate-x-0' : '-translate-x-full lg:translate-x-0')">
            <div class="h-16 px-4 flex items-center justify-between border-b border-slate-800">
                <a href="{{ route('admin.dashboard') }}" class="font-bold flex items-center gap-2">
                    <i class="fas fa-book-open text-indigo-400"></i>
                    <span>{{ config('app.name') }}</span>
                </a>
                <button type="button" class="lg:hidden w-9 h-9 inline-flex items-center justify-center rounded-lg bg-slate-800" @click="sidebarOpen = false">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="px-4 py-4 border-b border-slate-800 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center font-bold">
                    {{ mb_substr(auth()->user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="min-w-0">
                    <div class="text-sm font-semibold truncate">{{ auth()->user()->name ?? 'Admin' }}</div>
                    <div class="text-xs text-slate-400 truncate">{{ auth()->user()->email ?? '' }}</div>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-200' }}">
                    <i class="fas fa-chart-line w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-600 text-white' : 'text-slate-200' }}">
                    <i class="fas fa-layer-group w-5 text-center"></i>
                    <span>Categories</span>
                </a>

                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 {{ request()->routeIs('admin.products.*') ? 'bg-indigo-600 text-white' : 'text-slate-200' }}">
                    <i class="fas fa-box w-5 text-center"></i>
                    <span>Products</span>
                </a>

                <a href="{{ route('admin.offers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 {{ request()->routeIs('admin.offers.*') ? 'bg-indigo-600 text-white' : 'text-slate-200' }}">
                    <i class="fas fa-percent w-5 text-center"></i>
                    <span>Offers</span>
                </a>

                <a href="{{ route('admin.sliders.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 {{ request()->routeIs('admin.sliders.*') ? 'bg-indigo-600 text-white' : 'text-slate-200' }}">
                    <i class="fas fa-images w-5 text-center"></i>
                    <span>Sliders</span>
                </a>

                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-600 text-white' : 'text-slate-200' }}">
                    <i class="fas fa-cog w-5 text-center"></i>
                    <span>Settings</span>
                </a>

                <a href="{{ route('admin.contact-messages.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 {{ request()->routeIs('admin.contact-messages.*') ? 'bg-indigo-600 text-white' : 'text-slate-200' }}">
                    <i class="fas fa-envelope w-5 text-center"></i>
                    <span>Messages</span>
                </a>

                <a href="{{ route('admin.email-management.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 {{ request()->routeIs('admin.email-management.*') ? 'bg-indigo-600 text-white' : 'text-slate-200' }}">
                    <i class="fas fa-mail-bulk w-5 text-center"></i>
                    <span>Email Management</span>
                </a>
            </nav>

            <div class="px-3 py-4 border-t border-slate-800 space-y-2">
                <button type="button" @click="darkMode = !darkMode" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg bg-slate-800 hover:bg-slate-700">
                    <i class="fas w-5 text-center" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                    <span x-text="darkMode ? 'Light Mode' : 'Dark Mode'"></span>
                </button>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-red-600/20 text-red-200 hover:text-red-100">
                        <i class="fas fa-sign-out-alt w-5 text-center"></i>
                        <span>تسجيل الخروج</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 min-w-0 lg:ms-72">
            <header class="hidden lg:flex items-center justify-between h-16 px-6 bg-white dark:bg-slate-900 border-b border-gray-200 dark:border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="font-semibold text-gray-900 dark:text-slate-100">{{ config('app.name') }}</div>
                    <div class="text-gray-400">/</div>
                    <div class="text-gray-700 dark:text-slate-200">@yield('page-title', 'لوحة التحكم')</div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="button" @click="darkMode = !darkMode" class="w-9 h-9 inline-flex items-center justify-center rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800">
                        <i class="fas" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                    </button>
                    <div class="text-sm text-gray-700 dark:text-slate-200">{{ auth()->user()->name ?? '' }}</div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>خروج</span>
                        </button>
                    </form>
                </div>
            </header>

            <main class="p-6">
                @if(session('success'))
                    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 text-green-800 px-4 py-3 dark:border-green-900/40 dark:bg-green-950/40 dark:text-green-200">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 text-red-800 px-4 py-3 dark:border-red-900/40 dark:bg-red-950/40 dark:text-red-200">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 text-red-800 px-4 py-3 dark:border-red-900/40 dark:bg-red-950/40 dark:text-red-200">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
