@extends('admin.layouts.app')

@section('title', 'الإعدادات')
@section('page-title', 'إعدادات الموقع')

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6 max-w-4xl">
    @csrf
    
    <!-- General Settings -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">الإعدادات العامة</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">اسم الموقع</label>
                <input type="text" name="site_name" value="{{ $settings['site_name'] ?? '' }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                <input type="email" name="site_email" value="{{ $settings['site_email'] ?? '' }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">رقم الهاتف</label>
                <input type="text" name="site_phone" value="{{ $settings['site_phone'] ?? '' }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">العنوان</label>
                <input type="text" name="site_address" value="{{ $settings['site_address'] ?? '' }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">وصف الموقع</label>
                <textarea name="site_description" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">{{ $settings['site_description'] ?? '' }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">رابط الموقع على خريطة جوجل</label>
                <input type="url" name="google_maps_url" value="{{ $settings['google_maps_url'] ?? '' }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500"
                       placeholder="https://www.google.com/maps/...">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">تفاصيل Location (وصف نصي للموقع)</label>
                <textarea name="location_details" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500"
                          placeholder="مثال: بجوار الوطنية مول، أمام ...">{{ $settings['location_details'] ?? '' }}</textarea>
            </div>
        </div>
    </div>
    
    <!-- Logo & Favicon -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">الشعار</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">شعار الموقع</label>
                @if(!empty($settings['site_logo']))
                    <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Logo" class="h-16 mb-2">
                @endif
                <input type="file" name="site_logo" accept="image/*"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">أيقونة الموقع (Favicon)</label>
                @if(!empty($settings['site_favicon']))
                    <img src="{{ asset('storage/' . $settings['site_favicon']) }}" alt="Favicon" class="h-8 mb-2">
                @endif
                <input type="file" name="site_favicon" accept=".ico,.png"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>
        </div>
    </div>
    
    <!-- Currency & Shipping -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">العملة والشحن</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">العملة</label>
                <input type="text" name="currency" value="{{ $settings['currency'] ?? 'EGP' }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">رمز العملة</label>
                <input type="text" name="currency_symbol" value="{{ $settings['currency_symbol'] ?? 'ج.م' }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">تكلفة الشحن</label>
                <input type="number" name="shipping_cost" value="{{ $settings['shipping_cost'] ?? 0 }}" step="0.01" min="0"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الحد الأدنى للشحن المجاني</label>
                <input type="number" name="free_shipping_threshold" value="{{ $settings['free_shipping_threshold'] ?? 0 }}" step="0.01" min="0"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">نسبة الضريبة (%)</label>
                <input type="number" name="tax_rate" value="{{ $settings['tax_rate'] ?? 0 }}" step="0.01" min="0" max="100"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>
    </div>

    <!-- Cart & Inventory Settings -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">إعدادات سلة التسوق والمخزون</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الحد الأدنى للسلة داخل أسيوط</label>
                <input type="number" name="cart_min_order_inside_assiut" value="{{ $settings['cart_min_order_inside_assiut'] ?? 50 }}" step="0.01" min="0"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الحد الأدنى للسلة خارج أسيوط</label>
                <input type="number" name="cart_min_order_outside_assiut" value="{{ $settings['cart_min_order_outside_assiut'] ?? 500 }}" step="0.01" min="0"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">أقصى عدد عناصر في السلة</label>
                <input type="number" name="cart_max_items" value="{{ $settings['cart_max_items'] ?? '' }}" min="1"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500"
                       placeholder="اتركه فارغًا لعدم التقييد">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">أقصى كمية للمنتج الواحد</label>
                <input type="number" name="cart_max_qty_per_item" value="{{ $settings['cart_max_qty_per_item'] ?? '' }}" min="1"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500"
                       placeholder="اتركه فارغًا لعدم التقييد">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الحد الافتراضي لتنبيه المخزون المنخفض</label>
                <input type="number" name="default_low_stock_threshold" value="{{ $settings['default_low_stock_threshold'] ?? 5 }}" min="0"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                <p class="text-xs text-gray-500 mt-1">يُستخدم كقيمة افتراضية لحقل حد التنبيه في المنتجات الجديدة.</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">تفريغ السلة بعد (بالساعات)</label>
                <input type="number" name="cart_auto_clear_hours" value="{{ $settings['cart_auto_clear_hours'] ?? '' }}" min="1"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500"
                       placeholder="اتركه فارغًا لعدم التقييد">
            </div>
        </div>

        <div class="mt-6 space-y-3">
            <label class="flex items-center cursor-pointer">
                <input type="checkbox" name="cart_allow_guest" value="1" {{ ($settings['cart_allow_guest'] ?? '1') == '1' ? 'checked' : '' }}
                       class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                <span class="mr-2 text-sm text-gray-700">السماح بإضافة للسلة بدون تسجيل</span>
            </label>
            <label class="flex items-center cursor-pointer">
                <input type="checkbox" name="cart_allow_notes" value="1" {{ ($settings['cart_allow_notes'] ?? '1') == '1' ? 'checked' : '' }}
                       class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                <span class="mr-2 text-sm text-gray-700">السماح بملاحظات على السلة/الطلب</span>
            </label>
        </div>
    </div>
    
    <!-- Social Media -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">روابط التواصل الاجتماعي</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fab fa-facebook text-blue-600 ml-1"></i>Facebook
                </label>
                <input type="url" name="facebook_url" value="{{ $settings['facebook_url'] ?? '' }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fab fa-twitter text-blue-400 ml-1"></i>Twitter
                </label>
                <input type="url" name="twitter_url" value="{{ $settings['twitter_url'] ?? '' }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fab fa-instagram text-pink-600 ml-1"></i>Instagram
                </label>
                <input type="url" name="instagram_url" value="{{ $settings['instagram_url'] ?? '' }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fab fa-youtube text-red-600 ml-1"></i>YouTube
                </label>
                <input type="url" name="youtube_url" value="{{ $settings['youtube_url'] ?? '' }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fab fa-tiktok text-gray-900 ml-1"></i>TikTok
                </label>
                <input type="url" name="tiktok_url" value="{{ $settings['tiktok_url'] ?? '' }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fab fa-whatsapp text-green-600 ml-1"></i>WhatsApp
                </label>
                <input type="text" name="whatsapp_number" value="{{ $settings['whatsapp_number'] ?? '' }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>
    </div>
    
    <!-- SEO -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">تحسين محركات البحث (SEO)</h3>
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">عنوان الصفحة الرئيسية (Meta Title)</label>
                <input type="text" name="meta_title" value="{{ $settings['meta_title'] ?? '' }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">وصف الموقع (Meta Description)</label>
                <textarea name="meta_description" rows="2"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">{{ $settings['meta_description'] ?? '' }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الكلمات المفتاحية (Meta Keywords)</label>
                <input type="text" name="meta_keywords" value="{{ $settings['meta_keywords'] ?? '' }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Google Analytics ID</label>
                <input type="text" name="google_analytics_id" value="{{ $settings['google_analytics_id'] ?? '' }}"
                       placeholder="UA-XXXXXXXXX-X or G-XXXXXXXXXX"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">الفوتر</h3>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">نص الفوتر</label>
            <textarea name="footer_text" rows="3"
                      class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">{{ $settings['footer_text'] ?? '' }}</textarea>
        </div>
    </div>
    
    <div class="flex items-center gap-4">
        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
            <i class="fas fa-save ml-2"></i>حفظ الإعدادات
        </button>
    </div>
</form>
@endsection
