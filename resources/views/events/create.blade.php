@extends('layout.master')

@section('title', __('events.create_event'))
@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/leafletmaps/leaflet.css') }}">
<!-- Flatpickr CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/datepikar/flatpickr.min.css') }}">
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
/* Venue Autocomplete Styles */
.venue-autocomplete-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    z-index: 1050;
    max-height: 300px;
    overflow-y: auto;
}

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

.autocomplete-header {
    padding: 0.5rem 0.75rem;
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    border-radius: 0.375rem 0.375rem 0 0;
}

.autocomplete-suggestions {
    padding: 0.25rem 0;
}

.autocomplete-suggestion {
    padding: 0.5rem 0.75rem;
    cursor: pointer;
    border-bottom: 1px solid #f1f3f4;
    transition: background-color 0.15s ease-in-out;
}

.autocomplete-suggestion:hover {
    background-color: #e3f2fd;
}

.autocomplete-suggestion:last-child {
    border-bottom: none;
}

.autocomplete-suggestion .venue-name {
    font-weight: 600;
    color: #212529;
    margin-bottom: 0.25rem;
}

.autocomplete-suggestion .venue-details {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.autocomplete-suggestion .venue-stats {
    font-size: 0.75rem;
    color: #28a745;
    font-weight: 500;
}

.autocomplete-suggestion.selected {
    background-color: #007bff;
    color: white;
}

.autocomplete-suggestion.selected .venue-name,
.autocomplete-suggestion.selected .venue-details,
.autocomplete-suggestion.selected .venue-stats {
    color: white;
}

/* Disable browser autocomplete completely */
#venue_name {
    -webkit-autofill: none !important;
    -webkit-box-shadow: 0 0 0 1000px white inset !important;
    -webkit-text-fill-color: #000 !important;
    background-color: white !important;
    background-image: none !important;
}

/* Additional protection against browser autocomplete */
#venue_name:-webkit-autofill,
#venue_name:-webkit-autofill:hover,
#venue_name:-webkit-autofill:focus,
#venue_name:-webkit-autofill:active {
    -webkit-box-shadow: 0 0 0 1000px white inset !important;
    -webkit-text-fill-color: #000 !important;
    background-color: white !important;
    background-image: none !important;
    transition: background-color 5000s ease-in-out 0s;
}

/* Select2 Custom Styling */
.select2-container--default .select2-selection--single {
    height: 58px !important;
    border: 1px solid #ced4da !important;
    border-radius: 0.375rem !important;
    padding: 0.375rem 0.75rem !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 50px !important;
    padding-left: 0 !important;
    color: #495057 !important;
}

.select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: #6c757d !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 50px !important;
    right: 10px !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-color: #6c757d transparent transparent transparent !important;
}

.select2-container--default.select2-container--focus .select2-selection--single {
    border-color: #80bdff !important;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25) !important;
}

