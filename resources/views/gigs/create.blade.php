@extends('layout.master')

@section('title', __('gigs.create_gig'))

@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="page-title-box">
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0 small">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}" class="text-decoration-none">
                                <i class="ph ph-house me-1"></i>{{ __('common.home') }}
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('gigs.index') }}" class="text-decoration-none">
                                <i class="ph ph-briefcase me-1"></i>{{ __('gigs.title') }}
                            </a>
                        </li>
                        @if($selectedEvent)
                        <li class="breadcrumb-item">
                            <a href="{{ route('events.show', $selectedEvent) }}" class="text-decoration-none">
                                {{ $selectedEvent->title }}
                            </a>
                        </li>
                        @endif
                        <li class="breadcrumb-item active">
                            {{ __('gigs.create_gig') }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        @if($selectedEvent)
        <!-- Info Evento -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-light-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="ph ph-calendar fs-1 text-primary me-3"></i>
                            <div>
                                <h5 class="mb-1">{{ $selectedEvent->title }}</h5>
                                <p class="mb-0 text-muted">
                                    <i class="ph ph-calendar me-1"></i>
                                    {{ $selectedEvent->start_datetime->format('d/m/Y H:i') }}
                                    @if($selectedEvent->venue_name)
                                        <i class="ph ph-map-pin ms-3 me-1"></i>
                                        {{ $selectedEvent->venue_name }}, {{ $selectedEvent->city }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">
                            <i class="ph ph-briefcase me-2"></i>{{ __('gigs.create_gig') }}
                        </h4>

                        <form action="{{ route('gigs.store') }}" method="POST">
                            @csrf

                            <!-- Evento (hidden se pre-selezionato) -->
                            @if($selectedEvent)
                                <input type="hidden" name="event_id" value="{{ $selectedEvent->id }}">
                            @else
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label for="event_id" class="form-label">{{ __('gigs.fields.event') }} <span class="text-danger">*</span></label>
                                        <select class="form-select @error('event_id') is-invalid @enderror" id="event_id" name="event_id" required>
                                            <option value="">{{ __('gigs.filters.select_event') }}</option>
                                            @foreach($events as $event)
                                                <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                                    {{ $event->title }} ({{ $event->start_datetime->format('d/m/Y H:i') }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('event_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            <!-- Tipologia -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="type" class="form-label">{{ __('gigs.fields.type') }} <span class="text-danger">*</span></label>
                                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">{{ __('gigs.filters.select_type') }}</option>
                                        <option value="artist_poet" {{ old('type') == 'artist_poet' ? 'selected' : '' }}>Artista/Poeta</option>
                                        <option value="mc_guest" {{ old('type') == 'mc_guest' ? 'selected' : '' }}>MC/Ospite</option>
                                        <option value="technical_support" {{ old('type') == 'technical_support' ? 'selected' : '' }}>Supporto Tecnico</option>
                                        <option value="volunteer" {{ old('type') == 'volunteer' ? 'selected' : '' }}>Volontaria/Volontario</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Quantità -->
                                <div class="col-md-6">
                                    <label for="quantity" class="form-label">Quantità <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                           id="quantity" name="quantity" value="{{ old('quantity', 1) }}" min="1" required>
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Lingua richiesta -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="language" class="form-label">{{ __('gigs.fields.language') }} (opzionale)</label>
                                    <select class="form-select @error('language') is-invalid @enderror" id="language" name="language">
                                        <option value="">Nessuna preferenza</option>
                                        <option value="italian" {{ old('language') == 'italian' ? 'selected' : '' }}>Italiano</option>
                                        <option value="english" {{ old('language') == 'english' ? 'selected' : '' }}>Inglese</option>
                                        <option value="french" {{ old('language') == 'french' ? 'selected' : '' }}>Francese</option>
                                        <option value="german" {{ old('language') == 'german' ? 'selected' : '' }}>Tedesco</option>
                                        <option value="spanish" {{ old('language') == 'spanish' ? 'selected' : '' }}>Spagnolo</option>
                                        <option value="portuguese" {{ old('language') == 'portuguese' ? 'selected' : '' }}>Portoghese</option>
                                        <option value="other" {{ old('language') == 'other' ? 'selected' : '' }}>Altro</option>
                                    </select>
                                    @error('language')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Cachet -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="has_cachet" onchange="toggleCachetFields()">
                                        <label class="form-check-label" for="has_cachet">
                                            <strong>Cachet</strong>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6" id="cachet-amount" style="display: none;">
                                    <label class="form-label">Ammontare</label>
                                    <input type="number" class="form-control" name="cachet_amount" min="0" step="0.01" placeholder="0.00" value="{{ old('cachet_amount') }}">
                                </div>
                                <div class="col-md-6" id="cachet-currency" style="display: none;">
                                    <label class="form-label">Valuta</label>
                                    <select class="form-select" name="cachet_currency">
                                        <option value="EUR" {{ old('cachet_currency') == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                        <option value="USD" {{ old('cachet_currency') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                                        <option value="GBP" {{ old('cachet_currency') == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Spese di viaggio -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="has_travel" onchange="toggleTravelFields()">
                                        <label class="form-check-label" for="has_travel">
                                            <strong>Spese di viaggio</strong>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6" id="travel-max" style="display: none;">
                                    <label class="form-label">Tetto massimo copertura biglietti</label>
                                    <input type="number" class="form-control" name="travel_max" min="0" step="0.01" placeholder="0.00" value="{{ old('travel_max') }}">
                                </div>
                            </div>

                            <!-- Vitto e alloggio -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="has_accommodation" onchange="toggleAccommodationFields()">
                                        <label class="form-check-label" for="has_accommodation">
                                            <strong>Vitto e alloggio</strong>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12" id="accommodation-details" style="display: none;">
                                    <label class="form-label">Dettagli vitto e alloggio</label>
                                    <textarea class="form-control" name="accommodation_details" rows="3" placeholder="Descrivi le condizioni di vitto e alloggio offerte...">{{ old('accommodation_details') }}</textarea>
                                </div>
                            </div>

                            <!-- Azioni -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('gigs.index') }}" class="btn btn-secondary">
                                            <i class="ph ph-arrow-left me-2"></i>{{ __('common.cancel') }}
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ph ph-check me-2"></i>{{ __('gigs.create_gig') }}
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
</div>
@endsection

@push('scripts')
<script>
function toggleCachetFields() {
    const hasCachet = document.getElementById('has_cachet').checked;
    document.getElementById('cachet-amount').style.display = hasCachet ? 'block' : 'none';
    document.getElementById('cachet-currency').style.display = hasCachet ? 'block' : 'none';
}

function toggleTravelFields() {
    const hasTravel = document.getElementById('has_travel').checked;
    document.getElementById('travel-max').style.display = hasTravel ? 'block' : 'none';
}

function toggleAccommodationFields() {
    const hasAccommodation = document.getElementById('has_accommodation').checked;
    document.getElementById('accommodation-details').style.display = hasAccommodation ? 'block' : 'none';
}
</script>
@endpush
