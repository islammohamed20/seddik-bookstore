<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductAttributeController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductAttribute::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_ar', 'like', "%{$search}%")
                    ->orWhere('name_en', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $attributes = $query->orderBy('sort_order')->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.product-attributes.index', compact('attributes'));
    }

    public function create()
    {
        return view('admin.product-attributes.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateAttribute($request);

        $validated['slug'] = $this->generateUniqueSlug($validated['name_ar']);
        $validated['options'] = $this->parseOptions($validated['options'] ?? null);
        $validated['is_active'] = $request->boolean('is_active');

        ProductAttribute::create($validated);

        return redirect()
            ->route('admin.product-attributes.index')
            ->with('success', 'تم إضافة الخاصية بنجاح');
    }

    public function edit(ProductAttribute $productAttribute)
    {
        return view('admin.product-attributes.edit', [
            'attribute' => $productAttribute,
        ]);
    }

    public function update(Request $request, ProductAttribute $productAttribute)
    {
        $validated = $this->validateAttribute($request, $productAttribute->id);

        if ($productAttribute->name_ar !== $validated['name_ar']) {
            $validated['slug'] = $this->generateUniqueSlug($validated['name_ar'], $productAttribute->id);
        }

        $validated['options'] = $this->parseOptions($validated['options'] ?? null);
        $validated['is_active'] = $request->boolean('is_active');

        $productAttribute->update($validated);

        return redirect()
            ->route('admin.product-attributes.edit', $productAttribute)
            ->with('success', 'تم تحديث الخاصية بنجاح');
    }

    public function destroy(ProductAttribute $productAttribute)
    {
        $productAttribute->delete();

        return redirect()
            ->route('admin.product-attributes.index')
            ->with('success', 'تم حذف الخاصية بنجاح');
    }

    public function toggleStatus(ProductAttribute $productAttribute)
    {
        $productAttribute->update(['is_active' => ! $productAttribute->is_active]);

        return redirect()
            ->route('admin.product-attributes.index')
            ->with('success', 'تم تحديث حالة الخاصية');
    }

    private function validateAttribute(Request $request, ?int $ignoreId = null): array
    {
        $uniqueRule = 'unique:product_attributes,slug';
        if ($ignoreId) {
            $uniqueRule .= ',' . $ignoreId;
        }

        return $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', $uniqueRule],
            'input_type' => 'required|in:select,radio,checkbox',
            'options' => 'required_if:input_type,select,radio,checkbox|nullable|string',
            'validation_rules' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);
    }

    private function parseOptions(?string $raw): ?array
    {
        if ($raw === null || trim($raw) === '') {
            return null;
        }

        $options = collect(explode(',', $raw))
            ->map(fn ($val) => trim($val))
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        return empty($options) ? null : $options;
    }

    private function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        if ($slug === '') {
            $slug = 'attribute';
        }

        $original = $slug;
        $counter = 1;

        $query = ProductAttribute::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $original . '-' . $counter++;
            $query = ProductAttribute::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $slug;
    }
}
