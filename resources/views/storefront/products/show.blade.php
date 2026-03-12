@extends('layouts.storefront')

@section('title', ($product->name_ar ?? $product->name_en) . ' | مكتبة الصديق')

@section('meta_description', Str::limit(strip_tags($product->description_ar ?? $product->description_en ?? ''), 160))

@section('og_title', ($product->name_ar ?? $product->name_en) . ' | مكتبة الصديق')
@section('og_description', Str::limit(strip_tags($product->description_ar ?? $product->description_en ?? ''), 200))
@section('og_image', $product->primary_image?->url ?? asset('images/og-default.jpg'))
@section('og_type', 'product')

@section('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org/",
    "@type": "Product",
    "name": "{{ $product->name_ar ?? $product->name_en }}",
    "image": [
        @if($product->images && $product->images->count() > 0)
            @foreach($product->images as $img)
            "{{ asset('storage/' . $img->path) }}"{{ !$loop->last ? ',' : '' }}
            @endforeach
        @else
            "{{ asset('images/no-product.png') }}"
        @endif
    ],
    "description": "{{ Str::limit(strip_tags($product->description_ar ?? $product->description_en ?? ''), 500) }}",
    "sku": "{{ $product->sku ?? $product->id }}",
    "brand": {
        "@type": "Brand",
        "name": "{{ $product->brand?->name_ar ?? $product->brand?->name_en ?? 'مكتبة الصديق' }}"
    },
    "offers": {
        "@type": "Offer",
        "url": "{{ route('products.show', $product) }}",
        "priceCurrency": "EGP",
        "price": "{{ $product->final_price }}",
        "priceValidUntil": "{{ now()->addMonth()->format('Y-m-d') }}",
        "availability": "{{ $product->stock_quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
        "seller": {
            "@type": "Organization",
            "name": "مكتبة الصديق"
        }
    }
    @if($product->category)
    ,"category": "{{ $product->category->name_ar ?? $product->category->name_en }}"
    @endif
}
</script>
@endsection

@section('content')
<nav class="bg-gray-50 py-4">
    <div class="container mx-auto px-4">
        <ol class="flex items-center flex-wrap gap-2 text-sm">
            <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-primary-blue transition"><i class="fas fa-home"></i></a></li>
            <li class="text-gray-400">/</li>
            <li><a href="{{ route('products.index') }}" class="text-gray-500 hover:text-primary-blue transition">المنتجات</a></li>
            @if($product->category)
            <li class="text-gray-400">/</li>
            <li><a href="{{ route('products.category', $product->category->slug) }}" class="text-gray-500 hover:text-primary-blue transition">{{ $product->category->name_ar }}</a></li>
            @endif
            <li class="text-gray-400">/</li>
            <li class="text-primary-blue font-medium truncate max-w-[200px]">{{ $product->name_ar ?? $product->name_en }}</li>
        </ol>
    </div>
</nav>

