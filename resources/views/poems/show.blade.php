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

                            <!-- Selettore Lingue Disponibili -->
                            @if($poem->available_languages->count() > 1)
                            <div class="mb-3">
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <span class="text-muted small">
                                        <i class="ph ph-translate me-1"></i>{{ __('poems.available_languages') }}:
                                    </span>
                                    <div class="btn-group" role="group" id="language-selector">
                                        @foreach($poem->available_languages as $lang)
                                            <button type="button"
                                                    class="btn btn-sm {{ $lang['is_original'] ? 'btn-primary' : 'btn-outline-primary' }} language-btn"
                                                    data-language="{{ $lang['code'] }}"
                                                    data-original="{{ $lang['is_original'] ? 'true' : 'false' }}">
                                                @if($lang['is_original'])
                                                    <i class="ph ph-flag me-1"></i>
                                                @else
                                                    <i class="ph ph-translate me-1"></i>
                                                @endif
                                                {{ $lang['name'] }}
                                                @if(!$lang['is_original'] && $lang['is_official'])
                                                    <i class="ph ph-check-circle ms-1 text-success"></i>
                                                @endif
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="d-flex align-items-center text-muted small">
                                <i class="ph ph-user me-1"></i>
                                <a href="{{ route('user.show', $poem->user) }}" class="text-decoration-none hover-effect">{{ $poem->user->getDisplayName() }}</a>
                                <span class="mx-2">•</span>
                                <i class="ph ph-calendar me-1"></i>
                                {{ $poem->published_at ? $poem->published_at->format('d/m/Y') : $poem->created_at->format('d/m/Y') }}
                                <span class="mx-2">•</span>
                                <i class="ph ph-eye me-1"></i>
                                {{ number_format($poem->views_count) }} {{ __('poems.stats.views') }}
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
                                        <a class="dropdown-item" href="{{ route('poems.edit', $poem->slug) }}">
                                            <i class="ph ph-pencil me-2"></i>{{ __('common.edit') }}
                                        </a>
                                    </li>
                                @endif
                                @if($poem->canBeDeletedBy(auth()->user()))
                                    <li>
                                        <form action="{{ route('poems.destroy', $poem->slug) }}" method="POST" class="d-inline">
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
                @if($poem->thumbnail)
                <div class="card-img-top">
                    <img src="{{ $poem->thumbnail }}" class="img-fluid w-100" alt="{{ $poem->title }}">
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
                                <h5 class="text-primary mb-1">{{ number_format($poem->likes_count) }}</h5>
                                <small class="text-muted">{{ __('poems.stats.likes') }}</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-end">
                                <h5 class="text-info mb-1">{{ number_format($poem->comments_count) }}</h5>
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
                        <x-social-like-button :content="$poem" />
                        <x-social-comment-button :content="$poem" type="poem" />

                        <button class="btn btn-warning icon-btn" onclick="toggleBookmark()" id="bookmarkBtn" title="{{ __('poems.actions.bookmark') }}">
                            <i class="ph {{ $poem->is_bookmarked_by_current_user ? 'ph-bookmark-fill text-warning' : 'ph-bookmark' }}"></i>
                        </button>

                        <button class="btn btn-info icon-btn" onclick="sharePoem()" title="{{ __('common.share') }}">
                            <i class="ph ph-share"></i>
                        </button>


                        <x-report-button :content="$poem" type="poem" />
                    </div>
                    @else
                    <!-- Contatori social per utenti non autenticati -->
                    <div class="d-flex justify-content-center gap-4 mb-4">
                        <div class="text-center">
                            <div class="social-counter" style="display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 8px; border-radius: 8px;">
                                <i class="ph ph-heart f-s-24 text-muted" style="opacity: 0.6;"></i>
                                <span class="text-secondary f-s-12">{{ number_format($poem->likes_count) }}</span>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="social-counter" style="display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 8px; border-radius: 8px;">
                                <i class="ph ph-bookmark f-s-24 text-muted" style="opacity: 0.6;"></i>
                                <span class="text-secondary f-s-12">{{ number_format($poem->bookmark_count) }}</span>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="social-counter" style="display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 8px; border-radius: 8px;">
                                <i class="ph ph-share f-s-24 text-muted" style="opacity: 0.6;"></i>
                                <span class="text-secondary f-s-12">Condividi</span>
                            </div>
                        </div>
                    </div>

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

            <!-- Sezione commenti unificata -->
            <x-social-comments-section :content="$poem" />
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
                    <h6>
                        <a href="{{ route('user.show', $poem->user) }}" class="text-decoration-none hover-effect">
                            {{ $poem->user->getDisplayName() }}
                        </a>
                    </h6>
                    <p class="text-muted small mb-3">{{ $poem->user->bio ?? __('poems.no_bio') }}</p>

                    <div class="row text-center">
                        <div class="col-4">
                            <h6 class="text-primary">{{ $poem->user->poems()->published()->count() }}</h6>
                            <small class="text-muted">{{ __('poems.poems') }}</small>
                        </div>
                        <div class="col-4">
                            <h6 class="text-info">{{ $poem->user->poems()->published()->get()->sum('likes_count') }}</h6>
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
                            @if($relatedPoem->thumbnail)
                                <img src="{{ $relatedPoem->thumbnail }}" class="rounded me-3"
                                     width="60" height="60" alt="{{ $relatedPoem->title }}">
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="mb-1">
                                    @if($relatedPoem->slug)
                                        <a href="{{ route('poems.show', $relatedPoem->slug) }}" class="text-decoration-none">
                                            {{ $relatedPoem->title }}
                                        </a>
                                    @else
                                        <span class="text-muted">{{ $relatedPoem->title }}</span>
                                    @endif
                                </h6>
                                <small class="text-muted">
                                    <a href="{{ route('user.show', $relatedPoem->user) }}" class="text-decoration-none hover-effect">
                                        {{ $relatedPoem->user->getDisplayName() }}
                                    </a>
                                </small>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <x-social-like-button :content="$relatedPoem" type="poem" size="sm" />
                                    <x-social-view-counter :content="$relatedPoem" type="poem" size="sm" />
                                    <x-social-comment-button :content="$relatedPoem" type="poem" size="sm" />
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
                            </div>
                        </div>
                        <div class="col-6">
                            <h6 class="text-warning">{{ $poem->comments_count }}</h6>
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
// Funzioni rimosse - ora usiamo i componenti unificati

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


