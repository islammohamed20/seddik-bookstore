@extends('admin.layouts.app')

@section('title', 'الإشعارات')
@section('page-title', 'الإشعارات')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">جميع الإشعارات</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $notifications->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-bell text-indigo-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">غير مقروءة</p>
                    <p class="text-2xl font-bold text-red-600">
                        {{ \App\Models\AdminNotification::unread()->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-envelope text-red-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">اليوم</p>
                    <p class="text-2xl font-bold text-green-600">
                        {{ \App\Models\AdminNotification::whereDate('created_at', today())->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-day text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">هذا الأسبوع</p>
                    <p class="text-2xl font-bold text-blue-600">
                        {{ \App\Models\AdminNotification::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-week text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-3">
                <form method="GET" action="{{ route('admin.notifications.index') }}" class="flex flex-wrap gap-3">
                    <!-- Type Filter -->
                    <select name="type" class="rounded-lg border-gray-300 shadow-sm" onchange="this.form.submit()">
                        <option value="">جميع الأنواع</option>
                        <option value="order" {{ request('type') === 'order' ? 'selected' : '' }}>الطلبات</option>
                        <option value="login" {{ request('type') === 'login' ? 'selected' : '' }}>تسجيلات الدخول</option>
                        <option value="registration" {{ request('type') === 'registration' ? 'selected' : '' }}>التسجيلات</option>
                        <option value="email" {{ request('type') === 'email' ? 'selected' : '' }}>البريد</option>
                        <option value="message" {{ request('type') === 'message' ? 'selected' : '' }}>الرسائل</option>
                    </select>

                    <!-- Status Filter -->
                    <select name="status" class="rounded-lg border-gray-300 shadow-sm" onchange="this.form.submit()">
                        <option value="">جميع الحالات</option>
                        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>غير مقروءة</option>
                        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>مقروءة</option>
                    </select>

                    @if(request()->hasAny(['type', 'status']))
                        <a href="{{ route('admin.notifications.index') }}" 
                           class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="fas fa-times ml-1"></i>
                            مسح الفلاتر
                        </a>
                    @endif
                </form>
            </div>

            <div class="flex gap-2">
                <form method="POST" action="{{ route('admin.notifications.mark-all-read') }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        <i class="fas fa-check-double ml-1"></i>
                        تعليم الكل كمقروء
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.notifications.clear-read') }}" 
                      onsubmit="return confirm('هل تريد حذف جميع الإشعارات المقروءة؟')">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-trash ml-1"></i>
                        حذف المقروءة
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($notifications->isEmpty())
            <div class="flex flex-col items-center justify-center p-12 text-gray-400">
                <i class="fas fa-bell-slash text-6xl mb-4"></i>
                <p class="text-xl">لا توجد إشعارات</p>
            </div>
        @else
            <div class="divide-y divide-gray-200">
                @foreach($notifications as $notification)
                    <div class="notification-item p-6 hover:bg-gray-50 transition-colors {{ $notification->is_read ? 'opacity-60' : '' }}">
                        <div class="flex items-start gap-4">
                            <!-- Icon -->
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center 
                                    @if($notification->type === 'order') bg-green-500
                                    @elseif($notification->type === 'login') bg-blue-500
                                    @elseif($notification->type === 'registration') bg-purple-500
                                    @elseif($notification->type === 'email') bg-orange-500
                                    @elseif($notification->type === 'message') bg-pink-500
                                    @else bg-gray-500
                                    @endif">
                                    <i class="{{ $notification->icon }} text-white text-lg"></i>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h3 class="font-semibold text-gray-800">{{ $notification->title }}</h3>
                                            @if(!$notification->is_read)
                                                <span class="w-2 h-2 bg-indigo-600 rounded-full"></span>
                                            @endif
                                        </div>
                                        <p class="text-gray-600 mb-2">{{ $notification->message }}</p>
                                        <div class="flex items-center gap-4 text-sm text-gray-400">
                                            <span>
                                                <i class="far fa-clock ml-1"></i>
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                            <span>
                                                <i class="fas fa-tag ml-1"></i>
                                                @if($notification->type === 'order') طلب
                                                @elseif($notification->type === 'login') تسجيل دخول
                                                @elseif($notification->type === 'registration') تسجيل
                                                @elseif($notification->type === 'email') بريد
                                                @elseif($notification->type === 'message') رسالة
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex gap-2">
                                        @if($notification->url)
                                            <a href="{{ $notification->url }}" 
                                               class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                               title="عرض التفاصيل">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        @endif

                                        @if(!$notification->is_read)
                                            <form method="POST" action="{{ route('admin.notifications.mark-read', $notification->id) }}">
                                                @csrf
                                                <button type="submit" 
                                                        class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                                        title="تعليم كمقروء">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <form method="POST" action="{{ route('admin.notifications.destroy', $notification->id) }}"
                                              onsubmit="return confirm('هل تريد حذف هذا الإشعار؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                    title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="bg-white rounded-lg shadow p-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
