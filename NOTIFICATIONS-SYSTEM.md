# نظام الإشعارات في لوحة التحكم

## 📋 نظرة عامة

تم إضافة نظام إشعارات متكامل في لوحة التحكم يعرض تحديثات فورية للأحداث المهمة التالية:
- ✅ **الطلبات**: إشعار فوري عند استلام طلب جديد أو تحديث حالة طلب
- ✅ **تسجيلات الدخول**: إشعار عند تسجيل دخول المستخدمين
- ✅ **التسجيلات الجديدة**: إشعار عند تسجيل عملاء جدد
- ✅ **البريد الإلكتروني**: إشعار عند إرسال رسائل بريد إلكتروني
- ✅ **الرسائل**: إشعار عند استلام رسائل جديدة من العملاء

## 🎯 المميزات

### 1. إشعارات Popup الفورية
- 🔔 أيقونة جرس في شريط الرأس العلوي
- 🔴 Badge تعرض عدد الإشعارات غير المقروءة
- 📱 قائمة منسدلة تعرض آخر 10 إشعارات
- ⚡ تحديث تلقائي كل 15 ثانية (Polling)
- 🔊 صوت تنبيه عند وصول إشعار جديد
- 🎨 ألوان مميزة لكل نوع من الإشعارات

### 2. Toast Notifications
- 🎯 رسالة منبثقة أسفل الشاشة عند وصول إشعار جديد
- ⏱️ تختفي تلقائياً بعد 5 ثوان
- 🖱️ إمكانية إغلاقها يدوياً

### 3. صفحة الإشعارات الكاملة
- 📊 إحصائيات شاملة (إجمالي - غير مقروءة - اليوم - هذا الأسبوع)
- 🔍 فلترة حسب النوع (طلبات، تسجيلات، رسائل، إلخ)
- 🔍 فلترة حسب الحالة (مقروءة / غير مقروءة)
- 📄 صفحات متعددة (Pagination)
- ✔️ تعليم الكل كمقروء
- 🗑️ حذف الإشعارات المقروءة
- 🗑️ حذف إشعارات فردية

## 📁 الملفات المضافة

### Models
```
app/Models/AdminNotification.php
```

### Controllers
```
app/Http/Controllers/Admin/NotificationController.php
```

### Views
```
resources/views/admin/notifications/index.blade.php
```

### JavaScript
```
public/js/admin-notifications.js
```

### Migrations
```
database/migrations/2026_02_08_204718_create_admin_notifications_table.php
```

## 🔧 التعديلات على الملفات الموجودة

تم تعديل الملفات التالية لإضافة إنشاء إشعارات تلقائية:

1. **app/Http/Controllers/CheckoutController.php**
   - إضافة إشعار عند إنشاء طلب جديد

2. **app/Http/Controllers/Admin/OrderController.php**
   - إضافة إشعار عند تحديث حالة الطلب

3. **app/Http/Controllers/Auth/RegisteredUserController.php**
   - إضافة إشعار عند تسجيل عميل جديد

4. **app/Http/Controllers/Auth/AuthenticatedSessionController.php**
   - إضافة إشعار عند تسجيل دخول مستخدم

5. **app/Http/Controllers/ContactController.php**
   - إضافة إشعار عند استلام رسالة جديدة

6. **routes/admin.php**
   - إضافة مسارات الإشعارات

7. **resources/views/admin/layouts/app.blade.php**
   - إضافة سكريبت نظام الإشعارات
   - إضافة رابط صفحة الإشعارات في القائمة الجانبية

## 📊 جدول قاعدة البيانات

```sql
admin_notifications
├── id
├── type (order, login, registration, email, message)
├── title
├── message
├── data (JSON - بيانات إضافية)
├── icon (Font Awesome class)
├── url (رابط التفاصيل)
├── is_read
├── read_at
├── created_at
└── updated_at
```

## 🎨 أنواع الإشعارات والألوان

