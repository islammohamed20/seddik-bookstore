/**
 * Cookie Consent Management System
 */
class CookieConsent {
    constructor() {
        this.cookieName = 'cookie_consent';
        this.consentDuration = 365; // days
        this.categories = {
            necessary: true, // Always true, can't be disabled
            analytics: false,
            marketing: false,
            preferences: false
        };
        
        this.init();
    }

    /**
     * Initialize cookie consent system
     */
    init() {
        // Check if consent already given
        if (!this.hasConsent()) {
            this.showConsentBanner();
        } else {
            this.loadApprovedCookies();
        }
    }

    /**
     * Check if user has given consent
     */
    hasConsent() {
        return this.getCookie(this.cookieName) !== null;
    }

    /**
     * Get cookie value
     */
    getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) {
            return parts.pop().split(';').shift();
        }
        return null;
    }

    /**
     * Set cookie
     */
    setCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        const expires = `expires=${date.toUTCString()}`;
        document.cookie = `${name}=${value};${expires};path=/;SameSite=Lax`;
    }

    /**
     * Show consent banner
     */
    showConsentBanner() {
        const banner = this.createConsentBanner();
        document.body.appendChild(banner);
        
        // Animate in
        setTimeout(() => {
            banner.classList.add('show');
        }, 100);
    }

    /**
     * Create consent banner HTML
     */
    createConsentBanner() {
        const banner = document.createElement('div');
        banner.id = 'cookie-consent-banner';
        banner.className = 'cookie-consent-banner';
        
        banner.innerHTML = `
            <div class="cookie-consent-content">
                <div class="cookie-consent-text">
                    <h4 class="cookie-consent-title">
                        <i class="fas fa-cookie-bite"></i>
                        نحن نستخدم ملفات تعريف الارتباط
                    </h4>
                    <p class="cookie-consent-description">
                        نستخدم ملفات تعريف الارتباط (الكوكيز) لتحسين تجربتك وتقديم المحتوى المناسب. 
                        بالمتابعة، أنت توافق على استخدامها.
                    </p>
                </div>
                <div class="cookie-consent-actions">
                    <button id="accept-all-cookies" class="btn btn-accept">
                        قبول الجميع
                    </button>
                    <button id="customize-cookies" class="btn btn-customize">
                        تخصيص
                    </button>
                    <button id="reject-optional-cookies" class="btn btn-reject">
                        رفض الاختيارية
                    </button>
                </div>
            </div>
        `;

        this.attachBannerEvents(banner);
        return banner;
    }

    /**
     * Attach event listeners to banner
     */
    attachBannerEvents(banner) {
        banner.querySelector('#accept-all-cookies').addEventListener('click', () => {
            this.acceptAll();
        });

        banner.querySelector('#customize-cookies').addEventListener('click', () => {
            this.showCustomizeModal();
        });

        banner.querySelector('#reject-optional-cookies').addEventListener('click', () => {
            this.rejectOptional();
        });
    }

    /**
     * Accept all cookies
     */
    acceptAll() {
        this.categories = {
            necessary: true,
            analytics: true,
            marketing: true,
            preferences: true
        };
        this.saveConsent();
        this.hideConsentBanner();
        this.loadApprovedCookies();
    }

    /**
     * Reject optional cookies
     */
    rejectOptional() {
        this.categories = {
            necessary: true,
            analytics: false,
            marketing: false,
            preferences: false
        };
        this.saveConsent();
        this.hideConsentBanner();
        this.loadApprovedCookies();
    }

    /**
     * Show customize modal
     */
    showCustomizeModal() {
        const modal = this.createCustomizeModal();
        document.body.appendChild(modal);
        
        // Animate in
        setTimeout(() => {
            modal.classList.add('show');
        }, 100);
    }

    /**
     * Create customize modal
     */
    createCustomizeModal() {
        const modal = document.createElement('div');
        modal.id = 'cookie-customize-modal';
        modal.className = 'cookie-modal-overlay';
        
        modal.innerHTML = `
            <div class="cookie-modal">
                <div class="cookie-modal-header">
                    <h3>تخصيص ملفات تعريف الارتباط</h3>
                    <button class="close-modal">&times;</button>
                </div>
                <div class="cookie-modal-body">
                    <div class="cookie-category">
                        <div class="category-header">
                            <h4>ضرورية</h4>
                            <div class="toggle-container">
                                <input type="checkbox" id="necessary" checked disabled>
                                <label for="necessary" class="toggle-disabled">مطلوبة</label>
                            </div>
                        </div>
                        <p>هذه الملفات ضرورية لعمل الموقع ولا يمكن تعطيلها.</p>
                    </div>
                    
                    <div class="cookie-category">
                        <div class="category-header">
                            <h4>التحليلات</h4>
                            <div class="toggle-container">
                                <input type="checkbox" id="analytics" ${this.categories.analytics ? 'checked' : ''}>
                                <label for="analytics" class="toggle"></label>
                            </div>
                        </div>
                        <p>تساعدنا في فهم كيفية استخدام الزوار للموقع لتحسين الأداء.</p>
                    </div>
                    
                    <div class="cookie-category">
                        <div class="category-header">
                            <h4>التسويق</h4>
                            <div class="toggle-container">
                                <input type="checkbox" id="marketing" ${this.categories.marketing ? 'checked' : ''}>
                                <label for="marketing" class="toggle"></label>
                            </div>
                        </div>
                        <p>تُستخدم لعرض إعلانات مخصصة بناءً على اهتماماتك.</p>
                    </div>
                    
                    <div class="cookie-category">
                        <div class="category-header">
                            <h4>التفضيلات</h4>
                            <div class="toggle-container">
                                <input type="checkbox" id="preferences" ${this.categories.preferences ? 'checked' : ''}>
                                <label for="preferences" class="toggle"></label>
                            </div>
                        </div>
                        <p>تحفظ تفضيلاتك مثل اللغة والعملة والموقع.</p>
                    </div>
                </div>
                <div class="cookie-modal-footer">
                    <button id="save-preferences" class="btn btn-primary">حفظ التفضيلات</button>
                    <button id="accept-all-modal" class="btn btn-secondary">قبول الجميع</button>
                </div>
            </div>
        `;

        this.attachModalEvents(modal);
        return modal;
    }

    /**
     * Attach modal event listeners
     */
    attachModalEvents(modal) {
        modal.querySelector('.close-modal').addEventListener('click', () => {
            this.hideModal(modal);
        });

        modal.querySelector('#save-preferences').addEventListener('click', () => {
            this.saveCustomPreferences(modal);
        });

        modal.querySelector('#accept-all-modal').addEventListener('click', () => {
            this.acceptAll();
            this.hideModal(modal);
        });

        // Close on overlay click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                this.hideModal(modal);
            }
        });
    }

    /**
     * Save custom preferences
     */
    saveCustomPreferences(modal) {
        this.categories.analytics = modal.querySelector('#analytics').checked;
        this.categories.marketing = modal.querySelector('#marketing').checked;
        this.categories.preferences = modal.querySelector('#preferences').checked;
        
        this.saveConsent();
        this.hideConsentBanner();
        this.hideModal(modal);
        this.loadApprovedCookies();
    }

    /**
     * Save consent to cookie
     */
    saveConsent() {
        const consentData = {
            timestamp: new Date().toISOString(),
            categories: this.categories
        };
        this.setCookie(this.cookieName, JSON.stringify(consentData), this.consentDuration);
    }

    /**
     * Hide consent banner
     */
    hideConsentBanner() {
        const banner = document.getElementById('cookie-consent-banner');
        if (banner) {
            banner.classList.add('hide');
            setTimeout(() => {
                banner.remove();
            }, 300);
        }
    }

    /**
     * Hide modal
     */
    hideModal(modal) {
        modal.classList.add('hide');
        setTimeout(() => {
            modal.remove();
        }, 300);
    }

    /**
     * Load approved cookies and scripts
     */
    loadApprovedCookies() {
        const consent = this.getCookie(this.cookieName);
        if (!consent) return;

        try {
            const consentData = JSON.parse(consent);
            this.categories = consentData.categories;

            // Load analytics if approved
            if (this.categories.analytics) {
                this.loadAnalytics();
            }

            // Load marketing cookies if approved
            if (this.categories.marketing) {
                this.loadMarketing();
            }

            // Apply preferences if approved
            if (this.categories.preferences) {
                this.loadPreferences();
            }
        } catch (error) {
            console.error('Error parsing consent data:', error);
        }
    }

    /**
     * Load analytics scripts
     */
    loadAnalytics() {
        console.log('Loading analytics...');
        // Add Google Analytics or other analytics scripts here
        // Example:
        // gtag('config', 'GA_TRACKING_ID');
    }

    /**
     * Load marketing scripts
     */
    loadMarketing() {
        console.log('Loading marketing tools...');
        // Add Facebook Pixel, Google Ads, etc.
    }

    /**
     * Load preferences
     */
    loadPreferences() {
        console.log('Loading user preferences...');
        // Apply saved user preferences
    }

    /**
     * Get consent status
     */
    getConsentStatus() {
        const consent = this.getCookie(this.cookieName);
        if (!consent) return null;

        try {
            return JSON.parse(consent);
        } catch (error) {
            return null;
        }
    }

    /**
     * Show cookie settings (for privacy page or settings)
     */
    showCookieSettings() {
        this.showCustomizeModal();
    }
}

// Initialize cookie consent when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.cookieConsent = new CookieConsent();
});