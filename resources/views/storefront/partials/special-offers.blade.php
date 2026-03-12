<!-- Special Offers Banner Section -->
<section class="py-8 bg-white">
    <div class="container mx-auto px-4">
        @if(isset($homeOffers) && $homeOffers->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($homeOffers as $offer)
                    @php
                        $from = $offer->banner_color_from ?: '#003399';
                        $to = $offer->banner_color_to ?: '#003D7A';
                        $useCustomGradient = $offer->banner_color_from || $offer->banner_color_to;
                    @endphp
                    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-primary-blue to-blue-800 p-6 md:p-8 group hover:shadow-2xl transition-all duration-300"
                         style="{{ $useCustomGradient ? 'background-image: linear-gradient(135deg, ' . $from . ', ' . $to . ');' : '' }}">
                        @if($offer->banner_image)
                            <img src="{{ Storage::url($offer->banner_image) }}"
                                 alt="{{ $offer->name }}"
                                 class="absolute inset-0 w-full h-full object-cover opacity-25">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-br from-black/20 via-black/10 to-black/30"></div>
                        <div class="absolute top-0 left-0 w-32 h-32 bg-white/10 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
                        <div class="absolute bottom-0 right-0 w-48 h-48 bg-white/5 rounded-full translate-x-1/4 translate-y-1/4"></div>

                        <div class="relative z-10 flex items-center gap-4">
                            <div class="w-16 h-16 md:w-20 md:h-20 bg-white/20 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                                <i class="fas fa-tags text-2xl md:text-3xl text-white"></i>
                            </div>
                            <div class="text-white">
                                <span class="text-xs md:text-sm font-medium text-primary-yellow">عرض خاص</span>
                                <h3 class="text-lg md:text-xl font-bold mb-1">{{ $offer->name }}</h3>
                                <p class="text-white/80 text-sm">
                                    {{ $offer->description ?: 'لفترة محدودة' }}
                                </p>
                            </div>
                        </div>

                        <a href="{{ route('offers.show', $offer) }}" class="absolute inset-0 z-20"></a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
