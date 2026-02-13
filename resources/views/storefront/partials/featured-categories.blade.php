<!-- Featured Categories Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <span class="inline-block bg-primary-yellow/20 text-primary-yellow px-4 py-2 rounded-full text-sm font-semibold mb-4">
                <i class="fas fa-th-large ml-1"></i>
                الأقسام
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-primary-blue mb-4">تصفح حسب الفئة</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">اختر من بين تشكيلتنا الواسعة من الأقسام</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 md:gap-6">
            @php 
            $categoryColors = [
                'school-supplies' => ['color' => 'from-blue-500 to-blue-600', 'bg' => 'bg-blue-50', 'text' => 'text-blue-600'],
                'leather-products' => ['color' => 'from-amber-500 to-amber-600', 'bg' => 'bg-amber-50', 'text' => 'text-amber-600'],
                'study-notes' => ['color' => 'from-green-500 to-green-600', 'bg' => 'bg-green-50', 'text' => 'text-green-600'],
                'montessori-toys' => ['color' => 'from-purple-500 to-purple-600', 'bg' => 'bg-purple-50', 'text' => 'text-purple-600'],
                'kids-toys' => ['color' => 'from-pink-500 to-pink-600', 'bg' => 'bg-pink-50', 'text' => 'text-pink-600'],
            ];
            @endphp
            
            @foreach($categories as $category)
            @php
                $colors = $categoryColors[$category->slug] ?? ['color' => 'from-gray-500 to-gray-600', 'bg' => 'bg-gray-50', 'text' => 'text-gray-600'];
            @endphp
                <a href="{{ route('products.category', $category->slug) }}" 
               class="group relative overflow-hidden rounded-2xl bg-white shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                <!-- Gradient Overlay on Hover -->
                <div class="absolute inset-0 bg-gradient-to-br {{ $colors['color'] }} opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                
                <!-- Content -->
                <div class="relative p-6 text-center">
                    <!-- Icon -->
                    <div class="w-16 h-16 {{ $colors['bg'] }} rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-white/20 transition-colors">
                        <i class="fas {{ $category->icon }} text-2xl {{ $colors['text'] }} group-hover:text-white transition-colors"></i>
                    </div>
                    
                    <!-- Title -->
                    <h3 class="font-bold text-gray-900 group-hover:text-white transition-colors">{{ $category->name_ar ?: $category->name_en }}</h3>
                    
                    <!-- Arrow -->
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
