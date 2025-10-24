<div>
    @assets
    <link rel="stylesheet" href="{{ asset('assets/vendor/leafletmaps/leaflet.css') }}">
    <script src="{{ asset('assets/vendor/leafletmaps/leaflet.js') }}"></script>
    <style>
        #eventMap { height: 400px; min-height: 400px; width: 100%; }
    </style>
    @endassets
    
    <div wire:ignore>
        <div id="eventMap" class="border rounded"></div>
    </div>
</div>

@script
<script>
let eventMap = null;
let eventMarker = null;

// Initialize map immediately
if (typeof L !== 'undefined') {
    initMap();
} else {
    setTimeout(initMap, 500);
}

function initMap() {
    if (typeof L === 'undefined') {
        setTimeout(initMap, 500);
        return;
    }
    
    if (eventMap) return;
    
    try {
        eventMap = L.map('eventMap').setView([41.9028, 12.4964], 6);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap',
            maxZoom: 19
        }).addTo(eventMap);
        
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
            
            // Reverse geocode to get address
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(r => r.json())
                .then(data => {
                    if (data && data.address) {
                        const addr = data.address;
                        
                        // Dispatch event to parent component with address data
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
        
        // Force map to recalculate size multiple times to ensure proper display
        setTimeout(() => {
            if (eventMap) {
                eventMap.invalidateSize();
                console.log('✅ Event Map initialized and sized');
            }
        }, 100);
        
        setTimeout(() => {
            if (eventMap) eventMap.invalidateSize();
        }, 500);
        
    } catch (e) {
        console.error('Map error:', e);
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

