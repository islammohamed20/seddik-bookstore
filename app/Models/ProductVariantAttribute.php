<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantAttribute extends Model
{
    protected $fillable = [
        'product_variant_id',
        'product_attribute_id',
        'value',
    ];

    protected $casts = [
        'product_variant_id' => 'integer',
        'product_attribute_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function attribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'product_attribute_id');
    }
}

