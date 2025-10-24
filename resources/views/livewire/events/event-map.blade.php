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
    console.log('🔄 initMap() called');
    
    // Wait for Leaflet library
    if (typeof L === 'undefined') {
        console.log('⏳ Leaflet not loaded yet');
        setTimeout(initMap, 300);
        return;
    }
    
    // Prevent double initialization
    if (eventMap !== null) {
        console.log('ℹ️ Map already initialized');
        return;
    }
    
    try {
        console.log('⏳ Waiting for map element with dimensions...');
        // Wait for element to have dimensions
        await waitForElement();
        
        console.log('🗺️ Initializing Leaflet map...');
        
        // Initialize map
        eventMap = L.map('eventMap', {
            scrollWheelZoom: true,
            zoomControl: true,
            attributionControl: true
        }).setView([41.9028, 12.4964], 6);
        
        console.log('✅ Leaflet map object created:', eventMap !== null);
        
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
            if (eventMap) {
                eventMap.invalidateSize(true);
                console.log('✅ Map resized and ready!');
            }
        }, 500);
        
    } catch (e) {
        // If map is already initialized, that's OK, just log it
        if (e.message && e.message.includes('already initialized')) {
            console.log('ℹ️ Map already initialized (this is OK)');
            // Don't set to null! The map is actually working
        } else {
            console.error('❌ Map initialization failed:', e);
            eventMap = null;
        }
    }
}

// Initialize ONCE - use a flag to prevent double init
let mapInitStarted = false;

function safeInitMap() {
    if (mapInitStarted) {
        console.log('⚠️ Map init already in progress, skipping');
        return;
    }
    mapInitStarted = true;
    initMap();
}

// Initialize on component load
document.addEventListener('livewire:navigated', () => {
    console.log('📡 livewire:navigated event');
    if (eventMap === null) {
        mapInitStarted = false;
        safeInitMap();
    }
});

// First load
console.log('🚀 Starting first map init...');
safeInitMap();

// Watch for fullAddress changes (Livewire 3 reactive approach)
let lastAddress = '';

Livewire.hook('commit', ({ component, succeed }) => {
    succeed(() => {
        const currentAddress = $wire.fullAddress || '';
        
        // Only geocode if address changed and has minimum length
        if (currentAddress !== lastAddress && currentAddress.length >= 5) {
            lastAddress = currentAddress;
            
            console.log('Auto-geocoding:', currentAddress);
            
            // Geocode using Nominatim
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(currentAddress)}`)
                .then(r => r.json())
                .then(data => {
                    console.log('Geocoding result:', data);
                    if (data && data.length > 0) {
                        const lat = parseFloat(data[0].lat);
                        const lng = parseFloat(data[0].lon);
                        
                        console.log('Setting pin at:', lat, lng);
                        console.log('eventMap exists:', eventMap !== null);
                        
                        // Update properties
                        $wire.set('latitude', lat);
                        $wire.set('longitude', lng);
                        
                        // Wait for map to be initialized if not ready
                        const updateMap = () => {
                            if (!eventMap) {
                                console.log('Map not ready, retrying...');
                                setTimeout(updateMap, 500);
                                return;
                            }
                            
                            console.log('Updating map with pin...');
                            eventMap.setView([lat, lng], 15);
                            
                            if (eventMarker) {
                                eventMarker.setLatLng([lat, lng]);
                            } else {
                                eventMarker = L.marker([lat, lng]).addTo(eventMap);
                            }
                            console.log('✅ Pin placed!');
                        };
                        
                        updateMap();
                    } else {
                        console.log('No results found');
                    }
                })
                .catch(err => {
                    console.error('Geocoding error:', err);
                });
        }
    });
});
</script>
@endscript

