@extends('layouts.storefront')

@section('title')
    @if(isset($currentCategory))
        {{ $currentCategory->name_ar }} - 
    @elseif(isset($currentBrand))
        {{ $currentBrand->name_ar }} - 
    @elseif(isset($searchQuery) && $searchQuery)
        نتائج البحث: {{ $searchQuery }} - 
    @endif
    {{ __('المنتجات') }} - {{ __('مكتبة الصديق') }}
@endsection

@section('content')
<!-- Breadcrumb -->
<div class="bg-gray-100 py-4">
    <div class="container mx-auto px-4">
        <nav class="flex items-center gap-2 text-sm">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-primary-blue transition">
                <i class="fas fa-home"></i>
            </a>
            <i class="fas fa-chevron-left text-gray-400 text-xs"></i>
            @if(isset($currentCategory))
                <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-primary-blue transition">المنتجات</a>
                <i class="fas fa-chevron-left text-gray-400 text-xs"></i>
                <span class="text-primary-blue font-semibold">{{ $currentCategory->name_ar }}</span>
            @elseif(isset($currentBrand))
                <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-primary-blue transition">المنتجات</a>
                <i class="fas fa-chevron-left text-gray-400 text-xs"></i>
                <span class="text-primary-blue font-semibold">{{ $currentBrand->name_ar }}</span>
            @elseif(isset($searchQuery) && $searchQuery)
                <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-primary-blue transition">المنتجات</a>
                <i class="fas fa-chevron-left text-gray-400 text-xs"></i>
                <span class="text-primary-blue font-semibold">نتائج البحث</span>
            @else
                <span class="text-primary-blue font-semibold">المنتجات</span>
            @endif
        </nav>
    </div>
</div>

<!-- Page Header -->
<section class="relative overflow-hidden py-6 sm:py-10">
    @if(isset($currentCategory) && ($currentCategory->banner_desktop || $currentCategory->banner_mobile))
        <!-- Banner Background -->
        <div class="absolute inset-0 w-full h-full">
            <img src="{{ asset('storage/' . ($currentCategory->banner_desktop ?? $currentCategory->banner_mobile)) }}" 
                 alt="{{ $currentCategory->name_ar }}"
                 class="hidden md:block w-full h-full object-cover">
            <img src="{{ asset('storage/' . ($currentCategory->banner_mobile ?? $currentCategory->banner_desktop)) }}" 
                 alt="{{ $currentCategory->name_ar }}"
                 class="md:hidden w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-black/60 to-black/40"></div>
        </div>
    @else
        <!-- Default Gradient Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-primary-blue to-blue-800"></div>
    @endif
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="text-right">
            @if(isset($currentCategory))
                <div class="inline-flex items-center gap-2 bg-white/20 text-white px-4 py-2 rounded-full text-sm mb-4">
                    <i class="fas fa-folder"></i>
                    قسم
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">{{ $currentCategory->name_ar }}</h1>
            @elseif(isset($currentBrand))
                <div class="inline-flex items-center gap-2 bg-white/20 text-white px-4 py-2 rounded-full text-sm mb-4">
                    <i class="fas fa-tag"></i>
                    ماركة
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">{{ $currentBrand->name_ar }}</h1>
            @elseif(isset($searchQuery) && $searchQuery)
                <div class="inline-flex items-center gap-2 bg-white/20 text-white px-4 py-2 rounded-full text-sm mb-4">
                    <i class="fas fa-search"></i>
                    نتائج البحث
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">"{{ $searchQuery }}"</h1>
            @elseif(isset($notFound) && $notFound)
                <div class="inline-flex items-center gap-2 bg-red-500/50 text-white px-4 py-2 rounded-full text-sm mb-4">
                    <i class="fas fa-exclamation-triangle"></i>
                    غير موجود
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">
                    {{ $notFoundType == 'category' ? 'القسم غير موجود' : 'الماركة غير موجودة' }}
                </h1>
            @else
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">جميع المنتجات</h1>
                <p class="text-white/80">اكتشف تشكيلتنا الواسعة من المنتجات</p>
            @endif
        </div>
    </div>
</section>

