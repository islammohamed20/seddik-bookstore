<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $this->getCart($request);

        // التحقق من صلاحية المنتجات في السلة وتحديث الأسعار
        $cart = $this->validateCart($cart);
        $this->putCart($request, $cart);

        $subtotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('storefront.cart.index', [
            'items' => $cart,
            'subtotal' => $subtotal,
        ]);
    }

    public function clear(Request $request)
    {
        $request->session()->forget('cart');

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
        if (! auth()->check()) {
            if ($request->wantsJson() || $request->ajax()) {
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

        // Use base product price
        $price = (float) ($product->sale_price ?? $product->price);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = $newQuantity;
            $cart[$key]['price'] = (float) $price;
        } else {
            $cart[$key] = [
                'product_id' => $product->id,
                'variant_id' => $variantId,
                'variant_name' => $variantName,
                'name' => $product->name_ar ?? $product->name_en,
                'slug' => $product->slug,
                'price' => (float) $price,
                'quantity' => $quantity,
                'image' => $product->images()->where('is_primary', true)->first()?->path,
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
            ->route('cart.index')
            ->with('status', 'added_to_cart');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        // التحقق من توفر الكمية
        if ($validated['quantity'] > $product->stock_quantity) {
            return redirect()
                ->back()
                ->with('error', 'الكمية المطلوبة غير متوفرة. المتوفر: '.$product->stock_quantity);
        }

        $cart = $this->getCart($request);

        $key = (string) $product->id;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = $validated['quantity'];
            // تحديث السعر في حالة تغيره
            $cart[$key]['price'] = (float) ($product->sale_price ?? $product->price);

            $this->putCart($request, $cart);
        }

        return redirect()
            ->route('cart.index')
            ->with('status', 'cart_updated');
    }

    public function destroy(Request $request, Product $product)
    {
        $cart = $this->getCart($request);

        $key = (string) $product->id;

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
        return $request->session()->get('cart', []);
    }

    protected function putCart(Request $request, array $cart): void
    {
        $request->session()->put('cart', $cart);
    }

    /**
     * التحقق من صحة السلة وإزالة المنتجات غير الصالحة
     */
    protected function validateCart(array $cart): array
    {
        $productIds = collect($cart)->pluck('product_id')->unique()->toArray();

        if (empty($productIds)) {
            return [];
        }

        $products = Product::query()
            ->whereIn('id', $productIds)
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        // Load variants for variant items
        $variantIds = collect($cart)->pluck('variant_id')->filter()->unique()->toArray();
        $variants = [];
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
            $product = $products->get($item['product_id']);

            if (! $product) {
                continue; // المنتج غير موجود أو غير نشط
            }

            // Check variant validity
            $variant = null;
            if (! empty($item['variant_id'])) {
                $variant = $variants[$item['variant_id']] ?? null;
                if (! $variant) {
                    continue; // المتغير غير موجود أو غير نشط
                }
            }

            // تحديث السعر
            $item['price'] = (float) ($product->sale_price ?? $product->price);
            $item['name'] = $product->name_ar ?? $product->name_en;

            // Update variant name
            if ($variant) {
                $item['variant_name'] = $variant->attributeValues->map(function ($av) {
                    return ($av->attribute?->name_ar ?? '') . ': ' . $av->value;
                })->join(' | ');
            }

            // التحقق من الكمية المتوفرة
            $availableStock = $variant ? (int) $variant->stock_quantity : $product->stock_quantity;
            if ($availableStock > 0) {
                $item['quantity'] = min($item['quantity'], $availableStock);
                $validatedCart[$key] = $item;
            }
        }

        return $validatedCart;
    }
}
