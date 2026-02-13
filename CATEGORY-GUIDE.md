# Quick Reference - Category Management

## Admin Panel Locations

### View All Categories
**URL:** `/admin/categories`
**Features:**
- Search by name
- Filter by active/inactive
- Toggle featured status (⭐)
- Toggle active status (✅/❌)
- Edit/Delete actions

### Add New Category
**URL:** `/admin/categories/create`
**Required Fields:**
- ✅ اسم التصنيف (Category Name)

**Optional Fields:**
- التصنيف الأب (Parent Category)
- الوصف (Description)
- أيقونة Font Awesome (Icon) - e.g., `fa-book-open`
- صورة التصنيف (Category Image)
- الترتيب (Sort Order) - default: 0
- نشط (Active) - default: checked
- تصنيف مميز (Featured) - shows on homepage

### Edit Category
**URL:** `/admin/categories/{slug}/edit`
Same fields as create, with current values pre-filled.

---

## Font Awesome Icons

### Common Icon Examples:
```
fa-book-open        - Open book
fa-book             - Closed book
fa-briefcase        - Briefcase
fa-graduation-cap   - Graduation cap
fa-file-alt         - File/document
fa-puzzle-piece     - Puzzle piece
fa-child            - Child
fa-gamepad          - Game controller
fa-star             - Star
fa-tag              - Price tag
fa-gift             - Gift
fa-pencil-alt       - Pencil
fa-pen              - Pen
fa-palette          - Palette
fa-paint-brush      - Paint brush
fa-calculator       - Calculator
fa-globe            - Globe
fa-folder           - Folder
fa-heart            - Heart
```

**Full Icons List:** https://fontawesome.com/icons

---

## Category Color Mapping

When adding new categories, use these color schemes for consistency:

### Blue (School/Education)
- Gradient: `from-blue-500 to-blue-600`
- Background: `bg-blue-50`
- Text: `text-blue-600`

### Amber/Yellow (Premium/Leather)
- Gradient: `from-amber-500 to-amber-600`
- Background: `bg-amber-50`
- Text: `text-amber-600`

### Green (Study/Academic)
- Gradient: `from-green-500 to-green-600`
- Background: `bg-green-50`
- Text: `text-green-600`

### Purple (Creative/Montessori)
- Gradient: `from-purple-500 to-purple-600`
- Background: `bg-purple-50`
- Text: `text-purple-600`

### Pink (Kids/Fun)
- Gradient: `from-pink-500 to-pink-600`
- Background: `bg-pink-50`
- Text: `text-pink-600`

### Red (Featured/Special)
- Gradient: `from-red-500 to-red-600`
- Background: `bg-red-50`
- Text: `text-red-600`

### Gray (Neutral/General)
- Gradient: `from-gray-500 to-gray-600`
- Background: `bg-gray-50`
- Text: `text-gray-600`

### Teal (Tech/Modern)
- Gradient: `from-teal-500 to-teal-600`
- Background: `bg-teal-50`
- Text: `text-teal-600`

### Orange (Sale/Discount)
- Gradient: `from-orange-500 to-orange-600`
- Background: `bg-orange-50`
- Text: `text-orange-600`

**Note:** Color mapping is in the view file. To add a new color for a new category slug, edit:
`resources/views/storefront/partials/featured-categories.blade.php` around line 13.

---

## Homepage Display Rules

Categories appear on homepage **ONLY** if:
1. ✅ `is_active = true`
2. ✅ `is_featured = true`
3. ✅ `parent_id IS NULL` (root categories only)

**Ordering:** By `sort_order` ASC (lowest number first)
**Limit:** Top 6 categories (can be changed in HomeController)

---

## Database Structure

### Categories Table Columns:
```
id                  - Primary key
parent_id           - Self-referencing (for sub-categories)
slug                - URL-friendly name
name_ar             - Arabic name
name_en             - English name
description_ar      - Arabic description
description_en      - English description
icon                - Font Awesome class (e.g., fa-book-open)
image               - Image path in storage/categories/
is_active           - Boolean (show/hide)
is_featured         - Boolean (show on homepage)
sort_order          - Integer (display order)
seo_title_ar        - SEO meta title (Arabic)
seo_title_en        - SEO meta title (English)
seo_description_ar  - SEO meta description (Arabic)
seo_description_en  - SEO meta description (English)
seo_keywords        - SEO keywords (comma-separated)
created_at          - Timestamp
updated_at          - Timestamp
```

