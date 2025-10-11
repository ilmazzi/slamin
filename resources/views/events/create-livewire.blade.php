@extends('layout.master')

@section('title', __('events.create_event'))

@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/leafletmaps/leaflet.css') }}">
<!-- Flatpickr CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datepikar/flatpickr.min.css') }}">
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
/* Map Controls Styles */
.leaflet-control-zoom {
    z-index: 1000 !important;
}

.leaflet-control-zoom a {
    background-color: white !important;
    border: 1px solid #ccc !important;
    color: #333 !important;
    font-weight: bold !important;
}

.leaflet-control-zoom a:hover {
    background-color: #f4f4f4 !important;
}
</style>
@endsection

@section('main-content')
    @livewire('event-creation')
@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/leafletmaps/leaflet.js') }}"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Flatpickr JS -->
<script src="{{ asset('assets/vendor/datepikar/flatpickr.js') }}"></script>

<script>
let map = null;
let marker = null;
let mapInitializing = false;
let isUpdatingFromMap = false;
let isUpdatingAddressFields = false;

// Initialize map when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, waiting for Livewire...');

    // Wait for Livewire to be ready
    setTimeout(() => {
        initializeMap();
        initializeFlatpickr();
    }, 3000);
});

// Initialize when Livewire is ready
document.addEventListener('livewire:init', () => {
    console.log('Livewire initialized');
    setTimeout(() => {
        initializeMap();
        initializeFlatpickr();
    }, 2000);
});

// Re-initialize when Livewire updates
document.addEventListener('livewire:updated', () => {
    console.log('⚠️ Livewire updated - checking map...');

    // Get the component that triggered the update
    const event = arguments[0];
    console.log('Update event:', event);

    setTimeout(() => {
        // Check if map container exists but map is not initialized
        const mapContainer = document.getElementById('locationMap');
        if (mapContainer && !map) {
            console.log('Re-initializing map after update...');
            initializeMap();
        } else if (mapContainer && map) {
            // Map exists, just resize it
            console.log('✅ Map still exists, resizing...');
            map.invalidateSize();

            // Restore marker if it existed
            if (window.lastMapClick) {
                if (marker) {
                    marker.remove();
                }
                marker = L.marker([window.lastMapClick.lat, window.lastMapClick.lng]).addTo(map);
                console.log('✅ Marker restored after Livewire update');
            }
        } else {
            console.log('❌ Map container not found after Livewire update!');
        }
    }, 100);
});

// Also try when step changes
document.addEventListener('livewire:navigated', () => {
    console.log('Livewire navigated');
    setTimeout(() => {
        if (document.getElementById('locationMap') && !map) {
            console.log('Re-initializing map after navigation...');
            initializeMap();
        }
    }, 1000);
});

// Listen for radio button changes (online/in-person toggle)
document.addEventListener('change', function(e) {
    if (e.target && (e.target.id === 'in_person' || e.target.id === 'online')) {
        console.log('🔄 Location mode changed:', e.target.id);

        if (e.target.id === 'in_person') {
            // Switching to in-person mode
            console.log('🔄 Switching to in-person mode, waiting for Livewire to update DOM...');

            // Wait for Livewire to update the DOM first
            setTimeout(() => {
                console.log('🔍 First check after 500ms...');
                let mapContainer = document.getElementById('locationMap');
                console.log('🔍 Map container found:', !!mapContainer);

                if (!mapContainer) {
                    console.log('⚠️ Container not found yet, waiting longer...');
                    // Wait longer for Livewire to update
                    setTimeout(() => {
                        console.log('🔍 Second check after 1500ms...');
                        mapContainer = document.getElementById('locationMap');
                        console.log('🔍 Map container found:', !!mapContainer);

                        if (mapContainer) {
                            console.log('🔍 Container visibility:', mapContainer.offsetParent !== null);
                            if (mapContainer.offsetParent !== null) {
                                console.log('🔄 Forcing map re-initialization for in-person mode...');
                                // Reset everything
                                map = null;
                                marker = null;
                                mapInitializing = false;
                                // Clear container
                                mapContainer.innerHTML = '';
                                // Re-initialize
                                setTimeout(() => {
                                    initializeMap();
                                }, 300);
                            }
                        } else {
                            console.log('❌ Container still not found after 1500ms');
                        }
                    }, 1000);
                } else {
                    console.log('🔍 Container visibility:', mapContainer.offsetParent !== null);
                    if (mapContainer.offsetParent !== null) {
                        console.log('🔄 Forcing map re-initialization for in-person mode...');
                        // Reset everything
                        map = null;
                        marker = null;
                        mapInitializing = false;
                        // Clear container
                        mapContainer.innerHTML = '';
                        // Re-initialize
                        setTimeout(() => {
                            initializeMap();
                        }, 300);
                    }
                }
            }, 500);
        } else {
            // Switching to online mode - reset variables
            console.log('🔄 Switching to online mode, resetting map variables...');
            map = null;
            marker = null;
            mapInitializing = false;
        }
    }
});

