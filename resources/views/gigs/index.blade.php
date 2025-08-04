@extends('layout.master')

@section('title', __('gigs.title'))

@section('styles')
<style>
.f-s-10 { font-size: 10px !important; }
.f-s-12 { font-size: 12px !important; }
.f-s-14 { font-size: 14px !important; }

@media (max-width: 768px) {
    .f-s-10 { font-size: 9px !important; }
    .f-s-12 { font-size: 11px !important; }
    .f-s-14 { font-size: 13px !important; }

    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
}
</style>
@endsection

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
                        <li class="breadcrumb-item active">
                            <i class="ph ph-briefcase me-1"></i>{{ __('gigs.title') }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Statistiche -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card card-light-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-primary rounded">
                                        <i class="ph ph-briefcase text-white"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ __('gigs.stats.total_gigs') }}</h6>
                                <h4 class="mb-0">{{ number_format($stats['total_gigs']) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-light-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-success rounded">
                                        <i class="ph ph-check-circle text-white"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ __('gigs.stats.open_gigs_count') }}</h6>
                                <h4 class="mb-0">{{ number_format($stats['open_gigs_count']) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-light-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-warning rounded">
                                        <i class="ph ph-warning text-white"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ __('gigs.stats.urgent_gigs_count') }}</h6>
                                <h4 class="mb-0">{{ number_format($stats['urgent_gigs_count']) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-light-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-info rounded">
                                        <i class="ph ph-users text-white"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ __('gigs.stats.total_applications') }}</h6>
                                <h4 class="mb-0">{{ number_format($gigs->sum('application_count')) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtri e Ricerca -->
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('gigs.index') }}" class="row g-3">
                    <div class="col-lg-4 col-md-6">
                        <label for="search" class="form-label">{{ __('gigs.filters.search') }}</label>
                        <input type="text" class="form-control" id="search" name="search"
                               value="{{ request('search') }}"
                               placeholder="{{ __('gigs.filters.search') }}">
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="category" class="form-label">{{ __('gigs.filters.filter_by_category') }}</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">{{ __('common.all') }}</option>
                            @foreach(__('gigs.categories') as $key => $category)
                                <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="type" class="form-label">{{ __('gigs.filters.filter_by_type') }}</label>
                        <select class="form-select" id="type" name="type">
                            <option value="">{{ __('common.all') }}</option>
                            @foreach(__('gigs.types') as $key => $type)
                                <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="sort" class="form-label">{{ __('gigs.filters.sort_by') }}</label>
                        <select class="form-select" id="sort" name="sort">
                            @foreach(__('gigs.filters.sort_options') as $key => $option)
                                <option value="{{ $key }}" {{ request('sort', 'recent') == $key ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ph ph-magnifying-glass me-1"></i>{{ __('common.search') }}
                            </button>
                            <a href="{{ route('gigs.index') }}" class="btn btn-light">
                                <i class="ph ph-arrows-clockwise me-1"></i>{{ __('common.reset') }}
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Filtri rapidi -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="d-flex flex-wrap gap-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remote" name="remote"
                                       value="1" {{ request('remote') ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="form-check-label" for="remote">
                                    {{ __('gigs.filters.show_remote') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="urgent" name="urgent"
                                       value="1" {{ request('urgent') ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="form-check-label" for="urgent">
                                    {{ __('gigs.filters.show_urgent') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="featured" name="featured"
                                       value="1" {{ request('featured') ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="form-check-label" for="featured">
                                    {{ __('gigs.filters.show_featured') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sezione Organizzatore -->
        @if($showOrganizerSection && $userEvents->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card ">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ph ph-calendar me-2"></i>{{ __('gigs.organizer_section.title') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($userEvents as $event)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100">
                                    <div class="card-body d-flex flex-column p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="flex-grow-1 me-2">
                                                <h6 class="card-title mb-2 fw-bold f-s-14">
                                                    <a href="{{ route('events.show', $event) }}" class="text-decoration-none text-dark">
                                                        {{ Str::limit($event->title, 50) }}
                                                    </a>
                                                </h6>
                                                @if($event->subtitle)
                                                    <h6 class="text-muted mb-2 f-s-12">{{ Str::limit($event->subtitle, 40) }}</h6>
                                                @endif
                                                <p class="text-muted mb-2 f-s-12">
                                                    {{ Str::limit($event->description, 60) }}
                                                </p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="bg-light-primary text-center d-flex flex-column align-items-center justify-content-center" style="min-width: 45px; min-height: 45px; font-size: 11px; border-radius: 6px;">
                                                    <div class="fw-bold f-s-12">{{ $event->start_datetime->format('d') }}</div>
                                                    <div class="f-s-10">{{ $event->start_datetime->format('M') }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="d-flex align-items-center text-muted mb-1">
                                                <i class="ph ph-clock me-1 f-s-12"></i>
                                                <span class="f-s-12">{{ $event->start_datetime->format('H:i') }} - {{ $event->end_datetime->format('H:i') }}</span>
                                            </div>
                                            <div class="d-flex align-items-center text-muted mb-1">
                                                <i class="ph ph-user me-1 f-s-12"></i>
                                                <span class="f-s-12">
                                                    <a href="{{ route('user.show', $event->organizer) }}" class="text-decoration-none hover-effect">
                                                        {{ $event->organizer->getDisplayName() }}
                                                    </a>
                                                </span>
                                            </div>
                                            @if($event->is_online)
                                                <div class="d-flex align-items-center text-muted mb-1">
                                                    <i class="ph ph-globe me-1 f-s-12"></i>
                                                    <span class="f-s-12">{{ $event->timezone }}</span>
                                                </div>
                                            @else
                                                <div class="d-flex align-items-center text-muted mb-1">
                                                    <i class="ph ph-map-pin me-1 f-s-12"></i>
                                                    <span class="f-s-12">{{ $event->city }}, {{ $event->country }}</span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="mt-auto">
                                            <!-- Event Info -->
                                            <div class="d-flex flex-wrap align-items-center gap-1 mb-2">
                                                @if($event->entry_fee > 0)
                                                    <span class="badge bg-warning f-s-10">{{ __('events.entry_fee') }}: €{{ $event->entry_fee }}</span>
                                                @else
                                                    <span class="badge bg-success f-s-10">{{ __('events.free') }}</span>
                                                @endif
                                                @if($event->max_participants)
                                                    <small class="text-muted f-s-10">{{ __('events.max_participants') }}: {{ $event->max_participants }}</small>
                                                @endif
                                                <span class="badge bg-primary f-s-10">
                                                    {{ $event->gigs()->count() }} {{ __('gigs.organizer_section.gigs') }}
                                                </span>
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="{{ route('events.show', $event) }}" class="btn btn-primary btn-sm">
                                                    <i class="ph ph-eye me-1"></i>{{ __('common.view') }}
                                                </a>
                                                <a href="{{ route('gigs.create') }}?event={{ $event->id }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="ph ph-plus me-1"></i>{{ __('gigs.organizer_section.add_gig') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Azioni principali -->
        @auth
            @unless(auth()->user()->hasRole('audience'))
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('gigs.create') }}" class="btn btn-primary">
                                <i class="ph ph-plus me-2"></i>{{ __('gigs.create_gig') }}
                            </a>
                            <a href="{{ route('gigs.my-gigs') }}" class="btn btn-outline-primary">
                                <i class="ph ph-briefcase me-2"></i>{{ __('gigs.my_gigs') }}
                            </a>
                            <a href="{{ route('gigs.my-applications') }}" class="btn btn-outline-info">
                                <i class="ph ph-user-plus me-2"></i>{{ __('gigs.applications.my_applications') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endunless
        @endauth

        <!-- Lista Gigs -->
        <div class="row">
            @forelse($gigs as $gig)
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="card equal-card">
                        <div class="card-body">
                            <!-- Header con badge di stato -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-1">
                                        <a href="{{ route('gigs.show', $gig) }}" class="text-decoration-none hover-effect">
                                            {{ $gig->title }}
                                        </a>
                                    </h5>
                                    <p class="text-muted small mb-0">
                                        <i class="ph ph-user me-1"></i>
                                        <a href="{{ route('user.show', $gig->user) }}" class="text-decoration-none hover-effect">
                                            {{ $gig->user->getDisplayName() }}
                                        </a>
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    @if($gig->is_urgent)
                                        <span class="badge bg-warning">
                                            <i class="ph ph-warning me-1"></i>{{ __('gigs.status.urgent') }}
                                        </span>
                                    @elseif($gig->is_featured)
                                        <span class="badge bg-info">
                                            <i class="ph ph-star me-1"></i>{{ __('gigs.status.featured') }}
                                        </span>
                                    @elseif($gig->is_closed)
                                        <span class="badge bg-secondary">
                                            <i class="ph ph-lock me-1"></i>{{ __('gigs.status.closed') }}
                                        </span>
                                    @elseif($gig->is_expired)
                                        <span class="badge bg-danger">
                                            <i class="ph ph-clock me-1"></i>{{ __('gigs.status.expired') }}
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="ph ph-check-circle me-1"></i>{{ __('gigs.status.open') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Descrizione -->
                            <p class="card-text text-muted small mb-3">
                                {{ Str::limit($gig->description, 120) }}
                            </p>

                            <!-- Categorie e tipo -->
                            <div class="mb-3">
                                <span class="badge bg-light-primary me-1">
                                    {{ __('gigs.categories.' . $gig->category) }}
                                </span>
                                <span class="badge bg-light-primary me-1">
                                    {{ __('gigs.types.' . $gig->type) }}
                                </span>
                                @if($gig->is_remote)
                                    <span class="badge bg-light-success">
                                        <i class="ph ph-globe me-1"></i>{{ __('gigs.fields.is_remote') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Informazioni aggiuntive -->
                            <div class="row text-center mb-3">
                                <div class="col-4">
                                    <small class="text-muted d-block">{{ __('gigs.stats.applications') }}</small>
                                    @if($gig->application_count > 0)
                                        <a href="{{ route('gigs.manage-applications', $gig) }}" class="text-decoration-none">
                                            <strong class="text-primary">{{ $gig->application_count }}</strong>
                                            <i class="ph ph-arrow-right ms-1"></i>
                                        </a>
                                    @else
                                        <strong>{{ $gig->application_count }}</strong>
                                    @endif
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">{{ __('gigs.stats.accepted_applications_count') }}</small>
                                    <strong class="text-success">{{ $gig->accepted_applications_count }}</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">{{ __('gigs.fields.deadline') }}</small>
                                    <strong>{{ $gig->deadline ? $gig->deadline->format('d/m/Y') : 'N/A' }}</strong>
                                </div>
                            </div>

                            <!-- Compenso e località -->
                            <div class="mb-3">
                                @if($gig->compensation)
                                    <div class="text-success small">
                                        <i class="ph ph-currency-eur me-1"></i>{{ $gig->compensation }}
                                    </div>
                                @endif
                                @if($gig->location)
                                    <div class="text-muted small">
                                        <i class="ph ph-map-pin me-1"></i>{{ $gig->location }}
                                    </div>
                                @endif
                            </div>

                            <!-- Azioni -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('gigs.show', $gig) }}" class="btn btn-primary btn-sm flex-fill">
                                    <i class="ph ph-eye me-1"></i>{{ __('gigs.actions.read') }}
                                </a>
                                @auth
                                    @unless(auth()->user()->hasRole('audience'))
                                        @if($gig->can_apply)
                                            <button class="btn btn-success btn-sm" onclick="applyToGig({{ $gig->id }})">
                                                <i class="ph ph-user-plus me-1"></i>{{ __('gigs.apply_gig') }}
                                            </button>
                                        @else
                                            <button class="btn btn-secondary btn-sm" disabled>
                                                <i class="ph ph-lock me-1"></i>{{ __('gigs.status.closed') }}
                                            </button>
                                        @endif
                                    @endunless
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="ph ph-sign-in me-1"></i>{{ __('gigs.messages.login_to_interact') }}
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="ph ph-briefcase text-muted" style="font-size: 4rem;"></i>
                            <h5 class="text-muted mt-3">{{ __('gigs.messages.no_gigs_found') }}</h5>
                            <p class="text-muted">{{ __('gigs.messages.no_gigs_description') }}</p>
                            @auth
                                @unless(auth()->user()->hasRole('audience'))
                                    <a href="{{ route('gigs.create') }}" class="btn btn-primary">
                                        <i class="ph ph-plus me-2"></i>{{ __('gigs.create_gig') }}
                                    </a>
                                @endunless
                            @endauth
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Paginazione -->
        @if($gigs->hasPages())
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
                <h5 class="modal-title" id="applyModalLabel">{{ __('gigs.applications.apply') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="applyForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="message" class="form-label">{{ __('gigs.applications.message') }} <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="message" name="message" rows="4"
                                  placeholder="{{ __('gigs.applications.message_placeholder') }}" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="experience" class="form-label">{{ __('gigs.applications.experience') }}</label>
                        <textarea class="form-control" id="experience" name="experience" rows="3"
                                  placeholder="{{ __('gigs.applications.experience_placeholder') }}"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="portfolio" class="form-label">{{ __('gigs.applications.portfolio') }}</label>
                        <input type="text" class="form-control" id="portfolio" name="portfolio"
                               placeholder="{{ __('gigs.applications.portfolio_placeholder') }}">
                    </div>
                    <div class="mb-3">
                        <label for="availability" class="form-label">{{ __('gigs.applications.availability') }}</label>
                        <textarea class="form-control" id="availability" name="availability" rows="2"
                                  placeholder="{{ __('gigs.applications.availability_placeholder') }}"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="compensation_expectation" class="form-label">{{ __('gigs.applications.compensation_expectation') }}</label>
                        <input type="text" class="form-control" id="compensation_expectation" name="compensation_expectation"
                               placeholder="{{ __('gigs.applications.compensation_expectation_placeholder') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('gigs.applications.submit_application') }}</button>
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

function applyToGig(gigId) {
    currentGigId = gigId;
    $('#applyModal').modal('show');
}

$('#applyForm').on('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch(`/gigs/${currentGigId}/apply`, {
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
