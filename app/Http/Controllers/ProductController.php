<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->with(['images' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')])
            ->active();

        if ($search = $request->string('q')->toString()) {
            $query->search($search);
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
            $query->whereRaw('COALESCE(sale_price_inside_assiut, price_inside_assiut) >= ?', [$minPrice]);
        }

        if ($maxPrice = $request->float('max_price')) {
            $query->whereRaw('COALESCE(sale_price_inside_assiut, price_inside_assiut) <= ?', [$maxPrice]);
        }

        $sort = $request->string('sort')->toString();

        $query = match ($sort) {
            'price_asc' => $query->orderByRaw('COALESCE(sale_price_inside_assiut, price_inside_assiut) asc'),
            'price_desc' => $query->orderByRaw('COALESCE(sale_price_inside_assiut, price_inside_assiut) desc'),
            'latest' => $query->latest(),
            'name_asc' => $query->orderBy('name_en'),
            'name_desc' => $query->orderByDesc('name_en'),
            default => $query->orderByDesc('is_featured')->orderBy('sort_order'),
        };

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::query()
            ->root()
            ->active()
            ->ordered()
            ->get();

        $brands = Brand::query()
            ->active()
            ->ordered()
            ->get();

        return view('storefront.products.index', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'filters' => [
                'q' => $search,
                'category' => $categorySlug,
                'brand' => $brandSlug,
                'type' => $type,
                'sort' => $sort,
                'in_stock' => $request->boolean('in_stock'),
                'min_price' => $minPrice ?? null,
                'max_price' => $maxPrice ?? null,
            ],
        ]);
    }

    /**
     * Filter products by category
     */
    public function byCategory(Request $request, $category)
    {
        $categoryModel = Category::where('slug', $category)->first();

        // إذا لم يوجد القسم، عرض صفحة خاصة
        if (! $categoryModel) {
            $categories = Category::root()->active()->ordered()->get();

            return view('storefront.products.index', [
                'products' => collect(),
                'categories' => $categories,
                'brands' => collect(),
                'notFound' => true,
                'notFoundType' => 'category',
                'notFoundSlug' => $category,
                'filters' => ['category' => $category],
            ]);
        }

        $query = Product::query()
            ->with(['images' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')])
            ->active()
            ->where('category_id', $categoryModel->id);

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::root()->active()->ordered()->get();
        $brands = Brand::active()->ordered()->get();

        return view('storefront.products.index', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'currentCategory' => $categoryModel,
            'filters' => [
                'category' => $category,
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

            return view('storefront.products.index', [
                'products' => collect(),
                'categories' => collect(),
                'brands' => $brands,
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

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::root()->active()->ordered()->get();
        $brands = Brand::active()->ordered()->get();

        return view('storefront.products.index', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'currentBrand' => $brandModel,
            'filters' => [
                'brand' => $brand,
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

        $categories = Category::root()->active()->ordered()->get();
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
        ]);

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
        ]);
    }
}
