<!-- Location & Contact Section -->
<section class="py-20 bg-white relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute inset-0" style="background-image: url(&quot;data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23003399' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E&quot;);"></div>
    </div>
    
    <div class="container mx-auto px-4 relative z-10">
        <!-- Section Header -->
        <div class="text-center mb-12">
            <span class="inline-block bg-primary-blue/10 text-primary-blue px-4 py-2 rounded-full text-sm font-semibold mb-4">
                <i class="fas fa-map-marker-alt ml-1"></i>
                تواصل معنا
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-primary-blue mb-4">موقعنا ومعلومات التواصل</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">نسعد بزيارتكم في فروعنا أو التواصل معنا عبر وسائل الاتصال المتاحة</p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
            <!-- Map -->
            <div class="rounded-2xl overflow-hidden shadow-2xl h-full min-h-[400px] relative group">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3001.6!2d31.2!3d27.1" 
                    width="100%" 
                    height="100%" 
                    style="border:0; min-height: 400px;" 
                    allowfullscreen="" 
                    loading="lazy"
                    class="w-full h-full">
                </iframe>
                <!-- Map Overlay on Hover -->
                <div class="absolute inset-0 bg-primary-blue/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                    <a href="https://maps.google.com/?q=27.1,31.2" target="_blank" 
                       class="bg-white text-primary-blue px-6 py-3 rounded-full font-semibold shadow-lg hover:bg-primary-blue hover:text-white transition flex items-center">
                        <i class="fas fa-external-link-alt ml-2"></i>
                        فتح في خرائط جوجل
                    </a>
                </div>
            </div>
            
            <!-- Contact Info -->
            <div class="space-y-5">
                <!-- Address Card -->
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 hover:shadow-lg transition-shadow group">
                    <div class="flex items-start gap-4">
                        <div class="w-14 h-14 bg-primary-blue/10 rounded-xl flex items-center justify-center group-hover:bg-primary-blue group-hover:text-white transition-colors">
                            <i class="fas fa-map-marker-alt text-2xl text-primary-blue group-hover:text-white transition-colors"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-lg text-primary-blue mb-2">العنوان</h3>
                            <p class="text-gray-600 leading-relaxed">شارع الجمهورية، بجوار الوطنية مول، أسيوط، مصر</p>
                        </div>
                    </div>
                </div>
                
                <!-- Phone Card -->
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 hover:shadow-lg transition-shadow group">
                    <div class="flex items-start gap-4">
                        <div class="w-14 h-14 bg-primary-yellow/20 rounded-xl flex items-center justify-center group-hover:bg-primary-yellow transition-colors">
                            <i class="fas fa-phone-alt text-2xl text-primary-yellow group-hover:text-primary-blue transition-colors"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-lg text-primary-blue mb-2">اتصل بنا</h3>
                            <div class="space-y-2">
                                <a href="tel:01223694848" class="flex items-center text-gray-600 hover:text-primary-blue transition">
                                    <i class="fas fa-phone text-sm ml-2 text-green-500"></i>
                                    01223694848
                                </a>
                                <a href="tel:01022221892" class="flex items-center text-gray-600 hover:text-primary-blue transition">
                                    <i class="fas fa-phone text-sm ml-2 text-green-500"></i>
                                    01022221892
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Working Hours Card -->
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 hover:shadow-lg transition-shadow group">
                    <div class="flex items-start gap-4">
                        <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center group-hover:bg-green-500 transition-colors">
                            <i class="fas fa-clock text-2xl text-green-600 group-hover:text-white transition-colors"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-lg text-primary-blue mb-2">ساعات العمل</h3>
                            <div class="space-y-1 text-gray-600">
                                <p class="flex justify-between">
                                    <span>السبت - الخميس</span>
                                    <span class="font-semibold">9:00 ص - 11:00 م</span>
                                </p>
                                <p class="flex justify-between">
                                    <span>الجمعة</span>
                                    <span class="font-semibold">4:00 م - 11:00 م</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- CTA Buttons -->
                <div class="grid grid-cols-2 gap-4 pt-2">
                    <a href="https://wa.me/201223694848" 
                       class="flex items-center justify-center gap-2 py-4 bg-green-500 hover:bg-green-600 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        <i class="fab fa-whatsapp text-xl"></i>
                        واتساب
                    </a>
                    <a href="{{ route('contact') }}" 
                       class="flex items-center justify-center gap-2 py-4 bg-primary-blue hover:bg-primary-blue/90 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        <i class="fas fa-envelope text-lg"></i>
                        راسلنا
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
