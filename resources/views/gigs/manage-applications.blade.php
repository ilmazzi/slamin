@extends('layout.master')

@section('title', __('gigs.applications.manage_applications'))

@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="page-title-box">
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                <div class="page-title-right">
                    
                </div>
            </div>
        </div>

        <!-- Header del Gig -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-2">{{ $gig->title }}</h4>
                        <p class="text-muted mb-2">{{ Str::limit($gig->description, 150) }}</p>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-light-primary">{{ __('gigs.categories.' . $gig->category) }}</span>
                            <span class="badge bg-light-primary">{{ __('gigs.types.' . $gig->type) }}</span>
                            @if($gig->is_remote)
                                <span class="badge bg-light-success">
                                    <i class="ph ph-globe me-1"></i>{{ __('gigs.fields.is_remote') }}
                                </span>
                            @endif
                            @if($gig->is_urgent)
                                <span class="badge bg-warning">
                                    <i class="ph ph-warning me-1"></i>{{ __('gigs.status.urgent') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="d-flex flex-column gap-2">
                            <div class="text-center">
                                <h5 class="mb-0 text-primary">{{ $gig->application_count }}</h5>
                                <small class="text-muted">{{ __('gigs.applications.total_applications') }}</small>
                            </div>
                            <div class="text-center">
                                <h5 class="mb-0 text-success">{{ $gig->accepted_applications_count }}</h5>
                                <small class="text-muted">{{ __('gigs.applications.accepted_applications') }}</small>
                            </div>
                            @if($gig->max_applications)
                                <div class="text-center">
                                    <h5 class="mb-0 text-info">{{ $gig->max_applications }}</h5>
                                    <small class="text-muted">{{ __('gigs.applications.max_positions') }}</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiche Candidature -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card card-light-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-primary rounded">
                                        <i class="ph ph-clock text-white"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ __('gigs.applications.pending') }}</h6>
                                <h4 class="mb-0">{{ $applications->where('status', 'pending')->count() }}</h4>
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
                                <h6 class="mb-1">{{ __('gigs.applications.accepted') }}</h6>
                                <h4 class="mb-0">{{ $applications->where('status', 'accepted')->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-light-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-danger rounded">
                                        <i class="ph ph-x-circle text-white"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ __('gigs.applications.rejected') }}</h6>
                                <h4 class="mb-0">{{ $applications->where('status', 'rejected')->count() }}</h4>
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
                                        <i class="ph ph-arrow-return-left text-white"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ __('gigs.applications.withdrawn') }}</h6>
                                <h4 class="mb-0">{{ $applications->where('status', 'withdrawn')->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Azioni -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex flex-wrap gap-2">
                    @if(!$gig->is_closed)
                        <button class="btn btn-danger" onclick="closeGig({{ $gig->id }})">
                            <i class="ph ph-lock me-2"></i>{{ __('gigs.actions.close_gig') }}
                        </button>
                    @else
                        <button class="btn btn-success" onclick="reopenGig({{ $gig->id }})">
                            <i class="ph ph-unlock me-2"></i>{{ __('gigs.actions.reopen_gig') }}
                        </button>
                    @endif
                    <button class="btn btn-primary" onclick="sendGlobalMessage({{ $gig->id }})">
                        <i class="ph ph-megaphone me-2"></i>{{ __('gigs.actions.send_global_message') }}
                    </button>
                    <a href="{{ route('gigs.show', $gig) }}" class="btn btn-outline-primary">
                        <i class="ph ph-eye me-2"></i>{{ __('gigs.actions.view_gig') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Lista Candidature -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ph ph-users me-2"></i>{{ __('gigs.applications.applications_list') }}
                </h5>
            </div>
            <div class="card-body">
                @forelse($applications as $application)
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="avatar-sm me-3">
                                            <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($application->user) }}"
                                                 alt="{{ $application->user->getDisplayName() }}"
                                                 class="rounded-circle" width="40" height="40">
                                        </div>
                                        <div>
                                            <h6 class="mb-1">
                                                <a href="{{ route('user.show', $application->user) }}"
                                                   class="text-decoration-none hover-effect">
                                                    {{ $application->user->getDisplayName() }}
                                                </a>
                                            </h6>
                                            <small class="text-muted">
                                                <i class="ph ph-clock me-1"></i>
                                                {{ $application->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>

                                    @if($application->message)
                                        <p class="mb-2">{{ Str::limit($application->message, 200) }}</p>
                                    @endif

                                    <div class="row text-center">
                                        @if($application->experience)
                                            <div class="col-4">
                                                <small class="text-muted d-block">{{ __('gigs.applications.experience') }}</small>
                                                <strong>{{ Str::limit($application->experience, 30) }}</strong>
                                            </div>
                                        @endif
                                        @if($application->portfolio)
                                            <div class="col-4">
                                                <small class="text-muted d-block">{{ __('gigs.applications.portfolio') }}</small>
                                                <a href="{{ $application->portfolio }}" target="_blank" class="text-decoration-none">
                                                    <strong>{{ __('gigs.applications.view_portfolio') }}</strong>
                                                </a>
                                            </div>
                                        @endif
                                        @if($application->compensation_expectation)
                                            <div class="col-4">
                                                <small class="text-muted d-block">{{ __('gigs.applications.compensation_expectation') }}</small>
                                                <strong>{{ $application->compensation_expectation }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="text-end">
                                        <!-- Status Badge -->
                                        <div class="mb-2">
                                            @if($application->status === 'pending')
                                                <span class="badge bg-warning">
                                                    <i class="ph ph-clock me-1"></i>{{ __('gigs.applications.pending') }}
                                                </span>
                                            @elseif($application->status === 'accepted')
                                                <span class="badge bg-success">
                                                    <i class="ph ph-check-circle me-1"></i>{{ __('gigs.applications.accepted') }}
                                                </span>
                                            @elseif($application->status === 'rejected')
                                                <span class="badge bg-danger">
                                                    <i class="ph ph-x-circle me-1"></i>{{ __('gigs.applications.rejected') }}
                                                </span>
                                            @elseif($application->status === 'withdrawn')
                                                <span class="badge bg-secondary">
                                                    <i class="ph ph-arrow-return-left me-1"></i>{{ __('gigs.applications.withdrawn') }}
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Action Buttons -->
                                        @if($application->status === 'pending')
                                            <div class="d-flex gap-2 justify-content-end">
                                                @if($gig->gig_type === 'translation')
                                                    <a href="{{ route('translations.negotiation.show', $application) }}"
                                                       class="btn btn-info btn-sm">
                                                        <i class="ph ph-chat-circle me-1"></i>Negoziare
                                                    </a>
                                                @endif
                                                <button class="btn btn-success btn-sm"
                                                        onclick="acceptApplication({{ $application->id }})">
                                                    <i class="ph ph-check me-1"></i>{{ __('gigs.applications.accept') }}
                                                </button>
                                                <button class="btn btn-danger btn-sm"
                                                        onclick="rejectApplication({{ $application->id }})">
                                                    <i class="ph ph-x me-1"></i>{{ __('gigs.applications.reject') }}
                                                </button>
                                            </div>
                                        @elseif($application->status === 'accepted' && $gig->gig_type === 'translation')
                                            <div class="d-flex gap-2 justify-content-end">
                                                <a href="{{ route('translations.negotiation.show', $application) }}"
                                                   class="btn btn-info btn-sm">
                                                    <i class="ph ph-chat-circle me-1"></i>Chat
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="ph ph-users text-muted" style="font-size: 4rem;"></i>
                        <h5 class="text-muted mt-3">{{ __('gigs.applications.no_applications') }}</h5>
                        <p class="text-muted">{{ __('gigs.applications.no_applications_description') }}</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Paginazione -->
        @if($applications->hasPages())
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        {{ $applications->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal per messaggio globale -->
<div class="modal fade" id="globalMessageModal" tabindex="-1" aria-labelledby="globalMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="globalMessageModalLabel">{{ __('gigs.actions.send_global_message') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('common.close') }}"></button>
            </div>
            <form id="globalMessageForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="global_message" class="form-label">{{ __('gigs.actions.message') }} <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="global_message" name="message" rows="4"
                                  placeholder="{{ __('gigs.actions.message_placeholder') }}" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('gigs.actions.send_message') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentGigId = {{ $gig->id }};

function acceptApplication(applicationId) {
    Swal.fire({
        title: '{{ __("gigs.applications.confirm_accept") }}',
        text: '{{ __("gigs.applications.confirm_accept_text") }}',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '{{ __("gigs.applications.accept") }}',
        cancelButtonText: '{{ __("common.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/gigs/applications/${applicationId}/accept`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Successo!', data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Errore!', data.error, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Errore!', 'Errore di connessione', 'error');
            });
        }
    });
}

function rejectApplication(applicationId) {
    Swal.fire({
        title: '{{ __("gigs.applications.confirm_reject") }}',
        text: '{{ __("gigs.applications.confirm_reject_text") }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '{{ __("gigs.applications.reject") }}',
        cancelButtonText: '{{ __("common.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/gigs/applications/${applicationId}/reject`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Successo!', data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Errore!', data.error, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Errore!', 'Errore di connessione', 'error');
            });
        }
    });
}

