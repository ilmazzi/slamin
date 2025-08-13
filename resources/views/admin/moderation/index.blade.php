@extends('layout.master')

@section('title', __('dashboard.dashboard') . ' Moderazione')

@section('main-content')
<div class="container-fluid">
    <!-- Mobile-First Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                <div>
                    <h4 class="mb-0 f-w-600 f-s-18">
                        <i class="ph-duotone ph-shield-check me-2"></i>
                        {{ __('dashboard.dashboard') }} Moderazione
                    </h4>
                    <p class="text-muted mb-0 f-s-14">Gestisci la moderazione di tutti i contenuti</p>
                </div>
                <div class="d-flex flex-column flex-sm-row gap-2">
                    <a href="{{ route('admin.moderation.settings') }}" class="btn btn-outline-primary btn-sm">
                        <i class="ph-duotone ph-gear me-2"></i>
                        Impostazioni
                    </a>
                    <a href="{{ route('admin.moderation.pending') }}" class="btn btn-primary btn-sm">
                        <i class="ph-duotone ph-list-checks me-2"></i>
                        Contenuti in Attesa
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile-First Statistics -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card hover-effect equal-card">
                <div class="card-body text-center py-3">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-light-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="ph ph-video-camera f-s-20"></i>
                        </div>
                    </div>
                    <h6 class="mb-2 f-s-16 f-w-600">Video</h6>
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <span class="badge bg-warning f-s-12">{{ $stats['videos']['pending'] }} in attesa</span>
                        <span class="badge bg-success f-s-12">{{ $stats['videos']['approved'] }} approvati</span>
                        <span class="badge bg-danger f-s-12">{{ $stats['videos']['rejected'] }} rifiutati</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card hover-effect equal-card">
                <div class="card-body text-center py-3">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-light-success d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="ph ph-book-open f-s-20"></i>
                        </div>
                    </div>
                    <h6 class="mb-2 f-s-16 f-w-600">Poesie</h6>
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <span class="badge bg-warning f-s-12">{{ $stats['poems']['pending'] }} in attesa</span>
                        <span class="badge bg-success f-s-12">{{ $stats['poems']['approved'] }} approvate</span>
                        <span class="badge bg-danger f-s-12">{{ $stats['poems']['rejected'] }} rifiutate</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card hover-effect equal-card">
                <div class="card-body text-center py-3">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-light-info d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="ph ph-calendar f-s-20"></i>
                        </div>
                    </div>
                    <h6 class="mb-2 f-s-16 f-w-600">Eventi</h6>
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <span class="badge bg-warning f-s-12">{{ $stats['events']['pending'] }} in attesa</span>
                        <span class="badge bg-success f-s-12">{{ $stats['events']['approved'] }} approvati</span>
                        <span class="badge bg-danger f-s-12">{{ $stats['events']['rejected'] }} rifiutati</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card hover-effect equal-card">
                <div class="card-body text-center py-3">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-light-warning d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="ph ph-image f-s-20"></i>
                        </div>
                    </div>
                    <h6 class="mb-2 f-s-16 f-w-600">Foto</h6>
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <span class="badge bg-warning f-s-12">{{ $stats['photos']['pending'] }} in attesa</span>
                        <span class="badge bg-success f-s-12">{{ $stats['photos']['approved'] }} approvate</span>
                        <span class="badge bg-danger f-s-12">{{ $stats['photos']['rejected'] }} rifiutate</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card hover-effect equal-card">
                <div class="card-body text-center py-3">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-light-secondary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="ph ph-newspaper f-s-20"></i>
                        </div>
                    </div>
                    <h6 class="mb-2 f-s-16 f-w-600">Articoli</h6>
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <span class="badge bg-warning f-s-12">{{ $stats['articles']['pending'] }} in attesa</span>
                        <span class="badge bg-success f-s-12">{{ $stats['articles']['approved'] }} approvati</span>
                        <span class="badge bg-danger f-s-12">{{ $stats['articles']['rejected'] }} rifiutati</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile-First Pending Content and Reports -->
    <div class="row g-3">
        <!-- Video in Attesa -->
        @if($pendingContent['videos']->count() > 0)
        <div class="col-12 col-lg-6">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                        <h5 class="mb-0 f-s-16 f-w-600">
                            <i class="ph-duotone ph-video-camera me-2"></i>
                            {{ __('common.video') }} in Attesa
                        </h5>
                        <a href="{{ route('admin.moderation.pending', ['type' => 'videos']) }}" class="btn btn-sm btn-outline-primary">
                            Vedi tutti
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @foreach($pendingContent['videos'] as $video)
                    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center mb-3 p-3 border rounded gap-2">
                        <div class="flex-shrink-0">
                            <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" class="rounded" style="width: 60px; height: 40px; object-fit: cover;">
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 f-s-14 f-w-600">{{ Str::limit($video->title, 30) }}</h6>
                            <small class="text-muted f-s-12">{{ $video->user->name ?? 'N/A' }}</small>
                        </div>
                        <div class="flex-shrink-0 d-flex gap-1">
                            <button class="btn btn-sm btn-success" onclick="approveContent('videos', {{ $video->id }})" title="Approva">
                                <i class="ph-duotone ph-check f-s-14"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="rejectContent('videos', {{ $video->id }})" title="Rifiuta">
                                <i class="ph-duotone ph-x f-s-14"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Poesie in Attesa -->
        @if($pendingContent['poems']->count() > 0)
        <div class="col-12 col-lg-6">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                        <h5 class="mb-0 f-s-16 f-w-600">
                            <i class="ph-duotone ph-book-open me-2"></i>
                            Poesie in Attesa
                        </h5>
                        <a href="{{ route('admin.moderation.pending', ['type' => 'poems']) }}" class="btn btn-sm btn-outline-primary">
                            Vedi tutti
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @foreach($pendingContent['poems'] as $poem)
                    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center mb-3 p-3 border rounded gap-2">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 f-s-14 f-w-600">{{ Str::limit($poem->title, 30) }}</h6>
                            <small class="text-muted f-s-12">{{ $poem->user->name ?? 'N/A' }}</small>
                        </div>
                        <div class="flex-shrink-0 d-flex gap-1">
                            <button class="btn btn-sm btn-success" onclick="approveContent('poems', {{ $poem->id }})" title="Approva">
                                <i class="ph-duotone ph-check f-s-14"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="rejectContent('poems', {{ $poem->id }})" title="Rifiuta">
                                <i class="ph-duotone ph-x f-s-14"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Eventi in Attesa -->
        @if($pendingContent['events']->count() > 0)
        <div class="col-12 col-lg-6">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                        <h5 class="mb-0 f-s-16 f-w-600">
                            <i class="ph-duotone ph-calendar me-2"></i>
                            Eventi in Attesa
                        </h5>
                        <a href="{{ route('admin.moderation.pending', ['type' => 'events']) }}" class="btn btn-sm btn-outline-primary">
                            Vedi tutti
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @foreach($pendingContent['events'] as $event)
                    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center mb-3 p-3 border rounded gap-2">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 f-s-14 f-w-600">{{ Str::limit($event->title, 30) }}</h6>
                            <small class="text-muted f-s-12">{{ $event->organizer->name ?? 'N/A' }}</small>
                        </div>
                        <div class="flex-shrink-0 d-flex gap-1">
                            <button class="btn btn-sm btn-success" onclick="approveContent('events', {{ $event->id }})" title="Approva">
                                <i class="ph-duotone ph-check f-s-14"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="rejectContent('events', {{ $event->id }})" title="Rifiuta">
                                <i class="ph-duotone ph-x f-s-14"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Foto in Attesa -->
        @if($pendingContent['photos']->count() > 0)
        <div class="col-12 col-lg-6">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                        <h5 class="mb-0 f-s-16 f-w-600">
                            <i class="ph-duotone ph-image me-2"></i>
                            Foto in Attesa
                        </h5>
                        <a href="{{ route('admin.moderation.pending', ['type' => 'photos']) }}" class="btn btn-sm btn-outline-primary">
                            Vedi tutti
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @foreach($pendingContent['photos'] as $photo)
                    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center mb-3 p-3 border rounded gap-2">
                        <div class="flex-shrink-0">
                            <img src="{{ $photo->thumbnail_url }}" alt="{{ $photo->title }}" class="rounded" style="width: 60px; height: 40px; object-fit: cover;">
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 f-s-14 f-w-600">{{ Str::limit($photo->title, 30) }}</h6>
                            <small class="text-muted f-s-12">{{ $photo->user->name ?? 'N/A' }}</small>
                        </div>
                        <div class="flex-shrink-0 d-flex gap-1">
                            <button class="btn btn-sm btn-success" onclick="approveContent('photos', {{ $photo->id }})" title="Approva">
                                <i class="ph-duotone ph-check f-s-14"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="rejectContent('photos', {{ $photo->id }})" title="Rifiuta">
                                <i class="ph-duotone ph-x f-s-14"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Articoli in Attesa -->
        @if($pendingContent['articles']->count() > 0)
        <div class="col-12 col-lg-6">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                        <h5 class="mb-0 f-s-16 f-w-600">
                            <i class="ph-duotone ph-newspaper me-2"></i>
                            Articoli in Attesa
                        </h5>
                        <a href="{{ route('admin.moderation.pending', ['type' => 'articles']) }}" class="btn btn-sm btn-outline-primary">
                            Vedi tutti
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @foreach($pendingContent['articles'] as $article)
                    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center mb-3 p-3 border rounded gap-2">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 f-s-14 f-w-600">{{ Str::limit($article->title, 30) }}</h6>
                            <small class="text-muted f-s-12">{{ $article->user->name ?? 'N/A' }}</small>
                        </div>
                        <div class="flex-shrink-0 d-flex gap-1">
                            <button class="btn btn-sm btn-success" onclick="approveContent('articles', {{ $article->id }})" title="Approva">
                                <i class="ph-duotone ph-check f-s-14"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="rejectContent('articles', {{ $article->id }})" title="Rifiuta">
                                <i class="ph-duotone ph-x f-s-14"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Mobile-First Recent Reports -->
    @if(isset($recentReports) && $recentReports->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="mb-0 f-s-16 f-w-600">
                        <i class="ph-duotone ph-flag me-2"></i>
                        Segnalazioni Recenti
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($recentReports as $report)
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="border rounded p-3">
                                <div class="d-flex flex-column gap-2">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h6 class="mb-1 f-s-14 f-w-600">{{ Str::limit($report->reason, 40) }}</h6>
                                        <span class="badge {{ $report->status === 'pending' ? 'bg-warning' : ($report->status === 'resolved' ? 'bg-success' : 'bg-danger') }} f-s-11">
                                            {{ ucfirst($report->status) }}
                                        </span>
                                    </div>
                                    <small class="text-muted f-s-12">
                                        {{ $report->reportable_type }} • {{ $report->reporter->name ?? 'Anonimo' }}
                                    </small>
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewReport({{ $report->id }})" title="Visualizza">
                                            <i class="ph-duotone ph-eye f-s-12"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" onclick="resolveReport({{ $report->id }})" title="Risolve">
                                            <i class="ph-duotone ph-check-circle f-s-12"></i>
                                        </button>
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
</div>

@endsection

@push('scripts')
<script>
// Mobile-First Moderation Functions
document.addEventListener('DOMContentLoaded', function() {
    // Mobile-specific adjustments
    const isMobile = window.innerWidth < 768;

    if (isMobile) {
        // Adjust card spacing for mobile
        const cards = document.querySelectorAll('.card.hover-effect');
        cards.forEach(card => {
            card.classList.add('mb-3');
        });

        // Make buttons more touch-friendly on mobile
        const buttons = document.querySelectorAll('.btn-sm');
        buttons.forEach(btn => {
            btn.style.minHeight = '44px';
            btn.style.minWidth = '44px';
        });
    }

    // Responsive adjustments
    function adjustMobileLayout() {
        const isMobile = window.innerWidth < 768;
        const statsCards = document.querySelectorAll('.equal-card');

        if (isMobile) {
            statsCards.forEach(card => {
                card.classList.add('mb-3');
            });
        } else {
            statsCards.forEach(card => {
                card.classList.remove('mb-3');
            });
        }
    }

    // Initial adjustment
    adjustMobileLayout();

    // Adjust on resize
    window.addEventListener('resize', adjustMobileLayout);
});

function approveContent(type, id) {
    Swal.fire({
        title: 'Conferma Approvazione',
        text: 'Sei sicuro di voler approvare questo contenuto?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sì, Approva',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostra loading
            Swal.fire({
                title: 'Approvazione in corso...',
                text: 'Attendi mentre approvo il contenuto',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/admin/moderation/approve/${type}/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Approvato!',
                        text: data.message || 'Contenuto approvato con successo',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Errore!',
                        text: data.message || 'Errore durante l\'approvazione'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Errore di Connessione',
                    text: 'Impossibile connettersi al server. Riprova più tardi.'
                });
            });
        }
    });
}

