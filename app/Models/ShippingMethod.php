<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ShippingMethod extends Model
{
    protected $fillable = [
        'shipping_zone_id',
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'type',
        'cost',
        'min_weight',
        'max_weight',
        'free_shipping_threshold',
        'delivery_time_min',
        'delivery_time_max',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'min_weight' => 'decimal:2',
        'max_weight' => 'decimal:2',
        'free_shipping_threshold' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function shippingZone()
    {
        return $this->belongsTo(ShippingZone::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name_ar');
    }

    public function getNameAttribute(): string
    {
        return $this->name_ar ?: $this->name_en ?: '';
    }

    public function getTypeNameAttribute(): string
    {
        return match ($this->type) {
            'flat_rate' => 'سعر ثابت',
            'weight_based' => 'حسب الوزن',
            'free_shipping' => 'شحن مجاني',
            'pickup' => 'الاستلام من المتجر',
            default => $this->type,
        };
    }

    public function getDeliveryTimeAttribute(): ?string
    {
        if (!$this->delivery_time_min && !$this->delivery_time_max) {
            return null;
        }

        if ($this->delivery_time_min == $this->delivery_time_max) {
            return $this->delivery_time_min . ' يوم';
        }

        return $this->delivery_time_min . '-' . $this->delivery_time_max . ' أيام';
    }
}
