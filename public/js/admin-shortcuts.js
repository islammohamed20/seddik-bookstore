/**
 * Admin Panel Keyboard Shortcuts
 * اختصارات لوحة المفاتيح للوحة التحكم
 */

class AdminKeyboardShortcuts {
    constructor() {
        this.shortcuts = {
            // حفظ النموذج
            'ctrl+s': () => this.saveForm(),
            
            // حذف العنصر
            'ctrl+shift+d': () => this.deleteItem(),
            
            // إنشاء جديد
            'ctrl+n': () => this.createNew(),
            
            // تعديل العنصر
            'ctrl+e': () => this.editItem(),
            
            // نسخ العنصر
            'ctrl+shift+c': () => this.copyItem(),
            
            // البحث
            'ctrl+f': () => this.focusSearch(),
            
            // إرسال سريع (للنماذج)
            'ctrl+enter': () => this.quickSubmit(),
            
            // إلغاء/العودة
            'escape': () => this.cancel(),
            
            // التنقل بين التبويبات
            'ctrl+tab': () => this.nextTab(),
            'ctrl+shift+tab': () => this.previousTab(),
            
            // فتح القائمة الجانبية
            'ctrl+shift+m': () => this.toggleSidebar(),
            
            // معاينة سريعة
            'ctrl+p': () => this.preview(),
            
            // تحديث الصفحة
            'ctrl+r': () => this.refresh(),
            
            // طباعة
            'ctrl+shift+p': () => this.print(),
        };
        
        this.init();
    }
    
    init() {
        document.addEventListener('keydown', (e) => this.handleKeydown(e));
        this.showShortcutsHint();
        
        // إضافة توضيحات الاختصارات للأزرار
        this.addShortcutTooltips();
    }
    
    handleKeydown(event) {
        const key = this.getKeyCombo(event);
        
        if (this.shortcuts[key]) {
            // تجاهل الاختصارات في حقول النص إلا المحددة
            if (this.isInTextInput(event) && !this.isTextInputAllowedShortcut(key)) {
                return;
            }
            
            event.preventDefault();
            this.shortcuts[key]();
            this.showShortcutFeedback(key);
        }
    }
    
    getKeyCombo(event) {
        const parts = [];
        
        if (event.ctrlKey) parts.push('ctrl');
        if (event.altKey) parts.push('alt');
        if (event.shiftKey) parts.push('shift');
        parts.push(event.key.toLowerCase());
        
        return parts.join('+');
    }
    
    isInTextInput(event) {
        const element = event.target;
        return ['INPUT', 'TEXTAREA', 'SELECT'].includes(element.tagName) ||
               element.contentEditable === 'true';
    }
    
    isTextInputAllowedShortcut(key) {
        return ['ctrl+s', 'ctrl+enter', 'escape', 'ctrl+f'].includes(key);
    }
    
    // === الوظائف الرئيسية ===
    
    saveForm() {
        // ابحث عن الـ form الرئيسي
        const allForms = document.querySelectorAll('form');
        let targetForm = null;
        
        // أولاً: ابحث عن form بـ action يحتوي على 'update' أو 'store'
        for (let form of allForms) {
            const action = form.getAttribute('action') || '';
            if (action.includes('update') || action.includes('store')) {
                targetForm = form;
                break;
            }
        }
        
        // إذا لم تجد، استخدم آخر form
        if (!targetForm && allForms.length > 0) {
            targetForm = allForms[allForms.length - 1];
        }
        
        // حافظ على آخر form كـ fallback
        if (!targetForm && allForms.length > 0) {
            targetForm = allForms[allForms.length - 1];
        }
        
        if (targetForm) {
            const submitBtn = targetForm.querySelector('button[type="submit"], input[type="submit"]');
            if (submitBtn && !submitBtn.disabled) {
                this.flashButton(submitBtn);
                // إذا كان هناك Alpine.js data، حاول تغيير الـ loading state
                if (targetForm.__x_dataStack) {
                    targetForm.__x_dataStack[0].loading = true;
                }
                // انتظر قليلاً ثم أرسل
                setTimeout(() => {
                    submitBtn.click();
                }, 50);
                return;
            }
        }
        
        this.showNotification('لم أتمكن من العثور على نموذج للحفظ', 'info');
    }
    
    deleteItem() {
        const deleteBtn = document.querySelector('button[title="حذف"], button[onclick*="delete"], button[onclick*="destroy"], form[method="POST"] button[class*="red"]');
        if (deleteBtn) {
            this.flashButton(deleteBtn);
            deleteBtn.click();
        } else {
            this.showNotification('لا يوجد عنصر للحذف', 'info');
        }
    }
    
    createNew() {
        const createBtn = document.querySelector('a[href*="/create"], a[title*="إضافة"], a[class*="bg-indigo"]');
        if (createBtn) {
            this.flashButton(createBtn);
            createBtn.click();
        } else {
            this.showNotification('لا يوجد رابط "إنشاء جديد"', 'info');
        }
    }
    
