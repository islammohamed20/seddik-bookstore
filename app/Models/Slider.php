<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_ar',
        'title_color_ar',
        'title_en',
        'subtitle_ar',
        'subtitle_color_ar',
        'subtitle_en',
        'image',
        'mobile_image',
        'button_text_ar',
        'button_text_en',
        'button_url',
        'open_in_new_tab',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'open_in_new_tab' => 'boolean',
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getTitleAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->title_ar : ($this->title_en ?? $this->title_ar);
    }

    public function getSubtitleAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->subtitle_ar : ($this->subtitle_en ?? $this->subtitle_ar);
    }

    public function getButtonTextAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->button_text_ar : ($this->button_text_en ?? $this->button_text_ar);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        return Storage::url($this->image);
    }

    public function getMobileImageUrlAttribute(): ?string
    {
        if (! $this->mobile_image) {
            return $this->image_url;
        }

        if (str_starts_with($this->mobile_image, 'http')) {
            return $this->mobile_image;
        }

        return Storage::url($this->mobile_image);
    }
}
