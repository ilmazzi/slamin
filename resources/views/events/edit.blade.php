@extends('layout.app')

@section('title', __('events.edit_event'))

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}" class="text-decoration-none">
                                <i class="ph ph-house me-1"></i>{{ __('common.dashboard') }}
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('events.index') }}" class="text-decoration-none">
                                <i class="ph ph-calendar me-1"></i>{{ __('events.events') }}
                            </a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('events.edit_event') }}</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="ph ph-pencil-simple me-2"></i>{{ __('events.edit_event') }}
                </h4>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ph ph-calendar-plus me-2"></i>{{ __('events.edit_event_details') }}
                        </h5>
                        <a href="{{ route('events.show', $event) }}" class="btn btn-light">
                            <i class="ph ph-eye me-1"></i>{{ __('common.view') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data" id="editEventForm">
                        @csrf
                        @method('PUT')

                        <!-- Step 1: Basic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="ph ph-info me-2"></i>{{ __('events.basic_information') }}
                                </h6>
                            </div>

                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">{{ __('events.title') }} *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                           id="title" name="title" value="{{ old('title', $event->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="category" class="form-label">{{ __('events.category') }} *</label>
                                    <select class="form-select @error('category') is-invalid @enderror"
                                            id="category" name="category" required>
                                        <option value="">{{ __('events.select_category') }}</option>
                                        @foreach(App\Models\Event::getCategories() as $value => $label)
                                            <option value="{{ $value }}" {{ old('category', $event->category) == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">{{ __('events.description') }}</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="4">{{ old('description', $event->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Date and Location -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="ph ph-map-pin me-2"></i>{{ __('events.date_and_location') }}
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_datetime" class="form-label">{{ __('events.start_datetime') }} *</label>
                                    <input type="datetime-local" class="form-control @error('start_datetime') is-invalid @enderror"
                                           id="start_datetime" name="start_datetime"
                                           value="{{ old('start_datetime', $event->start_datetime ? $event->start_datetime->format('Y-m-d\TH:i') : '') }}" required>
                                    @error('start_datetime')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_datetime" class="form-label">{{ __('events.end_datetime') }} *</label>
                                    <input type="datetime-local" class="form-control @error('end_datetime') is-invalid @enderror"
                                           id="end_datetime" name="end_datetime"
                                           value="{{ old('end_datetime', $event->end_datetime ? $event->end_datetime->format('Y-m-d\TH:i') : '') }}" required>
                                    @error('end_datetime')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="venue_name" class="form-label">{{ __('events.venue_name') }} *</label>
                                    <input type="text" class="form-control @error('venue_name') is-invalid @enderror"
                                           id="venue_name" name="venue_name" value="{{ old('venue_name', $event->venue_name) }}" required>
                                    @error('venue_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="city" class="form-label">{{ __('events.city') }} *</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                                           id="city" name="city" value="{{ old('city', $event->city) }}" required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="venue_address" class="form-label">{{ __('events.venue_address') }} *</label>
                                    <input type="text" class="form-control @error('venue_address') is-invalid @enderror"
                                           id="venue_address" name="venue_address" value="{{ old('venue_address', $event->venue_address) }}" required>
                                    @error('venue_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Hidden coordinates -->
                            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $event->latitude) }}">
                            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $event->longitude) }}">

                            <!-- Map -->
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('events.location_on_map') }}</label>
                                    <div id="map" style="height: 300px; border-radius: 0.375rem;"></div>
                                    <small class="text-muted">{{ __('events.map_help') }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Settings -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="ph ph-gear me-2"></i>{{ __('events.settings') }}
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="max_participants" class="form-label">{{ __('events.max_participants') }}</label>
                                    <input type="number" class="form-control @error('max_participants') is-invalid @enderror"
                                           id="max_participants" name="max_participants"
                                           value="{{ old('max_participants', $event->max_participants) }}" min="1">
                                    <small class="text-muted">{{ __('events.max_participants_help') }}</small>
                                    @error('max_participants')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input @error('accepts_requests') is-invalid @enderror"
                                               type="checkbox" id="accepts_requests" name="accepts_requests" value="1"
                                               {{ old('accepts_requests', $event->accepts_requests) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="accepts_requests">
                                            {{ __('events.accepts_requests') }}
                                        </label>
                                        @error('accepts_requests')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Image -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="ph ph-image me-2"></i>{{ __('events.event_image') }}
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">{{ __('events.new_image') }}</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                                           id="image" name="image" accept="image/*">
                                    <small class="text-muted">{{ __('events.image_help') }}</small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            @if($event->image_url)
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('events.current_image') }}</label>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $event->image_url }}" alt="{{ $event->title }}"
                                             class="img-thumbnail me-3" style="max-width: 100px; max-height: 100px;">
                                        <div>
                                            <small class="text-muted d-block">{{ __('events.current_image_help') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('events.show', $event) }}" class="btn btn-light">
                                        <i class="ph ph-arrow-left me-1"></i>{{ __('common.cancel') }}
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ph ph-check me-1"></i>{{ __('common.update') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<script>
let map, marker;

document.addEventListener('DOMContentLoaded', function() {
    initializeMap();
    setupFormValidation();
});

function initializeMap() {
    // Initialize map
    map = L.map('map').setView([{{ $event->latitude ?? 41.9028 }}, {{ $event->longitude ?? 12.4964 }}], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add marker if coordinates exist
    if ({{ $event->latitude ?? 'null' }} && {{ $event->longitude ?? 'null' }}) {
        marker = L.marker([{{ $event->latitude }}, {{ $event->longitude }}]).addTo(map);
    }

    // Handle map clicks
    map.on('click', function(e) {
        setMapLocation(e.latlng.lat, e.latlng.lng);
    });
}

function setMapLocation(lat, lng) {
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;

    if (marker) {
        map.removeLayer(marker);
    }

    marker = L.marker([lat, lng]).addTo(map);
    map.setView([lat, lng], 15);
}

function setupFormValidation() {
    const form = document.getElementById('editEventForm');

    form.addEventListener('submit', function(e) {
        let isValid = true;

        // Clear previous errors
        form.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });

        // Validate required fields
        const requiredFields = ['title', 'category', 'start_datetime', 'end_datetime', 'venue_name', 'venue_address', 'city'];

        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field && !field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            }
        });

        // Validate dates
        const startDate = document.getElementById('start_datetime').value;
        const endDate = document.getElementById('end_datetime').value;

        if (startDate && endDate && new Date(startDate) >= new Date(endDate)) {
            document.getElementById('end_datetime').classList.add('is-invalid');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            alert('{{ __("events.please_correct_errors") }}');
        }
    });
}
</script>
@endpush
