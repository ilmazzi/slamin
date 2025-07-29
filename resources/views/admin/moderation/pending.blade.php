@extends('layout.master')

@section('title', 'Contenuti in Attesa - Moderazione')

@section('main-content')
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">Contenuti in Attesa</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-gauge f-s-16"></i> {{ __('dashboard.dashboard') }}
                            </span>
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('admin.moderation.index') }}" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-shield-check f-s-16"></i> Moderazione
                            </span>
                        </a>
                    </li>
                    <li class="active">
                        <span class="f-s-14 f-w-500">
                            <i class="ph-duotone ph-list-checks f-s-16"></i> Contenuti in Attesa
                        </span>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Breadcrumb end -->

        <!-- Header Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h5 class="mb-1 f-w-600">
                            <i class="ph-duotone ph-list-checks me-2"></i>
                            Gestisci i contenuti in attesa di moderazione
                        </h5>
                        <p class="text-muted mb-0">Approva o rifiuta i contenuti degli utenti</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.moderation.index') }}" class="btn btn-outline-secondary">
                            <i class="ph-duotone ph-arrow-left me-2"></i>
                            {{ __('dashboard.dashboard') }}
                        </a>
                        <a href="{{ route('admin.moderation.settings') }}" class="btn btn-outline-primary">
                            <i class="ph-duotone ph-gear me-2"></i>
                            Impostazioni
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtri -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph-duotone ph-funnel me-2"></i>
                            Filtri
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-lg-3 col-md-6">
                                <label class="form-label">Tipo di Contenuto</label>
                                <select name="type" class="form-select">
                                    <option value="all" {{ $type == 'all' ? 'selected' : '' }}>Tutti i contenuti</option>
                                    <option value="videos" {{ $type == 'videos' ? 'selected' : '' }}>{{ __('common.video') }}</option>
                                    <option value="poems" {{ $type == 'poems' ? 'selected' : '' }}>Poesie</option>
                                    <option value="events" {{ $type == 'events' ? 'selected' : '' }}>Eventi</option>
                                    <option value="photos" {{ $type == 'photos' ? 'selected' : '' }}>{{ __('common.photo') }}</option>
                                    <option value="carousels" {{ $type == 'carousels' ? 'selected' : '' }}>Caroselli</option>
                                    <option value="video_comments" {{ $type == 'video_comments' ? 'selected' : '' }}>{{ __('common.comments_section') }} {{ __('common.video') }}</option>
                                    <option value="poem_comments" {{ $type == 'poem_comments' ? 'selected' : '' }}>{{ __('common.comments_section') }} Poesie</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <label class="form-label">{{ __('invitations.status') }}</label>
                                <select name="status" class="form-select">
                                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>{{ __('invitations.pending_invitations') }}</option>
                                    <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approvati</option>
                                    <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>{{ __('invitations.rejected_invitations') }}</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <label class="form-label">Filtro</label>
                                <select name="filter" class="form-select">
                                    <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>Tutti</option>
                                    <option value="pending" {{ $filter == 'pending' ? 'selected' : '' }}>Da Approvare</option>
                                    <option value="reports" {{ $filter == 'reports' ? 'selected' : '' }}>Segnalazioni</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-fill">
                                        <i class="ph-duotone ph-magnifying-glass me-2"></i>
                                        Filtra
                                    </button>
                                    <a href="{{ route('admin.moderation.pending') }}" class="btn btn-outline-secondary">
                                        <i class="ph-duotone ph-arrow-clockwise"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenuti -->
        <div class="row">
            @if($filter == 'reports' || $filter == 'all')
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="ph-duotone ph-flag me-2"></i>
                                Segnalazioni Recenti
                            </h5>
                            <span class="badge bg-warning">{{ $reports->count() }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($reports->count() > 0)
                            <div class="row">
                                @foreach($reports as $report)
                                <div class="col-lg-6 col-md-12 mb-3">
                                    <div class="card card-light-warning hover-effect">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar avatar-md bg-warning">
                                                        <i class="ph-duotone ph-flag text-white"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1 f-w-600">{{ Str::limit($report->reportable_title, 50) }}</h6>
                                                    <p class="text-muted mb-2 f-s-14">
                                                        <i class="ph-duotone ph-user me-1"></i>
                                                        {{ $report->user->name ?? 'N/A' }}
                                                    </p>
                                                    <p class="text-muted mb-2 f-s-14">
                                                        <i class="ph-duotone ph-warning me-1"></i>
                                                        {{ $report->reason_text }}
                                                    </p>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted">
                                                            <i class="ph-duotone ph-calendar me-1"></i>
                                                            {{ $report->created_at->diffForHumans() }}
                                                        </small>
                                                        <div class="d-flex gap-2">
                                                            <span class="badge bg-{{ $report->status_class }}">{{ $report->status_text }}</span>
                                                            <button class="btn btn-sm btn-outline-primary" onclick="handleReport({{ $report->id }}, 'investigate')">
                                                                <i class="ph-duotone ph-magnifying-glass"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="ph-duotone ph-flag f-s-48 text-muted mb-3"></i>
                                <h5 class="text-muted">Nessuna segnalazione attiva</h5>
                                <p class="text-muted">Non ci sono segnalazioni da gestire al momento.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            @if($filter == 'pending' || $filter == 'all')
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="ph-duotone ph-list-checks me-2"></i>
                                Contenuti {{ ucfirst($status) }}
                            </h5>
                            @php
                                $totalContent = 0;
                                foreach($content as $type => $items) {
                                    $totalContent += $items->count();
                                }
                            @endphp
                            <span class="badge bg-primary">{{ $totalContent }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @php
                            $hasContent = false;
                            foreach($content as $type => $items) {
                                if($items->count() > 0) {
                                    $hasContent = true;
                                    break;
                                }
                            }
                        @endphp

                        @if($hasContent)
                            @foreach($content as $type => $items)
                                @if($items->count() > 0)
                                <div class="mb-4">
                                    <h6 class="text-uppercase text-muted mb-3 f-w-600">
                                        <i class="ph-duotone ph-{{ $type == 'videos' ? 'video-camera' : ($type == 'poems' ? 'book-open' : ($type == 'events' ? 'calendar' : ($type == 'photos' ? 'image' : ($type == 'carousels' ? 'slideshow' : 'chat-circle')))) }} me-2"></i>
                                        {{ ucfirst(str_replace('_', ' ', $type)) }} ({{ $items->count() }})
                                    </h6>
                                    <div class="row">
                                        @foreach($items as $item)
                                        <div class="col-lg-6 col-md-12 mb-3">
                                            <div class="card hover-effect">
                                                @if($type == 'videos' && $item->thumbnail_path)
                                                <img src="{{ $item->thumbnail_url }}" class="card-img-top" alt="{{ $item->title }}" style="height: 150px; object-fit: cover;">
                                                @endif
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <h6 class="card-title f-w-600 mb-0">
                                                            {{ Str::limit($item->title ?? $item->content ?? 'N/A', 50) }}
                                                        </h6>
                                                        @if($status == 'pending')
                                                        <div class="d-flex gap-1">
                                                            <button class="btn btn-sm btn-success" onclick="approveContent('{{ $type }}', {{ $item->id }})" title="Approva">
                                                                <i class="ph-duotone ph-check"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-danger" onclick="rejectContent('{{ $type }}', {{ $item->id }})" title="Rifiuta">
                                                                <i class="ph-duotone ph-x"></i>
                                                            </button>
                                                        </div>
                                                        @endif
                                                    </div>

                                                    <p class="text-muted f-s-14 mb-2">
                                                        <i class="ph-duotone ph-user f-s-12 me-1"></i>
                                                        {{ $item->user->name ?? $item->organizer->name ?? 'N/A' }}
                                                    </p>

                                                    @if($item->description)
                                                    <p class="card-text f-s-14 mb-2">{{ Str::limit($item->description, 100) }}</p>
                                                    @endif

                                                    @if($type == 'poems' && $item->content)
                                                    <div class="mb-2">
                                                        <small class="text-muted f-s-12">Anteprima contenuto:</small>
                                                        <p class="f-s-14 mb-0">{{ Str::limit(strip_tags($item->content), 150) }}</p>
                                                    </div>
                                                    @endif

                                                    @if($type == 'events')
                                                    <div class="mb-2">
                                                        <small class="text-muted f-s-12">Dettagli evento:</small>
                                                        <p class="f-s-14 mb-0">
                                                            <i class="ph-duotone ph-calendar me-1"></i>
                                                            {{ $item->start_datetime ? $item->start_datetime->format('d/m/Y H:i') : 'N/A' }}
                                                            @if($item->city)
                                                            <br><i class="ph-duotone ph-map-pin me-1"></i>
                                                            {{ $item->city }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                    @endif

                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted">
                                                            <i class="ph-duotone ph-calendar f-s-12 me-1"></i>
                                                            {{ $item->created_at->diffForHumans() }}
                                                        </small>
                                                        <span class="badge bg-{{ $status == 'pending' ? 'warning' : ($status == 'approved' ? 'success' : 'danger') }}">
                                                            {{ ucfirst($status) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        @else
                            <div class="text-center py-5">
                                <i class="ph-duotone ph-list-checks f-s-48 text-muted mb-3"></i>
                                <h5 class="text-muted">Nessun contenuto trovato</h5>
                                <p class="text-muted">Non ci sono contenuti con i filtri selezionati.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
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

@push('scripts')
<script>
let currentAction = null;
let currentType = null;
let currentId = null;

function approveContent(type, id) {
    currentAction = 'approve';
    currentType = type;
    currentId = id;
    $('#moderationNotes').val('');
    $('#moderationModal').modal('show');
}

function rejectContent(type, id) {
    currentAction = 'reject';
    currentType = type;
    currentId = id;
    $('#moderationNotes').val('');
    $('#moderationModal').modal('show');
}

function handleReport(reportId, action) {
    const notes = prompt('Note (opzionali):');

    $.ajax({
        url: '{{ route("admin.moderation.reports.handle", ["report" => ":report"]) }}'.replace(':report', reportId),
        method: 'POST',
        data: {
            action: action,
            notes: notes,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Successo!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Errore!',
                    text: response.message
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Errore!',
                text: 'Errore durante l\'operazione'
            });
        }
    });
}

$('#confirmModeration').click(function() {
    const notes = $('#moderationNotes').val();
    let url;

    if (currentAction === 'approve') {
        url = '{{ route("admin.moderation.approve", ["type" => ":type", "id" => ":id"]) }}'
            .replace(':type', currentType)
            .replace(':id', currentId);
    } else if (currentAction === 'reject') {
        url = '{{ route("admin.moderation.reject", ["type" => ":type", "id" => ":id"]) }}'
            .replace(':type', currentType)
            .replace(':id', currentId);
    }

    $.ajax({
        url: url,
        method: 'POST',
        data: {
            notes: notes,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Successo!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Errore!',
                    text: response.message
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Errore!',
                text: 'Errore durante l\'operazione'
            });
        }
    });

    $('#moderationModal').modal('hide');
});
</script>
@endpush
