<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TagOption extends Model
{
    protected $fillable = [
        'tag_group_id',
        'slug',
        'name_ar',
        'name_en',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function group()
    {
        return $this->belongsTo(TagGroup::class, 'tag_group_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tag_option');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}

