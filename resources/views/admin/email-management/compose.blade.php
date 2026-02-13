@extends('admin.layouts.app')

@section('title', 'إرسال بريد جماعي')
@section('page-title', 'إرسال بريد جماعي')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <p class="text-gray-600 dark:text-slate-400">أرسل رسالة لجميع المشتركين النشطين</p>
        </div>
        <a href="{{ route('admin.email-management.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-slate-600 text-gray-700 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-slate-500 transition">
            <i class="fas fa-arrow-right ml-2"></i>
            رجوع للقائمة
        </a>
    </div>

    <!-- Alerts -->
    @if(session('success'))
    <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-xl p-4 flex items-start gap-3">
        <i class="fas fa-check-circle text-green-600 dark:text-green-400 mt-0.5"></i>
        <p class="text-green-800 dark:text-green-200">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl p-4 flex items-start gap-3">
        <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400 mt-0.5"></i>
        <p class="text-red-800 dark:text-red-200">{{ session('error') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Compose Form -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700">
                <div class="p-6 border-b border-gray-100 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">تفاصيل الرسالة</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.email-management.send') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Recipient Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                                المستلمون <span class="text-red-500">*</span>
                            </label>
                            <select name="recipient_type" class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('recipient_type') border-red-500 @enderror" required>
                                <option value="active">جميع المشتركين النشطين ({{ $subscribersCount }})</option>
                                <option value="all">جميع المشتركين (متضمن غير النشطين)</option>
                                <option value="test">اختبار (إرسال لي فقط)</option>
                            </select>
                            @error('recipient_type')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Subject -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                                عنوان الرسالة <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="subject" 
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('subject') border-red-500 @enderror" 
                                   placeholder="مثال: عروض خاصة على جميع المنتجات"
                                   value="{{ old('subject') }}"
                                   required>
                            @error('subject')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Message -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                                محتوى الرسالة <span class="text-red-500">*</span>
                            </label>
                            <textarea name="message" 
                                      rows="12" 
                                      class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('message') border-red-500 @enderror" 
                                      placeholder="اكتب محتوى الرسالة هنا..."
                                      required>{{ old('message') }}</textarea>
                            @error('message')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-500 dark:text-slate-400">
                                <i class="fas fa-info-circle ml-1"></i>
                                يمكنك استخدام HTML لتنسيق الرسالة
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-4 pt-4 border-t border-gray-100 dark:border-slate-700">
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium"
                                    onclick="return confirm('هل أنت متأكد من إرسال هذه الرسالة؟');">
                                <i class="fas fa-paper-plane ml-2"></i>
                                إرسال الآن
                            </button>
                            <a href="{{ route('admin.email-management.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-200 dark:bg-slate-600 text-gray-700 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-slate-500 transition font-medium">
                                إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Tips -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Tips Card -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700">
                <div class="p-6 border-b border-gray-100 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fas fa-lightbulb text-amber-500"></i>
                        نصائح للإرسال
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-check text-green-500 mt-1"></i>
                        <p class="text-sm text-gray-600 dark:text-slate-400">اختر عنواناً جذاباً ومختصراً</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="fas fa-check text-green-500 mt-1"></i>
                        <p class="text-sm text-gray-600 dark:text-slate-400">تجنب الكلمات المزعجة مثل "مجاني" و "اربح"</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="fas fa-check text-green-500 mt-1"></i>
                        <p class="text-sm text-gray-600 dark:text-slate-400">اختبر الرسالة أولاً قبل الإرسال الجماعي</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <i class="fas fa-check text-green-500 mt-1"></i>
                        <p class="text-sm text-gray-600 dark:text-slate-400">أضف دعوة واضحة للإجراء (CTA)</p>
                    </div>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-sm p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold">إحصائيات سريعة</h4>
                    <i class="fas fa-chart-pie text-2xl opacity-50"></i>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="opacity-80">المشتركين النشطين</span>
                        <span class="font-bold">{{ $subscribersCount }}</span>
                    </div>
                    <div class="h-px bg-white/20"></div>
                    <p class="text-sm opacity-70">
                        <i class="fas fa-info-circle ml-1"></i>
                        سيتم إرسال الرسالة لهذا العدد من المشتركين
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
