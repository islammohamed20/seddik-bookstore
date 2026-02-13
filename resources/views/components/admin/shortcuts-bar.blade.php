<!-- شريط الاختصارات السريعة -->
<div class="fixed bottom-0 left-0 right-0 bg-gray-900 text-white text-xs p-2 z-40 lg:left-64" 
     x-data="{ showShortcuts: false }"
     x-show="showShortcuts"
     x-transition
     {{ $attributes }}>
    
    <div class="flex justify-between items-center max-w-7xl mx-auto">
        <div class="flex items-center gap-4 flex-wrap">
            <span class="text-gray-400">⌨️ اختصارات سريعة:</span>
            @foreach($shortcuts as $shortcut)
                <span class="flex items-center gap-1">
                    @if(isset($shortcut['icon']))
                        <i class="{{ $shortcut['icon'] }} text-gray-400"></i>
                    @endif
                    <kbd class="bg-gray-700 px-1 py-0.5 rounded text-xs">{{ $shortcut['key'] }}</kbd>
                    <span>{{ $shortcut['action'] }}</span>
                </span>
            @endforeach
        </div>
        
        <div class="flex items-center gap-2">
            <button onclick="window.AdminShortcuts?.showShortcutsList()" 
                    class="text-gray-400 hover:text-white px-2 py-1 rounded" title="عرض جميع الاختصارات">
                <i class="fas fa-list"></i>
            </button>
            <button @click="showShortcuts = false" class="text-gray-400 hover:text-white px-2 py-1 rounded">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>

<!-- زر إظهار/إخفاء شريط الاختصارات -->
<button @click="$el.parentElement.querySelector('[x-data*=showShortcuts]').__x.$data.showShortcuts = !$el.parentElement.querySelector('[x-data*=showShortcuts]').__x.$data.showShortcuts" 
        class="fixed bottom-4 left-4 bg-indigo-600 text-white p-3 rounded-full shadow-lg hover:bg-indigo-700 transition z-50 lg:left-68"
        title="اضغط لعرض/إخفاء الاختصارات السريعة (Ctrl+Shift+H)"
        id="shortcuts-toggle">
    <i class="fas fa-keyboard text-sm"></i>
</button>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // إضافة معالج للتبديل بين عرض وإخفاء شريط الاختصارات
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.shiftKey && e.key === 'H') {
            e.preventDefault();
            document.getElementById('shortcuts-toggle')?.click();
        }
    });

    // عرض نصيحة سريعة للمستخدمين الجدد
    if (!localStorage.getItem('shortcuts_hint_shown')) {
        setTimeout(() => {
            const button = document.getElementById('shortcuts-toggle');
            if (button) {
                button.classList.add('animate-pulse');
                
                // إنشاء tooltip
                const tooltip = document.createElement('div');
                tooltip.className = 'fixed bottom-20 left-4 bg-black text-white text-xs p-2 rounded shadow-lg z-50 lg:left-68';
                tooltip.innerHTML = `
                    <div class="flex items-center gap-2">
                        <i class="fas fa-lightbulb text-yellow-400"></i>
                        <span>اضغط هنا لعرض اختصارات لوحة المفاتيح</span>
                        <button onclick="this.parentElement.parentElement.remove(); localStorage.setItem('shortcuts_hint_shown', 'true')" 
                                class="text-gray-300 hover:text-white ml-2">×</button>
                    </div>
                `;
                document.body.appendChild(tooltip);
                
                // إزالة التأثير والتلميح بعد 5 ثوان
                setTimeout(() => {
                    button.classList.remove('animate-pulse');
                    tooltip.remove();
                    localStorage.setItem('shortcuts_hint_shown', 'true');
                }, 5000);
            }
        }, 3000);
    }
});
</script>