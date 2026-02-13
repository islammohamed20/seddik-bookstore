@extends('layouts.storefront')

@section('title', __('Checkout') . ' | مكتبة الصديق')

@section('content')
    <section class="max-w-5xl mx-auto px-4 py-8 grid gap-8 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">
        <div>
            <h1 class="text-xl font-semibold mb-2">{{ __('Checkout') }}</h1>
            <p class="text-xs text-slate-500 mb-4">
                أكمل بياناتك لإتمام طلبك، الدفع عند الاستلام متاح داخل المدينة.
            </p>

            <form method="post" action="{{ route('checkout.store') }}" class="space-y-6">
                @csrf

                <div class="space-y-3">
                    <h2 class="text-sm font-semibold">{{ __('Contact') }}</h2>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div class="space-y-1">
                            <label class="block text-xs text-slate-600">{{ __('Full name') }}</label>
                            <input type="text" name="customer_name" value="{{ old('customer_name') }}" class="w-full px-3 py-2 text-sm rounded border border-slate-200">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs text-slate-600">{{ __('Phone') }}</label>
                            <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" class="w-full px-3 py-2 text-sm rounded border border-slate-200">
                        </div>
                        <div class="space-y-1 sm:col-span-2">
                            <label class="block text-xs text-slate-600">{{ __('Email (optional)') }}</label>
                            <input type="email" name="customer_email" value="{{ old('customer_email') }}" class="w-full px-3 py-2 text-sm rounded border border-slate-200">
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-semibold">{{ __('Billing address') }}</h2>
                        <button type="button" 
                                id="detect-location-btn"
                                class="inline-flex items-center gap-2 px-3 py-1.5 text-xs bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition">
                            <i class="fas fa-map-marker-alt"></i>
                            <span class="btn-text">تحديد موقعي الحالي</span>
                        </button>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div class="space-y-1">
                            <label class="block text-xs text-slate-600">{{ __('First name') }}</label>
                            <input type="text" name="billing_first_name" value="{{ old('billing_first_name') }}" class="w-full px-3 py-2 text-sm rounded border border-slate-200">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs text-slate-600">{{ __('Last name') }}</label>
                            <input type="text" name="billing_last_name" value="{{ old('billing_last_name') }}" class="w-full px-3 py-2 text-sm rounded border border-slate-200">
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs text-slate-600">{{ __('Street address') }}</label>
                        <input type="text" 
                               name="billing_address_line1" 
                               id="address"
                               value="{{ old('billing_address_line1') }}" 
                               class="w-full px-3 py-2 text-sm rounded border border-slate-200">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs text-slate-600">{{ __('Apartment, floor, etc. (optional)') }}</label>
                        <input type="text" 
                               name="billing_address_line2" 
                               id="area"
                               value="{{ old('billing_address_line2') }}" 
                               class="w-full px-3 py-2 text-sm rounded border border-slate-200">
                    </div>
                    <div class="grid gap-3 sm:grid-cols-3">
                        <div class="space-y-1">
                            <label class="block text-xs text-slate-600">{{ __('City') }}</label>
                            <input type="text" 
                                   name="billing_city" 
                                   id="city"
                                   value="{{ old('billing_city') }}" 
                                   class="w-full px-3 py-2 text-sm rounded border border-slate-200">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs text-slate-600">{{ __('Region') }}</label>
                            <input type="text" 
                                   name="billing_state" 
                                   id="state"
                                   value="{{ old('billing_state') }}" 
                                   class="w-full px-3 py-2 text-sm rounded border border-slate-200">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs text-slate-600">{{ __('Postal code') }}</label>
                            <input type="text" 
                                   name="billing_postal_code" 
                                   id="postal_code"
                                   value="{{ old('billing_postal_code') }}" 
                                   class="w-full px-3 py-2 text-sm rounded border border-slate-200">
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <h2 class="text-sm font-semibold">{{ __('Shipping') }}</h2>
                    <label class="inline-flex items-center gap-2 text-xs">
                        <input type="checkbox" name="shipping_same_as_billing" value="1" checked>
                        <span>{{ __('Shipping address is the same as billing address') }}</span>
                    </label>
                    <p class="text-[11px] text-slate-500">
                        يتم توصيل الطلبات داخل المدينة خلال ٢-٣ أيام عمل.
                    </p>
                </div>

                <div class="space-y-3">
                    <h2 class="text-sm font-semibold">{{ __('Payment method') }}</h2>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <label class="flex items-center gap-2 px-3 py-2 rounded border border-slate-200 text-sm">
                            <input type="radio" name="payment_method" value="cod" checked>
                            <span>{{ __('Cash on delivery') }}</span>
                        </label>
                        <label class="flex items-center gap-2 px-3 py-2 rounded border border-slate-200 text-sm">
                            <input type="radio" name="payment_method" value="whatsapp">
                            <span>{{ __('Order via WhatsApp') }}</span>
                        </label>
                        <label class="flex items-center gap-2 px-3 py-2 rounded border border-slate-200 text-sm">
                            <input type="radio" name="payment_method" value="stripe">
                            <span>{{ __('Card payment (Stripe)') }}</span>
                        </label>
                        <label class="flex items-center gap-2 px-3 py-2 rounded border border-slate-200 text-sm">
                            <input type="radio" name="payment_method" value="paymob">
                            <span>{{ __('Local payment (PayMob/Fawry)') }}</span>
                        </label>
                    </div>
                    <p class="text-[11px] text-slate-500">
                        لن يتم خصم أي مبلغ إلكترونياً حالياً، سيتم تأكيد الطلب يدوياً.
                    </p>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs text-slate-600">{{ __('Order notes (optional)') }}</label>
                    <textarea name="customer_notes" rows="3" class="w-full px-3 py-2 text-sm rounded border border-slate-200">{{ old('customer_notes') }}</textarea>
                </div>

                <button type="submit" class="px-6 py-2.5 rounded-full bg-amber-500 text-white text-sm font-medium">
                    {{ __('Place order') }}
                </button>
            </form>
        </div>

        <aside class="space-y-4">
            <div class="bg-white rounded-xl shadow-sm p-4 space-y-3">
                <h2 class="text-sm font-semibold mb-2">{{ __('Order summary') }}</h2>
                <ul class="space-y-2 text-sm">
                    @foreach($items as $item)
                        <li class="flex justify-between gap-2">
                            <span class="text-slate-700">
                                {{ $item['name'] }} × {{ $item['quantity'] }}
                            </span>
                            <span class="font-medium">
                                {{ number_format($item['price'] * $item['quantity'], 2) }} {{ __('EGP') }}
                            </span>
                        </li>
                    @endforeach
                </ul>
                <div class="border-t pt-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-600">{{ __('Subtotal') }}</span>
                        <span class="font-medium">
                            {{ number_format($subtotal, 2) }} {{ __('EGP') }}
                        </span>
                    </div>
                    <div class="flex justify-between text-xs text-slate-500 mt-1">
                        <span>{{ __('Shipping') }}</span>
                        <span>{{ __('To be calculated') }}</span>
                    </div>
                </div>
            </div>
        </aside>
    </section>

    <!-- Location Service Script -->
    <script src="{{ asset('js/location-service.js') }}"></script>
@endsection
