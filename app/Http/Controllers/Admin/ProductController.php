<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_ar', 'like', "%{$search}%")
                    ->orWhere('name_en', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%")
                    ->orWhere('description_ar', 'like', "%{$search}%")
                    ->orWhere('description_en', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by brand
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Filter by status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Sort
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $products = $query->paginate(15)->withQueryString();
        $categories = Category::active()->get();
        $brands = Brand::active()->get();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        $brands = Brand::active()->get();

        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function importPage()
    {
        $categories = Category::active()->get();
        $brands = Brand::active()->get();

        return view('admin.products.import', compact('categories', 'brands'));
    }

    public function template()
    {
        $columns = Schema::getColumnListing('products');

        // Columns to exclude from template (auto-generated)
        $exclude = ['id', 'slug', 'stock_status', 'low_stock_threshold', 'seo_title_ar', 'seo_title_en', 'seo_description_ar', 'seo_description_en', 'seo_keywords', 'extra_attributes', 'created_at', 'updated_at', 'shop_id', 'image', 'currency'];

        $headers = [];
        $sampleRow = [];
        $mappings = [
            'name_ar' => ['header' => 'name', 'sample' => 'كتاب تعليمي للأطفال'],
            'name_en' => ['header' => 'name_en', 'sample' => 'Educational Book for Kids'],
            'description_ar' => ['header' => 'description', 'sample' => 'وصف المنتج بالعربي'],
            'description_en' => ['header' => 'description_en', 'sample' => 'Product description in English'],
            'short_description_ar' => ['header' => 'short_description_ar', 'sample' => 'وصف مختصر'],
            'short_description_en' => ['header' => 'short_description_en', 'sample' => 'Short description'],
            'subtitle_ar' => ['header' => 'subtitle_ar', 'sample' => ''],
            'subtitle_en' => ['header' => 'subtitle_en', 'sample' => ''],
            'price' => ['header' => 'price', 'sample' => '150.00'],
            'price_inside_assiut' => ['header' => 'price_inside_assiut', 'sample' => '140.00'],
            'price_outside_assiut' => ['header' => 'price_outside_assiut', 'sample' => '160.00'],
            'sale_price' => ['header' => 'sale_price', 'sample' => '120.00'],
            'sale_price_inside_assiut' => ['header' => 'sale_price_inside_assiut', 'sample' => '110.00'],
            'sale_price_outside_assiut' => ['header' => 'sale_price_outside_assiut', 'sample' => '130.00'],
            'old_price' => ['header' => 'old_price', 'sample' => '200.00'],
            'stock_quantity' => ['header' => 'stock', 'sample' => '50'],
            'sku' => ['header' => 'sku', 'sample' => 'PRD-001'],
            'barcode' => ['header' => 'barcode', 'sample' => '1234567890123'],
            'category_id' => ['header' => 'category_id', 'sample' => '1'],
            'brand_id' => ['header' => 'brand_id', 'sample' => '1'],
            'type' => ['header' => 'type', 'sample' => 'school_supplies'],
            'is_active' => ['header' => 'is_active', 'sample' => '1'],
            'is_featured' => ['header' => 'is_featured', 'sample' => '0'],
            'is_bingo' => ['header' => 'is_bingo', 'sample' => '0'],
            'sort_order' => ['header' => 'sort_order', 'sample' => '0'],
        ];

        foreach ($columns as $col) {
            if (in_array($col, $exclude)) continue;
            if (isset($mappings[$col])) {
                $headers[] = $mappings[$col]['header'];
                $sampleRow[] = $mappings[$col]['sample'];
            }
        }

        $fileName = 'products-template-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($headers, $sampleRow) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM
            fputcsv($out, $headers);
            fputcsv($out, $sampleRow);
            fclose($out);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'short_description_ar' => 'nullable|string',
            'short_description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'price_inside_assiut' => 'required|numeric|min:0',
            'price_outside_assiut' => 'required|numeric|min:0',
            'sale_price_inside_assiut' => 'nullable|numeric|min:0',
            'sale_price_outside_assiut' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:1',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'stock_quantity.min' => 'الكمية لا يمكن أن تكون صفراً عند إنشاء منتج جديد.',
        ]);

        $stockQuantity = (int) $validated['stock_quantity'];
        $lowStockThreshold = 5;
        $stockStatus = match (true) {
            $stockQuantity <= 0 => 'out_of_stock',
            $stockQuantity <= $lowStockThreshold => 'low_stock',
            default => 'in_stock',
        };

        $data = $validated;
        $data['stock_quantity'] = $stockQuantity;
        $data['low_stock_threshold'] = $lowStockThreshold;
        $data['stock_status'] = $stockStatus;

        $data['slug'] = $this->uniqueSlug($validated['name_ar']);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');

        $product = Product::create($data);

        // Handle images
        if ($request->hasFile('images')) {
            $sortOrder = 0;
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'is_primary' => $sortOrder === 0,
                    'sort_order' => $sortOrder++,
                ]);
            }
        }

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'تم إضافة المنتج بنجاح');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'brand', 'images', 'orderItems']);

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $product->load('images');
        $categories = Category::active()->get();
        $brands = Brand::active()->get();

        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'short_description_ar' => 'nullable|string',
            'short_description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'price_inside_assiut' => 'required|numeric|min:0',
            'price_outside_assiut' => 'required|numeric|min:0',
            'sale_price_inside_assiut' => 'nullable|numeric|min:0',
            'sale_price_outside_assiut' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'nullable|string|max:100|unique:products,sku,'.$product->id,
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $stockQuantity = (int) $validated['stock_quantity'];
        $lowStockThreshold = (int) ($product->low_stock_threshold ?? 5);
        $stockStatus = match (true) {
            $stockQuantity <= 0 => 'out_of_stock',
            $stockQuantity <= $lowStockThreshold => 'low_stock',
            default => 'in_stock',
        };

        $data = $validated;
        $data['stock_quantity'] = $stockQuantity;
        $data['stock_status'] = $stockStatus;

        // Update slug if name changed
        if ($product->name_ar !== $validated['name_ar']) {
            $data['slug'] = Str::slug($validated['name_ar']);
            $originalSlug = $data['slug'];
            $counter = 1;
            while (Product::where('slug', $data['slug'])->where('id', '!=', $product->id)->exists()) {
                $data['slug'] = $originalSlug.'-'.$counter++;
            }
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');

        $product->update($data);

        // Handle new images
        if ($request->hasFile('images')) {
            $sortOrder = $product->images()->max('sort_order') ?? 0;
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'sort_order' => ++$sortOrder,
                ]);
            }
        }

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'تم تحديث المنتج بنجاح');
    }

    public function destroy(Product $product)
    {
        // Delete images from storage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'تم حذف المنتج بنجاح');
    }

    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => ! $product->is_active]);

        return back()->with('success', 'تم تحديث حالة المنتج');
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

    public function export(Request $request)
    {
        $filters = [
            'search' => $request->string('search')->toString(),
            'category_id' => $request->string('category_id')->toString(),
            'brand_id' => $request->string('brand_id')->toString(),
            'is_active' => $request->string('is_active')->toString(),
            'sort' => $request->string('sort')->toString() ?: 'created_at',
            'direction' => $request->string('direction')->toString() ?: 'desc',
        ];

        $fileName = 'products-'.$request->user()->id.'-'.now()->format('Y-m-d_H-i-s').'.csv';

        return response()->streamDownload(function () use ($filters) {
            $out = fopen('php://output', 'w');

            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, [
                'id',
                'name',
                'description',
                'price',
                'sale_price',
                'stock',
                'sku',
                'barcode',
                'category_id',
                'brand_id',
                'is_active',
                'is_featured',
                'type',
                'sort_order',
            ]);

            $query = Product::query();

            if ($filters['search'] !== '') {
                $search = $filters['search'];
                $query->where(function ($q) use ($search) {
                    $q->where('name_ar', 'like', "%{$search}%")
                        ->orWhere('name_en', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%")
                        ->orWhere('description_ar', 'like', "%{$search}%")
                        ->orWhere('description_en', 'like', "%{$search}%");
                });
            }

            if ($filters['category_id'] !== '') {
                $query->where('category_id', $filters['category_id']);
            }

            if ($filters['brand_id'] !== '') {
                $query->where('brand_id', $filters['brand_id']);
            }

            if ($filters['is_active'] !== '') {
                $query->where('is_active', $filters['is_active']);
            }

            $sortField = $filters['sort'];
            $sortDirection = strtolower($filters['direction']) === 'asc' ? 'asc' : 'desc';

            $allowedSortFields = [
                'id',
                'name_ar',
                'name_en',
                'sku',
                'barcode',
                'price',
                'sale_price',
                'stock_quantity',
                'is_active',
                'is_featured',
                'sort_order',
                'created_at',
                'updated_at',
            ];

            if (! in_array($sortField, $allowedSortFields, true)) {
                $sortField = 'created_at';
            }

            $query
                ->orderBy($sortField, $sortDirection)
                ->orderBy('id');

            $query->chunkById(500, function ($products) use ($out) {
                foreach ($products as $product) {
                    fputcsv($out, [
                        $product->id,
                        $product->name,
                        $product->description,
                        $product->price,
                        $product->sale_price,
                        $product->stock,
                        $product->sku,
                        $product->barcode,
                        $product->category_id,
                        $product->brand_id,
                        $product->is_active ? 1 : 0,
                        $product->is_featured ? 1 : 0,
                        $product->type,
                        $product->sort_order,
                    ]);
                }
            });

            fclose($out);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'max:5120',
                'mimes:csv,txt',
                'mimetypes:text/plain,text/csv,application/csv,application/vnd.ms-excel',
            ],
        ]);

        $file = $request->file('file');
        $filePath = $file->getRealPath();

        if (! $filePath) {
            return back()->withErrors(['file' => 'تعذر قراءة الملف.']);
        }

        $result = $this->importFromCsv($filePath);

        if ($result['ok'] !== true) {
            return back()->withErrors(['file' => $result['message']]);
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', "تم الاستيراد بنجاح: إضافة {$result['created']} / تحديث {$result['updated']} / تخطي {$result['skipped']}");
    }

    private function importFromCsv(string $filePath): array
    {
        $delimiter = ',';
        $header = null;

        $csv = new \SplFileObject($filePath);
        $csv->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);
        $csv->setCsvControl($delimiter);

        $header = $csv->fgetcsv();
        $header = is_array($header) ? $header : null;

        if (! $header || (count($header) === 1 && str_contains((string) ($header[0] ?? ''), ';'))) {
            $delimiter = ';';
            $csv = new \SplFileObject($filePath);
            $csv->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);
            $csv->setCsvControl($delimiter);
            $header = $csv->fgetcsv();
            $header = is_array($header) ? $header : null;
        }

        if (! $header) {
            return ['ok' => false, 'message' => 'الملف فارغ أو غير صالح.'];
        }

        $header = array_map(function ($value) {
            $value = (string) ($value ?? '');
            $value = preg_replace('/^\xEF\xBB\xBF/u', '', $value) ?? $value;

            return $this->normalizeHeader($value);
        }, $header);

        $index = [];
        foreach ($header as $i => $key) {
            if ($key === '') {
                continue;
            }
            $index[$key] = $i;
        }

        if (! isset($index['name']) || ! isset($index['price'])) {
            return ['ok' => false, 'message' => 'لازم يكون في أعمدة باسم name و price في أول صف.'];
        }

        $created = 0;
        $updated = 0;
        $skipped = 0;

        DB::beginTransaction();

        try {
            while (! $csv->eof()) {
                $row = $csv->fgetcsv();
                if (! is_array($row) || $row === [null] || $row === false) {
                    continue;
                }

                $name = $this->cell($row, $index, 'name');
                $price = $this->cell($row, $index, 'price');

                if ($name === '' || $price === '') {
                    $skipped++;

                    continue;
                }

                $productId = $this->cell($row, $index, 'id');
                $sku = $this->cell($row, $index, 'sku');

                $product = null;
                if ($productId !== '' && ctype_digit($productId)) {
                    $product = Product::query()->whereKey((int) $productId)->first();
                }
                if (! $product && $sku !== '') {
                    $product = Product::query()->where('sku', $sku)->first();
                }

                $description = $this->cell($row, $index, 'description');
                $salePrice = $this->cell($row, $index, 'sale_price');
                $stock = $this->cell($row, $index, 'stock');
                $barcode = $this->cell($row, $index, 'barcode');
                $categoryId = $this->cell($row, $index, 'category_id');
                $brandId = $this->cell($row, $index, 'brand_id');
                $isActive = $this->parseBoolean($this->cell($row, $index, 'is_active'));
                $isFeatured = $this->parseBoolean($this->cell($row, $index, 'is_featured'));
                $type = $this->cell($row, $index, 'type');
                $sortOrder = $this->cell($row, $index, 'sort_order');

                $priceValue = is_numeric($price) ? (float) $price : null;
                if ($priceValue === null || $priceValue < 0) {
                    $skipped++;

                    continue;
                }

                $salePriceValue = null;
                if ($salePrice !== '' && is_numeric($salePrice)) {
                    $salePriceValue = (float) $salePrice;
                    if ($salePriceValue < 0 || $salePriceValue >= $priceValue) {
                        $salePriceValue = null;
                    }
                }

                $stockQuantity = 0;
                if ($stock !== '' && is_numeric($stock)) {
                    $stockQuantity = max(0, (int) $stock);
                }

                $lowStockThreshold = (int) ($product?->low_stock_threshold ?? 5);
                $stockStatus = match (true) {
                    $stockQuantity <= 0 => 'out_of_stock',
                    $stockQuantity <= $lowStockThreshold => 'low_stock',
                    default => 'in_stock',
                };

                $categoryIdValue = null;
                if ($categoryId !== '' && ctype_digit($categoryId) && Category::query()->whereKey((int) $categoryId)->exists()) {
                    $categoryIdValue = (int) $categoryId;
                }

                $brandIdValue = null;
                if ($brandId !== '' && ctype_digit($brandId) && Brand::query()->whereKey((int) $brandId)->exists()) {
                    $brandIdValue = (int) $brandId;
                }

                $allowedTypes = [
                    'school_supplies',
                    'leather_products',
                    'study_notes',
                    'montessori_toys',
                    'kids_toys',
                    'bingo',
                ];
                $typeValue = in_array($type, $allowedTypes, true) ? $type : ($product?->type ?? 'school_supplies');

                $sortOrderValue = 0;
                if ($sortOrder !== '' && is_numeric($sortOrder)) {
                    $sortOrderValue = max(0, (int) $sortOrder);
                } elseif ($product) {
                    $sortOrderValue = (int) ($product->sort_order ?? 0);
                }

                $data = [
                    'name_ar' => $name,
                    'name_en' => $name,
                    'description_ar' => $description !== '' ? $description : null,
                    'description_en' => $description !== '' ? $description : null,
                    'price' => $priceValue,
                    'sale_price' => $salePriceValue,
                    'stock_quantity' => $stockQuantity,
                    'stock_status' => $stockStatus,
                    'category_id' => $categoryIdValue,
                    'brand_id' => $brandIdValue,
                    'sku' => $sku !== '' ? $sku : null,
                    'barcode' => $barcode !== '' ? $barcode : null,
                    'type' => $typeValue,
                    'sort_order' => $sortOrderValue,
                ];

                if ($isActive !== null) {
                    $data['is_active'] = $isActive;
                } elseif (! $product) {
                    $data['is_active'] = true;
                }

                if ($isFeatured !== null) {
                    $data['is_featured'] = $isFeatured;
                } elseif (! $product) {
                    $data['is_featured'] = false;
                }

                if ($product) {
                    if ($product->name_ar !== $name) {
                        $data['slug'] = $this->uniqueSlug($name, $product->id);
                    }
                    $product->update($data);
                    $updated++;
                } else {
                    $data['slug'] = $this->uniqueSlug($name);
                    Product::create($data);
                    $created++;
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            return ['ok' => false, 'message' => 'حصل خطأ أثناء الاستيراد.'];
        }

        return [
            'ok' => true,
            'created' => $created,
            'updated' => $updated,
            'skipped' => $skipped,
        ];
    }

    private function normalizeHeader(string $value): string
    {
        $value = trim(mb_strtolower($value));

        return match ($value) {
            'product_name', 'product', 'اسم', 'الاسم' => 'name',
            'desc', 'الوصف', 'description_ar', 'description_en' => 'description',
            'qty', 'quantity', 'المخزون', 'stock_quantity' => 'stock',
            'sale', 'saleprice', 'سعر_الخصم' => 'sale_price',
            'category', 'categoryid', 'التصنيف' => 'category_id',
            'brand', 'brandid', 'العلامة', 'الماركة' => 'brand_id',
            'active', 'نشط' => 'is_active',
            'featured', 'مميز' => 'is_featured',
            default => str_replace(' ', '_', $value),
        };
    }

    private function cell(array $row, array $index, string $key): string
    {
        if (! isset($index[$key])) {
            return '';
        }

        $i = $index[$key];
        $value = $row[$i] ?? '';

        return trim((string) ($value ?? ''));
    }

    private function parseBoolean(string $value): ?bool
    {
        $v = mb_strtolower(trim($value));
        if ($v === '') {
            return null;
        }

        if (in_array($v, ['1', 'true', 'yes', 'y', 'on', 'نعم'], true)) {
            return true;
        }

        if (in_array($v, ['0', 'false', 'no', 'n', 'off', 'لا'], true)) {
            return false;
        }

        return null;
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        if ($slug === '') {
            $slug = 'product';
        }
        $originalSlug = $slug;
        $counter = 1;

        $query = Product::query()->where('slug', $slug);
        if ($ignoreId !== null) {
            $query->where('id', '!=', $ignoreId);
        }

        while ($query->exists()) {
            $slug = $originalSlug.'-'.$counter++;
            $query = Product::query()->where('slug', $slug);
            if ($ignoreId !== null) {
                $query->where('id', '!=', $ignoreId);
            }
        }

        return $slug;
    }
}
