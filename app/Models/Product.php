<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'slug',
        'sku',
        'barcode',
        'name_ar',
        'name_en',
        'subtitle_ar',
        'subtitle_en',
        'short_description_ar',
        'short_description_en',
        'description_ar',
        'description_en',
        'type',
        'product_type',
        'price',
        'sale_price',
        'old_price',
        'stock_quantity',
        'low_stock_threshold',
        'stock_status',
        'is_active',
        'is_featured',
        'is_bingo',
        'sort_order',
        'seo_title_ar',
        'seo_title_en',
        'seo_description_ar',
        'seo_description_en',
        'seo_keywords',
        'extra_attributes',
        'video_path',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'old_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_bingo' => 'boolean',
        'extra_attributes' => 'array',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function attributeValues()
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * الحصول على السعر النهائي (سعر العرض أو السعر الأصلي)
     */
    public function getFinalPriceAttribute(): float
    {
        return (float) ($this->sale_price ?? $this->price);
    }

    /**
     * الحصول على السعر بناءً على الموقع الجغرافي
     * @deprecated Use price/sale_price accessors instead
     */
    public function getPriceByLocationAttribute(): float
    {
        return $this->final_price;
    }

    /**
     * الحصول على السعر الأصلي بناءً على الموقع الجغرافي
     */
    public function getOriginalPriceByLocationAttribute(): float
    {
        return (float) $this->price;
    }

    /**
     * التحقق مما إذا كان المستخدم داخل محافظة أسيوط
     */
    public function isUserInsideAssiut(): bool
    {
        // 1. Check authenticated user's city
        $user = auth()->user();
        if ($user && $user->city) {
            return str_contains(strtolower($user->city), 'assiut') || 
                   str_contains($user->city, 'أسيوط');
        }
        
        // 2. Check session for guest users
        if (session()->has('user_location')) {
            return session('user_location') === 'inside_assiut';
        }
        
        // Default to inside Assiut (or outside, depending on business logic)
        // For now, let's default to inside Assiut as it's a local bookstore
        return true; 
    }

    /**
     * الحصول على نسبة الخصم بناءً على الموقع الجغرافي
     */
    public function getDiscountPercentageByLocationAttribute(): ?int
    {
        $originalPrice = $this->getOriginalPriceByLocationAttribute();
        $finalPrice = $this->getPriceByLocationAttribute();
        
        if ($finalPrice >= $originalPrice) {
            return null;
        }

        return (int) round((($originalPrice - $finalPrice) / $originalPrice) * 100);
    }

    /**
     * الحصول على نسبة الخصم
     */
    public function getDiscountPercentageAttribute(): ?int
    {
        if (! $this->sale_price || $this->sale_price >= $this->price) {
            return null;
        }

        return (int) round((($this->price - $this->sale_price) / $this->price) * 100);
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

    public function getStockAttribute(): int
    {
        return (int) $this->stock_quantity;
    }

    public function getTotalStockQuantityAttribute(): int
    {
        if ($this->product_type === 'variable') {
            if ($this->relationLoaded('variants')) {
                return (int) $this->variants->sum('stock_quantity');
            }

            return (int) $this->variants()->sum('stock_quantity');
        }

        return (int) $this->stock_quantity;
    }

    /**
     * التحقق من توفر المنتج
     */
    public function getIsAvailableAttribute(): bool
    {
        return $this->is_active && $this->stock_status !== 'out_of_stock';
    }

    /**
     * الحصول على الصورة الرئيسية
     */
    public function getPrimaryImageAttribute(): ?ProductImage
    {
        return $this->images->firstWhere('is_primary', true)
            ?? $this->images->first();
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

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock_status', '!=', 'out_of_stock');
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->active()->inStock();
    }

    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name_ar', 'like', "%{$search}%")
                ->orWhere('name_en', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%")
                ->orWhere('barcode', 'like', "%{$search}%");
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'offer_product');
    }

    public function tagOptions()
    {
        return $this->belongsToMany(TagOption::class, 'product_tag_option');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function approvedReviews()
    {
        return $this->reviews()->approved();
    }

    public function getAverageRatingAttribute(): float
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->approvedReviews()->count();
    }
}