// Try to initialize map when currentStep changes to 2
document.addEventListener('livewire:updated', () => {
    // Check if we're on step 2
    const step2Element = document.getElementById('step-2');
    if (step2Element && step2Element.style.display !== 'none' && document.getElementById('locationMap')) {
        if (!map) {
            console.log('🔄 Step 2 is visible, trying to initialize map...');
            setTimeout(() => {
                initializeMap();
            }, 1000);
        } else {
            console.log('🔄 Step 2 is visible, resizing existing map...');
            setTimeout(() => {
                map.invalidateSize();
            }, 100);
        }
    }
});

function initializeMap() {
    console.log('=== INITIALIZING MAP ===');

    // Check if already initializing
    if (mapInitializing) {
        console.log('⚠️ Map initialization already in progress');
        return;
    }

    // Check if Leaflet is loaded first
    if (typeof L === 'undefined') {
        console.error('❌ Leaflet not loaded');
        return;
    }

    console.log('✅ Leaflet loaded');

    // Check if map is already initialized
    if (map) {
        console.log('⚠️ Map already initialized');
        return;
    }

    mapInitializing = true;

    // Try to find the map container
    const mapContainer = document.getElementById('locationMap');

    if (!mapContainer) {
        console.log('❌ Map container not found, retrying in 1 second...');
        mapInitializing = false;
        setTimeout(() => {
            initializeMap();
        }, 1000);
        return;
    }

    console.log('✅ Map container found:', mapContainer);

    // Check if container is visible
    if (mapContainer.offsetParent === null) {
        console.log('⚠️ Map container not visible, retrying in 1 second...');
        mapInitializing = false;
        setTimeout(() => {
            initializeMap();
        }, 1000);
        return;
    }

    console.log('✅ Map container is visible');

        try {
            // Always clear container for fresh start
            mapContainer.innerHTML = '';
            console.log('🧹 Container cleared for fresh initialization');

            // Initialize map centered on Rome, Italy
            map = L.map('locationMap', {
                zoomControl: true,
                preferCanvas: false
            }).setView([41.9028, 12.4964], 10);

        console.log('✅ Map created successfully');

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        console.log('✅ Tiles added');

        // Set zoom control position
        map.zoomControl.setPosition('topright');

        // Add click event to map
        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;

            console.log('🗺️ Map clicked:', lat, lng);

            // Set flag to prevent forward geocoding
            isUpdatingFromMap = true;

            // Update hidden inputs
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');

            if (latInput) latInput.value = lat;
            if (lngInput) lngInput.value = lng;

            // Update Livewire component WITHOUT triggering re-render
            try {
                const latInput = document.getElementById('latitude');
                const lngInput = document.getElementById('longitude');

                if (latInput && lngInput) {
                    // Update the input values
                    latInput.value = lat;
                    lngInput.value = lng;

                    // Use a more gentle approach to update Livewire
                    // Don't trigger input events immediately to avoid re-render
                    console.log('✅ Coordinates updated locally');

                    // Reverse geocoding to get address
                    reverseGeocode(lat, lng);

                    // Update Livewire after a delay to avoid re-render
                    setTimeout(() => {
                        if (latInput && lngInput) {
                            latInput.dispatchEvent(new Event('input', { bubbles: true }));
                            lngInput.dispatchEvent(new Event('input', { bubbles: true }));
                            console.log('✅ Livewire updated after delay');
                        }
                    }, 500);
                }
            } catch (e) {
                console.log('⚠️ Could not update Livewire component:', e);
            }

            // Add or update marker
            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng]).addTo(map);
            }
            console.log('✅ Marker updated');

            // Store coordinates for potential map recreation
            window.lastMapClick = { lat: lat, lng: lng };
        });

        // Force map to resize after initialization
        setTimeout(() => {
            if (map) {
                map.invalidateSize();
                console.log('✅ Map resized');

                // Restore marker if it existed before
                if (window.lastMapClick) {
                    marker = L.marker([window.lastMapClick.lat, window.lastMapClick.lng]).addTo(map);
                    console.log('✅ Marker restored from previous click');
                }
            }
        }, 300);

        console.log('🎉 Map initialization complete!');

    } catch (error) {
        console.error('❌ Error initializing map:', error);
    } finally {
        mapInitializing = false;
    }
}

