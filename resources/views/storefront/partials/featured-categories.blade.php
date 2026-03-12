<!-- Featured Categories Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <span class="inline-block bg-primary-yellow/20 text-black px-4 py-2 rounded-full text-sm font-semibold mb-4">
                <i class="fas fa-th-large ml-1"></i>
                الأقسام
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-primary-blue mb-4">تصفح حسب الفئة</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">اختر من بين تشكيلتنا الواسعة من الأقسام</p>
        </div>
        
        @php 
        // Fallback palette by slug (used when no custom colors are set)
        $categoryColors = [
            'school-supplies' => ['color' => 'from-blue-500 to-blue-600', 'bg' => 'bg-blue-50', 'text' => 'text-blue-600'],
            'leather-products' => ['color' => 'from-amber-500 to-amber-600', 'bg' => 'bg-amber-50', 'text' => 'text-amber-600'],
            'study-notes' => ['color' => 'from-green-500 to-green-600', 'bg' => 'bg-green-50', 'text' => 'text-green-600'],
            'montessori-toys' => ['color' => 'from-purple-500 to-purple-600', 'bg' => 'bg-purple-50', 'text' => 'text-purple-600'],
            'kids-toys' => ['color' => 'from-pink-500 to-pink-600', 'bg' => 'bg-pink-50', 'text' => 'text-pink-600'],
        ];
        @endphp

        <div class="md:hidden relative">
            <div class="pointer-events-none absolute left-0 top-0 bottom-0 w-6 bg-gradient-to-r from-gray-50 to-transparent z-10"></div>
            <div class="pointer-events-none absolute right-0 top-0 bottom-0 w-6 bg-gradient-to-l from-gray-50 to-transparent z-10"></div>
            <div class="flex gap-4 overflow-x-auto snap-x snap-mandatory -mx-4 px-4 pb-2" style="scrollbar-width: none;">
                @foreach($categories as $category)
                @php
                    $hasCustom = !empty($category->color_start);
                    $start = $category->color_start;
                    $end = $category->color_end ?: $category->color_start;
                    $colors = $categoryColors[$category->slug] ?? ['color' => 'from-gray-500 to-gray-600', 'bg' => 'bg-gray-50', 'text' => 'text-gray-600'];
                @endphp
                <a href="{{ route('products.category', $category->slug) }}" 
                   class="group flex flex-col items-center justify-center w-28 h-32 min-w-[7rem] snap-start rounded-2xl bg-white border border-gray-200 shadow-sm hover:shadow-md hover:border-gray-300 active:scale-95 transition">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center ring-1 ring-white/70 {{ $hasCustom ? '' : $colors['bg'] }}"
                         @if($hasCustom) data-gradient-start="{{ $start }}" data-gradient-end="{{ $end }}" @endif>
                        <i class="fas {{ $category->icon }} text-2xl {{ $hasCustom ? 'text-white' : $colors['text'] }}"></i>
                    </div>
                    <span class="mt-2 text-[12px] text-gray-900 leading-tight text-center line-clamp-2">
                        {{ $category->name_ar ?: $category->name_en }}
                    </span>
                </a>
                @endforeach
            </div>
        </div>

        <div class="hidden md:block">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 md:gap-6 justify-items-center">
                @foreach($categories as $category)
                @php
                    $hasCustom = !empty($category->color_start);
                    $start = $category->color_start;
                    $end = $category->color_end ?: $category->color_start;
                    $colors = $categoryColors[$category->slug] ?? ['color' => 'from-gray-500 to-gray-600', 'bg' => 'bg-gray-50', 'text' => 'text-gray-600'];
                @endphp
                    <a href="{{ route('products.category', $category->slug) }}" 
                   class="group relative overflow-hidden rounded-2xl bg-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 flex flex-col items-center justify-center">
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 {{ $hasCustom ? '' : 'bg-gradient-to-br '.$colors['color'] }}"
                         @if($hasCustom) data-gradient-start="{{ $start }}" data-gradient-end="{{ $end }}" @endif></div>
                    
                    <div class="relative p-6 text-center flex flex-col items-center justify-center h-full">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 transition-colors {{ $hasCustom ? '' : $colors['bg'].' group-hover:bg-white/20' }}"
                             @if($hasCustom) data-gradient-start="{{ $start }}" data-gradient-end="{{ $end }}" @endif>
                            <i class="fas {{ $category->icon }} text-2xl {{ $hasCustom ? 'text-white' : $colors['text'].' group-hover:text-white' }} transition-colors"></i>
                        </div>
                        
                        <h3 class="font-bold text-gray-900 group-hover:text-white transition-colors">{{ $category->name_ar ?: $category->name_en }}</h3>
                        
                        <div class="mt-3 opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="inline-flex items-center text-white text-sm">
                                تصفح
                                <i class="fas fa-arrow-left mr-1 text-xs"></i>
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        
        <!-- View All Categories -->
        <div class="text-center mt-10">
            <a href="{{ route('products.index') }}" 
               class="inline-flex items-center text-primary-blue hover:text-primary-blue/80 font-semibold transition group">
                عرض جميع الأقسام
                <i class="fas fa-arrow-left mr-2 transform group-hover:-translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
</section>

<script>
(() => {
    if (window.__featuredCategoriesGradientsApplied) return;
    window.__featuredCategoriesGradientsApplied = true;

    const nodes = document.querySelectorAll('[data-gradient-start][data-gradient-end]');
    nodes.forEach((el) => {
        const start = el.getAttribute('data-gradient-start');
        const end = el.getAttribute('data-gradient-end');
        if (!start || !end) return;
        el.style.backgroundImage = `linear-gradient(135deg, ${start}, ${end})`;
        el.style.backgroundColor = 'transparent';
    });
})();
</script>
