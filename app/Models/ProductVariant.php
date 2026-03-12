<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'attribute_combination',
        'sku',
        'price',
        'sale_price',
        'stock_quantity',
        'weight',
        'is_active',
        'image',
        'sort_order',
    ];

    protected $casts = [
        'attribute_combination' => 'array',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'weight' => 'decimal:3',
        'stock_quantity' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attributeValues()
    {
        return $this->hasMany(ProductVariantAttribute::class, 'product_variant_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Get the final price (sale_price if set, otherwise price, fallback to product price)
     */
    public function getFinalPriceAttribute(): float
    {
        if ($this->sale_price && $this->sale_price > 0) {
            return (float) $this->sale_price;
        }

        if ($this->price && $this->price > 0) {
            return (float) $this->price;
        }

        // Fallback to parent product price
        return (float) ($this->product?->final_price ?? 0);
    }

    /**
     * Get the display price (original price before discount)
     */
    public function getDisplayPriceAttribute(): float
    {
        if ($this->price && $this->price > 0) {
            return (float) $this->price;
        }

        return (float) ($this->product?->price ?? 0);
    }

    /**
     * Check if variant has a discount
     */
    public function getHasDiscountAttribute(): bool
    {
        return $this->sale_price && $this->sale_price > 0 && $this->sale_price < $this->display_price;
    }

    /**
     * Get discount percentage
     */
    public function getDiscountPercentageAttribute(): ?int
    {
        if (!$this->has_discount) {
            return null;
        }

        return (int) round((($this->display_price - $this->sale_price) / $this->display_price) * 100);
    }

    /**
     * Check if variant is in stock
     */
    public function getIsInStockAttribute(): bool
    {
        return $this->is_active && $this->stock_quantity > 0;
    }

    /**
     * Get a human-readable label for the attribute combination
     * e.g. "أحمر / XL"
     */
    public function getLabelAttribute(): string
    {
        if (empty($this->attribute_combination)) {
            return '';
        }

        return implode(' / ', array_values($this->attribute_combination));
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('is_active', true)->where('stock_quantity', '>', 0);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Check if this variant matches a given attribute combination
     * @param array $attributes e.g. ['اللون' => 'أحمر', 'المقاس' => 'XL']
     */
    public function matchesAttributes(array $attributes): bool
    {
        $combo = $this->attribute_combination ?? [];

        if (count($combo) !== count($attributes)) {
            return false;
        }

        foreach ($attributes as $key => $value) {
            if (!isset($combo[$key]) || $combo[$key] !== $value) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get image URL (variant image or fallback to product primary image)
     */
    public function getImageUrlAttribute(): ?string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }

        return $this->product?->primary_image
            ? asset('storage/' . $this->product->primary_image->path)
            : null;
    }
}
