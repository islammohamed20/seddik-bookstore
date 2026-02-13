<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::valid()
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->latest()
            ->get();

        return view('storefront.offers', compact('offers'));
    }

    public function show(Request $request, Offer $offer)
    {
        if (! $offer->is_active) {
            abort(404);
        }

        $products = $offer->products()
            ->with('images')
            ->active()
            ->paginate(12)
            ->withQueryString();

        return view('storefront.offers.show', compact('offer', 'products'));
    }
}
