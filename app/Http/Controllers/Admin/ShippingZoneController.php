<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingZone;
use Illuminate\Http\Request;

class ShippingZoneController extends Controller
{
    public function index(Request $request)
    {
        $query = ShippingZone::withCount('shippingMethods');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_ar', 'like', "%{$search}%")
                    ->orWhere('name_en', 'like', "%{$search}%");
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $zones = $query->ordered()->paginate(15)->withQueryString();

        return view('admin.shipping-zones.index', compact('zones'));
    }

    public function create()
    {
        return view('admin.shipping-zones.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'countries' => 'nullable|array',
            'cities' => 'nullable|array',
            'min_order_value' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        ShippingZone::create($validated);

        return redirect()
            ->route('admin.shipping-zones.index')
            ->with('success', 'تم إضافة منطقة الشحن بنجاح');
    }

    public function show(ShippingZone $shippingZone)
    {
        $shippingZone->load(['shippingMethods' => function ($query) {
            $query->ordered();
        }]);

        return view('admin.shipping-zones.show', compact('shippingZone'));
    }

    public function edit(ShippingZone $shippingZone)
    {
        return view('admin.shipping-zones.edit', compact('shippingZone'));
    }

    public function update(Request $request, ShippingZone $shippingZone)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'countries' => 'nullable|array',
            'cities' => 'nullable|array',
            'min_order_value' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $shippingZone->update($validated);

        return redirect()
            ->route('admin.shipping-zones.index')
            ->with('success', 'تم تحديث منطقة الشحن بنجاح');
    }

    public function destroy(ShippingZone $shippingZone)
    {
        if ($shippingZone->shippingMethods()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف منطقة شحن تحتوي على طرق شحن');
        }

        $shippingZone->delete();

        return redirect()
            ->route('admin.shipping-zones.index')
            ->with('success', 'تم حذف منطقة الشحن بنجاح');
    }

    public function toggleStatus(ShippingZone $shippingZone)
    {
        $shippingZone->update(['is_active' => !$shippingZone->is_active]);

        return back()->with('success', 'تم تحديث حالة منطقة الشحن');
    }
}
