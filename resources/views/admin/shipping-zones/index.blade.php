@extends('admin.layouts.app')

@section('title', 'مناطق الشحن')
@section('page-title', 'إدارة مناطق الشحن')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <p class="text-gray-600">إجمالي {{ $zones->total() }} منطقة شحن</p>
        <a href="{{ route('admin.shipping-zones.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
            <i class="fas fa-plus ml-2"></i>إضافة منطقة شحن
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow p-4">
        <form action="{{ route('admin.shipping-zones.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="بحث..."
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <select name="is_active" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    <option value="">كل الحالات</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>نشط</option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>غير نشط</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-900 flex-1">
                    <i class="fas fa-search ml-1"></i> بحث
                </button>
                <a href="{{ route('admin.shipping-zones.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
                    مسح
                </a>
            </div>
        </form>
    </div>
    
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">المنطقة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">طرق الشحن</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الحد الأدنى للطلب</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الحالة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($zones as $zone)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div>
                                <div class="font-medium text-gray-800">
                                    {{ $zone->name_ar ?: $zone->name_en }}
                                    @if($zone->name_ar && $zone->name_en)
                                        <span class="text-sm text-gray-500 block">{{ $zone->name_en }}</span>
                                    @endif
                                </div>
                                @if($zone->description_ar || $zone->description_en)
                                    <p class="text-sm text-gray-500 mt-1">{{ Str::limit($zone->description_ar ?: $zone->description_en, 50) }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full">
                                {{ $zone->shipping_methods_count }} طريقة
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if($zone->min_order_value > 0)
                                <span class="text-sm font-medium">{{ number_format($zone->min_order_value, 2) }} ج.م</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <form action="{{ route('admin.shipping-zones.toggle-status', $zone) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-2 py-1 text-xs rounded-full {{ $zone->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $zone->is_active ? 'نشط' : 'غير نشط' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.shipping-zones.show', $zone) }}" class="text-blue-600 hover:text-blue-800" title="عرض">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.shipping-zones.edit', $zone) }}" class="text-indigo-600 hover:text-indigo-800" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.shipping-zones.destroy', $zone) }}" method="POST" class="inline"
                                      onsubmit="return confirm('هل أنت متأكد؟ ستحذف جميع طرق الشحن المرتبطة بهذه المنطقة.')">
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
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            لا توجد مناطق شحن
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($zones->hasPages())
            <div class="px-4 py-3 border-t">
                {{ $zones->links() }}
            </div>
        @endif
    </div>
</div>
@endsection