// Reverse geocoding function
function reverseGeocode(lat, lng) {
    console.log('🔄 Starting reverse geocoding for:', lat, lng);

    // Show loading status
    const statusElement = document.getElementById('geocoding-status');
    if (statusElement) {
        statusElement.style.display = 'block';
    }

    // Use Nominatim API for reverse geocoding
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`)
        .then(response => response.json())
        .then(data => {
            console.log('✅ Reverse geocoding result:', data);

            if (data && data.address) {
                // Update address fields
                updateAddressFields(data.address, data.display_name);
            }

            // Hide loading status
            if (statusElement) {
                statusElement.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('❌ Reverse geocoding error:', error);

            // Hide loading status
            if (statusElement) {
                statusElement.style.display = 'none';
            }
        });
}

// Update address fields from geocoding result
function updateAddressFields(address, displayName) {
    console.log('📍 Updating address fields:', address);

    // Set flag to prevent forward geocoding
    isUpdatingAddressFields = true;

    // Update venue name if not set
    const venueNameInput = document.querySelector('input[wire\\:model\\.live="venue_name"]');
    if (venueNameInput && !venueNameInput.value && address.amenity) {
        venueNameInput.value = address.amenity;
        // Don't trigger event immediately to avoid re-render
        setTimeout(() => {
            venueNameInput.dispatchEvent(new Event('input', { bubbles: true }));
        }, 100);
    }

    // Update address (FIRST - most important) - NO LIVEWIRE, solo JavaScript puro
    const addressInput = document.getElementById('venue-address-input');
    if (addressInput) {
        const street = address.road || address.street || address.pedestrian || '';
        const houseNumber = address.house_number || '';
        const fullAddress = houseNumber ? `${street} ${houseNumber}` : street;
        if (fullAddress && fullAddress.trim()) {
            addressInput.value = fullAddress;
            console.log('✅ Address updated:', fullAddress);
        }
    }

    // Update city
    const cityInput = document.getElementById('city-input');
    if (cityInput) {
        const city = address.city || address.town || address.village || address.municipality || address.county || '';
        if (city && city.trim()) {
            cityInput.value = city;
            console.log('✅ City updated:', city);
        }
    }

    // Update postcode
    const postcodeInput = document.getElementById('postcode-input');
    if (postcodeInput && address.postcode) {
        postcodeInput.value = address.postcode;
        console.log('✅ Postcode updated:', address.postcode);
    }

    // Update country
    const countrySelect = document.getElementById('country-select');
    if (countrySelect && address.country_code) {
        const countryCode = address.country_code.toUpperCase();
        const option = countrySelect.querySelector(`option[value="${countryCode}"]`);
        if (option) {
            countrySelect.value = countryCode;
            console.log('✅ Country updated:', countryCode);
        }
    }

    console.log('✅ Address fields updated (no Livewire events triggered)');

    // Reset flags after updating address fields (longer timeout to prevent forward geocoding)
    setTimeout(() => {
        isUpdatingFromMap = false;
        isUpdatingAddressFields = false;
        console.log('🔄 Reset flags: isUpdatingFromMap and isUpdatingAddressFields');
    }, 3000);
}

// Also try to initialize when step 2 is shown
document.addEventListener('click', function(e) {
    // Check if it's a step navigation button
    if (e.target && (e.target.textContent && e.target.textContent.includes('Step 2')) ||
        (e.target.closest('[wire\\:click]') && e.target.closest('[wire\\:click]').getAttribute('wire:click') &&
         e.target.closest('[wire\\:click]').getAttribute('wire:click').includes('nextStep'))) {
        setTimeout(() => {
            if (document.getElementById('locationMap') && !map) {
                console.log('🔄 Initializing map after step navigation...');
                initializeMap();
            }
        }, 1500);
    }
});

// Also try when the page becomes visible (in case of tab switching)
document.addEventListener('visibilitychange', function() {
    if (!document.hidden && document.getElementById('locationMap') && !map) {
        console.log('🔄 Page visible, trying to initialize map...');
        setTimeout(() => {
            initializeMap();
        }, 1000);
    }
});

// Forward geocoding when address fields change (riabilitato grazie a wire:ignore!)
document.addEventListener('input', function(e) {
    // Check if it's an address field by ID
    if (e.target && (
        e.target.id === 'venue-address-input' ||
        e.target.id === 'city-input' ||
        e.target.id === 'postcode-input'
    )) {
        console.log('📝 User typing in address field:', e.target.id);
        // Debounce the geocoding
        clearTimeout(window.geocodingTimeout);
        window.geocodingTimeout = setTimeout(() => {
            geocodeAddress();
        }, 2000);
    }
});

// Also listen for country select change
document.addEventListener('change', function(e) {
    if (e.target && e.target.id === 'country-select') {
        console.log('📝 Country changed:', e.target.value);
        clearTimeout(window.geocodingTimeout);
        window.geocodingTimeout = setTimeout(() => {
            geocodeAddress();
        }, 1000);
    }
});

// Listen for online/in-person toggle
document.addEventListener('livewire:updated', () => {
    console.log('🔄 Livewire updated - checking map status...');
    const mapContainer = document.getElementById('locationMap');

    if (mapContainer) {
        console.log('✅ Map container exists');
        // Check if map container is visible (in-person mode)
        if (mapContainer.offsetParent !== null) {
            console.log('✅ In-person mode - map should be visible');

            // Check if container is empty (no leaflet elements)
            const hasLeafletElements = mapContainer.querySelector('.leaflet-container');
            console.log('🔍 Has leaflet elements:', !!hasLeafletElements);
            console.log('🔍 Map variable exists:', !!map);

            if (!map || !hasLeafletElements) {
                console.log('🔄 Livewire: Switching to in-person mode, initializing map...');
                // Reset variables first
                map = null;
                marker = null;
                mapInitializing = false;

                setTimeout(() => {
                    initializeMap();
                }, 800);
            } else {
                console.log('🔄 Livewire: Map already exists, resizing...');
                setTimeout(() => {
                    if (map) {
                        map.invalidateSize();
                    }
                }, 300);
            }
        } else {
            console.log('❌ Online mode - map is hidden (as expected)');
            // Map is hidden, do nothing
        }
    } else {
        console.log('❌ Map container not found');
        // Map container doesn't exist - reset map variable
        if (map) {
            console.log('🗑️ Map container removed, resetting map variable...');
            map = null;
            marker = null;
            mapInitializing = false;
        }
    }
});

// Forward geocoding function
function geocodeAddress() {
    console.log('🔄 Starting forward geocoding...');

    // Don't geocode if we're updating from map click or updating address fields
    if (isUpdatingFromMap || isUpdatingAddressFields) {
        console.log('⚠️ Skipping forward geocoding - updating from map or address fields');
        return;
    }

    const venueAddress = document.getElementById('venue-address-input')?.value;
    const city = document.getElementById('city-input')?.value;
    const postcode = document.getElementById('postcode-input')?.value;
    const country = document.getElementById('country-select')?.value;

    // Build address string
    let addressString = '';
    if (venueAddress) addressString += venueAddress;
    if (city) addressString += (addressString ? ', ' : '') + city;
    if (postcode) addressString += (addressString ? ', ' : '') + postcode;
    if (country) addressString += (addressString ? ', ' : '') + country;

    if (!addressString.trim()) {
        console.log('⚠️ No address to geocode');
        return;
    }

    console.log('📍 Geocoding address:', addressString);

    // Show loading status
    const statusElement = document.getElementById('geocoding-status');
    if (statusElement) {
        statusElement.style.display = 'block';
    }

    // Use Nominatim API for forward geocoding
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(addressString)}&limit=1&addressdetails=1`)
        .then(response => response.json())
        .then(data => {
            console.log('✅ Forward geocoding result:', data);

            if (data && data.length > 0) {
                const result = data[0];
                const lat = parseFloat(result.lat);
                const lng = parseFloat(result.lon);

                // Update map marker only
                if (map) {
                    if (marker) {
                        marker.setLatLng([lat, lng]);
                    } else {
                        marker = L.marker([lat, lng]).addTo(map);
                    }
                    map.setView([lat, lng], 15);
                    console.log('✅ Map marker updated');
                }

                // DON'T update coordinate inputs to prevent Livewire re-render
                // Coordinates will be updated only when user clicks on map
                console.log('ℹ️ Coordinates NOT updated (only map marker moved)');

                // Store coordinates for later use
                window.lastForwardGeocode = { lat: lat, lng: lng };
            }

            // Hide loading status
            if (statusElement) {
                statusElement.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('❌ Forward geocoding error:', error);

            // Hide loading status
            if (statusElement) {
                statusElement.style.display = 'none';
            }
        });
}

