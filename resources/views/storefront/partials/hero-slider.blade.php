<!-- Hero Slider with improved UX -->
@if($sliders && $sliders->count() > 0)
<section class="relative h-[450px] md:h-[550px] overflow-hidden" 
         x-data="{ 
             currentSlide: 0, 
             totalSlides: {{ $sliders->count() }},
             autoplay: true,
             init() {
                 this.startAutoplay();
             },
             startAutoplay() {
                 setInterval(() => {
                     if (this.autoplay) {
                         this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                     }
                 }, 5000);
             },
             goTo(index) {
                 this.currentSlide = index;
             },
             next() {
                 this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
             },
             prev() {
                 this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
             }
         }">
    
    @foreach($sliders as $index => $slider)
    <!-- Slide {{ $index + 1 }} -->
    <div x-show="currentSlide === {{ $index }}" 
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 transform translate-x-full"
         x-transition:enter-end="opacity-100 transform translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="absolute inset-0 bg-cover bg-center"
         style="background-image: url('{{ asset('storage/' . $slider->image) }}');">
        
        <!-- Overlay for better text readability -->
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
        
        <div class="container mx-auto px-4 relative z-10 h-full flex items-center">
            <div class="text-center md:text-right text-white max-w-2xl">
                @if($slider->title_ar || $slider->title_en)
                    <h1 class="text-4xl md:text-6xl font-black mb-4 leading-tight">
                        {{ $slider->title_ar ?: $slider->title_en }}
                    </h1>
                @endif
                
                @if($slider->subtitle_ar || $slider->subtitle_en)
                    <p class="text-xl md:text-2xl mb-8 leading-relaxed opacity-90">
                        {{ $slider->subtitle_ar ?: $slider->subtitle_en }}
                    </p>
                @endif
                
                @if($slider->button_url && ($slider->button_text_ar || $slider->button_text_en))
                    <a href="{{ $slider->button_url }}" 
                       @if($slider->open_in_new_tab) target="_blank" @endif
                       class="inline-block bg-primary-red text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-red-600 transition duration-300 transform hover:scale-105 shadow-lg">
                        {{ $slider->button_text_ar ?: $slider->button_text_en }}
                    </a>
                @endif
            </div>
        </div>
    </div>
    @endforeach

    <!-- Navigation Arrows -->
    <button @click="prev()" 
            class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/80 hover:bg-white rounded-full flex items-center justify-center shadow-lg transition transform hover:scale-110 z-20">
        <i class="fas fa-chevron-right text-gray-600"></i>
    </button>
    <button @click="next()" 
            class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/80 hover:bg-white rounded-full flex items-center justify-center shadow-lg transition transform hover:scale-110 z-20">
        <i class="fas fa-chevron-left text-gray-600"></i>
    </button>
    
    <!-- Slide Indicators -->
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-20">
        @foreach($sliders as $index => $slider)
        <button @click="goTo({{ $index }})" 
                :class="currentSlide === {{ $index }} ? 'bg-white' : 'bg-white/50'"
                class="w-3 h-3 rounded-full transition hover:bg-white">
        </button>
        @endforeach
    </div>
</section>
@else
<!-- Default welcome slider when no sliders exist -->
<section class="relative h-[450px] md:h-[550px] overflow-hidden bg-gradient-to-br from-primary-yellow via-yellow-400 to-primary-yellow flex items-center">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 left-20 w-64 h-64 bg-primary-blue rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-20 w-96 h-96 bg-white rounded-full blur-3xl"></div>
    </div>
    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center">
            <span class="inline-block bg-primary-blue text-white px-4 py-1 rounded-full text-sm font-semibold mb-4 animate-pulse">
                مرحباً بك في
            </span>
            <h1 class="text-4xl md:text-6xl font-black text-primary-blue mb-4 leading-tight">
                مكتبة <span class="text-primary-red">الصديق</span>
            </h1>
            <p class="text-xl md:text-2xl text-primary-blue/80 mb-8 leading-relaxed">
                وجهتك الموثوقة لجميع احتياجاتك المكتبية والتعليمية
            </p>
            <a href="{{ route('products.index') }}" 
               class="inline-block bg-primary-red text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-red-600 transition duration-300 transform hover:scale-105 shadow-lg">
                تسوق الآن
            </a>
        </div>
    </div>
</section>
@endif
        <i class="fas fa-chevron-right text-primary-blue"></i>
    </button>
    <button @click="next()" 
            class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/80 hover:bg-white rounded-full flex items-center justify-center shadow-lg transition transform hover:scale-110 z-20">
        <i class="fas fa-chevron-left text-primary-blue"></i>
    </button>

    <!-- Dots Navigation -->
    <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex gap-3 z-20">
        <template x-for="i in totalSlides" :key="i">
            <button @click="goTo(i - 1)" 
                    :class="currentSlide === (i - 1) ? 'bg-white w-10' : 'bg-white/50 w-3 hover:bg-white/70'" 
                    class="h-3 rounded-full transition-all duration-300">
            </button>
        </template>
    </div>
</section>
