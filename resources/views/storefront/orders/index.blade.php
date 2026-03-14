@extends('layouts.storefront')

@section('title', 'طلباتي | مكتبة الصديق')

@section('content')
<section class="max-w-5xl mx-auto px-4 py-8 space-y-6">
    <header class="flex items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-primary-blue">طلباتي</h1>
            <p class="text-sm text-slate-600">تابع حالة طلباتك وراجع التفاصيل.</p>
        </div>
        <a href="{{ route('products.index') }}" class="px-4 py-2 rounded-lg bg-primary-blue text-white text-sm font-semibold hover:bg-blue-700 transition">
            متابعة التسوق
        </a>
    </header>

    @if(session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 text-green-700 px-4 py-3 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 text-red-700 px-4 py-3 text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if($orders->isEmpty())
        <div class="bg-white rounded-xl shadow-sm p-8 text-center">
            <p class="text-slate-600 mb-4">لا توجد طلبات حتى الآن.</p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-blue text-white text-sm font-semibold hover:bg-blue-700 transition">
                ابدأ أول طلب
            </a>
        </div>
    @else
        <div class="space-y-3">
            @foreach($orders as $order)
                <article class="bg-white rounded-xl shadow-sm p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="space-y-1">
                        <p class="text-sm text-slate-500">رقم الطلب</p>
                        <p class="font-mono font-semibold text-slate-900">#{{ $order->order_number }}</p>
                        <p class="text-xs text-slate-500">{{ $order->created_at?->format('Y-m-d h:i A') }}</p>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                        <div>
                            <p class="text-slate-500">الإجمالي</p>
                            <p class="font-semibold text-amber-700">{{ number_format($order->grand_total, 2) }} ج.م</p>
                        </div>
                        <div>
                            <p class="text-slate-500">الحالة</p>
                            <p class="font-medium">{{ $order->status }}</p>
                        </div>
                        <div>
                            <p class="text-slate-500">الدفع</p>
                            <p class="font-medium">{{ $order->payment_status }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('orders.show', $order) }}" class="px-4 py-2 rounded-lg border border-gray-200 text-sm font-semibold hover:bg-gray-50 transition">
                            عرض التفاصيل
                        </a>
                        @if($order->is_cancellable)
                            <form method="POST" action="{{ route('orders.cancel', $order) }}" onsubmit="return confirm('هل أنت متأكد من إلغاء الطلب؟');">
                                @csrf
                                <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-semibold hover:bg-red-700 transition">
                                    إلغاء
                                </button>
                            </form>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>

        <div>
            {{ $orders->links() }}
        </div>
    @endif
</section>
@endsection
