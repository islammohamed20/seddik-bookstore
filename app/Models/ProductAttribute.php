<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttribute extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name_ar',
        'name_en',
        'slug',
        'input_type',
        'options',
        'validation_rules',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getDisplayNameAttribute(): string
    {
        return $this->name_ar ?: ($this->name_en ?? '');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
