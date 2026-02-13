<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('لوحة التحكم') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Admin Link -->
            @if(auth()->user()->isAdmin())
                <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 flex justify-between items-center shadow-sm sm:rounded-lg">
                    <div>
                        <p class="font-bold text-indigo-700">أنت مسجل كمدير للنظام</p>
                        <p class="text-sm text-indigo-600">يمكنك الوصول إلى لوحة تحكم الإدارة الكاملة من هنا.</p>
                    </div>
                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                        الذهاب للوحة الإدارة
                    </a>
                </div>
            @endif

            <!-- Welcome Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">مرحباً، {{ $user->name }}!</h3>
                    <p class="text-gray-600 mt-1">هذه لوحة التحكم الخاصة بحسابك. يمكنك متابعة طلباتك وإدارة بياناتك من هنا.</p>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Orders -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 ml-4">
                        <i class="fas fa-shopping-bag text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">إجمالي الطلبات</p>
                        <p class="text-2xl font-bold">{{ $ordersCount }}</p>
                    </div>
                </div>

                <!-- Active Cart (Example) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 ml-4">
                        <i class="fas fa-shopping-cart text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">في السلة</p>
                        <p class="text-2xl font-bold">{{ session('cart') ? count(session('cart')) : 0 }}</p>
                    </div>
                </div>

                <!-- Account Status -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 ml-4">
                        <i class="fas fa-user-check text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">حالة الحساب</p>
                        <p class="text-lg font-bold text-green-600">نشط</p>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">أحدث الطلبات</h3>
                    <a href="{{ route('orders.index') }}" class="text-sm text-blue-600 hover:text-blue-800">عرض كل الطلبات</a>
                </div>
                <div class="p-6">
                    @if($recentOrders->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-right text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3">رقم الطلب</th>
                                        <th class="px-6 py-3">التاريخ</th>
                                        <th class="px-6 py-3">الحالة</th>
                                        <th class="px-6 py-3">الإجمالي</th>
                                        <th class="px-6 py-3">إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                            <td class="px-6 py-4 font-medium text-gray-900">
                                                #{{ $order->order_number }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ $order->created_at->format('Y/m/d') }}
                                            </td>
                                            <td class="px-6 py-4">
                                                @php
                                                    $statusColors = [
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                        'processing' => 'bg-blue-100 text-blue-800',
                                                        'shipped' => 'bg-purple-100 text-purple-800',
                                                        'delivered' => 'bg-green-100 text-green-800',
                                                        'cancelled' => 'bg-red-100 text-red-800',
                                                    ];
                                                    $statusNames = [
                                                        'pending' => 'قيد الانتظار',
                                                        'processing' => 'قيد التجهيز',
                                                        'shipped' => 'تم الشحن',
                                                        'delivered' => 'تم التوصيل',
                                                        'cancelled' => 'ملغي',
                                                    ];
                                                @endphp
                                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ $statusNames[$order->status] ?? $order->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ number_format($order->grand_total, 2) }} ج.م
                                            </td>
                                            <td class="px-6 py-4">
                                                <a href="{{ route('orders.show', $order) }}" class="font-medium text-blue-600 hover:underline">عرض التفاصيل</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-400 mb-3">
                                <i class="fas fa-box-open text-4xl"></i>
                            </div>
                            <p class="text-gray-500">ليس لديك طلبات حتى الآن.</p>
                            <a href="{{ route('products.index') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                تصفح المنتجات
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Links -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="{{ route('profile.edit') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 transition">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 ml-4">
                            <i class="fas fa-user-cog text-xl"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 text-lg font-bold tracking-tight text-gray-900">تعديل الملف الشخصي</h5>
                            <p class="font-normal text-gray-700">تحديث اسمك، بريدك الإلكتروني، وكلمة المرور.</p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('cart.index') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:bg-gray-50 transition">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-pink-100 text-pink-600 ml-4">
                            <i class="fas fa-shopping-cart text-xl"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 text-lg font-bold tracking-tight text-gray-900">عربة التسوق</h5>
                            <p class="font-normal text-gray-700">مراجعة المنتجات في عربتك وإتمام الشراء.</p>
                        </div>
                    </div>
                </a>
            </div>
            
        </div>
    </div>
</x-app-layout>
