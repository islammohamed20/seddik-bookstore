@extends('admin.layouts.app')

@section('title', 'قوالب البريد')
@section('page-title', 'قوالب البريد')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-gray-600">إدارة القوالب التي تُستخدم في رسائل OTP وإشعارات السلة وحالات الدفع</p>
        </div>
        <a href="{{ route('admin.email-management.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
            <i class="fas fa-arrow-right ml-2"></i>
            رجوع لإدارة البريد
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase">القالب</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Key</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase">الحالة</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase">آخر تعديل</th>
                    <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($templates as $template)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="font-semibold text-gray-900">{{ $template->name }}</div>
                        @if($template->description)
                            <div class="text-sm text-gray-500">{{ $template->description }}</div>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700 font-mono">{{ $template->key }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $template->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            <i class="fas {{ $template->is_active ? 'fa-check-circle' : 'fa-pause-circle' }} ml-1"></i>
                            {{ $template->is_active ? 'مفعل' : 'معطل' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $template->updated_at?->format('Y-m-d H:i') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.email-templates.edit', $template) }}" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm">
                            <i class="fas fa-pen ml-2"></i>
                            تعديل
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-10 text-center text-gray-500">لا توجد قوالب</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
