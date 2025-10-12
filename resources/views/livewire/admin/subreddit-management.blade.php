<div>
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">
                                <i class="ph ph-folders text-primary me-2"></i>Gestione Subreddit
                            </h4>
                            <p class="text-muted mb-0">Crea, modifica e gestisci i subreddit del forum</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.forum.dashboard') }}" class="btn btn-light-secondary">
                                <i class="ph ph-arrow-left me-2"></i>Dashboard
                            </a>
                            <button wire:click="create" class="btn btn-primary">
                                <i class="ph ph-plus-circle me-2"></i>Nuovo Subreddit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Subreddits Table --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Subreddit</th>
                            <th>Descrizione</th>
                            <th>Stats</th>
                            <th>Stato</th>
                            <th>Creato</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subreddits as $subreddit)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box rounded me-2" style="background-color: {{ $subreddit->color }}22;">
                                            <i class="ph ph-chats-circle" style="color: {{ $subreddit->color }};"></i>
                                        </div>
                                        <div>
                                            <a href="{{ route('forum.subreddit.show', $subreddit->slug) }}" 
                                               target="_blank" class="text-dark fw-bold">
                                                r/{{ $subreddit->name }}
                                            </a>
                                            <div class="small text-muted">{{ $subreddit->slug }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <small>{{ Str::limit($subreddit->description, 60) }}</small>
                                </td>
                                <td>
                                    <div class="small">
                                        <div><i class="ph ph-article"></i> {{ number_format($subreddit->posts_count) }} post</div>
                                        <div><i class="ph ph-users"></i> {{ number_format($subreddit->subscribers_count) }} iscritti</div>
                                    </div>
                                </td>
                                <td>
                                    @if($subreddit->is_active)
                                        <span class="badge bg-light-success text-success">
                                            <i class="ph ph-check"></i> Attivo
                                        </span>
                                    @else
                                        <span class="badge bg-light-danger text-danger">
                                            <i class="ph ph-x"></i> Disattivato
                                        </span>
                                    @endif
                                    @if($subreddit->is_private)
                                        <span class="badge bg-light-warning text-warning">
                                            <i class="ph ph-lock"></i> Privato
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $subreddit->created_at->format('d/m/Y') }}</small>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button wire:click="edit({{ $subreddit->id }})" 
                                                class="btn btn-sm btn-light-primary">
                                            <i class="ph ph-pencil"></i>
                                        </button>
                                        <button wire:click="manageModerators({{ $subreddit->id }})" 
                                                class="btn btn-sm btn-light-info">
                                            <i class="ph ph-user-gear"></i>
                                        </button>
                                        <button wire:click="toggleActive({{ $subreddit->id }})" 
                                                class="btn btn-sm btn-light-{{ $subreddit->is_active ? 'danger' : 'success' }}">
                                            <i class="ph ph-{{ $subreddit->is_active ? 'x' : 'check' }}"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="ph ph-folders f-s-48 mb-2"></i>
                                    <p>Nessun subreddit trovato</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($subreddits->hasPages())
            <div class="card-footer">
                {{ $subreddits->links() }}
            </div>
        @endif
    </div>

    {{-- Create/Edit Modal --}}
    @if($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="ph ph-{{ $editMode ? 'pencil' : 'plus-circle' }} me-2"></i>
                            {{ $editMode ? 'Modifica Subreddit' : 'Nuovo Subreddit' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save">
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="form-label">Nome <span class="text-danger">*</span></label>
                                    <input type="text" wire:model.live="name" 
                                           class="form-control @error('name') is-invalid @enderror">
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Colore</label>
                                    <input type="color" wire:model="color" 
                                           class="form-control form-control-color w-100">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Slug (URL) <span class="text-danger">*</span></label>
                                <input type="text" wire:model="slug" 
                                       class="form-control @error('slug') is-invalid @enderror"
                                       {{ $editMode ? 'readonly' : '' }}>
                                @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <small class="text-muted">URL: /forum/r/{{ $slug ?: 'slug' }}</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Descrizione <span class="text-danger">*</span></label>
                                <textarea wire:model="description" rows="3" 
                                          class="form-control @error('description') is-invalid @enderror"></textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Regole</label>
                                <textarea wire:model="rules" rows="5" class="form-control"></textarea>
                                <small class="text-muted">Una regola per riga</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" wire:model="is_active" 
                                               class="form-check-input" id="is_active">
                                        <label class="form-check-label" for="is_active">Subreddit Attivo</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" wire:model="is_private" 
                                               class="form-check-input" id="is_private">
                                        <label class="form-check-label" for="is_private">Subreddit Privato</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="closeModal" class="btn btn-light-secondary">Annulla</button>
                        <button type="button" wire:click="save" class="btn btn-primary">
                            <i class="ph ph-check me-2"></i>{{ $editMode ? 'Aggiorna' : 'Crea' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Moderators Modal --}}
    @if($showModeratorsModal && $currentSubreddit)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="ph ph-user-gear me-2"></i>
                            Moderatori - r/{{ $currentSubreddit->name }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModeratorsModal"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Search User --}}
                        <div class="mb-4">
                            <label class="form-label">Aggiungi Moderatore</label>
                            <input type="text" wire:model.live.debounce.300ms="searchUser" 
                                   class="form-control" 
                                   placeholder="Cerca utente per nome o email...">
                            
                            @if(count($searchResults) > 0)
                                <div class="list-group mt-2">
                                    @foreach($searchResults as $user)
                                        <button wire:click="addModerator({{ $user['id'] }})" 
                                                class="list-group-item list-group-item-action">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $user['name'] }}</strong>
                                                    <div class="small text-muted">{{ $user['email'] }}</div>
                                                </div>
                                                <i class="ph ph-plus text-success"></i>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Current Moderators --}}
                        <div>
                            <h6 class="mb-3">Moderatori Attuali ({{ count($moderators) }})</h6>
                            @forelse($moderators as $moderator)
                                <div class="card mb-2">
                                    <div class="card-body py-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $moderator['user']['name'] }}</strong>
                                                <div class="small text-muted">
                                                    <span class="badge bg-light-{{ $moderator['role'] === 'admin' ? 'danger' : 'primary' }}">
                                                        {{ $moderator['role'] === 'admin' ? 'Admin' : 'Moderatore' }}
                                                    </span>
                                                </div>
                                            </div>
                                            <button wire:click="removeModerator({{ $moderator['id'] }})" 
                                                    wire:confirm="Rimuovere questo moderatore?"
                                                    class="btn btn-sm btn-light-danger">
                                                <i class="ph ph-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-muted py-4">
                                    <i class="ph ph-users f-s-36 mb-2"></i>
                                    <p>Nessun moderatore assegnato</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="closeModeratorsModal" class="btn btn-primary">
                            Chiudi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
