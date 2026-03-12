@extends('admin.layouts.app')

@section('title', 'تعديل مجموعة التاجات: ' . $tag->name_ar)
@section('page-title', 'تعديل مجموعة التاجات')

@section('content')
<div class="space-y-6" x-data="tagGroupForm()">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">تعديل مجموعة التاجات</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $tag->name_ar }}</p>
        </div>
        <a href="{{ route('admin.tags.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800 transition">
            <i class="fas fa-arrow-right"></i>
            رجوع للقائمة
        </a>
    </div>

    <form action="{{ route('admin.tags.update', $tag) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- معلومات المجموعة -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800 border-b pb-2">معلومات المجموعة</h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الاسم بالعربي <span class="text-red-500">*</span></label>
                        <input type="text" name="name_ar" value="{{ old('name_ar', $tag->name_ar) }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الاسم بالإنجليزي</label>
                        <input type="text" name="name_en" value="{{ old('name_en', $tag->name_en) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">الترتيب</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $tag->sort_order) }}" min="0"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" id="is_active"
                               {{ old('is_active', $tag->is_active) ? 'checked' : '' }}
                               class="rounded text-indigo-600 focus:ring-indigo-500">
                        <label for="is_active" class="text-sm text-gray-700">نشط</label>
                    </div>

                    <div class="pt-3 border-t">
                        <p class="text-xs text-gray-400">Slug: <span class="font-mono">{{ $tag->slug }}</span></p>
                        <p class="text-xs text-gray-400 mt-1">تاريخ الإنشاء: {{ $tag->created_at->format('Y/m/d') }}</p>
                    </div>
                </div>
            </div>

            <!-- الخيارات -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4 border-b pb-2">
                        <h3 class="font-semibold text-gray-800">الخيارات (Tags)</h3>
                        <button type="button" @click="addOption()"
                                class="inline-flex items-center gap-1 text-sm bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg transition">
                            <i class="fas fa-plus"></i>
                            إضافة خيار
                        </button>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(option, index) in options" :key="index">
                            <div class="flex items-center gap-3 bg-gray-50 rounded-lg p-3">
                                <span class="text-gray-400 text-sm font-mono w-6 text-center" x-text="index + 1"></span>
                                <input type="hidden" :name="'options[' + index + '][id]'" x-model="option.id">
                                <div class="flex-1">
                                    <input type="text" :name="'options[' + index + '][name_ar]'" x-model="option.name_ar"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="الاسم بالعربي *" required>
                                </div>
                                <div class="flex-1">
                                    <input type="text" :name="'options[' + index + '][name_en]'" x-model="option.name_en"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                           placeholder="الاسم بالإنجليزي">
                                </div>
                                <button type="button" @click="removeOption(index)"
                                        class="text-red-500 hover:text-red-700 p-1 transition">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </template>

                        <div x-show="options.length === 0" class="text-center py-8 text-gray-400">
                            <i class="fas fa-tags text-3xl mb-2"></i>
                            <p class="text-sm">لم يتم إضافة خيارات بعد. اضغط "إضافة خيار" للبدء.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end mt-6 gap-3">
            <a href="{{ route('admin.tags.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800 px-4 py-2.5 border border-gray-300 rounded-lg transition">
                إلغاء
            </a>
            <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium transition">
                <i class="fas fa-save"></i>
                حفظ التعديلات
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function tagGroupForm() {
    return {
        options: @json($tag->options->map(fn($o) => ['id' => $o->id, 'name_ar' => $o->name_ar, 'name_en' => $o->name_en])),
        addOption() {
            this.options.push({ id: null, name_ar: '', name_en: '' });
        },
        removeOption(index) {
            if (this.options[index].id && !confirm('هل أنت متأكد من حذف هذا الخيار؟ سيتم إزالته من جميع المنتجات المرتبطة.')) {
                return;
            }
            this.options.splice(index, 1);
        }
    }
}
</script>
@endpush
@endsection
