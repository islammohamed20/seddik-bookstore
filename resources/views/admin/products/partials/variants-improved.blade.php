<div class="bg-white rounded-lg shadow p-6" x-data="variantsManager()">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800">متغيرات المنتج</h3>
        <button type="button"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700"
                @click="addVariant()">
            <i class="fas fa-plus"></i>
            إضافة متغير
        </button>
    </div>

    @php
        $attrs = ($attributes ?? collect())->filter(fn($a) => $a->is_active)->values();
    @endphp

    @if($attrs->isEmpty())
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-sm text-yellow-800">
            لا توجد خصائص نشطة لإضافة متغيرات. يمكنك إنشاء خصائص من قسم "خصائص المنتجات".
        </div>
    @else
        <template x-if="variants.length === 0">
            <div class="border border-dashed border-gray-300 rounded-xl p-6 text-center text-gray-500">
                لا توجد متغيرات بعد. اضغط "إضافة متغير" لإضافة أول متغير.
            </div>
        </template>

        <div class="space-y-4">
            <template x-for="(variant, idx) in variants" :key="idx">
                <div class="rounded-xl border border-gray-200">
                    <div class="px-4 py-3 bg-gray-50 flex items-center justify-between rounded-t-xl">
                        <div class="text-sm text-gray-700 font-medium">
                            متغير #<span x-text="idx + 1"></span>
                        </div>
                        <button type="button"
                                class="text-red-600 hover:text-red-700 text-sm"
                                @click="removeVariant(idx)">
                            <i class="fas fa-trash ml-1"></i> حذف المتغير
                        </button>
                    </div>
                    <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                            <input type="text"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   x-model="variant.sku"
                                   :name="`variants[${idx}][sku]`"
                                   placeholder="SKU اختياري">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">السعر (اختياري)</label>
                            <input type="number" step="0.01" min="0"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   x-model="variant.price"
                                   :name="`variants[${idx}][price]`"
                                   placeholder="اتركه فارغاً لاستخدام سعر المنتج">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الكمية المتاحة *</label>
                            <input type="number" min="0"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                   x-model="variant.stock_quantity"
                                   :name="`variants[${idx}][stock_quantity]`"
                                   placeholder="0">
                        </div>
                    </div>

                    <div class="px-4 pb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-3">
                            <p class="text-sm font-medium text-gray-700 mb-2">الخصائص</p>
                            <div class="grid grid-cols-1 md:grid-cols-{{ max(1, min(3, $attrs->count())) }} gap-3">
                                @foreach($attrs as $attr)
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">
                                            {{ $attr->display_name }}
                                        </label>
                                        @php
                                            $options = is_array($attr->options) ? $attr->options : [];
                                        @endphp
                                        @if(!empty($options))
                                            <select
                                                class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                                :name="`variants[${idx}][attributes][{{ $attr->id }}]`"
                                                x-model="variant.attributes['{{ $attr->id }}']">
                                                <option value="">— اختر —</option>
                                                @foreach($options as $opt)
                                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="text"
                                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                                   :name="`variants[${idx}][attributes][{{ $attr->id }}]`"
                                                   x-model="variant.attributes['{{ $attr->id }}']"
                                                   placeholder="أدخل قيمة">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox"
                                       class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                       x-model="variant.is_active"
                                       :name="`variants[${idx}][is_active]`"
                                       :value="1"
                                       :checked="variant.is_active">
                                <span class="mr-2 text-sm text-gray-700">نشط</span>
                            </label>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    @endif
</div>

<script>
function variantsManager() {
    return {
        variants: [],
        addVariant() {
            this.variants.push({
                sku: '',
                price: '',
                stock_quantity: 0,
                is_active: true,
                attributes: {}
            });
        },
        removeVariant(idx) {
            this.variants.splice(idx, 1);
        }
    }
}
</script>

