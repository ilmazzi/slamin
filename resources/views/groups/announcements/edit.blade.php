@extends('layout.master')

@section('title', 'Modifica annuncio - ' . $group->name)

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="page-title">
                            <i class="ti ti-edit me-2"></i>
                            Modifica annuncio
                        </h1>
                        <p class="page-description">
                            Modifica l'annuncio "{{ $announcement->title }}" per {{ $group->name }}
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('groups.announcements.show', [$group, $announcement]) }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i>Torna all'annuncio
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ti ti-edit me-2"></i>
                                Modifica annuncio
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('groups.announcements.update', [$group, $announcement]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                
                                <!-- Titolo -->
                                <div class="mb-3">
                                    <label for="title" class="form-label">Titolo *</label>
                                    <input type="text" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title', $announcement->title) }}" 
                                           required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Contenuto -->
                                <div class="mb-3">
                                    <label for="content" class="form-label">Contenuto *</label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" 
                                              id="content" 
                                              name="content" 
                                              rows="6" 
                                              required>{{ old('content', $announcement->content) }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Visibilità -->
                                <div class="mb-3">
                                    <label for="visibility" class="form-label">Visibilità *</label>
                                    <select class="form-select @error('visibility') is-invalid @enderror" 
                                            id="visibility" 
                                            name="visibility" 
                                            required>
                                        <option value="members_only" {{ old('visibility', $announcement->visibility) === 'members_only' ? 'selected' : '' }}>
                                            Solo membri del gruppo
                                        </option>
                                        <option value="public" {{ old('visibility', $announcement->visibility) === 'public' ? 'selected' : '' }}>
                                            Pubblico (visibile a tutti)
                                        </option>
                                        @if($group->hasModerator(auth()->user()))
                                            <option value="admins_only" {{ old('visibility', $announcement->visibility) === 'admins_only' ? 'selected' : '' }}>
                                                Solo amministratori
                                            </option>
                                        @endif
                                    </select>
                                    @error('visibility')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Pinning (solo per moderatori) -->
                                @if($group->hasModerator(auth()->user()))
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="is_pinned" 
                                               name="is_pinned" 
                                               value="1" 
                                               {{ old('is_pinned', $announcement->is_pinned) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_pinned">
                                            <i class="ti ti-pin me-1"></i>
                                            Fissa questo annuncio in cima alla bacheca
                                        </label>
                                    </div>
                                </div>
                                @endif

                                <!-- Scadenza -->
                                <div class="mb-3">
                                    <label for="expires_at" class="form-label">Scadenza (opzionale)</label>
                                    <input type="datetime-local" 
                                           class="form-control @error('expires_at') is-invalid @enderror" 
                                           id="expires_at" 
                                           name="expires_at" 
                                           value="{{ old('expires_at', $announcement->expires_at?->format('Y-m-d\TH:i')) }}">
                                    @error('expires_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">
                                        L'annuncio sarà automaticamente nascosto dopo questa data
                                    </div>
                                </div>

                                <!-- Pulsanti -->
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-check me-1"></i>Aggiorna annuncio
                                    </button>
                                    <a href="{{ route('groups.announcements.show', [$group, $announcement]) }}" class="btn btn-secondary">
                                        Annulla
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="ti ti-info-circle me-2"></i>
                                Informazioni
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6>Visibilità</h6>
                                <ul class="list-unstyled small text-muted">
                                    <li><i class="ti ti-users me-1"></i><strong>Solo membri:</strong> Visibile solo ai membri del gruppo</li>
                                    <li><i class="ti ti-world me-1"></i><strong>Pubblico:</strong> Visibile a tutti gli utenti</li>
                                    @if($group->hasModerator(auth()->user()))
                                        <li><i class="ti ti-shield me-1"></i><strong>Solo admin:</strong> Visibile solo agli amministratori</li>
                                    @endif
                                </ul>
                            </div>
                            
                            <div class="mb-3">
                                <h6>Dettagli annuncio</h6>
                                <ul class="list-unstyled small text-muted">
                                    <li><i class="ti ti-user me-1"></i><strong>Autore:</strong> {{ $announcement->author->name }}</li>
                                    <li><i class="ti ti-calendar me-1"></i><strong>Creato:</strong> {{ $announcement->created_at->format('d/m/Y H:i') }}</li>
                                    <li><i class="ti ti-clock me-1"></i><strong>Ultima modifica:</strong> {{ $announcement->updated_at->format('d/m/Y H:i') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
