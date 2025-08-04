@extends('layout.master')

@section('title', $gig->title)

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
                        <li class="breadcrumb-item active text-truncate" style="max-width: 200px;">
                            {{ $gig->title }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Contenuto principale -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <!-- Header con badge di stato -->
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div class="flex-grow-1">
                                <h2 class="card-title mb-2">{{ $gig->title }}</h2>
                                <p class="text-muted mb-0">
                                    <i class="ph ph-user me-1"></i>
                                    <a href="{{ route('user.show', $gig->user) }}" class="text-decoration-none hover-effect">
                                        {{ $gig->user->getDisplayName() }}
                                    </a>
                                    <span class="mx-2">•</span>
                                    <i class="ph ph-calendar me-1"></i>
                                    @if($gig->created_at)
                                        {{ $gig->created_at->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">Non disponibile</span>
                                    @endif
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

                        <!-- Categorie e tipo -->
                        <div class="mb-4">
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

                        <!-- Descrizione -->
                        <div class="mb-4">
                            <h5>{{ __('gigs.fields.description') }}</h5>
                            <p class="text-muted">{{ $gig->description }}</p>
                        </div>

                        <!-- Requisiti -->
                        @if($gig->requirements)
                            <div class="mb-4">
                                <h5>{{ __('gigs.fields.requirements') }}</h5>
                                <p class="text-muted">{{ $gig->requirements }}</p>
                            </div>
                        @endif

                        <!-- Informazioni aggiuntive -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="text-muted">{{ __('gigs.fields.compensation') }}</h6>
                                <p class="text-success">
                                    <i class="ph ph-currency-eur me-1"></i>
                                    {{ $gig->compensation ?: __('common.free') }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted">{{ __('gigs.fields.deadline') }}</h6>
                                <p class="text-muted">
                                    <i class="ph ph-calendar me-1"></i>
                                    @if($gig->deadline)
                                        {{ $gig->deadline->format('d/m/Y H:i') }}
                                        @if($gig->days_until_deadline !== null)
                                            @if($gig->days_until_deadline > 0)
                                                <span class="badge bg-info ms-2">{{ $gig->days_until_deadline }} giorni rimasti</span>
                                            @else
                                                <span class="badge bg-danger ms-2">Scaduto</span>
                                            @endif
                                        @endif
                                    @else
                                        <span class="text-muted">Non specificata</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if($gig->location)
                            <div class="mb-4">
                                <h6 class="text-muted">{{ __('gigs.fields.location') }}</h6>
                                <p class="text-muted">
                                    <i class="ph ph-map-pin me-1"></i>{{ $gig->location }}
                                </p>
                            </div>
                        @endif

                        <!-- Statistiche -->
                        <div class="row text-center mb-4">
                            <div class="col-6">
                                <div class="border-end">
                                    <h4 class="mb-1">{{ $gig->application_count }}</h4>
                                    <small class="text-muted">{{ __('gigs.stats.applications') }}</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h4 class="mb-1">{{ $gig->max_applications }}</h4>
                                <small class="text-muted">Max candidature</small>
                            </div>
                        </div>

                        <!-- Azioni -->
                        <div class="d-flex gap-2">
                            @auth
                                @unless(auth()->user()->hasRole('audience'))
                                    @if($gig->can_apply && !$userApplication)
                                        <button class="btn btn-success" onclick="applyToGig({{ $gig->id }})">
                                            <i class="ph ph-user-plus me-2"></i>{{ __('gigs.apply_gig') }}
                                        </button>
                                    @elseif($userApplication)
                                        <button class="btn btn-secondary" disabled>
                                            <i class="ph ph-check me-2"></i>Candidatura inviata
                                        </button>
                                    @else
                                        <button class="btn btn-secondary" disabled>
                                            <i class="ph ph-lock me-2"></i>{{ __('gigs.status.closed') }}
                                        </button>
                                    @endif

                                    @if($gig->canBeEditedBy(auth()->user()))
                                        <button class="btn btn-info" onclick="shareGig({{ $gig->id }})">
                                            <i class="ph ph-share me-2"></i>{{ __('gigs.actions.share') }}
                                        </button>
                                    @endif
                                @endunless
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-primary">
                                    <i class="ph ph-sign-in me-2"></i>{{ __('gigs.messages.login_to_interact') }}
                                </a>
                            @endauth

                            @auth
                                @if($gig->canBeEditedBy(auth()->user()))
                                    <a href="{{ route('gigs.edit', $gig) }}" class="btn btn-outline-primary">
                                        <i class="ph ph-pencil me-2"></i>{{ __('common.edit') }}
                                    </a>
                                    <a href="{{ route('gigs.manage-applications', $gig) }}" class="btn btn-outline-info">
                                        <i class="ph ph-users me-2"></i>{{ __('gigs.applications.manage_applications') }}
                                        @if($gig->application_count > 0)
                                            <span class="badge bg-primary ms-1">{{ $gig->application_count }}</span>
                                        @endif
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Informazioni evento/gruppo -->
                @if($gig->event)
                    <div class="card mb-4">
                        <div class="card-body">
                            <h6 class="card-title">{{ __('gigs.fields.event') }}</h6>
                            <p class="mb-2">
                                <a href="{{ route('events.show', $gig->event) }}" class="text-decoration-none hover-effect">
                                    {{ $gig->event->title }}
                                </a>
                            </p>
                            <small class="text-muted">
                                <i class="ph ph-calendar me-1"></i>
                                @if($gig->event && $gig->event->start_datetime)
                                    {{ $gig->event->start_datetime->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">Data non disponibile</span>
                                @endif
                            </small>
                        </div>
                    </div>
                @endif

                @if($gig->group)
                    <div class="card mb-4">
                        <div class="card-body">
                            <h6 class="card-title">{{ __('gigs.fields.group') }}</h6>
                            <p class="mb-2">
                                <a href="{{ route('groups.show', $gig->group) }}" class="text-decoration-none hover-effect">
                                    {{ $gig->group->name }}
                                </a>
                            </p>
                            <small class="text-muted">
                                <i class="ph ph-users me-1"></i>
                                {{ $gig->group->members()->count() }} membri
                            </small>
                        </div>
                    </div>
                @endif

                <!-- Informazioni autore -->
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">{{ __('gigs.about_author') }}</h6>
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($gig->user) }}"
                                 alt="{{ $gig->user->getDisplayName() }}"
                                 class="rounded-circle me-3"
                                 style="width: 50px; height: 50px; object-fit: cover;">
                            <div>
                                <h6 class="mb-1">
                                    <a href="{{ route('user.show', $gig->user) }}" class="text-decoration-none hover-effect">
                                        {{ $gig->user->getDisplayName() }}
                                    </a>
                                </h6>
                                <small class="text-muted">{{ $gig->user->getRoleDisplayNameAttribute() }}</small>
                            </div>
                        </div>
                        @if($gig->user->bio)
                            <p class="text-muted small">{{ Str::limit($gig->user->bio, 100) }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
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

let currentGigId = {{ $gig->id }};

function applyToGig(gigId) {
    currentGigId = gigId;
    $('#applyModal').modal('show');
}

function shareGig(gigId) {
    Swal.fire({
        title: 'Condividi Ingaggio',
        text: 'Sei sicuro di voler condividere questo ingaggio con tutti gli utenti non-audience?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sì, condividi!',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/gigs/${gigId}/share`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        'Condiviso!',
                        data.message,
                        'success'
                    );
                } else {
                    Swal.fire(
                        'Errore!',
                        data.error || 'Errore durante la condivisione',
                        'error'
                    );
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire(
                    'Errore!',
                    'Errore di connessione',
                    'error'
                );
            });
        }
    });
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