.select2-dropdown {
    border: 1px solid #ced4da !important;
    border-radius: 0.375rem !important;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.select2-container--default .select2-results__option {
    padding: 0.75rem !important;
    border-bottom: 1px solid #f1f3f4 !important;
    transition: all 0.2s ease-in-out !important;
}

.select2-container--default .select2-results__option:last-child {
    border-bottom: none !important;
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #007bff !important;
    color: white !important;
}

/* Hover effect for non-selected options */
.select2-container--default .select2-results__option:hover:not(.select2-results__option--highlighted) {
    background-color: #e9ecef !important;
    transform: translateX(2px) !important;
}

.select2-container--default .select2-results__option:hover:not(.select2-results__option--highlighted) .venue-name {
    color: #495057 !important;
}

.select2-container--default .select2-results__option:hover:not(.select2-results__option--highlighted) .venue-stats {
    color: #28a745 !important;
}

.select2-container--default .select2-search--dropdown .select2-search__field {
    border: 1px solid #ced4da !important;
    border-radius: 0.375rem !important;
    padding: 0.375rem 0.75rem !important;
}

.venue-option {
    display: flex;
    flex-direction: column;
    padding: 0.5rem 0;
}

.venue-option .venue-name {
    font-weight: 600;
    color: #212529;
    margin-bottom: 0.25rem;
}

.venue-option .venue-details {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.venue-option .venue-stats {
    font-size: 0.75rem;
    color: #28a745;
    font-weight: 500;
}
</style>

@endsection

@section('main-content')
<div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <div class="col-md-8">
    <!-- Page Header -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="text-center mb-4">
                <h2 class="mb-2">
                    <i class="ph ph-calendar-plus me-2"></i>{{ __('events.create_event') }}
                </h2>
                <p class="text-muted mb-0">{{ __('events.create_event_help') }}</p>
            </div>

                        <!-- Wizard Steps - Mobile First -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <!-- Desktop Steps -->
                                <div class="d-none d-lg-flex align-items-center justify-content-center">
                                    <div class="text-center" data-step="1">
                                        <i class="ph ph-info fs-1 text-primary mb-2"></i>
                                        <div class="small fw-bold text-primary">{{ __('events.step_1') }}</div>
                                    </div>
                                    <i class="ph ph-arrow-right text-muted mx-3"></i>
                                    <div class="text-center" data-step="2">
                                        <i class="ph ph-calendar-check fs-1 text-muted mb-2"></i>
                                        <div class="small fw-bold text-muted">{{ __('events.step_2') }}</div>
                                    </div>
                                    <i class="ph ph-arrow-right text-muted mx-3"></i>
                                    <div class="text-center" data-step="3">
                                        <i class="ph ph-gear fs-1 text-muted mb-2"></i>
                                        <div class="small fw-bold text-muted">{{ __('events.step_3') }}</div>
                                    </div>
                                    <i class="ph ph-arrow-right text-muted mx-3"></i>
                                    <div class="text-center" data-step="4">
                                        <i class="ph ph-users fs-1 text-muted mb-2"></i>
                                        <div class="small fw-bold text-muted">{{ __('events.step_4') }}</div>
                                    </div>
                                    <i class="ph ph-arrow-right text-muted mx-3"></i>
                                    <div class="text-center" data-step="5">
                                        <i class="ph ph-eye fs-1 text-muted mb-2"></i>
                                        <div class="small fw-bold text-muted">{{ __('events.step_5') }}</div>
                                    </div>
                                </div>

                                <!-- Mobile Steps -->
                                <div class="d-lg-none">
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="text-center flex-fill" data-step="1">
                                                    <i class="ph ph-info f-s-24 text-primary mb-1"></i>
                                                    <div class="f-s-10 fw-bold text-primary">{{ __('events.step_1_short') }}
                                                    </div>
                                                </div>
                                                <i class="ph ph-arrow-right text-muted f-s-12 mx-1"></i>
                                                <div class="text-center flex-fill" data-step="2">
                                                    <i class="ph ph-calendar-check f-s-24 text-muted mb-1"></i>
                                                    <div class="f-s-10 fw-bold text-muted">{{ __('events.step_2_short') }}
                                                    </div>
                                                </div>
                                                <i class="ph ph-arrow-right text-muted f-s-12 mx-1"></i>
                                                <div class="text-center flex-fill" data-step="3">
                                                    <i class="ph ph-gear f-s-24 text-muted mb-1"></i>
                                                    <div class="f-s-10 fw-bold text-muted">{{ __('events.step_3_short') }}
                                                    </div>
                                                </div>
                                                <i class="ph ph-arrow-right text-muted f-s-12 mx-1"></i>
                                                <div class="text-center flex-fill" data-step="4">
                                                    <i class="ph ph-users f-s-24 text-muted mb-1"></i>
                                                    <div class="f-s-10 fw-bold text-muted">{{ __('events.step_4_short') }}
                                                    </div>
                                                </div>
                                                <i class="ph ph-arrow-right text-muted f-s-12 mx-1"></i>
                                                <div class="text-center flex-fill" data-step="5">
                                                    <i class="ph ph-eye f-s-24 text-muted mb-1"></i>
                                                    <div class="f-s-10 fw-bold text-muted">{{ __('events.step_5_short') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
        </div>
    </div>

    <form id="eventForm" action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

            <!-- Form Steps -->

                <!-- Step 1: Basic Information -->
                <div class="card" id="step-1">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph ph-info me-2"></i>{{ __('events.step_basic_info') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="form-floating">
                                            <input type="text" name="title" id="title" class="form-control"
                                                placeholder="{{ __('events.title_placeholder') }}" required>
                                    <label for="title">{{ __('events.title_event') }} *</label>
                                </div>
                                <div class="error-feedback" id="title-error"></div>
                            </div>

                            <!-- Toggle per Sottotitolo -->
                            <div class="col-12 mb-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" id="subtitle-toggle">
                                        <label class="form-check-label" for="subtitle-toggle">
                                            <i class="ph ph-plus-circle me-2"></i>Aggiungi sottotitolo
                                        </label>
                                    </div>
                                    <small class="text-muted">Opzionale</small>
                                </div>
                            </div>

                            <!-- Campo Sottotitolo (nascosto di default) -->
                            <div class="col-12 mb-3" id="subtitle-field" style="display: none;">
                                <div class="form-floating">
                                            <input type="text" name="subtitle" id="subtitle" class="form-control"
                                                placeholder="Inserisci un sottotitolo per l'evento...">
                                    <label for="subtitle">Sottotitolo evento</label>
                                </div>
                                        <small class="text-muted">Un sottotitolo può aiutare a descrivere meglio il tuo
                                            evento</small>
                                <div class="error-feedback" id="subtitle-error"></div>
                            </div>

                            <div class="col-12 mb-3">
                                <div class="form-floating">
                                    <textarea name="description" id="description" class="form-control" style="height: 120px" placeholder="Descrizione"></textarea>
                                    <label for="description">{{ __('events.description_event') }}</label>
                                </div>
                                <small class="text-muted">{{ __('events.description_event_help') }}</small>
                                <div class="error-feedback" id="description-error"></div>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label class="form-label">{{ __('events.event_category') }} *</label>
                                <select name="category" id="category" class="form-select" required>
                                    <option value="">{{ __('events.category_placeholder') }}</option>
                                            @foreach (App\Models\Event::getCategories() as $key => $name)
                                        <option value="{{ $key }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">{{ __('events.category_help') }}</small>
                                <div class="error-feedback" id="category-error"></div>
                            </div>

                            <!-- Sezione Pubblico/Privato -->
                            <div class="col-12 mb-3">
                                <h6 class="text-primary mb-3">
                                    <i class="ph ph-globe me-2"></i>{{ __('events.event_mode') }}
                                </h6>
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <div class="form-check">
                                                    <input type="radio" name="is_public" id="public" value="1"
                                                        class="form-check-input" checked>
                                            <label for="public" class="form-check-label">
                                                <i class="ph ph-globe me-2"></i>{{ __('events.mode_public') }}
                                                        <small
                                                            class="d-block text-muted">{{ __('events.public_event_description') }}</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="form-check">
                                                    <input type="radio" name="is_public" id="private" value="0"
                                                        class="form-check-input">
                                            <label for="private" class="form-check-label">
                                                <i class="ph ph-lock me-2"></i>{{ __('events.mode_private') }}
                                                        <small
                                                            class="d-block text-muted">{{ __('events.private_event_description') }}</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Inviti per eventi privati - spostati al quarto step -->
                        </div>
                    </div>
                </div>

                <!-- Step 2: Date and Location -->
                <div class="card d-none" id="step-2">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph ph-calendar-clock me-2"></i>{{ __('events.date_and_location') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Date and Time -->
                            <div class="col-12 col-md-6 mb-3">
                                <div class="form-floating">
                                            <input type="text" name="start_datetime" id="start_datetime"
                                                class="form-control flatpickr-input"
                                                placeholder="Seleziona data e ora inizio..." required readonly>
                                            <label for="start_datetime">{{ __('events.start_date') }}
                                                {{ __('events.start_time') }} *</label>
                                </div>
                                <div class="error-feedback" id="start_datetime-error"></div>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <div class="form-floating">
                                            <input type="text" name="end_datetime" id="end_datetime"
                                                class="form-control flatpickr-input"
                                                placeholder="Seleziona data e ora fine..." required readonly>
                                            <label for="end_datetime">{{ __('events.end_date') }}
                                                {{ __('events.end_time') }} *</label>
                                </div>
                                <div class="error-feedback" id="end_datetime-error"></div>
                            </div>

                            <!-- Availability-Based Event Option -->
                            <div class="col-12 mb-3">
                                <div class="card border-warning">
                                    <div class="card-header bg-light-warning">
                                        <div class="form-check">
                                                    <input type="checkbox" name="is_availability_based"
                                                        id="is_availability_based" class="form-check-input"
                                                        value="1">
                                            <label for="is_availability_based" class="form-check-label f-w-600">
                                                        <i
                                                            class="ph ph-calendar-check me-2"></i>{{ __('events.availability_based_event') }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="card-body" id="availability-settings" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                            <input type="text" name="availability_deadline"
                                                                id="availability_deadline"
                                                                class="form-control flatpickr-input"
                                                                placeholder="Seleziona scadenza risposte..." readonly>
                                                            <label
                                                                for="availability_deadline">{{ __('events.availability_deadline') }}</label>
                                                </div>
                                            </div>
                                            <div class="col-12 mt-3">
                                                <div class="form-floating">
                                                            <textarea name="availability_instructions" id="availability_instructions" class="form-control" rows="3"
                                                                placeholder="{{ __('events.availability_instructions_placeholder') }}"></textarea>
                                                            <label
                                                                for="availability_instructions">{{ __('events.availability_instructions') }}</label>
                                                </div>
                                                        <small
                                                            class="text-muted">{{ __('events.availability_instructions_help') }}</small>
                                            </div>
                                        </div>

                                        <!-- Sezione Date Multiple per Disponibilità -->
                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <div class="card border-success">
                                                    <div class="card-header bg-light-success">
                                                        <h6 class="mb-0">
                                                                    <i
                                                                        class="ph ph-calendar-plus me-2"></i>{{ __('events.availability_multiple_dates') }}
                                                        </h6>
                                                    </div>
                                                    <div class="card-body">
                                                                <p class="text-muted mb-3">
                                                                    {{ __('events.availability_multiple_dates_help') }}</p>

                                                        <!-- Lista opzioni di date -->
                                                        <div id="availability-options-list">
                                                            <!-- Le opzioni verranno aggiunte qui dinamicamente -->
                                                        </div>

                                                        <!-- Pulsante per aggiungere nuova data -->
                                                        <div class="text-center mt-3">
                                                                    <button type="button" class="btn btn-outline-success"
                                                                        id="add-availability-option">
                                                                        <i
                                                                            class="ph ph-plus me-2"></i>{{ __('events.add_availability_option') }}
                                                            </button>
                                                        </div>

                                                        <div class="alert alert-info mt-3">
                                                            <i class="ph ph-info me-2"></i>
                                                            <strong>{{ __('events.availability_multiple_dates_notice') }}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Online Event Option -->
                            <div class="col-12 mb-3">
                                <div class="card border-info">
                                    <div class="card-header bg-light-info">
                                        <div class="form-check">
                                                    <input type="checkbox" name="is_online" id="is_online"
                                                        class="form-check-input" value="1">
                                            <label for="is_online" class="form-check-label f-w-600">
                                                <i class="ph ph-globe me-2"></i>{{ __('events.online_event') }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="card-body" id="online-event-settings" style="display: none;">
                                        <div class="row">
                                            <!-- Timezone -->
                                            <div class="col-md-6 mb-3">
                                                <div class="form-floating">
                                                    <select name="timezone" id="timezone" class="form-select">
                                                                <option value="">{{ __('events.select_timezone') }}
                                                                </option>
                                                                <option value="Europe/Rome" selected>Europe/Rome (UTC+1/+2)
                                                                </option>
                                                                <option value="Europe/London">Europe/London (UTC+0/+1)
                                                                </option>
                                                                <option value="Europe/Paris">Europe/Paris (UTC+1/+2)
                                                                </option>
                                                                <option value="Europe/Berlin">Europe/Berlin (UTC+1/+2)
                                                                </option>
                                                                <option value="Europe/Madrid">Europe/Madrid (UTC+1/+2)
                                                                </option>
                                                                <option value="Europe/Amsterdam">Europe/Amsterdam
                                                                    (UTC+1/+2)</option>
                                                                <option value="Europe/Brussels">Europe/Brussels (UTC+1/+2)
                                                                </option>
                                                                <option value="Europe/Vienna">Europe/Vienna (UTC+1/+2)
                                                                </option>
                                                                <option value="Europe/Zurich">Europe/Zurich (UTC+1/+2)
                                                                </option>
                                                                <option value="Europe/Stockholm">Europe/Stockholm
                                                                    (UTC+1/+2)</option>
                                                        <option value="Europe/Oslo">Europe/Oslo (UTC+1/+2)</option>
                                                                <option value="Europe/Copenhagen">Europe/Copenhagen
                                                                    (UTC+1/+2)</option>
                                                                <option value="Europe/Helsinki">Europe/Helsinki (UTC+2/+3)
                                                                </option>
                                                                <option value="Europe/Warsaw">Europe/Warsaw (UTC+1/+2)
                                                                </option>
                                                                <option value="Europe/Prague">Europe/Prague (UTC+1/+2)
                                                                </option>
                                                                <option value="Europe/Budapest">Europe/Budapest (UTC+1/+2)
                                                                </option>
                                                                <option value="Europe/Bucharest">Europe/Bucharest
                                                                    (UTC+2/+3)</option>
                                                                <option value="Europe/Sofia">Europe/Sofia (UTC+2/+3)
                                                                </option>
                                                                <option value="Europe/Zagreb">Europe/Zagreb (UTC+1/+2)
                                                                </option>
                                                                <option value="Europe/Ljubljana">Europe/Ljubljana
                                                                    (UTC+1/+2)</option>
                                                                <option value="Europe/Athens">Europe/Athens (UTC+2/+3)
                                                                </option>
                                                                <option value="Europe/Nicosia">Europe/Nicosia (UTC+2/+3)
                                                                </option>
                                                                <option value="Europe/Valletta">Europe/Valletta (UTC+1/+2)
                                                                </option>
                                                                <option value="America/New_York">America/New_York
                                                                    (UTC-5/-4)</option>
                                                                <option value="America/Chicago">America/Chicago (UTC-6/-5)
                                                                </option>
                                                                <option value="America/Denver">America/Denver (UTC-7/-6)
                                                                </option>
                                                                <option value="America/Los_Angeles">America/Los_Angeles
                                                                    (UTC-8/-7)</option>
                                                                <option value="America/Toronto">America/Toronto (UTC-5/-4)
                                                                </option>
                                                                <option value="America/Vancouver">America/Vancouver
                                                                    (UTC-8/-7)</option>
                                                                <option value="America/Mexico_City">America/Mexico_City
                                                                    (UTC-6/-5)</option>
                                                                <option value="America/Sao_Paulo">America/Sao_Paulo
                                                                    (UTC-3/-2)</option>
                                                                <option value="America/Buenos_Aires">America/Buenos_Aires
                                                                    (UTC-3)</option>
                                                                <option value="America/Santiago">America/Santiago
                                                                    (UTC-3/-4)</option>
                                                                <option value="Australia/Sydney">Australia/Sydney
                                                                    (UTC+10/+11)</option>
                                                                <option value="Australia/Melbourne">Australia/Melbourne
                                                                    (UTC+10/+11)</option>
                                                                <option value="Australia/Perth">Australia/Perth (UTC+8)
                                                                </option>
                                                                <option value="Pacific/Auckland">Pacific/Auckland
                                                                    (UTC+12/+13)</option>
                                                        <option value="Asia/Tokyo">Asia/Tokyo (UTC+9)</option>
                                                        <option value="Asia/Seoul">Asia/Seoul (UTC+9)</option>
                                                                <option value="Asia/Shanghai">Asia/Shanghai (UTC+8)
                                                                </option>
                                                                <option value="Asia/Singapore">Asia/Singapore (UTC+8)
                                                                </option>
                                                                <option value="Asia/Kuala_Lumpur">Asia/Kuala_Lumpur (UTC+8)
                                                                </option>
                                                        <option value="Asia/Jakarta">Asia/Jakarta (UTC+7)</option>
                                                        <option value="Asia/Manila">Asia/Manila (UTC+8)</option>
                                                        <option value="Asia/Bangkok">Asia/Bangkok (UTC+7)</option>
                                                                <option value="Asia/Ho_Chi_Minh">Asia/Ho_Chi_Minh (UTC+7)
                                                                </option>
                                                        <option value="Asia/Dubai">Asia/Dubai (UTC+4)</option>
                                                        <option value="Asia/Riyadh">Asia/Riyadh (UTC+3)</option>
                                                                <option value="Asia/Kolkata">Asia/Kolkata (UTC+5:30)
                                                                </option>
                                                        <option value="Asia/Bangkok">Asia/Bangkok (UTC+7)</option>
                                                                <option value="Asia/Ho_Chi_Minh">Asia/Ho_Chi_Minh (UTC+7)
                                                                </option>
                                                        <option value="Asia/Dubai">Asia/Dubai (UTC+4)</option>
                                                        <option value="Asia/Riyadh">Asia/Riyadh (UTC+3)</option>
                                                                <option value="Asia/Kolkata">Asia/Kolkata (UTC+5:30)
                                                                </option>
                                                                <option value="Asia/Tehran">Asia/Tehran (UTC+3:30/+4:30)
                                                                </option>
                                                                <option value="Asia/Jerusalem">Asia/Jerusalem (UTC+2/+3)
                                                                </option>
                                                                <option value="Africa/Cairo">Africa/Cairo (UTC+2/+3)
                                                                </option>
                                                                <option value="Africa/Johannesburg">Africa/Johannesburg
                                                                    (UTC+2)</option>
                                                        <option value="Africa/Lagos">Africa/Lagos (UTC+1)</option>
                                                                <option value="Africa/Nairobi">Africa/Nairobi (UTC+3)
                                                                </option>
                                                                <option value="Africa/Casablanca">Africa/Casablanca
                                                                    (UTC+0/+1)</option>
                                                                <option value="Africa/Tunis">Africa/Tunis (UTC+1/+2)
                                                                </option>
                                                                <option value="Africa/Algiers">Africa/Algiers (UTC+1)
                                                                </option>
                                                    </select>
                                                    <label for="timezone">{{ __('events.timezone') }} *</label>
                                                </div>
                                                <small class="text-muted">{{ __('events.timezone_help') }}</small>
                                            </div>

                                            <!-- Online URL -->
                                            <div class="col-md-6 mb-3">
                                                <div class="form-floating">
                                                            <input type="url" name="online_url" id="online_url"
                                                                class="form-control"
                                                                placeholder="{{ __('events.online_url_placeholder') }}">
                                                    <label for="online_url">{{ __('events.online_url') }}</label>
                                                </div>
                                                        <small
                                                            class="text-muted">{{ __('events.online_url_help') }}</small>
                                            </div>
                                        </div>

                                        <!-- Online Event Notice -->
                                        <div class="alert alert-info">
                                            <i class="ph ph-info me-2"></i>
                                            <strong>{{ __('events.online_event_notice') }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

 <!-- Recent Venues Dropdown -->
                                    @if ($recentVenues->count() > 0)
 <div class="col-12 mb-3" id="recent-venues-section">
     <label class="form-label">
         <i class="ph ph-trend-up me-2"></i>{{ __('events.recent_venues') }}
     </label>
     <select name="recent_venue" id="recent_venue" class="form-select">
         <option value="">{{ __('events.select_recent_venue') }}</option>
                                                @foreach ($recentVenues as $venue)
                                                    <option value="{{ $loop->index }}"
                                                        data-venue="{{ json_encode($venue) }}">
                                                        {{ $venue->venue_name }} - {{ $venue->venue_address }},
                                                        {{ $venue->city }}
                                                        ({{ $venue->total_usage }} volte, {{ $venue->unique_users }}
                                                        utenti)
             </option>
         @endforeach
     </select>
     <small class="text-muted">{{ __('events.recent_venues_help') }}</small>
 </div>
 @endif
                            <!-- Location -->
                            <div class="col-12 mb-3" id="venue-name-container">
                                <div class="form-floating position-relative">
                                            <input type="text" name="venue_name" id="venue_name" class="form-control"
                                                placeholder=" " required autocomplete="new-password" data-lpignore="true"
                                                data-form-type="other" spellcheck="false">
                                    <label for="venue_name">{{ __('events.venue_name') }} *</label>

                                    <!-- Autocomplete Dropdown -->
                                            <div id="venue-autocomplete" class="venue-autocomplete-dropdown"
                                                style="display: none;">
                                        <div class="autocomplete-header">
                                            <small class="text-muted">
                                                        <i
                                                            class="ph ph-lightbulb me-1"></i>{{ __('events.venue_suggestions') }}
                                            </small>
                                        </div>
                                        <div id="venue-suggestions" class="autocomplete-suggestions">
                                            <!-- Suggestions will be populated here -->
                                        </div>
                                    </div>
                                </div>
                                <div class="error-feedback" id="venue_name-error"></div>
                            </div>



                            <div class="col-12 col-md-6 mb-3" id="venue-address-container">
                                <div class="form-floating">
                                            <input type="text" name="venue_address" id="venue_address"
                                                class="form-control"
                                                placeholder="{{ __('events.venue_address_placeholder') }}" required>
                                    <label for="venue_address">{{ __('events.venue_address') }} *</label>
                                </div>
                                <div class="error-feedback" id="venue_address-error"></div>
                            </div>

                            <div class="col-12 col-md-3 mb-3" id="city-container">
                                <div class="form-floating">
                                            <input type="text" name="city" id="city" class="form-control"
                                                placeholder="{{ __('events.city_placeholder') }}" required>
                                    <label for="city">{{ __('events.city') }} *</label>
                                </div>
                                <div class="error-feedback" id="city-error"></div>
                            </div>

                            <div class="col-12 col-md-3 mb-3" id="postcode-container">
                                <div class="form-floating">
                                            <input type="text" name="postcode" id="postcode" class="form-control"
                                                placeholder="{{ __('events.postcode_placeholder') }}" required>
                                    <label for="postcode">{{ __('events.postcode') }} *</label>
                                </div>
                                <div class="error-feedback" id="postcode-error"></div>
                            </div>

                            <div class="col-12 col-md-6 mb-3" id="country-container">
                                <div class="form-floating">
                                    <select name="country" id="country" class="form-select" required>
                                        <option value="">{{ __('events.select_country') }}...</option>
                                        <option value="IT" selected>{{ __('events.italy') }}</option>
                                        <option value="FR">{{ __('events.france') }}</option>
                                        <option value="ES">{{ __('events.spain') }}</option>
                                        <option value="DE">{{ __('events.germany') }}</option>
                                        <option value="CH">{{ __('events.switzerland') }}</option>
                                        <option value="AT">{{ __('events.austria') }}</option>
                                        <option value="BE">{{ __('events.belgium') }}</option>
                                        <option value="NL">{{ __('events.netherlands') }}</option>
                                        <option value="PT">{{ __('events.portugal') }}</option>
                                        <option value="GB">{{ __('events.united_kingdom') }}</option>
                                        <option value="IE">{{ __('events.ireland') }}</option>
                                        <option value="SE">{{ __('events.sweden') }}</option>
                                        <option value="NO">{{ __('events.norway') }}</option>
                                        <option value="DK">{{ __('events.denmark') }}</option>
                                        <option value="FI">{{ __('events.finland') }}</option>
                                        <option value="PL">{{ __('events.poland') }}</option>
                                        <option value="CZ">{{ __('events.czech_republic') }}</option>
                                        <option value="SK">{{ __('events.slovakia') }}</option>
                                        <option value="HU">{{ __('events.hungary') }}</option>
                                        <option value="RO">{{ __('events.romania') }}</option>
                                        <option value="BG">{{ __('events.bulgaria') }}</option>
                                            <option value="HR">{{ __('events.croatia') }}</option>
                                        <option value="SI">{{ __('events.slovenia') }}</option>
                                        <option value="GR">{{ __('events.greece') }}</option>
                                        <option value="CY">{{ __('events.cyprus') }}</option>
                                        <option value="MT">{{ __('events.malta') }}</option>
                                        <option value="US">{{ __('events.united_states') }}</option>
                                        <option value="CA">{{ __('events.canada') }}</option>
                                        <option value="MX">{{ __('events.mexico') }}</option>
                                        <option value="BR">{{ __('events.brazil') }}</option>
                                        <option value="AR">{{ __('events.argentina') }}</option>
                                        <option value="CL">{{ __('events.chile') }}</option>
                                        <option value="AU">{{ __('events.australia') }}</option>
                                        <option value="NZ">{{ __('events.new_zealand') }}</option>
                                        <option value="JP">{{ __('events.japan') }}</option>
                                        <option value="KR">{{ __('events.south_korea') }}</option>
                                        <option value="CN">{{ __('events.china') }}</option>
                                        <option value="IN">{{ __('events.india') }}</option>
                                        <option value="TH">{{ __('events.thailand') }}</option>
                                        <option value="SG">{{ __('events.singapore') }}</option>
                                        <option value="MY">{{ __('events.malaysia') }}</option>
                                        <option value="ID">{{ __('events.indonesia') }}</option>
                                        <option value="PH">{{ __('events.philippines') }}</option>
                                        <option value="VN">{{ __('events.vietnam') }}</option>
                                        <option value="RU">{{ __('events.russia') }}</option>
                                                <option value="UA">{{ __('events.ukraine') }}</option>
                                        <option value="BY">{{ __('events.belarus') }}</option>
                                        <option value="TR">{{ __('events.turkey') }}</option>
                                        <option value="IL">{{ __('events.israel') }}</option>
                                        <option value="AE">{{ __('events.united_arab_emirates') }}</option>
                                        <option value="SA">{{ __('events.saudi_arabia') }}</option>
                                        <option value="EG">{{ __('events.egypt') }}</option>
                                        <option value="ZA">{{ __('events.south_africa') }}</option>
                                        <option value="NG">{{ __('events.nigeria') }}</option>
                                        <option value="KE">{{ __('events.kenya') }}</option>
                                        <option value="MA">{{ __('events.morocco') }}</option>
                                        <option value="TN">{{ __('events.tunisia') }}</option>
                                        <option value="DZ">{{ __('events.algeria') }}</option>
                                    </select>
                                    <label for="country">{{ __('events.country') }} *</label>
                                </div>
                            </div>


                            <div class="col-12 mb-3" id="map-info-banner-container">
                                <div class="alert alert-info" id="map-info-banner">
                                    <i class="ph ph-info me-2"></i>
                                            <strong>{{ __('events.auto_positioning_title') }}:</strong>
                                            {{ __('events.auto_positioning_description') }}
                                </div>
                            </div>

                            <!-- Hidden coordinates -->
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">

                            <!-- Map -->
                            <div class="col-12 mb-3">
                                <label class="form-label">{{ __('events.map_location') }}</label>
                                <div id="locationMap" class="border rounded" style="height: 300px;"></div>
                                <small class="text-muted">{{ __('events.map_auto_positioning_help') }}</small>
                                <div id="geocoding-status" class="small text-info mt-1" style="display: none;">
                                            <i class="ph ph-spinner-gap me-1"></i>
                                            {{ __('events.auto_positioning_status') }}
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <!-- Step 3: Details -->
                <div class="card d-none" id="step-3">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph ph-gear me-2"></i>{{ __('events.step_event_details') }}
                        </h5>
                                <p class="text-muted mb-0">{{ __('events.step_details_description') }}</p>
                    </div>
                    <div class="card-body">
                                <div class="row g-3">
                                    <!-- Card Media -->
                                    <div class="col-md-6">
                                        <div class="card border-primary">
                                            <div class="card-header bg-light-primary py-2">
                                                <h6 class="mb-0 text-primary">
                                                    <i class="ph ph-image me-2"></i>Media e Contenuti
                                                </h6>
                                </div>
                                            <div class="card-body p-3">
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('events.event_image') }}
                                                        ({{ __('common.optional') }})</label>
                                                    <input type="file" name="event_image" id="event_image"
                                                        class="form-control" accept="image/*">
                            </div>
                                                <div class="mb-0">
                                                    <label class="form-label">{{ __('events.promotional_video') }}
                                                        ({{ __('common.optional') }})</label>
                                                    <input type="url" name="promotional_video" id="promotional_video"
                                                        class="form-control"
                                                        placeholder="{{ __('events.promotional_video_placeholder') }}">
                                </div>
                                    </div>
                                </div>
                            </div>

                                    <!-- Card Pagamento -->
                                    <div class="col-md-6">
                                        <div class="card border-success">
                                            <div class="card-header bg-light-success py-2">
                                                <h6 class="mb-0 text-success">
                                                    <i class="ph ph-currency-circle-dollar me-2"></i>Pagamento
                                                </h6>
                                            </div>
                                            <div class="card-body p-3">
                                <div class="form-check mb-3">
                                                    <input type="checkbox" name="is_paid_event" id="is_paid_event"
                                                        class="form-check-input" value="1">
                                    <label for="is_paid_event" class="form-check-label">
                                                        {{ __('events.is_paid_event') }}
                                    </label>
                                </div>
                                <div id="paymentFields" style="display: none;">
                                                    <div class="row g-2">
                                                        <div class="col-6">
                                                            <input type="number" name="ticket_price" id="ticket_price"
                                                                class="form-control" min="0" step="0.01"
                                                                placeholder="Prezzo">
                                            </div>
                                                        <div class="col-6">
                                                            <select name="ticket_currency" id="ticket_currency"
                                                                class="form-select">
                                                    <option value="EUR">EUR (€)</option>
                                                    <option value="USD">USD ($)</option>
                                                    <option value="GBP">GBP (£)</option>
                                                    <option value="CHF">CHF (CHF)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                            </div>
                                </div>
                            </div>

                                    <!-- Card Gruppi -->
                                    <div class="col-md-6">
                                        <div class="card border-info">
                                            <div class="card-header bg-light-info py-2">
                                                <h6 class="mb-0 text-info">
                                                    <i class="ph ph-users me-2"></i>Associazioni
                                                </h6>
                                            </div>
                                            <div class="card-body p-3">
                                <div class="form-check mb-3">
                                                    <input type="checkbox" name="is_linked_to_group"
                                                        id="is_linked_to_group" class="form-check-input" value="1">
                                    <label for="is_linked_to_group" class="form-check-label">
                                                        {{ __('events.is_linked_to_group') }}
                                    </label>
                                </div>
                                <div id="groupFields" style="display: none;">
                                    <!-- Campo di ricerca gruppi -->
                                    <div class="mb-3">
                                                        <label
                                                            class="form-label">{{ __('events.search_groups') }}</label>
                                        <div class="input-group">
                                                            <input type="text" id="groupSearchInput"
                                                                class="form-control"
                                                   placeholder="{{ __('events.search_groups_placeholder') }}"
                                                   onkeydown="handleGroupSearchKeydown(event)">
                                                            <button type="button" class="btn btn-outline-primary"
                                                                onclick="searchGroups()">
                                                <i class="ph ph-magnifying-glass"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Risultati ricerca gruppi -->
                                    <div id="groupSearchResults" class="mb-3" style="display: none;">
                                                        <h6 class="text-muted">{{ __('events.search_results') }}</h6>
                                        <div id="groupSearchResultsList" class="list-group">
                                            <!-- Risultati ricerca qui -->
                                        </div>
                                    </div>

                                    <!-- Lista gruppi selezionati -->
                                    <div class="mb-3">
                                                        <label
                                                            class="form-label">{{ __('events.selected_groups') }}</label>
                                        <div id="selectedGroupsList" class="mb-3">
                                                            <p class="text-muted">{{ __('events.no_groups_selected') }}
                                                            </p>
                                        </div>
                                    </div>

                                    <!-- Lista completa gruppi (nascosta di default) -->
                                    <div class="mb-3">
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-2">
                                                            <label
                                                                class="form-label mb-0">{{ __('events.all_groups') }}</label>
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-secondary"
                                                                onclick="toggleAllGroups()">
                                                <i class="ph ph-list" id="toggleGroupsIcon"></i>
                                                                <span
                                                                    id="toggleGroupsText">{{ __('events.show_all_groups') }}</span>
                                            </button>
                                        </div>
                                        <div id="allGroupsList" style="display: none;">
                                            <div class="row">
                                                                @if (isset($groups) && $groups->count() > 0)
                                                                    @foreach ($groups as $group)
                                                                        <div class="col-md-6 mb-2">
                                                            <div class="form-check">
                                                                                <input type="checkbox" name="group_ids[]"
                                                                       id="group_{{ $group->id }}"
                                                                       value="{{ $group->id }}"
                                                                       class="form-check-input group-checkbox">
                                                                                <label for="group_{{ $group->id }}"
                                                                                    class="form-check-label">
                                                                    <strong>{{ $group->name }}</strong>
                                                                                    @if ($group->description)
                                                                                        <br><small
                                                                                            class="text-muted">{{ Str::limit($group->description, 50) }}</small>
                                                                    @endif
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="col-12">
                                                        <p class="text-muted">Nessun gruppo disponibile</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ __('events.groups_help') }}</small>
                                                    </div>
                                                </div>
                                    </div>
                                </div>
                            </div>

                                    <!-- Card Festival -->
                                    <div class="col-md-6">
                                        <div class="card border-warning">
                                            <div class="card-header bg-light-warning py-2">
                                                <h6 class="mb-0 text-warning">
                                                    <i class="ph ph-trophy me-2"></i>Festival
                                                </h6>
                                            </div>
                                            <div class="card-body p-3">
                                                <div id="festival-management-section">
                                <!-- Sezione per associare a festival esistente (per categorie diverse da "Festival") -->
                                <div id="create-festival-section" style="display: none;">
                                    <div class="form-check mb-3">
                                                            <input type="checkbox" name="is_festival_event"
                                                                id="is_festival_event" class="form-check-input"
                                                                value="1">
                                        <label for="is_festival_event" class="form-check-label">
                                                                {{ __('events.is_festival_event') }}
                                        </label>
                                    </div>
                                    <div id="festivalFields" style="display: none;">
                                                            <select name="festival_id" id="festival_id"
                                                                class="form-select">
                                                                <option value="">{{ __('events.select_festival') }}
                                                                </option>
                                            </select>
                                    </div>
                                </div>

                                <!-- Sezione per selezionare eventi esistenti (quando categoria = "Festival") -->
                                <div id="select-festival-events-section" style="display: none;">
                                                        <h6 class="text-success mb-2">
                                                            <i
                                                                class="ph ph-trophy me-2"></i>{{ __('events.festival_events_selection') }}
                                    </h6>
                                                        <div class="alert alert-border-success alert-sm mb-3"
                                                            role="alert">
                                                            <small>
                                                                <i class="ph ph-info-circle me-1"></i>
                                            {{ __('events.festival_events_selection_help') }}
                                                            </small>
                                    </div>

                                    <!-- Barra di ricerca eventi -->
                                    <div class="mb-3">
                                                            <label
                                                                class="form-label">{{ __('events.select_festival_events') }}</label>
                                        <div class="input-group">
                                                                <input type="text" id="eventSearchInput"
                                                                    class="form-control"
                                                                    placeholder="{{ __('events.search_existing_events') }}"
                                                                    onkeydown="handleEventSearchKeydown(event)">
                                                                <button type="button" class="btn btn-outline-success"
                                                                    onclick="searchEventsForFestival()">
                                                <i class="ph ph-magnifying-glass"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Risultati ricerca eventi -->
                                                        <div id="searchResultsEvents" class="mb-3"
                                                            style="display: none;">
                                                            <h6 class="text-muted">{{ __('events.search_results') }}</h6>
                                        <div id="searchResultsListEvents" class="list-group">
                                            <!-- Risultati qui -->
                                        </div>
                                    </div>

                                    <!-- Eventi selezionati per il festival -->
                                    <div>
                                                            <h6 class="text-success mb-2">
                                                                <i
                                                                    class="ph ph-check-circle me-2"></i>{{ __('events.selected_events') }}
                                                                <span id="selectedEventsCount"
                                                                    class="badge bg-success">0</span>
                                                            </h6>
                                                            <div class="alert alert-info alert-sm mb-3" role="alert">
                                                                <small>
                                                                    <i class="ph ph-info-circle me-1"></i>
                                                                    {{ __('events.festival_events_help') }}
                                                                </small>
                                        </div>
                                        <div id="selectedEventsList" class="row g-2">
                                                                <div class="col-12 text-center text-muted py-3"
                                                                    id="noSelectedEvents">
                                                <i class="ph ph-calendar-plus f-s-24 mb-2"></i>
                                                                    <p class="mb-0">
                                                                        {{ __('events.no_events_selected') }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hidden input per i dati degli eventi selezionati -->
                                                        <input type="hidden" name="selected_festival_events"
                                                            id="selectedFestivalEventsData" value="[]">
                                </div>
                            </div>
                                            </div>
                        </div>
                                    </div>
                                </div>

                    </div>
                </div>

                <!-- Step 4: Inviti e Ingaggi -->
                <div class="card d-none" id="step-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph ph-users me-2"></i>{{ __('events.invites_and_gig') }}
                        </h5>
                    </div>
                    <div class="card-body">
                            <div class="row g-3">
                                <!-- Card Inviti Privati -->
                                <div class="col-md-6" id="private-invites-section" style="display: none;">
                                    <div class="card border-primary">
                                        <div class="card-header bg-light-primary py-2">
                                    <h6 class="mb-0 text-primary">
                                                <i class="ph ph-user-plus me-1"></i>Inviti Privati
                                    </h6>
                                </div>
                                        <div class="card-body p-3">
                                            <h6 class="mb-2 text-primary">
                                                <i class="ph ph-envelope me-1"></i>{{ __('events.private_invites') }}
                                            </h6>
                                    <!-- Barra di ricerca -->
                                    <div class="mb-3">
                                                <div class="input-group input-group-sm">
                                                    <input type="text" id="privateUserSearchInput"
                                                        class="form-control"
                                                        placeholder="{{ __('events.search_users') }}"
                                                        onkeydown="handlePrivateUserSearchKeydown(event)">
                                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                                        onclick="searchPrivateUsersForInvite()">
                                                <i class="ph ph-magnifying-glass"></i>
                                            </button>
                                        </div>
                                                <small
                                                    class="text-muted f-s-11">{{ __('events.search_users_help') }}</small>
                                    </div>

                                    <!-- Risultati ricerca -->
                                    <div id="privateSearchResultsInvite" class="mb-3" style="display: none;">
                                        <h6 class="text-muted fw-bold">{{ __('events.search_results') }}</h6>
                                        <div id="privateSearchResultsListInvite" class="list-group">
                                            <!-- Risultati qui -->
                                        </div>
                                    </div>

                                    <!-- Utenti suggeriti -->
                                    <div class="mb-3">
                                        <h6 class="text-muted fw-bold">{{ __('events.suggested_users') }}</h6>
                                                <p class="text-muted small mb-2">{{ __('events.suggested_users_help') }}
                                                </p>
                                        <div id="privateSuggestedUsersList" class="row g-2">
                                            <!-- Utenti suggeriti qui -->
                                        </div>
                                    </div>

                                    <!-- Utenti invitati -->
                                    <div>
                                        <h6 class="text-muted fw-bold d-flex align-items-center">
                                            {{ __('events.invited_users') }}
                                                    <span id="privateInviteCount"
                                                        class="badge bg-light text-primary border ms-2">0</span>
                                        </h6>
                                        <div id="privateInvitedUsersList" class="row g-2">
                                                    <div class="col-12 text-center text-muted py-4"
                                                        id="noPrivateInvitedUsers">
                                                <i class="ph ph-user-plus f-s-32 mb-3 text-primary opacity-50"></i>
                                                <p class="mb-0">{{ __('events.no_invited_users') }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hidden input per i dati degli inviti -->
                                            <input type="hidden" name="private_invited_users"
                                                id="privateInvitedUsersData" value="[]">
                                </div>
                            </div>
                        </div>

                                <div class="row g-3">

                                    <!-- Card Inviti Artisti -->
                                    <div class="col-md-6">
                                        <div class="card border-success">
                                            <div class="card-header bg-light-success py-2">
                                                <h6 class="mb-0 text-success">
                                        <i class="ph ph-envelope me-2"></i>{{ __('events.invites') }}
                                    </h6>
                                </div>
                                            <div class="card-body p-3">
                                    <!-- Search Users -->
                                    <div class="mb-4  mt-4">

                                        <div class="input-group">
                                                        <input type="text" id="artistUserSearchInput"
                                                            class="form-control"
                                                            placeholder="{{ __('events.search_users') }}"
                                                            onkeydown="handleArtistUserSearchKeydown(event)">

                                                        <button type="button" class="btn btn-outline-primary"
                                                            onclick="searchArtistUsersForInvite()">
                                                <i class="ph ph-magnifying-glass"></i>
                                            </button>

                                        </div>
                                                    <small
                                                        class="text-muted">{{ __('events.search_users_help') }}</small>
                                    </div>

                                    <!-- Search Results -->
                                                <div id="artistSearchResultsInvite" class="mb-3"
                                                    style="display: none;">
                                        <h6 class="text-muted fw-bold">{{ __('events.search_results') }}</h6>
                                        <div id="artistSearchResultsListInvite" class="list-group">
                                            <!-- Risultati qui -->
                                        </div>
                                    </div>

                                    <!-- Utenti suggeriti -->
                                    <div class="mb-3">
                                                    <h6 class="text-muted fw-bold">{{ __('events.suggested_users') }}
                                                    </h6>
                                                    <p class="text-muted small mb-2">
                                                        {{ __('events.suggested_users_help') }}</p>
                                        <div id="artistSuggestedUsersList" class="row g-2">
                                            <!-- Utenti suggeriti qui -->
                                        </div>
                                    </div>

                                    <!-- Utenti invitati -->
                                    <div>
                                        <h6 class="text-muted fw-bold d-flex align-items-center">
                                            {{ __('events.invited_users') }}
                                                        <span id="artistInviteCount"
                                                            class="badge text-primary border ms-2">0</span>
                                        </h6>
                                        <div id="artistInvitedUsersList" class="row g-2">
                                                        <div class="col-12 text-center text-muted py-4"
                                                            id="noArtistInvitedUsers">
                                                            <i
                                                                class="ph ph-user-plus f-s-32 mb-3 text-primary opacity-50"></i>
                                                <p class="mb-0">{{ __('events.no_invited_users') }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hidden input per i dati degli inviti -->
                                                <input type="hidden" name="artist_invited_users"
                                                    id="artistInvitedUsersData" value="[]">
                                </div>
                            </div>
                        </div>

                                    <div class="row g-3">

                                        <!-- Card Ingaggi -->
                                        <div class="col-md-6">
                                            <div class="card border-warning">
                                                <div class="card-header bg-light-warning py-2">
                                                    <h6 class="mb-0 text-warning">
                                        <i class="ph ph-briefcase me-2"></i>{{ __('events.gigs') }}
                                    </h6>
                                </div>
                                                <div class="card-body p-3">
                                    <!-- Container per le posizioni d'ingaggio -->
                                    <div id="gigPositionsContainer">
                                        <!-- Le posizioni verranno aggiunte qui dinamicamente -->
                                    </div>

                                    <!-- Pulsante per aggiungere nuova posizione -->
                                    <div class="text-center mt-3">
                                                        <button type="button" class="btn btn-light-success btn-lg"
                                                            onclick="addGigPosition()">
                                                            <i
                                                                class="ph ph-plus me-2"></i>{{ __('events.add_gig_position') }}
                                        </button>
                                    </div>

                                    <!-- Hidden input for gig positions data -->
                                                    <input type="hidden" name="gig_positions" id="gigPositionsData"
                                                        value="[]">
                                </div>
                            </div>
                        </div>

                                        <div class="row g-3">

                                            <!-- Card Registration Deadline -->
                                            <div class="col-md-6">
                                                <div class="card border-info">
                                                    <div class="card-header bg-light-info py-2">
                                                        <h6 class="mb-0 text-info">
                                                            <i
                                                                class="ph ph-clock me-2"></i>{{ __('events.registration_deadline') }}
                                    </h6>
                                </div>
                                                    <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                <div class="d-flex align-items-start">
                                                    <i class="ph ph-info-circle me-2 mt-1"></i>
                                                    <div>
                                                        <strong>{{ __('events.registration_deadline_info') }}</strong>
                                                                            <p class="mb-0 mt-1">
                                                                                {{ __('events.registration_deadline_help') }}
                                                                            </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check p-3 border rounded h-100">
                                                                    <input type="radio" name="has_registration_deadline"
                                                                        id="no_deadline" value="0"
                                                                        class="form-check-input" checked>
                                                                    <label for="no_deadline"
                                                                        class="form-check-label h-100 d-flex flex-column justify-content-center">
                                                    <div class="d-flex align-items-center mb-2">
                                                                            <i
                                                                                class="ph ph-infinity me-2 text-success"></i>
                                                                            <span
                                                                                class="fw-bold">{{ __('events.no_registration_deadline') }}</span>
                                                    </div>
                                                                        <small
                                                                            class="text-muted">{{ __('events.no_registration_deadline_help') }}</small>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check p-3 border rounded h-100">
                                                                    <input type="radio" name="has_registration_deadline"
                                                                        id="has_deadline" value="1"
                                                                        class="form-check-input">
                                                                    <label for="has_deadline"
                                                                        class="form-check-label h-100 d-flex flex-column justify-content-center">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="ph ph-clock me-2 text-warning"></i>
                                                                            <span
                                                                                class="fw-bold">{{ __('events.set_registration_deadline') }}</span>
                                                    </div>
                                                                        <small
                                                                            class="text-muted">{{ __('events.set_registration_deadline_help') }}</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Date/Time Picker per la scadenza -->
                                                        <div id="registrationDeadlinePicker" class="mt-3"
                                                            style="display: none;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                                    <label
                                                                        class="form-label fw-bold">{{ __('events.registration_deadline_date') }}
                                                                        *</label>
                                                                    <input type="text"
                                                                        name="registration_deadline_date"
                                                                        id="registrationDeadlineDate" class="form-control"
                                                                        placeholder="{{ __('events.select_date') }}">
                                            </div>
                                            <div class="col-md-6">
                                                                    <label
                                                                        class="form-label fw-bold">{{ __('events.registration_deadline_time') }}
                                                                        *</label>
                                                                    <input type="text"
                                                                        name="registration_deadline_time"
                                                                        id="registrationDeadlineTime" class="form-control"
                                                                        placeholder="{{ __('events.select_time') }}">
                                            </div>
                                        </div>
                                        <small class="text-muted mt-2 d-block">
                                                                <i
                                                                    class="ph ph-info-circle me-1"></i>{{ __('events.registration_deadline_note') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                                            <!-- Card Event Status -->
                                            <div class="col-md-6">
                                                <div class="card border-secondary">
                                                    <div class="card-header bg-light-secondary py-2">
                                                        <h6 class="mb-0 text-secondary">
                                                            <i
                                                                class="ph ph-globe me-2"></i>{{ __('events.event_status') }}
                                    </h6>
                                </div>
                                                    <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-md-6 mt-4">
                                            <div class="form-check p-3 border rounded h-100">
                                                                    <input type="radio" name="status" id="published"
                                                                        value="published" class="form-check-input"
                                                                        checked>
                                                                    <label for="published"
                                                                        class="form-check-label h-100 d-flex flex-column justify-content-center">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="ph ph-globe me-2 text-success"></i>
                                                                            <span
                                                                                class="fw-bold">{{ __('events.publish_immediately') }}</span>
                                                    </div>
                                                                        <small
                                                                            class="text-muted">{{ __('events.publish_immediately_help') }}</small>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-4">
                                            <div class="form-check p-3 border rounded h-100">
                                                                    <input type="radio" name="status" id="draft"
                                                                        value="draft" class="form-check-input">
                                                                    <label for="draft"
                                                                        class="form-check-label h-100 d-flex flex-column justify-content-center">
                                                    <div class="d-flex align-items-center mb-2">
                                                                            <i
                                                                                class="ph ph-note-pencil me-2 text-warning"></i>
                                                                            <span
                                                                                class="fw-bold">{{ __('events.save_as_draft') }}</span>
                                                    </div>
                                                                        <small
                                                                            class="text-muted">{{ __('events.save_as_draft_help') }}</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Role Selection Modal -->
                <div class="modal fade" id="roleSelectionModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-white border-bottom">
                                <h5 class="modal-title mb-0 text-primary">
                                                        <i
                                                            class="ph ph-user-circle-plus me-2"></i>{{ __('events.invite_artist') }}
                                </h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <!-- User Info Card -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-body text-center">
                                                            <div
                                                                class="avatar avatar-lg mx-auto mb-3 bg-primary text-white d-flex align-items-center justify-content-center f-s-24 fw-bold">
                                            <span id="selectedUserInitials"></span>
                                        </div>
                                        <h5 id="selectedUserName" class="mb-1"></h5>
                                        <small class="text-muted" id="selectedUserEmail"></small>
                                    </div>
                                </div>

                                <!-- Role Selection -->
                                <div class="mb-4">
                                                        <label class="form-label fw-bold">{{ __('events.select_role') }}
                                                            *</label>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                                                <div
                                                                    class="form-check p-3 border rounded h-100 hover-effect">
                                                                    <input type="radio" name="invitationRole"
                                                                        value="performer" checked
                                                                        class="form-check-input">
                                                                    <label
                                                                        class="form-check-label h-100 d-flex flex-column justify-content-center">
                                                    <div class="d-flex align-items-center mb-2">
                                                                            <i
                                                                                class="ph ph-microphone me-2 text-primary f-s-20"></i>
                                                                            <span
                                                                                class="fw-bold">{{ __('events.performer') }}</span>
                                                    </div>
                                                                        <small
                                                                            class="text-muted">{{ __('events.performer_help') }}</small>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                                                <div
                                                                    class="form-check p-3 border rounded h-100 hover-effect">
                                                                    <input type="radio" name="invitationRole"
                                                                        value="judge" class="form-check-input">
                                                                    <label
                                                                        class="form-check-label h-100 d-flex flex-column justify-content-center">
                                                    <div class="d-flex align-items-center mb-2">
                                                                            <i
                                                                                class="ph ph-scales me-2 text-warning f-s-20"></i>
                                                                            <span
                                                                                class="fw-bold">{{ __('events.judge') }}</span>
                                                    </div>
                                                                        <small
                                                                            class="text-muted">{{ __('events.judge_help') }}</small>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                                                <div
                                                                    class="form-check p-3 border rounded h-100 hover-effect">
                                                                    <input type="radio" name="invitationRole"
                                                                        value="technician" class="form-check-input">
                                                                    <label
                                                                        class="form-check-label h-100 d-flex flex-column justify-content-center">
                                                    <div class="d-flex align-items-center mb-2">
                                                                            <i
                                                                                class="ph ph-gear me-2 text-info f-s-20"></i>
                                                                            <span
                                                                                class="fw-bold">{{ __('events.technician') }}</span>
                                                    </div>
                                                                        <small
                                                                            class="text-muted">{{ __('events.technician_help') }}</small>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                                                <div
                                                                    class="form-check p-3 border rounded h-100 hover-effect">
                                                                    <input type="radio" name="invitationRole"
                                                                        value="host" class="form-check-input">
                                                                    <label
                                                                        class="form-check-label h-100 d-flex flex-column justify-content-center">
                                                    <div class="d-flex align-items-center mb-2">
                                                                            <i
                                                                                class="ph ph-user-focus me-2 text-success f-s-20"></i>
                                                                            <span
                                                                                class="fw-bold">{{ __('events.host') }}</span>
                                                    </div>
                                                                        <small
                                                                            class="text-muted">{{ __('events.host_help') }}</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Custom Message -->
                                <div class="mb-3">
                                                        <label
                                                            class="form-label fw-bold">{{ __('events.custom_message') }}
                                                            <span
                                                                class="text-muted">({{ __('common.optional') }})</span></label>
                                                        <textarea id="invitationMessage" class="form-control" rows="4"
                                                            placeholder="{{ __('events.custom_message_placeholder') }}"></textarea>
                                                        <small
                                                            class="text-muted">{{ __('events.custom_message_help') }}</small>
                                </div>
                            </div>
                            <div class="modal-footer">
                                                    <button type="button" class="btn btn-light-secondary"
                                                        data-bs-dismiss="modal">
                                    <i class="ph ph-x me-2"></i>{{ __('common.cancel') }}
                                </button>
                                                    <button type="button" class="btn btn-primary btn-lg"
                                                        onclick="confirmInvitation()">
                                                        <i
                                                            class="ph ph-paper-plane me-2"></i>{{ __('events.send_invitation') }}
                                </button>
                            </div>
                        </div>
                    </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 5: Preview -->
                <div class="card d-none" id="step-5">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph ph-eye me-2"></i>{{ __('events.event_preview') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="eventPreview" class="preview-card">
                            <!-- Dynamic preview will be generated here -->
                        </div>

                        <div class="text-center mt-4">
                                                <button type="submit" class="btn btn-light-primary btn-lg w-100"
                                                    id="submitBtn">
                                                    <i
                                                        class="ph ph-check-circle me-2"></i>{{ __('events.create_event') }}
                            </button>
                            <div class="mt-2" id="submitStatus" style="display: none;">
                                <small class="text-muted">
                                                        <i
                                                            class="ph ph-spinner-gap me-1"></i>{{ __('events.creation_in_progress') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                </form>
            </div>
            <!-- Sidebar -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="ph ph-navigation-arrow me-2"></i>{{ __('events.navigation') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- Step Navigation -->
                        <div class="d-grid gap-2 d-md-flex justify-content-between mb-4">
                            <button type="button" class="btn btn-light-secondary flex-fill" id="prevStep" disabled>
                                <i class="ph ph-arrow-left me-1"></i>{{ __('events.previous_step') }}
                            </button>
                            <button type="button" class="btn btn-light-primary flex-fill" id="nextStep">
                                {{ __('events.next_step') }}<i class="ph ph-arrow-right ms-1"></i>
                            </button>
                        </div>

                        <!-- Progress -->
                        <div class="mb-3">
                            <label class="form-label">{{ __('events.progress') }}</label>
                            <div class="progress">
                                <div class="progress-bar bg-primary" id="progressBar" style="width: 25%"></div>
                            </div>
                            <small class="text-muted">{{ __('events.step_progress') }} <span id="currentStep">1</span>
                                {{ __('events.of') }} 5</small>
                        </div>

                        <!-- Quick Tips -->
                        <div class="alert alert-light-info" role="alert">
                            <h6 class="text-info">
                                <i class="ph ph-lightbulb me-2"></i>{{ __('events.tip') }}
                            </h6>
                            <p class="mb-0 small text-info" id="stepTip">
                                {{ __('events.step_tip') }}
                            </p>
                        </div>

                        <!-- Auto-save Status -->
                        <div class="text-center">
                            <small class="text-muted">
                                <i class="ph ph-floppy-disk me-1"></i>
                                <span id="autosaveStatus">{{ __('events.autosave_status') }}</span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>


@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/leafletmaps/leaflet.js') }}"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
// Test di base per verificare se il JavaScript si carica


let currentStep = 1;
let map = null;
let marker = null;
let tags = [];
let selectedInvitations = [];

// Venue Autocomplete Variables
let venueAutocompleteTimeout = null;
let selectedSuggestionIndex = -1;

const stepTips = {
    1: "{{ __('events.step_tip_1') }}",
    2: "{{ __('events.step_tip_2') }}",
    3: "{{ __('events.step_tip_3') }}",
    4: "{{ __('events.step_tip_4') }}",
    5: "{{ __('events.step_tip_5') }}"
};

// Global error handler
window.addEventListener('error', function(e) {
    console.error('Global JavaScript error:', e.error);
    console.error('Error details:', {
        message: e.message,
        filename: e.filename,
        lineno: e.lineno,
        colno: e.colno,
        stack: e.error ? e.error.stack : 'No stack available'
    });
});

document.addEventListener('DOMContentLoaded', function() {


    // Aspetta un momento per essere sicuri che tutto il DOM sia pronto
    setTimeout(() => {
        try {

            initializeForm();

            setupEventListeners();

            // ========================================
            // INIZIALIZZAZIONE EVENTI RICORRENTI
            // ========================================


            const isRecurringCheckbox = document.getElementById('is_recurring');
            const recurrenceSettings = document.getElementById('recurrence-settings');




            if (isRecurringCheckbox && recurrenceSettings) {

                isRecurringCheckbox.addEventListener('change', function() {

                    if (this.checked) {
                        recurrenceSettings.style.display = 'block';
                        updateRecurrencePreview();
                    } else {
                        recurrenceSettings.style.display = 'none';
                    }
                });

                // Gestione tipo di ricorrenza
                const recurrenceType = document.getElementById('recurrence_type');
                const weekdaysSelection = document.getElementById('weekdays-selection');
                const monthdaySelection = document.getElementById('monthday-selection');

                if (recurrenceType) {
                    recurrenceType.addEventListener('change', function() {
                        const type = this.value;

                        // Nascondi tutte le sezioni specifiche
                        if (weekdaysSelection) weekdaysSelection.style.display = 'none';
                        if (monthdaySelection) monthdaySelection.style.display = 'none';

                        // Mostra la sezione appropriata
                        if (type === 'weekly' && weekdaysSelection) {
                            weekdaysSelection.style.display = 'block';
                        } else if (type === 'monthly' && monthdaySelection) {
                            monthdaySelection.style.display = 'block';
                        }

                        updateRecurrencePreview();
                    });
                }

                // Aggiorna anteprima quando cambiano i valori
                const recurrenceInterval = document.getElementById('recurrence_interval');
                const recurrenceCount = document.getElementById('recurrence_count');
                const recurrenceMonthday = document.getElementById('recurrence_monthday');

                [recurrenceInterval, recurrenceCount, recurrenceMonthday].forEach(element => {
                    if (element) {
                        element.addEventListener('input', updateRecurrencePreview);
                    }
                });

                // Aggiorna anteprima quando cambiano i giorni della settimana
                        const weekdayCheckboxes = document.querySelectorAll(
                            'input[name="recurrence_weekdays[]"]');
                weekdayCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', updateRecurrencePreview);
                });
            } else {
                // Gli elementi di ricorrenza potrebbero non esistere in tutte le pagine
                // Non è un errore critico, quindi rimuoviamo il console.error
                // console.error('Recurrence elements not found!');
            }

            // ========================================
            // GESTIONE AVAILABILITY OPTIONS
            // ========================================

            const availabilityBasedCheckbox = document.getElementById('is_availability_based');
            const availabilityOptionsContainer = document.getElementById('availability-settings');
            const addAvailabilityOptionBtn = document.getElementById('add-availability-option');
            const availabilityOptionsList = document.getElementById('availability-options-list');

                    if (availabilityBasedCheckbox && availabilityOptionsContainer &&
                        addAvailabilityOptionBtn && availabilityOptionsList) {
                // Aggiungi nuova opzione
                addAvailabilityOptionBtn.addEventListener('click', function() {
                    addAvailabilityOption();
                });
            }

            // ========================================
            // GESTIONE DATE MULTIPLE PER DISPONIBILITÀ
            // ========================================

                    const availabilityRecurrenceType = document.getElementById(
                        'availability_recurrence_type');
                    const availabilityWeekdaysSelection = document.getElementById(
                        'availability-weekdays-selection');
                    const availabilityMonthdaySelection = document.getElementById(
                        'availability-monthday-selection');

            if (availabilityRecurrenceType) {
                availabilityRecurrenceType.addEventListener('change', function() {
                    const type = this.value;

                    // Nascondi tutte le sezioni specifiche
                            if (availabilityWeekdaysSelection) availabilityWeekdaysSelection.style
                                .display = 'none';
                            if (availabilityMonthdaySelection) availabilityMonthdaySelection.style
                                .display = 'none';

                    // Mostra la sezione appropriata
                    if (type === 'weekly' && availabilityWeekdaysSelection) {
                        availabilityWeekdaysSelection.style.display = 'block';
                    } else if (type === 'monthly' && availabilityMonthdaySelection) {
                        availabilityMonthdaySelection.style.display = 'block';
                    }

                    updateAvailabilityRecurrencePreview();
                });
            }

            // Aggiorna anteprima quando cambiano i valori per disponibilità
                    const availabilityRecurrenceInterval = document.getElementById(
                        'availability_recurrence_interval');
                    const availabilityRecurrenceCount = document.getElementById(
                        'availability_recurrence_count');
                    const availabilityRecurrenceMonthday = document.getElementById(
                        'availability_recurrence_monthday');

                    [availabilityRecurrenceInterval, availabilityRecurrenceCount,
                        availabilityRecurrenceMonthday
                    ].forEach(element => {
                if (element) {
                    element.addEventListener('input', updateAvailabilityRecurrencePreview);
                }
            });

            // Aggiorna anteprima quando cambiano i giorni della settimana per disponibilità
                    const availabilityWeekdayCheckboxes = document.querySelectorAll(
                        'input[name="availability_recurrence_weekdays[]"]');
            availabilityWeekdayCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateAvailabilityRecurrencePreview);
            });

            // ========================================
            // INIZIALIZZAZIONE EVENTI ONLINE
            // ========================================


            const isOnlineCheckbox = document.getElementById('is_online');
            const onlineEventSettings = document.getElementById('online-event-settings');




            if (isOnlineCheckbox && onlineEventSettings) {


                // Controlla lo stato iniziale della checkbox

                if (isOnlineCheckbox.checked) {

                    onlineEventSettings.style.display = 'block';
                    makeLocationFieldsOptional();
                } else {

                    onlineEventSettings.style.display = 'none';
                    makeLocationFieldsRequired();
                }

                isOnlineCheckbox.addEventListener('change', function() {

                    if (this.checked) {

                        onlineEventSettings.style.display = 'block';
                        // Rendi i campi del luogo opzionali per eventi online
                        makeLocationFieldsOptional();
                    } else {

                        onlineEventSettings.style.display = 'none';
                        // Rendi i campi del luogo obbligatori per eventi fisici
                        makeLocationFieldsRequired();
                    }
                });
            } else {
                // Gli elementi di eventi online potrebbero non esistere in tutte le pagine
                // Non è un errore critico, quindi rimuoviamo il console.error
                // console.error('Online event elements not found!');
            }

            // ========================================
            // GESTIONE EVENTI BASATI SU DISPONIBILITÀ
            // ========================================

            const isAvailabilityBasedCheckbox = document.getElementById('is_availability_based');
            const availabilitySettings = document.getElementById('availability-settings');

            if (isAvailabilityBasedCheckbox && availabilitySettings) {
                // Controlla lo stato iniziale della checkbox
                if (isAvailabilityBasedCheckbox.checked) {
                    availabilitySettings.style.display = 'block';
                } else {
                    availabilitySettings.style.display = 'none';
                }

                isAvailabilityBasedCheckbox.addEventListener('change', function() {
                    console.log('Availability checkbox changed, checked:', this.checked);
                    if (this.checked) {
                        availabilitySettings.style.display = 'block';
                        // Rendi i campi di data opzionali per eventi basati su disponibilità
                        makeDateFieldsOptional();
                    } else {
                        availabilitySettings.style.display = 'none';
                        // Rendi i campi di data obbligatori per eventi normali
                        makeDateFieldsRequired();
                    }
                });
            }

            // Assicurati che la mappa sia visibile di default per eventi fisici
            const mapContainer = document.getElementById('locationMap');
            if (mapContainer && !isOnlineCheckbox?.checked) {

                mapContainer.style.display = 'block';
                // Assicurati che anche il container della mappa sia visibile
                const mapSection = mapContainer.closest('.col-12');

            // ========================================
            // INIZIALIZZAZIONE TOGGLE SOTTOTITOLO
            // ========================================


            const subtitleToggle = document.getElementById('subtitle-toggle');
            const subtitleField = document.getElementById('subtitle-field');
            const subtitleInput = document.getElementById('subtitle');




            if (subtitleToggle && subtitleField) {


                subtitleToggle.addEventListener('change', function() {

                    if (this.checked) {
                        subtitleField.style.display = 'block';
                        subtitleInput.focus();
                    } else {
                        subtitleField.style.display = 'none';
                                    subtitleInput.value =
                                    ''; // Pulisce il campo quando si disattiva
                    }
                });
            } else {
                // Gli elementi del sottotitolo potrebbero non esistere in tutte le pagine
                // Non è un errore critico, quindi rimuoviamo il console.error
                // console.error('Subtitle toggle elements not found!');
            }
                if (mapSection) {
                    mapSection.style.display = 'block';
                }
            }

            // Inizializza la mappa se siamo già al passo 2
            if (currentStep === 2) {

                setTimeout(initializeMap, 200);
            }

            // ========================================
            // INIZIALIZZAZIONE FESTIVAL
            // ========================================

            // Inizializza la sezione festival basandosi sulla categoria selezionata
            const initialCategory = document.getElementById('category').value;
            if (initialCategory) {
                updateFestivalSectionDisplay(initialCategory);
            }

            // Controllo aggiuntivo per assicurarsi che il JavaScript venga eseguito
            setTimeout(() => {

                const isOnlineCheckbox = document.getElementById('is_online');
                if (isOnlineCheckbox && isOnlineCheckbox.checked) {

                    makeLocationFieldsOptional();
                }
            }, 500);

            // Funzione per rendere i campi del luogo opzionali
            function makeLocationFieldsOptional() {


                // Lista degli ID degli elementi da nascondere
                const elementsToHide = [
                    'venue-name-container',
                    'recent-venues-section',
                    'venue-address-container',
                    'city-container',
                    'postcode-container',
                    'country-container',
                    'map-info-banner-container'
                ];

                // Lista degli ID dei campi input da rendere opzionali
                const fieldsToMakeOptional = [
                    'venue_name',
                    'venue_address',
                    'city',
                    'postcode',
                    'country'
                ];

                // Nascondi tutti gli elementi
                elementsToHide.forEach(elementId => {
                    const element = document.getElementById(elementId);
                    if (element) {
                        element.style.display = 'none';

                    } else {

                    }
                });

                // Rendi opzionali tutti i campi di localizzazione
                fieldsToMakeOptional.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field) {
                        field.required = false;


                        // Rimuovi l'asterisco dal label
                        const label = field.parentElement.querySelector('label');
                        if (label) {
                            label.textContent = label.textContent.replace(' *', '');
                        }
                    }
                });

                // Nascondi la mappa
                const mapContainer = document.getElementById('locationMap');
                if (mapContainer) {
                    mapContainer.style.display = 'none';

                }

                // Nascondi il container della mappa
                const mapSection = mapContainer?.closest('.col-12');
                if (mapSection) {
                    mapSection.style.display = 'none';
                }


            }

            // Funzione per rendere i campi del luogo obbligatori
            function makeLocationFieldsRequired() {


                // Lista degli ID degli elementi da mostrare
                const elementsToShow = [
                    'venue-name-container',
                    'recent-venues-section',
                    'venue-address-container',
                    'city-container',
                    'postcode-container',
                    'country-container',
                    'map-info-banner-container'
                ];

                // Lista degli ID dei campi input da rendere obbligatori
                const fieldsToMakeRequired = [
                    'venue_name',
                    'venue_address',
                    'city',
                    'postcode',
                    'country'
                ];

                // Mostra tutti gli elementi
                elementsToShow.forEach(elementId => {
                    const element = document.getElementById(elementId);
                    if (element) {
                        element.style.display = 'block';

                    } else {

                    }
                });

                // Rendi obbligatori tutti i campi di localizzazione
                fieldsToMakeRequired.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field) {
                        field.required = true;


                        // Aggiungi l'asterisco al label se non c'è già
                        const label = field.parentElement.querySelector('label');
                        if (label && !label.textContent.includes('*')) {
                            label.textContent += ' *';
                        }
                    }
                });

                // Mostra la mappa
                const mapContainer = document.getElementById('locationMap');
                if (mapContainer) {
                    mapContainer.style.display = 'block';

                }

                // Mostra il container della mappa
                const mapSection = mapContainer?.closest('.col-12');
                if (mapSection) {
                    mapSection.style.display = 'block';
                }


            }

            // Funzione per rendere i campi di data opzionali (per eventi basati su disponibilità)
            function makeDateFieldsOptional() {
                console.log('makeDateFieldsOptional called');
                const dateFields = ['start_datetime', 'end_datetime'];

                dateFields.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field) {
                        field.required = false;
                        console.log('Field', fieldId, 'set to not required');

                        // Rimuovi l'asterisco dal label
                        const label = field.parentElement.querySelector('label');
                        if (label) {
                            label.textContent = label.textContent.replace(' *', '');
                        }
                    }
                });
            }

            // Funzione per rendere i campi di data obbligatori (per eventi normali)
            function makeDateFieldsRequired() {
                const dateFields = ['start_datetime', 'end_datetime'];

                dateFields.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field) {
                        field.required = true;

                        // Aggiungi l'asterisco al label se non c'è già
                        const label = field.parentElement.querySelector('label');
                        if (label && !label.textContent.includes('*')) {
                            label.textContent += ' *';
                        }
                    }
                });
            }


        } catch (error) {
            console.error('Errore durante l\'inizializzazione:', error);
            console.error('Stack trace:', error.stack);
        }
    }, 100);
});