@if(isset($categories) && $categories->isNotEmpty())
<section class="bg-white border-b">
    <div class="container mx-auto px-4 py-4">
        <div class="flex gap-2 overflow-x-auto md:overflow-visible md:flex-wrap whitespace-nowrap md:whitespace-normal pb-1 category-scroll">
            <a href="{{ route('products.index') }}"
               class="shrink-0 px-3 sm:px-4 py-2.5 rounded-full text-xs sm:text-sm font-semibold transition {{ !isset($currentCategory) ? 'bg-primary-blue text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                كل المنتجات
            </a>
            @foreach($categories as $category)
            <a href="{{ route('products.category', $category->slug) }}"
               class="shrink-0 px-3 sm:px-4 py-2.5 rounded-full text-xs sm:text-sm font-semibold transition {{ isset($parentCategory) && $parentCategory->id === $category->id ? 'bg-primary-blue text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                {{ $category->name_ar }}
            </a>
            @endforeach
        </div>

        @if(isset($parentCategory) && isset($subcategories) && $subcategories->isNotEmpty())
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-sm font-semibold text-gray-600 mb-3">الأقسام الفرعية داخل {{ $parentCategory->name_ar }}</p>
            <div class="flex gap-2 overflow-x-auto md:overflow-visible md:flex-wrap whitespace-nowrap md:whitespace-normal pb-1 category-scroll">
                <a href="{{ route('products.category', $parentCategory->slug) }}"
                   class="shrink-0 px-3 sm:px-4 py-2.5 rounded-full text-xs sm:text-sm font-semibold transition {{ isset($currentCategory) && $currentCategory->id === $parentCategory->id ? 'bg-primary-blue text-white' : 'bg-blue-50 text-primary-blue hover:bg-blue-100' }}">
                    الكل
                </a>
                @foreach($subcategories as $subcategory)
                <a href="{{ route('products.category', $subcategory->slug) }}"
                   class="shrink-0 px-3 sm:px-4 py-2.5 rounded-full text-xs sm:text-sm font-semibold transition {{ isset($currentCategory) && $currentCategory->id === $subcategory->id ? 'bg-primary-blue text-white' : 'bg-blue-50 text-primary-blue hover:bg-blue-100' }}">
                    {{ $subcategory->name_ar }}
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endif

