@extends('layouts.storefront')

@section('title', ($page->meta_title ?? $page->title) . ' - ' . __('مكتبة الصديق'))

@section('content')
<section class="bg-gray-50 py-10 md:py-14">
    <div class="container mx-auto px-4">
        <div class="mb-4 text-sm text-gray-500 flex items-center gap-2">
            <a href="{{ route('home') }}" class="hover:text-primary-blue">
                <i class="fas fa-home"></i>
            </a>
            <span>/</span>
            <span class="text-gray-700 font-medium">{{ $page->title }}</span>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 md:p-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">
                {{ $page->title }}
            </h1>
            <div class="prose prose-sm md:prose-base max-w-none text-gray-700 leading-relaxed">
                {!! nl2br(e($page->content)) !!}
            </div>
        </div>
    </div>
</section>
@endsection

