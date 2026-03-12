@extends('admin.layouts.app')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-admin.stats-card
            title="إجمالي الإيرادات"
            :value="number_format($stats['total_revenue'], 2) . ' ج.م'"
            icon="fas fa-dollar-sign"
            color="green" />
        
        <x-admin.stats-card
            title="إيرادات اليوم"
            :value="number_format($stats['today_revenue'], 2) . ' ج.م'"
            icon="fas fa-calendar-day"
            color="blue" />
        
        <x-admin.stats-card
            title="إجمالي الطلبات"
            :value="number_format($stats['total_orders'])"
            icon="fas fa-shopping-cart"
            color="purple" />
        
        <x-admin.stats-card
            title="طلبات قيد الانتظار"
            :value="number_format($stats['pending_orders'])"
            icon="fas fa-clock"
            color="yellow" />
    </div>
    
    <!-- Second Row Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-admin.stats-card
            title="المنتجات"
            :value="number_format($stats['total_products'])"
            icon="fas fa-box"
            color="indigo"
            :subtitle="$stats['active_products'] . ' نشط'" />
        
        <x-admin.stats-card
            title="المستخدمين"
            :value="number_format($stats['total_users'])"
            icon="fas fa-users"
            color="pink" />
        
        <x-admin.stats-card
            title="التصنيفات"
            :value="number_format($stats['total_categories'])"
            icon="fas fa-folder"
            color="teal" />
        
        <x-admin.stats-card
            title="رسائل غير مقروءة"
            :value="number_format($stats['unread_messages'])"
            icon="fas fa-envelope"
            color="red" />
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Orders -->
        <x-admin.card :padding="false">
            <x-slot name="header">
                <h3 class="text-lg font-semibold text-gray-800">أحدث الطلبات</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                    عرض الكل <i class="fas fa-arrow-left mr-1"></i>
                </a>
            </x-slot>
            
            <x-admin.table :headers="['رقم الطلب', 'العميل', 'المبلغ', 'الحالة']" striped>
                @forelse($recentOrders as $order)
                    <x-admin.table.row>
                        <x-admin.table.cell>
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                #{{ $order->order_number }}
                            </a>
                        </x-admin.table.cell>
                        <x-admin.table.cell class="text-gray-600">
                            {{ $order->user->name ?? 'زائر' }}
                        </x-admin.table.cell>
                        <x-admin.table.cell class="font-medium">
                            {{ number_format($order->grand_total, 2) }} ج.م
                        </x-admin.table.cell>
                        <x-admin.table.cell>
                            @php
                                $statusVariants = [
                                    'pending' => 'warning',
                                    'processing' => 'info',
                                    'shipped' => 'purple',
                                    'delivered' => 'success',
                                    'cancelled' => 'danger',
                                ];
                                $statusNames = [
                                    'pending' => 'قيد الانتظار',
                                    'processing' => 'قيد المعالجة',
                                    'shipped' => 'تم الشحن',
                                    'delivered' => 'تم التوصيل',
                                    'cancelled' => 'ملغي',
                                ];
                            @endphp
                            <x-admin.badge :variant="$statusVariants[$order->status] ?? 'default'">
                                {{ $statusNames[$order->status] ?? $order->status }}
                            </x-admin.badge>
                        </x-admin.table.cell>
                    </x-admin.table.row>
                @empty
                    <tr>
                        <td colspan="4">
                            <x-admin.empty-state 
                                title="لا توجد طلبات"
                                description="لم يتم تسجيل أي طلبات بعد"
                                icon="fas fa-shopping-cart" />
                        </td>
                    </tr>
                @endforelse
            </x-admin.table>
        </x-admin.card>
        
        <!-- Top Selling Products -->
        <x-admin.card :padding="false">
            <x-slot name="header">
                <h3 class="text-lg font-semibold text-gray-800">المنتجات الأكثر مبيعاً</h3>
                <a href="{{ route('admin.products.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                    عرض الكل <i class="fas fa-arrow-left mr-1"></i>
                </a>
            </x-slot>
            
            <div class="divide-y divide-gray-100">
                @forelse($topProducts as $product)
                    <div class="flex items-center p-4 hover:bg-gray-50 transition-colors">
                        <div class="w-12 h-12 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                            @if($product->images->first())
                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <i class="fas fa-image"></i>
                                </div>
                            @endif
                        </div>
                        <div class="mr-4 flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $product->name }}</p>
                            <p class="text-xs text-gray-500">{{ number_format($product->price, 2) }} ج.م</p>
                        </div>
                        <div class="text-right">
                            <div class="flex flex-col items-end">
                                <span class="text-sm font-bold text-indigo-600">{{ $product->sold_quantity ?? 0 }}</span>
                                <span class="text-xs text-gray-500">مبيعات</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <x-admin.empty-state 
                        title="لا توجد مبيعات بعد"
                        description="لم يتم تسجيل أي مبيعات للمنتجات"
                        icon="fas fa-chart-line" />
                @endforelse
            </div>
        </x-admin.card>
    </div>
    
    <!-- Low Stock Alert -->
    @if($lowStockProducts->count() > 0)
        <x-admin.card :padding="false">
            <x-slot name="header">
                <div class="flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                    <h3 class="text-lg font-semibold text-gray-800">تنبيه: منتجات قاربت على النفاد</h3>
                </div>
            </x-slot>
            
            <x-admin.table :headers="['المنتج', 'الكمية المتبقية', 'إجراء']" striped>
                @foreach($lowStockProducts as $product)
                    <x-admin.table.row>
                        <x-admin.table.cell>{{ $product->name }}</x-admin.table.cell>
                        <x-admin.table.cell>
                            <x-admin.badge variant="danger">
                                {{ $product->stock }} قطعة
                            </x-admin.badge>
                        </x-admin.table.cell>
                        <x-admin.table.cell>
                            <a href="{{ route('admin.products.edit', $product) }}" 
                               class="text-indigo-600 hover:text-indigo-800 font-medium">
                                <i class="fas fa-edit ml-1"></i>
                                تحديث المخزون
                            </a>
                        </x-admin.table.cell>
                    </x-admin.table.row>
                @endforeach
            </x-admin.table>
        </x-admin.card>
    @endif
</div>
@endsection
