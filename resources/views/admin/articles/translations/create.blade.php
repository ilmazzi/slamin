@extends('layout.master')

@section('title', 'Crea Traduzione Articolo')

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="ph-duotone ph-translate f-s-24 text-primary me-2"></i>
                            Crea Traduzione per: {{ $article->title }}
                        </h4>
                        <a href="{{ route('admin.articles.translations.index') }}" class="btn btn-outline-secondary">
                            <i class="ph ph-arrow-left"></i> Torna alla Lista
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($availableLanguages) > 0)
                        <form action="{{ route('admin.articles.translations.store', $article) }}" method="POST" id="translationForm">
                            @csrf

                            <div class="row">
                                <div class="col-lg-8">
                                    <!-- Lingua -->
                                    <div class="mb-3">
                                        <label for="language" class="form-label">Lingua di Destinazione *</label>
                                        <select class="form-select @error('language') is-invalid @enderror" id="language" name="language" required>
                                            <option value="">Seleziona una lingua</option>
                                            @if(isset($translationLanguages) && is_array($translationLanguages) && count($translationLanguages) > 0)
                                                @foreach($translationLanguages as $lang)
                                                    <option value="{{ $lang }}" {{ old('language') == $lang ? 'selected' : '' }}>
                                                        @switch($lang)
                                                            @case('it') 🇮🇹 Italiano @break
                                                            @case('en') 🇬🇧 English @break
                                                            @case('fr') 🇫🇷 Français @break
                                                            @case('es') 🇪🇸 Español @break
                                                            @case('de') 🇩🇪 Deutsch @break
                                                            @case('pt') 🇵🇹 Português @break
                                                            @case('ru') 🇷🇺 Русский @break
                                                            @default {{ $lang }} @break
                                                        @endswitch
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="" disabled>ERRORE: Nessuna lingua disponibile</option>
                                                <option value="it">🇮🇹 Italiano (DEBUG)</option>
                                                <option value="en">🇬🇧 English (DEBUG)</option>
                                                <option value="fr">🇫🇷 Français (DEBUG)</option>
                                                <option value="es">🇪🇸 Español (DEBUG)</option>
                                                <option value="de">🇩🇪 Deutsch (DEBUG)</option>
                                                <option value="pt">🇵🇹 Português (DEBUG)</option>
                                                <option value="ru">🇷🇺 Русский (DEBUG)</option>
                                            @endif
                                        </select>
                                        @error('language')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            <strong>Nota:</strong> Puoi creare nuove traduzioni o sovrascrivere quelle esistenti.
                                            <br><strong>DEBUG:</strong>
                                            @if(isset($translationLanguages))
                                                Array count: {{ count($translationLanguages) }} |
                                                Content: {{ json_encode($translationLanguages) }} |
                                                Original lang: {{ $article->original_language ?? 'it' }}
                                            @else
                                                ERRORE: $translationLanguages non definito!
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Titolo -->
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Titolo Tradotto *</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                               id="title" name="title" value="{{ old('title') }}" required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Titolo originale: <em>{{ $article->title }}</em></div>
                                    </div>

                                    <!-- Excerpt -->
                                    <div class="mb-3">
                                        <label for="excerpt" class="form-label">Riassunto Tradotto</label>
                                        <textarea class="form-control @error('excerpt') is-invalid @enderror"
                                                  id="excerpt" name="excerpt" rows="3">{{ old('excerpt') }}</textarea>
                                        @error('excerpt')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @if($article->excerpt)
                                            <div class="form-text">Riassunto originale: <em>{{ $article->excerpt }}</em></div>
                                        @endif
                                    </div>

                                    <!-- Contenuto -->
                                    <div class="mb-3">
                                        <label for="content" class="form-label">Contenuto Tradotto *</label>
                                        <textarea class="form-control @error('content') is-invalid @enderror"
                                                  id="content" name="content" rows="15" required>{{ old('content') }}</textarea>
                                        @error('content')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <!-- Articolo Originale -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Articolo Originale</h6>
                                        </div>
                                        <div class="card-body">
                                            <h6>{{ $article->title }}</h6>
                                            @if($article->excerpt)
                                                <p class="text-muted small">{{ Str::limit($article->excerpt, 100) }}</p>
                                            @endif
                                            <p class="small text-muted mb-2">
                                                <strong>Lingua:</strong>
                                                @switch($article->original_language ?? 'it')
                                                    @case('it') 🇮🇹 Italiano @break
                                                    @case('en') 🇬🇧 English @break
                                                    @case('fr') 🇫🇷 Français @break
                                                    @case('es') 🇪🇸 Español @break
                                                    @case('de') 🇩🇪 Deutsch @break
                                                    @case('pt') 🇵🇹 Português @break
                                                    @case('ru') 🇷🇺 Русский @break
                                                @endswitch
                                            </p>
                                            <p class="small text-muted mb-2">
                                                <strong>Autore:</strong> {{ $article->user->name }}
                                            </p>
                                            <p class="small text-muted mb-0">
                                                <strong>Data:</strong> {{ $article->created_at->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Opzioni -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Opzioni Traduzione</h6>
                                        </div>
                                        <div class="card-body">
                                            <!-- Tipo Traduzione -->
                                            <div class="mb-3">
                                                <label for="translation_type" class="form-label">Tipo</label>
                                                <select class="form-select @error('translation_type') is-invalid @enderror"
                                                        id="translation_type" name="translation_type">
                                                    <option value="manual" selected>Manuale</option>
                                                </select>
                                                @error('translation_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <!-- Stato -->
                                            <div class="mb-3">
                                                <label for="status" class="form-label">Stato</label>
                                                <select class="form-select @error('status') is-invalid @enderror"
                                                        id="status" name="status">
                                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Bozza</option>
                                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Pubblicato</option>
                                                </select>
                                                @error('status')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                        </div>
                                    </div>

                                    <!-- Traduzioni Esistenti -->
                                    @if($article->translations->count() > 0)
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">Traduzioni Esistenti</h6>
                                            </div>
                                            <div class="card-body">
                                                @foreach($article->translations as $translation)
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span>
                                                            @switch($translation->language)
                                                                @case('it') 🇮🇹 @break
                                                                @case('en') 🇬🇧 @break
                                                                @case('fr') 🇫🇷 @break
                                                                @case('es') 🇪🇸 @break
                                                                @case('de') 🇩🇪 @break
                                                                @case('pt') 🇵🇹 @break
                                                                @case('ru') 🇷🇺 @break
                                                            @endswitch
                                                            {{ strtoupper($translation->language) }}
                                                        </span>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="badge bg-{{ $translation->status == 'published' ? 'success' : 'warning' }}">
                                                                {{ $translation->status }}
                                                            </span>
                                                            <small class="text-muted">(sovrascrivibile)</small>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                <div class="alert alert-info mt-2 mb-0">
                                                    <small><i class="ph ph-info me-1"></i> Le traduzioni esistenti possono essere sovrascritte creando una nuova traduzione per la stessa lingua.</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
                                    <i class="ph ph-arrow-left"></i> Annulla
                                </button>
                                <div>
                                    <button type="button" class="btn btn-outline-primary me-2" onclick="previewTranslation()">
                                        <i class="ph ph-eye"></i> Anteprima
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ph ph-check"></i> Salva Traduzione
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-5">
                            <i class="ph-duotone ph-translate f-s-48 text-muted mb-3"></i>
                            <h5 class="text-muted">Nessuna lingua disponibile per la traduzione</h5>
                            <p class="text-muted">
                                Questo articolo ha già traduzioni per tutte le lingue supportate o la lingua originale è l'unica disponibile.
                            </p>
                            <div class="alert alert-info mt-3">
                                <strong>Debug Info:</strong><br>
                                Lingua originale: <strong>{{ $article->original_language ?? 'it' }}</strong><br>
                                Traduzioni esistenti: <strong>{{ $article->translations->pluck('language')->implode(', ') ?: 'Nessuna' }}</strong><br>
                                Lingue supportate: <strong>it, en, fr, es, de, pt, ru</strong>
                            </div>
                            <a href="{{ route('admin.articles.translations.index') }}" class="btn btn-primary">
                                <i class="ph ph-arrow-left"></i> Torna alla Lista
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewTranslation() {
    // Implement preview functionality
    Swal.fire({
        title: 'Anteprima',
        text: 'Funzionalità di anteprima in sviluppo',
        icon: 'info',
        confirmButtonText: 'OK'
    });
}
</script>
@endsection
