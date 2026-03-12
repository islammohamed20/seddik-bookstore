<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    /**
     * Store a new variant for a product
     */
    public function storeVariant(Product $product, array $data): ProductVariant
    {
        $variant = $product->variants()->create([
            'attribute_combination' => $data['attribute_combination'],
            'sku' => $data['sku'] ?? null,
            'price' => $data['price'] ?? $product->price,
            'sale_price' => $data['sale_price'] ?? null,
            'stock_quantity' => (int) ($data['stock_quantity'] ?? 0),
            'weight' => $data['weight'] ?? null,
            'is_active' => $data['is_active'] ?? true,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        // Handle variant image upload
        if (isset($data['image']) && $data['image']) {
            $path = $data['image']->store('products/variants', 'public');
            $variant->update(['image' => $path]);
        }

        // Ensure product is marked as variable
        $this->syncProductVariantFlags($product);

        return $variant;
    }

    /**
     * Update an existing variant
     */
    public function updateVariant(ProductVariant $variant, array $data): ProductVariant
    {
        $updateData = [
            'attribute_combination' => $data['attribute_combination'],
            'sku' => $data['sku'] ?? null,
            'price' => $data['price'] ?? $variant->product->price,
            'sale_price' => $data['sale_price'] ?? null,
            'stock_quantity' => (int) ($data['stock_quantity'] ?? 0),
            'weight' => $data['weight'] ?? null,
            'is_active' => $data['is_active'] ?? true,
            'sort_order' => $data['sort_order'] ?? 0,
        ];

        // Handle image upload
        if (isset($data['image']) && $data['image']) {
            // Delete old image
            if ($variant->image) {
                Storage::disk('public')->delete($variant->image);
            }
            $updateData['image'] = $data['image']->store('products/variants', 'public');
        }

        $variant->update($updateData);

        $this->syncProductVariantFlags($variant->product);

        return $variant;
    }

    /**
     * Delete a variant
     */
    public function deleteVariant(ProductVariant $variant): void
    {
        $product = $variant->product;

        if ($variant->image) {
            Storage::disk('public')->delete($variant->image);
        }

        $variant->delete();

        $this->syncProductVariantFlags($product);
    }

    /**
     * Sync product's has_variants flag and stock based on variants
     */
    public function syncProductVariantFlags(Product $product): void
    {
        $product->refresh();
        $activeVariantsCount = $product->variants()->where('is_active', true)->count();
        $hasVariants = $activeVariantsCount > 0;

        $updateData = ['has_variants' => $hasVariants];

        // For variable products, sync total stock from variants
        if ($product->product_type === 'variable' && $hasVariants) {
            $totalStock = $product->variants()->where('is_active', true)->sum('stock_quantity');
            $updateData['stock_quantity'] = $totalStock;
            $updateData['stock_status'] = match (true) {
                $totalStock <= 0 => 'out_of_stock',
                $totalStock <= ($product->low_stock_threshold ?? 5) => 'low_stock',
                default => 'in_stock',
            };
        }

        $product->update($updateData);
    }

    /**
     * Bulk save variants from form submission
     * Handles creating new, updating existing, and deleting removed variants
     */
    public function syncVariants(Product $product, array $variantsData): void
    {
        DB::transaction(function () use ($product, $variantsData) {
            $existingIds = $product->variants()->pluck('id')->toArray();
            $submittedIds = [];

            foreach ($variantsData as $data) {
                if (!empty($data['id']) && in_array($data['id'], $existingIds)) {
                    // Update existing variant
                    $variant = ProductVariant::find($data['id']);
                    if ($variant) {
                        $this->updateVariant($variant, $data);
                        $submittedIds[] = (int) $data['id'];
                    }
                } else {
                    // Create new variant
                    $variant = $this->storeVariant($product, $data);
                    $submittedIds[] = $variant->id;
                }
            }

            // Delete variants that were removed from the form
            $toDelete = array_diff($existingIds, $submittedIds);
            if (!empty($toDelete)) {
                $variants = ProductVariant::whereIn('id', $toDelete)->get();
                foreach ($variants as $variant) {
                    $this->deleteVariant($variant);
                }
            }

            $this->syncProductVariantFlags($product);
        });
    }
}