function rejectContent(type, id) {
    Swal.fire({
        title: 'Motivo del Rifiuto',
        input: 'textarea',
        inputLabel: 'Inserisci il motivo del rifiuto',
        inputPlaceholder: 'Spiega perché questo contenuto è stato rifiutato...',
        inputAttributes: {
            'aria-label': 'Motivo del rifiuto'
        },
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Rifiuta',
        cancelButtonText: 'Annulla',
        inputValidator: (value) => {
            if (!value || value.trim().length < 10) {
                return 'Il motivo deve essere di almeno 10 caratteri';
            }
        }
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            // Mostra loading
            Swal.fire({
                title: 'Rifiuto in corso...',
                text: 'Attendi mentre rifiuto il contenuto',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/admin/moderation/reject/${type}/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ reason: result.value.trim() })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Rifiutato!',
                        text: data.message || 'Contenuto rifiutato con successo',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Errore!',
                        text: data.message || 'Errore durante il rifiuto'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Errore di Connessione',
                    text: 'Impossibile connettersi al server. Riprova più tardi.'
                });
            });
        }
    });
}

function viewReport(reportId) {
    Swal.fire({
        title: 'Dettagli Segnalazione',
        text: 'Funzionalità in sviluppo. I dettagli completi saranno disponibili presto.',
        icon: 'info',
        confirmButtonColor: '#007bff'
    });
}

function resolveReport(reportId) {
    Swal.fire({
        title: 'Conferma Risoluzione',
        text: 'Sei sicuro di voler risolvere questa segnalazione?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sì, Risolvi',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostra loading
            Swal.fire({
                title: 'Risoluzione in corso...',
                text: 'Attendi mentre risolvo la segnalazione',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/admin/moderation/reports/${reportId}/handle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    action: 'resolve',
                    notes: 'Segnalazione risolta dall\'amministratore'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Risolto!',
                        text: data.message || 'Segnalazione risolta con successo',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Errore!',
                        text: data.message || 'Errore durante la risoluzione'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Errore di Connessione',
                    text: 'Impossibile connettersi al server. Riprova più tardi.'
                });
            });
        }
    });
}
</script>
@endpush


