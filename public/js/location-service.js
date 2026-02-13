/**
 * Location Service for automatic address detection
 */
class LocationService {
    constructor() {
        this.apiKey = ''; // You'll need a geocoding API key
        this.coordinates = null;
        this.address = null;
    }

    /**
     * Get user's current location
     */
    async getCurrentLocation() {
        try {
            if (!navigator.geolocation) {
                throw new Error('الموقع الجغرافي غير مدعوم في هذا المتصفح');
            }

            const position = await new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(resolve, reject, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 300000 // 5 minutes
                });
            });

            this.coordinates = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            return this.coordinates;
        } catch (error) {
            console.error('Location error:', error);
            throw new Error(this.getLocationErrorMessage(error.code));
        }
    }

    /**
     * Convert coordinates to address using reverse geocoding
     */
    async getAddressFromCoordinates(lat, lng) {
        try {
            // Using Nominatim (free OpenStreetMap service)
            const response = await fetch(
                `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1&accept-language=ar`
            );
            
            if (!response.ok) {
                throw new Error('فشل في الحصول على العنوان');
            }

            const data = await response.json();
            
            if (!data || !data.display_name) {
                throw new Error('لم يتم العثور على عنوان لهذا الموقع');
            }

            this.address = this.formatAddress(data);
            return this.address;
        } catch (error) {
            console.error('Geocoding error:', error);
            throw error;
        }
    }

    /**
     * Format the address from geocoding response
     */
    formatAddress(geocodingData) {
        const address = geocodingData.address || {};
        
        return {
            full_address: geocodingData.display_name,
            street: address.road || address.pedestrian || '',
            area: address.neighbourhood || address.suburb || '',
            city: address.city || address.town || address.village || '',
            state: address.state || address.governorate || '',
            country: address.country || 'مصر',
            postal_code: address.postcode || '',
            coordinates: {
                lat: parseFloat(geocodingData.lat),
                lng: parseFloat(geocodingData.lon)
            }
        };
    }

    /**
     * Get location error message in Arabic
     */
    getLocationErrorMessage(errorCode) {
        const messages = {
            1: 'تم رفض الوصول للموقع. يرجى السماح بالوصول للموقع في إعدادات المتصفح',
            2: 'فشل في تحديد الموقع. تأكد من تفعيل الـ GPS أو الاتصال بالإنترنت',
            3: 'انتهت مهلة تحديد الموقع. يرجى المحاولة مرة أخرى',
            default: 'حدث خطأ في تحديد الموقع'
        };
        
        return messages[errorCode] || messages.default;
    }

    /**
     * Auto-detect and fill address
     */
    async autoDetectAndFill(callback) {
        try {
            // Show loading state
            this.showLocationLoading(true);
            
            // Get coordinates
            const coords = await this.getCurrentLocation();
            
            // Get address
            const address = await this.getAddressFromCoordinates(coords.lat, coords.lng);
            
            // Hide loading state
            this.showLocationLoading(false);
            
            // Execute callback with address data
            if (callback && typeof callback === 'function') {
                callback(address);
            }
            
            return address;
        } catch (error) {
            this.showLocationLoading(false);
            this.showLocationError(error.message);
            throw error;
        }
    }

    /**
     * Show loading indicator
     */
    showLocationLoading(show = true) {
        const button = document.getElementById('detect-location-btn');
        const icon = button?.querySelector('i');
        const text = button?.querySelector('.btn-text');
        
        if (!button) return;
        
        if (show) {
            button.disabled = true;
            if (icon) icon.className = 'fas fa-spinner fa-spin';
            if (text) text.textContent = 'جاري تحديد الموقع...';
        } else {
            button.disabled = false;
            if (icon) icon.className = 'fas fa-map-marker-alt';
            if (text) text.textContent = 'تحديد موقعي الحالي';
        }
    }

    /**
     * Show error message
     */
    showLocationError(message) {
        // Create or update error alert
        let errorDiv = document.getElementById('location-error');
        
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.id = 'location-error';
            errorDiv.className = 'alert alert-warning mt-2 d-none';
            
            const button = document.getElementById('detect-location-btn');
            if (button && button.parentNode) {
                button.parentNode.insertBefore(errorDiv, button.nextSibling);
            }
        }
        
        errorDiv.innerHTML = `
            <i class="fas fa-exclamation-triangle ml-2"></i>
            ${message}
        `;
        errorDiv.classList.remove('d-none');
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            errorDiv.classList.add('d-none');
        }, 5000);
    }
}

// Create global instance
window.locationService = new LocationService();

/**
 * Initialize location detection for forms
 */
function initLocationDetection() {
    const button = document.getElementById('detect-location-btn');
    
    if (button) {
        button.addEventListener('click', async function() {
            try {
                const address = await window.locationService.autoDetectAndFill((addressData) => {
                    // Fill form fields
                    fillAddressForm(addressData);
                });
            } catch (error) {
                console.error('Location detection failed:', error);
            }
        });
    }
}

/**
 * Fill address form with detected data
 */
function fillAddressForm(addressData) {
    const fields = {
        'address': addressData.full_address,
        'street': addressData.street,
        'area': addressData.area,
        'city': addressData.city,
        'state': addressData.state,
        'country': addressData.country,
        'postal_code': addressData.postal_code
    };
    
    // Fill form fields
    Object.entries(fields).forEach(([fieldName, value]) => {
        const input = document.querySelector(`[name="${fieldName}"], #${fieldName}`);
        if (input && value) {
            input.value = value;
            
            // Trigger change event for frameworks that need it
            input.dispatchEvent(new Event('change'));
            input.dispatchEvent(new Event('input'));
        }
    });
    
    // Store coordinates as hidden fields if they exist
    if (addressData.coordinates) {
        let latField = document.querySelector('[name="latitude"], #latitude');
        let lngField = document.querySelector('[name="longitude"], #longitude');
        
        if (!latField) {
            latField = document.createElement('input');
            latField.type = 'hidden';
            latField.name = 'latitude';
            document.querySelector('form').appendChild(latField);
        }
        
        if (!lngField) {
            lngField = document.createElement('input');
            lngField.type = 'hidden';
            lngField.name = 'longitude';
            document.querySelector('form').appendChild(lngField);
        }
        
        latField.value = addressData.coordinates.lat;
        lngField.value = addressData.coordinates.lng;
    }
    
    // Show success message
    showLocationSuccess('تم تحديد العنوان تلقائياً بنجاح!');
}

/**
 * Show success message
 */
function showLocationSuccess(message) {
    let successDiv = document.getElementById('location-success');
    
    if (!successDiv) {
        successDiv = document.createElement('div');
        successDiv.id = 'location-success';
        successDiv.className = 'alert alert-success mt-2 d-none';
        
        const button = document.getElementById('detect-location-btn');
        if (button && button.parentNode) {
            button.parentNode.insertBefore(successDiv, button.nextSibling);
        }
    }
    
    successDiv.innerHTML = `
        <i class="fas fa-check-circle ml-2"></i>
        ${message}
    `;
    successDiv.classList.remove('d-none');
    
    // Auto hide after 3 seconds
    setTimeout(() => {
        successDiv.classList.add('d-none');
    }, 3000);
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', initLocationDetection);