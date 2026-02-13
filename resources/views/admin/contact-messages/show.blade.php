@extends('admin.layouts.app')

@section('title', 'عرض الرسالة')
@section('page-title', 'رسالة من: ' . $contactMessage->name)

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="space-y-4">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">{{ $contactMessage->subject }}</h3>
                    <p class="text-sm text-gray-500">{{ $contactMessage->created_at->format('Y/m/d H:i') }}</p>
                </div>
                @php
                    $statusClasses = [
                        'unread' => 'bg-yellow-100 text-yellow-800',
                        'read' => 'bg-blue-100 text-blue-800',
                        'replied' => 'bg-green-100 text-green-800',
                    ];
                    $statusNames = [
                        'unread' => 'غير مقروء',
                        'read' => 'مقروء',
                        'replied' => 'تم الرد',
                    ];
                @endphp
                <span class="px-3 py-1 text-sm rounded-full {{ $statusClasses[$contactMessage->status] ?? 'bg-gray-100' }}">
                    {{ $statusNames[$contactMessage->status] ?? $contactMessage->status }}
                </span>
            </div>
            
            <div class="border-t border-gray-200 pt-4">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-500">الاسم</p>
                        <p class="font-medium">{{ $contactMessage->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">البريد الإلكتروني</p>
                        <p class="font-medium">{{ $contactMessage->email }}</p>
                    </div>
                    @if($contactMessage->phone)
                    <div>
                        <p class="text-sm text-gray-500">الهاتف</p>
                        <p class="font-medium">{{ $contactMessage->phone }}</p>
                    </div>
                    @endif
                </div>
                
                <div>
                    <p class="text-sm text-gray-500 mb-2">الرسالة</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-800 whitespace-pre-wrap">{{ $contactMessage->message }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if($contactMessage->reply_message)
    <div class="bg-green-50 rounded-lg shadow p-6">
        <h4 class="font-semibold text-green-800 mb-2">الرد المرسل</h4>
        <p class="text-green-700 whitespace-pre-wrap">{{ $contactMessage->reply_message }}</p>
        <p class="text-xs text-green-600 mt-2">تم الرد في: {{ $contactMessage->replied_at?->format('Y/m/d H:i') }}</p>
    </div>
    @else
    <div class="bg-white rounded-lg shadow p-6">
        <h4 class="font-semibold text-gray-800 mb-4">إرسال رد</h4>
        <form action="{{ route('admin.contact-messages.reply', $contactMessage) }}" method="POST">
            @csrf
            <textarea name="reply_message" rows="4" required
                      placeholder="اكتب ردك هنا..."
                      class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500"></textarea>
            <button type="submit" class="mt-4 bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-paper-plane ml-2"></i>إرسال الرد
            </button>
        </form>
    </div>
    @endif
    
    <a href="{{ route('admin.contact-messages.index') }}" class="inline-block bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
        <i class="fas fa-arrow-right ml-2"></i>العودة
    </a>
</div>
@endsection
