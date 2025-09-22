@extends('layout.master')

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph-duotone ph-translate f-s-24 text-primary me-2"></i>
                        Gestione Traduzioni Articoli
                    </h5>
                    <div class="card-actions">
                        <a href="{{ route('admin.articles.index') }}" class="btn btn-light">
                            <i class="ph ph-arrow-left me-2"></i>Torna agli Articoli
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($articles->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Titolo</th>
                                        <th>Autore</th>
                                        <th>Lingua Originale</th>
                                        <th>Tipo</th>
                                        <th>Stato Traduzioni</th>
                                        <th>Data Creazione</th>
                                        <th>Azioni</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($articles as $article)
                                        @if($article && $article->exists)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-3">
                                                        @if($article->featured_image)
                                                            <img src="{{ Storage::url($article->featured_image) }}" alt="{{ $article->title }}" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                                        @else
                                                            <div class="avatar-title bg-primary text-white rounded">
                                                                <i class="ph ph-newspaper"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ is_array($article->title) ? ($article->title['it'] ?? $article->title['en'] ?? 'N/A') : $article->title }}</h6>
                                                        <small class="text-muted">{{ Str::limit($article->excerpt ? (is_array($article->excerpt) ? ($article->excerpt['it'] ?? $article->excerpt['en'] ?? '') : $article->excerpt) : '', 100) }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-xs me-2">
                                                        <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($article->user) }}" alt="{{ $article->user->getDisplayName() }}" class="rounded-circle" style="width: 24px; height: 24px; object-fit: cover;">
                                                    </div>
                                                    <span>{{ $article->user->getDisplayName() }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ strtoupper($article->original_language ?? 'it') }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-1">
                                                    @if($article->is_news)
                                                        <span class="badge bg-primary">📰 News</span>
                                                    @endif
                                                    @if($article->featured)
                                                        <span class="badge bg-warning">⭐ Featured</span>
                                                    @endif
                                                    @if(!$article->is_news && !$article->featured)
                                                        <span class="badge bg-secondary">📄 Articolo</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach(['it', 'en', 'fr', 'es', 'de', 'pt', 'ru'] as $lang)
                                                        @php
                                                            // Inizializza translation_status se non esiste
                                                            if (!$article->translation_status) {
                                                                $article->translation_status = [];
                                                            }
                                                            $status = $article->translation_status[$lang] ?? 'pending';

                                                            // Controlla se c'è una traduzione per questa lingua
                                                            $hasTranslation = \App\Models\ArticleTranslation::where('article_id', $article->id)
                                                                ->where('language', $lang)
                                                                ->exists();

                                                            // Se lo status è 'completed' o c'è una traduzione, dovrebbe essere verde
                                                            if ($status === 'completed' || $hasTranslation) {
                                                                $status = 'completed';
                                                            }
                                                        @endphp
                                                        <span class="badge {{ $status === 'completed' ? 'bg-success' : ($hasTranslation ? 'bg-warning' : 'bg-secondary') }}" title="{{ strtoupper($lang) }}: {{ ucfirst($status) }} {{ $hasTranslation ? '(Traduzione esistente)' : '' }}">
                                                            {{ strtoupper($lang) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $article->created_at->format('d/m/Y H:i') }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if($article->needs_translation)
                                                        <a href="{{ route('admin.articles.translations.create', $article) }}" class="btn btn-sm btn-primary">
                                                            <i class="ph ph-plus me-1"></i>Traduci
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-warning" onclick="unmarkFromTranslation({{ $article->id }})" data-article-id="{{ $article->id }}">
                                                            <i class="ph ph-x me-1"></i>Rimuovi
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-info" onclick="markForTranslation({{ $article->id }})" data-article-id="{{ $article->id }}">
                                                            <i class="ph ph-check me-1"></i>Marca per Traduzione
                                                        </button>
                                                    @endif
                                                    <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-light" target="_blank">
                                                        <i class="ph ph-eye me-1"></i>Vedi
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $articles->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ph-duotone ph-translate f-s-48 text-muted mb-3"></i>
                            <h5 class="text-muted">Nessun articolo disponibile</h5>
                            <p class="text-muted">Non ci sono articoli pubblicati da tradurre.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>

function markForTranslation(articleId) {
    // Get article ID from data attribute if not provided
    if (!articleId && event.target) {
        articleId = event.target.getAttribute('data-article-id');
    }

    if (!articleId) {
        Swal.fire({
            title: 'Errore!',
            text: 'ID articolo non valido',
            icon: 'error',
            confirmButtonText: 'OK'
        });
        return;
    }

    Swal.fire({
        title: 'Conferma',
        text: 'Marcare questo articolo per traduzione in tutte le lingue?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sì, marca!',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/articles/${articleId}/mark-for-translation`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Successo!',
                        text: 'Articolo marcato per traduzione!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Errore!',
                        text: data.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Errore!',
                    text: 'Errore nel marcare l\'articolo',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
}

function unmarkFromTranslation(articleId) {
    // Get article ID from data attribute if not provided
    if (!articleId && event.target) {
        articleId = event.target.getAttribute('data-article-id');
    }

    if (!articleId) {
        Swal.fire({
            title: 'Errore!',
            text: 'ID articolo non valido',
            icon: 'error',
            confirmButtonText: 'OK'
        });
        return;
    }

    Swal.fire({
        title: 'Conferma',
        text: 'Rimuovere questo articolo dalla lista traduzioni?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sì, rimuovi!',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/articles/${articleId}/unmark-from-translation`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Successo!',
                        text: 'Articolo rimosso dalla lista traduzioni!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Errore!',
                        text: data.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Errore!',
                    text: 'Errore nel rimuovere l\'articolo',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
}
</script>
@endsection
