@extends('layouts.storefront')

@section('title', __('تسجيل الدخول') . ' - ' . __('متجر الصديق'))

@section('content')
<div class="min-h-screen bg-gray-100 py-12 flex items-center justify-center">
    <div class="w-full max-w-md px-4">
        @php
            $siteLogoPath = \App\Models\Setting::getValue('site_logo');
            $siteLogoUrl = $siteLogoPath ? asset('storage/'.$siteLogoPath) : null;
            $siteName = \App\Models\Setting::getValue('site_name', 'مكتبة الصديق');
        @endphp
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-3">
                @if($siteLogoUrl)
                    <div class="w-16 h-16 rounded-2xl overflow-hidden bg-white flex items-center justify-center shadow">
                        <img src="{{ $siteLogoUrl }}" alt="{{ $siteName }}" class="w-full h-full object-contain">
                    </div>
                @else
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-primary-yellow to-primary-blue flex items-center justify-center text-white text-2xl font-black">
                        <i class="fas fa-book"></i>
                    </div>
                @endif
                <div class="text-right">
                    <div class="text-2xl font-extrabold text-primary-blue">{{ $siteName }}</div>
                </div>
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="mb-6 text-center">
                <h2 class="text-2xl font-bold text-primary-blue mb-1">تسجيل الدخول</h2>
                <p class="text-sm text-gray-500">
                    سجّل دخولك لمتابعة الطلبات، تتبع الشحنات وحفظ المفضلة.
                </p>
            </div>
            
            <!-- Admin Login Link -->
            <div class="mb-6 text-center">
                <a href="{{ route('admin.login') }}" class="text-sm text-primary-blue hover:text-primary-blue/80 transition-colors">
                    <i class="fas fa-shield-alt ml-1"></i>
                    تسجيل دخول الأدمن
                </a>
            </div>

            @if (session('status'))
                <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-sm text-red-700">
                    <ul class="list-disc list-inside text-right">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-gray-700 font-semibold mb-2">البريد الإلكتروني</label>
                    <input id="email"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autofocus
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-yellow focus:border-transparent transition @error('email') border-red-500 @enderror"
                           placeholder="example@email.com">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div x-data="{ show: false }">
                    <label for="password" class="block text-gray-700 font-semibold mb-2">كلمة المرور</label>
                    <div class="relative">
                        <input id="password"
                               :type="show ? 'text' : 'password'"
                               name="password"
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 pr-11 focus:ring-2 focus:ring-primary-yellow focus:border-transparent transition @error('password') border-red-500 @enderror"
                               placeholder="••••••••">
                        <button type="button"
                                x-on:click="show = !show"
                                class="absolute inset-y-0 left-0 px-3 flex items-center text-gray-400 hover:text-gray-600 text-xs">
                            <span x-show="!show">إظهار</span>
                            <span x-show="show">إخفاء</span>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-sm pt-1">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox"
                               name="remember"
                               class="w-4 h-4 text-primary-yellow border-gray-300 rounded focus:ring-primary-yellow">
                        <span class="mr-2 text-gray-700">تذكرني</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-primary-blue hover:underline">
                            نسيت كلمة المرور؟
                        </a>
                    @endif
                </div>

                <div class="space-y-3">
                    <button type="submit"
                            class="w-full bg-primary-blue hover:bg-primary-blue/90 text-white font-bold py-3 rounded-lg transition transform hover:scale-[1.02]">
                        تسجيل الدخول
                    </button>
                    <p class="text-xs text-gray-500 text-center">
                        بتسجيل الدخول، فأنت توافق على
                        <a href="#" class="text-primary-blue hover:underline">الشروط والأحكام</a>
                        و
                        <a href="#" class="text-primary-blue hover:underline">سياسة الخصوصية</a>.
                    </p>
                </div>
            </form>

            @if (Route::has('register'))
                <div class="mt-6 pt-5 border-t border-dashed border-gray-200">
                    <p class="text-center text-gray-600 text-sm">
                        جديد في المتجر؟
                        <a href="{{ route('register') }}" class="text-primary-blue font-semibold hover:underline">
                            أنشئ حساباً خلال دقيقة واحدة
                        </a>
                    </p>
                </div>
            @endif
        </div>
        <div class="mt-6 text-center text-xs text-gray-500">
            <span class="inline-flex items-center justify-center gap-2">
                <i class="fas fa-lock text-[10px]"></i>
                معلوماتك مشفّرة ومحميّة بتشفير آمن.
            </span>
        </div>
    </div>
</div>
@endsection
