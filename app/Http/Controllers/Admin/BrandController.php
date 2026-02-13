<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $query = Brand::withCount('products');

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

        $brands = $query->orderBy('sort_order')->orderBy('name_ar')->paginate(15)->withQueryString();

        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data = $validated;
        $data['slug'] = Str::slug($validated['name_ar']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $validated['sort_order'] ?? 0;

        $originalSlug = $data['slug'];
        $counter = 1;
        while (Brand::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug.'-'.$counter++;
        }

        // Handle logo
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('brands', 'public');
        }

        Brand::create($data);

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'تم إضافة العلامة التجارية بنجاح');
    }

    public function show(Brand $brand)
    {
        $brand->load('products');

        return view('admin.brands.show', compact('brand'));
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'website' => 'nullable|url|max:255',
            'is_active' => 'boolean',
        ]);

        $data = $validated;
        $data['name_ar'] = $validated['name'];
        $data['name_en'] = $validated['name'];
        $data['description_ar'] = $validated['description'] ?? null;
        $data['description_en'] = $validated['description'] ?? null;
        unset($data['name'], $data['description'], $data['website']);

        // Update slug if name changed
        if ($brand->name !== $validated['name']) {
            $data['slug'] = Str::slug($validated['name']);

            $originalSlug = $data['slug'];
            $counter = 1;
            while (Brand::where('slug', $data['slug'])->where('id', '!=', $brand->id)->exists()) {
                $data['slug'] = $originalSlug.'-'.$counter++;
            }
        }

        $data['is_active'] = $request->boolean('is_active', true);

        // Handle logo
        if ($request->hasFile('logo')) {
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            $data['logo'] = $request->file('logo')->store('brands', 'public');
        }

        $brand->update($data);

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'تم تحديث العلامة التجارية بنجاح');
    }

    public function destroy(Brand $brand)
    {
        if ($brand->products()->exists()) {
            return back()->with('error', 'لا يمكن حذف العلامة التجارية لأنها تحتوي على منتجات');
        }

        if ($brand->logo) {
            Storage::disk('public')->delete($brand->logo);
        }

        $brand->delete();

        return redirect()
            ->route('admin.brands.index')
            ->with('success', 'تم حذف العلامة التجارية بنجاح');
    }

    public function toggleStatus(Brand $brand)
    {
        $brand->update(['is_active' => ! $brand->is_active]);

        return back()->with('success', 'تم تحديث حالة العلامة التجارية');
    }
}
