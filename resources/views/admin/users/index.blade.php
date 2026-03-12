@extends('admin.layouts.app')

@php
    $isCustomersPage = request()->routeIs('admin.customers.index');
@endphp

@section('title', $isCustomersPage ? 'العملاء' : 'المستخدمين')
@section('page-title', $isCustomersPage ? 'إدارة العملاء' : 'إدارة المستخدمين')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <p class="text-gray-600">إجمالي {{ $users->total() }} {{ $isCustomersPage ? 'عميل' : 'مستخدم' }}</p>
        <a href="{{ route('admin.users.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
            <i class="fas fa-plus ml-2"></i>{{ $isCustomersPage ? 'إضافة عميل' : 'إضافة مستخدم' }}
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow p-4">
        <form action="{{ $isCustomersPage ? route('admin.customers.index') : route('admin.users.index') }}" method="GET" class="flex gap-4">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="بحث بالاسم أو البريد..."
                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            <select name="role" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                <option value="">كل الأدوار</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-900">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
    
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">المستخدم</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الدور</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">تاريخ التسجيل</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الحالة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </td>
                        <td class="px-4 py-3">
                            @foreach($user->roles as $role)
                                <span class="px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-800">{{ $role->name }}</span>
                            @endforeach
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $user->created_at->format('Y/m/d') }}</td>
                        <td class="px-4 py-3">
                            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-2 py-1 text-xs rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}"
                                        {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                    {{ $user->is_active ? 'نشط' : 'غير نشط' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-800">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-right text-gray-500">{{ $isCustomersPage ? 'لا يوجد عملاء' : 'لا يوجد مستخدمين' }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{ $users->links() }}
</div>
@endsection
