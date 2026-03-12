/**
 * تحسينات الخصائص المتقدمة للمنتجات
 * يوفر معالجة متقدمة لحفظ والتحقق من الخصائص
 */

class ProductAttributesManager {
    constructor() {
        this.isProcessing = false;
        this.validationErrors = [];
        this.init();
    }

    init() {
        // Follow form submission
        document.addEventListener('submit', (e) => this.handleFormSubmit(e));
        
        // Add event delegation for variant attribute changes
        document.addEventListener('change', (e) => {
            if (e.target.name && e.target.name.includes('[attributes]')) {
                this.onAttributeChange(e.target);
            }
        });
    }

    /**
     * التعامل مع تقديم الفورم
     */
    handleFormSubmit(e) {
        const form = e.target;
        
        // Only process product forms
        if (!form.id?.includes('product')) return;

        if (form.querySelector('input[name="has_variants_section"]')) {
            e.preventDefault();
            this.validateAndSubmit(form);
        }
    }

    /**
     * التحقق من صحة البيانات قبل الحفظ
     */
    validateAndSubmit(form) {
        this.validationErrors = [];

        // Check variant data
        const variantsInput = this.extractVariantsFromForm(form);
        this.validateVariants(variantsInput);

        if (this.validationErrors.length > 0) {
            this.showValidationErrors();
            return false;
        }

        // Submit if valid
        this.isProcessing = true;
        form.submit();
    }

    /**
     * استخراج بيانات المتغيرات من الفورم
     */
    extractVariantsFromForm(form) {
        const variants = [];
        const formData = new FormData(form);
        
        // Build variants array from form data
        let index = 0;
        while (formData.has(`variants[${index}][sku]`)) {
            variants.push({
                index,
                sku: formData.get(`variants[${index}][sku]`),
                stock_quantity: formData.get(`variants[${index}][stock_quantity]`),
                price: formData.get(`variants[${index}][price]`),
            });
            index++;
        }

        return variants;
    }

    /**
     * التحقق من صحة المتغيرات
     */
    validateVariants(variants) {
        if (variants.length === 0) return; // No variants - optional for simple products

        variants.forEach((variant, idx) => {
            // SKU validation
            if (!variant.sku || variant.sku.trim() === '') {
                this.addError(`المتغير ${idx + 1}: SKU مطلوب`);
            }

            // Stock validation
            if (!variant.stock_quantity || isNaN(parseInt(variant.stock_quantity))) {
                this.addError(`المتغير ${idx + 1}: الكمية المتاحة مطلوبة ويجب أن تكون رقماً`);
            }

            if (parseInt(variant.stock_quantity) < 0) {
                this.addError(`المتغير ${idx + 1}: الكمية المتاحة لا يمكن أن تكون سالبة`);
            }
        });
    }

    /**
     * إضافة خطأ التحقق
     */
    addError(message) {
        this.validationErrors.push(message);
    }

    /**
     * عرض أخطاء التحقق
     */
    showValidationErrors() {
        // Create error notification
        const errorHTML = `
            <div class="fixed top-4 right-4 bg-red-50 border border-red-200 rounded-xl p-4 shadow-lg z-50 max-w-md">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-red-800 mb-2">تحقق من البيانات المدخلة:</h4>
                        <ul class="text-red-700 text-sm space-y-1">
                            ${this.validationErrors.map(err => `<li><i class="fas fa-times-circle ml-1"></i>${err}</li>`).join('')}
                        </ul>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 ml-auto">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;

        // Remove old errors
        document.querySelectorAll('.product-validation-error').forEach(el => el.remove());

        // Add new error
        const errorDiv = document.createElement('div');
        errorDiv.className = 'product-validation-error';
        errorDiv.innerHTML = errorHTML;
        document.body.appendChild(errorDiv);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            errorDiv.querySelector('[onclick]')?.click();
        }, 5000);
    }

    /**
     * معالج تغيير الخصائص
     */
    onAttributeChange(input) {
        // Validate the input value
        const value = input.value.trim();
        
        // Add visual feedback
        if (!value && !input.required) {
            input.classList.add('opacity-50');
        } else {
            input.classList.remove('opacity-50');
        }
    }

    /**
     * تنسيق بيانات المتغيرات قبل الإرسال
     */
    cleanVariantData(formData) {
        const cleaned = new FormData();
        
        for (const [key, value] of formData.entries()) {
            if (key.includes('[attributes]')) {
                // Clean attribute values
                const cleanValue = typeof value === 'string' ? value.trim() : value;
                if (cleanValue !== '') {
                    cleaned.append(key, cleanValue);
                }
            } else {
                cleaned.append(key, value);
            }
        }

        return cleaned;
    }

    /**
     * تصدير حالة المتغيرات (للتصحيح)
     */
    debugVariants(form) {
        const formData = new FormData(form);
        const debug = {};
        
        for (const [key, value] of formData.entries()) {
            if (key.includes('variants')) {
                debug[key] = value;
            }
        }

        console.log('Variants Debug:', debug);
        return debug;
    }
}

// Initialize on document ready
document.addEventListener('DOMContentLoaded', () => {
    window.ProductAttributesManager = new ProductAttributesManager();
});

// Export for global access if needed
if (typeof window !== 'undefined') {
    window.AttributesManager = ProductAttributesManager;
}
