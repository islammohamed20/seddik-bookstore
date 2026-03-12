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

                    <div id="shipping-address-container" class="space-y-3 mt-3 hidden border-l-2 border-slate-200 pl-4">
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div class="space-y-1">
                                <label class="block text-xs text-slate-600">{{ __('First name') }}</label>
                                <input type="text" name="shipping_first_name" value="{{ old('shipping_first_name') }}" class="w-full px-3 py-2 text-sm rounded border border-slate-200">
                            </div>
                            <div class="space-y-1">
                                <label class="block text-xs text-slate-600">{{ __('Last name') }}</label>
                                <input type="text" name="shipping_last_name" value="{{ old('shipping_last_name') }}" class="w-full px-3 py-2 text-sm rounded border border-slate-200">
                            </div>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs text-slate-600">{{ __('Street address') }}</label>
                            <input type="text" name="shipping_address_line1" value="{{ old('shipping_address_line1') }}" class="w-full px-3 py-2 text-sm rounded border border-slate-200">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs text-slate-600">{{ __('Apartment, floor, etc. (optional)') }}</label>
                            <input type="text" name="shipping_address_line2" value="{{ old('shipping_address_line2') }}" class="w-full px-3 py-2 text-sm rounded border border-slate-200">
                        </div>
                        <div class="grid gap-3 sm:grid-cols-3">
                            <div class="space-y-1">
                                <label class="block text-xs text-slate-600">{{ __('City') }}</label>
                                <input type="text" name="shipping_city" value="{{ old('shipping_city') }}" class="w-full px-3 py-2 text-sm rounded border border-slate-200">
                            </div>
                            <div class="space-y-1">
                                <label class="block text-xs text-slate-600">{{ __('Region') }}</label>
                                <input type="text" name="shipping_state" value="{{ old('shipping_state') }}" class="w-full px-3 py-2 text-sm rounded border border-slate-200">
                            </div>
                            <div class="space-y-1">
                                <label class="block text-xs text-slate-600">{{ __('Postal code') }}</label>
                                <input type="text" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}" class="w-full px-3 py-2 text-sm rounded border border-slate-200">
                            </div>
                        </div>
                    </div>

                    <p class="text-[11px] text-slate-500">
                        يتم توصيل الطلبات داخل المدينة خلال ٢-٣ أيام عمل.
                    </p>
                </div>

                <!-- Shipping Methods -->
                <div id="shipping-methods-wrapper" class="space-y-3 hidden">
                    <h2 class="text-sm font-semibold">{{ __('Shipping Method') }}</h2>
                    <div id="shipping-methods-container" class="space-y-2">
                        <!-- Dynamic Content -->
                    </div>
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
                        <span id="subtotal-display">{{ number_format($subtotal, 2) }} {{ __('EGP') }}</span>
                    </div>
                    <div class="flex justify-between text-xs text-slate-500 mt-1">
                        <span>{{ __('Shipping') }}</span>
                        <span id="shipping-cost-display">{{ __('To be calculated') }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-slate-800 mt-2 pt-2 border-t">
                        <span>{{ __('Total') }}</span>
                        <span id="total-display">{{ number_format($grandTotal, 2) }} {{ __('EGP') }}</span>
                    </div>
                </div>
            </div>
        </aside>
    </section>

    <!-- Location Service Script -->
    <script src="{{ asset('js/location-service.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const subtotal = {{ $subtotal }};
            const discount = {{ $couponDiscount ?? 0 }};
            
            const billingCity = document.querySelector('[name="billing_city"]');
            const billingCountry = document.querySelector('[name="billing_country_code"]');
            const shippingCity = document.querySelector('[name="shipping_city"]');
            const shippingCountry = document.querySelector('[name="shipping_country_code"]');
            const sameAsBilling = document.querySelector('[name="shipping_same_as_billing"]');
            
            const methodsWrapper = document.getElementById('shipping-methods-wrapper');
            const methodsContainer = document.getElementById('shipping-methods-container');
            const shippingCostDisplay = document.getElementById('shipping-cost-display');
            const totalDisplay = document.getElementById('total-display');

            function calculateShipping() {
                let city, country;
                
                if (sameAsBilling && sameAsBilling.checked) {
                    city = billingCity ? billingCity.value : '';
                    country = billingCountry ? billingCountry.value : 'EG';
                } else {
                    city = shippingCity ? shippingCity.value : '';
                    country = shippingCountry ? shippingCountry.value : 'EG';
                }

                if (!city) return;

                fetch('{{ route("checkout.calculate-shipping") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ city, country })
                })
                .then(response => response.json())
                .then(data => {
                    methodsContainer.innerHTML = '';
                    methodsWrapper.classList.remove('hidden');
                    
                    if (data.methods.length === 0) {
                        methodsContainer.innerHTML = '<p class="text-xs text-red-500">لا توجد طرق شحن متاحة لهذا العنوان</p>';
                        shippingCostDisplay.textContent = 'غير متوفر';
                        updateTotal(0); // Assuming 0 if unavailable? Or prevent submit?
                        return;
                    }

                    data.methods.forEach((method, index) => {
                        const div = document.createElement('div');
                        div.className = 'flex items-center gap-2 px-3 py-2 rounded border border-slate-200 text-sm cursor-pointer hover:bg-slate-50';
                        div.innerHTML = `
                            <input type="radio" name="shipping_method" value="${method.value}" ${index === 0 ? 'checked' : ''} data-cost="${method.cost}" id="method_${method.id}">
                            <div class="flex-1">
                                <label for="method_${method.id}" class="flex justify-between cursor-pointer w-full">
                                    <span class="font-medium">${method.label}</span>
                                </label>
                                ${method.description ? `<p class="text-xs text-slate-500">${method.description}</p>` : ''}
                            </div>
                        `;
                        div.addEventListener('click', function(e) {
                            if (e.target.tagName !== 'INPUT') {
                                const input = this.querySelector('input');
                                input.checked = true;
                                input.dispatchEvent(new Event('change', {bubbles: true}));
                            }
                        });
                        methodsContainer.appendChild(div);
                    });

                    // Trigger change to update total
                    const firstInput = methodsContainer.querySelector('input[type="radio"]');
                    if (firstInput) {
                        firstInput.dispatchEvent(new Event('change', {bubbles: true}));
                        updateTotal(parseFloat(firstInput.dataset.cost));
                    }
                })
                .catch(error => console.error('Error:', error));
            }

            function updateTotal(shippingCost) {
                shippingCostDisplay.textContent = shippingCost > 0 ? shippingCost.toFixed(2) + ' EGP' : 'مجاني';
                const total = subtotal + shippingCost - discount;
                totalDisplay.textContent = total.toFixed(2) + ' EGP';
            }

            methodsContainer.addEventListener('change', function(e) {
                if (e.target.name === 'shipping_method') {
                    updateTotal(parseFloat(e.target.dataset.cost));
                }
            });

            [billingCity, billingCountry, shippingCity, shippingCountry].forEach(el => {
                if (el) {
                    el.addEventListener('change', calculateShipping);
                    el.addEventListener('blur', calculateShipping);
                }
            });

            if (sameAsBilling) {
                sameAsBilling.addEventListener('change', function() {
                    const container = document.getElementById('shipping-address-container');
                    if (this.checked) {
                        container.classList.add('hidden');
                    } else {
                        container.classList.remove('hidden');
                    }
                    calculateShipping();
                });
            }

            // Initial calculation if city is filled
            if (billingCity && billingCity.value) {
                calculateShipping();
            }
        });
    </script>
@endsection