@extends('admin.layouts.app')

@section('title', 'خصائص المنتجات')
@section('page-title', 'خصائص المنتجات')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">خصائص المنتجات</h2>
            <p class="text-sm text-gray-500">أضف خصائص عامة وقيمها لاستخدامها داخل المتغيرات.</p>
        </div>
        <a href="{{ route('admin.product-attributes.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700">
            <i class="fas fa-plus"></i>
            إضافة خاصية
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <form method="GET" class="flex items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="flex-1 rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                   placeholder="بحث بالاسم أو الـ slug">
            <button type="submit" class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 text-sm">بحث</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-right py-3 px-4 text-gray-600">الاسم</th>
                        <th class="text-right py-3 px-4 text-gray-600">النوع</th>
                        <th class="text-right py-3 px-4 text-gray-600">القيم</th>
                        <th class="text-right py-3 px-4 text-gray-600">الحالة</th>
                        <th class="text-right py-3 px-4 text-gray-600">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attributes as $attribute)
                        <tr class="border-b border-gray-100">
                            <td class="py-3 px-4">
                                <div class="font-medium text-gray-800">{{ $attribute->name_ar }}</div>
                                <div class="text-xs text-gray-500">{{ $attribute->slug }}</div>
                            </td>
                            <td class="py-3 px-4 text-gray-600">{{ $attribute->input_type }}</td>
                            <td class="py-3 px-4">
                                @if(is_array($attribute->options))
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($attribute->options as $opt)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-indigo-50 text-indigo-700">{{ $opt }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($attribute->is_active)
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-green-100 text-green-800">نشط</span>
                                @else
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-red-100 text-red-800">معطّل</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.product-attributes.edit', $attribute) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-indigo-600 hover:bg-indigo-50">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.product-attributes.toggle-status', $attribute) }}">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-amber-600 hover:bg-amber-50">
                                            <i class="fas fa-toggle-{{ $attribute->is_active ? 'on' : 'off' }}"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.product-attributes.destroy', $attribute) }}"
                                          onsubmit="return confirm('هل تريد حذف هذه الخاصية؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-600 hover:bg-red-50">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-right text-gray-500">لا توجد خصائص بعد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4">
            {{ $attributes->links() }}
        </div>
    </div>
</div>
@endsection
