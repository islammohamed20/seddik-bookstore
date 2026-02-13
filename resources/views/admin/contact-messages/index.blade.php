@extends('admin.layouts.app')

@section('title', 'رسائل التواصل')
@section('page-title', 'إدارة رسائل التواصل')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-4">
        <form action="{{ route('admin.contact-messages.index') }}" method="GET" class="flex gap-4">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="بحث..."
                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            <select name="status" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                <option value="">كل الحالات</option>
                @foreach($statuses as $key => $name)
                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $name }}</option>
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
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">المرسل</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الموضوع</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">التاريخ</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الحالة</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">إجراءات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($messages as $message)
                    <tr class="hover:bg-gray-50 {{ $message->status === 'unread' ? 'bg-indigo-50' : '' }}">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-800 {{ $message->status === 'unread' ? 'font-bold' : '' }}">{{ $message->name }}</p>
                            <p class="text-xs text-gray-500">{{ $message->email }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ Str::limit($message->subject, 40) }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $message->created_at->format('Y/m/d H:i') }}</td>
                        <td class="px-4 py-3">
                            @php
                                $statusClasses = [
                                    'unread' => 'bg-yellow-100 text-yellow-800',
                                    'read' => 'bg-blue-100 text-blue-800',
                                    'replied' => 'bg-green-100 text-green-800',
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs rounded-full {{ $statusClasses[$message->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statuses[$message->status] ?? $message->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.contact-messages.show', $message) }}" class="text-indigo-600 hover:text-indigo-800">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('admin.contact-messages.destroy', $message) }}" method="POST" class="inline"
                                      onsubmit="return confirm('هل أنت متأكد؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">لا توجد رسائل</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $messages->links() }}
</div>
@endsection