// Gestione cambio lingua dinamico
document.addEventListener('DOMContentLoaded', function() {
    const languageButtons = document.querySelectorAll('.language-btn');
    const poemTitle = document.querySelector('.card-title');
    const poemContent = document.querySelector('.poem-content');
    const poemDescription = document.querySelector('.poem-description');

    // Dati originali della poesia
    const originalData = {
        title: {!! json_encode($poem->title) !!},
        content: {!! json_encode($poem->content) !!},
        description: {!! json_encode($poem->description ?? '') !!}
    };

    languageButtons.forEach(button => {
        button.addEventListener('click', function() {
            const language = this.dataset.language;
            const isOriginal = this.dataset.original === 'true';

            // Aggiorna lo stato dei pulsanti
            languageButtons.forEach(btn => {
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-outline-primary');
            });
            this.classList.remove('btn-outline-primary');
            this.classList.add('btn-primary');

            if (isOriginal) {
                // Mostra il contenuto originale
                poemTitle.textContent = originalData.title;
                poemContent.innerHTML = originalData.content;
                if (poemDescription) {
                    poemDescription.textContent = originalData.description;
                }
            } else {
                // Carica il contenuto tradotto
                const url = `/poems/{{ $poem->slug }}/translations/${language}`;
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        poemTitle.textContent = data.title;
                        poemContent.innerHTML = data.content;
                        if (poemDescription) {
                            poemDescription.textContent = data.description || '';
                        }
                    })
                    .catch(error => {
                        console.error('Errore nel caricamento della traduzione:', error);
                        // Fallback al contenuto originale
                        poemTitle.textContent = originalData.title;
                        poemContent.innerHTML = originalData.content;
                        if (poemDescription) {
                            poemDescription.textContent = originalData.description;
                        }
                    });
            }
        });
    });
});
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
