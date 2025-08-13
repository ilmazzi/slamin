@extends('layout.master')

@section('title', __('poems.my_poems.title'))

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">{{ __('poems.my_poems.title') }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">{{ __('common.home') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('poems.index') }}">{{ __('poems.title') }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('poems.my_poems.title') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiche rapide -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card card-light-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-1">{{ $poems->total() }}</h4>
                            <p class="text-muted mb-0">{{ __('poems.my_poems.total_poems') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ph ph-pen-nib display-4 text-primary"></i>
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
                            <h4 class="mb-1">{{ $poems->where('is_public', true)->count() }}</h4>
                            <p class="text-muted mb-0">{{ __('poems.my_poems.published') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ph ph-globe display-4 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-light-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-1">{{ $poems->where('is_draft', true)->count() }}</h4>
                            <p class="text-muted mb-0">{{ __('poems.my_poems.drafts') }}</p>
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
                            <h4 class="mb-1">{{ $poems->sum('like_count') }}</h4>
                            <p class="text-muted mb-0">{{ __('poems.my_poems.total_likes') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ph ph-heart display-4 text-info"></i>
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
                                <i class="ph ph-list text-primary me-2"></i>
                                {{ __('poems.my_poems.your_poems') }}
                            </h5>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('poems.drafts') }}" class="btn btn-outline-warning">
                                <i class="ph ph-floppy-disk me-2"></i>
                                {{ __('poems.my_poems.view_drafts') }}
                            </a>
                            <a href="{{ route('poems.create') }}" class="btn btn-primary">
                                <i class="ph ph-plus me-2"></i>
                                {{ __('poems.my_poems.create_new') }}
                            </a>
                            <a href="{{ route('poems.index') }}" class="btn btn-outline-primary">
                                <i class="ph ph-list me-2"></i>
                                {{ __('poems.title') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista poesie -->
    <div class="row">
        @forelse($poems as $poem)
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card hover-effect">
                    <!-- {{ __('common.thumbnail') }} -->
                    @if($poem->thumbnail)
                        <div class="card-img-top">
                            <img src="{{ $poem->thumbnail }}" class="img-fluid" alt="{{ $poem->title }}">
                        </div>
                    @endif

                    <div class="card-body">
                        <!-- Status badge -->
                        <div class="mb-2">
                            @if($poem->is_draft)
                                <span class="badge bg-warning">
                                    <i class="ph ph-floppy-disk me-1"></i>{{ __('poems.status.draft') }}
                                </span>
                            @elseif($poem->is_public)
                                <span class="badge bg-success">
                                    <i class="ph ph-globe me-1"></i>{{ __('poems.status.published') }}
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="ph ph-lock me-1"></i>{{ __('poems.status.private') }}
                                </span>
                            @endif

                            @if($poem->is_featured)
                                <span class="badge bg-primary">
                                    <i class="ph ph-star me-1"></i>{{ __('poems.status.featured') }}
                                </span>
                            @endif
                        </div>

                        <!-- Titolo -->
                        <h5 class="card-title">
                            <a href="{{ route('poems.show', $poem) }}" class="text-decoration-none">
                                {{ $poem->title }}
                            </a>
                        </h5>

                        <!-- Categoria e tipo -->
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="ph ph-tag me-1"></i>{{ $poem->category }} • {{ $poem->poem_type }}
                            </small>
                        </div>

                        <!-- Anteprima contenuto -->
                        <p class="card-text text-muted small">
                            {{ Str::limit($poem->content, 150) }}
                        </p>

                        <!-- Statistiche -->
                        <div class="row text-center mb-3">
                            <div class="col-4">
                                <small class="text-muted">
                                    <i class="ph ph-eye me-1"></i>{{ number_format($poem->view_count) }}
                                </small>
                            </div>
                            <div class="col-4">
                                <small class="text-muted">
                                    <i class="ph ph-heart me-1"></i>{{ number_format($poem->like_count) }}
                                </small>
                            </div>
                            <div class="col-4">
                                <small class="text-muted">
                                    <i class="ph ph-chat-circle me-1"></i>{{ number_format($poem->comment_count) }}
                                </small>
                            </div>
                        </div>

                        <!-- Data -->
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="ph ph-calendar me-1"></i>
                                @if($poem->published_at)
                                    {{ __('poems.my_poems.published_on') }} {{ $poem->published_at->format('d/m/Y') }}
                                @else
                                    {{ __('poems.my_poems.created_on') }} {{ $poem->created_at->format('d/m/Y') }}
                                @endif
                            </small>
                        </div>

                        <!-- {{ __('invitations.actions') }} -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('poems.show', $poem) }}" class="btn btn-outline-primary">
                                    <i class="ph ph-eye"></i>
                                </a>
                                @if($poem->canBeEditedBy(auth()->user()))
                                    <a href="{{ route('poems.edit', $poem) }}" class="btn btn-outline-secondary">
                                        <i class="ph ph-pencil"></i>
                                    </a>
                                @endif
                                @if($poem->canBeDeletedBy(auth()->user()))
                                    <form action="{{ route('poems.destroy', $poem) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger"
                                                onclick="return confirm('{{ __('poems.delete_confirm') }}')">
                                            <i class="ph ph-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>

                            <div class="dropdown">
                                <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                    <i class="ph ph-dots-three-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('poems.show', $poem) }}">
                                            <i class="ph ph-eye me-2"></i>{{ __('common.view') }}
                                        </a>
                                    </li>
                                    @if($poem->canBeEditedBy(auth()->user()))
                                        <li>
                                            <a class="dropdown-item" href="{{ route('poems.edit', $poem) }}">
                                                <i class="ph ph-pencil me-2"></i>{{ __('common.edit') }}
                                            </a>
                                        </li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="sharePoem('{{ route('poems.show', $poem) }}')">
                                            <i class="ph ph-share me-2"></i>{{ __('common.share') }}
                                        </a>
                                    </li>
                                    @if($poem->canBeDeletedBy(auth()->user()))
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('poems.destroy', $poem) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"
                                                        onclick="return confirm('{{ __('poems.delete_confirm') }}')">
                                                    <i class="ph ph-trash me-2"></i>{{ __('common.delete') }}
                                                </button>
                                            </form>
                                        </li>
                                    @endif
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
                        <i class="ph ph-pen-nib display-1 text-muted mb-3"></i>
                        <h4 class="text-muted mb-3">{{ __('poems.my_poems.no_poems') }}</h4>
                        <p class="text-muted mb-4">{{ __('poems.my_poems.no_poems_description') }}</p>
                        <a href="{{ route('poems.create') }}" class="btn btn-primary">
                            <i class="ph ph-plus me-2"></i>
                            {{ __('poems.my_poems.create_first_poem') }}
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Paginazione -->
    @if($poems->hasPages())
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{ $poems->links() }}
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
function sharePoem(url) {
    if (navigator.share) {
        navigator.share({
            title: '{{ __("poems.share_title") }}',
            text: '{{ __("poems.share_text") }}',
            url: url,
        });
    } else {
        // Fallback: copia l'URL negli appunti
        navigator.clipboard.writeText(url).then(() => {
            alert('{{ __("poems.url_copied") }}');
        });
    }
}
</script>
@endpush
@endsection
