<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم') - {{ config('app.name') }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts - Cairo -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * { font-family: 'Cairo', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-100">
    <div x-data="{ sidebarOpen: true, mobileMenuOpen: false }" class="min-h-screen">
        
        <!-- Mobile Menu Button -->
        <div class="lg:hidden fixed top-4 right-4 z-50">
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-lg bg-indigo-600 text-white">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        
        <!-- Sidebar -->
        <aside :class="{ 'translate-x-0': mobileMenuOpen, 'translate-x-full lg:translate-x-0': !mobileMenuOpen }"
               class="fixed inset-y-0 right-0 z-40 w-64 bg-indigo-800 text-white transform transition-transform duration-300 lg:translate-x-0">
            
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 bg-indigo-900 flex-shrink-0">
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold">
                    <i class="fas fa-book-open ml-2"></i>
                    {{ config('app.name', 'صديق بوك') }}
                </a>
            </div>
            
            <!-- Navigation -->
            <nav class="mt-6 px-3 pb-6 overflow-y-auto" style="max-height: calc(100vh - 4rem);">
                <x-admin.sidebar-link 
                    route="admin.dashboard" 
                    icon="fas fa-home" 
                    label="لوحة التحكم" />
                
                <x-admin.sidebar-link 
                    route="admin.products.*" 
                    icon="fas fa-box" 
                    label="المنتجات" />
                
                <x-admin.sidebar-link 
                    route="admin.categories.*" 
                    icon="fas fa-folder" 
                    label="التصنيفات" />
                
                <x-admin.sidebar-link 
                    route="admin.brands.*" 
                    icon="fas fa-tags" 
                    label="العلامات التجارية" />
                
                <x-admin.sidebar-link 
                    route="admin.orders.*" 
                    icon="fas fa-shopping-cart" 
                    label="الطلبات" />
                
                <x-admin.sidebar-link 
                    route="admin.users.*" 
                    icon="fas fa-users" 
                    label="المستخدمين" />
                
                <x-admin.sidebar-link 
                    route="admin.coupons.*" 
                    icon="fas fa-ticket-alt" 
                    label="الكوبونات" />
                
                <x-admin.sidebar-link 
                    route="admin.offers.*" 
                    icon="fas fa-percentage" 
                    label="العروض" />
                
                <x-admin.sidebar-link 
                    route="admin.sliders.*" 
                    icon="fas fa-images" 
                    label="السلايدر" />
                
                <x-admin.sidebar-link 
                    route="admin.pages.*" 
                    icon="fas fa-file-alt" 
                    label="الصفحات" />
                
                <x-admin.sidebar-link 
                    route="admin.contact-messages.*" 
                    icon="fas fa-envelope" 
                    label="الرسائل" />
                
                <x-admin.sidebar-link 
                    route="admin.email-management.*" 
                    icon="fas fa-mail-bulk" 
                    label="إدارة البريد" />
                
                <x-admin.sidebar-link 
                    route="admin.shipping-zones.*" 
                    icon="fas fa-shipping-fast" 
                    label="إعدادات الشحن" />

                <x-admin.sidebar-link 
                    route="admin.visit-reports.*" 
                    icon="fas fa-chart-line" 
                    label="تقارير الزيارات" />
                
                <x-admin.sidebar-link 
                    route="admin.notifications.*" 
                    icon="fas fa-bell" 
                    label="الإشعارات" />
                
                <x-admin.sidebar-link 
                    route="admin.settings.*" 
                    icon="fas fa-cog" 
                    label="الإعدادات" />
                
                <hr class="my-4 border-indigo-700">
                
                <a href="{{ route('home') }}" target="_blank"
                   class="flex items-center px-4 py-3 rounded-lg mb-1 text-indigo-200 hover:bg-indigo-700">
                    <i class="fas fa-external-link-alt w-5"></i>
                    <span class="mr-3">عرض الموقع</span>
                </a>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-3 rounded-lg text-indigo-200 hover:bg-indigo-700">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span class="mr-3">تسجيل الخروج</span>
                    </button>
                </form>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <div class="lg:mr-64 min-h-screen">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-800">@yield('page-title', 'لوحة التحكم')</h1>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-gray-600">مرحباً، {{ auth()->user()->name }}</span>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                    <x-admin.alert type="success" class="mb-4">
                        {{ session('success') }}
                    </x-admin.alert>
                @endif
                
                @if(session('error'))
                    <x-admin.alert type="error" class="mb-4">
                        {{ session('error') }}
                    </x-admin.alert>
                @endif
                
                @if(session('warning'))
                    <x-admin.alert type="warning" class="mb-4">
                        {{ session('warning') }}
                    </x-admin.alert>
                @endif
                
                @if(session('info'))
                    <x-admin.alert type="info" class="mb-4">
                        {{ session('info') }}
                    </x-admin.alert>
                @endif
                
                @if($errors->any())
                    <x-admin.alert type="error" class="mb-4">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-admin.alert>
                @endif
                
                @yield('content')
            </main>
        </div>
        
        <!-- Mobile Overlay -->
        <div x-show="mobileMenuOpen" 
             @click="mobileMenuOpen = false"
             class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden"
             x-cloak></div>
             
        <!-- شريط الاختصارات السريعة -->
        <x-admin.shortcuts-bar x-ref="shortcutsBar" />
    </div>
    
    <!-- Admin Keyboard Shortcuts -->
    <script src="{{ asset('js/admin-shortcuts.js') }}"></script>
    
    <!-- Admin Notifications -->
    <script src="{{ asset('js/admin-notifications.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
