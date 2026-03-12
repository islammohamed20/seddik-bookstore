@extends('layouts.storefront')

@section('title', __('اتصل بنا') . ' - ' . __('مكتبة الصديق'))

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-primary-blue via-blue-800 to-primary-blue py-20 overflow-hidden">
    <!-- Background Decorations -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 right-10 w-64 h-64 bg-primary-yellow rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 left-10 w-80 h-80 bg-white rounded-full blur-3xl"></div>
    </div>
    
    <div class="container mx-auto px-4 relative z-10">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center justify-center gap-2 text-sm text-white/70">
                <li><a href="{{ route('home') }}" class="hover:text-white transition"><i class="fas fa-home"></i></a></li>
                <li>/</li>
                <li class="text-primary-yellow font-medium">اتصل بنا</li>
            </ol>
        </nav>
        
        <div class="text-right">
            <span class="inline-block bg-primary-yellow text-primary-blue px-4 py-2 rounded-full text-sm font-bold mb-4">
                <i class="fas fa-headset ml-1"></i>
                نحن هنا لمساعدتك
            </span>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">تواصل معنا</h1>
            <p class="text-white/80 text-lg max-w-2xl mx-auto">فريقنا جاهز للإجابة على استفساراتك ومساعدتك في أي وقت</p>
        </div>
    </div>
</section>

