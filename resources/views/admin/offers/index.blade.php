@extends('admin.layouts.app')

@section('title', 'العروض')
@section('page-title', 'إدارة العروض')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <p class="text-gray-600">إجمالي {{ $offers->total() }} عرض</p>
        <a href="{{ route('admin.offers.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
            <i class="fas fa-plus ml-2"></i>إضافة عرض
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">العرض</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الخصم</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">المنتجات</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الفترة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">مميز</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الترتيب</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الحالة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($offers as $offer)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $offer->name }}</td>
                        <td class="px-4 py-3">
                            @if($offer->discount_type === 'percent')
                                {{ (float) ($offer->discount_value ?? 0) }}%
                            @elseif($offer->discount_type === 'fixed')
                                {{ number_format((float) ($offer->discount_value ?? 0), 2) }} ج.م
                            @else
                                شحن مجاني
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $offer->products_count }}</td>
                        <td class="px-4 py-3 text-sm">
                            {{ $offer->starts_at->format('Y/m/d') }} - {{ $offer->ends_at->format('Y/m/d') }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full {{ $offer->is_featured ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-600' }}">
                                {{ $offer->is_featured ? 'مميز' : 'عادي' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $offer->sort_order ?? 0 }}</td>
                        <td class="px-4 py-3">
                            <form action="{{ route('admin.offers.toggle-status', $offer) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-2 py-1 text-xs rounded-full {{ $offer->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $offer->is_active ? 'نشط' : 'غير نشط' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.offers.edit', $offer) }}" class="text-indigo-600 hover:text-indigo-800">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.offers.destroy', $offer) }}" method="POST" class="inline"
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
                        <td colspan="8" class="px-4 py-8 text-right text-gray-500">لا توجد عروض</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $offers->links() }}
</div>
@endsection
