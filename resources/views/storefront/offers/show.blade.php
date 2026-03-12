@extends('layouts.storefront')

@section('title', ($offer->name_ar ?? $offer->name_en) . ' - ' . __('العروض') . ' - ' . __('مكتبة الصديق'))

@section('content')
@php
    $heroFrom = $offer->banner_color_from ?: '#003399';
    $heroTo = $offer->banner_color_to ?: '#003D7A';
    $useHeroGradient = $offer->banner_color_from || $offer->banner_color_to;
@endphp
<section class="bg-gradient-to-br from-primary-blue to-primary-blue-dark py-14"
         style="{{ $useHeroGradient ? 'background-image: linear-gradient(135deg, ' . $heroFrom . ', ' . $heroTo . ');' : '' }}">
    <div class="container mx-auto px-4">
        <nav class="text-white/80 text-sm mb-6">
            <a href="{{ route('home') }}" class="hover:text-white">الرئيسية</a>
            <span class="mx-2">/</span>
            <a href="{{ route('offers') }}" class="hover:text-white">العروض</a>
            <span class="mx-2">/</span>
            <span class="text-primary-yellow font-semibold">{{ $offer->name_ar ?? $offer->name_en }}</span>
        </nav>

        <div class="grid lg:grid-cols-3 gap-8 items-start">
            <div class="lg:col-span-2">
                <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-3">
                    {{ $offer->name_ar ?? $offer->name_en }}
                </h1>
                @if($offer->description_ar || $offer->description_en)
                    <p class="text-white/90 leading-relaxed max-w-3xl">
                        {{ $offer->description_ar ?? $offer->description_en }}
                    </p>
                @endif

                <div class="flex flex-wrap gap-3 mt-6">
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 text-white border border-white/15">
                        <i class="fas fa-tag text-primary-yellow"></i>
                        @if($offer->discount_type === 'percent')
                            خصم {{ (int) $offer->discount_value }}%
                        @elseif($offer->discount_type === 'fixed')
                            خصم {{ number_format((float) ($offer->discount_value ?? 0), 2) }} ج.م
                        @else
                            شحن مجاني
                        @endif
                    </span>
                    @if($offer->ends_at)
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 text-white border border-white/15">
                            <i class="fas fa-clock text-primary-yellow"></i>
                            ينتهي: {{ $offer->ends_at->format('Y/m/d') }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                @if($offer->banner_image)
                    <img src="{{ Storage::url($offer->banner_image) }}" alt="{{ $offer->name_ar ?? $offer->name_en }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gradient-to-br from-primary-yellow to-amber-400 flex items-center justify-center">
                        <i class="fas fa-tags text-5xl text-primary-blue/50"></i>
                    </div>
                @endif
                <div class="p-5">
                    <a href="{{ route('products.index') }}" class="block w-full text-center bg-primary-yellow hover:bg-primary-yellow-dark text-primary-blue font-bold py-3 rounded-xl transition">
                        تصفح كل المنتجات
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-14 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex items-end justify-between gap-4 mb-8">
            <h2 class="text-2xl md:text-3xl font-extrabold text-primary-blue">منتجات العرض</h2>
            <a href="{{ route('offers') }}" class="text-sm text-primary-blue hover:underline">عودة للعروض</a>
        </div>

        @if($products->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach($products as $p)
                    <a href="{{ route('products.show', $p) }}" class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all transform hover:-translate-y-1">
                        <div class="relative aspect-square bg-gray-100 overflow-hidden">
                            @if($p->primary_image)
                                <img src="{{ $p->primary_image->url }}" alt="{{ $p->name_ar }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @elseif($p->images->first())
                                <img src="{{ $p->images->first()->url }}" alt="{{ $p->name_ar }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-image text-gray-300 text-4xl"></i>
                                </div>
                            @endif

                            @if($p->sale_price && $p->sale_price < $p->price)
                                <span class="absolute top-3 right-3 bg-primary-red text-white text-xs font-bold px-3 py-1 rounded-full">
                                    -{{ $p->price > 0 ? number_format((($p->price - $p->sale_price) / $p->price) * 100) : 0 }}%
                                </span>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-gray-800 line-clamp-2 group-hover:text-primary-blue transition">{{ $p->name_ar }}</h3>
                            <div class="mt-3 flex items-center justify-between">
                                <div class="text-sm">
                                    @if($p->sale_price)
                                        <span class="font-extrabold text-primary-blue">{{ number_format($p->sale_price, 2) }} ج.م</span>
                                        <span class="text-gray-400 line-through ml-2">{{ number_format($p->price, 2) }}</span>
                                    @else
                                        <span class="font-extrabold text-primary-blue">{{ number_format($p->price, 2) }} ج.م</span>
                                    @endif
                                </div>
                                <span class="text-xs px-2 py-1 rounded-full {{ $p->is_available ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $p->is_available ? 'متوفر' : 'غير متوفر' }}
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $products->links() }}
            </div>
        @else
            <div class="bg-white rounded-2xl shadow p-10 text-center">
                <i class="fas fa-box-open text-5xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-extrabold text-gray-800 mb-2">لا توجد منتجات ضمن هذا العرض حالياً</h3>
                <p class="text-gray-600 mb-6">جرّب تصفح المنتجات أو العودة للعروض.</p>
                <div class="flex flex-wrap justify-center gap-3">
                    <a href="{{ route('products.index') }}" class="bg-primary-blue text-white px-6 py-3 rounded-xl font-bold hover:bg-primary-blue/90 transition">تصفح المنتجات</a>
                    <a href="{{ route('offers') }}" class="bg-primary-yellow text-primary-blue px-6 py-3 rounded-xl font-bold hover:bg-primary-yellow-dark transition">عودة للعروض</a>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
