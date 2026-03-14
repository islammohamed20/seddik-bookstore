<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $this->getCart($request);

        // التحقق من صلاحية المنتجات في السلة وتحديث الأسعار
        $cart = $this->validateCart($cart);
        $this->putCart($request, $cart);

        $subtotal = collect($cart)->sum(function ($item) {
            return ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
        });

        return view('storefront.cart.index', [
            'items' => $cart,
            'subtotal' => $subtotal,
        ]);
    }

    public function clear(Request $request)
    {
        $request->session()->forget('cart');

        if (Auth::check()) {
            Auth::user()->cartItems()->delete();
        }

        return redirect()->route('cart.index')->with('status', 'cart_cleared');
    }

    public function applyCoupon(Request $request)
    {
        // TODO: Implement coupon logic
        return redirect()->route('cart.index');
    }

    public function removeCoupon(Request $request)
    {
        $request->session()->forget('coupon');

        return redirect()->route('cart.index');
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:100'],
            'variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
        ]);

        $quantity = $validated['quantity'] ?? 1;
        $variantId = $validated['variant_id'] ?? null;

        // التحقق من أن المنتج نشط ومتوفر
        if (! $product->is_active) {
            return redirect()
                ->back()
                ->with('error', 'هذا المنتج غير متوفر حالياً');
        }

        if ($product->stock_status === 'out_of_stock') {
            return redirect()
                ->back()
                ->with('error', 'هذا المنتج نفذ من المخزون');
        }

        // التحقق من المتغير إن كان المنتج متغير
        $variant = null;
        $variantName = '';
        if ($product->product_type === 'variable' && $variantId) {
            $variant = $product->variants()->with('attributeValues.attribute')->find($variantId);
            if (! $variant) {
                return redirect()
                    ->back()
                    ->with('error', 'اختيار المتغير غير صحيح');
            }
            // Build variant display name from attributes
            $variantName = $variant->attributeValues->map(function ($av) {
                return ($av->attribute?->name_ar ?? $av->attribute?->name_en ?? '') . ': ' . $av->value;
            })->join(' | ');
        } elseif ($product->product_type === 'variable' && $product->variants()->count() > 0) {
            return redirect()
                ->back()
                ->with('error', 'يرجى اختيار المواصفات المطلوبة');
        }

        $cart = $this->getCart($request);

        // Cart key includes variant_id for variable products
        $key = $variantId ? $product->id . '_v' . $variantId : (string) $product->id;
        $currentQuantity = $cart[$key]['quantity'] ?? 0;
        $newQuantity = $currentQuantity + $quantity;

        // Check stock for variant or product
        $availableStock = $variant ? (int) $variant->stock_quantity : $product->stock_quantity;
        if ($newQuantity > $availableStock) {
            return redirect()
                ->back()
                ->with('error', 'الكمية المطلوبة غير متوفرة. المتوفر: ' . $availableStock);
        }

        // Use variant price when available, otherwise product price
        $price = $variant
            ? (float) $variant->final_price
            : (float) ($product->sale_price ?? $product->price);

        $imagePath = $variant?->image
            ?: ($product->images->firstWhere('is_primary', true)?->path ?? $product->images->first()?->path);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = $newQuantity;
            $cart[$key]['price'] = (float) $price;
            $cart[$key]['image'] = $imagePath;
        } else {
            $cart[$key] = [
                'product_id' => $product->id,
                'variant_id' => $variantId,
                'variant_name' => $variantName,
                'name' => $product->name_ar ?? $product->name_en,
                'slug' => $product->slug,
                'price' => (float) $price,
                'quantity' => $quantity,
                'image' => $imagePath,
            ];
        }

        $this->putCart($request, $cart);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart',
                'cart_count' => count($cart),
            ]);
        }

        return redirect()
            ->back()
            ->with('status', 'added_to_cart')
            ->with('success', 'تمت إضافة المنتج للسلة بنجاح!');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
            'variant_id' => ['nullable', 'integer', 'exists:product_variants,id'],
        ]);

        $variantId = $validated['variant_id'] ?? null;
        $variant = null;
        if ($variantId) {
            $variant = $product->variants()->find($variantId);
            if (! $variant) {
                return redirect()->back()->with('error', 'اختيار المتغير غير صحيح');
            }
        }

        // التحقق من توفر الكمية
        $availableStock = $variant ? (int) $variant->stock_quantity : (int) $product->stock_quantity;
        if ($validated['quantity'] > $availableStock) {
            return redirect()
                ->back()
                ->with('error', 'الكمية المطلوبة غير متوفرة. المتوفر: '.$availableStock);
        }

        $cart = $this->getCart($request);

        $key = $variantId ? $product->id . '_v' . $variantId : (string) $product->id;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = $validated['quantity'];
            // تحديث السعر في حالة تغيره
            $cart[$key]['price'] = $variant
                ? (float) $variant->final_price
                : (float) ($product->sale_price ?? $product->price);
            $cart[$key]['image'] = $variant?->image
                ?: ($product->images()->where('is_primary', true)->first()?->path ?? $product->images()->first()?->path);

            $this->putCart($request, $cart);
        }

        return redirect()
            ->route('cart.index')
            ->with('status', 'cart_updated');
    }

    public function destroy(Request $request, Product $product)
    {
        $cart = $this->getCart($request);

        $variantId = $request->integer('variant_id');
        $key = $variantId ? $product->id . '_v' . $variantId : (string) $product->id;

        if (isset($cart[$key])) {
            unset($cart[$key]);

            $this->putCart($request, $cart);
        }

        return redirect()
            ->route('cart.index')
            ->with('status', 'removed_from_cart');
    }

    protected function getCart(Request $request): array
    {
        if (Auth::check()) {
            $user = Auth::user();
            $dbCartItems = $user->cartItems()->get();
            $cart = [];
            
            foreach ($dbCartItems as $item) {
                $key = $item->variant_id ? $item->product_id . '_v' . $item->variant_id : (string) $item->product_id;
                $cart[$key] = [
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'quantity' => $item->quantity,
                ];
            }
            
            // Sync session cart with DB if session is newer or has items not in DB
            $sessionCart = $request->session()->get('cart', []);
            if (!empty($sessionCart)) {
                $merged = false;
                foreach ($sessionCart as $key => $details) {
                    if (!isset($cart[$key])) {
                        $cart[$key] = $details;
                        $merged = true;
                    }
                }
                if ($merged) {
                    $this->putCart($request, $cart);
                }
            }
            
            return $cart;
        }
        
        return $request->session()->get('cart', []);
    }

    protected function putCart(Request $request, array $cart): void
    {
        $request->session()->put('cart', $cart);

        if (Auth::check()) {
            $user = Auth::user();
            
            // Sync to DB
            // 1. Clear current DB cart
            $user->cartItems()->delete();
            
            // 2. Insert new cart
            foreach ($cart as $key => $item) {
                CartItem::create([
                    'user_id' => $user->id,
                    'product_id' => $item['product_id'],
                    'variant_id' => !empty($item['variant_id']) ? (int) $item['variant_id'] : null,
                    'quantity' => $item['quantity'],
                ]);
            }
        }
    }

    /**
     * التحقق من صحة السلة وإزالة المنتجات غير الصالحة
     */
    protected function validateCart(array $cart): array
    {
        $productIds = collect($cart)->pluck('product_id')->filter()->unique()->toArray();

        if (empty($productIds)) {
            return [];
        }

        $products = Product::query()
            ->whereIn('id', $productIds)
            ->where('is_active', true)
            ->with(['images'])
            ->get()
            ->keyBy('id');

        // Load variants for variant items
        $variantIds = collect($cart)->pluck('variant_id')->filter()->unique()->toArray();
        $variants = collect();
        if (! empty($variantIds)) {
            $variants = \App\Models\ProductVariant::query()
                ->whereIn('id', $variantIds)
                ->where('is_active', true)
                ->with('attributeValues.attribute')
                ->get()
                ->keyBy('id');
        }

        $validatedCart = [];

        foreach ($cart as $key => $item) {
            if (empty($item['product_id'])) {
                continue;
            }
            $product = $products->get($item['product_id']);

            if (! $product) {
                continue; // المنتج غير موجود أو غير نشط
            }

            // Check variant validity
            $variant = null;
            if (! empty($item['variant_id'])) {
                $variant = $variants->get($item['variant_id']);
                if (! $variant) {
                    continue; // المتغير غير موجود أو غير نشط
                }
            }

            // تحديث السعر
            if ($variant) {
                $item['price'] = (float) $variant->final_price;
            } else {
                $item['price'] = (float) ($product->sale_price ?? $product->price);
            }

            $item['name'] = $product->name_ar ?? $product->name_en;
            $item['slug'] = $product->slug;

            // Update variant name
            if ($variant) {
                $item['variant_name'] = $variant->attributeValues->map(function ($av) {
                    return ($av->attribute?->name_ar ?? '') . ': ' . $av->value;
                })->join(' | ');
                $item['image'] = $variant->image
                    ?: ($product->images->firstWhere('is_primary', true)?->path ?? $product->images->first()?->path);
            } else {
                $item['image'] = $product->images->firstWhere('is_primary', true)?->path ?? $product->images->first()?->path;
            }

            // التحقق من التوفر والكمية
            if ($variant) {
                if ($variant->is_active && ($variant->stock_quantity > 0 || $variant->stock_status !== 'out_of_stock')) {
                    $item['quantity'] = $variant->stock_quantity > 0 
                        ? min($item['quantity'], $variant->stock_quantity) 
                        : $item['quantity'];
                    $validatedCart[$key] = $item;
                }
            } else {
                if ($product->is_active && ($product->stock_quantity > 0 || $product->stock_status !== 'out_of_stock')) {
                    $item['quantity'] = $product->stock_quantity > 0 
                        ? min($item['quantity'], $product->stock_quantity) 
                        : $item['quantity'];
                    $validatedCart[$key] = $item;
                }
            }
        }

        return $validatedCart;
    }
}
