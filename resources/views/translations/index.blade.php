@extends('layout.master')

@section('main-content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-light-header">
                <h1 class="card-title mb-0">
                    <i class="fas fa-language text-primary me-2"></i>
                    Gigs di Traduzione
                </h1>
                <p class="card-text text-muted">Trova poesie da tradurre e inizia a collaborare con gli autori</p>
            </div>
        </div>
    </div>

    <!-- Filtri -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-light">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="language" class="form-label">Lingua</label>
                            <select name="language" id="language" class="form-select">
                                <option value="">Tutte le lingue</option>
                                <option value="en" {{ request('language') == 'en' ? 'selected' : '' }}>Inglese</option>
                                <option value="es" {{ request('language') == 'es' ? 'selected' : '' }}>Spagnolo</option>
                                <option value="fr" {{ request('language') == 'fr' ? 'selected' : '' }}>Francese</option>
                                <option value="de" {{ request('language') == 'de' ? 'selected' : '' }}>Tedesco</option>
                                <option value="it" {{ request('language') == 'it' ? 'selected' : '' }}>Italiano</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="sort" class="form-label">Ordina per</label>
                            <select name="sort" id="sort" class="form-select">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Più recenti</option>
                                <option value="deadline" {{ request('sort') == 'deadline' ? 'selected' : '' }}>Scadenza</option>
                                <option value="compensation" {{ request('sort') == 'compensation' ? 'selected' : '' }}>Compenso</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search me-1"></i>Filtra
                            </button>
                            <a href="{{ route('translations.index') }}" class="btn btn-light">
                                <i class="fas fa-times me-1"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista Gigs -->
    <div class="row">
        @forelse($translationGigs as $gig)
        <div class="col-lg-6 col-xl-4 mb-4">
            <div class="card hover-effect">
                <div class="card-body">
                    <!-- Header Card -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1">
                                <a href="{{ route('translations.show', $gig) }}" class="text-decoration-none">
                                    {{ $gig->title }}
                                </a>
                            </h5>
                            <p class="text-muted small mb-0">
                                <i class="fas fa-user me-1"></i>{{ $gig->user->name }}
                            </p>
                        </div>
                        <span class="badge bg-primary">
                            <i class="fas fa-language me-1"></i>Traduzione
                        </span>
                    </div>

                    <!-- Poesia -->
                    <div class="mb-3">
                        <h6 class="text-primary mb-1">
                            <i class="fas fa-book me-1"></i>Poesia
                        </h6>
                        <p class="mb-0 fw-medium">{{ $gig->poem->title }}</p>
                        <p class="text-muted small mb-0">{{ Str::limit($gig->poem->content, 100) }}</p>
                    </div>

                    <!-- Lingue Richieste -->
                    <div class="mb-3">
                        <h6 class="text-primary mb-1">
                            <i class="fas fa-globe me-1"></i>Lingue Richieste
                        </h6>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($gig->target_languages as $lang)
                            <span class="badge bg-light text-dark">{{ strtoupper($lang) }}</span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Compenso e Scadenza -->
                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="text-center">
                                <h6 class="text-success mb-0">
                                    <i class="fas fa-euro-sign me-1"></i>{{ number_format($gig->compensation, 2) }}
                                </h6>
                                <small class="text-muted">Compenso</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h6 class="text-warning mb-0">
                                    <i class="fas fa-clock me-1"></i>{{ $gig->deadline->format('d/m') }}
                                </h6>
                                <small class="text-muted">Scadenza</small>
                            </div>
                        </div>
                    </div>

                    <!-- Candidature -->
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="fas fa-users me-1"></i>
                            {{ $gig->applications->count() }} candidature
                        </small>
                    </div>

                    <!-- Azioni -->
                    <div class="d-grid">
                        <a href="{{ route('translations.show', $gig) }}" class="btn btn-primary">
                            <i class="fas fa-eye me-1"></i>Visualizza Dettagli
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card-light text-center py-5">
                <div class="card-body">
                    <i class="fas fa-language text-muted mb-3" style="font-size: 3rem;"></i>
                    <h4 class="text-muted">Nessun gig di traduzione disponibile</h4>
                    <p class="text-muted">Non ci sono attualmente gigs di traduzione che corrispondono ai tuoi filtri.</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Paginazione -->
    @if($translationGigs->hasPages())
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $translationGigs->links() }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