function initializeForm() {
    // Set minimum date to now
    const now = new Date();
    const localDateTime = new Date(now.getTime() - now.getTimezoneOffset() * 60000).toISOString().slice(0, 16);

    // Check if elements exist before setting properties
    const startDateTime = document.getElementById('start_datetime');
    const endDateTime = document.getElementById('end_datetime');

    if (startDateTime) startDateTime.min = localDateTime;
    if (endDateTime) endDateTime.min = localDateTime;

    // Initialize venue autocomplete
    initializeVenueAutocomplete();

    // Initialize Select2 for recent venues
    initializeSelect2Venues();
}

function initializeMap() {


    // Controlla se l'elemento mappa esiste e se la mappa non è già inizializzata
    const mapContainer = document.getElementById('locationMap');



    if (!mapContainer) {
        // Il container della mappa potrebbe non esistere in tutte le pagine
        // Non è un errore critico, quindi rimuoviamo il console.error
        // console.error('Map container not found!');
        return;
    }

    if (map !== null) {

        return;
    }


    map = L.map('locationMap', {
        zoomControl: true
    }).setView([41.9028, 12.4964], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Assicurati che i controlli di zoom siano visibili
    map.zoomControl.setPosition('topright');

    // Aggiungi controlli aggiuntivi se disponibili
    if (L.control.scale) {
        L.control.scale().addTo(map);
    }

    map.on('click', function(e) {
        setMapLocation(e.latlng.lat, e.latlng.lng);
    });

    // Force map to resize after initialization
    setTimeout(() => {
        if (map) {

            map.invalidateSize();
        }
    }, 200);


}

function setupEventListeners() {
    // Step navigation - check if elements exist
    const nextStepBtn = document.getElementById('nextStep');
    const prevStepBtn = document.getElementById('prevStep');

    if (nextStepBtn) {





        nextStepBtn.addEventListener('click', function(e) {
            e.preventDefault();
            nextStep();
        });

        // Aggiungi supporto per touch events su mobile
        nextStepBtn.addEventListener('touchend', function(e) {
            e.preventDefault();
            e.stopPropagation();
            nextStep();
        });
    } else {
        // Il pulsante next step potrebbe non esistere in tutte le pagine
        // Non è un errore critico, quindi rimuoviamo il console.error
        // console.error('Next step button not found!');
    }
    if (prevStepBtn) {
        prevStepBtn.addEventListener('click', prevStep);

        // Aggiungi supporto per touch events su mobile
        prevStepBtn.addEventListener('touchend', function(e) {
            e.preventDefault();
            e.stopPropagation();
            prevStep();
        });
    } else {
        // Il pulsante prev step potrebbe non esistere in tutte le pagine
        // Non è un errore critico, quindi rimuoviamo il console.error
        // console.error('Prev step button not found!');
    }

    // Direct step navigation (clicking on wizard steps)
    document.querySelectorAll('.wizard-step').forEach(stepEl => {
        stepEl.addEventListener('click', function() {
            const targetStep = parseInt(this.dataset.step);
            if (targetStep <= currentStep + 1) { // Allow going to next step or any previous step
                currentStep = targetStep;
                showStep(currentStep);
                updateProgress();
            }
        });
    });

    // Auto-update end time based on start time
    const startDateTime = document.getElementById('start_datetime');
    if (startDateTime) {
        startDateTime.addEventListener('change', function() {
            const startTime = new Date(this.value);
            const endTime = new Date(startTime.getTime() + 3 * 60 * 60 * 1000); // +3 hours
            const endDateTimeEl = document.getElementById('end_datetime');
            if (endDateTimeEl) {
                endDateTimeEl.value = endTime.toISOString().slice(0, 16);
            }
            updatePreview();
            updateRecurrencePreview(); // Aggiorna anche l'anteprima ricorrenza
        });
    }

    // ========================================
    // GESTIONE EVENTI RICORRENTI
    // ========================================


    const isRecurringCheckbox = document.getElementById('is_recurring');
    const recurrenceSettings = document.getElementById('recurrence-settings');




    if (isRecurringCheckbox && recurrenceSettings) {
        isRecurringCheckbox.addEventListener('change', function() {

            if (this.checked) {
                recurrenceSettings.style.display = 'block';
                updateRecurrencePreview();
            } else {
                recurrenceSettings.style.display = 'none';
            }
        });
    }

    // Gestione cambio tipo ricorrenza
    const recurrenceType = document.getElementById('recurrence_type');
    if (recurrenceType) {
        recurrenceType.addEventListener('change', function() {
            updateRecurrenceFields();
            updateRecurrencePreview();
        });
    }

    // Gestione cambio intervallo
    const recurrenceInterval = document.getElementById('recurrence_interval');
    if (recurrenceInterval) {
        recurrenceInterval.addEventListener('change', updateRecurrencePreview);
        recurrenceInterval.addEventListener('input', updateRecurrencePreview);
    }

    // Gestione cambio numero occorrenze
    const recurrenceCount = document.getElementById('recurrence_count');
    if (recurrenceCount) {
        recurrenceCount.addEventListener('change', updateRecurrencePreview);
        recurrenceCount.addEventListener('input', updateRecurrencePreview);
    }

    // Gestione cambio giorni settimana
    const weekdayCheckboxes = document.querySelectorAll('input[name="recurrence_weekdays[]"]');
    weekdayCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateRecurrencePreview);
    });

    // Gestione cambio giorno mese
    const recurrenceMonthday = document.getElementById('recurrence_monthday');
    if (recurrenceMonthday) {
        recurrenceMonthday.addEventListener('change', updateRecurrencePreview);
    }

    // Gestione cambio date inizio/fine per ricorrenza
    const endDateTime = document.getElementById('end_datetime');
    if (endDateTime) {
        endDateTime.addEventListener('change', updateRecurrencePreview);
    }





        // Automatic geocoding when all fields are filled
    let geocodeTimeout;
    const addressFields = ['venue_address', 'city', 'postcode', 'country'];

    addressFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('input', function() {
                clearTimeout(geocodeTimeout);

                // Wait 1 second after user stops typing
                geocodeTimeout = setTimeout(() => {
                    const address = document.getElementById('venue_address').value.trim();
                    const city = document.getElementById('city').value.trim();
                    const postcode = document.getElementById('postcode').value.trim();
                    const country = document.getElementById('country').value;

                    // Only geocode if we have at least address and city
                    if (address && city) {
                        let fullAddress = address + ', ' + city;
                        if (postcode) fullAddress += ', ' + postcode;
                        if (country) fullAddress += ', ' + country;

                        geocodeAddress(fullAddress);
                    }
                }, 1000);
            });
        }
    });

    // Tag system
    const tagTextInput = document.getElementById('tagTextInput');
    if (tagTextInput) {
        tagTextInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addTag(this.value.trim());
                this.value = '';
            }
        });
    }

    // Image preview
    const eventImage = document.getElementById('event_image');
    if (eventImage) {
        eventImage.addEventListener('change', previewImage);
    }

    // Real-time preview updates
    ['title', 'description', 'venue_name', 'city', 'ticket_price', 'start_datetime'].forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('input', updatePreview);
        }
    });

    // User search functionality for private events
    const privateUserSearchInput = document.getElementById('privateUserSearchInput');
    if (privateUserSearchInput) {
        privateUserSearchInput.addEventListener('keydown', handlePrivateUserSearchKeydown);
    }

    // User search functionality for artist invites
    const artistUserSearchInput = document.getElementById('artistUserSearchInput');
    if (artistUserSearchInput) {
        artistUserSearchInput.addEventListener('keydown', handleArtistUserSearchKeydown);
    }

    // Image upload preview
    const imageInput = document.getElementById('event_image');
    if (imageInput) {
        imageInput.addEventListener('change', updatePreview);
    }

    // Public/Private radio change
    document.querySelectorAll('input[name="is_public"]').forEach(radio => {
        radio.addEventListener('change', updatePreview);
    });

    // Public/Private toggle
    document.querySelectorAll('input[name="is_public"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const privateInvitesSection = document.getElementById('private-invites-section');

            // Mostra/nascondi sezione inviti per eventi privati
            if (privateInvitesSection) {
                if (this.value === '0') {
                    privateInvitesSection.style.display = 'block';
                    loadSuggestedUsers();
                } else {
                    privateInvitesSection.style.display = 'none';
                }
            }
        });
    });

    // ========================================
    // GESTIONE TERZO STEP - DETTAGLI EVENTO
    // ========================================

    // Gestione video promozionale
    const promotionalVideo = document.getElementById('promotional_video');
    if (promotionalVideo) {
        promotionalVideo.addEventListener('input', function() {
            const videoUrl = this.value.trim();
            const videoPreview = document.getElementById('videoPreview');
            const videoEmbed = document.getElementById('videoEmbed');

            if (videoUrl) {
                const embedUrl = convertToEmbedUrl(videoUrl);
                if (embedUrl) {
                    videoEmbed.src = embedUrl;
                    videoPreview.style.display = 'block';
                } else {
                    videoPreview.style.display = 'none';
                }
            } else {
                videoPreview.style.display = 'none';
            }
        });
    }

    // Gestione evento a pagamento
    const isPaidEvent = document.getElementById('is_paid_event');
    const paymentFields = document.getElementById('paymentFields');
    if (isPaidEvent && paymentFields) {
        isPaidEvent.addEventListener('change', function() {
            paymentFields.style.display = this.checked ? 'block' : 'none';
        });
    }

    // Gestione evento legato a gruppo
    const isLinkedToGroupCheckbox = document.getElementById('is_linked_to_group');
    const groupFields = document.getElementById('groupFields');
    if (isLinkedToGroupCheckbox && groupFields) {
        isLinkedToGroupCheckbox.addEventListener('change', function() {
            groupFields.style.display = this.checked ? 'block' : 'none';
        });
    }

    // Gestione selezione gruppi multipli
    const groupCheckboxes = document.querySelectorAll('.group-checkbox');
    if (groupCheckboxes.length > 0) {
        groupCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateSelectedGroupsList();

                // Aggiorna l'anteprima se siamo nello step 5
                        if (typeof currentStep !== 'undefined' && currentStep === 5 &&
                            typeof updatePreview === 'function') {
                    updatePreview();
                }
            });
        });
    }

        // ========================================
    // GESTIONE FESTIVAL - LOGICA BASATA SU CATEGORIA
    // ========================================

    // Gestione cambio categoria
    const categorySelect = document.getElementById('category');
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            const selectedCategory = this.value;


            // Aggiorna la visualizzazione della sezione festival nel quarto step
            updateFestivalSectionDisplay(selectedCategory);
        });
    }

    // Gestione creazione nuovo festival (quando non è selezionato un festival nel primo step)
    const isFestivalEvent = document.getElementById('is_festival_event');
    const festivalFields = document.getElementById('festivalFields');
    if (isFestivalEvent && festivalFields) {
        isFestivalEvent.addEventListener('change', function() {
            festivalFields.style.display = this.checked ? 'block' : 'none';
        });
    }
}

