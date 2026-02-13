@extends('layouts.storefront')

@section('title', 'الصفحة غير موجودة - مكتبة الصديق')

@section('content')
<section class="min-h-[70vh] flex items-center justify-center py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto text-center">
            <!-- Animated 404 -->
            <div class="relative mb-8">
                <div class="text-[150px] md:text-[200px] font-black text-gray-100 leading-none select-none">
                    404
                </div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-32 h-32 md:w-40 md:h-40 bg-primary-yellow rounded-full flex items-center justify-center shadow-2xl animate-bounce">
                        <i class="fas fa-book-open text-5xl md:text-6xl text-primary-blue"></i>
                    </div>
                </div>
            </div>

            <h1 class="text-3xl md:text-4xl font-bold text-primary-blue mb-4">
                عذراً! الصفحة غير موجودة
            </h1>
            
            <p class="text-gray-600 text-lg mb-8 max-w-md mx-auto">
                يبدو أن الصفحة التي تبحث عنها قد تم نقلها أو حذفها أو أنها غير موجودة.
            </p>

            <!-- Search -->
            <form action="{{ route('products.search') }}" method="GET" class="max-w-lg mx-auto mb-8">
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <input type="text" 
                               name="q" 
                               placeholder="ابحث عن المنتج هنا..."
                               class="w-full border-2 border-gray-200 rounded-xl pl-12 pr-4 py-4 focus:ring-2 focus:ring-primary-yellow focus:border-primary-yellow transition">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <button type="submit" 
                            class="bg-primary-blue hover:bg-primary-blue/90 text-white px-6 rounded-xl font-semibold transition">
                        بحث
                    </button>
                </div>
            </form>

            <!-- Action Buttons -->
            <div class="flex flex-wrap justify-center gap-4 mb-12">
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center bg-primary-yellow text-primary-blue px-8 py-4 rounded-xl font-bold hover:bg-yellow-400 transition transform hover:scale-105 shadow-lg">
                    <i class="fas fa-home ml-2"></i>
                    الصفحة الرئيسية
                </a>
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center border-2 border-primary-blue text-primary-blue px-8 py-4 rounded-xl font-bold hover:bg-primary-blue hover:text-white transition">
                    <i class="fas fa-store ml-2"></i>
                    تصفح المنتجات
                </a>
            </div>

            <!-- Quick Links -->
            <div class="bg-gray-50 rounded-2xl p-8">
                <h3 class="font-bold text-gray-900 mb-4">روابط سريعة قد تفيدك:</h3>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('products.index', ['type' => 'stationery']) }}" 
                       class="px-4 py-2 bg-white rounded-lg text-gray-600 hover:text-primary-blue hover:shadow transition">
                        <i class="fas fa-pencil-alt ml-1"></i>
                        أدوات مكتبية
                    </a>
                    <a href="{{ route('offers') }}" 
                       class="px-4 py-2 bg-white rounded-lg text-gray-600 hover:text-primary-blue hover:shadow transition">
                        <i class="fas fa-tags ml-1"></i>
                        العروض
                    </a>
                    <a href="{{ route('contact') }}" 
                       class="px-4 py-2 bg-white rounded-lg text-gray-600 hover:text-primary-blue hover:shadow transition">
                        <i class="fas fa-envelope ml-1"></i>
                        اتصل بنا
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