---

## Controller Methods

### CategoryController (Admin)
- `index()` - List all categories with search/filter
- `create()` - Show create form
- `store()` - Save new category
- `show()` - View single category details
- `edit()` - Show edit form
- `update()` - Update category
- `destroy()` - Delete category (checks for products first)
- `toggleStatus()` - Toggle is_active
- `toggleFeatured()` - Toggle is_featured

### HomeController (Storefront)
```php
$categories = Category::root()
    ->active()
    ->featured()
    ->ordered()
    ->take(6)
    ->get();
```

---

## Routes

### Admin Routes:
```
GET     /admin/categories                               - index
GET     /admin/categories/create                        - create
POST    /admin/categories                               - store
GET     /admin/categories/{category}                    - show
GET     /admin/categories/{category}/edit               - edit
PUT     /admin/categories/{category}                    - update
DELETE  /admin/categories/{category}                    - destroy
POST    /admin/categories/{category}/toggle-status      - toggle active
POST    /admin/categories/{category}/toggle-featured    - toggle featured
```

### Storefront Routes:
```
GET     /                                               - Categories on homepage
GET     /products/category/{category}                   - Products by category
```

---

## Testing Commands

### Check Category Count:
```bash
php artisan tinker --execute="echo Category::count() . ' categories' . PHP_EOL;"
```

### View Featured Categories:
```bash
php artisan tinker --execute="Category::featured()->get(['name_ar', 'icon', 'sort_order'])->each(fn(\$c) => print_r(['name' => \$c->name_ar, 'icon' => \$c->icon, 'order' => \$c->sort_order]));"
```

### Check Category with Products:
```bash
php artisan tinker --execute="Category::withCount('products')->get(['name_ar', 'products_count'])->each(fn(\$c) => echo \$c->name_ar . ': ' . \$c->products_count . ' products' . PHP_EOL);"
```

### Re-seed Categories:
```bash
php artisan db:seed --class=CategorySeeder
```

---

## Troubleshooting

### Icons Not Showing:
1. Check Font Awesome CDN in `layouts/storefront.blade.php`
2. Verify icon class name (must start with `fa-`)
3. Check browser console for 404 errors

### Category Not on Homepage:
1. Check `is_active = true`
2. Check `is_featured = true`
3. Check `parent_id IS NULL`
4. Clear view cache: `php artisan view:clear`

### Colors Not Applying:
1. Check `$categoryColors` array in `featured-categories.blade.php`
2. Ensure category slug matches array key exactly
3. Clear browser cache

### Slug Already Exists:
- Controller automatically appends `-1`, `-2`, etc. if slug conflict
- To fix: Manually set unique slug in database

---

## Best Practices

### Naming:
- ✅ Use clear, descriptive Arabic names
- ✅ Add English name for bilingual support
- ✅ Keep slug short and URL-friendly

### Icons:
- ✅ Use solid icons (`fas`) not regular (`far`) or light (`fal`)
- ✅ Choose recognizable, on-brand icons
- ✅ Test icon appearance before saving

### Ordering:
- ✅ Use multiples of 10 (10, 20, 30...) for sort_order
- ✅ Makes it easy to insert categories between existing ones
- ✅ Example: to insert between 10 and 20, use 15

### Images:
- ✅ Upload square images (500x500px recommended)
- ✅ Use WebP or optimized JPEG/PNG
- ✅ Keep file size under 200KB

### SEO:
- ✅ Fill in SEO fields for better search rankings
- ✅ Include target keywords naturally
- ✅ Keep meta description under 160 characters

---

## Quick Actions

### Make Category Featured:
1. Go to `/admin/categories`
2. Find category row
3. Click the gray "عادي" badge under "مميز" column
4. Badge turns yellow ⭐ = now featured on homepage

### Reorder Categories:
1. Go to `/admin/categories`
2. Click "Edit" on category
3. Change "الترتيب" value
4. Save
5. Lower number = appears first

### Hide Category:
1. Go to `/admin/categories`
2. Click green "نشط" badge
3. Badge turns red "غير نشط" = hidden from site

### Delete Category:
- ⚠️ Can only delete if:
  - No products assigned
  - No child categories
- Otherwise: Move products first, then delete

---

## Support

For issues or questions:
- Check Laravel logs: `storage/logs/laravel.log`
- Run: `php artisan route:list | grep categories`
- Clear all cache: `php artisan optimize:clear`
