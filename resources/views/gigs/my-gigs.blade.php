@extends('layout.master')

@section('title', __('gigs.my_gigs'))

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
                        <li class="breadcrumb-item active">
                            <i class="ph ph-user me-1"></i>{{ __('gigs.my_gigs') }}
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
                                <h6 class="mb-1">{{ __('gigs.stats.my_gigs_count') }}</h6>
                                <h4 class="mb-0">{{ number_format($gigs->total()) }}</h4>
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
                                <h4 class="mb-0">{{ number_format($gigs->where('is_closed', false)->where('deadline', '>', now())->count()) }}</h4>
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
            <div class="col-xl-3 col-md-6">
                <div class="card card-light-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <span class="avatar-title bg-info rounded">
                                        <i class="ph ph-user-check text-white"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ __('gigs.stats.accepted_applications_count') }}</h6>
                                <h4 class="mb-0">{{ number_format($gigs->sum('accepted_applications_count')) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Azioni principali -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('gigs.create') }}" class="btn btn-primary">
                        <i class="ph ph-plus me-2"></i>{{ __('gigs.create_gig') }}
                    </a>
                    <a href="{{ route('gigs.index') }}" class="btn btn-outline-primary">
                        <i class="ph ph-globe me-2"></i>{{ __('gigs.browse_all') }}
                    </a>
                    <a href="{{ route('gigs.my-applications') }}" class="btn btn-outline-info">
                        <i class="ph ph-user-plus me-2"></i>{{ __('gigs.applications.my_applications') }}
                    </a>
                </div>
            </div>
        </div>

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
                                        <i class="ph ph-calendar me-1"></i>
                                        @if($gig->created_at)
                                            {{ $gig->created_at->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">Data non disponibile</span>
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

                            <!-- Informazioni gig -->
                            <div class="mb-3">
                                <p class="text-muted small mb-2">
                                    {{ Str::limit($gig->description, 100) }}
                                </p>

                                <div class="row text-center mb-3">
                                    <div class="col-4">
                                        <div class="border-end">
                                            <h6 class="mb-1">{{ $gig->application_count }}</h6>
                                            <small class="text-muted">{{ __('gigs.stats.applications') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border-end">
                                            <h6 class="mb-1">{{ $gig->accepted_applications_count }}</h6>
                                            <small class="text-muted">{{ __('gigs.stats.accepted_applications_count') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <h6 class="mb-1">{{ $gig->max_applications }}</h6>
                                        <small class="text-muted">Max</small>
                                    </div>
                                </div>

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
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <small class="text-muted d-block">
                                            <i class="ph ph-currency-eur me-1"></i>
                                            {{ $gig->compensation ?: __('common.free') }}
                                        </small>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">
                                            <i class="ph ph-calendar me-1"></i>
                                            @if($gig->deadline)
                                                {{ $gig->deadline->format('d/m/Y') }}
                                                @if($gig->days_until_deadline !== null && $gig->days_until_deadline > 0)
                                                    <span class="badge bg-info ms-1">{{ $gig->days_until_deadline }}g</span>
                                                @endif
                                            @else
                                                <span class="text-muted">Non specificata</span>
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Azioni -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('gigs.show', $gig) }}" class="btn btn-primary btn-sm flex-fill">
                                    <i class="ph ph-eye me-1"></i>{{ __('common.view') }}
                                </a>
                                <a href="{{ route('gigs.edit', $gig) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="ph ph-pencil me-1"></i>{{ __('common.edit') }}
                                </a>
                                <button class="btn btn-outline-danger btn-sm" onclick="deleteGig({{ $gig->id }})">
                                    <i class="ph ph-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <div class="mb-3">
                                <i class="ph ph-briefcase text-muted" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="text-muted">{{ __('gigs.messages.no_my_gigs') }}</h5>
                            <p class="text-muted">{{ __('gigs.messages.no_my_gigs_description') }}</p>
                            <a href="{{ route('gigs.create') }}" class="btn btn-primary">
                                <i class="ph ph-plus me-2"></i>{{ __('gigs.create_gig') }}
                            </a>
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
                        {{ $gigs->links() }}
                    </div>
                </div>
            </div>
        @endif
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

function deleteGig(gigId) {
    Swal.fire({
        title: '{{ __("gigs.messages.confirm_delete") }}',
        text: 'Questa azione non può essere annullata!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sì, elimina!',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/gigs/${gigId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        'Eliminato!',
                        data.message,
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire(
                        'Errore!',
                        data.error || '{{ __("gigs.messages.delete_error") }}',
                        'error'
                    );
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire(
                    'Errore!',
                    '{{ __("gigs.messages.delete_error") }}',
                    'error'
                );
            });
        }
    });
}
</script>
@endpush
