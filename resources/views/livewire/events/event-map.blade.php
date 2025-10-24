<div>
    @assets
    <link rel="stylesheet" href="{{ asset('assets/vendor/leafletmaps/leaflet.css') }}">
    <script src="{{ asset('assets/vendor/leafletmaps/leaflet.js') }}"></script>
    @endassets
    
    <div wire:ignore>
        <div id="eventMap" class="border rounded"></div>
    </div>
</div>

@script
<script>
let eventMap = null;
let eventMarker = null;

function initMap() {
    // Wait for Leaflet to be loaded
    if (typeof L === 'undefined') {
        setTimeout(initMap, 300);
        return;
    }
    
    // Prevent double initialization
    if (eventMap) return;
    
    try {
        // Wait for DOM element to be ready
        const mapElement = document.getElementById('eventMap');
        if (!mapElement) {
            setTimeout(initMap, 300);
            return;
        }
        
        // Initialize map
        eventMap = L.map('eventMap', {
            scrollWheelZoom: true,
            zoomControl: true
        }).setView([41.9028, 12.4964], 6);
        
        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap',
            maxZoom: 19
        }).addTo(eventMap);
        
        // Click handler
        eventMap.on('click', (e) => {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            
            $wire.latitude = lat;
            $wire.longitude = lng;
            
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
        
        // CRITICAL: Force resize after initialization
        setTimeout(() => {
            if (eventMap) {
                eventMap.invalidateSize(true);
            }
        }, 250);
        
        setTimeout(() => {
            if (eventMap) {
                eventMap.invalidateSize(true);
            }
        }, 750);
        
        setTimeout(() => {
            if (eventMap) {
                eventMap.invalidateSize(true);
            }
        }, 1500);
        
        console.log('✅ Event Map initialized');
        
    } catch (e) {
        console.error('❌ Map initialization error:', e);
        setTimeout(initMap, 500);
    }
}

// Start initialization
initMap();

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

