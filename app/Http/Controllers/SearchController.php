<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Live search for products
     */
    public function search(Request $request)
    {
        $query = $request->string('q')->trim()->toString();
        
        if (strlen($query) < 2) {
            return response()->json([
                'products' => [],
                'categories' => [],
                'total' => 0,
            ]);
        }
        
        // Search products
        $products = Product::query()
            ->with(['images' => fn($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')->limit(1)])
            ->with('category:id,name_ar,slug')
            ->active()
            ->where(function ($q) use ($query) {
                $q->where('name_ar', 'LIKE', "%{$query}%")
                  ->orWhere('name_en', 'LIKE', "%{$query}%")
                  ->orWhere('sku', 'LIKE', "%{$query}%")
                  ->orWhere('description_ar', 'LIKE', "%{$query}%");
            })
            ->orderByDesc('is_featured')
            ->take(8)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name_ar ?? $product->name_en,
                    'slug' => $product->slug,
                    'price' => $product->final_price,
                    'original_price' => $product->price,
                    'has_discount' => $product->sale_price && $product->sale_price < $product->price,
                    'image' => $product->primary_image?->url ?? ($product->images->first()?->url ?? null),
                    'category' => $product->category?->name_ar,
                    'category_slug' => $product->category?->slug,
                    'url' => route('products.show', $product),
                    'is_featured' => $product->is_featured,
                    'in_stock' => $product->stock_quantity > 0,
                ];
            });
        
        // Search categories
        $categories = Category::query()
            ->active()
            ->where(function ($q) use ($query) {
                $q->where('name_ar', 'LIKE', "%{$query}%")
                  ->orWhere('name_en', 'LIKE', "%{$query}%");
            })
            ->take(4)
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name_ar ?? $category->name_en,
                    'slug' => $category->slug,
                    'url' => route('products.category', $category->slug),
                    'products_count' => $category->products_count ?? 0,
                ];
            });
        
        // Count total matching products
        $total = Product::query()
            ->active()
            ->where(function ($q) use ($query) {
                $q->where('name_ar', 'LIKE', "%{$query}%")
                  ->orWhere('name_en', 'LIKE', "%{$query}%")
                  ->orWhere('sku', 'LIKE', "%{$query}%");
            })
            ->count();
        
        return response()->json([
            'products' => $products,
            'categories' => $categories,
            'total' => $total,
            'query' => $query,
            'search_url' => route('products.search', ['q' => $query]),
        ]);
    }
}
