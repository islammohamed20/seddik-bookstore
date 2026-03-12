<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Display wishlist page
     */
    public function index()
    {
        $wishlist = session('wishlist', []);
        
        $products = collect();
        if (!empty($wishlist)) {
            $products = Product::whereIn('id', $wishlist)
                ->with(['images' => fn($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')])
                ->active()
                ->get();
        }
        
        return view('storefront.wishlist.index', compact('products'));
    }

    /**
     * Add product to wishlist
     */
    public function store(Product $product)
    {
        if (! auth()->check()) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب عليك تسجيل الدخول أولاً',
                    'requires_login' => true,
                ], 401);
            }

            return redirect()
                ->route('login')
                ->with('status', 'يجب عليك تسجيل الدخول أولاً');
        }

        $wishlist = session('wishlist', []);
        
        if (!in_array($product->id, $wishlist)) {
            $wishlist[] = $product->id;
            session(['wishlist' => $wishlist]);
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تمت إضافة المنتج للمفضلة',
                    'count' => count($wishlist),
                ]);
            }
            
            return back()->with('status', 'تمت إضافة المنتج للمفضلة');
        }
        
        if (request()->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'المنتج موجود بالفعل في المفضلة',
                'count' => count($wishlist),
            ]);
        }
        
        return back()->with('status', 'المنتج موجود بالفعل في المفضلة');
    }

    /**
     * Remove product from wishlist
     */
    public function destroy(Product $product)
    {
        $wishlist = session('wishlist', []);
        
        $wishlist = array_filter($wishlist, fn($id) => $id !== $product->id);
        $wishlist = array_values($wishlist);
        
        session(['wishlist' => $wishlist]);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف المنتج من المفضلة',
                'count' => count($wishlist),
            ]);
        }
        
        return back()->with('status', 'تم حذف المنتج من المفضلة');
    }

    /**
     * Toggle product in wishlist
     */
    public function toggle(Product $product)
    {
        if (! auth()->check()) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'يجب عليك تسجيل الدخول أولاً',
                    'requires_login' => true,
                ], 401);
            }

            return redirect()
                ->route('login')
                ->with('status', 'يجب عليك تسجيل الدخول أولاً');
        }

        $wishlist = session('wishlist', []);
        
        if (in_array($product->id, $wishlist)) {
            $wishlist = array_filter($wishlist, fn($id) => $id !== $product->id);
            $wishlist = array_values($wishlist);
            $message = 'تم حذف المنتج من المفضلة';
            $added = false;
        } else {
            $wishlist[] = $product->id;
            $message = 'تمت إضافة المنتج للمفضلة';
            $added = true;
        }
        
        session(['wishlist' => $wishlist]);
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'added' => $added,
                'count' => count($wishlist),
            ]);
        }
        
        return back()->with('status', $message);
    }

    /**
     * Clear wishlist
     */
    public function clear()
    {
        session()->forget('wishlist');
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم مسح المفضلة',
                'count' => 0,
            ]);
        }
        
        return back()->with('status', 'تم مسح المفضلة');
    }

    /**
     * Get wishlist count (for AJAX)
     */
    public function count()
    {
        return response()->json([
            'count' => count(session('wishlist', [])),
        ]);
    }
}
