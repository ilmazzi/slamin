<div>
    <div wire:ignore>
        <div id="eventMap" class="border rounded"></div>
    </div>
</div>

@script
<script>
let eventMap = null;
let eventMarker = null;

// Wait for element to have dimensions before initializing
function waitForElement() {
    return new Promise((resolve) => {
        const checkElement = () => {
            const mapElement = document.getElementById('eventMap');
            if (!mapElement) {
                setTimeout(checkElement, 100);
                return;
            }
            
            // Check if element has dimensions
            const rect = mapElement.getBoundingClientRect();
            if (rect.height === 0 || rect.width === 0) {
                setTimeout(checkElement, 100);
                return;
            }
            
            resolve(mapElement);
        };
        checkElement();
    });
}

async function initMap() {
    // Wait for Leaflet library
    if (typeof L === 'undefined') {
        setTimeout(initMap, 300);
        return;
    }
    
    // Prevent double initialization
    if (eventMap !== null) {
        return;
    }
    
    try {
        // Wait for element to have dimensions
        await waitForElement();
        
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
        
        // Click handler
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
        
        // Force resize after tiles load
        setTimeout(() => {
            if (eventMap) eventMap.invalidateSize(true);
        }, 500);
        
    } catch (e) {
        eventMap = null;
    }
}

// Initialize on component load
document.addEventListener('livewire:navigated', () => {
    eventMap = null;
    initMap();
});

// First load
initMap();

// Listen for trigger-geocode event from THIS component (Livewire 3 way)
@this.on('trigger-geocode', (event) => {
    const address = event.address || event[0]?.address;
    if (!address) {
        console.log('No address to geocode');
        return;
    }
    
    console.log('Geocoding address:', address);
    
    // Geocode using Nominatim
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
        .then(r => r.json())
        .then(data => {
            console.log('Geocoding result:', data);
            if (data && data.length > 0) {
                const lat = parseFloat(data[0].lat);
                const lng = parseFloat(data[0].lon);
                
                console.log('Setting pin at:', lat, lng);
                
                // Update wire properties using @this (Livewire 3)
                @this.set('latitude', lat);
                @this.set('longitude', lng);
                
                // Update map
                if (eventMap) {
                    eventMap.setView([lat, lng], 15);
                    
                    if (eventMarker) {
                        eventMarker.setLatLng([lat, lng]);
                    } else {
                        eventMarker = L.marker([lat, lng]).addTo(eventMap);
                    }
                }
            } else {
                console.log('No results found for address');
            }
        })
        .catch(err => {
            console.error('Geocoding error:', err);
        });
});

// Listen for updates from parent component (e.g. when a recent venue is selected)
@this.on('update-map-location', (event) => {
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

