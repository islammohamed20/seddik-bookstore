<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if (! $setting) {
            return $default;
        }

        $locale = app()->getLocale();

        $valueAr = $setting->value_ar ?? null;
        $valueEn = $setting->value_en ?? null;
        $generic = $setting->value ?? null;

        if ($locale === 'ar') {
            return $valueAr ?? $valueEn ?? $generic ?? $default;
        }

        return $valueEn ?? $valueAr ?? $generic ?? $default;
    }
}