// ========================================
// FUNZIONI GLOBALI PER GESTIONE GRUPPI
// ========================================

// Funzione per aggiornare la lista dei gruppi selezionati
function updateSelectedGroupsList() {
    const selectedGroups = Array.from(document.querySelectorAll('.group-checkbox:checked'));
    const selectedGroupsList = document.getElementById('selectedGroupsList');

    if (selectedGroups.length === 0) {
        selectedGroupsList.innerHTML = '<p class="text-muted f-s-14">Nessun gruppo selezionato</p>';
    } else {
        let html = '<div class="list-group">';
        selectedGroups.forEach(checkbox => {
            const label = document.querySelector(`label[for="${checkbox.id}"]`);
            const groupName = label ? label.querySelector('strong').textContent : 'Gruppo sconosciuto';
            const groupDesc = label ? label.querySelector('small')?.textContent : '';

            html += `
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${groupName}</strong>
                        ${groupDesc ? `<br><small class="text-muted">${groupDesc}</small>` : ''}
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeGroup(${checkbox.value})">
                        <i class="ph ph-x"></i>
                    </button>
                </div>
            `;
        });
        html += '</div>';
        selectedGroupsList.innerHTML = html;
    }
}

// Funzione per rimuovere un gruppo dalla selezione
function removeGroup(groupId) {
    const checkbox = document.getElementById(`group_${groupId}`);
    if (checkbox) {
        checkbox.checked = false;
        updateSelectedGroupsList();
    }
}

// Funzione per gestire la pressione dei tasti nella ricerca gruppi
function handleGroupSearchKeydown(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        searchGroups();
    }
}

// Funzione per ricercare i gruppi
function searchGroups() {
    const searchTerm = document.getElementById('groupSearchInput').value.trim();
    const searchResults = document.getElementById('groupSearchResults');
    const searchResultsList = document.getElementById('groupSearchResultsList');

    if (searchTerm.length < 2) {
        searchResults.style.display = 'none';
        return;
    }

    // Mostra loading
            searchResultsList.innerHTML =
                '<div class="list-group-item text-center"><i class="ph ph-spinner ph-spin"></i> Ricerca in corso...</div>';
    searchResults.style.display = 'block';

    // Simula ricerca (in produzione, fai una chiamata AJAX)
    fetch(`/api/groups/search?q=${encodeURIComponent(searchTerm)}`)
        .then(response => response.json())
        .then(data => {
            displayGroupSearchResults(data.groups || []);
        })
        .catch(error => {
            console.error('Errore nella ricerca gruppi:', error);
            // Fallback: ricerca locale nei gruppi esistenti
            const localResults = searchGroupsLocally(searchTerm);
            displayGroupSearchResults(localResults);
        });
}

// Funzione per ricerca locale nei gruppi esistenti (fallback)
function searchGroupsLocally(searchTerm) {
    // Ottieni i gruppi dal DOM invece che dal JSON inline
    const allGroups = [];
    document.querySelectorAll('.group-checkbox').forEach(checkbox => {
        const label = document.querySelector(`label[for="${checkbox.id}"]`);
        if (label) {
            const name = label.querySelector('strong').textContent;
            const desc = label.querySelector('small')?.textContent || '';
            allGroups.push({
                id: checkbox.value,
                name: name,
                description: desc
            });
        }
    });

    const filtered = allGroups.filter(group =>
        group.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
        (group.description && group.description.toLowerCase().includes(searchTerm.toLowerCase()))
    );
    return filtered;
}

// Funzione per mostrare i risultati della ricerca gruppi
function displayGroupSearchResults(groups) {
    const searchResultsList = document.getElementById('groupSearchResultsList');

    if (groups.length === 0) {
                searchResultsList.innerHTML =
                    '<div class="list-group-item text-center text-muted">Nessun gruppo trovato</div>';
    } else {
        let html = '';
        groups.forEach(group => {
            const isSelected = document.getElementById(`group_${group.id}`)?.checked || false;
            html += `
                <div class="list-group-item d-flex justify-content-between align-items-center ${isSelected ? 'bg-light' : ''}">
                    <div class="flex-grow-1">
                        <strong>${group.name}</strong>
                        ${group.description ? `<br><small class="text-muted">${group.description}</small>` : ''}
                    </div>
                    <button type="button" class="btn btn-sm ${isSelected ? 'btn-success' : 'btn-outline-primary'}"
                            onclick="toggleGroupFromSearch(${group.id})">
                        <i class="ph ph-${isSelected ? 'check' : 'plus'}"></i>
                        ${isSelected ? 'Selezionato' : 'Seleziona'}
                    </button>
                </div>
            `;
        });
        searchResultsList.innerHTML = html;
    }
}

// Funzione per selezionare/deselezionare un gruppo dai risultati di ricerca
function toggleGroupFromSearch(groupId) {
    const checkbox = document.getElementById(`group_${groupId}`);
    if (checkbox) {
        checkbox.checked = !checkbox.checked;
        updateSelectedGroupsList();
        // Aggiorna i risultati di ricerca
        searchGroups();
    }
}

// Funzione per mostrare/nascondere tutti i gruppi
function toggleAllGroups() {
    const allGroupsList = document.getElementById('allGroupsList');
    const toggleIcon = document.getElementById('toggleGroupsIcon');
    const toggleText = document.getElementById('toggleGroupsText');

    if (allGroupsList.style.display === 'none') {
        allGroupsList.style.display = 'block';
        toggleIcon.className = 'ph ph-eye-slash';
        toggleText.textContent = 'Nascondi tutti';
    } else {
        allGroupsList.style.display = 'none';
        toggleIcon.className = 'ph ph-list';
        toggleText.textContent = 'Mostra tutti';
    }
}

function nextStep() {
    console.log('nextStep called, currentStep:', currentStep);

    const validationResult = validateCurrentStep();
    console.log('nextStep validation result:', validationResult);

    if (validationResult) {

        if (currentStep < 5) {
            currentStep++;

            showStep(currentStep);
            updateProgress();
            if (currentStep === 5) {
                updatePreview();
            }
        } else {

        }
    } else {

    }
}

function prevStep() {
    if (currentStep > 1) {
        currentStep--;
        showStep(currentStep);
        updateProgress();
    }
}

function showStep(step) {
    // Hide all steps
    for (let i = 1; i <= 5; i++) {
        const stepElement = document.getElementById(`step-${i}`);
        if (stepElement) {
            stepElement.classList.add('d-none');
        }
    }

    // Show current step
    const currentStepElement = document.getElementById(`step-${step}`);
    if (currentStepElement) {
        currentStepElement.classList.remove('d-none');
    }

    // Initialize map when reaching step 2
    if (step === 2) {
        setTimeout(initializeMap, 100); // Small delay to ensure DOM is ready
    }

            // Update wizard step indicators with clear color logic
    for (let i = 1; i <= 5; i++) {
        const stepContainer = document.querySelector(`[data-step="${i}"]`);
        if (stepContainer) {
            const icon = stepContainer.querySelector('i');
            const text = stepContainer.querySelector('div');

            // Reset colors
            icon.classList.remove('text-primary', 'text-success', 'text-muted');
            text.classList.remove('text-primary', 'text-success', 'text-muted');

            if (i === step) {
                // CURRENT STEP = PRIMARY (teal)
                icon.classList.add('text-primary');
                text.classList.add('text-primary');
            } else if (i < step) {
                // COMPLETED STEPS = SUCCESS (green)
                icon.classList.add('text-success');
                text.classList.add('text-success');
            } else {
                // FUTURE STEPS = MUTED (gray)
                icon.classList.add('text-muted');
                text.classList.add('text-muted');
            }
        }
    }

    // Update navigation buttons
    const prevStepBtn = document.getElementById('prevStep');
    const nextStepBtn = document.getElementById('nextStep');

    if (prevStepBtn) prevStepBtn.disabled = step === 1;
    if (nextStepBtn) nextStepBtn.style.display = step === 5 ? 'none' : 'block';

    // Update tip
    const stepTipElement = document.getElementById('stepTip');
    if (stepTipElement) {
        stepTipElement.textContent = stepTips[step];
    }
}

function updateProgress() {
    const progress = (currentStep / 5) * 100;
    const progressBar = document.getElementById('progressBar');
    const currentStepEl = document.getElementById('currentStep');

    if (progressBar) {
        progressBar.style.width = progress + '%';
    }
    if (currentStepEl) {
        currentStepEl.textContent = currentStep;
    }
}

function validateCurrentStep() {
    const step = currentStep;
    let isValid = true;
    console.log('Validating step:', step);



    // Clear previous errors and highlighting
    document.querySelectorAll('.error-feedback').forEach(el => el.textContent = '');
    document.querySelectorAll('.is-invalid').forEach(el => {
        el.classList.remove('is-invalid');
        el.style.borderColor = '';
    });

    if (step === 1) {
        const title = document.getElementById('title').value.trim();
        const description = document.getElementById('description').value.trim();
        const category = document.getElementById('category').value;



        if (!title) {

            showError('title', 'Il titolo è obbligatorio');
            highlightError('title');
            isValid = false;
        } else if (title.length < 5) {

            showError('title', 'Il titolo deve essere di almeno 5 caratteri');
            highlightError('title');
            isValid = false;
        }

        // Description is optional - no minimum length requirement
        // if (description && description.length < 20) {
        //
        //     showError('description', 'La descrizione deve essere di almeno 20 caratteri');
        //     highlightError('description');
        //     isValid = false;
        // }

        if (!category) {

            showError('category', 'La categoria è obbligatoria');
            highlightError('category');
            isValid = false;
        }

        // Validazione per selezione categoria (opzionale nel primo step)
        const selectedCategory = document.getElementById('category').value;
        if (selectedCategory && selectedCategory !== '') {

            // La selezione della categoria è obbligatoria, ma non serve validazione aggiuntiva qui
        }
    }

    if (step === 2) {
        const startDateTime = document.getElementById('start_datetime').value;
        const endDateTime = document.getElementById('end_datetime').value;
        const isOnline = document.getElementById('is_online')?.checked || false;

                console.log('Step 2 validation - startDateTime:', startDateTime, 'endDateTime:', endDateTime, 'isOnline:',
                    isOnline);

        // Check if event is availability-based
        const isAvailabilityBased = document.getElementById('is_availability_based')?.checked || false;
        console.log('Step 2 validation - isAvailabilityBased:', isAvailabilityBased);

        // Debug: Check if fields are actually required
        const startDateTimeField = document.getElementById('start_datetime');
        const endDateTimeField = document.getElementById('end_datetime');
                console.log('Step 2 validation - startDateTimeField.required:', startDateTimeField?.required,
                    'endDateTimeField.required:', endDateTimeField?.required);


        if (!startDateTime && !isAvailabilityBased) {
            showError('start_datetime', 'Data e ora di inizio sono obbligatorie');
            highlightError('start_datetime');
            isValid = false;
        }

        if (!endDateTime && !isAvailabilityBased) {
            showError('end_datetime', 'Data e ora di fine sono obbligatorie');
            highlightError('end_datetime');
            isValid = false;
        } else if (startDateTime && endDateTime && new Date(endDateTime) <= new Date(startDateTime)) {
            showError('end_datetime', 'La data di fine deve essere successiva a quella di inizio');
            highlightError('start_datetime');
            highlightError('end_datetime');
            isValid = false;
        }

        // Validazione campi di localizzazione solo per eventi fisici
        if (!isOnline) {
            const venueName = document.getElementById('venue_name').value.trim();
            const venueAddress = document.getElementById('venue_address').value.trim();
            const city = document.getElementById('city').value.trim();

            if (!venueName) {
                showError('venue_name', 'Il nome del venue è obbligatorio');
                highlightError('venue_name');
                isValid = false;
            }

            if (!venueAddress) {
                showError('venue_address', 'L\'indirizzo è obbligatorio');
                highlightError('venue_address');
                isValid = false;
            }

            if (!city) {
                showError('city', 'La città è obbligatoria');
                highlightError('city');
                isValid = false;
            }
        } else {

        }

        // Validate recurrence settings if enabled
        const isRecurring = document.getElementById('is_recurring');
        if (isRecurring && isRecurring.checked) {
            const recurrenceType = document.getElementById('recurrence_type').value;
            const recurrenceInterval = document.getElementById('recurrence_interval').value;

            if (!recurrenceType) {
                showError('recurrence_type', 'Il tipo di ricorrenza è obbligatorio');
                highlightError('recurrence_type');
                isValid = false;
            }

            if (!recurrenceInterval || recurrenceInterval <= 0) {
                showError('recurrence_interval', 'L\'intervallo di ricorrenza deve essere maggiore di 0');
                highlightError('recurrence_interval');
                isValid = false;
            }

            // Always validate recurrence_count when recurring is enabled
            const recurrenceCount = document.getElementById('recurrence_count').value;
            if (!recurrenceCount || recurrenceCount <= 0) {
                showError('recurrence_count', 'Il numero di occorrenze deve essere maggiore di 0');
                highlightError('recurrence_count');
                isValid = false;
            }

            if (recurrenceType === 'weekly') {
                const weekdays = document.querySelectorAll('input[name="recurrence_weekdays[]"]:checked');
                if (weekdays.length === 0) {
                    showError('recurrence_weekdays', 'Seleziona almeno un giorno della settimana');
                    highlightError('recurrence_weekdays');
                    isValid = false;
                }
            }

            if (recurrenceType === 'monthly') {
                const monthday = document.getElementById('recurrence_monthday').value;
                if (!monthday) {
                    showError('recurrence_monthday', 'Seleziona il giorno del mese');
                    highlightError('recurrence_monthday');
                    isValid = false;
                }
            }
        }
    }

            if (step === 3) {


        // Validazione per evento a pagamento
        const isPaidEvent = document.getElementById('is_paid_event');
        if (isPaidEvent && isPaidEvent.checked) {
            const ticketPrice = document.getElementById('ticket_price').value;
            const ticketCurrency = document.getElementById('ticket_currency').value;

            if (!ticketPrice || ticketPrice <= 0) {
                showError('ticket_price', 'Il prezzo del biglietto deve essere maggiore di 0');
                highlightError('ticket_price');
                isValid = false;
            }

            if (!ticketCurrency) {
                showError('ticket_currency', 'Seleziona una valuta');
                highlightError('ticket_currency');
                isValid = false;
            }
        }

        // Validazione per festival (logica basata su categoria)
        const selectedCategory = document.getElementById('category').value;
        const isFestivalEvent = document.getElementById('is_festival_event');

                if (selectedCategory === 'festival') {
            // Categoria "Festival" selezionata: gli eventi sono opzionali
                    const selectedFestivalEvents = JSON.parse(document.getElementById('selectedFestivalEventsData').value ||
                        '[]');


            // Rimuovi eventuali messaggi di errore precedenti
            const errorDiv = document.querySelector('.festival-error-message');
            if (errorDiv) {
                errorDiv.style.display = 'none';
            }

            // Rimuovi evidenziazione errore
            const searchInput = document.getElementById('eventSearchInput');
            if (searchInput) {
                searchInput.classList.remove('is-invalid');
                searchInput.style.borderColor = '';
            }
        } else if (isFestivalEvent && isFestivalEvent.checked) {
            // Altre categorie con checkbox "appartiene ad un festival" attiva: valida selezione festival
            const festivalId = document.getElementById('festival_id').value;
            if (!festivalId) {
                showError('festival_id', 'Seleziona un festival');
                highlightError('festival_id');
                isValid = false;
            }
        }


    }

    if (step === 4) {


        // Validazione per selezione pubblico/privato
        const isPublic = document.querySelector('input[name="is_public"]:checked');
        if (!isPublic) {
            showError('is_public', 'Seleziona se l\'evento è pubblico o privato');
            highlightError('public');
            highlightError('private');
            isValid = false;
        }

        // Validazione per eventi privati - verifica che ci siano inviti
        if (isPublic && isPublic.value === '0') {
                    const privateInvitedUsers = JSON.parse(document.getElementById('privateInvitedUsersData').value ||
                    '[]');
            if (privateInvitedUsers.length === 0) {
                showError('privateInvitedUsersData', 'Per eventi privati devi invitare almeno un utente');
                highlightError('privateInvitedUsersData');
                isValid = false;
            }
        }

        // Validazione per selezione gruppi (opzionale)
        const isLinkedToGroup = document.getElementById('is_linked_to_group')?.checked || false;
        const groupCheckboxes = document.querySelectorAll('.group-checkbox:checked');
        const selectedGroupNames = Array.from(groupCheckboxes).map(cb => {
            const label = document.querySelector(`label[for="${cb.id}"]`);
            return label ? label.textContent.trim() : '';
        }).filter(name => name);
        if (isLinkedToGroup && selectedGroupNames.length === 0) {
            showError('groupFields', 'Seleziona almeno un gruppo');
            highlightError('groupFields');
            isValid = false;
        }

        // Validazione per scadenza iscrizioni
        const hasDeadline = document.getElementById('has_deadline')?.checked || false;
        if (hasDeadline) {
            const deadlineDate = document.getElementById('registrationDeadlineDate').value;
            const deadlineTime = document.getElementById('registrationDeadlineTime').value;

            if (!deadlineDate) {
                showError('registrationDeadlineDate', 'Seleziona la data di scadenza');
                highlightError('registrationDeadlineDate');
                isValid = false;
            }

            if (!deadlineTime) {
                showError('registrationDeadlineTime', 'Seleziona l\'ora di scadenza');
                highlightError('registrationDeadlineTime');
                isValid = false;
            }

            // Verifica che la scadenza sia precedente all'inizio dell'evento
            if (deadlineDate && deadlineTime) {
                const startDateTime = document.getElementById('start_datetime').value;
                const deadlineDateTime = deadlineDate + ' ' + deadlineTime;

                if (startDateTime && deadlineDateTime) {
                    const startDate = new Date(startDateTime);
                    const deadlineDate = new Date(deadlineDateTime);

                    if (deadlineDate >= startDate) {
                                showError('registrationDeadlineDate',
                                    'La scadenza deve essere precedente alla data di inizio dell\'evento');
                        highlightError('registrationDeadlineDate');
                        highlightError('registrationDeadlineTime');
                        isValid = false;
                    }
                }
            }
        }


    }


    console.log('Validation result for step', step, ':', isValid);
    return isValid;
}

function showError(fieldId, message) {
    const errorEl = document.getElementById(fieldId + '-error');
    if (errorEl) {
        errorEl.textContent = message;
        errorEl.style.display = 'block';
        errorEl.style.color = '#dc3545';
    }
}

function highlightError(fieldId) {
    const field = document.getElementById(fieldId);
    if (field) {
        field.classList.add('is-invalid');
        field.style.borderColor = '#dc3545';
        field.style.boxShadow = '0 0 0 0.2rem rgba(220, 53, 69, 0.25)';
    }
}



function setMapLocation(lat, lng, skipReverseGeocode = false) {
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lng;

    if (marker) {
        map.removeLayer(marker);
    }

    marker = L.marker([lat, lng]).addTo(map);
    map.setView([lat, lng], 15);

    // Esegui reverse geocoding solo se non è stato esplicitamente saltato
    if (!skipReverseGeocode) {
        reverseGeocode(lat, lng);
    }
}

function geocodeAddress(address) {
    if (!address || address.length < 3) return;

    const statusEl = document.getElementById('geocoding-status');
    if (statusEl) {
        statusEl.style.display = 'block';
        statusEl.innerHTML = '<i class="ph ph-spinner-gap me-1"></i> Ricerca posizione sulla mappa...';
        statusEl.className = 'small text-info mt-1';
    }

    // Usa un endpoint più dettagliato per ottenere informazioni complete (tutti i paesi)
            fetch(
                    `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1&addressdetails=1`)
        .then(response => response.json())
        .then(data => {
            if (data && data.length > 0) {
                const result = data[0];
                setMapLocation(parseFloat(result.lat), parseFloat(result.lon), true); // Skip reverse geocoding

                // NON aggiorniamo i campi - solo posizioniamo sulla mappa
                // updateAddressFields(result);

                // Mostra successo
                if (statusEl) {
                    statusEl.innerHTML = '<i class="ph ph-check me-1"></i> Posizione trovata sulla mappa!';
                    statusEl.className = 'small text-success mt-1';
                    setTimeout(() => {
                        statusEl.style.display = 'none';
                    }, 3000);
                }
            } else {
                // Indirizzo non trovato
                if (statusEl) {
                    statusEl.innerHTML = '<i class="ph ph-warning me-1"></i> Indirizzo non trovato sulla mappa';
                    statusEl.className = 'small text-warning mt-1';
                    setTimeout(() => {
                        statusEl.style.display = 'none';
                    }, 3000);
                }
            }
        })
        .catch(error => {
            console.error('Geocoding error:', error);
            if (statusEl) {
                statusEl.innerHTML = '<i class="ph ph-warning me-1"></i> Errore nella ricerca';
                statusEl.className = 'small text-danger mt-1';
                setTimeout(() => {
                    statusEl.style.display = 'none';
                }, 3000);
            }
        });
}

function updateAddressFields(result) {
    // Aggiorna il campo indirizzo (solo via e numero)
    const venueAddressInput = document.getElementById('venue_address');
    if (venueAddressInput && result.address) {
        const addressParts = [];

        if (result.address.house_number) {
            addressParts.push(result.address.house_number);
        }
        if (result.address.road) {
            addressParts.push(result.address.road);
        }
        if (result.address.suburb) {
            addressParts.push(result.address.suburb);
        }

        // Se abbiamo parti dell'indirizzo, aggiorna il campo
        if (addressParts.length > 0) {
            const cleanAddress = addressParts.join(', ');
            // Aggiorna solo se il campo è vuoto o se l'indirizzo è diverso
            if (!venueAddressInput.value.trim() || venueAddressInput.value.trim() !== cleanAddress) {
                venueAddressInput.value = cleanAddress;
            }
        }
    }

    // Aggiorna il campo città
    const cityInput = document.getElementById('city');
    if (cityInput && result.address) {
        let city = '';

        // Priorità: city > town > village > municipality > county > state
        city = result.address.city ||
               result.address.town ||
               result.address.village ||
               result.address.municipality ||
               result.address.county ||
               result.address.state ||
               '';

        // Se non troviamo la città nei dettagli, prova a estrarla dal display_name
        if (!city && result.display_name) {
            const parts = result.display_name.split(',');
            // Cerca la parte che sembra una città (non troppo lunga, non numeri, non codici postali)
            for (let i = 1; i < Math.min(parts.length, 5); i++) {
                const part = parts[i].trim();
                // Escludi codici postali, numeri e parti troppo lunghe
                if (part.length > 2 && part.length < 50 &&
                    !/^\d+$/.test(part) &&
                    !/^\d{5}$/.test(part) && // Codici postali italiani
                    !/^\d{4}$/.test(part) && // Codici postali europei
                    !/^[A-Z]{2}$/.test(part)) { // Codici paese
                    city = part;
                    break;
                }
            }
        }

        // Aggiorna solo se il campo è vuoto o se abbiamo trovato una città valida
        if (city && (!cityInput.value.trim() || city.length > 2)) {
            cityInput.value = city;
        }
    }

    // Aggiorna il campo CAP
    const postcodeInput = document.getElementById('postcode');
    if (postcodeInput && result.address && result.address.postcode) {
        // Aggiorna solo se il campo è vuoto
        if (!postcodeInput.value.trim()) {
            postcodeInput.value = result.address.postcode;
        }
    }

    // Aggiorna il campo paese
    const countryInput = document.getElementById('country');
    if (countryInput && result.address && result.address.country_code) {
        // Aggiorna solo se il campo è vuoto
        if (!countryInput.value.trim()) {
            countryInput.value = result.address.country_code.toUpperCase();
        }
    }

    // Aggiorna anche il nome del venue se è vuoto
    const venueNameInput = document.getElementById('venue_name');
    if (venueNameInput && !venueNameInput.value.trim() && result.address) {
        // Prova a estrarre il nome del venue dall'indirizzo
        const venueName = result.address.house_number ||
                         result.address.road ||
                         result.address.suburb ||
                         '';
        if (venueName) {
            venueNameInput.value = venueName;
        }
    }
}





