@extends('admin.layouts.app')

@section('title', 'تعديل قالب البريد')
@section('page-title', 'تعديل قالب البريد')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="text-sm text-gray-500">Key: <span class="font-mono text-gray-700">{{ $template->key }}</span></div>
            <div class="text-xl font-bold text-gray-900">{{ $template->name }}</div>
        </div>
        <a href="{{ route('admin.email-templates.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
            <i class="fas fa-arrow-right ml-2"></i>
            رجوع للقوالب
        </a>
    </div>

    <form method="POST" action="{{ route('admin.email-templates.update', $template) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-5">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">الاسم</label>
                    <input type="text" name="name" value="{{ old('name', $template->name) }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('name') border-red-500 @enderror">
                    @error('name')
                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>
                <div class="flex items-center gap-3 mt-6 lg:mt-0">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $template->is_active) ? 'checked' : '' }}
                           class="rounded text-indigo-600 focus:ring-indigo-500">
                    <label for="is_active" class="text-sm font-semibold text-gray-700">مفعل</label>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">الوصف</label>
                <input type="text" name="description" value="{{ old('description', $template->description) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('description') border-red-500 @enderror">
                @error('description')
                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">الموضوع</label>
                <input type="text" name="subject" value="{{ old('subject', $template->subject) }}"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('subject') border-red-500 @enderror">
                @error('subject')
                <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                @enderror
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">المحتوى (HTML)</label>
                    <textarea name="body_html" rows="14" required
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 font-mono text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('body_html') border-red-500 @enderror">{{ old('body_html', $template->body_html) }}</textarea>
                    @error('body_html')
                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">المحتوى (Text)</label>
                    <textarea name="body_text" rows="14"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 font-mono text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('body_text') border-red-500 @enderror">{{ old('body_text', $template->body_text) }}</textarea>
                    @error('body_text')
                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="font-semibold text-gray-800 mb-2">Variables المتاحة</div>
                <div class="flex flex-wrap gap-2">
                    @foreach(($template->variables ?? []) as $var)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-semibold font-mono">{{ '{' }}{{ '{' }}{{ $var }}{{ '}' }}{{ '}' }}</span>
                    @endforeach
                </div>
            </div>

        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold">
                <i class="fas fa-save ml-2"></i>
                حفظ
            </button>
            <a href="{{ route('admin.email-templates.index') }}" class="inline-flex items-center px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                إلغاء
            </a>
        </div>
    </form>

    <div class="bg-white border border-gray-200 rounded-xl p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
            <div class="font-semibold text-gray-900">اختبار القالب</div>
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('admin.email-templates.preview', $template) }}" target="_blank"
                   class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition text-sm font-semibold">
                    <i class="fas fa-eye ml-2"></i>
                    معاينة HTML
                </a>
                <a href="{{ route('admin.email-templates.preview', $template) }}?format=text" target="_blank"
                   class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition text-sm font-semibold">
                    <i class="fas fa-file-alt ml-2"></i>
                    معاينة Text
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.email-templates.test-send', $template) }}" class="mt-4">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 items-end">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">إرسال رسالة اختبار إلى</label>
                    <input type="email" name="test_email" value="{{ old('test_email', auth()->user()->email ?? '') }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('test_email') border-red-500 @enderror">
                    @error('test_email')
                    <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold">
                        <i class="fas fa-paper-plane ml-2"></i>
                        إرسال اختبار
                    </button>
                </div>
            </div>
            <div class="mt-2 text-xs text-gray-500">الإرسال يستخدم بيانات تجريبية تلقائية حسب نوع القالب (OTP/السلة/الدفع).</div>
        </form>
    </div>
</div>
@endsection
