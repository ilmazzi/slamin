<div>
    <div wire:ignore>
        <div id="eventMap" class="border rounded"></div>
    </div>
</div>

@script
<script>
let eventMap = null;
let eventMarker = null;

// Auto-initialize when DOM is ready
document.addEventListener('livewire:navigated', () => {
    initMap();
});

// Also init on first load
setTimeout(() => {
    initMap();
}, 1000);

function initMap() {
    // Wait for Leaflet library
    if (typeof L === 'undefined') {
        console.log('⏳ Waiting for Leaflet...');
        setTimeout(initMap, 500);
        return;
    }
    
    // Check if element exists
    const mapElement = document.getElementById('eventMap');
    if (!mapElement) {
        console.log('⏳ Waiting for map element...');
        setTimeout(initMap, 500);
        return;
    }
    
    // Prevent double initialization
    if (eventMap !== null) {
        console.log('ℹ️ Map already initialized');
        return;
    }
    
    try {
        console.log('🗺️ Initializing Event Map...');
        
        // Initialize map
        eventMap = L.map('eventMap', {
            scrollWheelZoom: true,
            zoomControl: true,
            attributionControl: true
        }).setView([41.9028, 12.4964], 6);
        
        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap',
            maxZoom: 19
        }).addTo(eventMap);
        
        // Click handler for selecting location
        eventMap.on('click', (e) => {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            
            $wire.set('latitude', lat);
            $wire.set('longitude', lng);
            
            if (eventMarker) {
                eventMarker.setLatLng([lat, lng]);
            } else {
                eventMarker = L.marker([lat, lng]).addTo(eventMap);
            }
            
            // Reverse geocode
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(r => r.json())
                .then(data => {
                    if (data && data.address) {
                        const addr = data.address;
                        $wire.dispatch('map-clicked', {
                            latitude: lat,
                            longitude: lng,
                            city: addr.city || addr.town || addr.village || '',
                            address: (addr.road || '') + (addr.house_number ? ' ' + addr.house_number : ''),
                            postcode: addr.postcode || '',
                            country: addr.country_code ? addr.country_code.toUpperCase() : ''
                        });
                    }
                });
        });
        
        // CRITICAL: Force multiple resizes to ensure proper display
        [250, 500, 1000, 2000].forEach(delay => {
            setTimeout(() => {
                if (eventMap) {
                    eventMap.invalidateSize(true);
                }
            }, delay);
        });
        
        console.log('✅ Event Map initialized successfully');
        
    } catch (e) {
        console.error('❌ Map initialization error:', e);
        eventMap = null;
        setTimeout(initMap, 1000);
    }
}

// Listen for updates from parent component (e.g. when a recent venue is selected)
Livewire.on('update-map-location', (event) => {
    const { latitude, longitude } = event;
    
    if (!eventMap || !latitude || !longitude) return;
    
    try {
        // Update map view
        eventMap.setView([latitude, longitude], 15);
        
        // Update or create marker
        if (eventMarker) {
            eventMarker.setLatLng([latitude, longitude]);
        } else {
            eventMarker = L.marker([latitude, longitude]).addTo(eventMap);
        }
        
        console.log('✅ Map updated to:', latitude, longitude);
    } catch (e) {
        console.error('Error updating map:', e);
    }
});
</script>
@endscript

