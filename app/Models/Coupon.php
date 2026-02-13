<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    public const TYPE_PERCENT = 'percent';

    public const TYPE_FIXED = 'fixed';

    public const TYPE_FREE_SHIPPING = 'free_shipping';

    protected $fillable = [
        'code',
        'name',
        'description',
        'discount_type',
        'discount_value',
        'max_discount_amount',
        'min_order_amount',
        'max_uses',
        'max_uses_per_user',
        'used_count',
        'starts_at',
        'ends_at',
        'is_active',
        'is_public',
        'restrictions',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_uses' => 'integer',
        'max_uses_per_user' => 'integer',
        'used_count' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
        'is_public' => 'boolean',
        'restrictions' => 'array',
    ];

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
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

    public function scopeByCode(Builder $query, string $code): Builder
    {
        return $query->where('code', strtoupper($code));
    }

    /*
    |--------------------------------------------------------------------------
    | Validation Methods
    |--------------------------------------------------------------------------
    */

    /**
     * التحقق من صلاحية الكوبون
     */
    public function isValid(?int $userId = null, float $orderTotal = 0): array
    {
        $errors = [];

        if (! $this->is_active) {
            $errors[] = 'الكوبون غير نشط';
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            $errors[] = 'الكوبون لم يبدأ بعد';
        }

        if ($this->ends_at && $this->ends_at->isPast()) {
            $errors[] = 'انتهت صلاحية الكوبون';
        }

        if ($this->max_uses && $this->used_count >= $this->max_uses) {
            $errors[] = 'تم استخدام الكوبون الحد الأقصى من المرات';
        }

        if ($this->min_order_amount && $orderTotal < $this->min_order_amount) {
            $errors[] = 'الحد الأدنى للطلب هو '.number_format($this->min_order_amount, 2).' ج.م';
        }

        if ($userId && $this->max_uses_per_user) {
            $userUsageCount = $this->usages()->where('user_id', $userId)->count();
            if ($userUsageCount >= $this->max_uses_per_user) {
                $errors[] = 'لقد استخدمت هذا الكوبون الحد الأقصى من المرات';
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * حساب قيمة الخصم
     */
    public function calculateDiscount(float $orderTotal): float
    {
        if ($this->discount_type === self::TYPE_FREE_SHIPPING) {
            return 0; // يتم التعامل معه بشكل منفصل
        }

        $discount = 0;

        if ($this->discount_type === self::TYPE_PERCENT) {
            $discount = $orderTotal * ($this->discount_value / 100);
        } elseif ($this->discount_type === self::TYPE_FIXED) {
            $discount = (float) $this->discount_value;
        }

        // تطبيق الحد الأقصى للخصم
        if ($this->max_discount_amount && $discount > $this->max_discount_amount) {
            $discount = (float) $this->max_discount_amount;
        }

        // التأكد من أن الخصم لا يتجاوز قيمة الطلب
        return min($discount, $orderTotal);
    }

    /**
     * زيادة عداد الاستخدام
     */
    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }
}
