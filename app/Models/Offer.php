<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    public const TYPE_PERCENT = 'percent';

    public const TYPE_FIXED = 'fixed';

    public const TYPE_FREE_SHIPPING = 'free_shipping';

    protected $fillable = [
        'slug',
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'discount_type',
        'discount_value',
        'max_discount_amount',
        'min_order_amount',
        'starts_at',
        'ends_at',
        'is_active',
        'is_featured',
        'badge_label_ar',
        'badge_label_en',
        'banner_image',
        'banner_color_from',
        'banner_color_to',
        'sort_order',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'offer_product');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeValid(Builder $query): Builder
    {
        return $query->active()
            ->where(function ($q) {
                $q->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            });
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getIsValidAttribute(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        if ($this->ends_at && $this->ends_at->isPast()) {
            return false;
        }

        return true;
    }

    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();

        if ($locale === 'ar') {
            return $this->name_ar ?: $this->name_en;
        }

        return $this->name_en ?: $this->name_ar;
    }

    public function getDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();

        if ($locale === 'ar') {
            return $this->description_ar ?: $this->description_en;
        }

        return $this->description_en ?: $this->description_ar;
    }

    public function getImageAttribute(): ?string
    {
        return $this->banner_image;
    }

    /**
     * حساب قيمة الخصم للمنتج
     */
    public function calculateDiscount(float $price): float
    {
        if ($this->discount_type === self::TYPE_PERCENT) {
            $discount = $price * ($this->discount_value / 100);
        } else {
            $discount = (float) $this->discount_value;
        }

        if ($this->max_discount_amount && $discount > $this->max_discount_amount) {
            $discount = (float) $this->max_discount_amount;
        }

        return min($discount, $price);
    }
}