function addTag(tagText) {
    if (tagText && !tags.includes(tagText)) {
        tags.push(tagText);
        updateTagsDisplay();
        updateTagsInput();
    }
}

function removeTag(tagText) {
    tags = tags.filter(tag => tag !== tagText);
    updateTagsDisplay();
    updateTagsInput();
}

function updateTagsDisplay() {
    const container = document.getElementById('tagsDisplay');

    // Clear existing tags
    container.innerHTML = '';

    // Add tags using Bootstrap badges
    tags.forEach(tag => {
        const tagEl = document.createElement('span');
        tagEl.className = 'badge bg-light-primary me-2 mb-2';
                tagEl.innerHTML =
                    `${tag} <span class="ms-1 text-decoration-none" role="button" onclick="removeTag('${tag}')">&times;</span>`;
        container.appendChild(tagEl);
    });
}

function updateTagsInput() {
    document.getElementById('tagsHidden').value = tags.join(',');
}

function previewImage() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}

function updatePreview() {
    if (currentStep !== 5) return;

    // Get all form values with safe element access
    const title = document.getElementById('title')?.value || 'Titolo {{ __('invitations.event') }}';
    const description = document.getElementById('description')?.value || 'Descrizione evento...';
    const requirements = document.getElementById('requirements')?.value || '';
    const category = document.getElementById('category')?.value || '';
    const categoryText = category && document.getElementById('category')?.options ?
                document.getElementById('category').options[document.getElementById('category').selectedIndex]?.text || '' :
                '';

    // Date and time
    const startDateTime = document.getElementById('start_datetime')?.value || '';
    const endDateTime = document.getElementById('end_datetime')?.value || '';

    // Registration deadline from new fields
    const hasRegistrationDeadline = document.getElementById('has_deadline')?.checked || false;
    const registrationDeadlineDate = document.getElementById('registrationDeadlineDate')?.value || '';
    const registrationDeadlineTime = document.getElementById('registrationDeadlineTime')?.value || '';
    const registrationDeadline = hasRegistrationDeadline && registrationDeadlineDate && registrationDeadlineTime ?
        registrationDeadlineDate + ' ' + registrationDeadlineTime : '';

    const invitationDeadline = document.getElementById('invitation_deadline')?.value || '';

    // Location
    const venueName = document.getElementById('venue_name')?.value || '';
    const venueAddress = document.getElementById('venue_address')?.value || '';
    const city = document.getElementById('city')?.value || '';
    const postcode = document.getElementById('postcode')?.value || '';
    const country = document.getElementById('country')?.value || '';
    const isOnline = document.getElementById('is_online')?.checked || false;
    const onlineUrl = document.getElementById('online_url')?.value || '';
    const timezone = document.getElementById('timezone')?.value || '';

    // Availability settings
    const isAvailabilityBased = document.getElementById('is_availability_based')?.checked || false;
    const availabilityDeadline = document.getElementById('availability_deadline')?.value || '';
    const availabilityInstructions = document.getElementById('availability_instructions')?.value || '';

    // Event settings
    const entryFee = document.getElementById('ticket_price')?.value || '0';
    const maxParticipants = document.getElementById('max_participants')?.value || '';
    const isPublicRadio = document.querySelector('input[name="is_public"]:checked');
    const isPublic = isPublicRadio ? isPublicRadio.value === '1' : true;
    const allowRequests = document.getElementById('allow_requests')?.checked || false;
    const statusRadio = document.querySelector('input[name="status"]:checked');
    const status = statusRadio ? statusRadio.value : 'published';

    // Festival data
    const isFestival = category === 'festival';
    const selectedFestivalEventsData = document.getElementById('selectedFestivalEventsData');
    const festivalEvents = selectedFestivalEventsData ? JSON.parse(selectedFestivalEventsData.value || '[]') : [];
    const isPartOfFestival = document.getElementById('is_festival_event')?.checked || false;
    const festivalId = document.getElementById('festival_id')?.value || '';

    // Recurrence
    const isRecurring = document.getElementById('is_recurring')?.checked || false;
    const recurrenceType = document.getElementById('recurrence_type')?.value || '';
    const recurrenceInterval = document.getElementById('recurrence_interval')?.value || '';
    const recurrenceCount = document.getElementById('recurrence_count')?.value || '';

    // Gig positions
    const gigPositionsData = document.getElementById('gigPositionsData');
    const gigPositions = gigPositionsData ? JSON.parse(gigPositionsData.value || '[]') : [];

    // Group association
    const isLinkedToGroup = document.getElementById('is_linked_to_group')?.checked || false;
    const groupId = document.getElementById('group_id')?.value || '';
    const groupSelect = document.getElementById('group_id');
    const selectedGroupName = groupSelect && groupSelect.options && groupSelect.selectedIndex >= 0 ?
        groupSelect.options[groupSelect.selectedIndex]?.text || '' : '';

    // Invitations
    const privateInvitedUsersData = document.getElementById('privateInvitedUsersData');
    const privateInvitedUsers = privateInvitedUsersData ? JSON.parse(privateInvitedUsersData.value || '[]') : [];
    const artistInvitedUsersData = document.getElementById('artistInvitedUsersData');
    const artistInvitedUsers = artistInvitedUsersData ? JSON.parse(artistInvitedUsersData.value || '[]') : [];
    const invitationsData = document.getElementById('invitationsData');
    const invitations = invitationsData ? JSON.parse(invitationsData.value || '[]') : [];

    // Get image preview or use fallback
    const imageInput = document.getElementById('event_image');
    let imageHtml = '';
    if (imageInput && imageInput.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            updatePreviewWithImage(e.target.result);
        };
        reader.readAsDataURL(imageInput.files[0]);
        return; // Will be called again with image
    } else {
        // Use fallback image
        imageHtml = `
            <div class="position-absolute w-100 h-100 bg-primary" style="opacity: 0.9;"></div>
            <div class="position-absolute top-50 start-50 translate-middle text-center w-100" style="z-index: 2;">
                <i class="ph ph-microphone-stage display-1 mb-3 opacity-50"></i>
            </div>
        `;
    }

    // Format dates
    const formatDate = (dateString) => {
        if (!dateString) return 'Non specificato';
        return new Date(dateString).toLocaleDateString('it-IT', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    };

    const formatTime = (dateString) => {
        if (!dateString) return 'Non specificato';
        return new Date(dateString).toLocaleTimeString('it-IT', {
            hour: '2-digit',
            minute: '2-digit'
        });
    };

    const formatDateOnly = (dateString) => {
        if (!dateString) return 'Non specificato';
        return new Date(dateString).toLocaleDateString('it-IT', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    };

    // Calculate duration
    let duration = '';
    if (startDateTime && endDateTime) {
        const start = new Date(startDateTime);
        const end = new Date(endDateTime);
        const diffHours = Math.round((end - start) / (1000 * 60 * 60));
        duration = `${diffHours} ${diffHours === 1 ? 'ora' : 'ore'}`;
    }

    const preview = `
        <!-- Hero Section -->
        <div class="position-relative overflow-hidden bg-primary" style="height: 300px;">
            ${imageHtml}

            <!-- Status Badges -->
            <div class="position-absolute top-0 start-0 m-3" style="z-index: 3;">
                <span class="badge bg-light-primary me-2">
                    <i class="ph ph-tag me-1"></i>${categoryText || 'Categoria non specificata'}
                </span>
                ${isFestival ? '<span class="badge bg-warning"><i class="ph ph-trophy me-1"></i>Festival</span>' : ''}
            </div>

            <span class="badge ${isPublic ? 'bg-light-success' : 'bg-light-warning'} position-absolute top-0 end-0 m-3" style="z-index: 3;">
                <i class="ph ph-${isPublic ? 'globe' : 'lock'} me-1"></i>
                ${isPublic ? 'Pubblico' : 'Privato'}
            </span>

            <div class="position-absolute bottom-0 start-0 text-white p-4 w-100" style="z-index: 3;">
                <h2 class="fw-bold mb-3 text-white">${title}</h2>
                <!-- Gruppi selezionati -->
                ${isLinkedToGroup && selectedGroupNames.length > 0 ? `
                    <div class="mb-2">
                        ${selectedGroupNames.map(name => `<span class=\"badge bg-light-primary me-2\">${name}</span>`).join(' ')}
                    </div>
                ` : ''}
                <div class="d-flex align-items-center mb-2">
                    <i class="ph ph-calendar-check me-2 fs-5"></i>
                    <span class="fs-5">${formatDate(startDateTime)}</span>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <i class="ph ph-map-pin me-2 fs-5"></i>
                    <span class="fs-5">
                        ${isOnline ?
                            '<i class="ph ph-globe me-1"></i>Evento Online' + (onlineUrl ? ` - ${onlineUrl}` : '') :
                            `${venueName ? venueName + ', ' : ''}${city}${country ? ', ' + country : ''}`
                        }
                    </span>
                </div>
                ${isOnline && timezone ? `
                    <div class="d-flex align-items-center">
                        <i class="ph ph-clock me-2 fs-5"></i>
                        <span class="fs-5">Fuso orario: ${timezone}</span>
                    </div>
                ` : ''}
            </div>
        </div>

        <!-- Event Details -->
        <div class="p-4">
            <!-- Description -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="ph ph-file-text me-2"></i>Descrizione Evento</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">${description}</p>
                    ${requirements ? `
                        <hr>
                        <h6 class="text-primary">Requisiti:</h6>
                        <p class="mb-0">${requirements}</p>
                    ` : ''}
                </div>
            </div>

            <!-- Timeline -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="ph ph-clock me-2"></i>Cronologia Evento</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border-start border-success border-4 ps-3">
                                <h6 class="mb-1 text-success">Inizio Evento</h6>
                                <p class="mb-0">${formatDate(startDateTime)}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border-start border-danger border-4 ps-3">
                                <h6 class="mb-1 text-danger">Fine Evento</h6>
                                <p class="mb-0">${formatDate(endDateTime)}</p>
                            </div>
                        </div>
                        ${duration ? `
                            <div class="col-12">
                                <div class="border-start border-info border-4 ps-3">
                                    <h6 class="mb-1 text-info">Durata</h6>
                                    <p class="mb-0">${duration}</p>
                                </div>
                            </div>
                        ` : ''}
                        ${registrationDeadline ? `
                            <div class="col-md-6">
                                <div class="border-start border-warning border-4 ps-3">
                                    <h6 class="mb-1 text-warning">Scadenza Iscrizioni</h6>
                                    <p class="mb-0">${formatDateOnly(registrationDeadline)}</p>
                                </div>
                            </div>
                        ` : ''}
                        ${invitationDeadline ? `
                            <div class="col-md-6">
                                <div class="border-start border-warning border-4 ps-3">
                                    <h6 class="mb-1 text-warning">Scadenza Inviti</h6>
                                    <p class="mb-0">${formatDateOnly(invitationDeadline)}</p>
                                </div>
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>

            <!-- Location Details -->
            ${!isOnline ? `
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ph ph-map-pin me-2"></i>Dettagli Luogo</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            ${venueName ? `
                                <div class="col-md-6">
                                    <strong>Venue:</strong> ${venueName}
                                </div>
                            ` : ''}
                            ${venueAddress ? `
                                <div class="col-md-6">
                                    <strong>Indirizzo:</strong> ${venueAddress}
                                </div>
                            ` : ''}
                            ${city ? `
                                <div class="col-md-4">
                                    <strong>Città:</strong> ${city}
                                </div>
                            ` : ''}
                            ${postcode ? `
                                <div class="col-md-4">
                                    <strong>CAP:</strong> ${postcode}
                                </div>
                            ` : ''}
                            ${country ? `
                                <div class="col-md-4">
                                    <strong>Paese:</strong> ${country}
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            ` : ''}

            <!-- Event Settings -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="ph ph-gear me-2"></i>Impostazioni Evento</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="border-start border-primary border-4 ps-3">
                                <h6 class="mb-1 text-primary">Costo Ingresso</h6>
                                <p class="mb-0">${entryFee == 0 ? '{{ __('common.free') }}' : '€' + entryFee}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border-start border-info border-4 ps-3">
                                <h6 class="mb-1 text-info">Tipo Evento</h6>
                                <p class="mb-0">${isPublic ? 'Aperto a tutti' : 'Solo su invito'}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border-start border-success border-4 ps-3">
                                <h6 class="mb-1 text-success">Stato</h6>
                                <p class="mb-0">${status === 'published' ? 'Pubblicato' : 'Bozza'}</p>
                            </div>
                        </div>
                        ${maxParticipants ? `
                            <div class="col-md-4">
                                <div class="border-start border-warning border-4 ps-3">
                                    <h6 class="mb-1 text-warning">Max Partecipanti</h6>
                                    <p class="mb-0">${maxParticipants}</p>
                                </div>
                            </div>
                        ` : ''}
                        <div class="col-md-4">
                            <div class="border-start border-secondary border-4 ps-3">
                                <h6 class="mb-1 text-secondary">Richieste</h6>
                                <p class="mb-0">${allowRequests ? 'Accettate' : 'Non accettate'}</p>
                            </div>
                        </div>
                        ${isRecurring ? `
                            <div class="col-md-4">
                                <div class="border-start border-purple border-4 ps-3">
                                    <h6 class="mb-1 text-purple">Ricorrenza</h6>
                                    <p class="mb-0">${recurrenceType} - ${recurrenceCount} volte</p>
                                </div>
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>

            <!-- Group Association -->
            ${isLinkedToGroup && selectedGroupName ? `
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ph ph-users me-2 text-success"></i>Gruppo Associato</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="ph ph-users text-success me-3 f-s-24"></i>
                            <div>
                                <h6 class="mb-1 text-success">${selectedGroupName}</h6>
                                <small class="text-muted">Questo evento è associato a questo gruppo</small>
                            </div>
                        </div>
                    </div>
                </div>
            ` : ''}

            <!-- Festival Information -->
            ${isFestival && festivalEvents.length > 0 ? `
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ph ph-trophy me-2"></i>Eventi del Festival</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Questo festival include ${festivalEvents.length} evento${festivalEvents.length === 1 ? '' : 'i'}:</p>
                        <div class="row g-2">
                            ${festivalEvents.map(event => `
                                <div class="col-md-6">
                                    <div class="card card-light-primary border-0">
                                        <div class="card-body p-2">
                                            <div class="d-flex align-items-center">
                                                <i class="ph ph-calendar me-2 text-primary"></i>
                                                <div>
                                                    <strong>${event.title}</strong>
                                                    <br><small class="text-muted">${event.date} - ${event.venue}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            ` : ''}

            ${isPartOfFestival && festivalId ? `
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ph ph-trophy me-2"></i>Parte di un Festival</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">Questo evento fa parte di un festival più grande.</p>
                    </div>
                </div>
            ` : ''}

            <!-- Gig Positions -->
            ${gigPositions.length > 0 ? `
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ph ph-briefcase me-2"></i>Posizioni d'Ingaggio Aperte</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            ${gigPositions.map(position => `
                                <div class="col-md-6">
                                    <div class="card card-light-success border-0">
                                        <div class="card-body p-2">
                                            <div class="d-flex align-items-center">
                                                <i class="ph ph-user-circle me-2 text-success"></i>
                                                <div>
                                                    <strong>${position.type}</strong>
                                                    <br><small class="text-muted">${position.quantity} posizione${position.quantity === 1 ? '' : 'i'}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            ` : ''}

            <!-- Invitations -->
            ${invitations.length > 0 ? `
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ph ph-user-plus me-2"></i>Inviti Specifici</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            ${invitations.map(invitation => `
                                <div class="col-md-6">
                                    <div class="card card-light-info border-0">
                                        <div class="card-body p-2">
                                            <div class="d-flex align-items-center">
                                                <i class="ph ph-user me-2 text-info"></i>
                                                <div>
                                                    <strong>${invitation.user_name}</strong>
                                                    <br><small class="text-muted">${invitation.role}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            ` : ''}

            <!-- Private Event Invitations -->
            ${!isPublic && privateInvitedUsers.length > 0 ? `
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ph ph-users me-2"></i>Utenti Invitati</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            ${privateInvitedUsers.map(user => `
                                <div class="col-md-6">
                                    <div class="card card-light-warning border-0">
                                        <div class="card-body p-2">
                                            <div class="d-flex align-items-center">
                                                <i class="ph ph-user me-2 text-warning"></i>
                                                <div>
                                                    <strong>${user.name}</strong>
                                                    <br><small class="text-muted">${user.email}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            ` : ''}

            <!-- Tags -->
            ${tags.length > 0 ? `
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ph ph-tag me-2"></i>Tags</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-1">
                            ${tags.map(tag => `<span class="badge bg-light-primary rounded px-3 py-1">#${tag}</span>`).join('')}
                        </div>
                    </div>
                </div>
            ` : ''}

            <!-- Artist Invitations -->
            ${artistInvitedUsers.length > 0 ? `
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ph ph-user-circle-plus me-2"></i>Artisti Invitati</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            ${artistInvitedUsers.map(user => `
                                <div class="col-md-6">
                                    <div class="card card-light-primary border-0">
                                        <div class="card-body p-2">
                                            <div class="d-flex align-items-center">
                                                <i class="ph ph-user-circle me-2 text-primary"></i>
                                                <div>
                                                    <strong>${user.name}</strong>
                                                    <br><small class="text-muted">${user.role || 'Artista'}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            ` : ''}
        </div>
    `;

    const eventPreviewElement = document.getElementById('eventPreview');
    if (eventPreviewElement) {
        eventPreviewElement.innerHTML = preview;
    } else {
        // L'elemento eventPreview potrebbe non esistere in tutte le pagine
        // Non è un errore critico, quindi rimuoviamo il console.error
        // console.error('eventPreview element not found');
    }
}

function updatePreviewWithImage(imageSrc) {
    if (currentStep !== 5) return;

    // Get all form values with safe element access (same as updatePreview)
    const title = document.getElementById('title')?.value || 'Titolo {{ __('invitations.event') }}';
    const description = document.getElementById('description')?.value || 'Descrizione evento...';
    const requirements = document.getElementById('requirements')?.value || '';
    const category = document.getElementById('category')?.value || '';
    const categoryText = category && document.getElementById('category')?.options ?
                document.getElementById('category').options[document.getElementById('category').selectedIndex]?.text || '' :
                '';

    // Date and time
    const startDateTime = document.getElementById('start_datetime')?.value || '';
    const endDateTime = document.getElementById('end_datetime')?.value || '';

    // Registration deadline from new fields
    const hasRegistrationDeadline = document.getElementById('has_deadline')?.checked || false;
    const registrationDeadlineDate = document.getElementById('registrationDeadlineDate')?.value || '';
    const registrationDeadlineTime = document.getElementById('registrationDeadlineTime')?.value || '';
    const registrationDeadline = hasRegistrationDeadline && registrationDeadlineDate && registrationDeadlineTime ?
        registrationDeadlineDate + ' ' + registrationDeadlineTime : '';

    const invitationDeadline = document.getElementById('invitation_deadline')?.value || '';

    // Location
    const venueName = document.getElementById('venue_name')?.value || '';
    const venueAddress = document.getElementById('venue_address')?.value || '';
    const city = document.getElementById('city')?.value || '';
    const postcode = document.getElementById('postcode')?.value || '';
    const country = document.getElementById('country')?.value || '';
    const isOnline = document.getElementById('is_online')?.checked || false;
    const onlineUrl = document.getElementById('online_url')?.value || '';
    const timezone = document.getElementById('timezone')?.value || '';

    // Availability settings
    const isAvailabilityBased = document.getElementById('is_availability_based')?.checked || false;
    const availabilityDeadline = document.getElementById('availability_deadline')?.value || '';
    const availabilityInstructions = document.getElementById('availability_instructions')?.value || '';

    // Event settings
    const entryFee = document.getElementById('ticket_price')?.value || '0';
    const maxParticipants = document.getElementById('max_participants')?.value || '';
    const isPublicRadio = document.querySelector('input[name="is_public"]:checked');
    const isPublic = isPublicRadio ? isPublicRadio.value === '1' : true;
    const allowRequests = document.getElementById('allow_requests')?.checked || false;
    const statusRadio = document.querySelector('input[name="status"]:checked');
    const status = statusRadio ? statusRadio.value : 'published';

    // Festival data
    const isFestival = category === 'festival';
    const selectedFestivalEventsData = document.getElementById('selectedFestivalEventsData');
    const festivalEvents = selectedFestivalEventsData ? JSON.parse(selectedFestivalEventsData.value || '[]') : [];
    const isPartOfFestival = document.getElementById('is_festival_event')?.checked || false;
    const festivalId = document.getElementById('festival_id')?.value || '';

    // Recurrence
    const isRecurring = document.getElementById('is_recurring')?.checked || false;
    const recurrenceType = document.getElementById('recurrence_type')?.value || '';
    const recurrenceInterval = document.getElementById('recurrence_interval')?.value || '';
    const recurrenceCount = document.getElementById('recurrence_count')?.value || '';

    // Gig positions
    const gigPositionsData = document.getElementById('gigPositionsData');
    const gigPositions = gigPositionsData ? JSON.parse(gigPositionsData.value || '[]') : [];

    // Group association
    const isLinkedToGroup = document.getElementById('is_linked_to_group')?.checked || false;
    const groupCheckboxes = document.querySelectorAll('.group-checkbox:checked');
    const selectedGroupNames = Array.from(groupCheckboxes).map(cb => {
        const label = document.querySelector(`label[for="${cb.id}"]`);
        return label ? label.textContent.trim() : '';
    }).filter(name => name);

    // Invitations
    const privateInvitedUsersData = document.getElementById('privateInvitedUsersData');
    const privateInvitedUsers = privateInvitedUsersData ? JSON.parse(privateInvitedUsersData.value || '[]') : [];
    const artistInvitedUsersData = document.getElementById('artistInvitedUsersData');
    const artistInvitedUsers = artistInvitedUsersData ? JSON.parse(artistInvitedUsersData.value || '[]') : [];
    const invitationsData = document.getElementById('invitationsData');
    const invitations = invitationsData ? JSON.parse(invitationsData.value || '[]') : [];

    // Format dates
    const formatDate = (dateString) => {
        if (!dateString) return 'Non specificato';
        return new Date(dateString).toLocaleDateString('it-IT', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    };

    const formatTime = (dateString) => {
        if (!dateString) return 'Non specificato';
        return new Date(dateString).toLocaleTimeString('it-IT', {
            hour: '2-digit',
            minute: '2-digit'
        });
    };

    const formatDateOnly = (dateString) => {
        if (!dateString) return 'Non specificato';
        return new Date(dateString).toLocaleDateString('it-IT', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    };

    // Calculate duration
    let duration = '';
    if (startDateTime && endDateTime) {
        const start = new Date(startDateTime);
        const end = new Date(endDateTime);
        const diffHours = Math.round((end - start) / (1000 * 60 * 60));
        duration = `${diffHours} ${diffHours === 1 ? 'ora' : 'ore'}`;
    }

    const preview = `
        <!-- Hero Section with Image -->
        <div class="position-relative overflow-hidden" style="height: 300px;">
            <img src="${imageSrc}" alt="${title}" class="position-absolute w-100 h-100" style="object-fit: cover;">
            <div class="position-absolute w-100 h-100 bg-primary" style="opacity: 0.7;"></div>

            <!-- Status Badges -->
            <div class="position-absolute top-0 start-0 m-3" style="z-index: 3;">
                <span class="badge bg-light-primary me-2">
                    <i class="ph ph-tag me-1"></i>${categoryText || 'Categoria non specificata'}
                </span>
                ${isFestival ? '<span class="badge bg-warning"><i class="ph ph-trophy me-1"></i>Festival</span>' : ''}
            </div>

            <span class="badge ${isPublic ? 'bg-light-success' : 'bg-light-warning'} position-absolute top-0 end-0 m-3" style="z-index: 3;">
                <i class="ph ph-${isPublic ? 'globe' : 'lock'} me-1"></i>
                ${isPublic ? 'Pubblico' : 'Privato'}
            </span>

            <div class="position-absolute bottom-0 start-0 text-white p-4 w-100" style="z-index: 3;">
                <h2 class="fw-bold mb-3 text-white">${title}</h2>
                <!-- Gruppi selezionati -->
                ${isLinkedToGroup && selectedGroupNames.length > 0 ? `
                    <div class="mb-2">
                        ${selectedGroupNames.map(name => `<span class=\"badge bg-light-primary me-2\">${name}</span>`).join(' ')}
                    </div>
                ` : ''}
                <div class="d-flex align-items-center mb-2">
                    <i class="ph ph-calendar-check me-2 fs-5"></i>
                    <span class="fs-5">${formatDate(startDateTime)}</span>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <i class="ph ph-map-pin me-2 fs-5"></i>
                    <span class="fs-5">
                        ${isOnline ?
                            '<i class="ph ph-globe me-1"></i>Evento Online' + (onlineUrl ? ` - ${onlineUrl}` : '') :
                            `${venueName ? venueName + ', ' : ''}${city}${country ? ', ' + country : ''}`
                        }
                    </span>
                </div>
                ${isOnline && timezone ? `
                    <div class="d-flex align-items-center">
                        <i class="ph ph-clock me-2 fs-5"></i>
                        <span class="fs-5">Fuso orario: ${timezone}</span>
                    </div>
                ` : ''}
            </div>
        </div>

        <!-- Event Details -->
        <div class="p-4">
            <!-- Description -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="ph ph-file-text me-2"></i>Descrizione Evento</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">${description}</p>
                    ${requirements ? `
                        <hr>
                        <h6 class="text-primary">Requisiti:</h6>
                        <p class="mb-0">${requirements}</p>
                    ` : ''}
                </div>
            </div>

            <!-- Timeline -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="ph ph-clock me-2"></i>Cronologia Evento</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border-start border-success border-4 ps-3">
                                <h6 class="mb-1 text-success">Inizio Evento</h6>
                                <p class="mb-0">${formatDate(startDateTime)}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border-start border-danger border-4 ps-3">
                                <h6 class="mb-1 text-danger">Fine Evento</h6>
                                <p class="mb-0">${formatDate(endDateTime)}</p>
                            </div>
                        </div>
                        ${duration ? `
                            <div class="col-12">
                                <div class="border-start border-info border-4 ps-3">
                                    <h6 class="mb-1 text-info">Durata</h6>
                                    <p class="mb-0">${duration}</p>
                                </div>
                            </div>
                        ` : ''}
                        ${registrationDeadline ? `
                            <div class="col-md-6">
                                <div class="border-start border-warning border-4 ps-3">
                                    <h6 class="mb-1 text-warning">Scadenza Iscrizioni</h6>
                                    <p class="mb-0">${formatDateOnly(registrationDeadline)}</p>
                                </div>
                            </div>
                        ` : ''}
                        ${invitationDeadline ? `
                            <div class="col-md-6">
                                <div class="border-start border-warning border-4 ps-3">
                                    <h6 class="mb-1 text-warning">Scadenza Inviti</h6>
                                    <p class="mb-0">${formatDateOnly(invitationDeadline)}</p>
                                </div>
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>

            <!-- Location Details -->
            ${!isOnline ? `
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ph ph-map-pin me-2"></i>Dettagli Luogo</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            ${venueName ? `
                                <div class="col-md-6">
                                    <strong>Venue:</strong> ${venueName}
                                </div>
                            ` : ''}
                            ${venueAddress ? `
                                <div class="col-md-6">
                                    <strong>Indirizzo:</strong> ${venueAddress}
                                </div>
                            ` : ''}
                            ${city ? `
                                <div class="col-md-4">
                                    <strong>Città:</strong> ${city}
                                </div>
                            ` : ''}
                            ${postcode ? `
                                <div class="col-md-4">
                                    <strong>CAP:</strong> ${postcode}
                                </div>
                            ` : ''}
                            ${country ? `
                                <div class="col-md-4">
                                    <strong>Paese:</strong> ${country}
                                </div>
                            ` : ''}
                        </div>
                    </div>
                </div>
            ` : ''}

            <!-- Event Settings -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="ph ph-gear me-2"></i>Impostazioni Evento</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="border-start border-primary border-4 ps-3">
                                <h6 class="mb-1 text-primary">Costo Ingresso</h6>
                                <p class="mb-0">${entryFee == 0 ? '{{ __('common.free') }}' : '€' + entryFee}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border-start border-info border-4 ps-3">
                                <h6 class="mb-1 text-info">Tipo Evento</h6>
                                <p class="mb-0">${isPublic ? 'Aperto a tutti' : 'Solo su invito'}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border-start border-success border-4 ps-3">
                                <h6 class="mb-1 text-success">Stato</h6>
                                <p class="mb-0">${status === 'published' ? 'Pubblicato' : 'Bozza'}</p>
                            </div>
                        </div>
                        ${maxParticipants ? `
                            <div class="col-md-4">
                                <div class="border-start border-warning border-4 ps-3">
                                    <h6 class="mb-1 text-warning">Max Partecipanti</h6>
                                    <p class="mb-0">${maxParticipants}</p>
                                </div>
                            </div>
                        ` : ''}
                        <div class="col-md-4">
                            <div class="border-start border-secondary border-4 ps-3">
                                <h6 class="mb-1 text-secondary">Richieste</h6>
                                <p class="mb-0">${allowRequests ? 'Accettate' : 'Non accettate'}</p>
                            </div>
                        </div>
                        ${isRecurring ? `
                            <div class="col-md-4">
                                <div class="border-start border-purple border-4 ps-3">
                                    <h6 class="mb-1 text-purple">Ricorrenza</h6>
                                    <p class="mb-0">${recurrenceType} - ${recurrenceCount} volte</p>
                                </div>
                            </div>
                        ` : ''}
                    </div>
                </div>
            </div>

            <!-- Group Association -->
            ${isLinkedToGroup && selectedGroupNames.length > 0 ? `
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ph ph-users me-2 text-success"></i>Gruppi Associati</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="ph ph-users text-success me-3 f-s-24"></i>
                            <div>
                                ${selectedGroupNames.map(name => `<span class=\"badge bg-light-primary me-2\">${name}</span>`).join(' ')}
                                <br><small class="text-muted">Questo evento è associato a ${selectedGroupNames.length} gruppo${selectedGroupNames.length === 1 ? '' : 'i'}</small>
                            </div>
                        </div>
                    </div>
                </div>
            ` : ''}

            <!-- Festival Information -->
            ${isFestival && festivalEvents.length > 0 ? `
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ph ph-trophy me-2"></i>Eventi del Festival</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Questo festival include ${festivalEvents.length} evento${festivalEvents.length === 1 ? '' : 'i'}:</p>
                        <div class="row g-2">
                            ${festivalEvents.map(event => `
                                <div class="col-md-6">
                                    <div class="card card-light-primary border-0">
                                        <div class="card-body p-2">
                                            <div class="d-flex align-items-center">
                                                <i class="ph ph-calendar me-2 text-primary"></i>
                                                <div>
                                                    <strong>${event.title}</strong>
                                                    <br><small class="text-muted">${event.date} - ${event.venue}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            ` : ''}

            ${isPartOfFestival && festivalId ? `
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ph ph-trophy me-2"></i>Parte di un Festival</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">Questo evento fa parte di un festival più grande.</p>
                    </div>
                </div>
            ` : ''}

            <!-- Gig Positions -->
            ${gigPositions.length > 0 ? `
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ph ph-briefcase me-2"></i>Posizioni d'Ingaggio Aperte</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            ${gigPositions.map(position => `
                                <div class="col-md-6">
                                    <div class="card card-light-success border-0">
                                        <div class="card-body p-2">
                                            <div class="d-flex align-items-center">
                                                <i class="ph ph-user-circle me-2 text-success"></i>
                                                <div>
                                                    <strong>${position.type}</strong>
                                                    <br><small class="text-muted">${position.quantity} posizione${position.quantity === 1 ? '' : 'i'}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            ` : ''}

            <!-- Invitations -->
            ${invitations.length > 0 ? `
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ph ph-user-plus me-2"></i>Inviti Specifici</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            ${invitations.map(invitation => `
                                <div class="col-md-6">
                                    <div class="card card-light-info border-0">
                                        <div class="card-body p-2">
                                            <div class="d-flex align-items-center">
                                                <i class="ph ph-user me-2 text-info"></i>
                                                <div>
                                                    <strong>${invitation.user_name}</strong>
                                                    <br><small class="text-muted">${invitation.role}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            ` : ''}

            <!-- Private Event Invitations -->
            ${!isPublic && privateInvitedUsers.length > 0 ? `
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ph ph-users me-2"></i>Utenti Invitati</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            ${privateInvitedUsers.map(user => `
                                <div class="col-md-6">
                                    <div class="card card-light-warning border-0">
                                        <div class="card-body p-2">
                                            <div class="d-flex align-items-center">
                                                <i class="ph ph-user me-2 text-warning"></i>
                                                <div>
                                                    <strong>${user.name}</strong>
                                                    <br><small class="text-muted">${user.email}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            ` : ''}

            <!-- Tags -->
            ${tags.length > 0 ? `
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ph ph-tag me-2"></i>Tags</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-1">
                            ${tags.map(tag => `<span class="badge bg-light-primary rounded px-3 py-1">#${tag}</span>`).join('')}
                        </div>
                    </div>
                </div>
            ` : ''}

            <!-- Artist Invitations -->
            ${artistInvitedUsers.length > 0 ? `
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="ph ph-user-circle-plus me-2"></i>Artisti Invitati</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            ${artistInvitedUsers.map(user => `
                                <div class="col-md-6">
                                    <div class="card card-light-primary border-0">
                                        <div class="card-body p-2">
                                            <div class="d-flex align-items-center">
                                                <i class="ph ph-user-circle me-2 text-primary"></i>
                                                <div>
                                                    <strong>${user.name}</strong>
                                                    <br><small class="text-muted">${user.role || 'Artista'}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                </div>
            ` : ''}
        </div>
    `;

    const eventPreviewElement = document.getElementById('eventPreview');
    if (eventPreviewElement) {
        eventPreviewElement.innerHTML = preview;
    } else {
        // L'elemento eventPreview potrebbe non esistere in tutte le pagine
        // Non è un errore critico, quindi rimuoviamo il console.error
        // console.error('eventPreview element not found');
    }
}

    // Disable form submission on Enter key
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
                    e.preventDefault();
                    return false;
                }
            });
        }
    });

    // Search users for invitations
    function searchUsers() {
    const query = document.getElementById('userSearch').value.trim();
    if (query.length < 2) {
        Swal.fire({
            icon: 'info',
            title: 'Ricerca Troppo Breve',
            text: 'Inserisci almeno 2 caratteri per la ricerca',
            confirmButtonColor: 'var(--theme-default)'
        });
        return;
    }

    // Simulated search - in production this would be an AJAX call
    fetch(`/api/users/search?q=${encodeURIComponent(query)}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
        .then(response => response.json())
        .then(data => {
            displaySearchResults(data);
        })
        .catch(error => {
            console.error('Error searching users:', error);
            // Fallback with demo data for testing
                    const demoUsers = [{
                            id: 1,
                            name: 'Marco Poeta',
                            email: 'marco@poetry.it',
                            roles: ['poet'],
                            avatar: null
                        },
                        {
                            id: 2,
                            name: 'Sofia Judge',
                            email: 'sofia@slam.it',
                            roles: ['judge'],
                            avatar: null
                        },
                        {
                            id: 3,
                            name: 'Alex Tech',
                            email: 'alex@tech.it',
                            roles: ['technician'],
                            avatar: null
                        }
            ].filter(user =>
                user.name.toLowerCase().includes(query.toLowerCase()) ||
                user.email.toLowerCase().includes(query.toLowerCase())
            );
            displaySearchResults(demoUsers);
        });
}

function displaySearchResults(users) {
    const resultsContainer = document.getElementById('searchResults');
    const resultsList = document.getElementById('searchResultsList');

    if (users.length === 0) {
        resultsList.innerHTML = '<div class="list-group-item text-center text-muted">Nessun utente trovato</div>';
        resultsContainer.style.display = 'block';
        return;
    }

    resultsList.innerHTML = users.map(user => `
        <div class="list-group-item list-group-item-action">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">${user.name}</h6>
                    <small class="text-muted">${user.email}</small>
                    <div class="mt-1">
                        ${user.roles.map(role => `<span class="badge bg-secondary me-1">${getRoleDisplayName(role)}</span>`).join('')}
                    </div>
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addInvitation(${user.id}, '${user.name.replace(/'/g, "\\'")}', '${user.email.replace(/'/g, "\\'")}', ['${user.roles.join("','")}'])">
                    <i class="ph ph-plus"></i> Invita
                </button>
            </div>
        </div>
    `).join('');

    resultsContainer.style.display = 'block';
}

function getRoleDisplayName(role) {
    const roleNames = {
        'poet': 'Poeta',
        'judge': 'Giudice',
        'organizer': '{{ __('events.organizer') }}',
        'technician': 'Tecnico',
        'audience': 'Pubblico'
    };
    return roleNames[role] || role;
}

let pendingInvitation = null;

function addInvitation(userId, userName, userEmail, userRoles) {
    // Check if user is already invited
    if (selectedInvitations.find(inv => inv.user_id === userId)) {
        Swal.fire({
            icon: 'info',
            title: 'Già Invitato',
            text: 'Questo utente è già stato invitato all\'evento',
            confirmButtonColor: 'var(--theme-default)'
        });
        return;
    }

    // Store pending invitation data
    pendingInvitation = {
        user_id: userId,
        name: userName,
        email: userEmail,
        roles: userRoles
    };

    // Populate modal with user data
    document.getElementById('selectedUserName').textContent = userName;
    document.getElementById('selectedUserEmail').textContent = userEmail;
            document.getElementById('selectedUserInitials').textContent = userName.split(' ').map(n => n[0]).join('')
                .toUpperCase();

    // Reset form
    document.querySelector('input[name="invitationRole"][value="performer"]').checked = true;
    document.getElementById('invitationMessage').value = '';

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('roleSelectionModal'));
    modal.show();
}

function confirmInvitation() {
    const selectedRole = document.querySelector('input[name="invitationRole"]:checked').value;
    const message = document.getElementById('invitationMessage').value.trim();

    const invitation = {
        user_id: pendingInvitation.user_id,
        name: pendingInvitation.name,
        email: pendingInvitation.email,
        role: selectedRole,
                message: message ||
                    `Ciao ${pendingInvitation.name}, sei invitato al nostro evento Poetry Slam come ${getRoleDisplayName(selectedRole)}!`
    };

    selectedInvitations.push(invitation);
    updateInvitationsList();
    updateInvitationsData();

    // Hide modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('roleSelectionModal'));
    modal.hide();

    // Show success message
    Swal.fire({
        icon: 'success',
        title: 'Invito Aggiunto!',
        text: `${pendingInvitation.name} è stato aggiunto alla lista degli inviti`,
        timer: 2000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });

    pendingInvitation = null;
}

function removeInvitation(userId) {
    const invitation = selectedInvitations.find(inv => inv.user_id === userId);
    if (!invitation) return;

    Swal.fire({
        title: '{{ __('events.remove_invitation_title') }}',
        text: `{{ __('events.remove_invitation_confirm') }} ${invitation.name}?`,
        icon: 'question',
        showCancelButton: true,
                confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '{{ __('events.yes_remove') }}',
        cancelButtonText: '{{ __('common.cancel') }}'
    }).then((result) => {
        if (result.isConfirmed) {
            selectedInvitations = selectedInvitations.filter(inv => inv.user_id !== userId);
            updateInvitationsList();
            updateInvitationsData();

            Swal.fire({
                icon: 'success',
                title: '{{ __('events.invitation_removed') }}',
                text: `{{ __('events.invitation_removed_message') }} ${invitation.name} {{ __('events.has_been_removed') }}`,
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }
    });
}

function updateInvitationsList() {
    const container = document.getElementById('invitationsList');
    const countBadge = document.getElementById('invitationCount');
    const noInvitations = document.getElementById('noInvitations');

    countBadge.textContent = selectedInvitations.length;

    if (selectedInvitations.length === 0) {
        noInvitations.style.display = 'block';
        return;
    }

    noInvitations.style.display = 'none';
    container.innerHTML = selectedInvitations.map(inv => `
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="card-title mb-1">${inv.name}</h6>
                            <small class="text-muted">${inv.email}</small>
                            <div class="mt-2">
                                <span class="badge bg-primary">${getRoleDisplayName(inv.role)}</span>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeInvitation(${inv.user_id})">
                            <i class="ph ph-x"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

function updateInvitationsData() {
    document.getElementById('invitationsData').value = JSON.stringify(selectedInvitations);
}

// Enhanced form submission
document.getElementById('eventForm').addEventListener('submit', function(e) {
    console.log('Form submit event triggered on mobile/desktop');
    e.preventDefault(); // Prevent default submission

    // Validate dates
    const startDateTime = document.getElementById('start_datetime').value;
    const endDateTime = document.getElementById('end_datetime').value;

    const now = new Date();
    const startDate = startDateTime ? new Date(startDateTime.replace(' ', 'T')) : null;
    const endDate = endDateTime ? new Date(endDateTime.replace(' ', 'T')) : null;

    let hasErrors = false;

    // Clear previous errors and styling
    document.querySelectorAll('.error-feedback').forEach(el => el.textContent = '');
    document.querySelectorAll('.form-control.is-invalid').forEach(el => {
        el.classList.remove('is-invalid');
        el.classList.add('is-valid');
    });

        // Validate start datetime (only required for non-availability-based events)
        const isAvailabilityBased = document.getElementById('is_availability_based')?.checked || false;
            console.log('Form validation - isAvailabilityBased:', isAvailabilityBased, 'startDateTime:',
                startDateTime, 'endDateTime:', endDateTime);
        if (!startDateTime && !isAvailabilityBased) {
                document.getElementById('start_datetime-error').textContent =
                    '{{ __('events.start_datetime_required') }}';
            document.getElementById('start_datetime').classList.add('is-invalid');
            document.getElementById('start_datetime').classList.remove('is-valid');
            hasErrors = true;
    } else if (startDate && startDate <= now) {
                document.getElementById('start_datetime-error').textContent =
                    '{{ __('events.start_datetime_future') }}';
        document.getElementById('start_datetime').classList.add('is-invalid');
        document.getElementById('start_datetime').classList.remove('is-valid');
        hasErrors = true;
    } else {
        document.getElementById('start_datetime').classList.remove('is-invalid');
        document.getElementById('start_datetime').classList.add('is-valid');
    }

    // Validate end datetime (only required for non-availability-based events)
    if (!endDateTime && !isAvailabilityBased) {
                document.getElementById('end_datetime-error').textContent =
                    '{{ __('events.end_datetime_required') }}';
        document.getElementById('end_datetime').classList.add('is-invalid');
        document.getElementById('end_datetime').classList.remove('is-valid');
        hasErrors = true;
    } else if (startDate && endDate && endDate <= startDate) {
                document.getElementById('end_datetime-error').textContent =
                    '{{ __('events.end_datetime_after_start') }}';
        document.getElementById('end_datetime').classList.add('is-invalid');
        document.getElementById('end_datetime').classList.remove('is-valid');
        hasErrors = true;
    } else {
        document.getElementById('end_datetime').classList.remove('is-invalid');
        document.getElementById('end_datetime').classList.add('is-valid');
    }

    if (hasErrors) {
        // Scroll to first error
        const firstError = document.querySelector('.form-control.is-invalid');
        if (firstError) {
                    firstError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
            firstError.focus();
        }

        // Show error alert
        Swal.fire({
            icon: 'error',
            title: '{{ __('events.validation_error') }}',
            text: '{{ __('events.validation_error_message') }}',
            confirmButtonText: '{{ __('common.ok') }}'
        });
        return;
    }

    const submitBtn = document.getElementById('submitBtn');
    const submitStatus = document.getElementById('submitStatus');

    // Clear localStorage draft since we're submitting
    localStorage.removeItem('eventDraft');


    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="ph ph-spinner-gap me-2"></i>{{ __('events.creating') }}';
    submitStatus.style.display = 'block';

    // Submit the form
    console.log('About to submit form');
    this.submit();
});

// Add specific event listener for submit button for mobile debugging
document.addEventListener('DOMContentLoaded', function() {
    const submitBtn = document.getElementById('submitBtn');
    if (submitBtn) {
        submitBtn.addEventListener('click', function(e) {
            console.log('Submit button clicked on mobile/desktop');
        });

        submitBtn.addEventListener('touchend', function(e) {
            console.log('Submit button touched on mobile');
            // Force form submission on mobile
            e.preventDefault();
            e.stopPropagation();
            const form = document.getElementById('eventForm');
            if (form) {
                console.log('Forcing form submission on mobile');
                        form.dispatchEvent(new Event('submit', {
                            bubbles: true,
                            cancelable: true
                        }));
            }
        });
    }
});

function startAutoSave() {
    setInterval(() => {
        const formData = new FormData(document.getElementById('eventForm'));
        const data = Object.fromEntries(formData.entries());

        // Ensure radio buttons and checkboxes are properly saved
        const isPublicRadio = document.querySelector('input[name="is_public"]:checked');
        if (isPublicRadio) {
            data.is_public = isPublicRadio.value;
        }

        // Ensure availability fields are properly saved
        const isAvailabilityBasedCheckbox = document.getElementById('is_availability_based');
        if (isAvailabilityBasedCheckbox) {
            data.is_availability_based = isAvailabilityBasedCheckbox.checked ? '1' : '0';
        }

        // Save to localStorage
        localStorage.setItem('eventDraft', JSON.stringify(data));

        document.getElementById('autosaveStatus').innerHTML =
            '<i class="ph ph-check me-1"></i>{{ __('events.saved') }} ' + new Date().toLocaleTimeString();
    }, 30000); // Save every 30 seconds
}

// Load draft on page load
window.addEventListener('load', function() {
    const draft = localStorage.getItem('eventDraft');
    if (draft) {
        try {
            const data = JSON.parse(draft);
            Object.keys(data).forEach(key => {
                // Handle radio buttons specially
                if (key === 'is_public') {
                            const radioButton = document.querySelector(
                                `input[name="is_public"][value="${data[key]}"]`);
                    if (radioButton) {
                        radioButton.checked = true;
                    }
                    return;
                }

                // Handle availability checkbox specially
                if (key === 'is_availability_based') {
                    const checkbox = document.getElementById('is_availability_based');
                    if (checkbox) {
                        checkbox.checked = data[key] === '1';
                        // Trigger change event to show/hide availability settings
                        checkbox.dispatchEvent(new Event('change'));
                    }
                    return;
                }

                // Handle other form elements
                const element = document.getElementById(key);
                if (element && element.type !== 'file') {
                    element.value = data[key];
                }
            });
        } catch (e) {
            console.error('Error loading draft:', e);
        }
    }

    // Clear localStorage if we're on a success page (via URL parameter or session)
            @if (session('success'))
        localStorage.removeItem('eventDraft');
    @endif
});

// Initialize Flatpickr for date/time inputs
document.addEventListener('DOMContentLoaded', function() {
    // Start datetime picker
    flatpickr("#start_datetime", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: "today",
        minTime: "00:00",
        time_24hr: true,
        onChange: function(selectedDates, dateStr, instance) {
            // Update end datetime minimum date
            if (endDateTimePicker) {
                endDateTimePicker.set('minDate', selectedDates[0]);
            }
            // Clear error when valid date is selected
            document.getElementById('start_datetime-error').textContent = '';
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
    const endDateTimePicker = flatpickr("#end_datetime", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: "today",
        minTime: "00:00",
        time_24hr: true,
        onChange: function(selectedDates, dateStr, instance) {
            // Clear error when valid date is selected
            document.getElementById('end_datetime-error').textContent = '';
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

    // Registration deadline date picker
    const registrationDeadlineDatePicker = flatpickr("#registrationDeadlineDate", {
        dateFormat: "Y-m-d",
        minDate: "today",
        onChange: function(selectedDates, dateStr, instance) {
            // Update time picker minimum date
            if (registrationDeadlineTimePicker) {
                registrationDeadlineTimePicker.set('minDate', selectedDates[0]);
            }
        }
    });

    // Registration deadline time picker
    const registrationDeadlineTimePicker = flatpickr("#registrationDeadlineTime", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        minTime: "00:00",
        maxTime: "23:59"
    });

    // Setup registration deadline radio buttons
    const hasDeadlineRadio = document.getElementById('has_deadline');
    const noDeadlineRadio = document.getElementById('no_deadline');
    const deadlinePicker = document.getElementById('registrationDeadlinePicker');

    if (hasDeadlineRadio && noDeadlineRadio && deadlinePicker) {
        hasDeadlineRadio.addEventListener('change', function() {
            if (this.checked) {
                deadlinePicker.style.display = 'block';
            }
        });

        noDeadlineRadio.addEventListener('change', function() {
            if (this.checked) {
                deadlinePicker.style.display = 'none';
                // Clear the date/time inputs
                if (registrationDeadlineDatePicker) {
                    registrationDeadlineDatePicker.clear();
                }
                if (registrationDeadlineTimePicker) {
                    registrationDeadlineTimePicker.clear();
                }
            }
        });
    }

});
// Funzioni per gestione inviti eventi privati
let privateInvitedUsers = [];
let artistInvitedUsers = [];
let suggestedUsers = [];

// Funzione helper per gestire l'avatar in modo coerente
function getUserAvatarHtml(user) {
    const avatarUrl = user.avatar_url || '/assets/images/avatar/default.png';
    return `<img src="${avatarUrl}" alt="${user.name}" class="img-fluid">`;
}

// Funzione helper per l'avatar con wrapper (per liste e risultati)
function getUserAvatarWithWrapper(user) {
    const avatarUrl = user.avatar_url || '/assets/images/avatar/default.png';
    return `<img src="${avatarUrl}" alt="${user.name}" class="img-fluid">`;
}

// Carica utenti suggeriti
function loadSuggestedUsers() {
    fetch('/api/users/suggested', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        suggestedUsers = data.users || [];
        displayPrivateSuggestedUsers();
        displayArtistSuggestedUsers();
    })
    .catch(error => {
        console.error('{{ __('common.loading_error') }} utenti suggeriti:', error);
    });
}

// Mostra utenti suggeriti per eventi privati
function displayPrivateSuggestedUsers() {
    const container = document.getElementById('privateSuggestedUsersList');
    if (!container) return;

    if (suggestedUsers.length === 0) {
        container.innerHTML = `
            <div class="col-12 text-center text-muted py-2">
                <i class="ph ph-users f-s-16 mb-1"></i>
                <p class="mb-0 small">{{ __('events.no_suggested_users') }}</p>
            </div>
        `;
        return;
    }

    container.innerHTML = suggestedUsers.map(user => `
        <div class="col-md-6 col-lg-4 mb-2">
            <div class="card hover-effect">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <a href="/user/${user.id}" target="_blank" class="h-40 w-40 d-flex-center b-r-50 overflow-hidden flex-shrink-0 me-3 text-decoration-none">
                            <img src="${user.avatar_url || '/assets/images/avatar/default.png'}" alt="${user.name}" class="img-fluid">
                        </a>
                        <div class="flex-grow-1 ps-2">
                            <div class="fw-medium txt-ellipsis-1">${user.name}</div>
                            <div class="text-muted f-s-12 txt-ellipsis-1">${user.email}</div>
                        </div>
                        <button type="button" class="btn btn-light-primary icon-btn b-r-4"
                                onclick="invitePrivateUser(${user.id}, '${user.name.replace(/'/g, "\\'")}', '${user.email.replace(/'/g, "\\'")}', '${user.avatar_url}')"
                                title="{{ __('events.invite_user') }}">
                            <i class="ph ph-plus f-s-12"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

// Mostra utenti suggeriti per artisti
function displayArtistSuggestedUsers() {
    const container = document.getElementById('artistSuggestedUsersList');
    if (!container) return;

    if (suggestedUsers.length === 0) {
        container.innerHTML = `
            <div class="col-12 text-center text-muted py-2">
                <i class="ph ph-users f-s-16 mb-1"></i>
                <p class="mb-0 small">{{ __('events.no_suggested_users') }}</p>
            </div>
        `;
        return;
    }

    container.innerHTML = suggestedUsers.map(user => `
        <div class="col-md-6 col-lg-4 mb-2">
            <div class="card hover-effect">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <a href="/user/${user.id}" target="_blank" class="h-40 w-40 d-flex-center b-r-50 overflow-hidden flex-shrink-0 me-3 text-decoration-none">
                            <img src="${user.avatar_url || '/assets/images/avatar/default.png'}" alt="${user.name}" class="img-fluid">
                        </a>
                        <div class="flex-grow-1 ps-2">
                            <div class="fw-medium txt-ellipsis-1">${user.name}</div>
                            <div class="text-muted f-s-12 txt-ellipsis-1">${user.email}</div>
                        </div>
                        <button type="button" class="btn btn-light-primary icon-btn b-r-4"
                                onclick="inviteArtistUser(${user.id}, '${user.name.replace(/'/g, "\\'")}', '${user.email.replace(/'/g, "\\'")}', '${user.avatar_url}')"
                                title="{{ __('events.invite_user') }}">
                            <i class="ph ph-plus f-s-12"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

// Cerca utenti per invito privato
function searchPrivateUsersForInvite() {
    const searchTerm = document.getElementById('privateUserSearchInput').value.trim();
    if (!searchTerm) return;

    fetch(`/api/users/search?q=${encodeURIComponent(searchTerm)}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        displayPrivateSearchResults(data.users || []);
    })
    .catch(error => {
        console.error('Errore nella ricerca utenti privati:', error);
    });
}

// Cerca utenti per invito artisti
function searchArtistUsersForInvite() {
    const searchTerm = document.getElementById('artistUserSearchInput').value.trim();
    if (!searchTerm) return;

    fetch(`/api/users/search?q=${encodeURIComponent(searchTerm)}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        displayArtistSearchResults(data.users || []);
    })
    .catch(error => {
        console.error('Errore nella ricerca artisti:', error);
    });
}

// Mostra risultati ricerca per inviti privati
function displayPrivateSearchResults(users) {
    const container = document.getElementById('privateSearchResultsListInvite');
    const resultsDiv = document.getElementById('privateSearchResultsInvite');

    if (!container || !resultsDiv) return;

    if (users.length === 0) {
        container.innerHTML = `
            <div class="text-center text-muted py-3">
                <i class="ph ph-magnifying-glass f-s-24 mb-2"></i>
                <p class="mb-0">Nessun utente trovato</p>
            </div>
        `;
    } else {
        container.innerHTML = users.map(user => `
            <div class="list-group-item">
                <div class="d-flex align-items-center">
                    <a href="/user/${user.id}" target="_blank" class="h-40 w-40 d-flex-center b-r-50 overflow-hidden me-3 text-decoration-none">
                        <img src="${user.avatar_url || '/assets/images/avatar/default.png'}" alt="${user.name}" class="img-fluid">
                    </a>
                    <div class="flex-grow-1">
                        <h6 class="mb-1 f-s-14 f-w-600 text-dark">${user.name}</h6>
                        <small class="text-muted f-s-12">${user.email}</small>
                    </div>
                    <div class="flex-shrink-0">
                        <button type="button" class="btn btn-primary btn-sm hover-effect"
                                onclick="invitePrivateUser(${user.id}, '${user.name.replace(/'/g, "\\'")}', '${user.email.replace(/'/g, "\\'")}', '${user.avatar_url}')">
                            <i class="ph ph-plus f-s-12"></i> {{ __('events.invite_user') }}
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }

    resultsDiv.style.display = 'block';
}

// Mostra risultati ricerca per inviti artisti
function displayArtistSearchResults(users) {
    const container = document.getElementById('artistSearchResultsListInvite');
    const resultsDiv = document.getElementById('artistSearchResultsInvite');

    if (!container || !resultsDiv) return;

    if (users.length === 0) {
        container.innerHTML = `
            <div class="text-center text-muted py-3">
                <i class="ph ph-magnifying-glass f-s-24 mb-2"></i>
                <p class="mb-0">Nessun artista trovato</p>
            </div>
        `;
    } else {
        container.innerHTML = users.map(user => `
            <div class="list-group-item">
                <div class="d-flex align-items-center">
                    <a href="/user/${user.id}" target="_blank" class="h-40 w-40 d-flex-center b-r-50 overflow-hidden me-3 text-decoration-none">
                        <img src="${user.avatar_url || '/assets/images/avatar/default.png'}" alt="${user.name}" class="img-fluid">
                    </a>
                    <div class="flex-grow-1">
                        <h6 class="mb-1 f-s-14 f-w-600 text-dark">${user.name}</h6>
                        <small class="text-muted f-s-12">${user.email}</small>
                    </div>
                    <div class="flex-shrink-0">
                        <button type="button" class="btn btn-primary btn-sm hover-effect"
                                onclick="inviteArtistUser(${user.id}, '${user.name.replace(/'/g, "\\'")}', '${user.email.replace(/'/g, "\\'")}', '${user.avatar_url}')">
                            <i class="ph ph-plus f-s-12"></i> {{ __('events.invite_user') }}
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }

    resultsDiv.style.display = 'block';
}

// Invita utente per eventi privati
function invitePrivateUser(userId, userName, userEmail, userAvatarUrl) {


    // Controlla se l'utente è già stato invitato
    if (privateInvitedUsers.some(user => user.id === userId)) {

        return;
    }

    const user = {
        id: userId,
        name: userName,
        email: userEmail,
        avatar_url: userAvatarUrl
    };

    privateInvitedUsers.push(user);


    updatePrivateInvitedUsersDisplay();
    updatePrivateInvitedUsersData();

    // Mostra feedback visivo
    const button = event.target.closest('button');
    if (button) {
        const originalContent = button.innerHTML;
        button.innerHTML = '<i class="ph ph-check f-s-12"></i>';
        button.classList.remove('btn-light-primary');
        button.classList.add('btn-light-success');
        button.disabled = true;

        setTimeout(() => {
            button.innerHTML = originalContent;
            button.classList.remove('btn-light-success');
            button.classList.add('btn-light-primary');
            button.disabled = false;
        }, 2000);
    }
}

// Invita artista
function inviteArtistUser(userId, userName, userEmail, userAvatarUrl) {


    // Controlla se l'utente è già stato invitato
    if (artistInvitedUsers.some(user => user.id === userId)) {

        return;
    }

    const user = {
        id: userId,
        name: userName,
        email: userEmail,
        avatar_url: userAvatarUrl
    };

    artistInvitedUsers.push(user);


    updateArtistInvitedUsersDisplay();
    updateArtistInvitedUsersData();

    // Mostra feedback visivo
    const button = event.target.closest('button');
    if (button) {
        const originalContent = button.innerHTML;
        button.innerHTML = '<i class="ph ph-check f-s-12"></i>';
        button.classList.remove('btn-light-primary');
        button.classList.add('btn-light-success');
        button.disabled = true;

        setTimeout(() => {
            button.innerHTML = originalContent;
            button.classList.remove('btn-light-success');
            button.classList.add('btn-light-primary');
            button.disabled = false;
        }, 2000);
    }
}

// Rimuovi invito privato
function removePrivateInvite(userId) {


    privateInvitedUsers = privateInvitedUsers.filter(user => user.id !== userId);


    updatePrivateInvitedUsersDisplay();
    updatePrivateInvitedUsersData();
}

// Rimuovi invito artista
function removeArtistInvite(userId) {


    artistInvitedUsers = artistInvitedUsers.filter(user => user.id !== userId);


    updateArtistInvitedUsersDisplay();
    updateArtistInvitedUsersData();
}

// Aggiorna visualizzazione utenti invitati per eventi privati
function updatePrivateInvitedUsersDisplay() {


    const container = document.getElementById('privateInvitedUsersList');
    const countElement = document.getElementById('privateInviteCount');

    if (!container) {
        // Il container privateInvitedUsersList potrebbe non esistere in tutte le pagine
        // Non è un errore critico, quindi rimuoviamo il console.error
        // console.error('Container privateInvitedUsersList not found');
        return;
    }

    if (!countElement) {
        // L'elemento privateInviteCount potrebbe non esistere in tutte le pagine
        // Non è un errore critico, quindi rimuoviamo il console.error
        // console.error('Element privateInviteCount not found');
        return;
    }

    if (privateInvitedUsers.length === 0) {
        container.innerHTML = `
            <div class="col-12 text-center text-muted py-4" id="noPrivateInvitations">
                <i class="ph ph-user-plus display-4 mb-2"></i>
                <p>Nessun utente invitato ancora.<br>Cerca e aggiungi utenti da invitare.</p>
            </div>
        `;
    } else {
        container.innerHTML = privateInvitedUsers.map(user => `
            <div class="col-md-6 col-lg-4 mb-2">
                <div class="card hover-effect">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="h-40 w-40 d-flex-center b-r-50 overflow-hidden flex-shrink-0 me-3">
                                ${getUserAvatarHtml(user)}
                            </div>
                            <div class="flex-grow-1 ps-2">
                                <div class="fw-medium txt-ellipsis-1">${user.name}</div>
                                <div class="text-muted f-s-12 txt-ellipsis-1">${user.email}</div>
                            </div>
                            <button type="button" class="btn btn-light-danger icon-btn b-r-4"
                                    onclick="removePrivateInvite(${user.id})" title="Rimuovi invito">
                                <i class="ph ph-x f-s-12"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    countElement.textContent = privateInvitedUsers.length;

}

// Aggiorna visualizzazione artisti invitati
function updateArtistInvitedUsersDisplay() {


    const container = document.getElementById('artistInvitedUsersList');
    const countElement = document.getElementById('artistInviteCount');

    if (!container) {
        // Il container artistInvitedUsersList potrebbe non esistere in tutte le pagine
        // Non è un errore critico, quindi rimuoviamo il console.error
        // console.error('Container artistInvitedUsersList not found');
        return;
    }

    if (!countElement) {
        // L'elemento artistInviteCount potrebbe non esistere in tutte le pagine
        // Non è un errore critico, quindi rimuoviamo il console.error
        // console.error('Element artistInviteCount not found');
        return;
    }

    if (artistInvitedUsers.length === 0) {
        container.innerHTML = `
            <div class="col-12 text-center text-muted py-4" id="noArtistInvitations">
                <i class="ph ph-user-plus display-4 mb-2"></i>
                <p>Nessun artista selezionato ancora.<br>Cerca e aggiungi artisti da invitare.</p>
            </div>
        `;
    } else {
        container.innerHTML = artistInvitedUsers.map(user => `
            <div class="col-md-6 col-lg-4 mb-2">
                <div class="card hover-effect">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="h-40 w-40 d-flex-center b-r-50 overflow-hidden flex-shrink-0 me-3">
                                ${getUserAvatarHtml(user)}
                            </div>
                            <div class="flex-grow-1 ps-2">
                                <div class="fw-medium txt-ellipsis-1">${user.name}</div>
                                <div class="text-muted f-s-12 txt-ellipsis-1">${user.email}</div>
                                <div class="text-muted f-s-10 txt-ellipsis-1">${user.role || 'performer'}</div>
                            </div>
                            <button type="button" class="btn btn-light-danger icon-btn b-r-4"
                                    onclick="removeArtistInvite(${user.id})" title="Rimuovi invito">
                                <i class="ph ph-x f-s-12"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    countElement.textContent = artistInvitedUsers.length;

}

// Aggiorna dati nascosti per inviti privati
function updatePrivateInvitedUsersData() {
    const hiddenInput = document.getElementById('privateInvitedUsersData');
    if (hiddenInput) {
        hiddenInput.value = JSON.stringify(privateInvitedUsers);
    }
}

// Aggiorna dati nascosti per inviti artisti
function updateArtistInvitedUsersData() {
    const hiddenInput = document.getElementById('artistInvitedUsersData');
    if (hiddenInput) {
        hiddenInput.value = JSON.stringify(artistInvitedUsers);
    }
}

// Gestisce il tasto Invio nella ricerca utenti privati
function handlePrivateUserSearchKeydown(event) {
    if (event.key === 'Enter') {
        event.preventDefault(); // Previene il submit del form
        searchPrivateUsersForInvite(); // Esegue la ricerca invece
    }
}

// Gestisce il tasto Invio nella ricerca artisti
function handleArtistUserSearchKeydown(event) {
    if (event.key === 'Enter') {
        event.preventDefault(); // Previene il submit del form
        searchArtistUsersForInvite(); // Esegue la ricerca invece
    }
}

// Cerca utenti per inviti
function searchUsers() {
    const searchTerm = document.getElementById('userSearch').value.trim();
        const resultsDiv = document.getElementById('searchResults');
    const resultsList = document.getElementById('searchResultsList');

    if (!searchTerm) {
        alert('Inserisci un termine di ricerca');
        return;
    }



    // Simula una ricerca (in produzione dovrebbe essere una chiamata AJAX)
    fetch(`/api/users/search?q=${encodeURIComponent(searchTerm)}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {


        if (data.users && data.users.length > 0) {
            resultsList.innerHTML = data.users.map(user => `
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold">${user.name}</div>
                        <small class="text-muted">${user.email}</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary"
                            onclick="selectUserForInvitation(${user.id}, '${user.name.replace(/'/g, "\\'")}', '${user.email.replace(/'/g, "\\'")}')">
                        <i class="ph ph-plus me-1"></i>Seleziona
                    </button>
                </div>
            `).join('');
            resultsDiv.style.display = 'block';
        } else {
                        resultsList.innerHTML =
                            '<div class="list-group-item text-center text-muted">Nessun utente trovato</div>';
            resultsDiv.style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Search error:', error);
                    resultsList.innerHTML =
                        '<div class="list-group-item text-center text-danger">Errore durante la ricerca</div>';
        resultsDiv.style.display = 'block';
    });
}

// Seleziona utente per invito
function selectUserForInvitation(userId, userName, userEmail) {


    // Controlla se l'utente è già stato invitato
    if (invitedUsers.some(user => user.id === userId)) {
        alert('Questo utente è già stato invitato');
        return;
    }

    // Mostra il modal per selezionare il ruolo
            document.getElementById('selectedUserInitials').textContent = userName.split(' ').map(n => n[0]).join('')
                .toUpperCase();
    document.getElementById('selectedUserName').textContent = userName;
    document.getElementById('selectedUserEmail').textContent = userEmail;

    // Salva i dati dell'utente selezionato
            window.selectedUser = {
                id: userId,
                name: userName,
                email: userEmail
            };

    // Mostra il modal
    const modal = new bootstrap.Modal(document.getElementById('roleSelectionModal'));
    modal.show();
}

// Conferma invito
function confirmInvitation() {
    const selectedUser = window.selectedUser;
    const role = document.querySelector('input[name="invitationRole"]:checked').value;
    const message = document.getElementById('invitationMessage').value.trim();

    if (!selectedUser) {
        alert('Nessun utente selezionato');
        return;
    }



    // Aggiungi l'utente alla lista degli invitati
    const invitation = {
        id: selectedUser.id,
        name: selectedUser.name,
        email: selectedUser.email,
        role: role,
        message: message
    };

    invitedUsers.push(invitation);


    // Aggiorna la visualizzazione
    updateArtistInvitedUsersDisplay();
    updateArtistInvitedUsersData();

    // Chiudi il modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('roleSelectionModal'));
    modal.hide();

        // Pulisci i campi
    document.getElementById('invitationMessage').value = '';
    window.selectedUser = null;

    // Mostra feedback
    alert('Invito aggiunto con successo!');
}

// ========================================
// FUNZIONI PER EVENTI RICORRENTI
// ========================================

// Aggiorna campi visibili in base al tipo di ricorrenza
function updateRecurrenceFields() {
    const recurrenceType = document.getElementById('recurrence_type').value;
    const weekdaysField = document.getElementById('weekdays-field');
    const monthdayField = document.getElementById('monthday-field');
    const intervalField = document.getElementById('interval-field');
    const intervalHelp = document.getElementById('interval-help');

    // Nascondi tutti i campi specifici
    weekdaysField.style.display = 'none';
    monthdayField.style.display = 'none';

    // Mostra campi appropriati
    switch (recurrenceType) {
        case 'daily':
            intervalField.style.display = 'block';
            intervalHelp.textContent = 'Ogni quanti giorni (es. 1 = ogni giorno, 2 = ogni 2 giorni)';
            break;
        case 'weekly':
            weekdaysField.style.display = 'block';
            intervalField.style.display = 'block';
            intervalHelp.textContent = 'Ogni quante settimane (es. 1 = ogni settimana, 2 = ogni 2 settimane)';
            break;
        case 'monthly':
            monthdayField.style.display = 'block';
            intervalField.style.display = 'block';
            intervalHelp.textContent = 'Ogni quanti mesi (es. 1 = ogni mese, 2 = ogni 2 mesi)';
            break;
        case 'yearly':
            intervalField.style.display = 'block';
            intervalHelp.textContent = 'Ogni quanti anni (es. 1 = ogni anno, 2 = ogni 2 anni)';
            break;
        default:
            intervalField.style.display = 'block';
            intervalHelp.textContent = 'Seleziona un tipo di ricorrenza';
            break;
    }
}

// Aggiorna anteprima ricorrenza
function updateRecurrencePreview() {
    const previewDiv = document.getElementById('recurrence-preview');
    if (!previewDiv) return;

    const isRecurring = document.getElementById('is_recurring').checked;
    if (!isRecurring) {
                previewDiv.innerHTML = '{{ __('events.recurrence_preview_placeholder') }}';
        return;
    }

    const recurrenceType = document.getElementById('recurrence_type').value;
    const startDateTime = document.getElementById('start_datetime').value;
    const endDateTime = document.getElementById('end_datetime').value;

    if (!startDateTime || !endDateTime) {
        previewDiv.innerHTML = '<span class="text-warning">Seleziona prima le date di inizio e fine</span>';
        return;
    }

    const startDate = new Date(startDateTime);
    const endDate = new Date(endDateTime);
    const duration = endDate - startDate;

    let previewText = '';
    let dates = [];

    switch (recurrenceType) {
        case 'daily':
            const dailyInterval = parseInt(document.getElementById('recurrence_interval').value) || 1;
            const dailyCount = parseInt(document.getElementById('recurrence_count').value) || 5;
            previewText = `${dailyCount} eventi, ogni ${dailyInterval} giorno${dailyInterval > 1 ? 'i' : ''}`;

                    for (let i = 0; i < Math.min(dailyCount,
                        10); i++) { // Mostra prime 10 occorrenze o il numero specificato
                const newDate = new Date(startDate);
                newDate.setDate(startDate.getDate() + (i * dailyInterval));
                dates.push(newDate);
            }
            break;
        case 'weekly':
            const weeklyInterval = parseInt(document.getElementById('recurrence_interval').value) || 1;
                    const selectedWeekdays = Array.from(document.querySelectorAll(
                            'input[name="recurrence_weekdays[]"]:checked'))
                .map(cb => parseInt(cb.value));

            if (selectedWeekdays.length === 0) {
                previewText = 'Seleziona almeno un giorno della settimana';
            } else {
                const weekdayNames = ['Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab'];
                const selectedNames = selectedWeekdays.map(d => weekdayNames[d]).join(', ');
                previewText = `Ogni ${weeklyInterval} settimana${weeklyInterval > 1 ? 'e' : ''} (${selectedNames})`;

                // Genera prime 8 occorrenze
                let currentDate = new Date(startDate);
                let count = 0;
                while (dates.length < 8 && count < 56) { // Max 8 settimane
                    if (selectedWeekdays.includes(currentDate.getDay())) {
                        dates.push(new Date(currentDate));
                    }
                    currentDate.setDate(currentDate.getDate() + 1);
                    count++;
                }
            }
            break;
        case 'monthly':
            const monthlyInterval = parseInt(document.getElementById('recurrence_interval').value) || 1;
            const monthday = parseInt(document.getElementById('recurrence_monthday').value) || 15;
            previewText = `Ogni ${monthlyInterval} mese${monthlyInterval > 1 ? 'i' : ''}, giorno ${monthday}`;

            for (let i = 0; i < 12; i++) { // Mostra prime 12 occorrenze
                const newDate = new Date(startDate);
                newDate.setMonth(startDate.getMonth() + (i * monthlyInterval));
                newDate.setDate(monthday);
                dates.push(newDate);
            }
            break;
        case 'yearly':
            const yearlyInterval = parseInt(document.getElementById('recurrence_interval').value) || 1;
            previewText = `Ogni ${yearlyInterval} anno${yearlyInterval > 1 ? 'i' : ''}`;

            for (let i = 0; i < 5; i++) { // Mostra prime 5 occorrenze
                const newDate = new Date(startDate);
                newDate.setFullYear(startDate.getFullYear() + (i * yearlyInterval));
                dates.push(newDate);
            }
            break;
    }

    // Formatta le date per la visualizzazione
    const formattedDates = dates.map(date => {
        return date.toLocaleDateString('it-IT', {
            weekday: 'short',
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        });
    });

    // Mostra anteprima
    if (dates.length > 0) {
        previewDiv.innerHTML = `
            <div class="mb-2"><strong>${previewText}</strong></div>
            <div class="small">
                <strong>Prime occorrenze:</strong><br>
                ${formattedDates.slice(0, 5).join('<br>')}
                ${formattedDates.length > 5 ? '<br><em>... e altre</em>' : ''}
            </div>
        `;
    } else {
        previewDiv.innerHTML = `<span class="text-warning">${previewText}</span>`;
    }
}

// ========================================
// FUNZIONI HELPER PER TERZO STEP
// ========================================

// Converte URL video in URL embed
function convertToEmbedUrl(url) {
    if (!url) return null;

    // YouTube
    const youtubeRegex = /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/;
    const youtubeMatch = url.match(youtubeRegex);
    if (youtubeMatch) {
        return `https://www.youtube.com/embed/${youtubeMatch[1]}`;
    }

    // Vimeo
    const vimeoRegex = /(?:vimeo\.com\/|player\.vimeo\.com\/video\/)(\d+)/;
    const vimeoMatch = url.match(vimeoRegex);
    if (vimeoMatch) {
        return `https://player.vimeo.com/video/${vimeoMatch[1]}`;
    }

    // Dailymotion
    const dailymotionRegex = /(?:dailymotion\.com\/video\/|dai\.ly\/)([a-zA-Z0-9]+)/;
    const dailymotionMatch = url.match(dailymotionRegex);
    if (dailymotionMatch) {
        return `https://www.dailymotion.com/embed/video/${dailymotionMatch[1]}`;
    }

    // Altri servizi possono essere aggiunti qui
    return null;
}

// Carica la lista dei festival
function loadFestivals() {
    const festivalSelect = document.getElementById('festival_id');
    if (!festivalSelect) return;

    // Pulisci le opzioni esistenti
            festivalSelect.innerHTML = '<option value="">{{ __('events.select_festival') }}</option>';

    // Chiamata API per caricare festival
    fetch('/api/festivals', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {

        if (data.festivals && data.festivals.length > 0) {
            data.festivals.forEach(festival => {
                const option = document.createElement('option');
                option.value = festival.id;
                option.textContent = festival.title;
                festivalSelect.appendChild(option);
            });
        }
    })
    .catch(error => {
        console.error('Errore nel caricamento festival:', error);
        // Fallback con dati di esempio
                    const sampleFestivals = [{
                            id: 1,
                            title: 'Festival di Poesia 2024'
                        },
                        {
                            id: 2,
                            title: 'Slam Poetry Festival'
                        },
                        {
                            id: 3,
                            title: 'Festival delle Arti Performative'
                        }
        ];

        sampleFestivals.forEach(festival => {
            const option = document.createElement('option');
            option.value = festival.id;
            option.textContent = festival.title;
            festivalSelect.appendChild(option);
        });
    });
}

// ========================================
// FUNZIONI PER GESTIONE FESTIVAL - NUOVA LOGICA
// ========================================

// Aggiorna la visualizzazione della sezione festival nel quarto step
function updateFestivalSectionDisplay(selectedCategory) {
    const createFestivalSection = document.getElementById('create-festival-section');
    const selectFestivalEventsSection = document.getElementById('select-festival-events-section');

    if (selectedCategory === 'festival') {
        // Categoria "Festival" selezionata: mostra selezione eventi esistenti
        if (createFestivalSection) createFestivalSection.style.display = 'none';
        if (selectFestivalEventsSection) {
            selectFestivalEventsSection.style.display = 'block';
            // Non serve caricare eventi specifici qui, verrà fatto quando l'utente cerca
        }
    } else {
        // Altre categorie: mostra opzione per associare a festival esistente
        if (createFestivalSection) createFestivalSection.style.display = 'block';
        if (selectFestivalEventsSection) selectFestivalEventsSection.style.display = 'none';

        // Carica la lista dei festival disponibili per la dropdown
        loadFestivals();
    }
}

// Carica gli eventi disponibili per il festival selezionato
function loadFestivalEvents(festivalId) {


    // Simula caricamento eventi (in produzione dovrebbe essere una chiamata AJAX)
    fetch(`/api/festivals/${festivalId}/events`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.events && data.events.length > 0) {

            // Qui puoi popolare una lista di eventi suggeriti
        }
    })
    .catch(error => {
        console.error('Errore nel caricamento eventi festival:', error);
        // Fallback con dati di esempio
                    const sampleEvents = [{
                            id: 1,
                            title: 'Slam Poetry Night',
                            date: '2024-03-15',
                            venue: 'Teatro Comunale'
                        },
                        {
                            id: 2,
                            title: 'Poetry Workshop',
                            date: '2024-03-16',
                            venue: 'Biblioteca Civica'
                        },
                        {
                            id: 3,
                            title: 'Open Mic Night',
                            date: '2024-03-17',
                            venue: 'Caffè Letterario'
                        }
        ];

    });
}

// Gestisce la ricerca di eventi per il festival
function handleEventSearchKeydown(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        searchEventsForFestival();
    }
}

// Cerca eventi per aggiungerli al festival
function searchEventsForFestival() {
    const searchInput = document.getElementById('eventSearchInput');
    const searchTerm = searchInput.value.trim();

    if (!searchTerm) {
        alert('Inserisci un termine di ricerca');
        return;
    }



    // Mostra indicatore di caricamento
    const resultsSection = document.getElementById('searchResultsEvents');
    const resultsContainer = document.getElementById('searchResultsListEvents');
    if (resultsSection && resultsContainer) {
                resultsContainer.innerHTML =
                    '<div class="text-center text-muted py-3"><i class="ph ph-spinner-gap me-2"></i>Ricerca in corso...</div>';
        resultsSection.style.display = 'block';
    }

    // Chiamata API per ricerca eventi
    fetch(`/api/events/search?q=${encodeURIComponent(searchTerm)}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {

        displayEventSearchResults(data.events || []);
    })
    .catch(error => {
        console.error('Errore nella ricerca eventi:', error);
        // Fallback con dati di esempio
                    const sampleResults = [{
                            id: 1,
                            title: 'Slam Poetry Night',
                            date: '15/03/2024',
                            venue: 'Teatro Comunale'
                        },
                        {
                            id: 2,
                            title: 'Poetry Workshop',
                            date: '16/03/2024',
                            venue: 'Biblioteca Civica'
                        },
                        {
                            id: 3,
                            title: 'Open Mic Poetry',
                            date: '17/03/2024',
                            venue: 'Caffè Letterario'
                        }
        ];
        displayEventSearchResults(sampleResults);
    });
}

// Mostra i risultati della ricerca eventi
function displayEventSearchResults(events) {
    const resultsContainer = document.getElementById('searchResultsListEvents');
    const resultsSection = document.getElementById('searchResultsEvents');

    if (!resultsContainer || !resultsSection) return;

    if (events.length === 0) {
        resultsContainer.innerHTML = '<div class="text-center text-muted py-3">Nessun evento trovato</div>';
        resultsSection.style.display = 'block';
        return;
    }

    resultsContainer.innerHTML = '';

    events.forEach(event => {
        const eventElement = document.createElement('div');
                eventElement.className =
                    'list-group-item list-group-item-action d-flex justify-content-between align-items-center';
        eventElement.innerHTML = `
            <div>
                <h6 class="mb-1">${event.title}</h6>
                <small class="text-muted">${event.date} - ${event.venue}</small>
            </div>
            <button type="button" class="btn btn-sm btn-success" onclick="addEventToFestival(${event.id}, '${event.title}', '${event.date}', '${event.venue}')">
                <i class="ph ph-plus me-1"></i>Aggiungi
            </button>
        `;
        resultsContainer.appendChild(eventElement);
    });

    resultsSection.style.display = 'block';
}

// Aggiunge un evento al festival
function addEventToFestival(eventId, eventTitle, eventDate, eventVenue) {
    const selectedEventsData = document.getElementById('selectedFestivalEventsData');
    const selectedEventsList = document.getElementById('selectedEventsList');
    const noSelectedEvents = document.getElementById('noSelectedEvents');
    const selectedEventsCount = document.getElementById('selectedEventsCount');

    if (!selectedEventsData || !selectedEventsList || !noSelectedEvents || !selectedEventsCount) return;

    // Ottieni gli eventi già selezionati
    let selectedEvents = JSON.parse(selectedEventsData.value || '[]');

    // Controlla se l'evento è già stato aggiunto
    if (selectedEvents.find(event => event.id === eventId)) {
        alert('Questo evento è già stato aggiunto al festival');
        return;
    }

    // Aggiungi il nuovo evento
    selectedEvents.push({
        id: eventId,
        title: eventTitle,
        date: eventDate,
        venue: eventVenue
    });

    // Aggiorna i dati nascosti
    selectedEventsData.value = JSON.stringify(selectedEvents);

    // Aggiorna la visualizzazione
    updateSelectedEventsDisplay(selectedEvents);

    // Pulisci eventuali errori di validazione
    clearFestivalValidationErrors();

    // Nascondi i risultati della ricerca
    document.getElementById('searchResultsEvents').style.display = 'none';
    document.getElementById('eventSearchInput').value = '';

    // Pulisci eventuali errori di validazione
    clearFestivalValidationErrors();
}

// Aggiorna la visualizzazione degli eventi selezionati
function updateSelectedEventsDisplay(selectedEvents) {
    const selectedEventsList = document.getElementById('selectedEventsList');
    const noSelectedEvents = document.getElementById('noSelectedEvents');
    const selectedEventsCount = document.getElementById('selectedEventsCount');

    if (!selectedEventsList || !noSelectedEvents || !selectedEventsCount) return;

    if (selectedEvents.length === 0) {
        selectedEventsList.innerHTML = `
            <div class="col-12 text-center text-muted py-3" id="noSelectedEvents">
                <i class="ph ph-calendar-plus f-s-24 mb-2"></i>
                <p class="mb-0">{{ __('events.no_events_selected') }}</p>
            </div>
        `;
        selectedEventsCount.textContent = '0';
        return;
    }

    // Nascondi il messaggio "nessun evento"
    noSelectedEvents.style.display = 'none';

    // Aggiorna il contatore
    selectedEventsCount.textContent = selectedEvents.length;

    // Genera la lista degli eventi
    selectedEventsList.innerHTML = '';

    selectedEvents.forEach(event => {
        const eventElement = document.createElement('div');
        eventElement.className = 'col-md-6 col-lg-4';
        eventElement.innerHTML = `
            <div class="card border-success">
                <div class="card-body p-3">
                    <h6 class="card-title mb-2">${event.title}</h6>
                    <p class="card-text small text-muted mb-2">
                        <i class="ph ph-calendar me-1"></i>${event.date}<br>
                        <i class="ph ph-map-pin me-1"></i>${event.venue}
                    </p>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeEventFromFestival(${event.id})">
                        <i class="ph ph-minus me-1"></i>{{ __('events.remove_event_from_festival') }}
                    </button>
                </div>
            </div>
        `;
        selectedEventsList.appendChild(eventElement);
    });
}

// Rimuove un evento dal festival
function removeEventFromFestival(eventId) {
    const selectedEventsData = document.getElementById('selectedFestivalEventsData');

    if (!selectedEventsData) return;

    // Ottieni gli eventi selezionati
    let selectedEvents = JSON.parse(selectedEventsData.value || '[]');

    // Rimuovi l'evento
    selectedEvents = selectedEvents.filter(event => event.id !== eventId);

    // Aggiorna i dati nascosti
    selectedEventsData.value = JSON.stringify(selectedEvents);

    // Aggiorna la visualizzazione
    updateSelectedEventsDisplay(selectedEvents);
}



// ===== GESTIONE POSIZIONI D'INGAGGIO =====

let gigPositionCounter = 0;

// Aggiunge una nuova posizione d'ingaggio
function addGigPosition() {
    gigPositionCounter++;
    const positionId = `gig-position-${gigPositionCounter}`;

            const positionTitle = '{{ __('events.gig_position', ['number' => ':number']) }}'.replace(':number',
                gigPositionCounter);
    const positionHtml = `
        <div class="card mb-3" id="${positionId}">
            <div class="card-header bg-light-success">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="ph ph-briefcase me-2"></i>${positionTitle}
                    </h6>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeGigPosition('${positionId}')">
                        <i class="ph ph-trash"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Tipologia -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('events.gig_type') }}</label>
                        <select class="form-select" name="gig_positions[${gigPositionCounter}][type]" required>
                             <option value="">{{ __('events.select_type') }}</option>
                             <option value="poeta">{{ __('events.artist_poet') }}</option>
                              <option value="mc">{{ __('events.mc_host') }}</option>
                            <option value="tecnico">{{ __('events.technical_support') }}</option>
                           <option value="volontario">{{ __('events.volunteer') }}</option>
                        </select>
                    </div>

                    <!-- Quantità -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('events.quantity') }}</label>
                        <input type="number" class="form-control" name="gig_positions[${gigPositionCounter}][quantity]" min="1" value="1" required>
                    </div>

                    <!-- {{ __('common.language_selector') }} richiesta -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('events.language_required') }}</label>
                        <select class="form-select" name="gig_positions[${gigPositionCounter}][language]">
                            <option value="">{{ __('events.no_preference') }}</option>
                            <option value="italiano">{{ __('events.italian') }}</option>
                            <option value="inglese">{{ __('events.english') }}</option>
                            <option value="francese">{{ __('events.french') }}</option>
                            <option value="tedesco">{{ __('events.german') }}</option>
                            <option value="spagnolo">{{ __('events.spanish') }}</option>
                            <option value="portoghese">{{ __('events.portuguese') }}</option>
                            <option value="altro">{{ __('events.other') }}</option>
                        </select>
                    </div>
                </div>

                <!-- Cachet -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="cachet-${gigPositionCounter}" onchange="toggleCachetFields(${gigPositionCounter})">
                            <label class="form-check-label" for="cachet-${gigPositionCounter}">
                                <strong>{{ __('events.cachet') }}</strong>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6" id="cachet-amount-${gigPositionCounter}" style="display: none;">
                        <label class="form-label">{{ __('events.amount') }}</label>
                        <input type="number" class="form-control" name="gig_positions[${gigPositionCounter}][cachet_amount]" min="0" step="0.01" placeholder="0.00">
                    </div>
                    <div class="col-md-6" id="cachet-currency-${gigPositionCounter}" style="display: none;">
                        <label class="form-label">{{ __('events.currency') }}</label>
                        <select class="form-select" name="gig_positions[${gigPositionCounter}][cachet_currency]">
                            <option value="EUR">EUR (€)</option>
                            <option value="USD">USD ($)</option>
                            <option value="GBP">GBP (£)</option>
                        </select>
                    </div>
                </div>

                <!-- Spese di viaggio -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="travel-${gigPositionCounter}" onchange="toggleTravelFields(${gigPositionCounter})">
                            <label class="form-check-label" for="travel-${gigPositionCounter}">
                                <strong>{{ __('events.travel_expenses') }}</strong>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6" id="travel-max-${gigPositionCounter}" style="display: none;">
                        <label class="form-label">{{ __('events.max_travel_coverage') }}</label>
                        <input type="number" class="form-control" name="gig_positions[${gigPositionCounter}][travel_max]" min="0" step="0.01" placeholder="0.00">
                    </div>
                    <div class="col-md-6" id="travel-currency-${gigPositionCounter}" style="display: none;">
                        <label class="form-label">{{ __('events.travel_currency') }}</label>
                        <select class="form-select" name="gig_positions[${gigPositionCounter}][travel_currency]">
                            <option value="EUR">EUR (€)</option>
                            <option value="USD">USD ($)</option>
                            <option value="GBP">GBP (£)</option>
                            <option value="CHF">CHF (CHF)</option>
                        </select>
                    </div>
                </div>

                <!-- Vitto e alloggio -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="accommodation-${gigPositionCounter}" onchange="toggleAccommodationFields(${gigPositionCounter})">
                            <label class="form-check-label" for="accommodation-${gigPositionCounter}">
                                <strong>{{ __('events.accommodation') }}</strong>
                            </label>
                        </div>
                    </div>
                    <div class="col-12" id="accommodation-details-${gigPositionCounter}" style="display: none;">
                        <label class="form-label">{{ __('events.accommodation_details') }}</label>
                        <textarea class="form-control" name="gig_positions[${gigPositionCounter}][accommodation_details]" rows="3" placeholder="{{ __('events.accommodation_placeholder') }}"></textarea>
                    </div>
                </div>
            </div>
        </div>
    `;

    document.getElementById('gigPositionsContainer').insertAdjacentHTML('beforeend', positionHtml);
    updateGigPositionsData();
}

// Rimuove una posizione d'ingaggio
function removeGigPosition(positionId) {
    document.getElementById(positionId).remove();
    updateGigPositionsData();
}

// Toggle campi cachet
function toggleCachetFields(positionNumber) {
    const isChecked = document.getElementById(`cachet-${positionNumber}`).checked;
    document.getElementById(`cachet-amount-${positionNumber}`).style.display = isChecked ? 'block' : 'none';
    document.getElementById(`cachet-currency-${positionNumber}`).style.display = isChecked ? 'block' : 'none';
}

// Toggle campi spese di viaggio
function toggleTravelFields(positionNumber) {
    const isChecked = document.getElementById(`travel-${positionNumber}`).checked;
    document.getElementById(`travel-max-${positionNumber}`).style.display = isChecked ? 'block' : 'none';
    document.getElementById(`travel-currency-${positionNumber}`).style.display = isChecked ? 'block' : 'none';
}

// Toggle campi vitto e alloggio
function toggleAccommodationFields(positionNumber) {
    const isChecked = document.getElementById(`accommodation-${positionNumber}`).checked;
    document.getElementById(`accommodation-details-${positionNumber}`).style.display = isChecked ? 'block' : 'none';
}

// Aggiorna i dati delle posizioni d'ingaggio
function updateGigPositionsData() {
    const positions = [];
    const positionCards = document.querySelectorAll('#gigPositionsContainer .card');

    positionCards.forEach(card => {
        const positionData = {
            type: card.querySelector('select[name*="[type]"]').value,
            quantity: card.querySelector('input[name*="[quantity]"]').value,
            language: card.querySelector('select[name*="[language]"]').value,
            cachet_amount: card.querySelector('input[name*="[cachet_amount]"]').value,
            cachet_currency: card.querySelector('select[name*="[cachet_currency]"]').value,
            travel_max: card.querySelector('input[name*="[travel_max]"]').value,
            accommodation_details: card.querySelector('textarea[name*="[accommodation_details]"]').value
        };
        positions.push(positionData);
    });

    document.getElementById('gigPositionsData').value = JSON.stringify(positions);
}

// Aggiungi event listener per aggiornare i dati quando cambiano i campi
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('gigPositionsContainer').addEventListener('change', updateGigPositionsData);
    document.getElementById('gigPositionsContainer').addEventListener('input', updateGigPositionsData);
});

// ========================================
// GESTIONE LUOGHI RECENTI E REVERSE GEOCODING
// ========================================

// ========================================
// VENUE AUTOCOMPLETE FUNCTIONALITY
// ========================================

// Initialize venue autocomplete
function initializeVenueAutocomplete() {
    const venueInput = document.getElementById('venue_name');
    const autocompleteDropdown = document.getElementById('venue-autocomplete');

    if (!venueInput || !autocompleteDropdown) return;

    // Force disable browser autocomplete
    venueInput.setAttribute('autocomplete', 'new-password');
    venueInput.setAttribute('data-lpignore', 'true');
    venueInput.setAttribute('data-form-type', 'other');
    venueInput.setAttribute('spellcheck', 'false');

    // Additional protection - change name attribute temporarily
    const originalName = venueInput.name;
    venueInput.name = 'venue_name_' + Date.now();
    setTimeout(() => {
        venueInput.name = originalName;
    }, 100);

    // Add event listeners
    venueInput.addEventListener('input', handleVenueInput);
    venueInput.addEventListener('keydown', handleVenueKeydown);
    venueInput.addEventListener('blur', handleVenueBlur);

    // Hide dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!venueInput.contains(e.target) && !autocompleteDropdown.contains(e.target)) {
            hideAutocomplete();
        }
    });
}

// Handle venue input changes
function handleVenueInput(e) {
    const query = e.target.value.trim();

    // Clear previous timeout
    if (venueAutocompleteTimeout) {
        clearTimeout(venueAutocompleteTimeout);
    }

    // Hide dropdown if query is too short
    if (query.length < 2) {
        hideAutocomplete();
        return;
    }

    // Debounce the search
    venueAutocompleteTimeout = setTimeout(() => {
        searchVenues(query);
    }, 300);
}

// Handle keyboard navigation
function handleVenueKeydown(e) {
    const suggestions = document.querySelectorAll('.autocomplete-suggestion');

            switch (e.key) {
        case 'ArrowDown':
            e.preventDefault();
            selectedSuggestionIndex = Math.min(selectedSuggestionIndex + 1, suggestions.length - 1);
            updateSuggestionSelection();
            break;
        case 'ArrowUp':
            e.preventDefault();
            selectedSuggestionIndex = Math.max(selectedSuggestionIndex - 1, -1);
            updateSuggestionSelection();
            break;
        case 'Enter':
            e.preventDefault();
            if (selectedSuggestionIndex >= 0 && suggestions[selectedSuggestionIndex]) {
                selectSuggestion(suggestions[selectedSuggestionIndex]);
            }
            break;
        case 'Escape':
            hideAutocomplete();
            break;
    }
}

// Handle venue input blur
function handleVenueBlur(e) {
    // Delay hiding to allow for suggestion clicks
    setTimeout(() => {
        hideAutocomplete();
    }, 200);
}

// Search venues via API
function searchVenues(query) {
    fetch(`/events/search-venues?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.venues.length > 0) {
                showAutocomplete(data.venues);
            } else {
                hideAutocomplete();
            }
        })
        .catch(error => {
            console.error('Error searching venues:', error);
            hideAutocomplete();
        });
}

