@extends('admin.layouts.app')

@section('title', 'التصنيفات')
@section('page-title', 'إدارة التصنيفات')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <p class="text-gray-600">إجمالي {{ $categories->total() }} تصنيف</p>
        <a href="{{ route('admin.categories.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
            <i class="fas fa-plus ml-2"></i>إضافة تصنيف
        </a>
    </div>
    
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4">
        <form action="{{ route('admin.categories.index') }}" method="GET" class="flex gap-4">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="بحث..."
                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <select name="is_active" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                <option value="">كل الحالات</option>
                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>نشط</option>
                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>غير نشط</option>
            </select>
            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-900">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
    
    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">التصنيف</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الأيقونة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">التصنيف الأب</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">المنتجات</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الترتيب</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">مميز</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الحالة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($categories as $category)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" 
                                         alt="{{ $category->name_ar ?: $category->name_en }}"
                                         class="w-10 h-10 rounded-lg object-cover">
                                @else
                                    <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-folder text-gray-400"></i>
                                    </div>
                                @endif
                                <div class="mr-3">
                                    <div class="font-medium text-gray-800">{{ $category->name_ar ?: $category->name_en }}</div>
                                    @if($category->name_en && $category->name_ar !== $category->name_en)
                                        <div class="text-sm text-gray-500">{{ $category->name_en }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @if($category->icon)
                                <i class="fas {{ $category->icon }} text-xl text-indigo-600"></i>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $category->parent->name_ar ?? $category->parent->name_en ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $category->products_count }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $category->sort_order }}</td>
                        <td class="px-4 py-3">
                            <form action="{{ route('admin.categories.toggle-featured', $category) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-2 py-1 text-xs rounded-full {{ $category->is_featured ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $category->is_featured ? '⭐ مميز' : 'عادي' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-3">
                            <form action="{{ route('admin.categories.toggle-status', $category) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-2 py-1 text-xs rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $category->is_active ? 'نشط' : 'غير نشط' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="text-indigo-600 hover:text-indigo-800">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا التصنيف؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-500">لا توجد تصنيفات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{ $categories->links() }}
</div>
@endsection
