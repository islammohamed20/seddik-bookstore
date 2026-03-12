@props(['product', 'badge' => null, 'badgeColor' => 'green'])

@php
    $p = $product;
    $isOnSale = $p->sale_price && $p->sale_price < $p->price;
    $discountPercent = $isOnSale ? (int) round((($p->price - $p->sale_price) / $p->price) * 100) : 0;
    $activeBadge = $badge ?: ($isOnSale ? ('خصم ' . $discountPercent . '%') : ($p->created_at?->gt(now()->subDays(20)) ? 'جديد' : null));
    $badgeColors = [
        'green' => 'bg-green-500',
        'red' => 'bg-primary-red',
        'yellow' => 'bg-primary-yellow text-primary-blue',
        'blue' => 'bg-primary-blue',
    ];
    $activeBadgeColor = $isOnSale ? 'red' : $badgeColor;
    $badgeBg = $badgeColors[$activeBadgeColor] ?? $badgeColors['green'];
    $rating = (float) ($p->average_rating ?? 0);
    $ratingCount = (int) ($p->reviews_count ?? 0);
@endphp

@once
    @push('styles')
        <style>
            .mobile-product-card {
                -webkit-tap-highlight-color: transparent;
                transition: transform 0.22s ease, box-shadow 0.22s ease;
            }

            .mobile-product-card:active {
                transform: scale(0.985);
            }

            .mobile-product-card .title-clamp {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
                min-height: 2.5rem;
            }
        </style>
    @endpush
@endonce

<article class="mobile-product-card group bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl overflow-hidden w-full">
    <a href="{{ route('products.show', $p) }}" class="block">
        <div class="relative aspect-square bg-slate-100 overflow-hidden">
            @if($p->primary_image)
            <img src="{{ $p->primary_image->url }}" 
                 alt="{{ $p->name_ar }}"
                 loading="lazy"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            @elseif($p->images->first())
            <img src="{{ $p->images->first()->url }}" 
                 alt="{{ $p->name_ar }}"
                 loading="lazy"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            @else
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200">
                <i class="fas fa-image text-slate-300 text-4xl"></i>
            </div>
            @endif

            @if($activeBadge)
            <span class="absolute top-2 right-2 inline-flex items-center {{ $badgeBg }} text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm z-10">
                {{ $activeBadge }}
            </span>
            @endif

            <div class="absolute top-2 left-2 z-10">
                <span class="sr-only">المفضلة</span>
            </div>

            <div class="absolute top-2 left-2 z-20">
            <form action="{{ route('wishlist.toggle', $p) }}" method="POST">
                @csrf
                <button type="submit"
                        class="w-8 h-8 bg-white/95 backdrop-blur rounded-full flex items-center justify-center {{ in_array($p->id, session('wishlist', [])) ? 'text-white bg-rose-500' : 'text-rose-500 hover:bg-rose-500 hover:text-white' }} transition-all duration-200 shadow"
                        title="{{ in_array($p->id, session('wishlist', [])) ? 'إزالة من المفضلة' : 'أضف للمفضلة' }}">
                    <i class="fas fa-heart text-[12px]"></i>
                </button>
            </form>
            </div>
        </div>
    </a>

    <div class="p-3">
        <h3 class="title-clamp text-[13px] sm:text-sm font-semibold leading-5 text-slate-800 mb-2 group-hover:text-primary-blue transition-colors">
            <a href="{{ route('products.show', $p) }}">{{ $p->name_ar }}</a>
        </h3>

        @if($ratingCount > 0)
            <div class="flex items-center gap-1 mb-2">
                <div class="flex items-center text-amber-400 text-[11px]">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="{{ $rating >= $i ? 'fas' : 'far' }} fa-star"></i>
                    @endfor
                </div>
                <span class="text-[11px] text-slate-500">({{ $ratingCount }})</span>
            </div>
        @endif

        <div class="mb-4">
            @if($isOnSale)
                <div class="flex items-center gap-1.5">
                    <span class="text-base sm:text-lg font-extrabold text-primary-red leading-none">{{ number_format($p->final_price, 2) }} ج.م</span>
                    <span class="text-[11px] text-slate-400 line-through">{{ number_format($p->price, 2) }}</span>
                </div>
            @else
                <span class="text-base sm:text-lg font-extrabold text-primary-blue leading-none">{{ number_format($p->final_price, 2) }} ج.م</span>
            @endif
        </div>

        @if($p->is_available)
            <form action="{{ route('cart.store', $p) }}" method="POST">
                @csrf
                <button type="submit"
                        class="w-full h-9 rounded-xl bg-primary-blue text-white text-xs font-bold tracking-wide hover:bg-primary-blue/90 active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-1.5"
                        title="أضف للسلة">
                    <i class="fas fa-shopping-cart text-[12px]"></i>
                    إضافة للسلة
                </button>
            </form>
        @else
            <div class="w-full h-9 rounded-xl bg-slate-100 text-slate-500 text-xs font-semibold flex items-center justify-center">
                غير متوفر حالياً
            </div>
        @endif
    </div>
</article>