function closeGig(gigId) {
    Swal.fire({
        title: '{{ __("gigs.actions.confirm_close") }}',
        text: '{{ __("gigs.actions.confirm_close_text") }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '{{ __("gigs.actions.close_gig") }}',
        cancelButtonText: '{{ __("common.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/gigs/${gigId}/close`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Successo!', data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Errore!', data.error, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Errore!', 'Errore di connessione', 'error');
            });
        }
    });
}

function reopenGig(gigId) {
    Swal.fire({
        title: '{{ __("gigs.actions.confirm_reopen") }}',
        text: '{{ __("gigs.actions.confirm_reopen_text") }}',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '{{ __("gigs.actions.reopen_gig") }}',
        cancelButtonText: '{{ __("common.cancel") }}'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/gigs/${gigId}/reopen`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Successo!', data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Errore!', data.error, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Errore!', 'Errore di connessione', 'error');
            });
        }
    });
}

function sendGlobalMessage(gigId) {
    $('#globalMessageModal').modal('show');
}

$('#globalMessageForm').on('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch(`/gigs/${currentGigId}/global-message`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Successo!', data.message, 'success').then(() => {
                $('#globalMessageModal').modal('hide');
                $('#globalMessageForm')[0].reset();
            });
        } else {
            Swal.fire('Errore!', data.error, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Errore!', 'Errore di connessione', 'error');
    });
});
</script>
@endpush
