@extends('layouts.storefront')

@section('title', __('Order') . ' #' . $order->order_number . ' | مكتبة الصديق')

@section('content')
    <section class="max-w-5xl mx-auto px-4 py-8 space-y-6">
        <header class="space-y-2">
            <h1 class="text-xl font-semibold">
                {{ __('Thank you for your order') }}
            </h1>
            <p class="text-sm text-slate-600">
                {{ __('Your order number is') }}
                <span class="font-mono font-semibold">#{{ $order->order_number }}</span>
            </p>
        </header>

        <div class="grid gap-6 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">
            <div class="space-y-4">
                <div class="bg-white rounded-xl shadow-sm p-4">
                    <h2 class="text-sm font-semibold mb-3">{{ __('Items') }}</h2>
                    <ul class="divide-y text-sm">
                        @foreach($order->items as $item)
                            <li class="py-2 flex justify-between gap-3">
                                <div>
                                    <p class="font-medium text-slate-800">
                                        {{ $item->product_name }}
                                    </p>
                                    <p class="text-xs text-slate-500">
                                        {{ __('Quantity') }}: {{ $item->quantity }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-slate-500">
                                        {{ number_format($item->unit_price, 2) }} {{ __('EGP') }}
                                    </p>
                                    <p class="text-sm font-semibold text-amber-700">
                                        {{ number_format($item->total, 2) }} {{ __('EGP') }}
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div class="bg-white rounded-xl shadow-sm p-4 text-sm space-y-1">
                        <h2 class="text-sm font-semibold mb-2">{{ __('Billing details') }}</h2>
                        <p>{{ $order->billing_first_name }} {{ $order->billing_last_name }}</p>
                        <p>{{ $order->billing_address_line1 }}</p>
                        @if($order->billing_address_line2)
                            <p>{{ $order->billing_address_line2 }}</p>
                        @endif
                        <p>{{ $order->billing_city }} {{ $order->billing_postal_code }}</p>
                        <p>{{ $order->billing_state }}</p>
                        <p>{{ $order->billing_phone }}</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-4 text-sm space-y-1">
                        <h2 class="text-sm font-semibold mb-2">{{ __('Shipping details') }}</h2>
                        <p>{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</p>
                        <p>{{ $order->shipping_address_line1 }}</p>
                        @if($order->shipping_address_line2)
                            <p>{{ $order->shipping_address_line2 }}</p>
                        @endif
                        <p>{{ $order->shipping_city }} {{ $order->shipping_postal_code }}</p>
                        <p>{{ $order->shipping_state }}</p>
                        <p>{{ $order->shipping_phone }}</p>
                    </div>
                </div>
            </div>

            <aside class="space-y-3">
                <div class="bg-white rounded-xl shadow-sm p-4 text-sm space-y-2">
                    <h2 class="text-sm font-semibold mb-2">{{ __('Order summary') }}</h2>
                    <div class="flex justify-between">
                        <span class="text-slate-600">{{ __('Subtotal') }}</span>
                        <span>{{ number_format($order->subtotal, 2) }} {{ __('EGP') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">{{ __('Shipping') }}</span>
                        <span>{{ number_format($order->shipping_total, 2) }} {{ __('EGP') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">{{ __('Discount') }}</span>
                        <span>-{{ number_format($order->discount_total, 2) }} {{ __('EGP') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">{{ __('Tax') }}</span>
                        <span>{{ number_format($order->tax_total, 2) }} {{ __('EGP') }}</span>
                    </div>
                    <div class="border-t pt-2 mt-2 flex justify-between items-center">
                        <span class="font-semibold">{{ __('Total') }}</span>
                        <span class="text-lg font-semibold text-amber-700">
                            {{ number_format($order->grand_total, 2) }} {{ __('EGP') }}
                        </span>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-4 text-xs text-slate-600 space-y-1">
                    <p>{{ __('Payment method') }}: {{ strtoupper($order->payment_method) }}</p>
                    <p>{{ __('Payment status') }}: {{ $order->payment_status }}</p>
                    <p>{{ __('Order status') }}: {{ $order->status }}</p>
                </div>
            </aside>
        </div>
    </section>
@endsection

