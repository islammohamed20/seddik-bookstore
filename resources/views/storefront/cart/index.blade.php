@extends('layouts.storefront')

@section('title', __('سلة التسوق') . ' - ' . __('مكتبة الصديق'))

@section('content')
<!-- Breadcrumb -->
<div class="bg-gray-100 py-4">
    <div class="container mx-auto px-4">
        <nav class="flex items-center gap-2 text-sm">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-primary-blue transition">
                <i class="fas fa-home"></i>
            </a>
            <i class="fas fa-chevron-left text-gray-400 text-xs"></i>
            <span class="text-primary-blue font-semibold">سلة التسوق</span>
        </nav>
    </div>
</div>

<section class="py-8">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-primary-blue mb-8 flex items-center gap-3">
            <i class="fas fa-shopping-cart"></i>
            سلة التسوق
        </h1>

        @if(session('status'))
            <div class="mb-6 p-4 rounded-lg flex items-center gap-3 
                {{ session('status') === 'removed_from_cart' ? 'bg-yellow-50 border border-yellow-200 text-yellow-800' : 'bg-green-50 border border-green-200 text-green-800' }}">
                <i class="fas {{ session('status') === 'removed_from_cart' ? 'fa-info-circle' : 'fa-check-circle' }} text-lg"></i>
                <span>
                    @if(session('status') === 'added_to_cart')
                        تمت إضافة المنتج إلى السلة بنجاح!
                    @elseif(session('status') === 'cart_updated')
                        تم تحديث السلة بنجاح!
                    @elseif(session('status') === 'removed_from_cart')
                        تم حذف المنتج من السلة.
                    @endif
                </span>
            </div>
        @endif

        @if(empty($items))
            <!-- Empty Cart State -->
            <div class="bg-white rounded-2xl shadow-lg p-12 text-right max-w-2xl mx-auto">
                <div class="w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-shopping-cart text-5xl text-gray-300"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-3">سلة التسوق فارغة!</h2>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    لم تقم بإضافة أي منتجات إلى السلة بعد. تصفح منتجاتنا واختر ما يناسبك.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('products.index') }}" 
                       class="inline-flex items-center bg-primary-blue text-white px-8 py-4 rounded-lg font-bold hover:bg-primary-blue/90 transition transform hover:scale-105">
                        <i class="fas fa-store ml-2"></i>
                        تصفح المنتجات
                    </a>
                    <a href="{{ route('offers') }}" 
                       class="inline-flex items-center border-2 border-primary-yellow text-primary-yellow px-8 py-4 rounded-lg font-bold hover:bg-primary-yellow hover:text-primary-blue transition">
                        <i class="fas fa-tags ml-2"></i>
                        العروض
                    </a>
                </div>
            </div>
        @else
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-4">
                    @foreach($items as $item)
                    <div class="bg-white rounded-xl shadow-lg p-4 flex gap-4 group hover:shadow-xl transition">
                        <!-- Product Image -->
                        <div class="w-24 h-24 md:w-32 md:h-32 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                            @if(!empty($item['image']))
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($item['image']) }}" 
                                     alt="{{ $item['name'] }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-image text-3xl text-gray-300"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Product Details -->
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start gap-2">
                                <div>
                                    <a href="{{ route('products.show', ['product' => $item['slug'] ?? $item['product_id']]) }}" 
                                       class="font-bold text-gray-900 hover:text-primary-blue transition line-clamp-2 text-lg">
                                        {{ $item['name'] }}
                                    </a>
                                    @if(!empty($item['variant_name']))
                                    <p class="text-sm text-indigo-600 mt-0.5">
                                        <i class="fas fa-tag ml-1"></i>{{ $item['variant_name'] }}
                                    </p>
                                    @elseif(!empty($item['variant_label']))
                                    <p class="text-sm text-indigo-600 mt-0.5">
                                        <i class="fas fa-tag ml-1"></i>{{ $item['variant_label'] }}
                                    </p>
                                    @endif
                                    @if(isset($item['category']))
                                    <p class="text-sm text-gray-500 mt-1">{{ $item['category'] }}</p>
                                    @endif
                                </div>
                                
                                <!-- Remove Button -->
                                <form method="post" action="{{ route('cart.destroy', ['product' => $item['product_id']]) }}">
                                    @csrf
                                    @method('delete')
                                    @if(!empty($item['variant_id']))
                                        <input type="hidden" name="variant_id" value="{{ $item['variant_id'] }}">
                                    @endif
                                    <button type="submit" 
                                            class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition"
                                            title="حذف">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>

                            <div class="flex flex-wrap items-end justify-between gap-4 mt-4">
                                <!-- Quantity -->
                                <form method="post" action="{{ route('cart.update', ['product' => $item['product_id']]) }}" 
                                      class="flex items-center gap-2" x-data="{ qty: {{ (int) $item['quantity'] }} }">
                                    @csrf
                                    @method('patch')
                                    @if(!empty($item['variant_id']))
                                        <input type="hidden" name="variant_id" value="{{ $item['variant_id'] }}">
                                    @endif
                                    <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                        <button type="button" 
                                                @click="qty = Math.max(1, qty - 1); $nextTick(() => $el.closest('form').submit())"
                                                class="w-10 h-10 flex items-center justify-center bg-gray-50 hover:bg-gray-100 transition text-gray-600">
                                            <i class="fas fa-minus text-sm"></i>
                                        </button>
                                        <input type="number" 
                                               name="quantity" 
                                               x-model="qty"
                                               min="1" 
                                               max="100"
                                               class="w-14 h-10 text-right border-0 focus:ring-0 font-semibold">
                                        <button type="button" 
                                                @click="qty = Math.min(100, qty + 1); $nextTick(() => $el.closest('form').submit())"
                                                class="w-10 h-10 flex items-center justify-center bg-gray-50 hover:bg-gray-100 transition text-gray-600">
                                            <i class="fas fa-plus text-sm"></i>
                                        </button>
                                    </div>
                                </form>

                                <!-- Price -->
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">السعر</p>
                                    <p class="text-xl font-bold text-primary-blue">
                                        {{ number_format($item['price'] * $item['quantity'], 2) }} <span class="text-sm">ج.م</span>
                                    </p>
                                    @if($item['quantity'] > 1)
                                        <p class="text-xs text-gray-400">{{ number_format($item['price'], 2) }} × {{ $item['quantity'] }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <!-- Clear Cart -->
                    <div class="flex justify-between items-center pt-4">
                        <a href="{{ route('products.index') }}" class="text-primary-blue hover:underline flex items-center gap-2">
                            <i class="fas fa-arrow-right"></i>
                            متابعة التسوق
                        </a>
                        <form method="post" action="{{ route('cart.clear') }}" x-data>
                            @csrf
                            @method('delete')
                            <button type="submit" 
                                    class="text-red-500 hover:text-red-700 flex items-center gap-2 text-sm"
                                    @click.prevent="if (confirm('هل أنت متأكد من إفراغ السلة؟')) $el.closest('form').submit()">
                                <i class="fas fa-trash"></i>
                                إفراغ السلة
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg p-6 sticky top-4">
                        <h3 class="text-xl font-bold text-primary-blue mb-6 pb-4 border-b">ملخص الطلب</h3>
                        
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-gray-600">
                                <span>المجموع الفرعي</span>
                                <span class="font-semibold">{{ number_format($subtotal, 2) }} ج.م</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>الشحن</span>
                                <span class="text-green-600 font-semibold">
                                    @if($subtotal >= 200)
                                        مجاني
                                    @else
                                        يحسب عند الدفع
                                    @endif
                                </span>
                            </div>
                            @if($subtotal < 200)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                <p class="text-sm text-yellow-800">
                                    <i class="fas fa-truck ml-1"></i>
                                    أضف {{ number_format(200 - $subtotal, 2) }} ج.م للحصول على شحن مجاني!
                                </p>
                                <div class="mt-2 bg-yellow-200 rounded-full h-2 overflow-hidden">
                                    @php $progress = min(100, ($subtotal > 0 ? ($subtotal / 200) * 100 : 0)); @endphp
                                    <div id="free-shipping-progress" 
                                         class="bg-yellow-500 h-full rounded-full transition-all" 
                                         data-progress="{{ (int) $progress }}"></div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Coupon -->
                        <div class="mb-6">
                            <form action="{{ route('cart.apply-coupon') }}" method="POST" class="flex gap-2">
                                @csrf
                                <input type="text" 
                                       name="coupon_code" 
                                       placeholder="كود الخصم"
                                       class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary-yellow focus:border-transparent">
                                <button type="submit" 
                                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold transition">
                                    تطبيق
                                </button>
                            </form>
                        </div>

                        <div class="border-t pt-4 mb-6">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-900">الإجمالي</span>
                                <span class="text-2xl font-bold text-primary-blue">{{ number_format($subtotal, 2) }} ج.م</span>
                            </div>
                        </div>

                        <a href="{{ route('checkout.index') }}" 
                           class="w-full bg-primary-yellow hover:bg-yellow-400 text-primary-blue font-bold py-4 rounded-lg transition flex items-center justify-center gap-2 text-lg transform hover:scale-[1.02]">
                            <i class="fas fa-lock"></i>
                            إتمام الطلب
                        </a>

                        <!-- Trust Badges -->
                        <div class="mt-6 pt-6 border-t">
                            <div class="flex items-center justify-center gap-6 text-gray-400 text-sm">
                                <div class="flex items-center gap-1">
                                    <i class="fas fa-shield-alt"></i>
                                    <span>دفع آمن</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <i class="fas fa-undo"></i>
                                    <span>استرجاع سهل</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- You May Also Like -->
@if(!empty($items))
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-bold text-primary-blue mb-6">قد يعجبك أيضاً</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Placeholder for related products -->
            @for($i = 0; $i < 4; $i++)
            <div class="bg-white rounded-xl shadow p-4 text-right">
                <div class="aspect-square bg-gray-100 rounded-lg mb-3 flex items-center justify-center">
                    <i class="fas fa-image text-3xl text-gray-300"></i>
                </div>
                <p class="text-sm text-gray-400">منتجات مقترحة</p>
            </div>
            @endfor
        </div>
    </div>
</section>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var el = document.getElementById('free-shipping-progress');
    if (el) {
        var val = parseInt(el.getAttribute('data-progress'), 10);
        var pct = Math.max(0, Math.min(100, isNaN(val) ? 0 : val));
        el.style.width = pct + '%';
    }
});
</script>
@endpush
@endsection