| النوع | اللون | الأيقونة |
|------|-------|---------|
| الطلبات | 🟢 أخضر | fas fa-shopping-cart |
| تسجيلات الدخول | 🔵 أزرق | fas fa-sign-in-alt |
| التسجيلات | 🟣 بنفسجي | fas fa-user-plus |
| البريد | 🟠 برتقالي | fas fa-envelope |
| الرسائل | 🌸 وردي | fas fa-comment-dots |

## 🚀 الاستخدام

### إنشاء إشعار يدوياً

```php
use App\Models\AdminNotification;

// إشعار طلب جديد
AdminNotification::createOrderNotification($order);

// إشعار تسجيل دخول
AdminNotification::createLoginNotification($user);

// إشعار تسجيل جديد
AdminNotification::createRegistrationNotification($user);

// إشعار بريد إلكتروني
AdminNotification::createEmailNotification($subject, $recipient);

// إشعار رسالة جديدة
AdminNotification::createMessageNotification($message);

// إشعار مخصص
AdminNotification::create([
    'type' => 'custom',
    'title' => 'عنوان الإشعار',
    'message' => 'نص الإشعار',
    'icon' => 'fas fa-info-circle',
    'url' => '/admin/somewhere',
    'data' => ['key' => 'value'], // اختياري
]);
```

### استعلامات مفيدة

```php
// جلب الإشعارات غير المقروءة
$unread = AdminNotification::unread()->get();

// جلب إشعارات الطلبات فقط
$orderNotifications = AdminNotification::byType('order')->get();

// جلب آخر 10 إشعارات
$recent = AdminNotification::recent(10)->get();

// تعليم إشعار كمقروء
$notification->markAsRead();
```

## ⚙️ إعدادات JavaScript

يمكنك تعديل إعدادات نظام الإشعارات في ملف `public/js/admin-notifications.js`:

```javascript
this.pollingFrequency = 15000; // تردد التحديث بالميلي ثانية (15 ثانية)
```

## 🔗 المسارات (Routes)

### API Endpoints
```
GET  /admin/notifications/recent         - جلب آخر الإشعارات
GET  /admin/notifications/unread-count   - عدد غير المقروءة
POST /admin/notifications/{id}/mark-read - تعليم كمقروء
POST /admin/notifications/mark-all-read  - تعليم الكل كمقروء
DELETE /admin/notifications/{id}         - حذف إشعار
POST /admin/notifications/clear-read     - حذف المقروءة
```

### صفحات الويب
```
GET /admin/notifications - صفحة عرض جميع الإشعارات
```

## 📱 التوافق

- ✅ متجاوب مع جميع الشاشات
- ✅ يعمل على جميع المتصفحات الحديثة
- ✅ دعم RTL كامل للغة العربية
- ✅ يعمل مع Alpine.js و Tailwind CSS

## 🎯 نصائح الأداء

1. **Polling Frequency**: حالياً مضبوط على 15 ثانية - يمكن تعديله حسب الحاجة
2. **Database Indexing**: تم إضافة indexes على `is_read` و `type` و `created_at`
3. **Pagination**: الإشعارات مقسمة على صفحات (20 إشعار لكل صفحة)
4. **Auto-cleanup**: يمكن إضافة scheduled job لحذف الإشعارات القديمة

## 📝 ملاحظات إضافية

- الإشعارات تظهر فقط للمسؤولين (Admin)
- يتم تخزين جميع الإشعارات في قاعدة البيانات
- الإشعارات المقروءة تبقى في النظام حتى يتم حذفها يدوياً
- يمكن توسيع النظام لدعم أنواع إشعارات جديدة بسهولة

## 🔮 تطويرات مستقبلية محتملة

- [ ] إشعارات في الوقت الفعلي باستخدام WebSockets/Pusher
- [ ] إعدادات تفضيلات الإشعارات لكل مسؤول
- [ ] إشعارات عبر البريد الإلكتروني
- [ ] إشعارات عبر SMS
- [ ] تقارير إحصائية متقدمة

---

**تم التطوير بواسطة**: GitHub Copilot  
**التاريخ**: 8 فبراير 2026
