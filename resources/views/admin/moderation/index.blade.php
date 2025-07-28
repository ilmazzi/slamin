@extends('layout.master')

@section('title', 'Dashboard Moderazione')

@section('main-content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 f-w-600">
                        <i class="ph-duotone ph-shield-check me-2"></i>
                        Dashboard Moderazione
                    </h4>
                    <p class="text-muted mb-0">Gestisci la moderazione di tutti i contenuti</p>
                </div>
                <div>
                    <a href="{{ route('admin.moderation.settings') }}" class="btn btn-outline-primary me-2">
                        <i class="ph-duotone ph-gear me-2"></i>
                        Impostazioni
                    </a>
                    <a href="{{ route('admin.moderation.pending') }}" class="btn btn-primary">
                        <i class="ph-duotone ph-list-checks me-2"></i>
                        Contenuti in Attesa
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiche -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card hover-effect">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-primary">
                                <i class="ph-duotone ph-video-camera text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Video</h6>
                            <div class="d-flex gap-2">
                                <span class="badge bg-warning">{{ $stats['videos']['pending'] }} in attesa</span>
                                <span class="badge bg-success">{{ $stats['videos']['approved'] }} approvati</span>
                                <span class="badge bg-danger">{{ $stats['videos']['rejected'] }} rifiutati</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card hover-effect">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-success">
                                <i class="ph-duotone ph-book-open text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Poesie</h6>
                            <div class="d-flex gap-2">
                                <span class="badge bg-warning">{{ $stats['poems']['pending'] }} in attesa</span>
                                <span class="badge bg-success">{{ $stats['poems']['approved'] }} approvate</span>
                                <span class="badge bg-danger">{{ $stats['poems']['rejected'] }} rifiutate</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card hover-effect">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-info">
                                <i class="ph-duotone ph-calendar text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Eventi</h6>
                            <div class="d-flex gap-2">
                                <span class="badge bg-warning">{{ $stats['events']['pending'] }} in attesa</span>
                                <span class="badge bg-success">{{ $stats['events']['approved'] }} approvati</span>
                                <span class="badge bg-danger">{{ $stats['events']['rejected'] }} rifiutati</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card hover-effect">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-warning">
                                <i class="ph-duotone ph-image text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Foto</h6>
                            <div class="d-flex gap-2">
                                <span class="badge bg-warning">{{ $stats['photos']['pending'] }} in attesa</span>
                                <span class="badge bg-success">{{ $stats['photos']['approved'] }} approvate</span>
                                <span class="badge bg-danger">{{ $stats['photos']['rejected'] }} rifiutate</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenuti in Attesa e Segnalazioni -->
    <div class="row">
        <!-- Video in Attesa -->
        @if($pendingContent['videos']->count() > 0)
        <div class="col-lg-6 mb-4">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ph-duotone ph-video-camera me-2"></i>
                            Video in Attesa
                        </h5>
                        <a href="{{ route('admin.moderation.pending', ['type' => 'videos']) }}" class="btn btn-sm btn-outline-primary">
                            Vedi tutti
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @foreach($pendingContent['videos'] as $video)
                    <div class="d-flex align-items-center mb-3 p-2 border rounded">
                        <div class="flex-shrink-0">
                            <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" class="rounded" style="width: 60px; height: 40px; object-fit: cover;">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">{{ Str::limit($video->title, 30) }}</h6>
                            <small class="text-muted">{{ $video->user->name ?? 'N/A' }}</small>
                        </div>
                        <div class="flex-shrink-0">
                            <button class="btn btn-sm btn-success me-1" onclick="approveContent('videos', {{ $video->id }})">
                                <i class="ph-duotone ph-check"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="rejectContent('videos', {{ $video->id }})">
                                <i class="ph-duotone ph-x"></i>
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
        <div class="col-lg-6 mb-4">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
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
                    <div class="d-flex align-items-center mb-3 p-2 border rounded">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ Str::limit($poem->title, 30) }}</h6>
                            <small class="text-muted">{{ $poem->user->name ?? 'N/A' }}</small>
                        </div>
                        <div class="flex-shrink-0">
                            <button class="btn btn-sm btn-success me-1" onclick="approveContent('poems', {{ $poem->id }})">
                                <i class="ph-duotone ph-check"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="rejectContent('poems', {{ $poem->id }})">
                                <i class="ph-duotone ph-x"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Segnalazioni Recenti -->
        @if($reports->count() > 0)
        <div class="col-lg-6 mb-4">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ph-duotone ph-flag me-2"></i>
                            Segnalazioni Recenti
                        </h5>
                        <a href="{{ route('admin.moderation.pending', ['filter' => 'reports']) }}" class="btn btn-sm btn-outline-warning">
                            Vedi tutte
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @foreach($reports->take(5) as $report)
                    <div class="d-flex align-items-center mb-3 p-2 border rounded">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-sm bg-warning">
                                <i class="ph-duotone ph-flag text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">{{ Str::limit($report->reportable_title, 30) }}</h6>
                            <small class="text-muted">
                                {{ $report->reason_text }} • {{ $report->user->name ?? 'N/A' }}
                            </small>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="badge bg-{{ $report->status_class }}">{{ $report->status_text }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal per note di moderazione -->
<div class="modal fade" id="moderationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Note di Moderazione</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <textarea id="moderationNotes" class="form-control" rows="3" placeholder="Inserisci note opzionali..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" id="confirmModeration">Conferma</button>
            </div>
        </div>
    </div>
</div>

@endsection



<!-- Kanban Board JS -->
<script src="{{ asset('assets/js/kanban_board.js') }}?v={{ time() }}"></script>