    editItem() {
        const editBtns = document.querySelectorAll('a[title="تعديل"], a[href*="/edit"]');
        if (editBtns.length === 1) {
            this.flashButton(editBtns[0]);
            editBtns[0].click();
        } else if (editBtns.length > 1) {
            this.showNotification('يوجد عدة عناصر للتعديل - اختر عنصر محدد', 'warning');
        } else {
            this.showNotification('لا يوجد عناصر للتعديل', 'info');
        }
    }
    
    copyItem() {
        // البحث عن زر نسخ أو تكرار
        const copyBtn = document.querySelector('button[title*="نسخ"], button[title*="تكرار"], a[href*="duplicate"]');
        if (copyBtn) {
            this.flashButton(copyBtn);
            copyBtn.click();
        } else {
            this.showNotification('لا يوجد خيار نسخ متاح', 'info');
        }
    }
    
    focusSearch() {
        const searchInput = document.querySelector('input[name="search"], input[placeholder*="بحث"]');
        if (searchInput) {
            searchInput.focus();
            searchInput.select();
        } else {
            this.showNotification('لا يوجد حقل بحث', 'info');
        }
    }
    
    quickSubmit() {
        this.saveForm();
    }
    
    cancel() {
        const cancelBtn = document.querySelector('a[href*="index"], a[title*="إلغاء"], button[title*="إلغاء"]');
        if (cancelBtn) {
            this.flashButton(cancelBtn);
            cancelBtn.click();
        } else {
            this.showNotification('لا يوجد خيار إلغاء', 'info');
        }
    }
    
    nextTab() {
        // التنقل بين التبويبات إذا كانت موجودة
        const tabs = document.querySelectorAll('[role="tab"], .tab-button');
        if (tabs.length > 1) {
            const activeTab = document.querySelector('[role="tab"][aria-selected="true"], .tab-button.active');
            if (activeTab) {
                const currentIndex = Array.from(tabs).indexOf(activeTab);
                const nextIndex = (currentIndex + 1) % tabs.length;
                tabs[nextIndex].click();
            }
        }
    }
    
    previousTab() {
        const tabs = document.querySelectorAll('[role="tab"], .tab-button');
        if (tabs.length > 1) {
            const activeTab = document.querySelector('[role="tab"][aria-selected="true"], .tab-button.active');
            if (activeTab) {
                const currentIndex = Array.from(tabs).indexOf(activeTab);
                const prevIndex = currentIndex === 0 ? tabs.length - 1 : currentIndex - 1;
                tabs[prevIndex].click();
            }
        }
    }
    
    toggleSidebar() {
        // البحث عن زر فتح/إغلاق القائمة الجانبية
        const sidebarToggle = document.querySelector('[x-data] button[\\@click*="sidebar"]');
        if (sidebarToggle) {
            sidebarToggle.click();
        }
    }
    
    preview() {
        const previewBtn = document.querySelector('a[target="_blank"], a[title*="معاينة"], a[href*="preview"]');
        if (previewBtn) {
            this.flashButton(previewBtn);
            previewBtn.click();
        } else {
            // فتح الموقع في تبويب جديد
            window.open('/', '_blank');
        }
    }
    
    refresh() {
        location.reload();
    }
    
    print() {
        window.print();
    }
    
    // === الوظائف المساعدة ===
    
    flashButton(button) {
        button.classList.add('animate-pulse');
        setTimeout(() => button.classList.remove('animate-pulse'), 300);
    }
    