// Initialize Flatpickr for date/time inputs
function initializeFlatpickr() {
    console.log('🕒 Initializing Flatpickr...');

    // Check if flatpickr is loaded
    if (typeof flatpickr === 'undefined') {
        console.error('❌ Flatpickr not loaded');
        return;
    }

    // Destroy existing instances first
    flatpickr('.flatpickr-input', 'destroy');

    // Start datetime picker
    const startDateTimePicker = flatpickr("#start_datetime", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: "today",
        minTime: "00:00",
        time_24hr: true,
        onChange: function(selectedDates, dateStr, instance) {
            // Update end datetime minimum date
            if (window.endDateTimePicker) {
                window.endDateTimePicker.set('minDate', selectedDates[0]);
            }
            // Clear error when valid date is selected
            document.getElementById('start_datetime').classList.remove('is-invalid');
            document.getElementById('start_datetime').classList.add('is-valid');
        },
        onClose: function(selectedDates, dateStr, instance) {
            // Ensure the format is correct for Laravel validation
            if (dateStr) {
                instance.input.value = dateStr.replace('T', ' ');
            }
        }
    });

    // End datetime picker
    window.endDateTimePicker = flatpickr("#end_datetime", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: "today",
        minTime: "00:00",
        time_24hr: true,
        onChange: function(selectedDates, dateStr, instance) {
            // Clear error when valid date is selected
            document.getElementById('end_datetime').classList.remove('is-invalid');
            document.getElementById('end_datetime').classList.add('is-valid');
        },
        onClose: function(selectedDates, dateStr, instance) {
            // Ensure the format is correct for Laravel validation
            if (dateStr) {
                instance.input.value = dateStr.replace('T', ' ');
            }
        }
    });

    // Registration deadline picker
    flatpickr("#registration_deadline", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: "today",
        minTime: "00:00",
        time_24hr: true,
        onChange: function(selectedDates, dateStr, instance) {
            // Ensure the format is correct for Laravel validation
            if (dateStr) {
                instance.input.value = dateStr.replace('T', ' ');
            }
        },
        onClose: function(selectedDates, dateStr, instance) {
            // Ensure the format is correct for Laravel validation
            if (dateStr) {
                instance.input.value = dateStr.replace('T', ' ');
            }
        }
    });

    // Invitation deadline picker
    flatpickr("#invitation_deadline", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: "today",
        minTime: "00:00",
        time_24hr: true,
        onChange: function(selectedDates, dateStr, instance) {
            // Ensure the format is correct for Laravel validation
            if (dateStr) {
                instance.input.value = dateStr.replace('T', ' ');
            }
        },
        onClose: function(selectedDates, dateStr, instance) {
            // Ensure the format is correct for Laravel validation
            if (dateStr) {
                instance.input.value = dateStr.replace('T', ' ');
            }
        }
    });

    // Availability deadline picker
    flatpickr("#availability_deadline", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: "today",
        minTime: "00:00",
        time_24hr: true,
        onChange: function(selectedDates, dateStr, instance) {
            // Ensure the format is correct for Laravel validation
            if (dateStr) {
                instance.input.value = dateStr.replace('T', ' ');
            }
        },
        onClose: function(selectedDates, dateStr, instance) {
            // Ensure the format is correct for Laravel validation
            if (dateStr) {
                instance.input.value = dateStr.replace('T', ' ');
            }
        }
    });

    console.log('✅ Flatpickr initialized successfully');
}

