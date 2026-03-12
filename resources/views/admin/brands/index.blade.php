@extends('admin.layouts.app')

@section('title', 'العلامات التجارية')
@section('page-title', 'إدارة العلامات التجارية')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <p class="text-gray-600">إجمالي {{ $brands->total() }} علامة تجارية</p>
        <a href="{{ route('admin.brands.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
            <i class="fas fa-plus ml-2"></i>إضافة علامة تجارية
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow p-4">
        <form action="{{ route('admin.brands.index') }}" method="GET" class="flex gap-4">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="بحث..."
                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-900">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
    
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">العلامة التجارية</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">المنتجات</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الحالة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($brands as $brand)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                @if($brand->logo)
                                    <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name_ar ?: $brand->name_en }}" class="w-10 h-10 rounded-lg object-cover">
                                @else
                                    <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-tag text-gray-400"></i>
                                    </div>
                                @endif
                                <div class="mr-3">
                                    <span class="font-medium text-gray-800">{{ $brand->name_ar ?: $brand->name_en }}</span>
                                    @if($brand->name_ar && $brand->name_en)
                                        <span class="text-sm text-gray-500 block">{{ $brand->name_en }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $brand->products_count }}</td>
                        <td class="px-4 py-3">
                            <form action="{{ route('admin.brands.toggle-status', $brand) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-2 py-1 text-xs rounded-full {{ $brand->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $brand->is_active ? 'نشط' : 'غير نشط' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.brands.edit', $brand) }}" class="text-indigo-600 hover:text-indigo-800">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="inline"
                                      onsubmit="return confirm('هل أنت متأكد؟')">
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
                        <td colspan="4" class="px-4 py-8 text-right text-gray-500">لا توجد علامات تجارية</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $brands->links() }}
</div>
@endsection
