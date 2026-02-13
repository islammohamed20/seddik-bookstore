@extends('layouts.storefront')

@section('title', 'مكتبة الصديق | Al-Seddik Library')

@section('content')
    <section class="border-b bg-gradient-to-b from-amber-50 to-slate-50">
        <div class="max-w-6xl mx-auto px-4 py-10 grid gap-8 md:grid-cols-2 items-center">
            <div class="space-y-4">
                <p class="text-xs uppercase tracking-wide text-amber-700">
                    أهلاً بك في
                </p>
                <h1 class="text-3xl sm:text-4xl font-bold leading-snug">
                    <span class="block mb-1">مكتبة الصديق</span>
                    <span class="block text-slate-700 text-lg">Al-Seddik Library</span>
                </h1>
                <p class="text-sm text-slate-600 leading-relaxed">
                    مكتبة متخصصة في الكتب، الأدوات المكتبية، الملخصات الدراسية، ألعاب المونتسوري،
                    ألعاب الأطفال، ومنتجات بينجو الرسمية وكل ما يحتاجه الطالب والعائلة.
                </p>
                <div class="flex flex-wrap gap-3 text-sm">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 rounded-full bg-amber-500 text-white font-medium">
                        تسوّق الآن
                    </a>
                    <a href="#" class="inline-flex items-center px-4 py-2 rounded-full border border-slate-200 text-slate-700 hover:border-amber-400 hover:text-amber-700 transition-colors">
                        تعرّف على خدماتنا
                    </a>
                </div>
            </div>
            <div class="space-y-4">
                @if($sliders->isNotEmpty())
                    <div class="relative overflow-hidden rounded-2xl bg-white shadow-sm">
                        <img src="{{ $sliders->first()->image }}" alt="{{ $sliders->first()->title_en }}" class="w-full h-56 object-cover">
                    </div>
                @else
                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-xl bg-white shadow-sm p-4">
                            <p class="text-xs font-medium text-slate-500 mb-1">{{ __('School supplies') }}</p>
                            <p class="text-sm text-slate-700">{{ __('Everything students need for a new year.') }}</p>
                        </div>
                        <div class="rounded-xl bg-white shadow-sm p-4">
                            <p class="text-xs font-medium text-slate-500 mb-1">{{ __('Leather products') }}</p>
                            <p class="text-sm text-slate-700">{{ __('Bags, wallets, and premium accessories.') }}</p>
                        </div>
                        <div class="rounded-xl bg-white shadow-sm p-4">
                            <p class="text-xs font-medium text-slate-500 mb-1">{{ __('Study summaries') }}</p>
                            <p class="text-sm text-slate-700">{{ __('Hand-picked notes to help students succeed.') }}</p>
                        </div>
                        <div class="rounded-xl bg-white shadow-sm p-4">
                            <p class="text-xs font-medium text-slate-500 mb-1">{{ __('Montessori and kids toys') }}</p>
                            <p class="text-sm text-slate-700">{{ __('Safe, educational and fun for children.') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section id="categories" class="max-w-6xl mx-auto px-4 py-10">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">
                {{ __('Shop by category') }}
            </h2>
            <p class="hidden sm:block text-xs text-slate-500">
                اختر القسم المناسب: كتب، قرطاسية، ألعاب، أو ملخصات دراسية.
            </p>
        </div>
        <div class="grid gap-4 grid-cols-2 sm:grid-cols-3 lg:grid-cols-6">
            @forelse($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="group rounded-xl bg-white shadow-sm px-3 py-4 flex flex-col items-center text-center hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-full bg-amber-50 flex items-center justify-center mb-2">
                        <span class="text-sm font-semibold text-amber-700">
                            {{ mb_substr($category->name_ar ?? $category->name_en, 0, 2) }}
                        </span>
                    </div>
                    <div class="text-xs font-medium text-slate-800">
                        {{ $category->name_ar ?? $category->name_en }}
                    </div>
                </a>
            @empty
                <p class="text-sm text-slate-500">
                    {{ __('Categories will appear here soon.') }}
                </p>
            @endforelse
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-4 pb-10">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">
                {{ __('Featured products') }}
            </h2>
            <a href="{{ route('products.index') }}" class="text-xs text-amber-700 hover:text-amber-800">
                {{ __('View all') }}
            </a>
        </div>
        <div class="grid gap-4 grid-cols-2 sm:grid-cols-3 lg:grid-cols-4">
            @forelse($featuredProducts as $product)
                <a href="{{ route('products.show', $product) }}" class="group rounded-xl bg-white shadow-sm overflow-hidden flex flex-col hover:shadow-md transition-shadow">
                    <div class="aspect-[4/3] bg-slate-100"></div>
                    <div class="px-3 py-3 space-y-1">
                        <p class="text-xs text-slate-500">
                            {{ $product->category?->name_ar ?? $product->category?->name_en }}
                        </p>
                        <p class="text-sm font-medium text-slate-800 line-clamp-2">
                            {{ $product->name_ar ?? $product->name_en }}
                        </p>
                        <p class="text-sm font-semibold text-amber-700">
                            @php $price = $product->sale_price ?? $product->price; @endphp
                            <span>{{ number_format($price, 2) }} {{ __('EGP') }}</span>
                        </p>
                    </div>
                </a>
            @empty
                <p class="text-sm text-slate-500">
                    {{ __('Products will appear here as soon as they are added.') }}
                </p>
            @endforelse
        </div>
    </section>
@endsection
