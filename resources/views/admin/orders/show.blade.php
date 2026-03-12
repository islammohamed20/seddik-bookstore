@extends('admin.layouts.app')

@section('title', 'تفاصيل الطلب')
@section('page-title', 'الطلب #' . $order->order_number)

@section('content')
<div class="space-y-6">
    <!-- Order Status Update -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">تحديث حالة الطلب</h3>
                <p class="text-sm text-gray-500">تاريخ الطلب: {{ $order->created_at->format('Y/m/d H:i') }}</p>
            </div>
            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="flex items-center gap-3">
                @csrf
                <select name="status" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    @foreach($statuses as $key => $name)
                        <option value="{{ $key }}" {{ $order->status === $key ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                    تحديث
                </button>
            </form>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Items -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">المنتجات</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">المنتج</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">السعر</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الكمية</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الإجمالي</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($order->items as $item)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        @if($item->product && $item->product->images->first())
                                            <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" 
                                                 class="w-12 h-12 rounded-lg object-cover">
                                        @else
                                            <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div class="mr-3">
                                            <p class="font-medium text-gray-800">{{ $item->product->name ?? $item->product_name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($item->price, 2) }} ج.م</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $item->quantity }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ number_format($item->price * $item->quantity, 2) }} ج.م</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-right font-medium">المجموع الفرعي</td>
                            <td class="px-4 py-3 font-medium">{{ number_format($order->subtotal, 2) }} ج.م</td>
                        </tr>
                        @if($order->discount_amount > 0)
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right text-green-600">الخصم</td>
                                <td class="px-4 py-3 text-green-600">-{{ number_format($order->discount_amount, 2) }} ج.م</td>
                            </tr>
                        @endif
                        @if($order->shipping_cost > 0)
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right">الشحن</td>
                                <td class="px-4 py-3">{{ number_format($order->shipping_cost, 2) }} ج.م</td>
                            </tr>
                        @endif
                        <tr class="text-lg">
                            <td colspan="3" class="px-4 py-3 text-right font-bold">الإجمالي</td>
                            <td class="px-4 py-3 font-bold text-indigo-600">{{ number_format($order->grand_total, 2) }} ج.م</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
        <!-- Customer & Shipping Info -->
        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">معلومات العميل</h3>
                @if($order->user)
                    <div class="space-y-2">
                        <p><span class="text-gray-500">الاسم:</span> {{ $order->user->name }}</p>
                        <p><span class="text-gray-500">البريد:</span> {{ $order->user->email }}</p>
                        <p><span class="text-gray-500">الهاتف:</span> {{ $order->user->phone ?? '-' }}</p>
                    </div>
                @else
                    <p class="text-gray-500">زائر</p>
                @endif
            </div>
            
            <!-- Shipping Address -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">عنوان الشحن</h3>
                <div class="space-y-2 text-sm">
                    <p>{{ $order->shipping_name }}</p>
                    <p>{{ $order->shipping_phone }}</p>
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_city }}{{ $order->shipping_state ? ', ' . $order->shipping_state : '' }}</p>
                </div>
            </div>
            
            <!-- Payment Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">معلومات الدفع</h3>
                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-500">طريقة الدفع:</span> {{ $order->payment_method }}</p>
                    <p>
                        <span class="text-gray-500">حالة الدفع:</span>
                        <span class="px-2 py-1 text-xs rounded-full {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $order->payment_status === 'paid' ? 'مدفوع' : 'قيد الانتظار' }}
                        </span>
                    </p>
                </div>
            </div>
            
            <!-- Coupon Info -->
            @if($order->coupon)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">الكوبون المستخدم</h3>
                    <p class="text-indigo-600 font-medium">{{ $order->coupon->code }}</p>
                    <p class="text-sm text-gray-500">
                        خصم: {{ $order->coupon->type === 'percentage' ? $order->coupon->value . '%' : number_format($order->coupon->value, 2) . ' ج.م' }}
                    </p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Order Notes -->
    @if($order->notes)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">ملاحظات</h3>
            <p class="text-gray-600">{{ $order->notes }}</p>
        </div>
    @endif
    
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.orders.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
            <i class="fas fa-arrow-right ml-2"></i>العودة للطلبات
        </a>
    </div>
</div>
@endsection
