@extends('layout.master')

@section('title', 'Nuovo annuncio - ' . $group->name)

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="page-title">
                            <i class="ti ti-plus me-2"></i>
                            Nuovo annuncio
                        </h1>
                        <p class="page-description">
                            Crea un nuovo annuncio per {{ $group->name }}
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('groups.announcements.index', $group) }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i>Torna alla bacheca
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
                                Dettagli annuncio
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('groups.announcements.store', $group) }}" method="POST">
                                @csrf
                                
                                <!-- Titolo -->
                                <div class="mb-3">
                                    <label for="title" class="form-label">Titolo *</label>
                                    <input type="text" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title') }}" 
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
                                              required>{{ old('content') }}</textarea>
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
                                        <option value="members_only" {{ old('visibility', 'members_only') === 'members_only' ? 'selected' : '' }}>
                                            Solo membri del gruppo
                                        </option>
                                        <option value="public" {{ old('visibility') === 'public' ? 'selected' : '' }}>
                                            Pubblico (visibile a tutti)
                                        </option>
                                        @if($group->hasModerator(auth()->user()))
                                            <option value="admins_only" {{ old('visibility') === 'admins_only' ? 'selected' : '' }}>
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
                                               {{ old('is_pinned') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_pinned">
                                            <i class="ti ti-pin me-1"></i>
                                            Fissa questo annuncio in cima alla bacheca
                                        </label>
                                    </div>
                                </div>
                                @endif

                                <!-- Sondaggio -->
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="has_poll" 
                                               name="has_poll" 
                                               value="1" 
                                               {{ old('has_poll') ? 'checked' : '' }}
                                               onchange="togglePollOptions()">
                                        <label class="form-check-label" for="has_poll">
                                            <i class="ti ti-chart-bar me-1"></i>
                                            Aggiungi un sondaggio
                                        </label>
                                    </div>
                                </div>

                                <!-- Opzioni sondaggio -->
                                <div id="poll-options" class="mb-3" style="display: none;">
                                    <label class="form-label">Opzioni del sondaggio</label>
                                    <div id="poll-options-container">
                                        <div class="input-group mb-2">
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="poll_options[]" 
                                                   placeholder="Opzione 1">
                                        </div>
                                        <div class="input-group mb-2">
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="poll_options[]" 
                                                   placeholder="Opzione 2">
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-primary" onclick="addPollOption()">
                                        <i class="ti ti-plus me-1"></i>Aggiungi opzione
                                    </button>
                                </div>

                                <!-- Scadenza -->
                                <div class="mb-3">
                                    <label for="expires_at" class="form-label">Scadenza (opzionale)</label>
                                    <input type="datetime-local" 
                                           class="form-control @error('expires_at') is-invalid @enderror" 
                                           id="expires_at" 
                                           name="expires_at" 
                                           value="{{ old('expires_at') }}">
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
                                        <i class="ti ti-check me-1"></i>Crea annuncio
                                    </button>
                                    <a href="{{ route('groups.announcements.index', $group) }}" class="btn btn-secondary">
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
                                <h6>Sondaggi</h6>
                                <p class="small text-muted">
                                    I sondaggi permettono ai membri di votare su questioni importanti del gruppo.
                                    Ogni membro può votare una sola volta.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePollOptions() {
    const hasPoll = document.getElementById('has_poll').checked;
    const pollOptions = document.getElementById('poll-options');
    
    if (hasPoll) {
        pollOptions.style.display = 'block';
    } else {
        pollOptions.style.display = 'none';
    }
}

function addPollOption() {
    const container = document.getElementById('poll-options-container');
    const optionCount = container.children.length + 1;
    
    if (optionCount > 10) {
        alert('Massimo 10 opzioni consentite');
        return;
    }
    
    const newOption = document.createElement('div');
    newOption.className = 'input-group mb-2';
    newOption.innerHTML = `
        <input type="text" class="form-control" name="poll_options[]" placeholder="Opzione ${optionCount}">
        <button type="button" class="btn btn-outline-danger" onclick="removePollOption(this)">
            <i class="ti ti-x"></i>
        </button>
    `;
    
    container.appendChild(newOption);
}

function removePollOption(button) {
    const container = document.getElementById('poll-options-container');
    if (container.children.length > 2) {
        button.parentElement.remove();
    }
}

// Inizializza lo stato del sondaggio
document.addEventListener('DOMContentLoaded', function() {
    togglePollOptions();
});
</script>
@endsection
