@extends('layout.master')

@section('title', __('events.create_event'))
@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/leafletmaps/leaflet.css') }}">
<!-- Flatpickr CSS -->
<link rel="stylesheet" type="text/css" href="{{asset('assets/vendor/datepikar/flatpickr.min.css')}}">
<style>
.hidden-for-online-event {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    position: absolute !important;
    left: -9999px !important;
    height: 0 !important;
    overflow: hidden !important;
    pointer-events: none !important;
}
</style>
@endsection

@section('main-content')
<div class="container-fluid">

    <!-- Page Header -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="text-center mb-4">
                <h2 class="mb-2">
                    <i class="ph ph-calendar-plus me-2"></i>{{ __('events.create_event') }} Slam in
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
                                                    <div class="f-s-10 fw-bold text-primary">{{ __('events.step_1_short') }}</div>
                                                </div>
                                                <i class="ph ph-arrow-right text-muted f-s-12 mx-1"></i>
                                                <div class="text-center flex-fill" data-step="2">
                                                    <i class="ph ph-calendar-check f-s-24 text-muted mb-1"></i>
                                                    <div class="f-s-10 fw-bold text-muted">{{ __('events.step_2_short') }}</div>
                                                </div>
                                                <i class="ph ph-arrow-right text-muted f-s-12 mx-1"></i>
                                                <div class="text-center flex-fill" data-step="3">
                                                    <i class="ph ph-gear f-s-24 text-muted mb-1"></i>
                                                    <div class="f-s-10 fw-bold text-muted">{{ __('events.step_3_short') }}</div>
                                                </div>
                                                <i class="ph ph-arrow-right text-muted f-s-12 mx-1"></i>
                                                <div class="text-center flex-fill" data-step="4">
                                                    <i class="ph ph-users f-s-24 text-muted mb-1"></i>
                                                    <div class="f-s-10 fw-bold text-muted">{{ __('events.step_4_short') }}</div>
                                                </div>
                                                <i class="ph ph-arrow-right text-muted f-s-12 mx-1"></i>
                                                <div class="text-center flex-fill" data-step="5">
                                                    <i class="ph ph-eye f-s-24 text-muted mb-1"></i>
                                                    <div class="f-s-10 fw-bold text-muted">{{ __('events.step_5_short') }}</div>
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

        <div class="row">
            <!-- Form Steps -->
            <div class="col-lg-8">

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
                                                                    <input type="text" name="title" id="title" class="form-control" placeholder="{{ __('events.title_placeholder') }}" required>
                                <label for="title">{{ __('events.title_event') }} *</label>
                                </div>
                                <div class="error-feedback" id="title-error"></div>
                            </div>

                            <div class="col-12 mb-3">
                                <div class="form-floating">
                                    <textarea name="description" id="description" class="form-control" style="height: 120px" placeholder="Descrizione"></textarea>
                                    <label for="description">{{ __('events.description_event') }}</label>
                                </div>
                                <small class="text-muted">Descrivi il tuo evento, cosa aspettarsi, il formato, ecc. (opzionale)</small>
                                <div class="error-feedback" id="description-error"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('events.event_mode') }} *</label>
                                <div class="form-check">
                                    <input type="radio" name="is_public" id="public" value="1" class="form-check-input" checked>
                                    <label for="public" class="form-check-label">
                                        <i class="ph ph-globe me-2"></i>{{ __('events.mode_public') }}
                                        <small class="d-block text-muted">{{ __('events.public_event_description') }}</small>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" name="is_public" id="private" value="0" class="form-check-input">
                                    <label for="private" class="form-check-label">
                                        <i class="ph ph-lock me-2"></i>{{ __('events.mode_private') }}
                                        <small class="d-block text-muted">{{ __('events.private_event_description') }}</small>
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('events.event_category') }} *</label>
                                <select name="category" id="category" class="form-select" required>
                                    <option value="">{{ __('events.category_placeholder') }}</option>
                                    @foreach(App\Models\Event::getCategories() as $key => $name)
                                        <option value="{{ $key }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">{{ __('events.category_help') }}</small>
                                <div class="error-feedback" id="category-error"></div>
                            </div>

                            <!-- Inviti per eventi privati -->
                            <div class="col-12 mb-3" id="private-invites-section" style="display: none;">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">
                                            <i class="ph ph-users me-2"></i>{{ __('events.invite_users') }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted mb-3">{{ __('events.invite_users_help') }}</p>

                                        <!-- Barra di ricerca -->
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('events.search_users') }}</label>
                                            <div class="input-group">
                                                <input type="text" id="userSearchInput" class="form-control" placeholder="{{ __('events.search_users') }}" onkeydown="handleUserSearchKeydown(event)">
                                                <button type="button" class="btn btn-outline-primary" onclick="searchUsersForInvite()">
                                                    <i class="ph ph-magnifying-glass"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Risultati ricerca -->
                                        <div id="searchResultsInvite" class="mb-3" style="display: none;">
                                            <h6>Risultati Ricerca</h6>
                                            <div id="searchResultsListInvite" class="list-group">
                                                <!-- Risultati qui -->
                                            </div>
                                        </div>

                                        <!-- Utenti suggeriti -->
                                        <div class="mb-3">
                                            <h6>{{ __('events.suggested_users') }}</h6>
                                            <p class="text-muted small">{{ __('events.suggested_users_help') }}</p>
                                            <div id="suggestedUsersList" class="row g-2">
                                                <!-- Utenti suggeriti qui -->
                                            </div>
                                        </div>

                                        <!-- Utenti invitati -->
                                        <div>
                                            <h6>{{ __('events.invited_users') }} <span id="inviteCount" class="badge bg-primary">0</span></h6>
                                            <div id="invitedUsersList" class="row g-2">
                                                <div class="col-12 text-center text-muted py-3" id="noInvitedUsers">
                                                    <i class="ph ph-user-plus f-s-24 mb-2"></i>
                                                    <p class="mb-0">{{ __('events.no_invited_users') }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Hidden input per i dati degli inviti -->
                                        <input type="hidden" name="invited_users" id="invitedUsersData" value="[]">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Date and Location -->
                <div class="card d-none" id="step-2">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph ph-calendar-clock me-2"></i>Data e Luogo
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Date and Time -->
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" name="start_datetime" id="start_datetime" class="form-control flatpickr-input" placeholder="Seleziona data e ora inizio..." required readonly>
                                    <label for="start_datetime">{{ __('events.start_date') }} {{ __('events.start_time') }} *</label>
                                </div>
                                <div class="error-feedback" id="start_datetime-error"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text" name="end_datetime" id="end_datetime" class="form-control flatpickr-input" placeholder="Seleziona data e ora fine..." required readonly>
                                    <label for="end_datetime">{{ __('events.end_date') }} {{ __('events.end_time') }} *</label>
                                </div>
                                <div class="error-feedback" id="end_datetime-error"></div>
                            </div>
                            </div>

                            <!-- Online Event Option -->
                            <div class="col-12 mb-3">
                                <div class="card border-info">
                                    <div class="card-header bg-light-info">
                                        <div class="form-check">
                                            <input type="checkbox" name="is_online" id="is_online" class="form-check-input" value="1">
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
                                                        <option value="">{{ __('events.select_timezone') }}</option>
                                                        <option value="Europe/Rome" selected>Europe/Rome (UTC+1/+2)</option>
                                                        <option value="Europe/London">Europe/London (UTC+0/+1)</option>
                                                        <option value="Europe/Paris">Europe/Paris (UTC+1/+2)</option>
                                                        <option value="Europe/Berlin">Europe/Berlin (UTC+1/+2)</option>
                                                        <option value="Europe/Madrid">Europe/Madrid (UTC+1/+2)</option>
                                                        <option value="Europe/Amsterdam">Europe/Amsterdam (UTC+1/+2)</option>
                                                        <option value="Europe/Brussels">Europe/Brussels (UTC+1/+2)</option>
                                                        <option value="Europe/Vienna">Europe/Vienna (UTC+1/+2)</option>
                                                        <option value="Europe/Zurich">Europe/Zurich (UTC+1/+2)</option>
                                                        <option value="Europe/Stockholm">Europe/Stockholm (UTC+1/+2)</option>
                                                        <option value="Europe/Oslo">Europe/Oslo (UTC+1/+2)</option>
                                                        <option value="Europe/Copenhagen">Europe/Copenhagen (UTC+1/+2)</option>
                                                        <option value="Europe/Helsinki">Europe/Helsinki (UTC+2/+3)</option>
                                                        <option value="Europe/Warsaw">Europe/Warsaw (UTC+1/+2)</option>
                                                        <option value="Europe/Prague">Europe/Prague (UTC+1/+2)</option>
                                                        <option value="Europe/Budapest">Europe/Budapest (UTC+1/+2)</option>
                                                        <option value="Europe/Bucharest">Europe/Bucharest (UTC+2/+3)</option>
                                                        <option value="Europe/Sofia">Europe/Sofia (UTC+2/+3)</option>
                                                        <option value="Europe/Zagreb">Europe/Zagreb (UTC+1/+2)</option>
                                                        <option value="Europe/Ljubljana">Europe/Ljubljana (UTC+1/+2)</option>
                                                        <option value="Europe/Athens">Europe/Athens (UTC+2/+3)</option>
                                                        <option value="Europe/Nicosia">Europe/Nicosia (UTC+2/+3)</option>
                                                        <option value="Europe/Valletta">Europe/Valletta (UTC+1/+2)</option>
                                                        <option value="America/New_York">America/New_York (UTC-5/-4)</option>
                                                        <option value="America/Chicago">America/Chicago (UTC-6/-5)</option>
                                                        <option value="America/Denver">America/Denver (UTC-7/-6)</option>
                                                        <option value="America/Los_Angeles">America/Los_Angeles (UTC-8/-7)</option>
                                                        <option value="America/Toronto">America/Toronto (UTC-5/-4)</option>
                                                        <option value="America/Vancouver">America/Vancouver (UTC-8/-7)</option>
                                                        <option value="America/Mexico_City">America/Mexico_City (UTC-6/-5)</option>
                                                        <option value="America/Sao_Paulo">America/Sao_Paulo (UTC-3/-2)</option>
                                                        <option value="America/Buenos_Aires">America/Buenos_Aires (UTC-3)</option>
                                                        <option value="America/Santiago">America/Santiago (UTC-3/-4)</option>
                                                        <option value="Australia/Sydney">Australia/Sydney (UTC+10/+11)</option>
                                                        <option value="Australia/Melbourne">Australia/Melbourne (UTC+10/+11)</option>
                                                        <option value="Australia/Perth">Australia/Perth (UTC+8)</option>
                                                        <option value="Pacific/Auckland">Pacific/Auckland (UTC+12/+13)</option>
                                                        <option value="Asia/Tokyo">Asia/Tokyo (UTC+9)</option>
                                                        <option value="Asia/Seoul">Asia/Seoul (UTC+9)</option>
                                                        <option value="Asia/Shanghai">Asia/Shanghai (UTC+8)</option>
                                                        <option value="Asia/Singapore">Asia/Singapore (UTC+8)</option>
                                                        <option value="Asia/Kuala_Lumpur">Asia/Kuala_Lumpur (UTC+8)</option>
                                                        <option value="Asia/Jakarta">Asia/Jakarta (UTC+7)</option>
                                                        <option value="Asia/Manila">Asia/Manila (UTC+8)</option>
                                                        <option value="Asia/Bangkok">Asia/Bangkok (UTC+7)</option>
                                                        <option value="Asia/Ho_Chi_Minh">Asia/Ho_Chi_Minh (UTC+7)</option>
                                                        <option value="Asia/Dubai">Asia/Dubai (UTC+4)</option>
                                                        <option value="Asia/Riyadh">Asia/Riyadh (UTC+3)</option>
                                                        <option value="Asia/Kolkata">Asia/Kolkata (UTC+5:30)</option>
                                                        <option value="Asia/Bangkok">Asia/Bangkok (UTC+7)</option>
                                                        <option value="Asia/Ho_Chi_Minh">Asia/Ho_Chi_Minh (UTC+7)</option>
                                                        <option value="Asia/Dubai">Asia/Dubai (UTC+4)</option>
                                                        <option value="Asia/Riyadh">Asia/Riyadh (UTC+3)</option>
                                                        <option value="Asia/Kolkata">Asia/Kolkata (UTC+5:30)</option>
                                                        <option value="Asia/Tehran">Asia/Tehran (UTC+3:30/+4:30)</option>
                                                        <option value="Asia/Jerusalem">Asia/Jerusalem (UTC+2/+3)</option>
                                                        <option value="Africa/Cairo">Africa/Cairo (UTC+2/+3)</option>
                                                        <option value="Africa/Johannesburg">Africa/Johannesburg (UTC+2)</option>
                                                        <option value="Africa/Lagos">Africa/Lagos (UTC+1)</option>
                                                        <option value="Africa/Nairobi">Africa/Nairobi (UTC+3)</option>
                                                        <option value="Africa/Casablanca">Africa/Casablanca (UTC+0/+1)</option>
                                                        <option value="Africa/Tunis">Africa/Tunis (UTC+1/+2)</option>
                                                        <option value="Africa/Algiers">Africa/Algiers (UTC+1)</option>
                                                    </select>
                                                    <label for="timezone">{{ __('events.timezone') }} *</label>
                                                </div>
                                                <small class="text-muted">{{ __('events.timezone_help') }}</small>
                                            </div>

                                            <!-- Online URL -->
                                            <div class="col-md-6 mb-3">
                                                <div class="form-floating">
                                                    <input type="url" name="online_url" id="online_url" class="form-control" placeholder="{{ __('events.online_url_placeholder') }}">
                                                    <label for="online_url">{{ __('events.online_url') }}</label>
                                                </div>
                                                <small class="text-muted">{{ __('events.online_url_help') }}</small>
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
 @if($recentVenues->count() > 0)
 <div class="col-12 mb-3" id="recent-venues-section">
     <div class="form-floating">
         <select name="recent_venue" id="recent_venue" class="form-select" onchange="loadRecentVenueFromDropdown(this.value)">
             <option value="">{{ __('events.select_recent_venue') }}</option>
             @foreach($recentVenues as $venue)
                 <option value="{{ $venue->id }}" data-venue="{{ json_encode($venue) }}">
                     {{ $venue->venue_name }} - {{ $venue->venue_address }}, {{ $venue->city }} ({{ $venue->usage_count }} volte)
                 </option>
             @endforeach
         </select>
         <label for="recent_venue">
             <i class="ph ph-clock-counter-clockwise me-2"></i>{{ __('events.recent_venues') }}
         </label>
     </div>
     <small class="text-muted">{{ __('events.recent_venues_help') }}</small>
 </div>
 @endif
                            <!-- Location -->
                            <div class="col-12 mb-3" id="venue-name-container">
                                <div class="form-floating">
                                                                    <input type="text" name="venue_name" id="venue_name" class="form-control" placeholder="{{ __('events.venue_name_placeholder') }}" required>
                                <label for="venue_name">{{ __('events.venue_name') }} *</label>
                                </div>
                                <div class="error-feedback" id="venue_name-error"></div>
                            </div>

                           

                            <div class="col-md-6 mb-3" id="venue-address-container">
                                <div class="form-floating">
                                    <input type="text" name="venue_address" id="venue_address" class="form-control" placeholder="{{ __('events.venue_address_placeholder') }}" required>
                                    <label for="venue_address">{{ __('events.venue_address') }} *</label>
                                </div>
                                <div class="error-feedback" id="venue_address-error"></div>
                            </div>

                            <div class="col-md-3 mb-3" id="city-container">
                                <div class="form-floating">
                                    <input type="text" name="city" id="city" class="form-control" placeholder="{{ __('events.city_placeholder') }}" required>
                                    <label for="city">{{ __('events.city') }} *</label>
                                </div>
                                <div class="error-feedback" id="city-error"></div>
                            </div>

                            <div class="col-md-3 mb-3" id="postcode-container">
                                <div class="form-floating">
                                    <input type="text" name="postcode" id="postcode" class="form-control" placeholder="{{ __('events.postcode_placeholder') }}" required>
                                    <label for="postcode">{{ __('events.postcode') }} *</label>
                                </div>
                                <div class="error-feedback" id="postcode-error"></div>
                            </div>

                            <div class="col-md-6 mb-3" id="country-container">
                                <div class="form-floating">
                                    <select name="country" id="country" class="form-select" required>
                                        <option value="">Seleziona paese...</option>
                                        <option value="IT" selected>Italia</option>
                                        <option value="FR">Francia</option>
                                        <option value="ES">Spagna</option>
                                        <option value="DE">Germania</option>
                                        <option value="CH">Svizzera</option>
                                        <option value="AT">Austria</option>
                                        <option value="BE">Belgio</option>
                                        <option value="NL">Paesi Bassi</option>
                                        <option value="PT">Portogallo</option>
                                        <option value="GB">Regno Unito</option>
                                        <option value="IE">Irlanda</option>
                                        <option value="SE">Svezia</option>
                                        <option value="NO">Norvegia</option>
                                        <option value="DK">Danimarca</option>
                                        <option value="FI">Finlandia</option>
                                        <option value="PL">Polonia</option>
                                        <option value="CZ">Repubblica Ceca</option>
                                        <option value="SK">Slovacchia</option>
                                        <option value="HU">Ungheria</option>
                                        <option value="RO">Romania</option>
                                        <option value="BG">Bulgaria</option>
                                        <option value="HR">Croazia</option>
                                        <option value="SI">Slovenia</option>
                                        <option value="GR">Grecia</option>
                                        <option value="CY">Cipro</option>
                                        <option value="MT">Malta</option>
                                        <option value="US">Stati Uniti</option>
                                        <option value="CA">Canada</option>
                                        <option value="MX">Messico</option>
                                        <option value="BR">Brasile</option>
                                        <option value="AR">Argentina</option>
                                        <option value="CL">Cile</option>
                                        <option value="AU">Australia</option>
                                        <option value="NZ">{{ __('notifications.new') }} Zelanda</option>
                                        <option value="JP">Giappone</option>
                                        <option value="KR">Corea del Sud</option>
                                        <option value="CN">Cina</option>
                                        <option value="IN">India</option>
                                        <option value="TH">Thailandia</option>
                                        <option value="SG">Singapore</option>
                                        <option value="MY">Malesia</option>
                                        <option value="ID">Indonesia</option>
                                        <option value="PH">Filippine</option>
                                        <option value="VN">Vietnam</option>
                                        <option value="RU">Russia</option>
                                        <option value="UA">Ucraina</option>
                                        <option value="BY">Bielorussia</option>
                                        <option value="TR">Turchia</option>
                                        <option value="IL">Israele</option>
                                        <option value="AE">Emirati Arabi Uniti</option>
                                        <option value="SA">Arabia Saudita</option>
                                        <option value="EG">Egitto</option>
                                        <option value="ZA">Sudafrica</option>
                                        <option value="NG">Nigeria</option>
                                        <option value="KE">Kenya</option>
                                        <option value="MA">Marocco</option>
                                        <option value="TN">Tunisia</option>
                                        <option value="DZ">Algeria</option>
                                    </select>
                                    <label for="country">Paese *</label>
                                </div>
                            </div>

                            <div class="col-12 mb-3" id="map-info-banner-container">
                                <div class="alert alert-info" id="map-info-banner">
                                    <i class="ph ph-info me-2"></i>
                                    <strong>{{ __('events.auto_positioning_title') }}:</strong> {{ __('events.auto_positioning_description') }}
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
                                    <i class="ph ph-spinner-gap me-1"></i> {{ __('events.auto_positioning_status') }}
                                </div>
                            </div>

                            <!-- Venue Owner -->
                            @if($venueOwners->count() > 0)
                            <div class="col-12 mb-3">
                                <div class="form-floating">
                                    <select name="venue_owner_id" id="venue_owner_id" class="form-select">
                                        <option value="">Nessun proprietario specifico</option>
                                        @foreach($venueOwners as $owner)
                                            <option value="{{ $owner->id }}">{{ $owner->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="venue_owner_id">Proprietario Venue (Opzionale)</label>
                                </div>
                                <small class="text-muted">Seleziona se conosci il proprietario del venue</small>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Step 3: Details -->
                <div class="card d-none" id="step-3">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph ph-gear me-2"></i>{{ __('events.step_event_details') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Requirements -->
                            <div class="col-12 mb-3">
                                <div class="form-floating">
                                    <textarea name="requirements" id="requirements" class="form-control" style="height: 100px" placeholder="Requisiti"></textarea>
                                    <label for="requirements">{{ __('events.requirements_participants') }}</label>
                                </div>
                                <small class="text-muted">{{ __('events.requirements_help') }}</small>
                            </div>

                            <!-- Participants and Fee -->
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="number" name="max_participants" id="max_participants" class="form-control" min="1" placeholder="Numero massimo">
                                    <label for="max_participants">{{ __('events.max_participants_optional') }}</label>
                                </div>
                                <small class="text-muted">{{ __('events.no_limit_help') }}</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="number" name="entry_fee" id="entry_fee" class="form-control" min="0" step="0.01" value="0" placeholder="Costo">
                                                                    <label for="entry_fee">{{ __('events.entry_fee') }} (€)</label>
                            </div>
                            <small class="text-muted">{{ __('events.entry_fee_help') }}</small>
                            </div>



                            <!-- Image Upload -->
                            <div class="col-12 mb-3">
                                <label class="form-label">{{ __('events.event_image') }} ({{ __('common.optional') }})</label>
                                <input type="file" name="event_image" id="event_image" class="form-control" accept="image/*">
                                <small class="text-muted">{{ __('events.image_format_help') }}</small>
                                <div class="mt-2" id="imagePreview" style="display: none;">
                                    <img id="previewImg" src="" class="img-thumbnail" style="max-height: 200px;">
                                </div>
                            </div>

                            <!-- Event Status -->
                            <div class="col-12 mb-3">
                                <label class="form-label">{{ __('events.event_status') }}</label>
                                <div class="form-check">
                                    <input type="radio" name="status" id="published" value="published" class="form-check-input" checked>
                                    <label for="published" class="form-check-label">
                                        <i class="ph ph-globe me-2"></i>{{ __('events.publish_immediately') }}
                                        <small class="d-block text-muted">{{ __('events.publish_immediately_help') }}</small>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" name="status" id="draft" value="draft" class="form-check-input">
                                    <label for="draft" class="form-check-label">
                                        <i class="ph ph-note-pencil me-2"></i>{{ __('events.save_as_draft') }}
                                        <small class="d-block text-muted">{{ __('events.save_as_draft_help') }}</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Inviti e Ingaggi -->
                <div class="card d-none" id="step-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph ph-users me-2"></i>Inviti e Ingaggi
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Sezione Inviti -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="ph ph-envelope me-2"></i>Inviti Artisti
                            </h6>
                            <div class="alert alert-border-primary" role="alert">
                                <h6>
                                    <i class="ph ph-info-circle f-s-18 me-2 text-info"></i>
                                    Inviti Specifici
                                </h6>
                                <p class="mb-0">
                                    Invita artisti specifici al tuo evento. Se accettano, il loro nome apparirà confermato (se la lineup è pubblica). Se rifiutano, riceverai una notifica per aprire posizioni d'ingaggio.
                                </p>
                            </div>

                            <!-- Search Users -->
                            <div class="mb-4">
                                <label class="form-label">Cerca Artisti da Invitare</label>
                                <div class="input-group">
                                    <input type="text" id="userSearchInput" class="form-control" placeholder="{{ __('events.search_users') }}" onkeydown="handleUserSearchKeydown(event)">
                                    <button type="button" class="btn btn-outline-primary" onclick="searchUsersForInvite()">
                                        <i class="ph ph-magnifying-glass"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Search Results -->
                            <div id="searchResultsInvite" class="mb-3" style="display: none;">
                                <h6>Risultati Ricerca</h6>
                                <div id="searchResultsListInvite" class="list-group">
                                    <!-- Risultati qui -->
                                </div>
                            </div>

                            <!-- Utenti suggeriti -->
                            <div class="mb-3">
                                <h6>{{ __('events.suggested_users') }}</h6>
                                <p class="text-muted small">{{ __('events.suggested_users_help') }}</p>
                                <div id="suggestedUsersList" class="row g-2">
                                    <!-- Utenti suggeriti qui -->
                                </div>
                            </div>

                            <!-- Utenti invitati -->
                            <div>
                                <h6>{{ __('events.invited_users') }} <span id="inviteCount" class="badge bg-primary">0</span></h6>
                                <div id="invitedUsersList" class="row g-2">
                                    <div class="col-12 text-center text-muted py-3" id="noInvitedUsers">
                                        <i class="ph ph-user-plus f-s-24 mb-2"></i>
                                        <p class="mb-0">{{ __('events.no_invited_users') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden input per i dati degli inviti -->
                            <input type="hidden" name="invited_users" id="invitedUsersData" value="[]">
                        </div>

                        <!-- Sezione Posizioni d'Ingaggio -->
                        <div class="mb-4">
                            <h6 class="text-success mb-3">
                                <i class="ph ph-briefcase me-2"></i>Apri Posizioni d'Ingaggio
                            </h6>
                            <div class="alert alert-border-success" role="alert">
                                <h6>
                                    <i class="ph ph-info-circle f-s-18 me-2 text-success"></i>
                                    Posizioni Aperte
                                </h6>
                                <p class="mb-0">
                                    Crea posizioni d'ingaggio per trovare artisti e professionisti. Ogni tipologia creerà una posizione nell'area Gig.
                                </p>
                            </div>

                            <!-- Container per le posizioni d'ingaggio -->
                            <div id="gigPositionsContainer">
                                <!-- Le posizioni verranno aggiunte qui dinamicamente -->
                            </div>

                            <!-- Pulsante per aggiungere nuova posizione -->
                            <div class="text-center mt-3">
                                <button type="button" class="btn btn-success" onclick="addGigPosition()">
                                    <i class="ph ph-plus me-2"></i>Aggiungi Posizione d'Ingaggio
                                </button>
                            </div>

                            <!-- Hidden input for gig positions data -->
                            <input type="hidden" name="gig_positions" id="gigPositionsData" value="[]">
                        </div>
                    </div>
                </div>

                <!-- Role Selection Modal -->
                <div class="modal fade" id="roleSelectionModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="ph ph-user-circle-plus me-2"></i>Invita Artista
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="text-center mb-3">
                                    <div class="participant-avatar mx-auto mb-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #0f626a 0%, #0c4e55 100%);">
                                        <span id="selectedUserInitials"></span>
                                    </div>
                                    <h6 id="selectedUserName"></h6>
                                    <small class="text-muted" id="selectedUserEmail"></small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Seleziona {{ __('invitations.role') }} *</label>
                                    <div class="check-container">
                                        <label class="check-box d-flex align-items-center p-3 border rounded mb-2" style="cursor: pointer;">
                                            <input type="radio" name="invitationRole" value="performer" checked>
                                            <span class="radiomark check-primary ms-2"></span>
                                            <div class="ms-3">
                                                <i class="ph ph-microphone me-2"></i>
                                                <span class="fw-bold">Performer</span>
                                                <small class="d-block text-muted">Artista che si esibisce sul palco</small>
                                            </div>
                                        </label>

                                        <label class="check-box d-flex align-items-center p-3 border rounded mb-2" style="cursor: pointer;">
                                            <input type="radio" name="invitationRole" value="judge">
                                            <span class="radiomark check-primary ms-2"></span>
                                            <div class="ms-3">
                                                <i class="ph ph-scales me-2"></i>
                                                <span class="fw-bold">Judge</span>
                                                <small class="d-block text-muted">Giudice della competizione</small>
                                            </div>
                                        </label>

                                        <label class="check-box d-flex align-items-center p-3 border rounded mb-2" style="cursor: pointer;">
                                            <input type="radio" name="invitationRole" value="technician">
                                            <span class="radiomark check-primary ms-2"></span>
                                            <div class="ms-3">
                                                <i class="ph ph-gear me-2"></i>
                                                <span class="fw-bold">Technician</span>
                                                <small class="d-block text-muted">Supporto tecnico audio/video</small>
                                            </div>
                                        </label>

                                        <label class="check-box d-flex align-items-center p-3 border rounded" style="cursor: pointer;">
                                            <input type="radio" name="invitationRole" value="host">
                                            <span class="radiomark check-primary ms-2"></span>
                                            <div class="ms-3">
                                                <i class="ph ph-user-focus me-2"></i>
                                                <span class="fw-bold">Host</span>
                                                <small class="d-block text-muted">Conduttore dell'evento</small>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Messaggio Personalizzato (Opzionale)</label>
                                    <textarea id="invitationMessage" class="form-control" rows="3" placeholder="Aggiungi un messaggio personalizzato per l'invito..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.cancel') }}</button>
                                <button type="button" class="btn btn-primary" onclick="confirmInvitation()">
                                    <i class="ph ph-paper-plane me-2"></i>{{ __('events.send_invitation') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 5: Preview -->
                <div class="card d-none" id="step-5">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph ph-eye me-2"></i>{{ __('events.preview_event') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="eventPreview" class="preview-card">
                            <!-- Dynamic preview will be generated here -->
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-light-primary btn-lg px-5" id="submitBtn">
                                <i class="ph ph-check-circle me-2"></i>{{ __('events.create_event_action') }}
                            </button>
                            <div class="mt-2" id="submitStatus" style="display: none;">
                                <small class="text-muted">
                                    <i class="ph ph-spinner-gap me-1"></i>{{ __('events.creation_in_progress') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar with Navigation -->
            <div class="col-lg-4">
                <div class="card position-sticky" style="top: 20px;">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="ph ph-navigation-arrow me-2"></i>{{ __('events.navigation') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- Step Navigation -->
                        <div class="d-flex justify-content-between mb-4">
                            <button type="button" class="btn btn-light-secondary" id="prevStep" disabled>
                                <i class="ph ph-arrow-left me-1"></i>{{ __('events.previous_step') }}
                            </button>
                            <button type="button" class="btn btn-light-primary" id="nextStep">
                                {{ __('events.next_step') }}<i class="ph ph-arrow-right ms-1"></i>
                            </button>
                        </div>

                        <!-- Progress -->
                        <div class="mb-3">
                            <label class="form-label">{{ __('events.progress') }}</label>
                            <div class="progress">
                                <div class="progress-bar bg-primary" id="progressBar" style="width: 25%"></div>
                            </div>
                            <small class="text-muted">{{ __('events.step_progress') }} <span id="currentStep">1</span> {{ __('events.of') }} 5</small>
                        </div>

                        <!-- Quick Tips -->
                        <div class="alert alert-light-info" role="alert">
                            <h6 class="text-info">
                                <i class="ph ph-lightbulb me-2"></i>{{ __('events.tip') }}
                            </h6>
                            <p class="mb-0 small text-info" id="stepTip">
                                Scegli un titolo accattivante che descriva chiaramente il tuo evento.
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
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/leafletmaps/leaflet.js') }}"></script>
<script>
// Test di base per verificare se il JavaScript si carica
console.log('=== JAVASCRIPT LOADED ===');

let currentStep = 1;
let map = null;
let marker = null;
let tags = [];
let selectedInvitations = [];

const stepTips = {
    1: "Scegli un titolo accattivante che descriva chiaramente il tuo evento.",
    2: "Imposta data, ora e luogo dell'evento. La mappa ti aiuterà a trovare la posizione esatta.",
    3: "Aggiungi dettagli, tag e configura le impostazioni dell'evento.",
    4: "Invita artisti specifici al tuo evento. Potrai sempre farlo anche dopo.",
    5: "Rivedi tutti i dettagli prima di pubblicare l'evento."
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
    console.log('DOM Content Loaded - Starting initialization');

    // Aspetta un momento per essere sicuri che tutto il DOM sia pronto
    setTimeout(() => {
        try {
            console.log('Initializing form...');
            initializeForm();
            console.log('Setting up event listeners...');
            setupEventListeners();

            // ========================================
            // INIZIALIZZAZIONE EVENTI RICORRENTI
            // ========================================
            console.log('=== INIZIALIZZAZIONE RICORRENZA ===');

            const isRecurringCheckbox = document.getElementById('is_recurring');
            const recurrenceSettings = document.getElementById('recurrence-settings');

            console.log('Checkbox found:', isRecurringCheckbox);
            console.log('Settings found:', recurrenceSettings);

            if (isRecurringCheckbox && recurrenceSettings) {
                console.log('Setting up recurrence checkbox listener');
                isRecurringCheckbox.addEventListener('change', function() {
                    console.log('Checkbox changed:', this.checked);
                    if (this.checked) {
                        recurrenceSettings.style.display = 'block';
                        updateRecurrencePreview();
                    } else {
                        recurrenceSettings.style.display = 'none';
                    }
                });
            } else {
                console.error('Recurrence elements not found!');
                console.log('Available elements with "recurrence" in ID:');
                document.querySelectorAll('[id*="recurrence"]').forEach(el => {
                    console.log('-', el.id, el.tagName);
                });
            }

            // ========================================
            // INIZIALIZZAZIONE EVENTI ONLINE
            // ========================================
            console.log('=== INIZIALIZZAZIONE EVENTI ONLINE ===');

            const isOnlineCheckbox = document.getElementById('is_online');
            const onlineEventSettings = document.getElementById('online-event-settings');

            console.log('Online checkbox found:', isOnlineCheckbox);
            console.log('Online settings found:', onlineEventSettings);

            if (isOnlineCheckbox && onlineEventSettings) {
                console.log('Setting up online event checkbox listener');
                
                // Controlla lo stato iniziale della checkbox
                console.log('Initial checkbox state:', isOnlineCheckbox.checked);
                if (isOnlineCheckbox.checked) {
                    console.log('Checkbox is checked, hiding location fields...');
                    onlineEventSettings.style.display = 'block';
                    makeLocationFieldsOptional();
                } else {
                    console.log('Checkbox is not checked, showing location fields...');
                    onlineEventSettings.style.display = 'none';
                    makeLocationFieldsRequired();
                }
                
                isOnlineCheckbox.addEventListener('change', function() {
                    console.log('Online checkbox changed:', this.checked);
                    if (this.checked) {
                        console.log('Checkbox checked, hiding location fields...');
                        onlineEventSettings.style.display = 'block';
                        // Rendi i campi del luogo opzionali per eventi online
                        makeLocationFieldsOptional();
                    } else {
                        console.log('Checkbox unchecked, showing location fields...');
                        onlineEventSettings.style.display = 'none';
                        // Rendi i campi del luogo obbligatori per eventi fisici
                        makeLocationFieldsRequired();
                    }
                });
            } else {
                console.error('Online event elements not found!');
                console.log('Available elements with "online" in ID:');
                document.querySelectorAll('[id*="online"]').forEach(el => {
                    console.log('-', el.id, el.tagName);
                });
            }

            // Assicurati che la mappa sia visibile di default per eventi fisici
            const mapContainer = document.getElementById('locationMap');
            if (mapContainer && !isOnlineCheckbox?.checked) {
                console.log('Making map visible for physical events');
                mapContainer.style.display = 'block';
                // Assicurati che anche il container della mappa sia visibile
                const mapSection = mapContainer.closest('.col-12');
                if (mapSection) {
                    mapSection.style.display = 'block';
                }
            }

            // Inizializza la mappa se siamo già al passo 2
            if (currentStep === 2) {
                console.log('Already on step 2, initializing map immediately');
                setTimeout(initializeMap, 200);
            }

            // Controllo aggiuntivo per assicurarsi che il JavaScript venga eseguito
            setTimeout(() => {
                console.log('=== CONTROLLO AGGIUNTIVO DOPO 500ms ===');
                const isOnlineCheckbox = document.getElementById('is_online');
                if (isOnlineCheckbox && isOnlineCheckbox.checked) {
                    console.log('Checkbox still checked after 500ms, forcing hide...');
                    makeLocationFieldsOptional();
                }
            }, 500);

            // Funzione per rendere i campi del luogo opzionali
            function makeLocationFieldsOptional() {
                console.log('=== NASCONDO TUTTA LA LOCALIZZAZIONE FISICA ===');
                
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
                        console.log('Nascosto elemento:', elementId);
                    } else {
                        console.log('Elemento non trovato:', elementId);
                    }
                });
                
                // Rendi opzionali tutti i campi di localizzazione
                fieldsToMakeOptional.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field) {
                        field.required = false;
                        console.log('Campo reso opzionale:', fieldId);
                        
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
                    console.log('Nascosta mappa');
                }
                
                // Nascondi il container della mappa
                const mapSection = mapContainer?.closest('.col-12');
                if (mapSection) {
                    mapSection.style.display = 'none';
                }
                
                console.log('=== LOCALIZZAZIONE FISICA COMPLETAMENTE NASCOSTA ===');
            }

            // Funzione per rendere i campi del luogo obbligatori
            function makeLocationFieldsRequired() {
                console.log('=== MOSTRO TUTTA LA LOCALIZZAZIONE FISICA ===');
                
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
                        console.log('Mostrato elemento:', elementId);
                    } else {
                        console.log('Elemento non trovato:', elementId);
                    }
                });
                
                // Rendi obbligatori tutti i campi di localizzazione
                fieldsToMakeRequired.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field) {
                        field.required = true;
                        console.log('Campo reso obbligatorio:', fieldId);
                        
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
                    console.log('Mostrata mappa');
                }
                
                // Mostra il container della mappa
                const mapSection = mapContainer?.closest('.col-12');
                if (mapSection) {
                    mapSection.style.display = 'block';
                }
                
                console.log('=== LOCALIZZAZIONE FISICA COMPLETAMENTE MOSTRATA ===');
            }

            console.log('Initialization completed successfully');
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
    const registrationDeadline = document.getElementById('registration_deadline');

    if (startDateTime) startDateTime.min = localDateTime;
    if (endDateTime) endDateTime.min = localDateTime;
    if (registrationDeadline) registrationDeadline.min = localDateTime;
}

function initializeMap() {
    console.log('=== INITIALIZING MAP ===');
    
    // Controlla se l'elemento mappa esiste e se la mappa non è già inizializzata
    const mapContainer = document.getElementById('locationMap');
    console.log('Map container found:', mapContainer);
    console.log('Map already initialized:', map !== null);
    
    if (!mapContainer) {
        console.error('Map container not found!');
        return;
    }
    
    if (map !== null) {
        console.log('Map already initialized, skipping');
        return;
    }

    console.log('Creating new map...');
    map = L.map('locationMap').setView([41.9028, 12.4964], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    map.on('click', function(e) {
        setMapLocation(e.latlng.lat, e.latlng.lng);
    });

    // Force map to resize after initialization
    setTimeout(() => {
        if (map) {
            console.log('Invalidating map size...');
            map.invalidateSize();
        }
    }, 200);
    
    console.log('Map initialization completed');
}

function setupEventListeners() {
    // Step navigation - check if elements exist
    const nextStepBtn = document.getElementById('nextStep');
    const prevStepBtn = document.getElementById('prevStep');

    if (nextStepBtn) {
        console.log('Next step button found, adding event listener');
        console.log('Button text:', nextStepBtn.textContent);
        console.log('Button disabled:', nextStepBtn.disabled);
        console.log('Button style:', nextStepBtn.style.display);

        nextStepBtn.addEventListener('click', function(e) {
            console.log('Next step button clicked!');
            e.preventDefault();
            nextStep();
        });
    } else {
        console.error('Next step button not found!');
        console.error('Available buttons:', document.querySelectorAll('button').length);
        document.querySelectorAll('button').forEach((btn, index) => {
            console.log(`Button ${index}:`, btn.id, btn.textContent);
        });
    }
    if (prevStepBtn) {
        console.log('Prev step button found, adding event listener');
        prevStepBtn.addEventListener('click', prevStep);
    } else {
        console.error('Prev step button not found!');
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
    console.log('=== SETTING UP RECURRENCE EVENT LISTENERS ===');

    const isRecurringCheckbox = document.getElementById('is_recurring');
    const recurrenceSettings = document.getElementById('recurrence-settings');

    console.log('Checkbox found:', isRecurringCheckbox);
    console.log('Settings found:', recurrenceSettings);

    if (isRecurringCheckbox && recurrenceSettings) {
        isRecurringCheckbox.addEventListener('change', function() {
            console.log('Checkbox changed:', this.checked);
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
    ['title', 'description', 'venue_name', 'city', 'entry_fee', 'start_datetime'].forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('input', updatePreview);
        }
    });

    // User search functionality
    const userSearchInput = document.getElementById('userSearchInput');
    if (userSearchInput) {
        userSearchInput.addEventListener('keydown', handleUserSearchKeydown);
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
}

function nextStep() {
    console.log('nextStep called, currentStep:', currentStep);

    const validationResult = validateCurrentStep();
    console.log('Validation result:', validationResult);

    if (validationResult) {
        console.log('Validation passed, proceeding to next step');
        if (currentStep < 5) {
            currentStep++;
            console.log('Moving to step:', currentStep);
            showStep(currentStep);
            updateProgress();
            if (currentStep === 5) {
                updatePreview();
            }
        } else {
            console.log('Already at last step');
        }
    } else {
        console.log('Validation failed, cannot proceed');
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

        console.log('Step 1 validation - title:', title, 'description:', description, 'category:', category);

        if (!title) {
            console.log('Title is empty');
            showError('title', 'Il titolo è obbligatorio');
            highlightError('title');
            isValid = false;
        } else if (title.length < 5) {
            console.log('Title too short:', title.length);
            showError('title', 'Il titolo deve essere di almeno 5 caratteri');
            highlightError('title');
            isValid = false;
        }

        // Description is optional - no minimum length requirement
        // if (description && description.length < 20) {
        //     console.log('Description too short:', description.length);
        //     showError('description', 'La descrizione deve essere di almeno 20 caratteri');
        //     highlightError('description');
        //     isValid = false;
        // }

        if (!category) {
            console.log('Category is empty');
            showError('category', 'La categoria è obbligatoria');
            highlightError('category');
            isValid = false;
        }
    }

    if (step === 2) {
        const startDateTime = document.getElementById('start_datetime').value;
        const endDateTime = document.getElementById('end_datetime').value;
        const isOnline = document.getElementById('is_online')?.checked || false;
        
        console.log('Step 2 validation - isOnline:', isOnline);

        if (!startDateTime) {
            showError('start_datetime', 'Data e ora di inizio sono obbligatorie');
            highlightError('start_datetime');
            isValid = false;
        }

        if (!endDateTime) {
            showError('end_datetime', 'Data e ora di fine sono obbligatorie');
            highlightError('end_datetime');
            isValid = false;
        } else if (new Date(endDateTime) <= new Date(startDateTime)) {
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
            console.log('Evento online - saltata validazione campi di localizzazione');
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
        console.log('Validating step 3...');
        const maxParticipants = document.getElementById('max_participants').value;

        console.log('Max participants value:', maxParticipants);

        // Il campo è opzionale, ma se viene compilato deve essere maggiore di 0
        if (maxParticipants && maxParticipants <= 0) {
            console.log('Max participants is invalid, showing error');
            showError('max_participants', 'Il numero massimo di partecipanti deve essere maggiore di 0');
            highlightError('max_participants');
            isValid = false;
        }

        console.log('Step 3 validation result:', isValid);
    }

    if (step === 4) {
        const imageElement = document.getElementById('image');
        const acceptsRequestsElement = document.getElementById('accepts_requests');

        // Verifica che gli elementi esistano prima di accedere alle loro proprietà
        if (imageElement && acceptsRequestsElement) {
            const image = imageElement.files[0];
            const acceptsRequests = acceptsRequestsElement.checked;

            if (!image && !acceptsRequests) {
                showError('image', 'Devi caricare un\'immagine o abilitare le richieste di partecipazione');
                highlightError('image');
                highlightError('accepts_requests');
                isValid = false;
            }
        }
    }

    console.log('Validation result:', isValid);
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
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1&addressdetails=1`)
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
        tagEl.innerHTML = `${tag} <span class="ms-1 text-decoration-none" role="button" onclick="removeTag('${tag}')">&times;</span>`;
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

    const title = document.getElementById('title').value || 'Titolo {{ __('invitations.event') }}';
    const description = document.getElementById('description').value || 'Descrizione evento...';
    const venueName = document.getElementById('venue_name').value || 'Nome Venue';
    const city = document.getElementById('city').value || 'Città';
    const startDateTime = document.getElementById('start_datetime').value;
    const entryFee = document.getElementById('entry_fee').value || '0';
    const isPublic = document.querySelector('input[name="is_public"]:checked').value === '1';
        const imageInput = document.getElementById('event_image');

    // Get image preview or use fallback
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

    const formattedDate = startDateTime ? new Date(startDateTime).toLocaleDateString('it-IT', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    }) : 'Data da definire';

    const preview = `
        <div class="position-relative overflow-hidden bg-primary" style="height: 250px;">
            ${imageHtml}
            <span class="badge ${isPublic ? 'bg-light-success' : 'bg-light-warning'} position-absolute top-0 end-0 m-3" style="z-index: 3;">
                <i class="ph ph-${isPublic ? 'globe' : 'lock'} me-1"></i>
                ${isPublic ? 'Pubblico' : 'Privato'}
            </span>
            <div class="position-absolute bottom-0 start-0 text-white p-4 w-100" style="z-index: 3;">
                <h3 class="fw-bold mb-2 text-white">${title}</h3>
                <div class="d-flex align-items-center mb-2">
                    <i class="ph ph-calendar-check me-2"></i>
                    <span>${formattedDate}</span>
                </div>
                <div class="d-flex align-items-center">
                    <i class="ph ph-map-pin me-2"></i>
                    <span>${venueName}, ${city}</span>
                </div>
            </div>
        </div>
        <div class="p-4">
            <div class="mb-4">
                <h6 class="mb-2 text-primary"><i class="ph ph-file-text me-2"></i>Descrizione</h6>
                <p class="mb-0 text-muted">${description}</p>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="border-start border-primary border-4 ps-3">
                        <h6 class="mb-1 text-primary">Costo</h6>
                        <p class="mb-0">${entryFee == 0 ? '{{ __('common.free') }}' : '€' + entryFee}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border-start border-primary border-4 ps-3">
                        <h6 class="mb-1 text-primary">Tipo</h6>
                        <p class="mb-0">${isPublic ? 'Aperto a tutti' : 'Solo su invito'}</p>
                    </div>
                </div>
            </div>

            ${tags.length > 0 ? `
                <div>
                    <h6 class="mb-2 text-primary"><i class="ph ph-tag me-2"></i>Tags</h6>
                    <div class="d-flex flex-wrap gap-1">
                        ${tags.map(tag => `<span class="bg-light-primary rounded px-3 py-1 small">#${tag}</span>`).join('')}
                    </div>
                </div>
            ` : ''}
        </div>
    `;

    document.getElementById('eventPreview').innerHTML = preview;
}

function updatePreviewWithImage(imageSrc) {
    if (currentStep !== 5) return;

    const title = document.getElementById('title').value || 'Titolo {{ __('invitations.event') }}';
    const description = document.getElementById('description').value || 'Descrizione evento...';
    const venueName = document.getElementById('venue_name').value || 'Nome Venue';
    const city = document.getElementById('city').value || 'Città';
    const startDateTime = document.getElementById('start_datetime').value;
    const entryFee = document.getElementById('entry_fee').value || '0';
    const isPublic = document.querySelector('input[name="is_public"]:checked').value === '1';

    const formattedDate = startDateTime ? new Date(startDateTime).toLocaleDateString('it-IT', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    }) : 'Data da definire';

    const preview = `
        <div class="position-relative overflow-hidden" style="height: 250px;">
            <img src="${imageSrc}" alt="${title}" class="position-absolute w-100 h-100" style="object-fit: cover;">
            <div class="position-absolute w-100 h-100 bg-primary" style="opacity: 0.7;"></div>
            <span class="badge ${isPublic ? 'bg-light-success' : 'bg-light-warning'} position-absolute top-0 end-0 m-3" style="z-index: 3;">
                <i class="ph ph-${isPublic ? 'globe' : 'lock'} me-1"></i>
                ${isPublic ? 'Pubblico' : 'Privato'}
            </span>
            <div class="position-absolute bottom-0 start-0 text-white p-4 w-100" style="z-index: 3;">
                <h3 class="fw-bold mb-2 text-white">${title}</h3>
                <div class="d-flex align-items-center mb-2">
                    <i class="ph ph-calendar-check me-2"></i>
                    <span>${formattedDate}</span>
                </div>
                <div class="d-flex align-items-center">
                    <i class="ph ph-map-pin me-2"></i>
                    <span>${venueName}, ${city}</span>
                </div>
            </div>
        </div>
        <div class="p-4">
            <div class="mb-4">
                <h6 class="mb-2 text-primary"><i class="ph ph-file-text me-2"></i>Descrizione</h6>
                <p class="mb-0 text-muted">${description}</p>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="border-start border-primary border-4 ps-3">
                        <h6 class="mb-1 text-primary">Costo</h6>
                        <p class="mb-0">${entryFee == 0 ? '{{ __('common.free') }}' : '€' + entryFee}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border-start border-primary border-4 ps-3">
                        <h6 class="mb-1 text-primary">Tipo</h6>
                        <p class="mb-0">${isPublic ? 'Aperto a tutti' : 'Solo su invito'}</p>
                    </div>
                </div>
            </div>

            ${tags.length > 0 ? `
                <div>
                    <h6 class="mb-2 text-primary"><i class="ph ph-tag me-2"></i>Tags</h6>
                    <div class="d-flex flex-wrap gap-1">
                        ${tags.map(tag => `<span class="bg-light-primary rounded px-3 py-1 small">#${tag}</span>`).join('')}
                    </div>
                </div>
            ` : ''}
        </div>
    `;

    document.getElementById('eventPreview').innerHTML = preview;
}

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
    fetch(`/api/users/search?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            displaySearchResults(data);
        })
        .catch(error => {
            console.error('Error searching users:', error);
            // Fallback with demo data for testing
            const demoUsers = [
                { id: 1, name: 'Marco Poeta', email: 'marco@poetry.it', roles: ['poet'], avatar: null },
                { id: 2, name: 'Sofia Judge', email: 'sofia@slam.it', roles: ['judge'], avatar: null },
                { id: 3, name: 'Alex Tech', email: 'alex@tech.it', roles: ['technician'], avatar: null }
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
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addInvitation(${user.id}, '${user.name}', '${user.email}', ['${user.roles.join("','")}'])">
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
    document.getElementById('selectedUserInitials').textContent = userName.substring(0, 2).toUpperCase();

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
        message: message || `Ciao ${pendingInvitation.name}, sei invitato al nostro evento Poetry Slam come ${getRoleDisplayName(selectedRole)}!`
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
    e.preventDefault(); // Prevent default submission

    // Validate dates
    const startDateTime = document.getElementById('start_datetime').value;
    const endDateTime = document.getElementById('end_datetime').value;
    const registrationDeadline = document.getElementById('registration_deadline').value;

    const now = new Date();
    const startDate = startDateTime ? new Date(startDateTime.replace(' ', 'T')) : null;
    const endDate = endDateTime ? new Date(endDateTime.replace(' ', 'T')) : null;
    const regDeadline = registrationDeadline ? new Date(registrationDeadline.replace(' ', 'T')) : null;

    let hasErrors = false;

    // Clear previous errors and styling
    document.querySelectorAll('.error-feedback').forEach(el => el.textContent = '');
    document.querySelectorAll('.form-control.is-invalid').forEach(el => {
        el.classList.remove('is-invalid');
        el.classList.add('is-valid');
    });

        // Validate start datetime
    if (!startDateTime) {
        document.getElementById('start_datetime-error').textContent = '{{ __('events.start_datetime_required') }}';
        document.getElementById('start_datetime').classList.add('is-invalid');
        document.getElementById('start_datetime').classList.remove('is-valid');
        hasErrors = true;
    } else if (startDate && startDate <= now) {
        document.getElementById('start_datetime-error').textContent = '{{ __('events.start_datetime_future') }}';
        document.getElementById('start_datetime').classList.add('is-invalid');
        document.getElementById('start_datetime').classList.remove('is-valid');
        hasErrors = true;
    } else {
        document.getElementById('start_datetime').classList.remove('is-invalid');
        document.getElementById('start_datetime').classList.add('is-valid');
    }

    // Validate end datetime
    if (!endDateTime) {
        document.getElementById('end_datetime-error').textContent = '{{ __('events.end_datetime_required') }}';
        document.getElementById('end_datetime').classList.add('is-invalid');
        document.getElementById('end_datetime').classList.remove('is-valid');
        hasErrors = true;
    } else if (startDate && endDate && endDate <= startDate) {
        document.getElementById('end_datetime-error').textContent = '{{ __('events.end_datetime_after_start') }}';
        document.getElementById('end_datetime').classList.add('is-invalid');
        document.getElementById('end_datetime').classList.remove('is-valid');
        hasErrors = true;
    } else {
        document.getElementById('end_datetime').classList.remove('is-invalid');
        document.getElementById('end_datetime').classList.add('is-valid');
    }

    // Validate registration deadline
    if (regDeadline && startDate && regDeadline >= startDate) {
        document.getElementById('registration_deadline-error').textContent = '{{ __('events.registration_deadline_before_start') }}';
        document.getElementById('registration_deadline').classList.add('is-invalid');
        document.getElementById('registration_deadline').classList.remove('is-valid');
        hasErrors = true;
    } else if (regDeadline) {
        document.getElementById('registration_deadline').classList.remove('is-invalid');
        document.getElementById('registration_deadline').classList.add('is-valid');
    }

    if (hasErrors) {
        // Scroll to first error
        const firstError = document.querySelector('.form-control.is-invalid');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
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
    console.log('Submitting form - draft cleared from localStorage');

    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="ph ph-spinner-gap me-2"></i>{{ __('events.creating') }}';
    submitStatus.style.display = 'block';

    // Submit the form
    this.submit();
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
                    const radioButton = document.querySelector(`input[name="is_public"][value="${data[key]}"]`);
                    if (radioButton) {
                        radioButton.checked = true;
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
    @if(session('success'))
        localStorage.removeItem('eventDraft');
        console.log('Event created successfully - draft cleared from localStorage');
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

    // Registration deadline picker
    flatpickr("#registration_deadline", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        minDate: "today",
        minTime: "00:00",
        time_24hr: true,
        allowInput: true,
        placeholder: "Seleziona data e ora scadenza...",
        onChange: function(selectedDates, dateStr, instance) {
            // Clear error when valid date is selected
            document.getElementById('registration_deadline-error').textContent = '';
            document.getElementById('registration_deadline').classList.remove('is-invalid');
            document.getElementById('registration_deadline').classList.add('is-valid');
        },
        onClose: function(selectedDates, dateStr, instance) {
            // Ensure the format is correct for Laravel validation
            if (dateStr) {
                instance.input.value = dateStr.replace('T', ' ');
            }
        }
    });
});

// Funzioni per gestione inviti eventi privati
let invitedUsers = [];
let suggestedUsers = [];

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
        displaySuggestedUsers();
    })
    .catch(error => {
        console.error('{{ __('common.loading_error') }} utenti suggeriti:', error);
    });
}

// Mostra utenti suggeriti
function displaySuggestedUsers() {
    const container = document.getElementById('suggestedUsersList');
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
                        <a href="/user/${user.id}" target="_blank" class="h-40 w-40 d-flex-center b-r-50 overflow-hidden bg-dark flex-shrink-0 me-3 text-decoration-none">
                            <img src="${user.avatar_url || '/assets/images/avatar/default.png'}"
                                 alt="${user.name}" class="img-fluid">
                        </a>
                        <div class="flex-grow-1 ps-2">
                            <div class="fw-medium txt-ellipsis-1">${user.name}</div>
                            <div class="text-muted f-s-12 txt-ellipsis-1">${user.email}</div>
                        </div>
                        <button type="button" class="btn btn-light-primary icon-btn b-r-4"
                                onclick="inviteUser(${user.id}, '${user.name}', '${user.email}')"
                                title="{{ __('events.invite_user') }}">
                            <i class="ph ph-plus f-s-12"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

// Cerca utenti per invito
function searchUsersForInvite() {
    const searchTerm = document.getElementById('userSearchInput').value.trim();
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
        displaySearchResults(data.users || []);
    })
    .catch(error => {
        console.error('Errore nella ricerca utenti:', error);
    });
}

// Mostra risultati ricerca
function displaySearchResults(users) {
    const container = document.getElementById('searchResultsListInvite');
    const resultsDiv = document.getElementById('searchResultsInvite');

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
                        <img src="${user.avatar_url || '/assets/images/avatar/default.png'}"
                             alt="${user.name}" class="img-fluid">
                    </a>
                    <div class="flex-grow-1">
                        <h6 class="mb-1 f-s-14 f-w-600 text-dark">${user.name}</h6>
                        <small class="text-muted f-s-12">${user.email}</small>
                    </div>
                    <div class="flex-shrink-0">
                        <button type="button" class="btn btn-primary btn-sm hover-effect"
                                onclick="inviteUser(${user.id}, '${user.name}', '${user.email}')">
                            <i class="ph ph-plus f-s-12"></i> {{ __('events.invite_user') }}
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }

    resultsDiv.style.display = 'block';
}

// Invita utente
function inviteUser(userId, userName, userEmail) {
    console.log('Inviting user:', userId, userName, userEmail);

    // Controlla se l'utente è già stato invitato
    if (invitedUsers.some(user => user.id === userId)) {
        console.log('User already invited');
        return;
    }

    const user = {
        id: userId,
        name: userName,
        email: userEmail
    };

    invitedUsers.push(user);
    console.log('Current invited users:', invitedUsers);

    updateInvitedUsersDisplay();
    updateInvitedUsersData();

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

// Rimuovi invito
function removeInvite(userId) {
    console.log('Removing invite for user:', userId);

    invitedUsers = invitedUsers.filter(user => user.id !== userId);
    console.log('Remaining invited users:', invitedUsers);

    updateInvitedUsersDisplay();
    updateInvitedUsersData();
}

// Aggiorna visualizzazione utenti invitati
function updateInvitedUsersDisplay() {
    console.log('Updating invited users display, count:', invitedUsers.length);

    const container = document.getElementById('invitationsList');
    const countElement = document.getElementById('invitationCount');

    if (!container) {
        console.error('Container invitationsList not found');
        return;
    }

    if (!countElement) {
        console.error('Element invitationCount not found');
        return;
    }

    if (invitedUsers.length === 0) {
        container.innerHTML = `
            <div class="col-12 text-center text-muted py-4" id="noInvitations">
                <i class="ph ph-user-plus display-4 mb-2"></i>
                <p>Nessun artista selezionato ancora.<br>Cerca e aggiungi artisti da invitare.</p>
            </div>
        `;
    } else {
        container.innerHTML = invitedUsers.map(user => `
            <div class="col-md-6 col-lg-4 mb-2">
                <div class="card hover-effect">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <a href="/user/${user.id}" target="_blank" class="h-40 w-40 d-flex-center b-r-50 overflow-hidden bg-dark flex-shrink-0 me-3 text-decoration-none">
                                <img src="/assets/images/avatar/default.png"
                                     alt="${user.name}" class="img-fluid">
                            </a>
                            <div class="flex-grow-1 ps-2">
                                <div class="fw-medium txt-ellipsis-1">${user.name}</div>
                                <div class="text-muted f-s-12 txt-ellipsis-1">${user.email}</div>
                                <div class="text-muted f-s-10 txt-ellipsis-1">${user.role || 'performer'}</div>
                            </div>
                            <button type="button" class="btn btn-light-danger icon-btn b-r-4"
                                    onclick="removeInvite(${user.id})" title="Rimuovi invito">
                                <i class="ph ph-x f-s-12"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    countElement.textContent = invitedUsers.length;
    console.log('Display updated, count element shows:', countElement.textContent);
}

// Aggiorna dati nascosti
function updateInvitedUsersData() {
    const hiddenInput = document.getElementById('invitationsData');
    if (hiddenInput) {
        hiddenInput.value = JSON.stringify(invitedUsers);
    }
}

// Gestisce il tasto Invio nella ricerca utenti
function handleUserSearchKeydown(event) {
    if (event.key === 'Enter') {
        event.preventDefault(); // Previene il submit del form
        searchUsers(); // Esegue la ricerca invece
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

    console.log('Searching users for:', searchTerm);

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
        console.log('Search results:', data);

        if (data.users && data.users.length > 0) {
            resultsList.innerHTML = data.users.map(user => `
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold">${user.name}</div>
                        <small class="text-muted">${user.email}</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary"
                            onclick="selectUserForInvitation(${user.id}, '${user.name}', '${user.email}')">
                        <i class="ph ph-plus me-1"></i>Seleziona
                    </button>
                </div>
            `).join('');
            resultsDiv.style.display = 'block';
        } else {
            resultsList.innerHTML = '<div class="list-group-item text-center text-muted">Nessun utente trovato</div>';
            resultsDiv.style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Search error:', error);
        resultsList.innerHTML = '<div class="list-group-item text-center text-danger">Errore durante la ricerca</div>';
        resultsDiv.style.display = 'block';
    });
}

// Seleziona utente per invito
function selectUserForInvitation(userId, userName, userEmail) {
    console.log('Selecting user for invitation:', userId, userName, userEmail);

    // Controlla se l'utente è già stato invitato
    if (invitedUsers.some(user => user.id === userId)) {
        alert('Questo utente è già stato invitato');
        return;
    }

    // Mostra il modal per selezionare il ruolo
    document.getElementById('selectedUserInitials').textContent = userName.split(' ').map(n => n[0]).join('').toUpperCase();
    document.getElementById('selectedUserName').textContent = userName;
    document.getElementById('selectedUserEmail').textContent = userEmail;

    // Salva i dati dell'utente selezionato
    window.selectedUser = { id: userId, name: userName, email: userEmail };

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

    console.log('Confirming invitation:', selectedUser, role, message);

    // Aggiungi l'utente alla lista degli invitati
    const invitation = {
        id: selectedUser.id,
        name: selectedUser.name,
        email: selectedUser.email,
        role: role,
        message: message
    };

    invitedUsers.push(invitation);
    console.log('Current invited users:', invitedUsers);

    // Aggiorna la visualizzazione
    updateInvitedUsersDisplay();
    updateInvitedUsersData();

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
        previewDiv.innerHTML = '{{ __("events.recurrence_preview_placeholder") }}';
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

            for (let i = 0; i < Math.min(dailyCount, 10); i++) { // Mostra prime 10 occorrenze o il numero specificato
                const newDate = new Date(startDate);
                newDate.setDate(startDate.getDate() + (i * dailyInterval));
                dates.push(newDate);
            }
            break;
        case 'weekly':
            const weeklyInterval = parseInt(document.getElementById('recurrence_interval').value) || 1;
            const selectedWeekdays = Array.from(document.querySelectorAll('input[name="recurrence_weekdays[]"]:checked'))
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

// ===== GESTIONE POSIZIONI D'INGAGGIO =====

let gigPositionCounter = 0;

// Aggiunge una nuova posizione d'ingaggio
function addGigPosition() {
    gigPositionCounter++;
    const positionId = `gig-position-${gigPositionCounter}`;

    const positionHtml = `
        <div class="card mb-3" id="${positionId}">
            <div class="card-header bg-light-success">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="ph ph-briefcase me-2"></i>Posizione d'Ingaggio #${gigPositionCounter}
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
                        <label class="form-label">Tipologia *</label>
                        <select class="form-select" name="gig_positions[${gigPositionCounter}][type]" required>
                            <option value="">Seleziona tipologia</option>
                            <option value="fonica">Fonica/Fonico</option>
                            <option value="illustratrice">Illustratrice/Illustratore</option>
                            <option value="musicista">Musicista</option>
                            <option value="mc">MC - Presentatrice/Presentatore</option>
                            <option value="poeta">Poeta</option>
                            <option value="traduttrice">Traduttrice/Traduttore</option>
                            <option value="videomaker">Videomaker</option>
                            <option value="volontaria">Volontaria/Volontario</option>
                            <option value="relatrice">Relatrice/Relatore</option>
                        </select>
                    </div>

                    <!-- Quantità -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Quantità *</label>
                        <input type="number" class="form-control" name="gig_positions[${gigPositionCounter}][quantity]" min="1" value="1" required>
                    </div>

                    <!-- {{ __('common.language_selector') }} richiesta -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('common.language_selector') }} richiesta (opzionale)</label>
                        <select class="form-select" name="gig_positions[${gigPositionCounter}][language]">
                            <option value="">Nessuna preferenza</option>
                            <option value="italiano">Italiano</option>
                            <option value="inglese">Inglese</option>
                            <option value="francese">Francese</option>
                            <option value="tedesco">Tedesco</option>
                            <option value="spagnolo">Spagnolo</option>
                            <option value="portoghese">Portoghese</option>
                            <option value="altro">Altro</option>
                        </select>
                    </div>
                </div>

                <!-- Cachet -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="cachet-${gigPositionCounter}" onchange="toggleCachetFields(${gigPositionCounter})">
                            <label class="form-check-label" for="cachet-${gigPositionCounter}">
                                <strong>Cachet</strong>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6" id="cachet-amount-${gigPositionCounter}" style="display: none;">
                        <label class="form-label">Ammontare</label>
                        <input type="number" class="form-control" name="gig_positions[${gigPositionCounter}][cachet_amount]" min="0" step="0.01" placeholder="0.00">
                    </div>
                    <div class="col-md-6" id="cachet-currency-${gigPositionCounter}" style="display: none;">
                        <label class="form-label">Valuta</label>
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
                                <strong>Spese di viaggio</strong>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-6" id="travel-max-${gigPositionCounter}" style="display: none;">
                        <label class="form-label">Tetto massimo copertura biglietti</label>
                        <input type="number" class="form-control" name="gig_positions[${gigPositionCounter}][travel_max]" min="0" step="0.01" placeholder="0.00">
                    </div>
                </div>

                <!-- Vitto e alloggio -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="accommodation-${gigPositionCounter}" onchange="toggleAccommodationFields(${gigPositionCounter})">
                            <label class="form-check-label" for="accommodation-${gigPositionCounter}">
                                <strong>Vitto e alloggio</strong>
                            </label>
                        </div>
                    </div>
                    <div class="col-12" id="accommodation-details-${gigPositionCounter}" style="display: none;">
                        <label class="form-label">Dettagli vitto e alloggio</label>
                        <textarea class="form-control" name="gig_positions[${gigPositionCounter}][accommodation_details]" rows="3" placeholder="Descrivi le condizioni di vitto e alloggio offerte..."></textarea>
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
                    showNotification('{{ __("events.venue_loaded_success") }}', 'success');
                } else {
                    showNotification('Luogo non trovato', 'warning');
                }
            } else {
                showNotification(data.message || 'Errore nel caricamento del luogo', 'error');
            }
        })
        .catch(error => {
            console.error('Error loading recent venue:', error);
            
            if (error.message === 'Authentication required') {
                showNotification('Devi essere autenticato per caricare i luoghi recenti', 'warning');
            } else if (error.message === 'API endpoint not found') {
                showNotification('Errore del server: endpoint non trovato', 'error');
            } else if (error.message.includes('Expected JSON response')) {
                showNotification('Errore del server: risposta non valida', 'error');
            } else {
                showNotification('{{ __("events.venue_load_error") }}', 'error');
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
            showNotification('{{ __("events.venue_loaded_success") }}', 'success');
            
        } catch (error) {
            console.error('Error parsing venue data:', error);
            showNotification('Errore nel caricamento dei dati del luogo', 'error');
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
        statusEl.innerHTML = '<i class="ph ph-spinner-gap me-1"></i> {{ __("events.searching_address") }}';
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
                    statusEl.innerHTML = '<i class="ph ph-check me-1"></i> {{ __("events.address_found") }}';
                    statusEl.className = 'small text-success mt-1';
                    setTimeout(() => {
                        statusEl.style.display = 'none';
                    }, 3000);
                }
            } else {
                // Indirizzo non trovato
                if (statusEl) {
                    statusEl.innerHTML = '<i class="ph ph-warning me-1"></i> {{ __("events.address_not_found") }}';
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
                statusEl.innerHTML = '<i class="ph ph-warning me-1"></i> {{ __("events.reverse_geocoding_error") }}';
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
    notification.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show position-fixed`;
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
</script>

<!-- Flatpickr JS -->
<script src="{{asset('assets/vendor/datepikar/flatpickr.js')}}"></script>
@endpush