<section class="py-12 bg-white" x-data="productShow()">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
            <div class="space-y-4">
                <div class="relative rounded-2xl bg-gray-100 overflow-hidden shadow-lg group">
                    <div class="aspect-[4/3]">
                        @if($product->images && $product->images->count() > 0)
                        <img x-show="variantImageUrl" x-cloak
                             :src="variantImageUrl"
                             alt="{{ $product->name_ar }}"
                             class="w-full h-full object-cover transition-opacity duration-300"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100">
                        @foreach($product->images as $index => $image)
                        <img x-show="!variantImageUrl && activeImage === {{ $index }}"
                             src="{{ asset('storage/' . $image->path) }}"
                             alt="{{ $product->name_ar }}"
                             class="w-full h-full object-cover transition-opacity duration-300"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100">
                        @endforeach
                        @elseif($product->primary_image)
                        <img x-show="variantImageUrl" x-cloak
                             :src="variantImageUrl"
                             alt="{{ $product->name_ar }}"
                             class="w-full h-full object-cover">
                        <img src="{{ $product->primary_image->url }}"
                             alt="{{ $product->name_ar }}"
                             class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-image text-gray-300 text-6xl"></i>
                        </div>
                        @endif
                    </div>

                    <div class="absolute top-4 right-4 flex flex-col gap-2">
                        @if($product->sale_price && $product->sale_price < $product->price)
                        <span class="px-3 py-1.5 rounded-full bg-primary-red text-white text-sm font-bold shadow-lg">
                            خصم {{ $product->price > 0 ? number_format((($product->price - $product->sale_price) / $product->price) * 100) : 0 }}%
                        </span>
                        @endif
                        @if($product->is_featured)
                        <span class="px-3 py-1.5 rounded-full bg-primary-yellow text-primary-blue text-sm font-bold shadow-lg">
                            <i class="fas fa-star ml-1"></i> مميز
                        </span>
                        @endif
                        @if($product->is_bingo)
                        <span class="px-3 py-1.5 rounded-full bg-amber-500 text-white text-sm font-bold shadow-lg">
                            Bingo
                        </span>
                        @endif
                    </div>

                    <button class="absolute bottom-4 left-4 w-10 h-10 bg-white/90 rounded-full flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-opacity hover:bg-white">
                        <i class="fas fa-search-plus text-gray-700"></i>
                    </button>
                </div>

                @if($product->video_path)
                <div class="mt-4">
                    <video controls class="w-full rounded-2xl shadow-lg bg-black">
                        <source src="{{ asset('storage/' . $product->video_path) }}" type="video/mp4">
                        متصفحك لا يدعم تشغيل الفيديو.
                    </video>
                </div>
                @endif

                @if($product->images && $product->images->count() > 1)
                <div class="flex gap-3 overflow-x-auto pb-2">
                    @foreach($product->images as $index => $image)
                    <button @click="activeImage = {{ $index }}; variantImageUrl = null"
                            :class="!variantImageUrl && activeImage === {{ $index }} ? 'ring-2 ring-primary-blue' : 'ring-1 ring-gray-200'"
                            class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden transition-all hover:ring-primary-blue">
                        <img src="{{ asset('storage/' . $image->path) }}"
                             alt="صورة {{ $index + 1 }}"
                             class="w-full h-full object-cover">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="space-y-6">
                <div class="flex items-center gap-3 flex-wrap">
                    @if($product->category)
                    <a href="{{ route('products.category', $product->category->slug) }}"
                       class="text-sm text-gray-500 hover:text-primary-blue transition">
                        <i class="fas fa-folder-open ml-1"></i>
                        {{ $product->category->name_ar }}
                    </a>
                    @endif
                    @if($product->brand)
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('products.brand', $product->brand->slug) }}"
                       class="text-sm text-gray-500 hover:text-primary-blue transition">
                        <i class="fas fa-tag ml-1"></i>
                        {{ $product->brand->name_ar ?? $product->brand->name }}
                    </a>
                    @endif
                </div>

                @if($product->tagOptions && $product->tagOptions->count() > 0)
                <div class="flex items-center gap-2 flex-wrap">
                    @foreach($product->tagOptions as $tagOption)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200">
                        <i class="fas fa-tag ml-1 text-indigo-400"></i>
                        {{ $tagOption->name_ar ?? $tagOption->name_en }}
                    </span>
                    @endforeach
                </div>
                @endif

                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                    {{ $product->name_ar ?? $product->name_en }}
                </h1>
                <div class="text-sm text-gray-600 -mt-3" x-text="matchedVariant ? matchedVariant.label : ''"></div>
                <div class="text-2xl font-extrabold text-primary-red -mt-2" x-text="formatPrice(matchedVariant ? matchedVariant.price : basePrice)"></div>

                @if($product->subtitle_ar || $product->subtitle_en)
                <p class="text-gray-600 text-lg">
                    {{ $product->subtitle_ar ?? $product->subtitle_en }}
                </p>
                @endif

                <div class="flex items-center gap-3">
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= floor($product->average_rating) ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                        @endfor
                    </div>
                    <span class="text-gray-500 text-sm">({{ $product->reviews_count }} تقييم)</span>
                </div>

                <div class="flex items-center gap-4 flex-wrap">
                    @if($product->stock_status === 'out_of_stock' || (!$product->is_available ?? false))
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-100 text-red-700 font-medium">
                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                        غير متوفر في المخزون
                    </span>
                    @elseif($product->stock_status === 'low_stock')
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-100 text-amber-700 font-medium">
                        <span class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></span>
                        الكمية قليلة - اطلب الآن
                    </span>
                    @else
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-green-100 text-green-700 font-medium">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                        متوفر في المخزون
                    </span>
                    @endif

                    @if($product->sku)
                    <span class="text-sm text-gray-500">
                        <i class="fas fa-barcode ml-1"></i>
                        {{ $product->sku }}
                    </span>
                    @endif
                </div>

                @if($product->short_description_ar || $product->short_description_en)
                <div class="prose prose-sm text-gray-600">
                    <p>{{ $product->short_description_ar ?? $product->short_description_en }}</p>
                </div>
                @endif

                @if($product->product_type === 'variable' && $product->variants->count() > 0)
                <div class="bg-gray-50 rounded-2xl p-5 space-y-4">
                    <div class="flex flex-wrap gap-3">
                        @foreach($product->variants as $variant)
                        <button type="button"
                                @click="selectVariant({{ $variant->id }})"
                                :class="matchedVariant && matchedVariant.id === {{ $variant->id }}
                                    ? 'border-indigo-600 bg-indigo-50 text-indigo-700 ring-2 ring-indigo-200'
                                    : 'border-gray-300 text-gray-700 hover:border-gray-400'"
                                class="px-4 py-2 rounded-lg border-2 text-sm font-medium transition-all">
                            {{ $variant->label ?: ('متغير ' . ($loop->index + 1)) }}
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($product->is_available ?? true)
                <form method="post" action="{{ route('cart.store', $product) }}" class="space-y-4">
                    @csrf
                    @if($product->product_type === 'variable' && $product->variants->count() > 0)
                    <input type="hidden" name="variant_id" :value="matchedVariant ? matchedVariant.id : ''">
                    @endif
                    
                    <div class="flex items-center gap-2 sm:gap-3" x-data="{ qty: 1 }">
                        <div class="flex items-center border-2 border-gray-200 rounded-xl overflow-hidden">
                            <button type="button" @click="qty = Math.max(1, qty - 1)"
                                    class="w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition">
                                <i class="fas fa-minus text-sm"></i>
                            </button>
                            <input type="number" name="quantity" x-model="qty" min="1" max="100"
                                   class="w-12 sm:w-16 h-10 sm:h-12 text-center border-0 focus:ring-0 font-semibold text-base sm:text-lg">
                            <button type="button" @click="qty = Math.min(100, qty + 1)"
                                    class="w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition">
                                <i class="fas fa-plus text-sm"></i>
                            </button>
                        </div>

                        <button type="submit"
                            class="w-32 sm:w-48 h-10 sm:h-12 bg-primary-blue text-white font-bold rounded-xl flex items-center justify-center gap-1 sm:gap-2 hover:bg-primary-blue/90 transition transform hover:scale-[1.02] shadow-lg text-sm sm:text-base">
                            <i class="fas fa-shopping-cart text-sm sm:text-base"></i>
                            <span class="hidden sm:inline">أضف للسلة</span>
                            <span class="sm:hidden">السلة</span>
                        </button>

                        <button type="button"
                                class="h-10 sm:h-12 px-3 sm:px-4 border-2 border-gray-200 text-gray-700 font-semibold rounded-xl flex items-center justify-center gap-1 sm:gap-2 hover:border-pink-500 hover:text-pink-500 transition text-sm sm:text-base">
                            <i class="far fa-heart text-sm sm:text-base"></i>
                            <span class="hidden sm:inline">المفضلة</span>
                        </button>

                        <button type="button" @click="shareProduct()"
                                class="w-10 h-10 sm:w-12 sm:h-12 border-2 border-gray-200 text-gray-700 rounded-xl flex items-center justify-center hover:border-primary-blue hover:text-primary-blue transition">
                            <i class="fas fa-share-alt text-sm sm:text-base"></i>
                        </button>
                    </div>
                </form>
                @else
                <div class="bg-red-50 rounded-xl p-4 text-center">
                    <p class="text-red-700 font-medium mb-3">هذا المنتج غير متوفر حالياً</p>
                    <button class="bg-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-700 transition">
                        <i class="fas fa-bell ml-2"></i>
                        أعلمني عند التوفر
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@if($related->isNotEmpty())
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-primary-blue">
                <i class="fas fa-lightbulb text-primary-yellow ml-2"></i>
                قد يعجبك أيضاً
            </h2>
            <a href="{{ route('products.index') }}" class="text-primary-blue hover:underline font-medium">
                عرض المزيد
                <i class="fas fa-arrow-left mr-1"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            @foreach($related as $item)
            <a href="{{ route('products.show', $item) }}"
               class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all transform hover:-translate-y-1">
                <div class="relative aspect-square bg-gray-100 overflow-hidden">
                    @if($item->images && $item->images->first())
                    <img src="{{ asset('storage/' . $item->images->first()->path) }}"
                         alt="{{ $item->name_ar }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @elseif($item->primary_image)
                    <img src="{{ $item->primary_image->url }}"
                         alt="{{ $item->name_ar }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="fas fa-image text-gray-300 text-4xl"></i>
                    </div>
                    @endif

                    @if($item->sale_price && $item->sale_price < $item->price)
                    <span class="absolute top-2 right-2 px-2 py-1 rounded-full bg-primary-red text-white text-xs font-bold">
                        -{{ $item->price > 0 ? number_format((($item->price - $item->sale_price) / $item->price) * 100) : 0 }}%
                    </span>
                    @endif
                </div>
                <div class="p-4">
                    <p class="text-xs text-gray-500 mb-1">{{ $item->category?->name_ar }}</p>
                    <h3 class="font-semibold text-gray-900 line-clamp-2 mb-2 group-hover:text-primary-blue transition-colors">
                        {{ $item->name_ar ?? $item->name_en }}
                    </h3>
                    @php $itemPrice = $item->final_price; @endphp
                    <div class="flex items-baseline gap-2">
                        <span class="font-bold text-primary-red">{{ number_format($itemPrice, 2) }} ج.م</span>
                        @if($item->sale_price && $item->sale_price < $item->price)
                        <span class="text-xs text-gray-400 line-through">{{ number_format($item->price, 2) }}</span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold mb-2">تقييمات العملاء</h2>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2">
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= floor($product->average_rating) ? 'text-yellow-400' : 'text-white/30' }}"></i>
                                    @endfor
                                </div>
                                <span class="text-2xl font-bold">{{ number_format($product->average_rating, 1) }}</span>
                            </div>
                            <span class="text-white/80">({{ $product->reviews_count }} تقييم)</span>
                        </div>
                    </div>
                    @if(auth()->check())
                    <button onclick="showReviewForm()" class="bg-white text-indigo-600 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition">
                        <i class="fas fa-star ml-2"></i>إضافة تقييم
                    </button>
                    @else
                    <button onclick="showLoginAlert()" class="bg-white text-indigo-600 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition">
                        <i class="fas fa-sign-in-alt ml-2"></i>سجل دخول لإضافة تقييم
                    </button>
                    @endif
                </div>
            </div>

            <div class="p-6">
                @if($product->approvedReviews()->count() > 0)
                <div class="space-y-4">
                    @foreach($product->approvedReviews()->with('user')->latest()->get() as $review)
                    <div class="border-b pb-4 last:border-b-0">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-gray-500"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $review->user->name }}</h4>
                                        <div class="flex items-center gap-2 mt-1">
                                            <div class="flex">
                                                @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                                                @endfor
                                            </div>
                                            <span class="text-xs text-gray-500">{{ $review->created_at->format('Y-m-d') }}</span>
                                        </div>
                                    </div>
                                </div>
                                @if($review->comment)
                                <p class="text-gray-700">{{ $review->comment }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-star text-4xl mb-3"></i>
                    <p>لا توجد تقييمات لهذا المنتج بعد</p>
                    <p class="text-sm mt-1">كن أول من يقيم هذا المنتج!</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@if(auth()->check())
<div id="reviewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-900">إضافة تقييم</h3>
            <button onclick="hideReviewForm()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form action="{{ route('products.review.store', $product) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">التقييم *</label>
                <div class="flex gap-2" id="ratingStars">
                    @for($i = 1; $i <= 5; $i++)
                    <button type="button" onclick="setRating({{ $i }})" class="text-3xl text-gray-300 hover:text-yellow-400 transition">
                        <i class="fas fa-star" data-rating="{{ $i }}"></i>
                    </button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="ratingInput" value="5" required>
            </div>

            <div>
                <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">التعليق (اختياري)</label>
                <textarea name="comment" id="comment" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="شاركنا رأيك في هذا المنتج..."></textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition">
                    إرسال التقييم
                </button>
                <button type="button" onclick="hideReviewForm()" class="flex-1 bg-gray-200 text-gray-700 py-2 rounded-lg hover:bg-gray-300 transition">
                    إلغاء
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showReviewForm() {
    document.getElementById('reviewModal').classList.remove('hidden');
    document.getElementById('reviewModal').classList.add('flex');
}

function hideReviewForm() {
    document.getElementById('reviewModal').classList.add('hidden');
    document.getElementById('reviewModal').classList.remove('flex');
}

function setRating(rating) {
    document.getElementById('ratingInput').value = rating;
    const stars = document.querySelectorAll('#ratingStars i');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('text-gray-300');
            star.classList.add('text-yellow-400');
        } else {
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-300');
        }
    });
}

