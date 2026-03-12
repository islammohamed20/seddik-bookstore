<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title_ar',
        'title_en',
        'content_ar',
        'content_en',
        'seo_title_ar',
        'seo_title_en',
        'seo_description_ar',
        'seo_description_en',
        'seo_keywords',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function getTitleAttribute(): string
    {
        $locale = app()->getLocale();

        if ($locale === 'ar') {
            return $this->title_ar ?: $this->title_en;
        }

        return $this->title_en ?: $this->title_ar;
    }

    public function getContentAttribute(): ?string
    {
        $locale = app()->getLocale();

        if ($locale === 'ar') {
            return $this->content_ar ?: $this->content_en;
        }

        return $this->content_en ?: $this->content_ar;
    }

    public function getMetaTitleAttribute(): ?string
    {
        $locale = app()->getLocale();

        if ($locale === 'ar') {
            return $this->seo_title_ar ?: $this->seo_title_en;
        }

        return $this->seo_title_en ?: $this->seo_title_ar;
    }

    public function getMetaDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();

        if ($locale === 'ar') {
            return $this->seo_description_ar ?: $this->seo_description_en;
        }

        return $this->seo_description_en ?: $this->seo_description_ar;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }
}