    showNotification(message, type = 'info') {
        // إنشاء تنبيه مؤقت
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-3 rounded-lg text-white text-sm shadow-lg transition-all duration-300 ${
            type === 'success' ? 'bg-green-500' : 
            type === 'warning' ? 'bg-yellow-500' : 
            type === 'error' ? 'bg-red-500' : 'bg-blue-500'
        }`;
        notification.innerHTML = `
            <div class="flex items-center gap-2">
                <i class="fas fa-keyboard text-white/80"></i>
                ${message}
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // إزالة التنبيه بعد 3 ثوان
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
    
    showShortcutFeedback(key) {
        const feedback = document.createElement('div');
        feedback.className = 'fixed bottom-4 left-4 bg-gray-800 text-white text-xs px-2 py-1 rounded shadow-lg z-50';
        feedback.textContent = key.toUpperCase();
        
        document.body.appendChild(feedback);
        setTimeout(() => feedback.remove(), 1000);
    }
    
    addShortcutTooltips() {
        // إضافة توضيحات للأزرار الرئيسية
        const buttons = {
            'button[type="submit"], input[type="submit"]': 'Ctrl+S للحفظ',
            'button[title="حذف"], button[onclick*="delete"]': 'Ctrl+Shift+D للحذف',
            'a[href*="/create"]': 'Ctrl+N للإنشاء',
            'a[title="تعديل"]': 'Ctrl+E للتعديل',
            'input[name="search"]': 'Ctrl+F للبحث',
            'a[title*="إلغاء"]': 'Esc للإلغاء'
        };
        
        Object.entries(buttons).forEach(([selector, tooltip]) => {
            const elements = document.querySelectorAll(selector);
            elements.forEach(element => {
                const originalTitle = element.getAttribute('title') || '';
                const newTitle = originalTitle ? `${originalTitle} (${tooltip})` : tooltip;
                element.setAttribute('title', newTitle);
            });
        });
    }
    
    showShortcutsHint() {
        // عرض تنبيه بالاختصارات عند تحميل الصفحة
        if (!localStorage.getItem('admin_shortcuts_shown')) {
            setTimeout(() => {
                this.showNotification('💡 تم تفعيل اختصارات لوحة المفاتيح! اضغط Ctrl+? لعرض القائمة الكاملة', 'success');
                localStorage.setItem('admin_shortcuts_shown', 'true');
            }, 2000);
        }
    }
    
    showShortcutsList() {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4';
        modal.innerHTML = `
            <div class="bg-white rounded-lg p-6 max-w-2xl w-full max-h-[80vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">⌨️ اختصارات لوحة المفاتيح</h3>
                    <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-medium text-gray-700 mb-2">🔧 العمليات الأساسية</h4>
                        <ul class="space-y-2 text-sm">
                            <li><kbd class="bg-gray-100 px-2 py-1 rounded">Ctrl+S</kbd> حفظ النموذج</li>
                            <li><kbd class="bg-gray-100 px-2 py-1 rounded">Ctrl+N</kbd> إنشاء جديد</li>
                            <li><kbd class="bg-gray-100 px-2 py-1 rounded">Ctrl+E</kbd> تعديل العنصر</li>
                            <li><kbd class="bg-gray-100 px-2 py-1 rounded">Ctrl+Shift+D</kbd> حذف العنصر</li>
                            <li><kbd class="bg-gray-100 px-2 py-1 rounded">Ctrl+Shift+C</kbd> نسخ العنصر</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-700 mb-2">🔍 التنقل والبحث</h4>
                        <ul class="space-y-2 text-sm">
                            <li><kbd class="bg-gray-100 px-2 py-1 rounded">Ctrl+F</kbd> التركيز على البحث</li>
                            <li><kbd class="bg-gray-100 px-2 py-1 rounded">Ctrl+Tab</kbd> التبويب التالي</li>
                            <li><kbd class="bg-gray-100 px-2 py-1 rounded">Ctrl+Shift+Tab</kbd> التبويب السابق</li>
                            <li><kbd class="bg-gray-100 px-2 py-1 rounded">Esc</kbd> إلغاء/عودة</li>
                            <li><kbd class="bg-gray-100 px-2 py-1 rounded">Ctrl+P</kbd> معاينة</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-700 mb-2">⚡ أخرى مفيدة</h4>
                        <ul class="space-y-2 text-sm">
                            <li><kbd class="bg-gray-100 px-2 py-1 rounded">Ctrl+Enter</kbd> إرسال سريع</li>
                            <li><kbd class="bg-gray-100 px-2 py-1 rounded">Ctrl+Shift+M</kbd> فتح/إغلاق القائمة</li>
                            <li><kbd class="bg-gray-100 px-2 py-1 rounded">Ctrl+R</kbd> تحديث الصفحة</li>
                            <li><kbd class="bg-gray-100 px-2 py-1 rounded">Ctrl+Shift+P</kbd> طباعة</li>
                        </ul>
                    </div>
                </div>
                <div class="mt-6 p-3 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-800">
                        💡 <strong>نصيحة:</strong> معظم الاختصارات تعمل في جميع صفحات لوحة التحكم. 
                        بعض الاختصارات قد لا تعمل داخل حقول النصوص لتجنب التعارض مع الكتابة.
                    </p>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // إغلاق بالضغط خارج النافذة
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
            }
        });
    }
}

// تهيئة الاختصارات عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', () => {
    const shortcuts = new AdminKeyboardShortcuts();
    
    // إضافة اختصار عرض قائمة المساعدة
    shortcuts.shortcuts['ctrl+?'] = () => shortcuts.showShortcutsList();
    shortcuts.shortcuts['ctrl+shift+?'] = () => shortcuts.showShortcutsList();
    
    // جعل الكلاس متاح عالمياً للاستخدام
    window.AdminShortcuts = shortcuts;
});

// إضافة CSS للتأثيرات
const style = document.createElement('style');
style.textContent = `
    .animate-pulse {
        animation: pulse 0.3s ease-in-out;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    kbd {
        font-family: monospace;
        font-size: 0.875em;
    }
`;
document.head.appendChild(style);