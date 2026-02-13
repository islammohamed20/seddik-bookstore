(function () {
    const STORAGE_KEY = 'visitor_location_sent';

    async function sendVisit(payload) {
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            await fetch('/track-visit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token || ''
                },
                body: JSON.stringify(payload)
            });
        } catch (e) {
            // silent
        }
    }

    async function reverseGeocode(lat, lon) {
        try {
            const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&zoom=10&addressdetails=1`;
            const res = await fetch(url, { headers: { 'Accept-Language': 'ar' } });
            const data = await res.json();
            const address = data.address || {};
            return {
                city: address.city || address.town || address.village || address.county || null,
                region: address.state || address.region || null,
                country: address.country || null
            };
        } catch (e) {
            return { city: null, region: null, country: null };
        }
    }

    async function trackWithGeolocation() {
        if (!navigator.geolocation) {
            return trackWithoutGeolocation();
        }

        navigator.geolocation.getCurrentPosition(async (pos) => {
            const lat = pos.coords.latitude;
            const lon = pos.coords.longitude;
            const place = await reverseGeocode(lat, lon);

            await sendVisit({
                latitude: lat,
                longitude: lon,
                city: place.city,
                region: place.region,
                country: place.country,
                path: window.location.pathname,
                referrer: document.referrer,
                source: 'browser'
            });

            sessionStorage.setItem(STORAGE_KEY, '1');
        }, async () => {
            await trackWithoutGeolocation();
        }, {
            enableHighAccuracy: false,
            timeout: 8000,
            maximumAge: 300000
        });
    }

    async function trackWithoutGeolocation() {
        await sendVisit({
            path: window.location.pathname,
            referrer: document.referrer,
            source: 'browser'
        });
        sessionStorage.setItem(STORAGE_KEY, '1');
    }

    function init() {
        if (sessionStorage.getItem(STORAGE_KEY)) return;
        trackWithGeolocation();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