<!-- Quick Contact Cards -->
<section class="py-8 -mt-12 relative z-20">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Phone Card -->
            <a href="tel:01223694848" class="group bg-white rounded-2xl shadow-xl p-6 flex items-center gap-4 hover:shadow-2xl transition transform hover:-translate-y-1">
                <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center group-hover:bg-green-500 transition">
                    <i class="fas fa-phone-alt text-2xl text-green-600 group-hover:text-white transition"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">اتصل بنا</h3>
                    <p class="text-gray-600 text-sm" dir="ltr">01223694848</p>
                </div>
            </a>
            
            <!-- WhatsApp Card -->
            <a href="https://wa.me/201223694848" target="_blank" class="group bg-white rounded-2xl shadow-xl p-6 flex items-center gap-4 hover:shadow-2xl transition transform hover:-translate-y-1">
                <div class="w-14 h-14 bg-emerald-100 rounded-xl flex items-center justify-center group-hover:bg-emerald-500 transition">
                    <i class="fab fa-whatsapp text-2xl text-emerald-600 group-hover:text-white transition"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">واتساب</h3>
                    <p class="text-gray-600 text-sm">راسلنا الآن</p>
                </div>
            </a>
            
            <!-- Email Card -->
            <a href="mailto:info@seddik-bookstore.com" class="group bg-white rounded-2xl shadow-xl p-6 flex items-center gap-4 hover:shadow-2xl transition transform hover:-translate-y-1">
                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-primary-blue transition">
                    <i class="fas fa-envelope text-2xl text-primary-blue group-hover:text-white transition"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">البريد الإلكتروني</h3>
                    <p class="text-gray-600 text-sm">info@seddik-bookstore.com</p>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Contact Content -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-primary-blue mb-2">أرسل لنا رسالة</h2>
                    <p class="text-gray-600">سنرد عليك في أقرب وقت ممكن</p>
                </div>
                
                @if(session('success'))
                <div class="bg-green-50 border-r-4 border-green-500 text-green-700 p-4 rounded-lg mb-6 flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                    <div>
                        <h4 class="font-bold">تم الإرسال بنجاح!</h4>
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                <i class="fas fa-user text-primary-yellow ml-1"></i>
                                الاسم الكامل *
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   placeholder="أدخل اسمك"
                                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-yellow focus:border-primary-yellow transition @error('name') border-red-500 @enderror">
                            @error('name')
                            <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle ml-1"></i>{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                <i class="fas fa-envelope text-primary-yellow ml-1"></i>
                                البريد الإلكتروني *
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   placeholder="example@email.com"
                                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-yellow focus:border-primary-yellow transition @error('email') border-red-500 @enderror">
                            @error('email')
                            <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle ml-1"></i>{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                <i class="fas fa-phone text-primary-yellow ml-1"></i>
                                رقم الهاتف
                            </label>
                            <input type="tel" name="phone" value="{{ old('phone') }}"
                                   placeholder="01xxxxxxxxx"
                                   class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-yellow focus:border-primary-yellow transition">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                <i class="fas fa-tag text-primary-yellow ml-1"></i>
                                الموضوع *
                            </label>
                            <select name="subject" required
                                    class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-yellow focus:border-primary-yellow transition @error('subject') border-red-500 @enderror">
                                <option value="">اختر الموضوع</option>
                                <option value="inquiry" {{ old('subject') == 'inquiry' ? 'selected' : '' }}>استفسار عام</option>
                                <option value="order" {{ old('subject') == 'order' ? 'selected' : '' }}>استفسار عن طلب</option>
                                <option value="complaint" {{ old('subject') == 'complaint' ? 'selected' : '' }}>شكوى</option>
                                <option value="suggestion" {{ old('subject') == 'suggestion' ? 'selected' : '' }}>اقتراح</option>
                                <option value="partnership" {{ old('subject') == 'partnership' ? 'selected' : '' }}>شراكة تجارية</option>
                            </select>
                            @error('subject')
                            <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle ml-1"></i>{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">
                            <i class="fas fa-comment text-primary-yellow ml-1"></i>
                            الرسالة *
                        </label>
                        <textarea name="message" rows="5" required
                                  placeholder="اكتب رسالتك هنا..."
                                  class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-primary-yellow focus:border-primary-yellow transition resize-none @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                        @error('message')
                        <p class="text-red-500 text-sm mt-1"><i class="fas fa-exclamation-circle ml-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-primary-yellow to-yellow-400 hover:from-yellow-400 hover:to-primary-yellow text-primary-blue font-bold py-4 rounded-xl transition transform hover:scale-[1.02] shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane"></i>
                        إرسال الرسالة
                    </button>
                </form>
            </div>
            
            <!-- Contact Info & Map -->
            <div class="space-y-6">
                <!-- Info Cards -->
                <div class="bg-white rounded-2xl shadow-xl p-8">
                    <h2 class="text-2xl font-bold text-primary-blue mb-6">معلومات التواصل</h2>
                    
                    <div class="space-y-5">
                        <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                            <div class="w-12 h-12 bg-primary-blue/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-primary-blue text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1">العنوان</h3>
                                <p class="text-gray-600 leading-relaxed">
                                    شارع الجمهورية، بجوار الوطنية مول<br>
                                    أسيوط، مصر
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-phone-alt text-green-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1">الهاتف</h3>
                                <div class="space-y-1">
                                    <a href="tel:01223694848" class="block text-gray-600 hover:text-primary-blue transition" dir="ltr">01223694848</a>
                                    <a href="tel:01022221892" class="block text-gray-600 hover:text-primary-blue transition" dir="ltr">01022221892</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-clock text-amber-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 mb-1">ساعات العمل</h3>
                                <div class="text-gray-600 text-sm space-y-1">
                                    <p class="flex justify-between gap-4">
                                        <span>السبت - الخميس</span>
                                        <span class="font-semibold">9:00 ص - 11:00 م</span>
                                    </p>
                                    <p class="flex justify-between gap-4">
                                        <span>الجمعة</span>
                                        <span class="font-semibold">4:00 م - 11:00 م</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                @php
                    $contactFacebookUrl = \App\Models\Setting::getValue('facebook_url');
                    $contactInstagramUrl = \App\Models\Setting::getValue('instagram_url');
                    $contactTwitterUrl = \App\Models\Setting::getValue('twitter_url');
                    $contactYoutubeUrl = \App\Models\Setting::getValue('youtube_url');
                    $contactTelegramUrl = \App\Models\Setting::getValue('telegram_url');
                @endphp
                @if($contactFacebookUrl || $contactInstagramUrl || $contactTwitterUrl || $contactYoutubeUrl || $contactTelegramUrl)
                    <div class="bg-gradient-to-br from-primary-blue to-blue-900 rounded-2xl shadow-xl p-8 text-white">
                        <h2 class="text-xl font-bold mb-4">تابعنا على وسائل التواصل</h2>
                        <p class="text-white/70 mb-6 text-sm">ابق على اطلاع بأحدث العروض والمنتجات</p>
                        <div class="flex gap-3">
                            @if($contactFacebookUrl)
                                <a href="{{ $contactFacebookUrl }}" target="_blank" class="w-12 h-12 bg-white/10 hover:bg-blue-600 text-white rounded-xl flex items-center justify-center transition transform hover:scale-110 backdrop-blur">
                                    <i class="fab fa-facebook-f text-xl"></i>
                                </a>
                            @endif
                            @if($contactInstagramUrl)
                                <a href="{{ $contactInstagramUrl }}" target="_blank" class="w-12 h-12 bg-white/10 hover:bg-gradient-to-br hover:from-purple-500 hover:to-pink-500 text-white rounded-xl flex items-center justify-center transition transform hover:scale-110 backdrop-blur">
                                    <i class="fab fa-instagram text-xl"></i>
                                </a>
                            @endif
                            @if($contactTwitterUrl)
                                <a href="{{ $contactTwitterUrl }}" target="_blank" class="w-12 h-12 bg-white/10 hover:bg-sky-500 text-white rounded-xl flex items-center justify-center transition transform hover:scale-110 backdrop-blur">
                                    <i class="fab fa-twitter text-xl"></i>
                                </a>
                            @endif
                            @if($contactYoutubeUrl)
                                <a href="{{ $contactYoutubeUrl }}" target="_blank" class="w-12 h-12 bg-white/10 hover:bg-red-600 text-white rounded-xl flex items-center justify-center transition transform hover:scale-110 backdrop-blur">
                                    <i class="fab fa-youtube text-xl"></i>
                                </a>
                            @endif
                            @if($contactTelegramUrl)
                                <a href="{{ $contactTelegramUrl }}" target="_blank" class="w-12 h-12 bg-white/10 hover:bg-blue-500 text-white rounded-xl flex items-center justify-center transition transform hover:scale-110 backdrop-blur">
                                    <i class="fab fa-telegram text-xl"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Map Section -->
        <div class="mt-12">
            <div class="bg-white rounded-2xl shadow-xl p-2 overflow-hidden">
                <iframe 
                    src="https://maps.google.com/maps?q=27.1831605,31.1801331&hl=ar&z=18&output=embed"
                    width="100%" 
                    height="400" 
                    style="border:0; border-radius: 16px;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-right mb-12">
            <span class="inline-block bg-primary-yellow/20 text-primary-yellow px-4 py-2 rounded-full text-sm font-bold mb-4">
                <i class="fas fa-question-circle ml-1"></i>
                الأسئلة الشائعة
            </span>
            <h2 class="text-3xl font-bold text-primary-blue">هل لديك سؤال؟</h2>
        </div>
        
        <div class="max-w-3xl mx-auto space-y-4" x-data="{ open: 1 }">
            @php
            $faqs = [
                ['q' => 'ما هي طرق الدفع المتاحة؟', 'a' => 'نقبل الدفع عند الاستلام، بطاقات الائتمان، التحويل البنكي، والمحافظ الإلكترونية مثل فودافون كاش وأورانج كاش.'],
                ['q' => 'كم تستغرق عملية الشحن؟', 'a' => 'يتم التوصيل خلال 2-5 أيام عمل لمعظم المحافظات. قد تختلف المدة حسب موقعك.'],
                ['q' => 'هل يمكنني إرجاع المنتج؟', 'a' => 'نعم، يمكنك إرجاع المنتج خلال 14 يوم من تاريخ الاستلام بشرط أن يكون في حالته الأصلية.'],
                ['q' => 'هل المنتجات أصلية؟', 'a' => 'نعم، جميع منتجاتنا أصلية 100% ومضمونة. نحن وكيل معتمد لشركة Bingo ونتعامل مع أفضل الموردين.'],
            ];
            @endphp
            
            @foreach($faqs as $index => $faq)
            <div class="border-2 border-gray-200 rounded-xl overflow-hidden" :class="open === {{ $index + 1 }} ? 'border-primary-blue' : ''">
                <button @click="open = open === {{ $index + 1 }} ? null : {{ $index + 1 }}"
                        class="w-full flex items-center justify-between p-5 text-right hover:bg-gray-50 transition">
                    <span class="font-semibold text-gray-900">{{ $faq['q'] }}</span>
                    <i class="fas fa-chevron-down text-primary-blue transition-transform" :class="open === {{ $index + 1 }} ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open === {{ $index + 1 }}" x-collapse>
                    <div class="px-5 pb-5 text-gray-600">
                        {{ $faq['a'] }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