// Show autocomplete dropdown
function showAutocomplete(venues) {
    const autocompleteDropdown = document.getElementById('venue-autocomplete');
    const suggestionsContainer = document.getElementById('venue-suggestions');

    if (!autocompleteDropdown || !suggestionsContainer) return;

    // Clear previous suggestions
    suggestionsContainer.innerHTML = '';

    // Add suggestions
    venues.forEach((venue, index) => {
        const suggestion = createSuggestionElement(venue, index);
        suggestionsContainer.appendChild(suggestion);
    });

    // Show dropdown
    autocompleteDropdown.style.display = 'block';
    selectedSuggestionIndex = -1;
}

// Create suggestion element
function createSuggestionElement(venue, index) {
    const suggestion = document.createElement('div');
    suggestion.className = 'autocomplete-suggestion';
    suggestion.dataset.index = index;
    suggestion.dataset.venue = JSON.stringify(venue);

    suggestion.innerHTML = `
        <div class="venue-name">${venue.venue_name}</div>
        <div class="venue-details">${venue.venue_address}, ${venue.city}</div>
        <div class="venue-stats">
            <i class="ph ph-users me-1"></i>${venue.unique_users} utenti •
            <i class="ph ph-calendar me-1"></i>${venue.total_usage} volte
        </div>
    `;

    // Add click handler
    suggestion.addEventListener('click', () => selectSuggestion(suggestion));

    return suggestion;
}

