@extends('admin.layouts.app')

@section('title', 'تعديل خاصية')
@section('page-title', 'تعديل خاصية')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('admin.product-attributes.update', $attribute) }}">
            @csrf
            @method('PUT')
            @include('admin.product-attributes._form')

            <div class="mt-6 flex items-center gap-3">
                <a href="{{ route('admin.product-attributes.index') }}"
                   class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">رجوع</a>
                <button type="submit"
                        class="px-6 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">حفظ التعديلات</button>
            </div>
        </form>
    </div>
</div>
@endsection
