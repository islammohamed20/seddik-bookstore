<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">اسم الخاصية (عربي) <span class="text-red-500">*</span></label>
        <input type="text" name="name_ar" value="{{ old('name_ar', $attribute->name_ar ?? '') }}" required
               class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">اسم الخاصية (إنجليزي)</label>
        <input type="text" name="name_en" value="{{ old('name_en', $attribute->name_en ?? '') }}"
               class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">نوع الإدخال <span class="text-red-500">*</span></label>
        <select name="input_type" required
                class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            @php
                $types = [
                    'select' => 'قائمة منسدلة',
                    'radio' => 'اختيار واحد (Radio)',
                    'checkbox' => 'اختيارات متعددة (Checkbox)',
                ];
                $currentType = old('input_type', $attribute->input_type ?? 'select');
            @endphp
            @foreach($types as $key => $label)
                <option value="{{ $key }}" {{ $currentType === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">الترتيب</label>
        <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $attribute->sort_order ?? 0) }}"
               class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
    </div>
</div>

<div class="mt-6">
    <label class="block text-sm font-medium text-gray-700 mb-1.5">قائمة القيم (افصل بين القيم بفاصلة)</label>
    <textarea name="options" rows="3"
              class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
              placeholder="مثال: أحمر, أزرق, أخضر">{{ old('options', isset($attribute) && is_array($attribute->options) ? implode(', ', $attribute->options) : '') }}</textarea>
    <p class="text-xs text-gray-500 mt-1">سيتم استخدام هذه القيم عند إضافة المتغيرات.</p>
</div>

<div class="mt-6">
    <label class="block text-sm font-medium text-gray-700 mb-1.5">قواعد التحقق (اختياري)</label>
    <input type="text" name="validation_rules" value="{{ old('validation_rules', $attribute->validation_rules ?? '') }}"
           class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
           placeholder="مثال: required|string|max:255">
</div>

<div class="mt-6 flex items-center gap-2">
    @php
        $isActive = old('is_active', $attribute->is_active ?? true);
    @endphp
    <input type="checkbox" name="is_active" value="1" {{ $isActive ? 'checked' : '' }}
           class="rounded text-indigo-600 border-gray-300 focus:ring-indigo-500">
    <span class="text-sm text-gray-700">نشط</span>
</div>
