@extends('layout.master')

@section('title', 'Ingaggi')

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
                                <i class="ph ph-house me-1"></i>Home
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <i class="ph ph-briefcase me-1"></i>Ingaggi
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Statistiche -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card card-light-primary hover-effect">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-primary rounded">
                                        <i class="ph ph-briefcase text-white f-s-14"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <h6 class="mb-1 f-s-12 f-w-500">Ingaggi Totali</h6>
                                <h4 class="mb-0 f-s-18 f-w-600">{{ number_format($stats['total_gigs'] ?? 0) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-light-success hover-effect">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-success rounded">
                                        <i class="ph ph-check-circle text-white f-s-14"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <h6 class="mb-1 f-s-12 f-w-500">Ingaggi Aperti</h6>
                                <h4 class="mb-0 f-s-18 f-w-600">{{ number_format($stats['open_gigs_count'] ?? 0) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-light-warning hover-effect">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-warning rounded">
                                        <i class="ph ph-warning text-white f-s-14"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <h6 class="mb-1 f-s-12 f-w-500">Ingaggi Urgenti</h6>
                                <h4 class="mb-0 f-s-18 f-w-600">{{ number_format($stats['urgent_gigs_count'] ?? 0) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-light-info hover-effect">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-info rounded">
                                        <i class="ph ph-users text-white f-s-14"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <h6 class="mb-1 f-s-12 f-w-500">Candidature Totali</h6>
                                <h4 class="mb-0 f-s-18 f-w-600">{{ number_format(isset($gigs) ? $gigs->sum('application_count') : 0) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtri -->
        <div class="card hover-effect">
            <div class="card-header">
                <h5 class="card-title mb-0 f-s-16 f-w-600">
                    <i class="ph ph-funnel me-2"></i>Filtri e Ricerca
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('gigs.index') }}" class="row g-3">
                    <div class="col-12 col-md-6 col-lg-4">
                        <label for="search" class="form-label f-s-14 f-w-500">Cerca ingaggi...</label>
                        <input type="text" class="form-control form-control-sm" id="search" name="search"
                               value="{{ request('search') }}"
                               placeholder="Cerca ingaggi...">
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                        <label for="category" class="form-label f-s-14 f-w-500">Filtra per categoria</label>
                        <select class="form-select form-select-sm" id="category" name="category">
                            <option value="">Tutti</option>
                            @if(isset($categories) && is_array($categories))
                                @foreach($categories as $key => $category)
                                <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                        <label for="type" class="form-label f-s-14 f-w-500">Filtra per tipo</label>
                        <select class="form-select form-select-sm" id="type" name="type">
                            <option value="">Tutti</option>
                            @if(isset($types) && is_array($types))
                                @foreach($types as $key => $type)
                                <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                        <label for="sort" class="form-label f-s-14 f-w-500">Ordina per</label>
                        <select class="form-select form-select-sm" id="sort" name="sort">
                            @if(isset($sortOptions) && is_array($sortOptions))
                                @foreach($sortOptions as $key => $option)
                                <option value="{{ $key }}" {{ request('sort', 'recent') == $key ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                        <label class="form-label f-s-14 f-w-500">&nbsp;</label>
                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="ph ph-magnifying-glass me-1"></i>Cerca
                            </button>
                            <a href="{{ route('gigs.index') }}" class="btn btn-light btn-sm">
                                <i class="ph ph-arrows-clockwise me-1"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Filtri rapidi -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="d-flex flex-wrap gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remote" name="remote"
                                       value="1" {{ request('remote') ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="form-check-label f-s-14" for="remote">
                                    Solo remoto
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="urgent" name="urgent"
                                       value="1" {{ request('urgent') ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="form-check-label f-s-14" for="urgent">
                                    Solo urgenti
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="featured" name="featured"
                                       value="1" {{ request('featured') ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="form-check-label f-s-14" for="featured">
                                    Solo in evidenza
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Azioni principali -->
        @auth
            @unless(auth()->user()->hasRole('audience'))
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <a href="{{ route('gigs.create') }}" class="btn btn-primary btn-sm">
                                <i class="ph ph-plus me-2"></i>Crea Ingaggio
                            </a>
                            <a href="{{ route('gigs.my-gigs') }}" class="btn btn-light btn-sm">
                                <i class="ph ph-briefcase me-2"></i>I Miei Ingaggi
                            </a>
                                            <a href="{{ route('gigs.my-applications') }}" class="btn btn-light btn-sm">
                                <i class="ph ph-user-plus me-2"></i>Le Mie Candidature
                </a>
                        </div>
                    </div>
                </div>
            @endunless
        @endauth

        <!-- Lista Gigs -->
        <div class="row mt-4">
            @forelse($gigs as $gig)
                <div class="col-12">
                    <div class="card hover-effect mb-3">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="flex-grow-1 me-3">
                                    <h6 class="card-title mb-1 f-s-16 f-w-600">
                                        <a href="{{ route('gigs.show', $gig) }}" class="text-decoration-none hover-effect">
                                            @php
                                                $gigTitle = is_array($gig->title) ? implode(', ', $gig->title) : ($gig->title ?? 'N/A');
                                            @endphp
                                            {{ $gigTitle }}
                                        </a>
                                    </h6>
                                    <p class="text-muted f-s-12 mb-0">
                                        <i class="ph ph-user me-1"></i>
                                        @if($gig->user)
                                            <a href="{{ route('user.show', $gig->user) }}" class="text-decoration-none hover-effect">
                                                {{ $gig->user->getDisplayName() }}
                                            </a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    @if($gig->gig_type === 'translation')
                                        <span class="badge bg-light-primary f-s-10">
                                            <i class="ph ph-translate me-1"></i>Traduzione
                                        </span>
                                    @elseif($gig->is_urgent)
                                        <span class="badge bg-warning f-s-10">
                                            <i class="ph ph-warning me-1"></i>Urgente
                                        </span>
                                    @elseif($gig->is_featured)
                                        <span class="badge bg-info f-s-10">
                                            <i class="ph ph-star me-1"></i>In Evidenza
                                        </span>
                                    @elseif($gig->is_closed)
                                        <span class="badge bg-secondary f-s-10">
                                            <i class="ph ph-lock me-1"></i>Chiuso
                                        </span>
                                    @elseif($gig->is_expired)
                                        <span class="badge bg-danger f-s-10">
                                            <i class="ph ph-clock me-1"></i>Scaduto
                                        </span>
                                    @else
                                        <span class="badge bg-success f-s-10">
                                            <i class="ph ph-check-circle me-1"></i>Aperto
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <p class="card-text text-muted f-s-12 mb-3">
                                @php
                                    $gigDescription = is_array($gig->description) ? implode(', ', $gig->description) : ($gig->description ?? '');
                                @endphp
                                {{ Str::limit($gigDescription, 100) }}
                            </p>

                            <!-- Categorie e tipo -->
                            <div class="mb-3">
                                <span class="badge bg-light-primary me-1 f-s-10">
                                    @php
                                        $categoryKey = is_array($gig->category) ? implode(', ', $gig->category) : ($gig->category ?? 'N/A');
                                        $categoryTranslation = isset($categories[$categoryKey]) ? $categories[$categoryKey] : $categoryKey;
                                    @endphp
                                    {{ $categoryTranslation }}
                                </span>
                                <span class="badge bg-light-primary me-1 f-s-10">
                                    @php
                                        $typeKey = is_array($gig->type) ? implode(', ', $gig->type) : ($gig->type ?? 'N/A');
                                        $typeTranslation = isset($types[$typeKey]) ? $types[$typeKey] : $typeKey;
                                    @endphp
                                    {{ $typeTranslation }}
                                </span>
                                @if($gig->is_remote)
                                    <span class="badge bg-light-success f-s-10">
                                        <i class="ph ph-globe me-1"></i>Remoto
                                    </span>
                                @endif
                            </div>

                            <!-- Informazioni aggiuntive -->
                            <div class="row text-center mb-3 g-2">
                                <div class="col-4">
                                    <small class="text-muted d-block f-s-10">Candidature</small>
                                    @if((int)($gig->application_count ?? 0) > 0)
                                        <a href="{{ route('gigs.manage-applications', $gig) }}" class="text-decoration-none">
                                            <strong class="text-primary f-s-12">{{ (int)($gig->application_count ?? 0) }}</strong>
                                            <i class="ph ph-arrow-right ms-1 f-s-10"></i>
                                        </a>
                                    @else
                                        <strong class="f-s-12">{{ (int)($gig->application_count ?? 0) }}</strong>
                                    @endif
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block f-s-10">Accettate</small>
                                    <strong class="text-success f-s-12">{{ (int)($gig->accepted_applications_count ?? 0) }}</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block f-s-10">Scadenza</small>
                                    <strong class="f-s-12">{{ $gig->deadline ? $gig->deadline->format('d/m/Y') : 'N/A' }}</strong>
                                </div>
                            </div>

                            <!-- Compenso e località -->
                            <div class="mb-3">
                                @if($gig->compensation)
                                    <div class="text-success f-s-12">
                                        <i class="ph ph-currency-eur me-1"></i>
                                        @php
                                            $gigCompensation = is_array($gig->compensation) ? implode(', ', $gig->compensation) : ($gig->compensation ?? 'N/A');
                                        @endphp
                                        {{ $gigCompensation }}
                                    </div>
                                @endif
                                @if($gig->location)
                                    <div class="text-muted f-s-12">
                                        <i class="ph ph-map-pin me-1"></i>
                                        @php
                                            $gigLocation = is_array($gig->location) ? implode(', ', $gig->location) : ($gig->location ?? 'N/A');
                                        @endphp
                                        {{ $gigLocation }}
                                    </div>
                                @endif
                            </div>

                            <!-- Azioni -->
                            <div class="d-flex flex-column flex-sm-row gap-2">
                                @if(isset($gig->poem) && $gig->poem)
                                    <a href="{{ route('poems.show', $gig->poem->slug) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="ph ph-eye me-1"></i>Visualizza
                                    </a>

                                    @php
                                        $isOwner = $gig->user && $gig->user->id === auth()->id();
                                        $isPoemAuthor = $gig->poem && $gig->poem->user_id === auth()->id();
                                        $userApplication = $gig->applications->where('user_id', auth()->id())->first();
                                        $hasAcceptedApplication = $gig->applications->where('status', 'accepted')->first();
                                    @endphp

                                    @if($isOwner)
                                        {{-- PROPRIETARIO DEL GIG --}}
                                        @if($hasAcceptedApplication)
                                            <a href="{{ route('translations.payment.show', $hasAcceptedApplication) }}" class="btn btn-success btn-sm">
                                                <i class="fas fa-credit-card me-1"></i>Pagamento
                                            </a>
                                        @elseif($gig->applications_count > 0)
                                            <a href="{{ route('gigs.manage-applications', $gig) }}" class="btn btn-info btn-sm">
                                                <i class="ph ph-chat-circle me-1"></i>Gestisci Candidature
                                            </a>
                                        @else
                                            <button type="button" class="btn btn-outline-info btn-sm disabled">
                                                <i class="ph ph-user me-1"></i>Il Tuo Ingaggio
                                            </button>
                                        @endif
                                    @elseif($isPoemAuthor)
                                        {{-- AUTORE DELLA POESIA --}}
                                        <button type="button" class="btn btn-outline-warning btn-sm disabled">
                                            <i class="ph ph-book me-1"></i>La Tua Poesia
                                        </button>
                                    @elseif($userApplication)
                                        {{-- UTENTE CON CANDIDATURA --}}
                                        @if($userApplication->status === 'accepted')
                                            <a href="{{ route('translations.payment.show', $userApplication) }}" class="btn btn-success btn-sm">
                                                <i class="fas fa-credit-card me-1"></i>Pagamento
                                            </a>
                                        @elseif($userApplication->status === 'pending')
                                            <a href="{{ route('translations.negotiation.show', $userApplication) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-comments me-1"></i>Negozia
                                            </a>
                                        @else
                                            <button type="button" class="btn btn-danger btn-sm disabled">
                                                <i class="ph ph-x me-1"></i>Rifiutato
                                            </button>
                                        @endif
                                    @elseif($gig->canUserApply(auth()->user()))
                                        {{-- UTENTE CHE PUÒ CANDIDARSI --}}
                                        <button type="button" class="btn btn-primary btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#applyModal"
                                                data-gig-id="{{ $gig->id }}"
                                                data-gig-type="gig"
                                                data-gig-title="{{ $gigTitle }}"
                                                data-gig-compensation="{{ $gig->compensation }}">
                                            <i class="ph ph-translate me-1"></i>Candidati
                                        </button>
                                    @else
                                        {{-- UTENTE CHE NON PUÒ CANDIDARSI --}}
                                        <button type="button" class="btn btn-light btn-sm disabled">
                                            <i class="ph ph-lock me-1"></i>Non Disponibile
                                        </button>
                                    @endif
                                @else
                                    {{-- GIG NORMALE (NON TRADUZIONE) --}}
                                    <a href="{{ route('gigs.show', $gig) }}" class="btn btn-primary btn-sm">
                                        <i class="ph ph-eye me-1"></i>Leggi
                                    </a>
                                    @auth
                                        @unless(auth()->user()->hasRole('audience'))
                                            @if($gig->can_apply)
                                                <button class="btn btn-success btn-sm" onclick="applyToGig({{ $gig->id }})">
                                                    <i class="ph ph-user-plus me-1"></i>Candidati
                                                </button>
                                            @else
                                                <button class="btn btn-light btn-sm" disabled>
                                                    <i class="ph ph-lock me-1"></i>Chiuso
                                                </button>
                                            @endif
                                        @endunless
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-light btn-sm">
                                            <i class="ph ph-sign-in me-1"></i>Accedi per candidarti
                                        </a>
                                    @endauth
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Nessun gig normale trovato -->
            @endforelse


            <!-- Messaggio se non ci sono gig -->
            @if(!isset($gigs) || $gigs->count() == 0)
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="ph ph-briefcase text-muted" style="font-size: 4rem;"></i>
                            <h5 class="text-muted mt-3">Nessun ingaggio trovato</h5>
                            <p class="text-muted">Non ci sono ingaggi che corrispondono ai tuoi criteri di ricerca.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Paginazione -->
        @if(isset($gigs) && $gigs->hasPages())
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        {{ $gigs->appends(request()->query())->links() }}
                    </div>
                </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal per candidatura -->
<div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="applyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="applyModalLabel">Candidati all'Ingaggio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('common.close') }}"></button>
            </div>
            <form id="applyForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="message" class="form-label">Messaggio <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="message" name="message" rows="4"
                                  placeholder="Scrivi un messaggio per presentare la tua candidatura..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="experience" class="form-label">Esperienza</label>
                        <textarea class="form-control" id="experience" name="experience" rows="3"
                                  placeholder="Descrivi la tua esperienza rilevante..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="portfolio" class="form-label">Portfolio</label>
                        <input type="text" class="form-control" id="portfolio" name="portfolio"
                               placeholder="Link al tuo portfolio o lavori precedenti...">
                    </div>
                    <div class="mb-3">
                        <label for="availability" class="form-label">Disponibilità</label>
                        <textarea class="form-control" id="availability" name="availability" rows="2"
                                  placeholder="Descrivi la tua disponibilità..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="compensation_expectation" class="form-label">Aspettative di Compenso</label>
                        <input type="text" class="form-control" id="compensation_expectation" name="compensation_expectation"
                               placeholder="Indica le tue aspettative di compenso...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary">Invia Candidatura</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Fallback per toastr se viene caricato da qualche parte
if (typeof toastr === 'undefined') {
    window.toastr = {
        success: function(message) {
            Swal.fire('Successo!', message, 'success');
        },
        error: function(message) {
            Swal.fire('Errore!', message, 'error');
        },
        warning: function(message) {
            Swal.fire('Attenzione!', message, 'warning');
        },
        info: function(message) {
            Swal.fire('Info', message, 'info');
        }
    };
}

let currentGigId = null;
let currentGigType = null;

function applyToGig(gigId, gigType = 'gig') {
    currentGigId = gigId;
    currentGigType = gigType;
    $('#applyModal').modal('show');
}

// Gestione click sui pulsanti di candidatura
$(document).on('click', '[data-bs-target="#applyModal"]', function() {
    const gigId = $(this).data('gig-id');
    const gigType = $(this).data('gig-type') || 'gig';
    const gigTitle = $(this).data('gig-title');
    const gigCompensation = $(this).data('gig-compensation');

    currentGigId = gigId;
    currentGigType = gigType;

    // Aggiorna il titolo del modal
    $('#applyModalLabel').text('Candidati all\'Ingaggio');

    // Aggiorna i placeholder
    $('#message').attr('placeholder', 'Scrivi un messaggio per presentare la tua candidatura...');
    $('#experience').attr('placeholder', 'Descrivi la tua esperienza rilevante...');
    $('#portfolio').attr('placeholder', 'Link al tuo portfolio o lavori precedenti...');
    $('#availability').attr('placeholder', 'Descrivi la tua disponibilità...');
    $('#compensation_expectation').attr('placeholder', 'Indica le tue aspettative di compenso...');
});

// Gestione loader per tutti i pulsanti
$(document).on('click', 'a.btn', function(e) {
    const $btn = $(this);
    const href = $btn.attr('href');

    // Se è un link interno e non è disabilitato
    if (href && href !== '#' && !$btn.hasClass('disabled') && !$btn.hasClass('btn-outline-info') && !$btn.hasClass('btn-outline-warning')) {
        // Mostra loader
        const originalText = $btn.html();
        $btn.html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Caricamento...');
        $btn.addClass('disabled');

        // Se è un link normale, il browser gestirà il redirect
        // Se è un link AJAX, gestisci qui
        if (href.includes('negotiation') || href.includes('payment')) {
            e.preventDefault();

            // Simula un piccolo delay per mostrare il loader
            setTimeout(() => {
                window.location.href = href;
            }, 500);
        }
    }
});

// Gestione loader per pulsanti con modal
$(document).on('click', 'button[data-bs-toggle="modal"]', function() {
    const $btn = $(this);

    if (!$btn.hasClass('disabled')) {
        // Mostra loader
        const originalText = $btn.html();
        $btn.html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Apertura...');
        $btn.addClass('disabled');

        // Rimuovi loader dopo un breve delay
        setTimeout(() => {
            $btn.html(originalText);
            $btn.removeClass('disabled');
        }, 1000);
    }
});

$('#applyForm').on('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    // Determina l'URL in base al tipo di gig
    let applyUrl;
    applyUrl = `/gigs/${currentGigId}/apply`;

    fetch(applyUrl, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire(
                'Candidatura Inviata!',
                data.message,
                'success'
            ).then(() => {
                $('#applyModal').modal('hide');
                $('#applyForm')[0].reset();
                location.reload();
            });
        } else {
            Swal.fire(
                'Errore!',
                data.error || 'Errore durante l\'invio della candidatura',
                'error'
            );
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire(
            'Errore!',
            'Errore di connessione o server non disponibile',
            'error'
        );
    });
});
</script>
@endpush
