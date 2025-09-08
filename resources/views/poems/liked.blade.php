@extends('layout.master')

@section('title', __('poems.liked.title'))

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">{{ __('poems.liked.title') }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">{{ __('common.home') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('poems.index') }}">{{ __('poems.title') }}</a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('poems.liked.title') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiche like -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card card-light-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-1">{{ $likedPoems->total() }}</h4>
                            <p class="text-muted mb-0">{{ __('poems.liked.total_liked') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ph ph-heart display-4 text-danger"></i>
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
                            <h4 class="mb-1">{{ $likedPoems->where('pivot.created_at', '>=', now()->subDays(7))->count() }}</h4>
                            <p class="text-muted mb-0">{{ __('poems.liked.recent_likes') }}</p>
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
                            <h4 class="mb-1">{{ $likedPoems->unique('user_id')->count() }}</h4>
                            <p class="text-muted mb-0">{{ __('poems.liked.authors') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ph ph-users display-4 text-success"></i>
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
                            <h4 class="mb-1">{{ $likedPoems->unique('category')->count() }}</h4>
                            <p class="text-muted mb-0">{{ __('poems.liked.categories') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ph ph-tag display-4 text-primary"></i>
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
                                <i class="ph ph-heart text-danger me-2"></i>
                                {{ __('poems.liked.your_liked_poems') }}
                            </h5>
                            <p class="text-muted mb-0">{{ __('poems.liked.description') }}</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('poems.create') }}" class="btn btn-primary">
                                <i class="ph ph-plus me-2"></i>
                                {{ __('poems.create.title') }}
                            </a>
                            <a href="{{ route('poems.bookmarks') }}" class="btn btn-outline-warning">
                                <i class="ph ph-bookmark me-2"></i>
                                {{ __('poems.liked.view_bookmarks') }}
                            </a>
                            <a href="{{ route('poems.index') }}" class="btn btn-outline-primary">
                                <i class="ph ph-magnifying-glass me-2"></i>
                                {{ __('poems.liked.explore_poems') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtri -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="category" class="form-label">{{ __('poems.filters.category') }}</label>
                            <select class="form-select" id="category" name="category">
                                <option value="">{{ __('common.all') }}</option>
                                @foreach($categories ?? [] as $key => $category)
                                    <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="sort" class="form-label">{{ __('poems.filters.sort') }}</label>
                            <select class="form-select" id="sort" name="sort">
                                <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>
                                    {{ __('poems.filters.recent') }}
                                </option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                                    {{ __('poems.filters.oldest') }}
                                </option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>
                                    {{ __('poems.filters.popular') }}
                                </option>
                                <option value="alphabetical" {{ request('sort') == 'alphabetical' ? 'selected' : '' }}>
                                    {{ __('poems.filters.alphabetical') }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ph ph-funnel me-2"></i>
                                    {{ __('common.filter') }}
                                </button>
                                <a href="{{ route('poems.liked') }}" class="btn btn-outline-secondary">
                                    <i class="ph ph-arrow-clockwise me-2"></i>
                                    {{ __('common.reset') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista poesie piaciute -->
    <div class="row">
        @forelse($likedPoems as $poem)
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
                            <span class="badge bg-danger">
                                <i class="ph ph-heart me-1"></i>{{ __('poems.status.liked') }}
                            </span>

                            @if($poem->is_featured)
                                <span class="badge bg-primary">
                                    <i class="ph ph-star me-1"></i>{{ __('poems.status.featured') }}
                                </span>
                            @endif

                            <span class="badge bg-secondary">{{ $poem->category }}</span>
                        </div>

                        <!-- Titolo -->
                        <h5 class="card-title">
                            <a href="{{ route('poems.show', $poem->slug) }}" class="text-decoration-none">
                                {{ $poem->title }}
                            </a>
                        </h5>

                        <!-- Autore -->
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="ph ph-user me-1"></i>
                                <a href="{{ route('user.show', $poem->user) }}" class="text-decoration-none hover-effect">{{ $poem->user->getDisplayName() }}</a>
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
                                    <i class="ph ph-eye me-1"></i>{{ number_format($poem->views_count) }}
                                </small>
                            </div>
                            <div class="col-4">
                                <small class="text-muted">
                                    <i class="ph ph-heart me-1"></i>{{ number_format($poem->likes_count) }}
                                </small>
                            </div>
                            <div class="col-4">
                                <small class="text-muted">
                                    <i class="ph ph-chat-circle me-1"></i>{{ number_format($poem->comments_count) }}
                                </small>
                            </div>
                        </div>

                        <!-- Data like -->
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="ph ph-heart me-1"></i>
                                {{ __('poems.liked.liked_on') }} {{ $poem->pivot->created_at->format('d/m/Y') }}
                            </small>
                        </div>

                        <!-- {{ __('invitations.actions') }} -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('poems.show', $poem->slug) }}" class="btn btn-outline-primary">
                                    <i class="ph ph-eye"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" onclick="unlikePoem({{ $poem->id }})">
                                    <i class="ph ph-heart-fill"></i>
                                </button>
                                <button type="button" class="btn btn-outline-warning" onclick="toggleBookmark({{ $poem->id }})">
                                    <i class="ph ph-bookmark{{ $poem->is_bookmarked_by_current_user ? '-fill' : '' }}"></i>
                                </button>
                                <button type="button" class="btn btn-outline-info" onclick="sharePoem('{{ route('poems.show', $poem->slug) }}')">
                                    <i class="ph ph-share"></i>
                                </button>
                            </div>

                            <div class="dropdown">
                                <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                    <i class="ph ph-dots-three-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('poems.show', $poem->slug) }}">
                                            <i class="ph ph-eye me-2"></i>{{ __('common.view') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="sharePoem('{{ route('poems.show', $poem->slug) }}')">
                                            <i class="ph ph-share me-2"></i>{{ __('common.share') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="toggleBookmark({{ $poem->id }})">
                                            <i class="ph ph-bookmark{{ $poem->is_bookmarked_by_current_user ? '-fill' : '' }} me-2"></i>
                                            {{ $poem->is_bookmarked_by_current_user ? __('poems.bookmarks.remove_bookmark') : __('poems.bookmarks.add_bookmark') }}
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#" onclick="unlikePoem({{ $poem->id }})">
                                            <i class="ph ph-heart-fill me-2"></i>{{ __('poems.liked.unlike') }}
                                        </a>
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
                        <i class="ph ph-heart display-1 text-muted mb-3"></i>
                        <h4 class="text-muted mb-3">{{ __('poems.liked.no_liked_poems') }}</h4>
                        <p class="text-muted mb-4">{{ __('poems.liked.no_liked_poems_description') }}</p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('poems.index') }}" class="btn btn-primary">
                                <i class="ph ph-magnifying-glass me-2"></i>
                                {{ __('poems.liked.explore_poems') }}
                            </a>
                            <a href="{{ route('poems.bookmarks') }}" class="btn btn-outline-warning">
                                <i class="ph ph-bookmark me-2"></i>
                                {{ __('poems.liked.view_bookmarks') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Paginazione -->
    @if($likedPoems->hasPages())
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{ $likedPoems->links() }}
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
function unlikePoem(poemId) {
    if (confirm('{{ __("poems.liked.unlike_confirm") }}')) {
        fetch(`/poems/${poemId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Rimuovi la card dalla pagina
                const card = document.querySelector(`[data-poem-id="${poemId}"]`);
                if (card) {
                    card.remove();
                }
                // Ricarica la pagina per aggiornare le statistiche
                window.location.reload();
            } else {
                alert(data.message || '{{ __("poems.liked.unlike_error") }}');
            }
        });
    }
}

function toggleBookmark(poemId) {
    fetch(`/poems/${poemId}/bookmark`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Aggiorna l'icona del bookmark
            const bookmarkBtn = document.querySelector(`[data-poem-id="${poemId}"] .btn-outline-warning i`);
            if (bookmarkBtn) {
                bookmarkBtn.className = data.bookmarked ? 'ph ph-bookmark-fill' : 'ph ph-bookmark';
            }
        } else {
            alert(data.message || '{{ __("poems.bookmarks.toggle_error") }}');
        }
    });
}

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