// Update suggestion selection
function updateSuggestionSelection() {
    const suggestions = document.querySelectorAll('.autocomplete-suggestion');

    suggestions.forEach((suggestion, index) => {
        if (index === selectedSuggestionIndex) {
            suggestion.classList.add('selected');
        } else {
            suggestion.classList.remove('selected');
        }
    });
}

// Select a suggestion
function selectSuggestion(suggestionElement) {
    const venueData = suggestionElement.dataset.venue;
    if (!venueData) return;

    try {
        const venue = JSON.parse(venueData);
        fillVenueData(venue);
        hideAutocomplete();
    } catch (error) {
        console.error('Error parsing venue data:', error);
    }
}

// Fill venue data from suggestion
function fillVenueData(venue) {
    // Fill venue name
    document.getElementById('venue_name').value = venue.venue_name;

    // Fill address
    const addressInput = document.getElementById('venue_address');
    if (addressInput) addressInput.value = venue.venue_address || '';

    // Fill city
    const cityInput = document.getElementById('city');
    if (cityInput) cityInput.value = venue.city || '';

    // Fill postcode
    const postcodeInput = document.getElementById('postcode');
    if (postcodeInput) postcodeInput.value = venue.postcode || '';

    // Fill country
    const countryInput = document.getElementById('country');
    if (countryInput) countryInput.value = venue.country || 'Italia';

    // Update map if coordinates are available
    if (venue.latitude && venue.longitude) {
        setMapLocation(parseFloat(venue.latitude), parseFloat(venue.longitude), true);
    }
}

