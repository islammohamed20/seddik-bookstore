@extends('admin.layouts.app')

@section('title', 'الطلبات')
@section('page-title', 'إدارة الطلبات')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="رقم الطلب أو العميل..."
                   class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            <select name="status" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                <option value="">كل الحالات</option>
                @foreach($statuses as $key => $name)
                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                   class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                   class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            <div class="flex gap-2">
                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-900 flex-1">
                    <i class="fas fa-search"></i>
                </button>
                <a href="{{ route('admin.orders.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
    
    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">رقم الطلب</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">العميل</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">المنتجات</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">المبلغ</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الحالة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">التاريخ</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                #{{ $order->order_number }}
                            </a>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-sm text-gray-800">{{ $order->user->name ?? 'زائر' }}</p>
                            <p class="text-xs text-gray-500">{{ $order->user->email ?? '' }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $order->items->count() }} منتج</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ number_format($order->grand_total, 2) }} ج.م</td>
                        <td class="px-4 py-3">
                            @php
                                $statusClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'processing' => 'bg-blue-100 text-blue-800',
                                    'shipped' => 'bg-purple-100 text-purple-800',
                                    'delivered' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs rounded-full {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statuses[$order->status] ?? $order->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $order->created_at->format('Y/m/d H:i') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-800">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">لا توجد طلبات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{ $orders->links() }}
</div>
@endsection
