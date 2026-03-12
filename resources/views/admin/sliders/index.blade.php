@extends('admin.layouts.app')

@section('title', 'السلايدر')
@section('page-title', 'إدارة السلايدر')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <p class="text-gray-600">إجمالي {{ $sliders->count() }} سلايد</p>
        <a href="{{ route('admin.sliders.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
            <i class="fas fa-plus ml-2"></i>إضافة سلايد
        </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($sliders as $slider)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <img src="{{ asset('storage/' . $slider->image) }}" alt="{{ $slider->title_ar ?: $slider->title_en }}" class="w-full h-40 object-cover">
                <div class="p-4">
                    <h3 class="font-semibold text-gray-800">
                        {{ $slider->title_ar ?: $slider->title_en ?: 'بدون عنوان' }}
                        @if($slider->title_ar && $slider->title_en)
                            <span class="text-sm text-gray-500 font-normal block">{{ $slider->title_en }}</span>
                        @endif
                    </h3>
                    @if($slider->subtitle_ar || $slider->subtitle_en)
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $slider->subtitle_ar ?: $slider->subtitle_en }}
                        </p>
                    @endif
                    <div class="flex items-center justify-between mt-4">
                        <form action="{{ route('admin.sliders.toggle-status', $slider) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-2 py-1 text-xs rounded-full {{ $slider->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $slider->is_active ? 'نشط' : 'غير نشط' }}
                            </button>
                        </form>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.sliders.edit', $slider) }}" class="text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.sliders.destroy', $slider) }}" method="POST" class="inline"
                                  onsubmit="return confirm('هل أنت متأكد؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-lg shadow p-8 text-right text-gray-500">
                لا توجد سلايدات
            </div>
        @endforelse
    </div>
</div>
@endsection
