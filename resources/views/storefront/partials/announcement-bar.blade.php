<!-- Announcement Bar -->
@php
    use App\Models\Setting;
    $announcementText = Setting::getValue('announcement_text', 'شحن مجاني للطلبات فوق 500 جنيه 🚚 | خصم 15% على جميع الألعاب التعليمية 🎁');
    $announcementEnabled = Setting::getValue('announcement_enabled', true);
@endphp

@if($announcementEnabled && $announcementText)
<div class="bg-gradient-to-r from-primary-yellow via-yellow-400 to-primary-yellow text-primary-blue py-2 overflow-hidden relative"
     x-data="{ show: true }"
     x-show="show"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100 max-h-10"
     x-transition:leave-end="opacity-0 max-h-0">
    <div class="container mx-auto px-4 flex items-center justify-center gap-4">
        <div class="flex items-center gap-2 animate-marquee whitespace-nowrap">
            <i class="fas fa-bullhorn text-primary-red"></i>
            <span class="font-bold text-sm">{{ $announcementText }}</span>
        </div>
        <button @click="show = false" class="absolute left-4 top-1/2 -translate-y-1/2 text-primary-blue/60 hover:text-primary-blue transition">
            <i class="fas fa-times text-xs"></i>
        </button>
    </div>
</div>
@endif
