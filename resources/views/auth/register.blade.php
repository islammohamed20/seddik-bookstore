@extends('layouts.storefront')

@section('title', __('إنشاء حساب') . ' - ' . __('متجر الصديق'))

@section('content')
<div class="min-h-screen bg-gray-100 py-12 flex items-center justify-center">
    <div class="w-full max-w-md px-4">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-block">
                <span class="text-3xl font-bold">
                    <span class="text-primary-yellow">متجر</span>
                    <span class="text-primary-blue">الصديق</span>
                </span>
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h2 class="text-2xl font-bold text-primary-blue text-center mb-6">إنشاء حساب جديد</h2>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-gray-700 font-semibold mb-2">الاسم الكامل</label>
                    <input id="name" 
                           type="text" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required 
                           autofocus
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-yellow focus:border-transparent transition @error('name') border-red-500 @enderror"
                           placeholder="أدخل اسمك الكامل">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-gray-700 font-semibold mb-2">البريد الإلكتروني</label>
                    <input id="email" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-yellow focus:border-transparent transition @error('email') border-red-500 @enderror"
                           placeholder="example@email.com">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-gray-700 font-semibold mb-2">رقم الهاتف</label>
                    <input id="phone"
                           type="tel"
                           name="phone"
                           value="{{ old('phone') }}"
                           required
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-yellow focus:border-transparent transition @error('phone') border-red-500 @enderror"
                           placeholder="01xxxxxxxxx">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-gray-700 font-semibold mb-2">كلمة المرور</label>
                    <input id="password" 
                           type="password" 
                           name="password" 
                           required
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-yellow focus:border-transparent transition @error('password') border-red-500 @enderror"
                           placeholder="••••••••">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-gray-700 font-semibold mb-2">تأكيد كلمة المرور</label>
                    <input id="password_confirmation" 
                           type="password" 
                           name="password_confirmation" 
                           required
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-primary-yellow focus:border-transparent transition"
                           placeholder="••••••••">
                </div>

                <button type="submit"
                        class="w-full bg-primary-blue hover:bg-primary-blue/90 text-white font-bold py-4 rounded-lg transition transform hover:scale-[1.02]">
                    إنشاء الحساب
                </button>
            </form>

            <!-- Login Link -->
            <p class="mt-6 text-center text-gray-600">
                لديك حساب بالفعل؟
                <a href="{{ route('login') }}" class="text-primary-blue font-semibold hover:underline">تسجيل الدخول</a>
            </p>
        </div>

        <!-- Back to home -->
        <p class="mt-6 text-center">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-primary-blue transition">
                <i class="fas fa-arrow-right ml-2"></i>
                العودة للرئيسية
            </a>
        </p>
    </div>
</div>
@endsection
