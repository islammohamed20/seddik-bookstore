@extends('layouts.storefront')

@section('title', 'إلغاء الاشتراك في النشرة البريدية')

@section('content')
<div class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto bg-white rounded-2xl shadow-lg p-8 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-envelope-open text-3xl text-gray-400"></i>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-900 mb-3">تم إلغاء الاشتراك</h1>
            <p class="text-gray-600 mb-6">
                تم إلغاء اشتراكك في النشرة البريدية بنجاح. لن نرسل لك المزيد من الرسائل.
            </p>
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 text-sm text-blue-800">
                <p class="font-medium mb-1">هل غيرت رأيك؟</p>
                <p>يمكنك الاشتراك مرة أخرى في أي وقت من أسفل الصفحة الرئيسية</p>
            </div>
            
            <a href="{{ route('home') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-primary-blue hover:bg-primary-blue-dark text-white font-semibold rounded-xl transition-all">
                <i class="fas fa-home"></i>
                العودة للرئيسية
            </a>
        </div>
    </div>
</div>
@endsection
