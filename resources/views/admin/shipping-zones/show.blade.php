@extends('admin.layouts.app')

@section('title', 'تفاصيل منطقة الشحن')
@section('page-title', 'تفاصيل منطقة الشحن: ' . ($shippingZone->name_ar ?: $shippingZone->name_en))

@section('content')
<div class="space-y-6">
    <!-- Zone Details -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">معلومات المنطقة</h3>
            <div class="flex gap-2">
                <a href="{{ route('admin.shipping-zones.edit', $shippingZone) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition text-sm">
                    <i class="fas fa-edit ml-1"></i> تعديل
                </a>
                <a href="{{ route('admin.shipping-zones.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-sm">
                    <i class="fas fa-arrow-right ml-1"></i> عودة
                </a>
            </div>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">الاسم العربي</label>
                <p class="text-gray-900 font-medium">{{ $shippingZone->name_ar ?? '-' }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">الاسم الإنجليزي</label>
                <p class="text-gray-900 font-medium">{{ $shippingZone->name_en ?? '-' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">الوصف العربي</label>
                <p class="text-gray-900">{{ $shippingZone->description_ar ?? '-' }}</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">الوصف الإنجليزي</label>
                <p class="text-gray-900">{{ $shippingZone->description_en ?? '-' }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">الحد الأدنى للطلب</label>
                <p class="text-gray-900 font-medium">{{ number_format($shippingZone->min_order_value, 2) }} ج.م</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">ترتيب العرض</label>
                <p class="text-gray-900">{{ $shippingZone->sort_order }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">الحالة</label>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $shippingZone->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $shippingZone->is_active ? 'نشط' : 'غير نشط' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Geographic Coverage -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">التغطية الجغرافية</h3>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-2">الدول المشمولة</label>
                <div class="flex flex-wrap gap-2">
                    @forelse($shippingZone->countries ?? [] as $country)
                        <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-lg text-sm">{{ $country }}</span>
                    @empty
                        <span class="text-gray-400 text-sm">لا يوجد دول محددة</span>
                    @endforelse
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-2">المدن المشمولة</label>
                <div class="flex flex-wrap gap-2 max-h-40 overflow-y-auto">
                    @forelse($shippingZone->cities ?? [] as $city)
                        <span class="bg-gray-50 text-gray-700 px-3 py-1 rounded-lg text-sm border border-gray-200">{{ $city }}</span>
                    @empty
                        <span class="text-gray-400 text-sm">لا يوجد مدن محددة</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Shipping Methods -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">طرق الشحن</h3>
            <a href="{{ route('admin.shipping-methods.create', ['shipping_zone_id' => $shippingZone->id]) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition text-sm flex items-center">
                <i class="fas fa-plus ml-2"></i>إضافة طريقة شحن
            </a>
        </div>
        <div class="p-6">
            @if($shippingZone->shippingMethods->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النوع</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التكلفة</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">وقت التوصيل</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($shippingZone->shippingMethods as $method)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $method->name_ar }}</div>
                                        <div class="text-xs text-gray-500">{{ $method->name_en }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="text-sm text-gray-700">{{ $method->type_name }}</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            @if($method->type == 'free_shipping')
                                                <span class="text-green-600 font-medium">مجاني</span>
                                            @elseif($method->type == 'pickup')
                                                <span class="text-blue-600">استلام</span>
                                            @else
                                                {{ number_format($method->cost, 2) }} ج.م
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                        {{ $method->delivery_time ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">
                                        <form action="{{ route('admin.shipping-methods.toggle-status', $method) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="relative inline-flex items-center cursor-pointer group">
                                                <input type="checkbox" class="sr-only peer" {{ $method->is_active ? 'checked' : '' }}>
                                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-center items-center gap-2">
                                            <a href="{{ route('admin.shipping-methods.edit', $method) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-lg transition-colors" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.shipping-methods.destroy', $method) }}" method="POST" class="inline-block" onsubmit="return confirm('هل أنت متأكد من حذف طريقة الشحن هذه؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors" title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-right py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                    <div class="text-gray-400 mb-3">
                        <i class="fas fa-shipping-fast text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">لا توجد طرق شحن</h3>
                    <p class="text-gray-500 mt-1 mb-4">لم يتم إضافة أي طرق شحن لهذه المنطقة بعد.</p>
                    <a href="{{ route('admin.shipping-methods.create', ['shipping_zone_id' => $shippingZone->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-plus ml-2 -mr-1"></i>
                        إضافة طريقة شحن
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection