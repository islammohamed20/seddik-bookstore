<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use App\Models\ShippingZone;
use Illuminate\Http\Request;

class ShippingMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect()->route('admin.shipping-zones.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $shippingZone = null;
        if ($request->has('shipping_zone_id')) {
            $shippingZone = ShippingZone::findOrFail($request->shipping_zone_id);
        }

        if (!$shippingZone) {
            return redirect()->route('admin.shipping-zones.index')
                ->with('error', 'يجب اختيار منطقة الشحن أولاً');
        }

        return view('admin.shipping-methods.create', compact('shippingZone'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_zone_id' => 'required|exists:shipping_zones,id',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'type' => 'required|in:flat_rate,weight_based,free_shipping,pickup',
            'cost' => 'required_if:type,flat_rate,weight_based|nullable|numeric|min:0',
            'min_weight' => 'nullable|numeric|min:0',
            'max_weight' => 'nullable|numeric|gt:min_weight',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'delivery_time_min' => 'nullable|integer|min:0',
            'delivery_time_max' => 'nullable|integer|gte:delivery_time_min',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');

        ShippingMethod::create($validated);

        return redirect()->route('admin.shipping-zones.show', $validated['shipping_zone_id'])
            ->with('success', 'تم إضافة طريقة الشحن بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShippingMethod $shippingMethod)
    {
        return view('admin.shipping-methods.edit', compact('shippingMethod'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShippingMethod $shippingMethod)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'type' => 'required|in:flat_rate,weight_based,free_shipping,pickup',
            'cost' => 'required_if:type,flat_rate,weight_based|nullable|numeric|min:0',
            'min_weight' => 'nullable|numeric|min:0',
            'max_weight' => 'nullable|numeric|gt:min_weight',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'delivery_time_min' => 'nullable|integer|min:0',
            'delivery_time_max' => 'nullable|integer|gte:delivery_time_min',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $shippingMethod->update($validated);

        return redirect()->route('admin.shipping-methods.edit', $shippingMethod)
            ->with('success', 'تم تحديث طريقة الشحن بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShippingMethod $shippingMethod)
    {
        $zoneId = $shippingMethod->shipping_zone_id;
        $shippingMethod->delete();

        return redirect()->route('admin.shipping-zones.show', $zoneId)
            ->with('success', 'تم حذف طريقة الشحن بنجاح');
    }

    public function toggleStatus(ShippingMethod $shippingMethod)
    {
        $shippingMethod->update(['is_active' => !$shippingMethod->is_active]);

        return back()->with('success', 'تم تغيير حالة طريقة الشحن بنجاح');
    }
}
