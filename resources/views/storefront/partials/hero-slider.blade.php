<!-- Hero Slider with improved UX -->
@once
    @push('styles')
        <style>
            .hero-slider-shell {
                height: 300px;
            }

            @media (min-width: 640px) {
                .hero-slider-shell {
                    height: 380px;
                }
            }

            @media (min-width: 768px) {
                .hero-slider-shell {
                    height: 550px;
                }
            }
        </style>
    @endpush
@endonce

@if($sliders && $sliders->count() > 0)
<section class="hero-slider-shell relative overflow-hidden" 
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
            <div :style="currentSlide === {{ $index }} ? 'opacity:1; z-index:10;' : 'opacity:0; z-index:0; pointer-events:none;'"
                style="{{ $index === 0 ? 'opacity:1; z-index:10;' : 'opacity:0; z-index:0; pointer-events:none;' }}"
                class="absolute inset-0 transition-opacity duration-500"
         x-data="{ titleColor: @json($slider->title_color_ar), subtitleColor: @json($slider->subtitle_color_ar) }">

        <img src="{{ asset('storage/' . $slider->image) }}"
             alt="{{ $slider->title_ar ?: $slider->title_en ?: 'Slider' }}"
             class="absolute inset-0 w-full h-full object-cover">

        <div class="absolute inset-0 bg-black/35"></div>

        <div class="container mx-auto px-4 relative z-10 h-full flex items-center justify-center">
            <div class="text-center text-white max-w-3xl mx-auto bg-primary-blue/55 md:bg-transparent backdrop-blur-[1px] md:backdrop-blur-0 rounded-2xl md:rounded-none px-4 py-4 sm:px-6 sm:py-6 md:p-0 shadow-lg md:shadow-none">
                @if($slider->title_ar || $slider->title_en)
                    <h1 class="text-xl sm:text-3xl md:text-6xl font-black mb-6 sm:mb-8 md:mb-6 leading-tight"
                        :style="titleColor ? 'color: ' + titleColor : ''">
                        {{ $slider->title_ar ?: $slider->title_en }}
                    </h1>
                @endif
                
                @if($slider->subtitle_ar || $slider->subtitle_en)
                    <p class="text-sm sm:text-lg md:text-2xl mb-4 sm:mb-6 md:mb-8 leading-relaxed opacity-95"
                       :style="subtitleColor ? 'color: ' + subtitleColor : ''">
                        {{ $slider->subtitle_ar ?: $slider->subtitle_en }}
                    </p>
                @endif
                
                @if($slider->button_url && ($slider->button_text_ar || $slider->button_text_en))
                    <a href="{{ $slider->button_url }}" 
                       @if($slider->open_in_new_tab) target="_blank" @endif
                       class="inline-block bg-primary-red text-white px-5 py-2.5 sm:px-8 sm:py-4 rounded-full font-bold text-sm sm:text-lg hover:bg-red-600 transition duration-300 transform hover:scale-105 shadow-lg">
                        {{ $slider->button_text_ar ?: $slider->button_text_en }}
                    </a>
                @endif
            </div>
        </div>
    </div>
    @endforeach

    <!-- Navigation Arrows -->
    <button @click="prev()" 
            class="hidden md:flex absolute right-4 top-1/2 -translate-y-1/2 w-11 h-11 bg-white/80 hover:bg-white rounded-full items-center justify-center shadow-lg transition transform hover:scale-110 z-20">
        <i class="fas fa-chevron-right text-gray-600"></i>
    </button>
    <button @click="next()" 
            class="hidden md:flex absolute left-4 top-1/2 -translate-y-1/2 w-11 h-11 bg-white/80 hover:bg-white rounded-full items-center justify-center shadow-lg transition transform hover:scale-110 z-20">
        <i class="fas fa-chevron-left text-gray-600"></i>
    </button>
    
    <!-- Slide Indicators -->
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex justify-center items-center gap-2 z-20">
        @foreach($sliders as $index => $slider)
        <button @click="goTo({{ $index }})" 
                :class="currentSlide === {{ $index }} ? 'bg-white' : 'bg-white/50'"
                class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full transition hover:bg-white">
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
