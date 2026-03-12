@extends('layouts.storefront')

@section('title', __('الملف الشخصي') . ' - ' . __('مكتبة الصديق'))

@section('content')
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="mb-8 text-center">
            <span class="inline-block bg-primary-blue/10 text-primary-blue px-4 py-2 rounded-full text-sm font-semibold mb-3">
                {{ __('حسابي') }}
            </span>
            <h1 class="text-3xl md:text-4xl font-bold text-primary-blue mb-2">
                {{ __('الملف الشخصي') }}
            </h1>
            <p class="text-gray-600">
                {{ __('قم بتحديث بيانات حسابك وكلمة المرور وإدارة حسابك بسهولة.') }}
            </p>
        </div>

        <div class="max-w-5xl mx-auto grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white shadow-sm rounded-2xl p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        {{ __('معلومات الحساب') }}
                    </h2>
                    <div class="border-t border-gray-100 pt-4">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-2xl p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        {{ __('تغيير كلمة المرور') }}
                    </h2>
                    <div class="border-t border-gray-100 pt-4">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white shadow-sm rounded-2xl p-6">
                    <h2 class="text-lg font-semibold text-red-600 mb-4">
                        {{ __('حذف الحساب') }}
                    </h2>
                    <p class="text-sm text-gray-600 mb-4">
                        {{ __('احرص على أخذ نسخة احتياطية من بياناتك قبل حذف الحساب نهائياً.') }}
                    </p>
                    <div class="border-t border-gray-100 pt-4">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
