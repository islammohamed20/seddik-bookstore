@extends('admin.layouts.app')

@section('title', 'إدارة البريد الإلكتروني')
@section('page-title', 'إدارة البريد الإلكتروني')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <p class="text-gray-600 dark:text-slate-400">إدارة المشتركين وإرسال رسائل جماعية</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.email-management.compose') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-paper-plane ml-2"></i>
                إرسال بريد جماعي
            </a>
            <a href="{{ route('admin.email-management.export') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                <i class="fas fa-download ml-2"></i>
                تصدير CSV
            </a>
        </div>
    </div>

    <!-- Test Email -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">اختبار إرسال البريد</h3>
        <form action="{{ route('admin.email-management.test-send') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @csrf
            <div>
                <label for="test_email" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">البريد المستلم</label>
                <input type="email" name="test_email" id="test_email" value="{{ old('test_email', auth()->user()->email ?? '') }}" required
                       class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-4 py-2 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label for="test_subject" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">الموضوع</label>
                <input type="text" name="test_subject" id="test_subject" value="{{ old('test_subject', 'رسالة اختبار من متجر الصديق') }}" required
                       class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-4 py-2 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
            </div>
            <div class="md:col-span-3">
                <label for="test_message" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-1">نص الرسالة (اختياري)</label>
                <textarea name="test_message" id="test_message" rows="3"
                          class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-4 py-2 bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">{{ old('test_message') }}</textarea>
            </div>
            <div>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    <i class="fas fa-paper-plane ml-2"></i>
                    إرسال اختبار
                </button>
            </div>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">إجمالي المشتركين</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">مشتركين نشطين</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-1">{{ $stats['active'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-check text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">موثقين</p>
                    <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 mt-1">{{ $stats['verified'] }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-indigo-600 dark:text-indigo-400 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">ألغوا الاشتراك</p>
                    <p class="text-3xl font-bold text-amber-600 dark:text-amber-400 mt-1">{{ $stats['unsubscribed'] }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-times text-amber-600 dark:text-amber-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscribers Table -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700">
        <div class="p-6 border-b border-gray-100 dark:border-slate-700">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">قائمة المشتركين</h3>
                
                <!-- Search & Filter -->
                <form method="GET" class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                    <div class="relative flex-1 sm:flex-none">
                        <input type="text" 
                               name="search" 
                               placeholder="البحث بالبريد أو الاسم..." 
                               value="{{ request('search') }}"
                               class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <select name="status" class="px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500">
                        <option value="">جميع الحالات</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        <i class="fas fa-search ml-1"></i> بحث
                    </button>
                    @if(request('search') || request('status'))
                    <a href="{{ route('admin.email-management.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-slate-600 text-gray-700 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-slate-500 transition">
                        <i class="fas fa-redo ml-1"></i> إعادة تعيين
                    </a>
                    @endif
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-slate-700/50">
                    <tr>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-slate-300 uppercase tracking-wider">#</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-slate-300 uppercase tracking-wider">البريد الإلكتروني</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-slate-300 uppercase tracking-wider">الاسم</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-slate-300 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-slate-300 uppercase tracking-wider">موثق</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-slate-300 uppercase tracking-wider">المصدر</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-slate-300 uppercase tracking-wider">تاريخ الاشتراك</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-slate-300 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($subscribers as $subscriber)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition">
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $subscriber->id }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-envelope text-gray-400"></i>
                                <span class="text-sm text-gray-900 dark:text-white">{{ $subscriber->email }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $subscriber->name ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($subscriber->isActive())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                    <i class="fas fa-circle text-[6px] ml-1.5"></i> نشط
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-slate-600 dark:text-slate-300">
                                    <i class="fas fa-circle text-[6px] ml-1.5"></i> غير نشط
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($subscriber->isVerified())
                                <span class="text-green-600 dark:text-green-400"><i class="fas fa-check-circle"></i> نعم</span>
                            @else
                                <span class="text-amber-600 dark:text-amber-400"><i class="fas fa-times-circle"></i> لا</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400">
                                {{ $subscriber->source }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-slate-400">{{ $subscriber->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.email-management.destroy', $subscriber) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('هل أنت متأكد من الحذف؟');"
                                  class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-inbox text-gray-300 dark:text-slate-600 text-5xl mb-4"></i>
                                <p class="text-gray-500 dark:text-slate-400">لا يوجد مشتركين حالياً</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subscribers->hasPages())
        <div class="p-6 border-t border-gray-100 dark:border-slate-700">
            {{ $subscribers->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
