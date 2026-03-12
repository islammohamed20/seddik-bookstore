<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;

class VariantResolver
{
    /**
     * Resolve a variant from a product given attribute selections.
     * Used by cart/checkout to find the correct variant.
     *
     * @param Product $product  The product (should have variants loaded)
     * @param array   $attributes  e.g. ['اللون' => 'أحمر', 'المقاس' => 'XL']
     * @return ProductVariant|null
     */
    public function resolve(Product $product, array $attributes): ?ProductVariant
    {
        if (!$product->has_variants || empty($attributes)) {
            return null;
        }

        $variants = $product->relationLoaded('variants')
            ? $product->variants
            : $product->variants()->active()->get();

        return $variants->first(fn (ProductVariant $v) => $v->matchesAttributes($attributes));
    }

    /**
     * Build a structured data array for the storefront variant selector.
     * This is embedded as JSON in the Blade template — no extra AJAX needed.
     *
     * @param Product $product  With variants loaded
     * @return array  Structure for Alpine.js to consume
     */
    public function buildStorefrontData(Product $product): array
    {
        if (!$product->has_variants) {
            return [];
        }

        $variants = $product->relationLoaded('variants')
            ? $product->variants->where('is_active', true)
            : $product->activeVariants()->get();

        if ($variants->isEmpty()) {
            return [];
        }

        // Extract all unique attribute names and their possible values
        $attributeOptions = [];
        foreach ($variants as $variant) {
            foreach ($variant->attribute_combination as $attrName => $attrValue) {
                if (!isset($attributeOptions[$attrName])) {
                    $attributeOptions[$attrName] = [];
                }
                if (!in_array($attrValue, $attributeOptions[$attrName])) {
                    $attributeOptions[$attrName][] = $attrValue;
                }
            }
        }

        // Build variant map for client-side matching
        $variantMap = $variants->map(fn (ProductVariant $v) => [
            'id' => $v->id,
            'attributes' => $v->attribute_combination,
            'price' => $v->final_price,
            'display_price' => $v->display_price,
            'has_discount' => $v->has_discount,
            'discount_percentage' => $v->discount_percentage,
            'stock' => $v->stock_quantity,
            'in_stock' => $v->is_in_stock,
            'sku' => $v->sku,
            'image' => $v->image_url,
            'label' => $v->label,
        ])->values()->toArray();

        return [
            'attributes' => $attributeOptions,
            'variants' => $variantMap,
        ];
    }

    /**
     * Generate a unique cart key for a product+variant combination
     */
    public function cartKey(int $productId, ?int $variantId = null): string
    {
        if ($variantId) {
            return $productId . '-v' . $variantId;
        }

        return (string) $productId;
    }

    /**
     * Parse a cart key back to product_id and variant_id
     */
    public function parseCartKey(string $key): array
    {
        if (str_contains($key, '-v')) {
            [$productId, $variantPart] = explode('-v', $key, 2);
            return [
                'product_id' => (int) $productId,
                'variant_id' => (int) $variantPart,
            ];
        }

        return [
            'product_id' => (int) $key,
            'variant_id' => null,
        ];
    }
}
