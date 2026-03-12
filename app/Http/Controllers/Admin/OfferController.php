<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        $query = Offer::withCount('products');

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

        $offers = $query->latest()->paginate(15)->withQueryString();

        return view('admin.offers.index', compact('offers'));
    }

    public function create()
    {
        $products = Product::active()->get();

        return view('admin.offers.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:fixed,percent,free_shipping,percentage',
            'discount_value' => 'required_unless:discount_type,free_shipping|nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'banner_color_from' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'banner_color_to' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
        ]);

        $discountType = $validated['discount_type'] === 'percentage' ? 'percent' : $validated['discount_type'];
        $discountValue = $validated['discount_value'] ?? null;

        if ($discountType === 'percent' && $discountValue !== null && $discountValue > 100) {
            return back()
                ->withInput()
                ->withErrors(['discount_value' => 'نسبة الخصم لا يمكن أن تتجاوز 100%']);
        }

        $slugBase = \Illuminate\Support\Str::slug($validated['name']);
        $slug = $slugBase;
        $counter = 1;
        while (Offer::where('slug', $slug)->exists()) {
            $slug = $slugBase.'-'.$counter++;
        }

        $data = [
            'slug' => $slug,
            'name_ar' => $validated['name'],
            'name_en' => $validated['name'],
            'description_ar' => $validated['description'] ?? null,
            'description_en' => $validated['description'] ?? null,
            'discount_type' => $discountType,
            'discount_value' => $discountValue,
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured', false),
            'sort_order' => $validated['sort_order'] ?? 0,
            'banner_color_from' => $validated['banner_color_from'] ?? null,
            'banner_color_to' => $validated['banner_color_to'] ?? null,
        ];

        if ($request->hasFile('image')) {
            $data['banner_image'] = $request->file('image')->store('offers', 'public');
        }

        $offer = Offer::create($data);

        // Attach products
        if (! empty($validated['products'])) {
            $offer->products()->attach($validated['products']);
        }

        return redirect()
            ->route('admin.offers.index')
            ->with('success', 'تم إضافة العرض بنجاح');
    }

    public function show(Offer $offer)
    {
        $offer->load('products');

        return view('admin.offers.show', compact('offer'));
    }

    public function edit(Offer $offer)
    {
        $offer->load('products');
        $products = Product::active()->get();

        return view('admin.offers.edit', compact('offer', 'products'));
    }

    public function update(Request $request, Offer $offer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:fixed,percent,free_shipping,percentage',
            'discount_value' => 'required_unless:discount_type,free_shipping|nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'banner_color_from' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'banner_color_to' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
        ]);

        $discountType = $validated['discount_type'] === 'percentage' ? 'percent' : $validated['discount_type'];
        $discountValue = $validated['discount_value'] ?? null;

        if ($discountType === 'percent' && $discountValue !== null && $discountValue > 100) {
            return back()
                ->withInput()
                ->withErrors(['discount_value' => 'نسبة الخصم لا يمكن أن تتجاوز 100%']);
        }

        $data = [
            'name_ar' => $validated['name'],
            'name_en' => $validated['name'],
            'description_ar' => $validated['description'] ?? null,
            'description_en' => $validated['description'] ?? null,
            'discount_type' => $discountType,
            'discount_value' => $discountValue,
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured', false),
            'sort_order' => $validated['sort_order'] ?? 0,
            'banner_color_from' => $validated['banner_color_from'] ?? null,
            'banner_color_to' => $validated['banner_color_to'] ?? null,
        ];

        if ($request->hasFile('image')) {
            if ($offer->banner_image) {
                Storage::disk('public')->delete($offer->banner_image);
            }
            $data['banner_image'] = $request->file('image')->store('offers', 'public');
        }

        $offer->update($data);

        // Sync products
        $offer->products()->sync($validated['products'] ?? []);

        return redirect()
            ->route('admin.offers.edit', $offer)
            ->with('success', 'تم تحديث العرض بنجاح');
    }

    public function destroy(Offer $offer)
    {
        if ($offer->banner_image) {
            Storage::disk('public')->delete($offer->banner_image);
        }

        $offer->products()->detach();
        $offer->delete();

        return redirect()
            ->route('admin.offers.index')
            ->with('success', 'تم حذف العرض بنجاح');
    }

    public function toggleStatus(Offer $offer)
    {
        $offer->update(['is_active' => ! $offer->is_active]);

        return back()->with('success', 'تم تحديث حالة العرض');
    }
}
