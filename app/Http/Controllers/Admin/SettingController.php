<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $rules = [
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'site_email' => 'nullable|email|max:255',
            'site_phone' => 'nullable|string|max:20',
            'site_address' => 'nullable|string|max:500',
            'location_details' => 'nullable|string|max:1000',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'site_favicon' => 'nullable|image|mimes:ico,png|max:512',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'tiktok_url' => 'nullable|url|max:255',
            'whatsapp_number' => 'nullable|string|max:20',
            'google_maps_url' => 'nullable|url|max:500',
            'currency' => 'nullable|string|max:10',
            'currency_symbol' => 'nullable|string|max:5',
            'shipping_cost' => 'nullable|numeric|min:0',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'cart_min_order_inside_assiut' => 'nullable|numeric|min:0',
            'cart_min_order_outside_assiut' => 'nullable|numeric|min:0',
            'cart_max_items' => 'nullable|integer|min:1',
            'cart_max_qty_per_item' => 'nullable|integer|min:1',
            'cart_auto_clear_hours' => 'nullable|integer|min:1',
            'default_low_stock_threshold' => 'nullable|integer|min:0',
            'cart_allow_guest' => 'boolean',
            'cart_allow_notes' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'google_analytics_id' => 'nullable|string|max:50',
            'footer_text' => 'nullable|string|max:1000',
        ];

        $input = $request->except(['site_logo', 'site_favicon']);

        $nullableKeys = [
            'site_description',
            'site_email',
            'site_phone',
            'site_address',
            'location_details',
            'facebook_url',
            'twitter_url',
            'instagram_url',
            'youtube_url',
            'tiktok_url',
            'whatsapp_number',
            'google_maps_url',
            'currency',
            'currency_symbol',
            'shipping_cost',
            'free_shipping_threshold',
            'tax_rate',
            'cart_min_order_inside_assiut',
            'cart_min_order_outside_assiut',
            'cart_max_items',
            'cart_max_qty_per_item',
            'cart_auto_clear_hours',
            'default_low_stock_threshold',
            'meta_title',
            'meta_description',
            'meta_keywords',
            'google_analytics_id',
            'footer_text',
        ];

        foreach ($nullableKeys as $k) {
            if (array_key_exists($k, $input) && $input[$k] === '') {
                $input[$k] = null;
            }
        }

        $validated = validator($input, $rules)->validate();

        $validated['cart_allow_guest'] = $request->boolean('cart_allow_guest');
        $validated['cart_allow_notes'] = $request->boolean('cart_allow_notes');

        // Handle logo upload
        if ($request->hasFile('site_logo')) {
            $oldLogo = Setting::where('key', 'site_logo')->value('value');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
            $validated['site_logo'] = $request->file('site_logo')->store('settings', 'public');
        }

        // Handle favicon upload
        if ($request->hasFile('site_favicon')) {
            $oldFavicon = Setting::where('key', 'site_favicon')->value('value');
            if ($oldFavicon) {
                Storage::disk('public')->delete($oldFavicon);
            }
            $validated['site_favicon'] = $request->file('site_favicon')->store('settings', 'public');
        }

        foreach ($validated as $key => $value) {
            $stored = is_bool($value) ? ($value ? '1' : '0') : $value;
            if (in_array($key, ['site_logo', 'site_favicon']) && $stored === null) {
                continue;
            }
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $stored]
            );
        }

        return back()->with('success', 'تم حفظ الإعدادات بنجاح');
    }
}
