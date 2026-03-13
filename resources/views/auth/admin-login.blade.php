<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تسجيل دخول الأدمن</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: '#3b82f6',
                            secondary: '#64748b',
                        },
                        fontFamily: {
                            sans: ['Cairo', 'ui-sans-serif', 'system-ui'],
                        }
                    }
                }
            }
        </script>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-blue-50 flex items-center justify-center p-4">
    
    {{-- Background Effects --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-[20%] -right-[10%] w-[55%] h-[55%] rounded-full bg-amber-300/20 blur-3xl animate-pulse"></div>
        <div class="absolute top-[35%] -left-[12%] w-[45%] h-[45%] rounded-full bg-blue-300/15 blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute -bottom-[12%] right-[18%] w-[35%] h-[35%] rounded-full bg-yellow-300/15 blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="w-full max-w-md relative z-10">
        {{-- Logo Section --}}
        @php
            $adminLogoPath = \App\Models\Setting::getValue('site_logo');
            $adminLogoUrl = $adminLogoPath ? asset('storage/'.$adminLogoPath) : null;
        @endphp
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-br from-amber-100 to-yellow-50 backdrop-blur-md border border-amber-200 shadow-2xl mb-4 hover:shadow-3xl transition-all duration-500 hover:scale-105">
                @if($adminLogoUrl)
                    <img src="{{ $adminLogoUrl }}" alt="{{ \App\Models\Setting::getValue('site_name', config('app.name')) }}" class="h-12 w-auto rounded">
                @else
                    <i class="fas fa-book-open text-4xl text-amber-600"></i>
                @endif
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">مكتبة الصديق</h1>
            <p class="text-gray-600 text-lg">Control Panel</p>
        </div>

        {{-- Login Card --}}
        <div class="bg-white/95 backdrop-blur rounded-2xl shadow-2xl overflow-hidden border border-amber-100 hover:shadow-3xl transition-all duration-500">
            <div class="p-8">
                @if (session('status'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 flex items-center gap-3">
                        <i class="fas fa-check-circle text-xl"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif
                
                @if (session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 text-red-700 flex items-center gap-3">
                        <i class="fas fa-exclamation-circle text-xl"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.store') }}" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">البريد الإلكتروني</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="block w-full pr-11 pl-4 py-3 bg-amber-50 border border-amber-200 text-gray-800 rounded-xl focus:ring-2 focus:ring-amber-500/60 focus:border-amber-300 transition-all outline-none hover:bg-amber-100 hover:border-amber-300"
                                placeholder="name@example.com">
                        </div>
                    </div>

                    <div x-data="{ show: false }">
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">كلمة المرور</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input id="password" :type="show ? 'text' : 'password'" name="password" required
                                class="block w-full pr-11 pl-12 py-3 bg-amber-50 border border-amber-200 text-gray-800 rounded-xl focus:ring-2 focus:ring-amber-500/60 focus:border-amber-300 transition-all outline-none hover:bg-amber-100 hover:border-amber-300"
                                placeholder="••••••••">
                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 hover:text-amber-600 transition-colors">
                                <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center cursor-pointer group">
                            <input type="checkbox" name="remember" 
                                class="w-4 h-4 rounded text-amber-600 border-gray-300 focus:ring-amber-500 cursor-pointer">
                            <span class="mr-2 text-sm text-gray-600 group-hover:text-gray-900 transition-colors">تذكرني</span>
                        </label>
                        
                        <a href="{{ route('password.request') }}" class="text-sm font-medium text-amber-600 hover:text-amber-700 transition-colors">
                            نسيت كلمة المرور؟
                        </a>
                    </div>

                    <button type="submit" 
                        class="w-full bg-gradient-to-l from-amber-500 to-yellow-400 hover:from-amber-600 hover:to-yellow-500 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-amber-500/25 hover:shadow-amber-600/35 transform hover:-translate-y-0.5 transition-all duration-200 hover:shadow-2xl hover:shadow-amber-600/40">
                        تسجيل الدخول
                    </button>
                </form>
            </div>
            
            <div class="bg-amber-50 px-8 py-4 border-t border-amber-100 flex items-center justify-between">
                <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-amber-600 transition-colors flex items-center gap-2 hover:translate-x-1 transform duration-200">
                    <i class="fas fa-arrow-right"></i>
                    <span>دخول العملاء</span>
                </a>
                <a href="{{ url('/') }}" class="text-sm text-gray-500 hover:text-amber-600 transition-colors flex items-center gap-2 hover:translate-x-1 transform duration-200">
                    <i class="fas fa-home"></i>
                    <span>المتجر</span>
                </a>
            </div>
        </div>
        
        <div class="mt-8 text-center">
            <p class="text-gray-500 text-sm">
                &copy; {{ date('Y') }} {{ config('app.name') }}. جميع الحقوق محفوظة
            </p>
        </div>
    </div>

</body>
</html>
