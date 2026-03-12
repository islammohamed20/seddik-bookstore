<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ProductVariantAttribute;
use App\Models\TagGroup;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images', 'variants']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_ar', 'like', "%{$search}%")
                    ->orWhere('name_en', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $categories = Category::active()->get();
        $brands = Brand::active()->get();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        $brands = Brand::active()->get();
        $attributes = ProductAttribute::active()->get();
        $tagGroups = TagGroup::with(['options' => function ($q) {
            $q->active()->ordered();
        }])->active()->ordered()->get();

        return view('admin.products.create', compact('categories', 'brands', 'attributes', 'tagGroups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'is_active' => 'boolean',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'short_description_ar' => 'nullable|string',
            'short_description_en' => 'nullable|string',
            'product_type' => 'required|in:simple,variable',
            'type' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'tag_options' => 'array',
            'tag_options.*' => 'exists:tag_options,id',
            'variants' => 'nullable|array',
        ]);

        $slug = $this->generateUniqueSlug($validated['name_ar']);
        
        $product = Product::create(array_merge($validated, [
            'slug' => $slug,
            'is_active' => $request->boolean('is_active', true),
            'stock_status' => $this->getStockStatus($validated['stock_quantity']),
        ]));

        if ($request->hasFile('images')) {
            $sortOrder = 0;
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create([
                    'path' => $path,
                    'sort_order' => ++$sortOrder,
                ]);
            }
        }
        
        if ($request->has('tag_options')) {
            $product->tagOptions()->sync($request->tag_options);
        }

        // Handle Variants Creation if provided
        $variantsData = $request->input('variants', []);
        if (!empty($variantsData)) {
            // Preload attributes for all variants
            $allAttributeIds = [];
            foreach ($variantsData as $v) {
                if (!empty($v['attributes']) && is_array($v['attributes'])) {
                    $allAttributeIds = array_merge($allAttributeIds, array_keys($v['attributes']));
                }
            }
            $productAttributes = ProductAttribute::whereIn('id', array_unique($allAttributeIds))->get()->keyBy('id');

            foreach ($variantsData as $variantData) {
                // Skip empty variants
                if (empty($variantData['attributes']) || !is_array($variantData['attributes'])) {
                    continue;
                }

                // Prepare attribute combination
                $combination = [];
                $first = array_slice($variantData['attributes'], 0, 1, true);
                foreach ($first as $attributeId => $value) {
                    $attr = $productAttributes->get($attributeId);
                    if ($attr) {
                         $combination[$attr->display_name] = $value;
                    }
                }

                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $variantData['sku'] ?? null,
                    'price' => !empty($variantData['price']) ? $variantData['price'] : null,
                    'stock_quantity' => $variantData['stock_quantity'] ?? 0,
                    'is_active' => !empty($variantData['is_active']),
                    'attribute_combination' => $combination,
                ]);

                foreach ($first as $attributeId => $value) {
                    ProductVariantAttribute::create([
                        'product_variant_id' => $variant->id,
                        'product_attribute_id' => $attributeId,
                        'value' => $value,
                    ]);
                }
            }

            // تحديث حالة المتغيرات في المنتج
            $product->update(['has_variants' => true, 'product_type' => 'variable']);
        }

        return redirect()->route('admin.products.edit', $product)->with('success', 'تم إنشاء المنتج بنجاح');
    }

    public function edit(Product $product)
    {
        $product->load(['images', 'variants.attributeValues.attribute', 'tagOptions']);
        $categories = Category::active()->get();
        $brands = Brand::active()->get();
        $attributes = ProductAttribute::active()->get();
        $tagGroups = TagGroup::with(['options' => function ($q) {
            $q->active()->ordered();
        }])->active()->ordered()->get();

        return view('admin.products.edit', compact('product', 'categories', 'brands', 'attributes', 'tagGroups'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'is_active' => 'boolean',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'short_description_ar' => 'nullable|string',
            'short_description_en' => 'nullable|string',
            'product_type' => 'required|in:simple,variable',
            'type' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'tag_options' => 'array',
            'tag_options.*' => 'exists:tag_options,id',
        ]);

        if ($product->name_ar !== $validated['name_ar']) {
            $validated['slug'] = $this->generateUniqueSlug($validated['name_ar'], $product->id);
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['stock_status'] = $this->getStockStatus($validated['stock_quantity'] ?? 0);

        $product->update($validated);
        
        if ($request->has('tag_options')) {
            $product->tagOptions()->sync($request->tag_options);
        }

        if ($request->hasFile('images')) {
            $sortOrder = $product->images()->max('sort_order') ?? 0;
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create([
                    'path' => $path,
                    'sort_order' => ++$sortOrder,
                ]);
            }
        }

        return redirect()->route('admin.products.edit', $product)->with('success', 'تم تحديث المنتج بنجاح');
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'تم حذف المنتج بنجاح');
    }

    public function deleteImage(Product $product, ProductImage $image)
    {
        if ($image->product_id !== $product->id) {
            abort(404);
        }

        Storage::disk('public')->delete($image->path);
        $image->delete();

        return back()->with('success', 'تم حذف الصورة');
    }

    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        return response()->json(['status' => 'success', 'is_active' => $product->is_active, 'message' => 'تم تغيير الحالة بنجاح']);
    }
    
    // Product Variants Methods
    public function getVariants(Product $product)
    {
        $variants = $product->variants()->with('attributeValues.attribute')->get();
        return response()->json(['variants' => $variants]);
    }
    
    public function storeVariant(Request $request, Product $product)
    {
        $validated = $request->validate([
            'sku'            => 'nullable|string|max:100',
            'price'          => 'required|numeric|min:0',
            'sale_price'     => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'is_active'      => 'nullable',
            'sort_order'     => 'nullable|integer|min:0',
            'attributes'     => 'required|array|min:1',
            'attributes.*'   => 'required|string',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Build attribute combination label
        $combination = [];
        $attributes = ProductAttribute::whereIn('id', array_keys($validated['attributes']))->get()->keyBy('id');
        foreach ($validated['attributes'] as $attributeId => $value) {
            $attr = $attributes->get($attributeId);
            if ($attr) $combination[$attr->name_ar ?? $attr->name] = $value;
        }

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('variants', 'public');
        }

        $variant = $product->variants()->create([
            'sku'                   => $validated['sku'] ?? null,
            'price'                 => $validated['price'],
            'sale_price'            => $validated['sale_price'] ?? null,
            'stock_quantity'        => $validated['stock_quantity'],
            'is_active'             => !empty($validated['is_active']),
            'sort_order'            => $validated['sort_order'] ?? 0,
            'attribute_combination' => $combination,
            'image'                 => $imagePath,
        ]);

        foreach ($validated['attributes'] as $attributeId => $value) {
            ProductVariantAttribute::create([
                'product_variant_id'  => $variant->id,
                'product_attribute_id'=> $attributeId,
                'value'               => $value,
            ]);
        }

        $product->update(['has_variants' => true, 'product_type' => 'variable']);

        return response()->json([
            'status'  => 'success',
            'message' => 'تم إضافة المتغير بنجاح',
            'variant' => $variant->load('attributeValues.attribute'),
        ]);
    }
    
    public function updateVariant(Request $request, Product $product, ProductVariant $variant)
    {
        if ($variant->product_id !== $product->id) abort(404);

        $validated = $request->validate([
            'sku'            => 'nullable|string|max:100',
            'price'          => 'required|numeric|min:0',
            'sale_price'     => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'is_active'      => 'nullable',
            'sort_order'     => 'nullable|integer|min:0',
            'attributes'     => 'required|array|min:1',
            'attributes.*'   => 'required|string',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Build attribute combination label
        $combination = [];
        $attributes = ProductAttribute::whereIn('id', array_keys($validated['attributes']))->get()->keyBy('id');
        foreach ($validated['attributes'] as $attributeId => $value) {
            $attr = $attributes->get($attributeId);
            if ($attr) $combination[$attr->name_ar ?? $attr->name] = $value;
        }

        // Handle image upload
        $imagePath = $variant->image; // keep existing by default
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($variant->image) Storage::disk('public')->delete($variant->image);
            $imagePath = $request->file('image')->store('variants', 'public');
        } elseif ($request->input('existing_image') === '') {
            // User explicitly removed the image
            if ($variant->image) Storage::disk('public')->delete($variant->image);
            $imagePath = null;
        }

        $variant->update([
            'sku'                   => $validated['sku'] ?? null,
            'price'                 => $validated['price'],
            'sale_price'            => $validated['sale_price'] ?? null,
            'stock_quantity'        => $validated['stock_quantity'],
            'is_active'             => !empty($validated['is_active']),
            'sort_order'            => $validated['sort_order'] ?? 0,
            'attribute_combination' => $combination,
            'image'                 => $imagePath,
        ]);

        $variant->attributeValues()->delete();
        foreach ($validated['attributes'] as $attributeId => $value) {
            ProductVariantAttribute::create([
                'product_variant_id'   => $variant->id,
                'product_attribute_id' => $attributeId,
                'value'                => $value,
            ]);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'تم تحديث المتغير بنجاح',
            'variant' => $variant->load('attributeValues.attribute'),
        ]);
    }
    
    public function destroyVariant(Product $product, ProductVariant $variant)
    {
        if ($variant->product_id !== $product->id) {
            abort(404);
        }
        
        $variant->attributeValues()->delete();
        $variant->delete();
        
        // Update product flags if no more variants
        if ($product->variants()->count() === 0) {
            $product->update(['has_variants' => false, 'product_type' => 'simple']);
        }
        
        return response()->json([
            'status' => 'success', 
            'message' => 'تم حذف المتغير بنجاح'
        ]);
    }

    private function generateUniqueSlug($name, $ignoreId = null)
    {
        $slug = Str::slug($name);
        if (empty($slug)) {
            $slug = 'product-' . time();
        }
        
        $query = Product::where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }
        
        while ($query->exists()) {
            $slug .= '-' . rand(1, 1000);
            $query = Product::where('slug', $slug);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
        }
        
        return $slug;
    }

    private function getStockStatus($quantity)
    {
        if ($quantity <= 0) return 'out_of_stock';
        if ($quantity <= 5) return 'low_stock';
        return 'in_stock';
    }
}