// ========================================
// AVAILABILITY OPTIONS MANAGEMENT
// ========================================
let availabilityOptionCounter = 0;

// Initialize availability options when page loads
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        initializeAvailabilityOptions();
    }, 3000);
});

// Initialize when Livewire is ready
document.addEventListener('livewire:init', () => {
    setTimeout(() => {
        initializeAvailabilityOptions();
    }, 2000);
});

// Re-initialize when Livewire updates
document.addEventListener('livewire:updated', () => {
    setTimeout(() => {
        initializeAvailabilityOptions();
    }, 1000);
});

function initializeAvailabilityOptions() {
    console.log('📅 Initializing availability options...');

    const addAvailabilityOptionBtn = document.getElementById('add-availability-option');
    if (addAvailabilityOptionBtn) {
        // Remove existing listeners to avoid duplicates
        addAvailabilityOptionBtn.removeEventListener('click', addAvailabilityOption);
        addAvailabilityOptionBtn.addEventListener('click', addAvailabilityOption);
        console.log('✅ Availability options initialized');
    }
}

// Aggiungi nuova opzione di disponibilità
function addAvailabilityOption() {
    availabilityOptionCounter++;
    console.log('➕ Adding availability option:', availabilityOptionCounter);

    const availabilityOptionsList = document.getElementById('availability-options-list');
    if (!availabilityOptionsList) {
        console.error('❌ Availability options list not found');
        return;
    }

    const optionHtml = `
        <div class="card mb-3" id="availability-option-${availabilityOptionCounter}">
            <div class="card-body">
                <div class="row align-items-end">
                    <div class="col-md-5 mb-2">
                        <label class="form-label">{{ __('events.availability_option_datetime') }} *</label>
                        <input type="text" name="availability_options[${availabilityOptionCounter}][datetime]"
                               class="form-control availability-datetime-picker"
                               placeholder="{{ __('events.availability_option_datetime') }}"
                               required readonly>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">{{ __('events.availability_option_description') }}</label>
                        <input type="text" name="availability_options[${availabilityOptionCounter}][description]"
                               class="form-control"
                               placeholder="{{ __('events.availability_option_description') }}">
                    </div>
                    <div class="col-md-1 mb-2">
                        <button type="button" class="btn btn-outline-danger"
                                onclick="removeAvailabilityOption(${availabilityOptionCounter})"
                                title="{{ __('events.remove_availability_option') }}">
                            <i class="ph ph-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    availabilityOptionsList.insertAdjacentHTML('beforeend', optionHtml);

    // Inizializza flatpickr per il nuovo campo dopo un breve delay
    setTimeout(() => {
        if (typeof flatpickr !== 'undefined') {
            const pickerElement = document.querySelector(`#availability-option-${availabilityOptionCounter} .availability-datetime-picker`);
            if (pickerElement) {
                flatpickr(pickerElement, {
                    enableTime: true,
                    dateFormat: "Y-m-d H:i",
                    minDate: "today",
                    minTime: "00:00",
                    time_24hr: true,
                    onChange: function(selectedDates, dateStr, instance) {
                        if (dateStr) {
                            instance.input.value = dateStr.replace('T', ' ');
                        }
                    },
                    onClose: function(selectedDates, dateStr, instance) {
                        if (dateStr) {
                            instance.input.value = dateStr.replace('T', ' ');
                        }
                    }
                });
                console.log('✅ Flatpickr initialized for availability option:', availabilityOptionCounter);
            }
        }
    }, 100);
}

// Rimuovi opzione di disponibilità
function removeAvailabilityOption(optionId) {
    console.log('🗑️ Removing availability option:', optionId);
    const optionElement = document.getElementById(`availability-option-${optionId}`);
    if (optionElement) {
        optionElement.remove();
        console.log('✅ Availability option removed:', optionId);
    }
}
</script>
@endpush


