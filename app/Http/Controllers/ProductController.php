<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\TagGroup;
use App\Services\VariantResolver;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->with(['images' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')])
            ->active();

        // Filter by tags (single selection)
        if ($tags = $request->input('tags')) {
            $tagId = is_numeric($tags) ? (int)$tags : null;
            if ($tagId) {
                $query->whereHas('tagOptions', function ($q) use ($tagId) {
                    $q->where('tag_options.id', $tagId);
                });
            }
        }

        if ($categorySlug = $request->string('category')->toString()) {
            $category = Category::query()
                ->where('slug', $categorySlug)
                ->first();

            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        if ($brandSlug = $request->string('brand')->toString()) {
            $brand = Brand::query()
                ->where('slug', $brandSlug)
                ->first();

            if ($brand) {
                $query->where('brand_id', $brand->id);
            }
        }

        if ($type = $request->string('type')->toString()) {
            $query->ofType($type);
        }

        // فلترة حسب حالة المخزون
        if ($request->boolean('in_stock')) {
            $query->inStock();
        }

        // فلترة حسب السعر
        if ($minPrice = $request->float('min_price')) {
            $query->whereRaw('COALESCE(sale_price, price) >= ?', [$minPrice]);
        }

        if ($maxPrice = $request->float('max_price')) {
            $query->whereRaw('COALESCE(sale_price, price) <= ?', [$maxPrice]);
        }

        $sort = $request->string('sort')->toString();

        $query = match ($sort) {
            'price_asc' => $query->orderByRaw('COALESCE(sale_price, price) asc'),
            'price_desc' => $query->orderByRaw('COALESCE(sale_price, price) desc'),
            'latest' => $query->latest(),
            'name_asc' => $query->orderBy('name_en'),
            'name_desc' => $query->orderByDesc('name_en'),
            default => $query->orderByDesc('is_featured')->orderBy('sort_order'),
        };

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::query()
            ->root()
            ->active()
            ->with(['children' => fn ($q) => $q->active()->ordered()])
            ->ordered()
            ->get();

        $brands = Brand::query()
            ->active()
            ->ordered()
            ->get();

        $tagGroups = TagGroup::query()
            ->active()
            ->with(['options' => fn($q) => $q->active()->ordered()])
            ->ordered()
            ->get();

        $selectedTags = $request->input('tags') ? [is_numeric($request->input('tags')) ? (int)$request->input('tags') : null] : [];
        $selectedTags = array_filter($selectedTags); // Remove null values

        return view('storefront.products.index', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'tagGroups' => $tagGroups,
            'selectedTags' => $selectedTags,
            'filters' => [
                'category' => $categorySlug,
                'brand' => $brandSlug,
                'type' => $type,
                'sort' => $sort,
                'in_stock' => $request->boolean('in_stock'),
                'min_price' => $minPrice ?? null,
                'max_price' => $maxPrice ?? null,
                'tags' => $selectedTags,
            ],
        ]);
    }

    /**
     * Filter products by category
     */
    public function byCategory(Request $request, $category)
    {
        $categoryModel = Category::query()
            ->with([
                'parent',
                'children' => fn ($q) => $q->active()->ordered(),
            ])
            ->where('slug', $category)
            ->first();

        // إذا لم يوجد القسم، عرض صفحة خاصة
        if (! $categoryModel) {
            $categories = Category::query()
                ->root()
                ->active()
                ->with(['children' => fn ($q) => $q->active()->ordered()])
                ->ordered()
                ->get();

            $tagGroups = TagGroup::query()
                ->active()
                ->with(['options' => fn($q) => $q->active()->ordered()])
                ->ordered()
                ->get();

            return view('storefront.products.index', [
                'products' => collect(),
                'categories' => $categories,
                'brands' => collect(),
                'tagGroups' => $tagGroups,
                'selectedTags' => [],
                'notFound' => true,
                'notFoundType' => 'category',
                'notFoundSlug' => $category,
                'filters' => ['category' => $category],
            ]);
        }

        $categoryIds = [$categoryModel->id];
        if (is_null($categoryModel->parent_id)) {
            $categoryIds = array_merge(
                $categoryIds,
                $categoryModel->children->pluck('id')->all()
            );
        }

        $query = Product::query()
            ->with(['images' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')])
            ->active()
            ->whereIn('category_id', $categoryIds);

        // Filter by tags (single selection)
        if ($tags = $request->input('tags')) {
            $tagId = is_numeric($tags) ? (int)$tags : null;
            if ($tagId) {
                $query->whereHas('tagOptions', function ($q) use ($tagId) {
                    $q->where('tag_options.id', $tagId);
                });
            }
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::query()
            ->root()
            ->active()
            ->with(['children' => fn ($q) => $q->active()->ordered()])
            ->ordered()
            ->get();
        $brands = Brand::active()->ordered()->get();

        $tagGroups = TagGroup::query()
            ->active()
            ->with(['options' => fn($q) => $q->active()->ordered()])
            ->ordered()
            ->get();

        $selectedTags = $request->input('tags') ? [is_numeric($request->input('tags')) ? (int)$request->input('tags') : null] : [];
        $selectedTags = array_filter($selectedTags); // Remove null values

        $parentCategory = $categoryModel->parent ?: $categoryModel;
        $subcategories = $parentCategory->children()->active()->ordered()->get();

        return view('storefront.products.index', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'tagGroups' => $tagGroups,
            'selectedTags' => $selectedTags,
            'currentCategory' => $categoryModel,
            'parentCategory' => $parentCategory,
            'subcategories' => $subcategories,
            'filters' => [
                'category' => $category,
                'tags' => $selectedTags,
            ],
        ]);
    }

    /**
     * Filter products by brand
     */
    public function byBrand(Request $request, $brand)
    {
        $brandModel = Brand::where('slug', $brand)->first();

        // إذا لم توجد الماركة، عرض صفحة خاصة
        if (! $brandModel) {
            $brands = Brand::active()->ordered()->get();

            $tagGroups = TagGroup::query()
                ->active()
                ->with(['options' => fn($q) => $q->active()->ordered()])
                ->ordered()
                ->get();

            return view('storefront.products.index', [
                'products' => collect(),
                'categories' => collect(),
                'brands' => $brands,
                'tagGroups' => $tagGroups,
                'selectedTags' => [],
                'notFound' => true,
                'notFoundType' => 'brand',
                'notFoundSlug' => $brand,
                'filters' => ['brand' => $brand],
            ]);
        }

        $query = Product::query()
            ->with(['images' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')])
            ->active()
            ->where('brand_id', $brandModel->id);

        // Filter by tags (single selection)
        if ($tags = $request->input('tags')) {
            $tagId = is_numeric($tags) ? (int)$tags : null;
            if ($tagId) {
                $query->whereHas('tagOptions', function ($q) use ($tagId) {
                    $q->where('tag_options.id', $tagId);
                });
            }
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::query()
            ->root()
            ->active()
            ->with(['children' => fn ($q) => $q->active()->ordered()])
            ->ordered()
            ->get();
        $brands = Brand::active()->ordered()->get();

        $tagGroups = TagGroup::query()
            ->active()
            ->with(['options' => fn($q) => $q->active()->ordered()])
            ->ordered()
            ->get();

        $selectedTags = $request->input('tags') ? [is_numeric($request->input('tags')) ? (int)$request->input('tags') : null] : [];
        $selectedTags = array_filter($selectedTags); // Remove null values

        return view('storefront.products.index', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'tagGroups' => $tagGroups,
            'selectedTags' => $selectedTags,
            'currentBrand' => $brandModel,
            'filters' => [
                'brand' => $brand,
                'tags' => $selectedTags,
            ],
        ]);
    }

    /**
     * Search products
     */
    public function search(Request $request)
    {
        $q = $request->string('q')->toString();

        $query = Product::query()
            ->with(['images' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')])
            ->active();

        if ($q) {
            $query->where(function ($query) use ($q) {
                $query->where('name_ar', 'like', "%{$q}%")
                    ->orWhere('name_en', 'like', "%{$q}%")
                    ->orWhere('description_ar', 'like', "%{$q}%")
                    ->orWhere('description_en', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%");
            });
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::query()
            ->root()
            ->active()
            ->with(['children' => fn ($q) => $q->active()->ordered()])
            ->ordered()
            ->get();
        $brands = Brand::active()->ordered()->get();

        return view('storefront.products.index', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'searchQuery' => $q,
            'filters' => [
                'q' => $q,
            ],
        ]);
    }

    public function show(Product $product)
    {
        // التحقق من أن المنتج نشط
        if (! $product->is_active) {
            abort(404);
        }

        $product->load([
            'images' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order'),
            'brand',
            'category',
            'tagOptions.group',
        ]);

        // Load active variants for variable products
        $variantData = [];
        if ($product->product_type === 'variable') {
            $product->load(['variants' => fn ($q) => $q->active()->ordered()]);
            $resolver = new VariantResolver();
            $variantData = $resolver->buildStorefrontData($product);
        }

        $related = Product::query()
            ->with(['images' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')])
            ->available()
            ->where('id', '!=', $product->id)
            ->when($product->category_id, function ($query) use ($product) {
                $query->where('category_id', $product->category_id);
            })
            ->inRandomOrder()
            ->take(8)
            ->get();

        return view('storefront.products.show', [
            'product' => $product,
            'related' => $related,
            'variantData' => $variantData,
        ]);
    }
}
