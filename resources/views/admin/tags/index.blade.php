@extends('admin.layouts.app')

@section('title', 'إدارة التاجات')
@section('page-title', 'إدارة التاجات')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">إدارة التاجات</h1>
            <p class="text-sm text-gray-500 mt-1">إنشاء وإدارة مجموعات التاجات وخياراتها لتصنيف المنتجات</p>
        </div>
        <a href="{{ route('admin.tags.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-plus"></i>
            إضافة مجموعة جديدة
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-right">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">الاسم بالعربي</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">الاسم بالإنجليزي</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">عدد الخيارات</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">الحالة</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">الترتيب</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($tagGroups as $group)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $group->id }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $group->name_ar }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $group->name_en ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                {{ $group->options_count }} خيار
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <form action="{{ route('admin.tags.toggle-status', $group) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $group->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fas {{ $group->is_active ? 'fa-check-circle' : 'fa-times-circle' }} ml-1"></i>
                                    {{ $group->is_active ? 'نشط' : 'معطل' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $group->sort_order }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.tags.edit', $group) }}" class="text-indigo-600 hover:text-indigo-800" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.tags.destroy', $group) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه المجموعة وجميع خياراتها؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-right text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-tags text-gray-300 text-5xl mb-4"></i>
                                <p>لا توجد مجموعات تاجات حالياً</p>
                                <a href="{{ route('admin.tags.create') }}" class="mt-3 text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                    <i class="fas fa-plus ml-1"></i> إنشاء مجموعة جديدة
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $tagGroups->links() }}
</div>
@endsection
