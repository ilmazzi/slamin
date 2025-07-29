@extends('layout.master')

@section('title', __('poems.drafts.title'))

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">{{ __('poems.drafts.title') }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">{{ __('common.home') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('poems.index') }}">{{ __('poems.title') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('poems.my-poems') }}">{{ __('poems.my_poems.title') }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('poems.drafts.title') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiche bozze -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card card-light-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-1">{{ $drafts->total() }}</h4>
                            <p class="text-muted mb-0">{{ __('poems.drafts.total_drafts') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ph ph-floppy-disk display-4 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-light-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-1">{{ $drafts->where('draft_saved_at', '>=', now()->subDays(7))->count() }}</h4>
                            <p class="text-muted mb-0">{{ __('poems.drafts.recent_drafts') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ph ph-clock display-4 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-light-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-1">{{ $drafts->where('word_count', '>=', 100)->count() }}</h4>
                            <p class="text-muted mb-0">{{ __('poems.drafts.near_complete') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ph ph-check-circle display-4 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-light-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-1">{{ $drafts->sum('word_count') }}</h4>
                            <p class="text-muted mb-0">{{ __('poems.drafts.total_words') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ph ph-text-aa display-4 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header con azioni -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">
                                <i class="ph ph-floppy-disk text-warning me-2"></i>
                                {{ __('poems.drafts.your_drafts') }}
                            </h5>
                            <p class="text-muted mb-0">{{ __('poems.drafts.description') }}</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('poems.my-poems') }}" class="btn btn-outline-primary">
                                <i class="ph ph-list me-2"></i>
                                {{ __('poems.drafts.view_all_poems') }}
                            </a>
                            <a href="{{ route('poems.create') }}" class="btn btn-primary">
                                <i class="ph ph-plus me-2"></i>
                                {{ __('poems.drafts.create_new') }}
                            </a>
                            <a href="{{ route('poems.index') }}" class="btn btn-outline-secondary">
                                <i class="ph ph-globe me-2"></i>
                                {{ __('poems.title') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista bozze -->
    <div class="row">
        @forelse($drafts as $draft)
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card hover-effect">
                    <!-- {{ __('common.thumbnail') }} -->
                    @if($draft->thumbnail_path)
                        <div class="card-img-top">
                            <img src="{{ $draft->thumbnail_url }}" class="img-fluid" alt="{{ $draft->title }}">
                        </div>
                    @endif

                    <div class="card-body">
                        <!-- Status badge -->
                        <div class="mb-2">
                            <span class="badge bg-warning">
                                <i class="ph ph-floppy-disk me-1"></i>{{ __('poems.status.draft') }}
                            </span>

                            @if($draft->word_count >= 100)
                                <span class="badge bg-success">
                                    <i class="ph ph-check-circle me-1"></i>{{ __('poems.drafts.near_complete_badge') }}
                                </span>
                            @else
                                <span class="badge bg-info">
                                    <i class="ph ph-pencil me-1"></i>{{ __('poems.drafts.in_progress') }}
                                </span>
                            @endif
                        </div>

                        <!-- Titolo -->
                        <h5 class="card-title">
                            <a href="{{ route('poems.edit', $draft) }}" class="text-decoration-none">
                                {{ $draft->title ?: __('poems.drafts.untitled') }}
                            </a>
                        </h5>

                        <!-- Categoria e tipo -->
                        @if($draft->category && $draft->poem_type)
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="ph ph-tag me-1"></i>{{ $draft->category }} • {{ $draft->poem_type }}
                                </small>
                            </div>
                        @endif

                        <!-- Anteprima contenuto -->
                        <p class="card-text text-muted small">
                            {{ Str::limit($draft->content, 150) ?: __('poems.drafts.no_content') }}
                        </p>

                        <!-- Statistiche -->
                        <div class="row text-center mb-3">
                            <div class="col-4">
                                <small class="text-muted">
                                    <i class="ph ph-text-aa me-1"></i>{{ $draft->word_count }}
                                </small>
                            </div>
                            <div class="col-4">
                                <small class="text-muted">
                                    <i class="ph ph-clock me-1"></i>{{ $draft->draft_saved_at ? $draft->draft_saved_at->diffForHumans() : __('poems.drafts.never_saved') }}
                                </small>
                            </div>
                            <div class="col-4">
                                <small class="text-muted">
                                    <i class="ph ph-calendar me-1"></i>{{ $draft->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>

                        <!-- Progresso -->
                        @if($draft->word_count > 0)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">{{ __('poems.drafts.completion') }}</small>
                                    <small class="text-muted">{{ min(100, ($draft->word_count / 50) * 100) }}%</small>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-warning"
                                         style="width: {{ min(100, ($draft->word_count / 50) * 100) }}%"></div>
                                </div>
                            </div>
                        @endif

                        <!-- {{ __('invitations.actions') }} -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('poems.edit', $draft) }}" class="btn btn-outline-warning">
                                    <i class="ph ph-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-outline-success" onclick="publishDraft({{ $draft->id }})">
                                    <i class="ph ph-paper-plane"></i>
                                </button>
                                <form action="{{ route('poems.destroy', $draft) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger"
                                            onclick="return confirm('{{ __('poems.delete_confirm') }}')">
                                        <i class="ph ph-trash"></i>
                                    </button>
                                </form>
                            </div>

                            <div class="dropdown">
                                <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                    <i class="ph ph-dots-three-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('poems.edit', $draft) }}">
                                            <i class="ph ph-pencil me-2"></i>{{ __('common.edit') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="publishDraft({{ $draft->id }})">
                                            <i class="ph ph-paper-plane me-2"></i>{{ __('poems.drafts.publish') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="duplicateDraft({{ $draft->id }})">
                                            <i class="ph ph-copy me-2"></i>{{ __('poems.drafts.duplicate') }}
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('poems.destroy', $draft) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger"
                                                    onclick="return confirm('{{ __('poems.delete_confirm') }}')">
                                                <i class="ph ph-trash me-2"></i>{{ __('common.delete') }}
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ph ph-floppy-disk display-1 text-muted mb-3"></i>
                        <h4 class="text-muted mb-3">{{ __('poems.drafts.no_drafts') }}</h4>
                        <p class="text-muted mb-4">{{ __('poems.drafts.no_drafts_description') }}</p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('poems.create') }}" class="btn btn-primary">
                                <i class="ph ph-plus me-2"></i>
                                {{ __('poems.drafts.create_first_draft') }}
                            </a>
                            <a href="{{ route('poems.my-poems') }}" class="btn btn-outline-primary">
                                <i class="ph ph-list me-2"></i>
                                {{ __('poems.drafts.view_published_poems') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Paginazione -->
    @if($drafts->hasPages())
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{ $drafts->links() }}
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
function publishDraft(draftId) {
    if (confirm('{{ __("poems.drafts.publish_confirm") }}')) {
        // Reindirizza alla pagina di modifica con il flag per pubblicare
        window.location.href = `/poems/${draftId}/edit?action=publish`;
    }
}

function duplicateDraft(draftId) {
    if (confirm('{{ __("poems.drafts.duplicate_confirm") }}')) {
        // Implementa la duplicazione della bozza
        fetch(`/poems/${draftId}/duplicate`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || '{{ __("poems.drafts.duplicate_error") }}');
            }
        });
    }
}
</script>
@endpush
@endsection