<section class="py-8">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Sidebar Filters -->
            <aside class="lg:w-72 flex-shrink-0">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-4">
                    <h3 class="font-bold text-lg text-primary-blue mb-4 flex items-center gap-2">
                        <i class="fas fa-filter"></i>
                        تصفية النتائج
                    </h3>
                    
                    <form action="{{ route('products.index') }}" method="GET" id="filterForm">
                        <!-- Tags -->
                        @if(isset($tagGroups) && $tagGroups->isNotEmpty())
                            @foreach($tagGroups as $tagGroup)
                                @if($tagGroup->options->isNotEmpty())
                                <div class="mb-6">
                                    <label class="block text-gray-700 font-semibold mb-3 text-sm">{{ $tagGroup->name_ar }}</label>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($tagGroup->options as $tagOption)
                                        <button type="button" 
                                                onclick="toggleTag({{ $tagOption->id }})"
                                                class="tag-btn px-3 py-1.5 rounded-full text-xs font-semibold transition-all {{ !empty($selectedTags) && $selectedTags[0] == $tagOption->id ? 'bg-primary-blue text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                                                data-tag-id="{{ $tagOption->id }}">
                                            {{ $tagOption->name_ar }}
                                        </button>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        @endif

                        <!-- Hidden input for selected tag (single selection) -->
                        <input type="hidden" name="tags" id="selectedTags" value="{{ !empty($selectedTags) ? $selectedTags[0] : '' }}">

                        <!-- Brands -->
                        @if(isset($brands) && $brands->isNotEmpty())
                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">الماركات</label>
                            <div class="space-y-2 max-h-48 overflow-y-auto">
                                @foreach($brands as $brand)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="radio" 
                                           name="brand" 
                                           value="{{ $brand->slug }}"
                                           {{ ($filters['brand'] ?? '') == $brand->slug ? 'checked' : '' }}
                                           class="w-4 h-4 text-primary-blue focus:ring-primary-yellow">
                                    <span class="text-gray-600 group-hover:text-primary-blue transition text-sm">{{ $brand->name_ar ?? $brand->name_en }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Sort -->
                        <div class="mb-6">
                            <label class="block text-gray-700 font-semibold mb-2 text-sm">ترتيب حسب</label>
                            <select name="sort" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-yellow">
                                <option value="">الأكثر صلة</option>
                                <option value="latest" {{ ($filters['sort'] ?? '') == 'latest' ? 'selected' : '' }}>الأحدث</option>
                                <option value="price_asc" {{ ($filters['sort'] ?? '') == 'price_asc' ? 'selected' : '' }}>السعر: من الأقل</option>
                                <option value="price_desc" {{ ($filters['sort'] ?? '') == 'price_desc' ? 'selected' : '' }}>السعر: من الأعلى</option>
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 bg-primary-blue hover:bg-primary-blue/90 text-white font-semibold py-2.5 rounded-lg transition">
                                <i class="fas fa-filter ml-1"></i>
                                تطبيق
                            </button>
                            <a href="{{ route('products.index') }}" class="px-4 py-2.5 border border-gray-300 text-gray-600 hover:bg-gray-100 rounded-lg transition">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </aside>

            <!-- Products Grid -->
            <div class="flex-1">
                @if(isset($notFound) && $notFound)
                    <!-- Not Found State -->
                    <div class="bg-white rounded-xl shadow-lg p-12 text-right">
                        <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-search text-4xl text-red-400"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">
                            {{ $notFoundType == 'category' ? 'القسم' : 'الماركة' }} "{{ $notFoundSlug }}" {{ $notFoundType == 'category' ? 'غير موجود' : 'غير موجودة' }}
                        </h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto">
                            عذراً، لم نتمكن من العثور على ما تبحث عنه. جرب البحث في الأقسام المتاحة أو تصفح جميع المنتجات.
                        </p>
                        <div class="flex flex-wrap justify-end gap-4">
                            <a href="{{ route('products.index') }}" class="inline-flex items-center bg-primary-blue text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-blue/90 transition">
                                <i class="fas fa-store ml-2"></i>
                                تصفح جميع المنتجات
                            </a>
                            <a href="{{ route('home') }}" class="inline-flex items-center border-2 border-primary-blue text-primary-blue px-6 py-3 rounded-lg font-semibold hover:bg-primary-blue hover:text-white transition">
                                <i class="fas fa-home ml-2"></i>
                                الصفحة الرئيسية
                            </a>
                        </div>
                    </div>
                @elseif($products->isEmpty())
                    <!-- Empty State -->
                    <div class="bg-white rounded-xl shadow-lg p-12 text-right">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-box-open text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">لا توجد منتجات</h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto">
                            @if(isset($searchQuery) && $searchQuery)
                                لم نجد أي نتائج لـ "{{ $searchQuery }}". جرب كلمات بحث مختلفة.
                            @else
                                لم يتم إضافة منتجات بعد. تفقد هذه الصفحة لاحقاً!
                            @endif
                        </p>
                        <a href="{{ route('home') }}" class="inline-flex items-center bg-primary-blue text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-blue/90 transition">
                            <i class="fas fa-home ml-2"></i>
                            الصفحة الرئيسية
                        </a>
                    </div>
                @else
                    <!-- Results Count -->
                    <div class="flex items-center justify-between mb-6 bg-white rounded-lg px-4 py-3 shadow">
                        <p class="text-gray-600 text-right">
                            <span class="font-bold text-primary-blue">{{ $products->total() }}</span> منتج
                        </p>
                    </div>

                    <!-- Products Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-6">
                        @foreach($products as $product)
                        <x-storefront.product-card :product="$product" />
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                        <div class="mt-8">
                            {{ $products->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-clamp: 2;
    }

    .category-scroll {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .category-scroll::-webkit-scrollbar {
        display: none;
    }
</style>
@endpush

@push('scripts')
<script>
    function toggleTag(tagId) {
        const input = document.getElementById('selectedTags');
        const currentTag = input.value ? parseInt(input.value) : null;
        
        // If clicking the same tag, deselect it
        if (currentTag === tagId) {
            input.value = '';
        } else {
            // Select the new tag (single selection)
            input.value = tagId;
        }
        
        // Update button styles - only one can be selected
        document.querySelectorAll('.tag-btn').forEach(btn => {
            const btnTagId = parseInt(btn.dataset.tagId);
            if (btnTagId === tagId && currentTag !== tagId) {
                // Select this button
                btn.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
                btn.classList.add('bg-primary-blue', 'text-white');
            } else {
                // Deselect all other buttons
                btn.classList.remove('bg-primary-blue', 'text-white');
                btn.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
            }
        });
        
        // Submit form
        document.getElementById('filterForm').submit();
    }
</script>
@endpush
@endsection
