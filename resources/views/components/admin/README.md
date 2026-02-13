# Admin Blade Components Documentation

مجموعة من الـ Blade Components القابلة لإعادة الاستخدام في لوحة التحكم.

## 📦 المكونات المتاحة

### 1. Stats Card - بطاقة الإحصائيات

عرض بطاقة إحصائية مع أيقونة ولون مخصص.

```blade
<x-admin.stats-card
    title="إجمالي الإيرادات"
    value="15,000 ج.م"
    icon="fas fa-dollar-sign"
    color="green"
    subtitle="نشط" />
```

**الخصائص:**
- `title` (required): عنوان البطاقة
- `value` (required): القيمة المعروضة
- `icon` (required): أيقونة Font Awesome
- `color` (optional): اللون (green, blue, purple, yellow, indigo, pink, teal, red, orange)
- `subtitle` (optional): نص إضافي تحت القيمة

---

### 2. Page Header - رأس الصفحة

عرض رأس الصفحة مع breadcrumbs وأزرار action.

```blade
<x-admin.page-header 
    title="إدارة المنتجات"
    :breadcrumbs="[
        ['title' => 'المنتجات', 'url' => route('admin.products.index')],
        ['title' => 'إضافة منتج']
    ]">
    <x-slot name="actions">
        <x-admin.button variant="primary" icon="fas fa-plus">
            إضافة منتج
        </x-admin.button>
    </x-slot>
</x-admin.page-header>
```

**الخصائص:**
- `title` (required): عنوان الصفحة
- `breadcrumbs` (optional): مصفوفة من الروابط
- `actions` (slot): أزرار الإجراءات

---

### 3. Alert - رسائل التنبيه

عرض رسائل النجاح، الخطأ، التحذير، أو المعلومات.

```blade
<x-admin.alert type="success">
    تم حفظ البيانات بنجاح
</x-admin.alert>

<x-admin.alert type="error" icon="fas fa-times-circle">
    حدث خطأ أثناء الحفظ
</x-admin.alert>
```

**الخصائص:**
- `type` (optional): نوع التنبيه (success, error, warning, info) - الافتراضي: info
- `dismissible` (optional): إمكانية الإغلاق - الافتراضي: true
- `icon` (optional): أيقونة مخصصة

---

### 4. Card - بطاقة عامة

حاوية بطاقة مع عنوان وتذييل اختياري.

```blade
<x-admin.card title="البيانات الأساسية">
    <p>محتوى البطاقة هنا...</p>
    
    <x-slot name="footer">
        <x-admin.button>حفظ</x-admin.button>
    </x-slot>
</x-admin.card>
```

**الخصائص:**
- `title` (optional): عنوان البطاقة
- `padding` (optional): إضافة padding للمحتوى - الافتراضي: true
- `header` (slot): محتوى مخصص للرأس
- `footer` (slot): محتوى التذييل

---

### 5. Button - الأزرار

أزرار بأشكال وألوان مختلفة.

```blade
<x-admin.button variant="primary" icon="fas fa-save">
    حفظ
</x-admin.button>

<x-admin.button variant="danger" size="sm" type="submit">
    حذف
</x-admin.button>
```

**الخصائص:**
- `type` (optional): نوع الزر - الافتراضي: button
- `variant` (optional): الشكل (primary, secondary, success, danger, warning, info, outline-primary, outline-danger, ghost)
- `size` (optional): الحجم (sm, md, lg) - الافتراضي: md
- `icon` (optional): أيقونة
- `iconPosition` (optional): موضع الأيقونة (right, left) - الافتراضي: right

---

### 6. Badge - الشارات

شارات صغيرة لعرض الحالات.

```blade
<x-admin.badge variant="success">نشط</x-admin.badge>
<x-admin.badge variant="danger" size="sm">ملغي</x-admin.badge>
```

**الخصائص:**
- `variant` (optional): الشكل (default, success, danger, warning, info, primary, purple)
- `size` (optional): الحجم (sm, md, lg) - الافتراضي: md

---

### 7. Sidebar Link - روابط القائمة الجانبية

روابط القائمة الجانبية مع تفعيل تلقائي.

```blade
<x-admin.sidebar-link 
    route="admin.products.*" 
    icon="fas fa-box" 
    label="المنتجات" />
```

**الخصائص:**
- `route` (required): اسم المسار (يدعم wildcard *)
- `icon` (required): أيقونة Font Awesome
- `label` (required): نص الرابط
- `badge` (slot): شارة إضافية

---

### 8. Table - الجداول

جدول بيانات مع headers وتنسيق.

