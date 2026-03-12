<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Slider;

class HomeController extends Controller
{
    public function __invoke()
    {
        $sliders = Slider::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->take(5)
            ->get();

        $categories = Category::query()
            ->active()
            ->featured()
            ->ordered()
            ->take(6)
            ->get();

        $featuredProducts = Product::query()
            ->with(['images' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')])
            ->available()
            ->featured()
            ->orderBy('sort_order')
            ->take(8)
            ->get();

        $latestProducts = Product::query()
            ->with(['images' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')])
            ->available()
            ->latest()
            ->take(8)
            ->get();

        $homeOffers = Offer::valid()
            ->featured()
            ->orderBy('sort_order')
            ->take(2)
            ->get();

        return view('storefront.home', [
            'sliders' => $sliders,
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
            'latestProducts' => $latestProducts,
            'homeOffers' => $homeOffers,
        ]);
    }
}
