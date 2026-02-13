@extends('admin.layouts.app')

@section('title', 'الكوبونات')
@section('page-title', 'إدارة الكوبونات')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <p class="text-gray-600">إجمالي {{ $coupons->total() }} كوبون</p>
        <a href="{{ route('admin.coupons.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
            <i class="fas fa-plus ml-2"></i>إضافة كوبون
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الكود</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الخصم</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الاستخدامات</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الصلاحية</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الحالة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($coupons as $coupon)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono font-bold text-indigo-600">{{ $coupon->code }}</td>
                        <td class="px-4 py-3">
                            {{ $coupon->type === 'percentage' ? $coupon->value . '%' : number_format($coupon->value, 2) . ' ج.م' }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $coupon->usages_count }} / {{ $coupon->max_uses ?? '∞' }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @if($coupon->expires_at)
                                <span class="{{ $coupon->expires_at->isPast() ? 'text-red-600' : 'text-gray-600' }}">
                                    {{ $coupon->expires_at->format('Y/m/d') }}
                                </span>
                            @else
                                <span class="text-gray-400">غير محدد</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <form action="{{ route('admin.coupons.toggle-status', $coupon) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-2 py-1 text-xs rounded-full {{ $coupon->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $coupon->is_active ? 'نشط' : 'غير نشط' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-indigo-600 hover:text-indigo-800">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="inline"
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
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">لا توجد كوبونات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $coupons->links() }}
</div>
@endsection