// Hide autocomplete dropdown
function hideAutocomplete() {
    const autocompleteDropdown = document.getElementById('venue-autocomplete');
    if (autocompleteDropdown) {
        autocompleteDropdown.style.display = 'none';
    }
    selectedSuggestionIndex = -1;
}

// ========================================
// SELECT2 VENUES FUNCTIONALITY
// ========================================

// Initialize Select2 for venues dropdown
function initializeSelect2Venues() {
    const venueSelect = document.getElementById('recent_venue');
    if (!venueSelect) return;

    $(venueSelect).select2({
                placeholder: '{{ __('events.select_recent_venue') }}',
        allowClear: true,
        width: '100%',
        language: {
            noResults: function() {
                return "Nessun luogo trovato";
            },
            searching: function() {
                return "Ricerca in corso...";
            }
        },
        templateResult: formatVenueOption,
        templateSelection: formatVenueSelection,
        escapeMarkup: function(markup) {
            return markup;
        }
    });

    // Handle selection change
    $(venueSelect).on('select2:select', function(e) {
        const selectedOption = e.params.data;
        if (selectedOption.id && selectedOption.id !== '') {
            loadRecentVenueFromDropdown(selectedOption.id);
        }
    });
}

// Format venue option for Select2 dropdown
function formatVenueOption(venue) {
    if (!venue.id) {
        return venue.text;
    }

    try {
        const venueData = JSON.parse(venue.element.getAttribute('data-venue'));
        return $(`
            <div class="venue-option">
                <div class="venue-name">${venueData.venue_name}</div>
                <div class="venue-details">${venueData.venue_address}, ${venueData.city}</div>
                <div class="venue-stats">
                    <i class="ph ph-users me-1"></i>${venueData.unique_users} utenti •
                    <i class="ph ph-calendar me-1"></i>${venueData.total_usage} volte
                </div>
            </div>
        `);
    } catch (error) {
        return venue.text;
    }
}

// Format venue selection for Select2 input
function formatVenueSelection(venue) {
    if (!venue.id) {
        return venue.text;
    }

    try {
        const venueData = JSON.parse(venue.element.getAttribute('data-venue'));
        return venueData.venue_name;
    } catch (error) {
        return venue.text;
    }
}

// Carica un luogo recente
function loadRecentVenue(venueId) {
    fetch(`/events/recent-venues`)
        .then(response => {
            // Controlla se la risposta è OK
            if (!response.ok) {
                if (response.status === 401 || response.status === 403) {
                    // Utente non autenticato o non autorizzato
                    throw new Error('Authentication required');
                } else if (response.status === 404) {
                    // Route non trovata
                    throw new Error('API endpoint not found');
                } else {
                    // Altri errori HTTP
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
            }

            // Controlla se la risposta è JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Expected JSON response but got: ' + contentType);
            }

            return response.json();
        })
        .then(data => {
            if (data.success) {
                const venue = data.venues.find(v => v.id === venueId);
                if (venue) {
                    // Popola i campi con i dati del luogo recente
                    document.getElementById('venue_name').value = venue.venue_name;
                    document.getElementById('venue_address').value = venue.venue_address;
                    document.getElementById('city').value = venue.city;
                    document.getElementById('postcode').value = venue.postcode;

                    // Se abbiamo le coordinate, posiziona sulla mappa
                    if (venue.latitude && venue.longitude) {
                        setMapLocation(venue.latitude, venue.longitude, true); // Skip reverse geocoding
                    }

                    // Mostra notifica di successo
                            showNotification('{{ __('events.venue_loaded_success') }}', 'success');
                } else {
                            showNotification('{{ __('events.venue_not_found') }}', 'warning');
                }
            } else {
                        showNotification(data.message || '{{ __('events.venue_load_error') }}', 'error');
            }
        })
        .catch(error => {
            console.error('Error loading recent venue:', error);

            if (error.message === 'Authentication required') {
                        showNotification('{{ __('events.authentication_required') }}', 'warning');
            } else if (error.message === 'API endpoint not found') {
                        showNotification('{{ __('events.server_error_endpoint') }}', 'error');
            } else if (error.message.includes('Expected JSON response')) {
                        showNotification('{{ __('events.server_error_response') }}', 'error');
            } else {
                        showNotification('{{ __('events.venue_load_error') }}', 'error');
            }
        });
}

// Carica un luogo recente dal dropdown
function loadRecentVenueFromDropdown(venueId) {
    if (!venueId) {
        return; // Nessuna selezione
    }

    const selectElement = document.getElementById('recent_venue');
    const selectedOption = selectElement.querySelector(`option[value="${venueId}"]`);

    if (selectedOption && selectedOption.dataset.venue) {
        try {
            const venue = JSON.parse(selectedOption.dataset.venue);

            // Popola i campi con i dati del luogo recente
            document.getElementById('venue_name').value = venue.venue_name;
            document.getElementById('venue_address').value = venue.venue_address;
            document.getElementById('city').value = venue.city;
            document.getElementById('postcode').value = venue.postcode;

            // Se abbiamo le coordinate, posiziona sulla mappa
            if (venue.latitude && venue.longitude) {
                setMapLocation(venue.latitude, venue.longitude, true); // Skip reverse geocoding
            }

            // Mostra notifica di successo
                    showNotification('{{ __('events.venue_loaded_success') }}', 'success');

        } catch (error) {
            console.error('Error parsing venue data:', error);
                    showNotification('{{ __('events.venue_data_error') }}', 'error');
        }
    } else {
        // Fallback: usa la funzione originale se i dati non sono nel dataset
        loadRecentVenue(venueId);
    }
}

// Funzione per il reverse geocoding quando si clicca sulla mappa
function reverseGeocode(lat, lng) {
    const statusEl = document.getElementById('geocoding-status');
    if (statusEl) {
        statusEl.style.display = 'block';
                statusEl.innerHTML = '<i class="ph ph-spinner-gap me-1"></i> {{ __('events.searching_address') }}';
        statusEl.className = 'small text-info mt-1';
    }

    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`)
        .then(response => response.json())
        .then(data => {
            if (data && data.address) {
                // Aggiorna i campi con i dati dell'indirizzo
                updateAddressFieldsFromReverseGeocode(data);

                // Mostra successo
                if (statusEl) {
                            statusEl.innerHTML = '<i class="ph ph-check me-1"></i> {{ __('events.address_found') }}';
                    statusEl.className = 'small text-success mt-1';
                    setTimeout(() => {
                        statusEl.style.display = 'none';
                    }, 3000);
                }
            } else {
                // Indirizzo non trovato
                if (statusEl) {
                            statusEl.innerHTML =
                                '<i class="ph ph-warning me-1"></i> {{ __('events.address_not_found') }}';
                    statusEl.className = 'small text-warning mt-1';
                    setTimeout(() => {
                        statusEl.style.display = 'none';
                    }, 3000);
                }
            }
        })
        .catch(error => {
            console.error('Reverse geocoding error:', error);
            if (statusEl) {
                        statusEl.innerHTML =
                            '<i class="ph ph-warning me-1"></i> {{ __('events.reverse_geocoding_error') }}';
                statusEl.className = 'small text-danger mt-1';
                setTimeout(() => {
                    statusEl.style.display = 'none';
                }, 3000);
            }
        });
}

// Aggiorna i campi dell'indirizzo dal reverse geocoding
function updateAddressFieldsFromReverseGeocode(data) {
    const address = data.address;

    // Aggiorna il campo indirizzo
    const venueAddressInput = document.getElementById('venue_address');
    if (venueAddressInput && address) {
        const addressParts = [];

        if (address.house_number) {
            addressParts.push(address.house_number);
        }
        if (address.road) {
            addressParts.push(address.road);
        }
        if (address.suburb) {
            addressParts.push(address.suburb);
        }

        if (addressParts.length > 0) {
            venueAddressInput.value = addressParts.join(', ');
        }
    }

    // Aggiorna il campo città
    const cityInput = document.getElementById('city');
    if (cityInput && address) {
        const city = address.city ||
                    address.town ||
                    address.village ||
                    address.municipality ||
                    address.county ||
                    address.state ||
                    '';

        if (city) {
            cityInput.value = city;
        }
    }

    // Aggiorna il campo CAP
    const postcodeInput = document.getElementById('postcode');
    if (postcodeInput && address.postcode) {
        postcodeInput.value = address.postcode;
    }

    // Aggiorna il campo nome venue se vuoto
    const venueNameInput = document.getElementById('venue_name');
    if (venueNameInput && !venueNameInput.value.trim() && address) {
        const venueName = address.amenity ||
                         address.shop ||
                         address.office ||
                         address.building ||
                         '';

        if (venueName) {
            venueNameInput.value = venueName;
        }
    }
}

// Funzione per mostrare notifiche
function showNotification(message, type = 'info') {
    // Crea una notifica temporanea
    const notification = document.createElement('div');
            notification.className =
                `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(notification);

    // Rimuovi automaticamente dopo 3 secondi
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}

// Funzione per pulire gli errori di validazione del festival
function clearFestivalValidationErrors() {
    // Rimuovi messaggio di errore se presente
    const errorDiv = document.querySelector('.festival-error-message');
    if (errorDiv) {
        errorDiv.style.display = 'none';
    }

    // Rimuovi evidenziazione errore
    const searchInput = document.getElementById('eventSearchInput');
    if (searchInput) {
        searchInput.classList.remove('is-invalid');
        searchInput.style.borderColor = '';
    }
}

// ========================================
// FUNZIONI PER GESTIONE AVAILABILITY OPTIONS
// ========================================

let availabilityOptionCounter = 0;

// Aggiungi nuova opzione di disponibilità
function addAvailabilityOption() {
    availabilityOptionCounter++;

    const availabilityOptionsList = document.getElementById('availability-options-list');
    if (!availabilityOptionsList) return;

    const optionHtml = `
        <div class="card border-light mb-3" id="availability-option-${availabilityOptionCounter}">
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

    // Inizializza flatpickr per il nuovo campo
            const newInput = availabilityOptionsList.querySelector(
                `#availability-option-${availabilityOptionCounter} .availability-datetime-picker`);
    if (newInput) {
        flatpickr(newInput, {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            time_24hr: true,
            minDate: "today",
            locale: "it"
        });
    }
}

// Rimuovi opzione di disponibilità
function removeAvailabilityOption(optionId) {
    const optionElement = document.getElementById(`availability-option-${optionId}`);
    if (optionElement) {
        optionElement.remove();
    }
}
</script>

<!-- Flatpickr JS -->
    <script src="{{ asset('assets/vendor/datepikar/flatpickr.js') }}"></script>
@endpush
