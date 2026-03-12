<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount('products');

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

        $categories = $query->orderBy('sort_order')->paginate(15)->withQueryString();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->active()->get();

        return view('admin.categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|string|max:100',
            'color_start' => ['nullable','string','max:9','regex:/^#?[0-9A-Fa-f]{6}$/'],
            'color_end' => ['nullable','string','max:9','regex:/^#?[0-9A-Fa-f]{6}$/'],
            'banner_desktop' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'banner_mobile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $data = $validated;
        // Normalize colors to #RRGGBB
        foreach (['color_start','color_end'] as $c) {
            if (!empty($data[$c])) {
                $v = ltrim($data[$c], '#');
                $data[$c] = '#'.$v;
            }
        }
        $data['name_en'] = $data['name_en'] ?? $data['name_ar'];
        $data['slug'] = Str::slug($validated['name_ar']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured', false);
        $data['sort_order'] = $validated['sort_order'] ?? 0;

        // Ensure unique slug
        $originalSlug = $data['slug'];
        $counter = 1;
        while (Category::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug.'-'.$counter++;
        }

        // Handle banners
        if ($request->hasFile('banner_desktop')) {
            $data['banner_desktop'] = $request->file('banner_desktop')->store('categories/banners', 'public');
        }
        if ($request->hasFile('banner_mobile')) {
            $data['banner_mobile'] = $request->file('banner_mobile')->store('categories/banners', 'public');
        }

        Category::create($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'تم إضافة التصنيف بنجاح');
    }

    public function show(Category $category)
    {
        $category->load(['products', 'children']);

        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->active()
            ->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id|not_in:'.$category->id,
            'icon' => 'nullable|string|max:100',
            'color_start' => ['nullable','string','max:9','regex:/^#?[0-9A-Fa-f]{6}$/'],
            'color_end' => ['nullable','string','max:9','regex:/^#?[0-9A-Fa-f]{6}$/'],
            'banner_desktop' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'banner_mobile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $data = $validated;
        // Normalize colors to #RRGGBB
        foreach (['color_start','color_end'] as $c) {
            if (!empty($data[$c])) {
                $v = ltrim($data[$c], '#');
                $data[$c] = '#'.$v;
            }
        }
        $data['name_en'] = $data['name_en'] ?? $data['name_ar'];
        $data['is_featured'] = $request->boolean('is_featured', false);

        // Update slug if Arabic name changed
        if ($category->name_ar !== $validated['name_ar']) {
            $data['slug'] = Str::slug($validated['name_ar']);
            $originalSlug = $data['slug'];
            $counter = 1;
            while (Category::where('slug', $data['slug'])->where('id', '!=', $category->id)->exists()) {
                $data['slug'] = $originalSlug.'-'.$counter++;
            }
        }

        $data['is_active'] = $request->boolean('is_active', true);

        // Handle banners
        if ($request->hasFile('banner_desktop')) {
            if ($category->banner_desktop) {
                Storage::disk('public')->delete($category->banner_desktop);
            }
            $data['banner_desktop'] = $request->file('banner_desktop')->store('categories/banners', 'public');
        }
        if ($request->hasFile('banner_mobile')) {
            if ($category->banner_mobile) {
                Storage::disk('public')->delete($category->banner_mobile);
            }
            $data['banner_mobile'] = $request->file('banner_mobile')->store('categories/banners', 'public');
        }

        $category->update($data);

        return redirect()
            ->route('admin.categories.edit', $category)
            ->with('success', 'تم تحديث التصنيف بنجاح');
    }

    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->exists()) {
            return back()->with('error', 'لا يمكن حذف التصنيف لأنه يحتوي على منتجات');
        }

        // Check if category has children
        if ($category->children()->exists()) {
            return back()->with('error', 'لا يمكن حذف التصنيف لأنه يحتوي على تصنيفات فرعية');
        }

        // Delete banners
        if ($category->banner_desktop) {
            Storage::disk('public')->delete($category->banner_desktop);
        }
        if ($category->banner_mobile) {
            Storage::disk('public')->delete($category->banner_mobile);
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'تم حذف التصنيف بنجاح');
    }

    public function toggleStatus(Category $category)
    {
        $category->update(['is_active' => ! $category->is_active]);

        return back()->with('success', 'تم تحديث حالة التصنيف');
    }

    public function toggleFeatured(Category $category)
    {
        $category->update(['is_featured' => ! $category->is_featured]);

        return back()->with('success', 'تم تحديث حالة التمييز');
    }

    public function deleteBanner(Category $category, $type)
    {
        if (!in_array($type, ['desktop', 'mobile'])) {
            return back()->with('error', 'نوع الصورة غير صحيح');
        }

        $field = 'banner_' . $type;
        
        if ($category->$field) {
            Storage::disk('public')->delete($category->$field);
            $category->update([$field => null]);
            
            return back()->with('success', 'تم حذف صورة البانر بنجاح');
        }

        return back()->with('error', 'لا توجد صورة لحذفها');
    }
}