```blade
<x-admin.table :headers="['الاسم', 'البريد', 'الحالة']" striped>
    <x-admin.table.row>
        <x-admin.table.cell>محمد أحمد</x-admin.table.cell>
        <x-admin.table.cell>mohamed@example.com</x-admin.table.cell>
        <x-admin.table.cell>
            <x-admin.badge variant="success">نشط</x-admin.badge>
        </x-admin.table.cell>
    </x-admin.table.row>
</x-admin.table>
```

**الخصائص:**
- `headers` (optional): مصفوفة العناوين
- `striped` (optional): خطوط متناوبة - الافتراضي: false
- `hoverable` (optional): تأثير hover - الافتراضي: true

---

### 9. Empty State - حالة فارغة

عرض رسالة عند عدم وجود بيانات.

```blade
<x-admin.empty-state 
    title="لا توجد منتجات"
    description="لم يتم إضافة أي منتجات بعد"
    icon="fas fa-box-open">
    <x-slot name="action">
        <x-admin.button variant="primary">
            إضافة أول منتج
        </x-admin.button>
    </x-slot>
</x-admin.empty-state>
```

**الخصائص:**
- `icon` (optional): أيقونة - الافتراضي: fas fa-inbox
- `title` (optional): العنوان - الافتراضي: "لا توجد بيانات"
- `description` (optional): وصف إضافي
- `action` (slot): زر أو إجراء

---

## 🎨 الألوان المتاحة

- `green` - أخضر (Revenue, Success)
- `blue` - أزرق (Info, Today)
- `purple` - بنفسجي (Orders)
- `yellow` - أصفر (Warnings, Pending)
- `indigo` - نيلي (Primary)
- `pink` - وردي (Users)
- `teal` - تركواز (Categories)
- `red` - أحمر (Danger, Errors)
- `orange` - برتقالي

---

## 💡 أمثلة عملية

### صفحة Dashboard كاملة

```blade
@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <x-admin.page-header title="لوحة التحكم" />
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <x-admin.stats-card
            title="إجمالي الإيرادات"
            value="15,000 ج.م"
            icon="fas fa-dollar-sign"
            color="green" />
    </div>
    
    <x-admin.card title="أحدث الطلبات" :padding="false">
        <x-admin.table :headers="['رقم الطلب', 'المبلغ', 'الحالة']" striped>
            <!-- rows here -->
        </x-admin.table>
    </x-admin.card>
</div>
@endsection
```

### صفحة CRUD

```blade
<x-admin.page-header 
    title="إدارة المنتجات"
    :breadcrumbs="[['title' => 'المنتجات']]">
    <x-slot name="actions">
        <x-admin.button 
            variant="primary" 
            icon="fas fa-plus"
            onclick="window.location='{{ route('admin.products.create') }}'">
            إضافة منتج
        </x-admin.button>
    </x-slot>
</x-admin.page-header>

<x-admin.card title="البحث والفلترة">
    <!-- filters form -->
</x-admin.card>

<x-admin.card :padding="false">
    <x-admin.table :headers="['المنتج', 'السعر', 'الحالة', 'إجراءات']" striped>
        @forelse($products as $product)
            <x-admin.table.row>
                <x-admin.table.cell>{{ $product->name }}</x-admin.table.cell>
                <x-admin.table.cell>{{ $product->price }}</x-admin.table.cell>
                <x-admin.table.cell>
                    <x-admin.badge :variant="$product->is_active ? 'success' : 'danger'">
                        {{ $product->is_active ? 'نشط' : 'غير نشط' }}
                    </x-admin.badge>
                </x-admin.table.cell>
                <x-admin.table.cell>
                    <!-- actions -->
                </x-admin.table.cell>
            </x-admin.table.row>
        @empty
            <tr>
                <td colspan="4">
                    <x-admin.empty-state title="لا توجد منتجات" />
                </td>
            </tr>
        @endforelse
    </x-admin.table>
</x-admin.card>
```

---

## 🚀 الميزات

- ✅ **RTL Ready**: دعم كامل للعربية
- ✅ **Responsive**: متجاوب مع جميع الشاشات
- ✅ **Customizable**: قابل للتخصيص عبر attributes
- ✅ **Consistent**: تصميم موحد في كل اللوحة
- ✅ **Reusable**: قابل لإعادة الاستخدام
- ✅ **Alpine.js**: تفاعلية باستخدام Alpine
- ✅ **Tailwind CSS**: تنسيقات Tailwind

---

## 📝 ملاحظات

1. جميع الـ components تستخدم Tailwind CSS و Alpine.js
2. يمكن تمرير classes إضافية عبر `class="custom-class"`
3. الـ slots تسمح بمحتوى مخصص
4. الألوان والأحجام قابلة للتخصيص

تم التطوير بواسطة: مكتبة الصديق - Admin Components