setRating(5);
</script>
@endif

<script>
function shareProduct() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $product->name_ar ?? $product->name_en }}',
            url: window.location.href
        });
    } else {
        navigator.clipboard.writeText(window.location.href);
        alert('تم نسخ رابط المنتج');
    }
}

function showLoginAlert() {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'fixed top-20 left-1/2 transform -translate-x-1/2 bg-indigo-600 text-white px-6 py-4 rounded-lg shadow-2xl z-50 flex items-center gap-3 animate-slide-down';
    alertDiv.innerHTML = '<i class="fas fa-info-circle text-xl"></i><span class="font-semibold">يجب عليك تسجيل الدخول أولاً</span>';
    
    const style = document.createElement('style');
    style.textContent = '@keyframes slide-down { from { opacity: 0; transform: translate(-50%, -20px); } to { opacity: 1; transform: translate(-50%, 0); } }';
    document.head.appendChild(style);
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.style.transition = 'opacity 0.5s ease-out';
        alertDiv.style.opacity = '0';
        setTimeout(() => {
            document.body.removeChild(alertDiv);
            document.head.removeChild(style);
        }, 500);
    }, 6000);
}

@php
    $variantsJson = collect();
    if ($product->product_type === 'variable' && $product->relationLoaded('variants')) {
        $variantsJson = $product->variants->map(function ($v) {
            return [
                'id' => $v->id,
                'label' => $v->label,
                'sku' => $v->sku,
                'price' => $v->final_price,
                'image' => $v->image_url,
            ];
        })->values();
    }
@endphp

function productShow() {
    return {
        activeImage: 0,
        basePrice: @json((float) $product->final_price),
        variants: @json($variantsJson),
        selectedVariantId: null,
        matchedVariant: null,
        variantImageUrl: null,

        formatPrice(price) {
            const p = Number(price ?? 0);
            if (Number.isNaN(p)) return '';
            return p.toFixed(2) + ' ج.م';
        },

        selectVariant(variantId) {
            this.selectedVariantId = this.selectedVariantId === variantId ? null : variantId;
            this.matchedVariant = this.selectedVariantId ? this.variants.find(v => v.id === this.selectedVariantId) : null;
            this.variantImageUrl = this.matchedVariant?.image || null;
        }
    }
}
</script>
@endsection
