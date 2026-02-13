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

        return app()->getLocale() === 'ar' ? ($setting->value_ar ?? $setting->value_en) : ($setting->value_en ?? $setting->value_ar);
    }
}
