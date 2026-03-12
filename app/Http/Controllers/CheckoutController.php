<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\AdminNotification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Models\ShippingMethod;
use App\Models\ShippingZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function create(Request $request)
    {
        $cart = $this->getCart($request);

        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        // التحقق من صحة السلة
        $cart = $this->validateCart($cart);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'السلة فارغة أو المنتجات غير متوفرة');
        }

        $this->putCart($request, $cart);

        $subtotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $minOrder = $this->getMinOrderValue($request);
        if ($minOrder > 0 && $subtotal < $minOrder) {
            return redirect()->route('cart.index')
                ->with('error', 'الحد الأدنى لقيمة الطلب هو ' . number_format($minOrder, 2) . ' ج.م');
        }

        // التحقق من الكوبون المطبق
        $appliedCoupon = $request->session()->get('applied_coupon');
        $couponDiscount = 0;

        if ($appliedCoupon) {
            $coupon = Coupon::find($appliedCoupon['id']);
            if ($coupon) {
                $validation = $coupon->isValid($request->user()?->id, $subtotal);
                if ($validation['valid']) {
                    $couponDiscount = $coupon->calculateDiscount($subtotal);
                } else {
                    $request->session()->forget('applied_coupon');
                }
            }
        }

        return view('storefront.checkout.create', [
            'items' => $cart,
            'subtotal' => $subtotal,
            'couponDiscount' => $couponDiscount,
            'appliedCoupon' => $appliedCoupon,
            'grandTotal' => $subtotal - $couponDiscount,
        ]);
    }

    /**
     * تطبيق كوبون الخصم
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => ['required', 'string', 'max:50'],
        ]);

        $coupon = Coupon::query()
            ->byCode($request->input('coupon_code'))
            ->first();

        if (! $coupon) {
            return back()->with('error', 'الكوبون غير صحيح');
        }

        $cart = $this->getCart($request);
        $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);

        $validation = $coupon->isValid($request->user()?->id, $subtotal);

        if (! $validation['valid']) {
            return back()->with('error', implode(', ', $validation['errors']));
        }

        $request->session()->put('applied_coupon', [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'name' => $coupon->name,
        ]);

        return back()->with('status', 'تم تطبيق الكوبون بنجاح');
    }

    /**
     * إزالة الكوبون
     */
    public function removeCoupon(Request $request)
    {
        $request->session()->forget('applied_coupon');

        return back()->with('status', 'تم إزالة الكوبون');
    }

    public function store(CheckoutRequest $request)
    {
        $cart = $this->getCart($request);

        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        // التحقق من صحة السلة
        $cart = $this->validateCart($cart);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'السلة فارغة أو المنتجات غير متوفرة');
        }

        $validated = $request->validated();

        if ($request->boolean('shipping_same_as_billing')) {
            $validated['shipping_first_name'] = $validated['billing_first_name'];
            $validated['shipping_last_name'] = $validated['billing_last_name'];
            $validated['shipping_email'] = $validated['billing_email'] ?? $validated['customer_email'];
            $validated['shipping_phone'] = $validated['billing_phone'];
            $validated['shipping_address_line1'] = $validated['billing_address_line1'];
            $validated['shipping_address_line2'] = $validated['billing_address_line2'];
            $validated['shipping_city'] = $validated['billing_city'];
            $validated['shipping_state'] = $validated['billing_state'];
            $validated['shipping_postal_code'] = $validated['billing_postal_code'];
            $validated['shipping_country_code'] = $validated['billing_country_code'];
        }

        $subtotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        $minOrder = $this->getMinOrderValue($request);
        if ($minOrder > 0 && $subtotal < $minOrder) {
            return redirect()->route('cart.index')
                ->with('error', 'الحد الأدنى لقيمة الطلب هو ' . number_format($minOrder, 2) . ' ج.م');
        }

        // معالجة الكوبون
        $coupon = null;
        $couponDiscount = 0;
        $appliedCoupon = $request->session()->get('applied_coupon');

        if ($appliedCoupon) {
            $coupon = Coupon::find($appliedCoupon['id']);
            if ($coupon) {
                $validation = $coupon->isValid($request->user()?->id, $subtotal);
                if ($validation['valid']) {
                    $couponDiscount = $coupon->calculateDiscount($subtotal);
                } else {
                    $coupon = null;
                }
            }
        }

        $shippingTotal = 0;
        $shippingMethodName = null;
        
        $shippingCity = $validated['shipping_city'] ?? $validated['billing_city'];
        $shippingCountry = $validated['shipping_country_code'] ?? $validated['billing_country_code'] ?? 'EG';
        
        // Calculate weight
        $weight = collect($cart)->sum(fn ($item) => ($item['weight'] ?? 0) * $item['quantity']);

        $availableMethods = $this->getAvailableShippingMethods($shippingCity, $shippingCountry, $subtotal, $weight);
        
        $selectedMethodId = $request->input('shipping_method');
        $selectedMethod = null;

        if ($selectedMethodId) {
             $selectedMethod = $availableMethods->first(function($m) use ($selectedMethodId) {
                 return (string)$m->id === (string)$selectedMethodId;
             });
        }
        
        // If no valid method selected but methods exist, default to first
        if (!$selectedMethod && $availableMethods->isNotEmpty()) {
            $selectedMethod = $availableMethods->first();
        }

        if ($selectedMethod) {
            $shippingTotal = $selectedMethod->cost;
            $shippingMethodName = $selectedMethod->name_ar ?? $selectedMethod->name_en ?? 'Standard Shipping';
        }

        $discountTotal = $couponDiscount;
        $taxTotal = 0;
        $grandTotal = $subtotal + $shippingTotal - $discountTotal + $taxTotal;

        $order = DB::transaction(function () use ($validated, $cart, $subtotal, $shippingTotal, $discountTotal, $taxTotal, $grandTotal, $request, $coupon, $couponDiscount, $shippingMethodName) {
            $order = new Order;

            $order->order_number = $this->generateOrderNumber();
            $order->status = Order::STATUS_PENDING;
            $order->payment_status = Order::PAYMENT_STATUS_UNPAID;
            $order->shipping_status = Order::SHIPPING_STATUS_PENDING;
            $order->payment_method = $validated['payment_method'];
            $order->currency = 'EGP';
            $order->subtotal = $subtotal;
            $order->shipping_total = $shippingTotal;
            $order->discount_total = $discountTotal;
            $order->tax_total = $taxTotal;
            $order->grand_total = $grandTotal;

            // حفظ بيانات الكوبون
            if ($coupon) {
                $order->coupon_id = $coupon->id;
                $order->coupon_code = $coupon->code;
                $order->coupon_discount = $couponDiscount;
            }

            $order->customer_name = $validated['customer_name'];
            $order->customer_email = $validated['customer_email'];
            $order->customer_phone = $validated['customer_phone'];

            $order->billing_first_name = $validated['billing_first_name'];
            $order->billing_last_name = $validated['billing_last_name'];
            $order->billing_email = $validated['billing_email'];
            $order->billing_phone = $validated['billing_phone'];
            $order->billing_address_line1 = $validated['billing_address_line1'];
            $order->billing_address_line2 = $validated['billing_address_line2'];
            $order->billing_city = $validated['billing_city'];
            $order->billing_state = $validated['billing_state'];
            $order->billing_postal_code = $validated['billing_postal_code'];
            $order->billing_country_code = $validated['billing_country_code'];

            $order->shipping_first_name = $validated['shipping_first_name'] ?? null;
            $order->shipping_last_name = $validated['shipping_last_name'] ?? null;
            $order->shipping_email = $validated['shipping_email'] ?? null;
            $order->shipping_phone = $validated['shipping_phone'] ?? null;
            $order->shipping_address_line1 = $validated['shipping_address_line1'] ?? null;
            $order->shipping_address_line2 = $validated['shipping_address_line2'] ?? null;
            $order->shipping_city = $validated['shipping_city'] ?? null;
            $order->shipping_state = $validated['shipping_state'] ?? null;
            $order->shipping_postal_code = $validated['shipping_postal_code'] ?? null;
            $order->shipping_country_code = $validated['shipping_country_code'] ?? null;
            $order->shipping_method = $shippingMethodName;

            $order->customer_notes = $validated['customer_notes'] ?? null;

            if ($request->user()) {
                $order->user_id = $request->user()->id;
            }

            $order->save();

            // حفظ عناصر الطلب
            foreach ($cart as $item) {
                $product = Product::query()->find($item['product_id']);

                if (! $product) {
                    continue;
                }

                $variantId = $item['variant_id'] ?? null;
                $variant = $variantId ? ProductVariant::find($variantId) : null;

                $orderItem = new OrderItem;
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $product->id;
                $orderItem->variant_id = $variantId;
                $orderItem->product_name = $product->name_en ?? $product->name_ar;
                $orderItem->product_sku = $variant ? $variant->sku : $product->sku;
                $orderItem->quantity = $item['quantity'];
                $orderItem->unit_price = $item['price'];
                $orderItem->discount_amount = 0;
                $orderItem->total = $item['price'] * $item['quantity'];

                // Save variant info in options for record keeping
                if ($variant) {
                    $orderItem->options = json_encode([
                        'variant_id' => $variant->id,
                        'variant_label' => $variant->label,
                        'attributes' => $variant->attribute_combination,
                    ]);
                }

                $orderItem->save();

                // تحديث المخزون
                if ($variant) {
                    $variant->decrement('stock_quantity', $item['quantity']);
                    // Sync parent product stock
                    $totalStock = $product->variants()->where('is_active', true)->sum('stock_quantity');
                    $product->stock_quantity = $totalStock;
                } else {
                    $product->decrement('stock_quantity', $item['quantity']);
                }

                if ($product->stock_quantity <= 0) {
                    $product->stock_status = 'out_of_stock';
                } elseif ($product->stock_quantity <= $product->low_stock_threshold) {
                    $product->stock_status = 'low_stock';
                }

                $product->save();
            }

            // تسجيل استخدام الكوبون
            if ($coupon) {
                CouponUsage::create([
                    'coupon_id' => $coupon->id,
                    'user_id' => $request->user()?->id,
                    'order_id' => $order->id,
                    'used_at' => now(),
                ]);

                $coupon->incrementUsage();
            }

            return $order;
        });

        // إنشاء إشعار للطلب الجديد
        AdminNotification::createOrderNotification($order);

        // مسح السلة والكوبون
        $request->session()->forget(['cart', 'applied_coupon']);
        $request->session()->put('last_order_number', $order->order_number);

        return redirect()->route('orders.show', ['order' => $order->order_number]);
    }

    protected function getMinOrderValue(Request $request): float
    {
        $location = $this->isInsideAssiut($request) ? 'inside' : 'outside';
        $defaultMin = $location === 'inside' ? 50 : 500;

        $key = $location === 'inside'
            ? 'cart_min_order_inside_assiut'
            : 'cart_min_order_outside_assiut';

        $value = Setting::getValue($key, $defaultMin);
        return is_numeric($value) ? (float) $value : (float) $defaultMin;
    }

    protected function isInsideAssiut(Request $request): bool
    {
        if ($request->user()?->city) {
            $city = $request->user()->city;
            return str_contains(strtolower($city), 'assiut') || str_contains($city, 'أسيوط');
        }

        return session('user_location', 'inside_assiut') === 'inside_assiut';
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
        $productIds = collect($cart)->pluck('product_id')->toArray();

        if (empty($productIds)) {
            return [];
        }

        $products = Product::query()
            ->whereIn('id', $productIds)
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        // Collect variant IDs from cart
        $variantIds = collect($cart)->pluck('variant_id')->filter()->unique()->toArray();
        $variants = [];
        if (!empty($variantIds)) {
            $variants = ProductVariant::whereIn('id', $variantIds)
                ->where('is_active', true)
                ->get()
                ->keyBy('id');
        }

        $validatedCart = [];

        foreach ($cart as $key => $item) {
            $product = $products->get($item['product_id']);

            if (! $product) {
                continue;
            }

            $variantId = $item['variant_id'] ?? null;

            if ($variantId) {
                $variant = $variants[$variantId] ?? null;
                if (!$variant || $variant->product_id !== $product->id) {
                    continue;
                }

                $item['price'] = $variant->final_price;
                $item['variant_label'] = $variant->label;
                $availableStock = $variant->stock_quantity;
            } else {
                $item['price'] = (float) ($product->sale_price ?? $product->price);
                $availableStock = $product->stock_quantity;
            }

            $item['name'] = $product->name_en ?? $product->name_ar;

            // التحقق من الكمية المتوفرة
            if ($availableStock > 0) {
                $item['quantity'] = min($item['quantity'], $availableStock);
                $validatedCart[$key] = $item;
            }
        }

        return $validatedCart;
    }

    protected function generateOrderNumber(): string
    {
        return strtoupper('SD'.now()->format('ymd').Str::random(5));
    }

    /**
     * حساب تكلفة الشحن
     */
    public function calculateShipping(Request $request)
    {
        $request->validate([
            'city' => 'nullable|string',
            'country' => 'nullable|string',
        ]);

        $cart = $this->getCart($request);
        if (empty($cart)) {
             return response()->json(['methods' => []]);
        }

        $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
        $weight = collect($cart)->sum(fn ($item) => ($item['weight'] ?? 0) * $item['quantity']);

        $city = $request->input('city');
        $country = $request->input('country', 'EG');

        $methods = $this->getAvailableShippingMethods($city, $country, $subtotal, $weight);

        $availableMethods = $methods->map(function ($method) {
            return [
                'id' => $method->id,
                'label' => $method->name . ' (' . ($method->cost > 0 ? number_format($method->cost, 2) . ' ج.م' : 'مجاني') . ')',
                'cost' => $method->cost,
                'value' => $method->id,
                'description' => $method->description_ar,
                'delivery_time' => $method->delivery_time,
            ];
        })->values();

        return response()->json([
            'methods' => $availableMethods
        ]);
    }

    private function getAvailableShippingMethods($city, $country, $subtotal, $weight = 0)
    {
        $zones = ShippingZone::active()->ordered()->get();
        $matchedZone = null;

        foreach ($zones as $zone) {
            // Check country match if countries are defined
            if (!empty($zone->countries) && !in_array($country, $zone->countries)) {
                continue;
            }
            
            // Check city match if cities are defined
            if (!empty($zone->cities)) {
                 // Check if city matches any city in the list (case insensitive/trim)
                 $cityMatch = false;
                 foreach ($zone->cities as $zoneCity) {
                     if (mb_strtolower(trim($zoneCity)) === mb_strtolower(trim($city))) {
                         $cityMatch = true;
                         break;
                     }
                 }
                 if (!$cityMatch) {
                     continue;
                 }
            }

            $matchedZone = $zone;
            break; 
        }

        if (!$matchedZone) {
            // Check global settings as fallback
            if (\App\Models\Setting::getValue('shipping_enabled', true)) {
                $globalCost = \App\Models\Setting::getValue('shipping_cost', 0);
                $freeThreshold = \App\Models\Setting::getValue('free_shipping_threshold');
                
                $cost = $globalCost;
                if ($freeThreshold && $subtotal >= $freeThreshold) {
                    $cost = 0;
                }

                // Create a dummy method for global shipping
                $globalMethod = new ShippingMethod();
                $globalMethod->id = 'global'; // String ID to distinguish
                $globalMethod->name_ar = 'شحن قياسي';
                $globalMethod->name_en = 'Standard Shipping';
                $globalMethod->cost = $cost;
                $globalMethod->type = 'flat_rate';
                
                return collect([$globalMethod]);
            }
            return collect([]);
        }

        return $matchedZone->shippingMethods()
            ->active()
            ->ordered()
            ->get()
            ->filter(function ($method) use ($subtotal, $weight) {
                if ($method->type === 'free_shipping') {
                    if ($method->free_shipping_threshold > 0 && $subtotal < $method->free_shipping_threshold) {
                        return false;
                    }
                }
                if ($method->type === 'weight_based') {
                    if ($weight < $method->min_weight || ($method->max_weight > 0 && $weight > $method->max_weight)) {
                        return false;
                    }
                }
                return true;
            })
            ->map(function ($method) use ($subtotal) {
                // Adjust cost for free shipping type
                if ($method->type === 'free_shipping') {
                    $method->cost = 0;
                }
                return $method;
            });
    }
}
