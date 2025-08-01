@extends('layout.master')

@section('title', $poem->title)

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <!-- Titolo su mobile, breadcrumb su desktop -->
                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                    
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0 small">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="text-decoration-none">
                                    <i class="ph ph-house me-1"></i>{{ __('common.home') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('poems.index') }}" class="text-decoration-none">
                                    <i class="ph ph-book-open me-1"></i>{{ __('poems.title') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active text-truncate" style="max-width: 200px;">
                                {{ $poem->title }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Contenuto principale -->
        <div class="col-lg-8">
            <div class="card">
                <!-- Header della poesia -->
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h2 class="card-title mb-2">{{ $poem->title }}</h2>
                            <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                                <span class="badge bg-light-primary">{{ __('poems.categories.' . $poem->category) }}</span>
                                <span class="badge bg-light-primary">{{ __('poems.poem_types.' . $poem->poem_type) }}</span>
                                <span class="badge bg-light-primary">{{ __('poems.languages.' . $poem->language) }}</span>
                                @if($poem->is_featured)
                                    <span class="badge bg-light-warning">
                                        <i class="ph ph-star me-1"></i>{{ __('poems.status.featured') }}
                                    </span>
                                @endif
                            </div>
                            <div class="d-flex align-items-center text-muted small">
                                <i class="ph ph-user me-1"></i>
                                <a href="#" class="text-decoration-none">{{ $poem->user->name }}</a>
                                <span class="mx-2">•</span>
                                <i class="ph ph-calendar me-1"></i>
                                {{ $poem->published_at ? $poem->published_at->format('d/m/Y') : $poem->created_at->format('d/m/Y') }}
                                <span class="mx-2">•</span>
                                <i class="ph ph-eye me-1"></i>
                                {{ number_format($poem->view_count) }} {{ __('poems.stats.views') }}
                            </div>
                        </div>

                                                @auth
                        <div class="dropdown">
                            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="dropdown">
                                <i class="ph ph-dots-three-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('poems.create') }}">
                                        <i class="ph ph-plus me-2"></i>{{ __('poems.create.title') }}
                                    </a>
                                </li>
                                @if($poem->canBeEditedBy(auth()->user()))
                                    <li>
                                        <a class="dropdown-item" href="{{ route('poems.edit', $poem) }}">
                                            <i class="ph ph-pencil me-2"></i>{{ __('common.edit') }}
                                        </a>
                                    </li>
                                @endif
                                @if($poem->canBeDeletedBy(auth()->user()))
                                    <li>
                                        <form action="{{ route('poems.destroy', $poem) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger"
                                                    onclick="return confirm('{{ __('poems.delete_confirm') }}')">
                                                <i class="ph ph-trash me-2"></i>{{ __('common.delete') }}
                                            </button>
                                        </form>
                                    </li>
                                @endif
                                <li>
                                    <a class="dropdown-item" href="#" onclick="sharePoem()">
                                        <i class="ph ph-share me-2"></i>{{ __('common.share') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                        @endauth
                    </div>
                </div>

                <!-- {{ __('common.thumbnail') }} -->
                @if($poem->thumbnail_path)
                <div class="card-img-top">
                    <img src="{{ $poem->thumbnail_url }}" class="img-fluid w-100" alt="{{ $poem->title }}">
                </div>
                @endif

                <!-- Contenuto della poesia -->
                <div class="card-body">
                    @if($poem->description)
                        <div class="mb-4">
                            <p class="text-muted">{{ $poem->description }}</p>
                        </div>
                    @endif

                    <div class="poem-content mb-4">
                        {!! nl2br(e($poem->content)) !!}
                    </div>

                    <!-- Tags -->
                    @if($poem->tags && count($poem->tags) > 0)
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">{{ __('poems.tags') }}:</h6>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($poem->tags as $tag)
                                    <span class="badge bg-light text-dark">{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Statistiche -->
                    <div class="row text-center mb-4">
                        <div class="col-4">
                            <div class="border-end">
                                <h5 class="text-primary mb-1">{{ number_format($poem->like_count) }}</h5>
                                <small class="text-muted">{{ __('poems.stats.likes') }}</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-end">
                                <h5 class="text-info mb-1">{{ number_format($poem->comment_count) }}</h5>
                                <small class="text-muted">{{ __('poems.stats.comments') }}</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <h5 class="text-success mb-1">{{ number_format($poem->bookmark_count) }}</h5>
                            <small class="text-muted">{{ __('poems.stats.bookmarks') }}</small>
                        </div>
                    </div>

                    <!-- {{ __('invitations.actions') }} social -->
                    @auth
                    <div class="d-flex justify-content-center gap-2 mb-4">
                        <button class="btn btn-primary icon-btn" onclick="toggleLike()" id="likeBtn" title="{{ __('poems.actions.like') }}">
                            <i class="ph {{ $poem->is_liked_by_current_user ? 'ph-heart-fill text-danger' : 'ph-heart' }}"></i>
                        </button>

                        <button class="btn btn-warning icon-btn" onclick="toggleBookmark()" id="bookmarkBtn" title="{{ __('poems.actions.bookmark') }}">
                            <i class="ph {{ $poem->is_bookmarked_by_current_user ? 'ph-bookmark-fill text-warning' : 'ph-bookmark' }}"></i>
                        </button>

                        <button class="btn btn-info icon-btn" onclick="sharePoem()" title="{{ __('common.share') }}">
                            <i class="ph ph-share"></i>
                        </button>

                        @if($poem->translation_available)
                            <button class="btn btn-outline-success" onclick="requestTranslation()">
                                <i class="ph ph-translate me-2"></i>
                                {{ __('poems.actions.request_translation') }}
                            </button>
                        @endif

                        <x-report-button :content="$poem" type="poem" />
                    </div>
                    @else
                    <div class="text-center mb-4">
                        <p class="text-muted">{{ __('poems.login_to_interact') }}</p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                <i class="ph ph-sign-in me-2"></i>
                                {{ __('auth.login') }}
                            </a>
                            <a href="{{ route('poems.create') }}" class="btn btn-outline-primary">
                                <i class="ph ph-plus me-2"></i>
                                {{ __('poems.create.title') }}
                            </a>
                        </div>
                    </div>
                    @endauth
                </div>
            </div>

            <!-- {{ __('common.comments_section') }} -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph ph-chats-circle text-primary me-2"></i>
                        {{ __('poems.stats.comments') }} ({{ $poem->comments->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @auth
                    <!-- Form per nuovo commento -->
                    <form action="{{ route('poems.comments.store', $poem) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="mb-3">
                            <textarea class="form-control" name="content" rows="3"
                                      placeholder="{{ __('poems.tooltips.comment_placeholder') }}" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="ph ph-paper-plane me-2"></i>
                            {{ __('poems.tooltips.post_comment') }}
                        </button>
                    </form>
                    @endif

                    <!-- Lista commenti -->
                    <div id="commentsList">
                        @forelse($poem->comments as $comment)
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0">
                                    <img src="{{ $comment->user->avatar_url ?? asset('assets/images/avatar/default.png') }}"
                                         class="rounded-circle" width="40" height="40" alt="{{ $comment->user->name }}">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $comment->user->name }}</h6>
                                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                        </div>
                                        @if($comment->canBeEditedBy(auth()->user()))
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                                    <i class="ph ph-dots-three-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="#" onclick="editComment({{ $comment->id }})">
                                                            <i class="ph ph-pencil me-2"></i>{{ __('common.edit') }}
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('poems.comments.destroy', $comment) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="ph ph-trash me-2"></i>{{ __('common.delete') }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                    <p class="mb-1">{{ $comment->content }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="ph ph-chats-circle display-4"></i>
                                <p class="mt-2">{{ __('poems.no_comments') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Informazioni autore -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph ph-user text-primary me-2"></i>
                        {{ __('poems.about_author') }}
                    </h5>
                </div>
                <div class="card-body text-center">
                   <img src="{{ $poem->user->getProfilePhotoUrlAttribute() }}"
                             class="rounded-circle mb-3" width="80" height="80" alt="{{ $poem->user->name }}">
                    <h6>{{ $poem->user->name }}</h6>
                    <p class="text-muted small mb-3">{{ $poem->user->bio ?? __('poems.no_bio') }}</p>

                    <div class="row text-center">
                        <div class="col-4">
                            <h6 class="text-primary">{{ $poem->user->poems()->published()->count() }}</h6>
                            <small class="text-muted">{{ __('poems.poems') }}</small>
                        </div>
                        <div class="col-4">
                            <h6 class="text-info">{{ $poem->user->poems()->published()->sum('like_count') }}</h6>
                            <small class="text-muted">{{ __('poems.total_likes') }}</small>
                        </div>
                        <div class="col-4">
                            <h6 class="text-success">{{ $poem->user->created_at->diffForHumans() }}</h6>
                            <small class="text-muted">{{ __('poems.member_since') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Poesie correlate -->
            @if($relatedPoems->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph ph-link text-primary me-2"></i>
                        {{ __('poems.related_poems') }}
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($relatedPoems as $relatedPoem)
                        <div class="d-flex mb-3">
                            @if($relatedPoem->thumbnail_path)
                                <img src="{{ $relatedPoem->thumbnail_url }}" class="rounded me-3"
                                     width="60" height="60" alt="{{ $relatedPoem->title }}">
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="mb-1">
                                    <a href="{{ route('poems.show', $relatedPoem) }}" class="text-decoration-none">
                                        {{ $relatedPoem->title }}
                                    </a>
                                </h6>
                                <small class="text-muted">{{ $relatedPoem->user->name }}</small>
                                <div class="d-flex align-items-center mt-1">
                                    <small class="text-muted me-3">
                                        <i class="ph ph-heart me-1"></i>{{ $relatedPoem->like_count }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="ph ph-eye me-1"></i>{{ $relatedPoem->view_count }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Statistiche dettagliate -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph ph-chart-line text-primary me-2"></i>
                        {{ __('poems.statistics') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border-end">
                                <h6 class="text-primary">{{ $poem->word_count }}</h6>
                                <small class="text-muted">{{ __('poems.words') }}</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <h6 class="text-info">{{ $poem->share_count }}</h6>
                            <small class="text-muted">{{ __('poems.shares') }}</small>
                        </div>
                        <div class="col-6">
                            <div class="border-end">
                                <h6 class="text-success">{{ $poem->translation_request_count }}</h6>
                                <small class="text-muted">{{ __('poems.translation_requests') }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6 class="text-warning">{{ $poem->comments->count() }}</h6>
                            <small class="text-muted">{{ __('poems.comments') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Toggle like
function toggleLike() {
    fetch('{{ route("poems.like", $poem) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const likeBtn = document.getElementById('likeBtn');
            const icon = likeBtn.querySelector('i');

            if (data.liked) {
                icon.className = 'ph ph-heart-fill text-danger me-2';
            } else {
                icon.className = 'ph ph-heart me-2';
            }

            // Aggiorna il contatore
            location.reload();
        }
    });
}

// Toggle bookmark
function toggleBookmark() {
    fetch('{{ route("poems.bookmark", $poem) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const bookmarkBtn = document.getElementById('bookmarkBtn');
            const icon = bookmarkBtn.querySelector('i');

            if (data.bookmarked) {
                icon.className = 'ph ph-bookmark-fill text-warning me-2';
            } else {
                icon.className = 'ph ph-bookmark me-2';
            }

            // Aggiorna il contatore
            location.reload();
        }
    });
}

// Share poem
function sharePoem() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $poem->title }}',
            text: '{{ $poem->description ?? $poem->title }}',
            url: window.location.href,
        });
    } else {
        // Fallback: copia l'URL negli appunti
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('{{ __("poems.url_copied") }}');
        });
    }
}

// Request translation
function requestTranslation() {
    if (confirm('{{ __("poems.translation_confirm") }}')) {
        fetch('{{ route("poems.request-translation", $poem) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('{{ __("poems.translation_requested") }}');
            } else {
                alert(data.message || '{{ __("poems.translation_error") }}');
            }
        });
    }
}
</script>
@endpush

@push('styles')
<style>
.poem-content {
    font-size: 1.1rem;
    line-height: 1.8;
    white-space: pre-line;
}
</style>
@endpush
@endsection
