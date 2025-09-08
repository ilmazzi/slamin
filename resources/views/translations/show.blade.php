@extends('layout.master')

@section('main-content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-light-header">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h1 class="card-title mb-0">
                            <i class="fas fa-language text-primary me-2"></i>
                            {{ $gig->title }}
                        </h1>
                        <p class="card-text text-muted">
                            <i class="fas fa-user me-1"></i>{{ $gig->user->name }} •
                            <i class="fas fa-calendar me-1"></i>{{ $gig->created_at->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-primary fs-6">
                            <i class="fas fa-language me-1"></i>Gig di Traduzione
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Contenuto Principale -->
        <div class="col-lg-8">
            <!-- Poesia Originale -->
            <div class="card hover-effect mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-book text-primary me-2"></i>
                        Poesia da Tradurre
                    </h5>
                </div>
                <div class="card-body">
                    <h4 class="text-primary mb-3">{{ $gig->poem->title }}</h4>
                    <div class="poem-content mb-3">
                        {!! nl2br(e($gig->poem->content)) !!}
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-user me-1"></i>{{ $gig->poem->user->name }}
                        </span>
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-calendar me-1"></i>{{ $gig->poem->created_at->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Dettagli Traduzione -->
            <div class="card hover-effect mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Dettagli Traduzione
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-2">
                                <i class="fas fa-globe me-1"></i>Lingue Richieste
                            </h6>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @foreach($gig->target_languages as $lang)
                                <span class="badge bg-primary">{{ strtoupper($lang) }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary mb-2">
                                <i class="fas fa-euro-sign me-1"></i>Compenso
                            </h6>
                            <h4 class="text-success mb-0">{{ number_format($gig->compensation, 2) }} €</h4>
                        </div>
                    </div>

                    @if($gig->translation_instructions)
                    <div class="mt-3">
                        <h6 class="text-primary mb-2">
                            <i class="fas fa-clipboard-list me-1"></i>Istruzioni
                        </h6>
                        <p class="mb-0">{{ $gig->translation_instructions }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Candidature -->
            @if($gig->applications->count() > 0)
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users text-primary me-2"></i>
                        Candidature ({{ $gig->applications->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($gig->applications as $application)
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $application->user->name }}</h6>
                                <p class="text-muted small mb-2">{{ $application->created_at->format('d/m/Y H:i') }}</p>
                                @if($application->message)
                                <p class="mb-2">{{ $application->message }}</p>
                                @endif
                                @if($application->compensation_expectation)
                                <p class="mb-0">
                                    <strong>Compenso richiesto:</strong> {{ number_format($application->compensation_expectation, 2) }} €
                                </p>
                                @endif
                            </div>
                            <div class="text-end">
                                <span class="badge
                                    @if($application->status === 'accepted') bg-success
                                    @elseif($application->status === 'rejected') bg-danger
                                    @else bg-warning
                                    @endif">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Info Gig -->
            <div class="card hover-effect mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Informazioni
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h6 class="text-success mb-0">
                                <i class="fas fa-euro-sign me-1"></i>{{ number_format($gig->compensation, 2) }}
                            </h6>
                            <small class="text-muted">Compenso</small>
                        </div>
                        <div class="col-6">
                            <h6 class="text-warning mb-0">
                                <i class="fas fa-clock me-1"></i>{{ $gig->deadline->format('d/m') }}
                            </h6>
                            <small class="text-muted">Scadenza</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <h6 class="text-info mb-0">
                                <i class="fas fa-users me-1"></i>{{ $gig->applications->count() }}
                            </h6>
                            <small class="text-muted">Candidature</small>
                        </div>
                        <div class="col-6">
                            <h6 class="text-primary mb-0">
                                <i class="fas fa-globe me-1"></i>{{ count($gig->target_languages) }}
                            </h6>
                            <small class="text-muted">Lingue</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Azioni -->
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cogs text-primary me-2"></i>
                        Azioni
                    </h5>
                </div>
                <div class="card-body">
                    @if($gig->user_id === auth()->id())
                        <!-- Autore del gig -->
                        <p class="text-muted mb-3">Sei l'autore di questo gig di traduzione.</p>
                        <a href="{{ route('gigs.edit', $gig) }}" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-edit me-1"></i>Modifica Gig
                        </a>
                    @else
                        <!-- Altri utenti -->
                        @php
                            $userApplication = $gig->applications->where('user_id', auth()->id())->first();
                        @endphp

                        @if($userApplication)
                            @if($userApplication->status === 'accepted')
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-1"></i>
                                    La tua candidatura è stata accettata!
                                </div>
                                <a href="{{ route('translations.negotiation', $userApplication) }}" class="btn btn-success w-100 mb-2">
                                    <i class="fas fa-comments me-1"></i>Vai alla Negoziazione
                                </a>
                            @elseif($userApplication->status === 'rejected')
                                <div class="alert alert-danger">
                                    <i class="fas fa-times-circle me-1"></i>
                                    La tua candidatura è stata rifiutata.
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-clock me-1"></i>
                                    La tua candidatura è in attesa di risposta.
                                </div>
                                <a href="{{ route('translations.negotiation', $userApplication) }}" class="btn btn-primary w-100 mb-2">
                                    <i class="fas fa-comments me-1"></i>Vai alla Negoziazione
                                </a>
                            @endif
                        @else
                            <!-- Non candidato -->
                            <a href="{{ route('gigs.apply', $gig) }}" class="btn btn-primary w-100 mb-2">
                                <i class="fas fa-paper-plane me-1"></i>Candidati
                            </a>
                        @endif
                    @endif

                    <a href="{{ route('translations.index') }}" class="btn btn-light w-100">
                        <i class="fas fa-arrow-left me-1"></i>Torna alla Lista
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
