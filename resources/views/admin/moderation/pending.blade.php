@extends('layout.master')

@section('title', 'Contenuti in Attesa - Moderazione')

@section('main-content')
<div class="page-wrapper">
    <div class="page-content">
        <!-- Mobile-First Breadcrumb -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title f-s-18 f-w-600">Contenuti in Attesa</h4>
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

        <!-- Mobile-First Header Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                    <div>
                        <h5 class="mb-1 f-w-600 f-s-16">
                            <i class="ph-duotone ph-list-checks me-2"></i>
                            Gestisci i contenuti in attesa di moderazione
                        </h5>
                        <p class="text-muted mb-0 f-s-14">Approva o rifiuta i contenuti degli utenti</p>
                    </div>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <a href="{{ route('admin.moderation.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="ph-duotone ph-arrow-left me-2"></i>
                            {{ __('dashboard.dashboard') }}
                        </a>
                        <a href="{{ route('admin.moderation.settings') }}" class="btn btn-outline-primary btn-sm">
                            <i class="ph-duotone ph-gear me-2"></i>
                            Impostazioni
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile-First Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 f-s-16 f-w-600">
                            <i class="ph-duotone ph-funnel me-2"></i>
                            Filtri
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="row g-3">
                            <div class="col-12 col-sm-6 col-lg-3">
                                <label class="form-label f-s-14 f-w-500">Tipo di Contenuto</label>
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
                            <div class="col-12 col-sm-6 col-lg-3">
                                <label class="form-label f-s-14 f-w-500">{{ __('invitations.status') }}</label>
                                <select name="status" class="form-select">
                                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>{{ __('invitations.pending_invitations') }}</option>
                                    <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approvati</option>
                                    <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>{{ __('invitations.rejected_invitations') }}</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <label class="form-label f-s-14 f-w-500">Filtro</label>
                                <select name="filter" class="form-select">
                                    <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>Tutti</option>
                                    <option value="pending" {{ $filter == 'pending' ? 'selected' : '' }}>Da Approvare</option>
                                    <option value="reports" {{ $filter == 'reports' ? 'selected' : '' }}>Segnalazioni</option>
                                    <option value="flagged" {{ $filter == 'flagged' ? 'selected' : '' }}>Contrassegnati</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <label class="form-label f-s-14 f-w-500">Ordina per</label>
                                <select name="sort" class="form-select">
                                    <option value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>Più recenti</option>
                                    <option value="oldest" {{ $sort == 'oldest' ? 'selected' : '' }}>Più vecchi</option>
                                    <option value="priority" {{ $sort == 'priority' ? 'selected' : '' }}>Priorità</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="d-flex flex-column flex-sm-row gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="ph-duotone ph-magnifying-glass me-2"></i>
                                        Applica Filtri
                                    </button>
                                    <a href="{{ route('admin.moderation.pending') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="ph-duotone ph-arrow-clockwise me-2"></i>
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile-First Content List -->
        <div class="row g-3">
            @forelse($contents as $content)
            <div class="col-12 col-lg-6">
                <div class="card hover-effect">
                    <div class="card-header">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ph-duotone {{ getContentIcon($content->type) }} f-s-16"></i>
                                <h6 class="mb-0 f-s-16 f-w-600">{{ getContentTypeName($content->type) }}</h6>
                            </div>
                            <span class="badge {{ getStatusBadgeClass($content->status) }} f-s-12">
                                {{ getStatusText($content->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Mobile-First Content Preview -->
                        <div class="d-flex flex-column gap-3">
                            <!-- Content Header -->
                            <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-2">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 f-s-16 f-w-600">{{ Str::limit($content->title ?? $content->content, 50) }}</h6>
                                    <small class="text-muted f-s-12">
                                        <i class="ph-duotone ph-user me-1"></i>
                                        {{ $content->user->name ?? 'N/A' }}
                                    </small>
                                </div>
                                <div class="flex-shrink-0">
                                    <small class="text-muted f-s-12">
                                        <i class="ph-duotone ph-calendar me-1"></i>
                                        {{ $content->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>

                            <!-- Content Preview -->
                            @if($content->type === 'videos' && $content->thumbnail_url)
                            <div class="text-center">
                                <img src="{{ $content->thumbnail_url }}" alt="{{ $content->title }}" 
                                     class="img-fluid rounded" style="max-height: 150px;">
                            </div>
                            @elseif($content->type === 'photos' && $content->image_url)
                            <div class="text-center">
                                <img src="{{ $content->image_url }}" alt="{{ $content->title }}" 
                                     class="img-fluid rounded" style="max-height: 150px;">
                            </div>
                            @elseif($content->type === 'poems' && $content->content)
                            <div class="border rounded p-3 bg-light">
                                <pre class="mb-0 f-s-13" style="white-space: pre-wrap;">{{ Str::limit($content->content, 200) }}</pre>
                            </div>
                            @elseif($content->type === 'events' && $content->description)
                            <div class="border rounded p-3 bg-light">
                                <p class="mb-0 f-s-13">{{ Str::limit($content->description, 200) }}</p>
                            </div>
                            @endif

                            <!-- Mobile-First Action Buttons -->
                            <div class="d-flex flex-column flex-sm-row gap-2">
                                <div class="d-flex gap-1 flex-grow-1">
                                    <button class="btn btn-success btn-sm flex-fill" 
                                            onclick="approveContent('{{ $content->type }}', {{ $content->id }})" 
                                            title="Approva">
                                        <i class="ph-duotone ph-check f-s-14 me-1"></i>
                                        <span class="d-none d-sm-inline">Approva</span>
                                    </button>
                                    <button class="btn btn-danger btn-sm flex-fill" 
                                            onclick="rejectContent('{{ $content->type }}', {{ $content->id }})" 
                                            title="Rifiuta">
                                        <i class="ph-duotone ph-x f-s-14 me-1"></i>
                                        <span class="d-none d-sm-inline">Rifiuta</span>
                                    </button>
                                </div>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-outline-primary btn-sm" 
                                            onclick="viewContent('{{ $content->type }}', {{ $content->id }})" 
                                            title="Visualizza">
                                        <i class="ph-duotone ph-eye f-s-14"></i>
                                    </button>
                                    <button class="btn btn-outline-info btn-sm" 
                                            onclick="editContent('{{ $content->type }}', {{ $content->id }})" 
                                            title="Modifica">
                                        <i class="ph-duotone ph-pencil f-s-14"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Additional Info -->
                            @if($content->reports_count > 0)
                            <div class="alert alert-warning py-2 mb-0">
                                <small class="f-s-12">
                                    <i class="ph-duotone ph-flag me-1"></i>
                                    {{ $content->reports_count }} segnalazione{{ $content->reports_count > 1 ? 'i' : '' }}
                                </small>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ph-duotone ph-check-circle f-s-48 text-success mb-3"></i>
                        <h5 class="f-s-18 f-w-600">Nessun contenuto in attesa</h5>
                        <p class="text-muted f-s-14">Tutti i contenuti sono stati moderati!</p>
                    </div>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Mobile-First Pagination -->
        @if($contents->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $contents->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
// Mobile-First Moderation Functions
document.addEventListener('DOMContentLoaded', function() {
    // Mobile-specific adjustments
    const isMobile = window.innerWidth < 768;
    
    if (isMobile) {
        // Make buttons more touch-friendly on mobile
        const buttons = document.querySelectorAll('.btn-sm');
        buttons.forEach(btn => {
            btn.style.minHeight = '44px';
            btn.style.minWidth = '44px';
        });
        
        // Adjust card spacing for mobile
        const cards = document.querySelectorAll('.card.hover-effect');
        cards.forEach(card => {
            card.classList.add('mb-3');
        });
    }
    
    // Responsive adjustments
    function adjustMobileLayout() {
        const isMobile = window.innerWidth < 768;
        const contentCards = document.querySelectorAll('.card.hover-effect');
        
        if (isMobile) {
            contentCards.forEach(card => {
                card.classList.add('mb-3');
            });
        } else {
            contentCards.forEach(card => {
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
    if (confirm('Sei sicuro di voler approvare questo contenuto?')) {
        fetch(`/admin/moderation/${type}/${id}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Errore durante l\'approvazione: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Errore di connessione');
        });
    }
}

function rejectContent(type, id) {
    const reason = prompt('Motivo del rifiuto:');
    if (reason) {
        fetch(`/admin/moderation/${type}/${id}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Errore durante il rifiuto: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Errore di connessione');
        });
    }
}

function viewContent(type, id) {
    // Implementation for viewing content
    console.log('Viewing content:', type, id);
}

function editContent(type, id) {
    // Implementation for editing content
    console.log('Editing content:', type, id);
}
</script>
@endpush

@php
function getContentIcon($type) {
    $icons = [
        'videos' => 'ph-video-camera',
        'photos' => 'ph-image',
        'poems' => 'ph-book-open',
        'events' => 'ph-calendar',
        'articles' => 'ph-newspaper',
        'carousels' => 'ph-images',
        'video_comments' => 'ph-chat-circle',
        'poem_comments' => 'ph-chat-circle'
    ];
    return $icons[$type] ?? 'ph-file-text';
}

function getContentTypeName($type) {
    $names = [
        'videos' => 'Video',
        'photos' => 'Foto',
        'poems' => 'Poesia',
        'events' => 'Evento',
        'articles' => 'Articolo',
        'carousels' => 'Carosello',
        'video_comments' => 'Commento Video',
        'poem_comments' => 'Commento Poesia'
    ];
    return $names[$type] ?? 'Contenuto';
}

function getStatusBadgeClass($status) {
    $classes = [
        'pending' => 'bg-warning',
        'approved' => 'bg-success',
        'rejected' => 'bg-danger',
        'flagged' => 'bg-info'
    ];
    return $classes[$status] ?? 'bg-secondary';
}

function getStatusText($status) {
    $texts = [
        'pending' => 'In Attesa',
        'approved' => 'Approvato',
        'rejected' => 'Rifiutato',
        'flagged' => 'Contrassegnato'
    ];
    return $texts[$status] ?? 'Sconosciuto';
}
@endphp
