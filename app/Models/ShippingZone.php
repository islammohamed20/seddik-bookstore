<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ShippingZone extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'countries',
        'cities',
        'min_order_value',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'countries' => 'array',
        'cities' => 'array',
        'min_order_value' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function shippingMethods()
    {
        return $this->hasMany(ShippingMethod::class);
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
}
