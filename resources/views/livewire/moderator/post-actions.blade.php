<div>
    @script
    <script>
        Livewire.on('swal:success', (data) => {
            Swal.fire({
                icon: 'success',
                title: data[0].title || 'Successo!',
                text: data[0].text || '',
                confirmButtonText: 'OK',
                confirmButtonClass: 'btn btn-primary'
            });
        });
    </script>
    @endscript

    {{-- Moderator Actions Panel --}}
    @if(auth()->user()->hasRole('admin') || $post->subreddit->isModerator(auth()->user()))
        <div class="card border-warning mb-3">
            <div class="card-header bg-light-warning">
                <h6 class="mb-0 text-warning">
                    <i class="ph ph-shield-check me-2"></i>Azioni Moderatore
                </h6>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    {{-- Sticky --}}
                    <button wire:click="toggleSticky" 
                            class="btn btn-sm {{ $post->is_sticky ? 'btn-success' : 'btn-light-success' }}">
                        <i class="ph ph-push-pin me-1"></i>
                        {{ $post->is_sticky ? 'Rimuovi Pin' : 'Fissa in Alto' }}
                    </button>

                    {{-- Lock --}}
                    <button wire:click="toggleLock" 
                            class="btn btn-sm {{ $post->is_locked ? 'btn-danger' : 'btn-light-danger' }}">
                        <i class="ph ph-lock me-1"></i>
                        {{ $post->is_locked ? 'Sblocca' : 'Blocca Commenti' }}
                    </button>

                    {{-- Archive --}}
                    <button wire:click="toggleArchive" 
                            class="btn btn-sm {{ $post->is_archived ? 'btn-warning' : 'btn-light-warning' }}">
                        <i class="ph ph-archive me-1"></i>
                        {{ $post->is_archived ? 'Ripristina' : 'Archivia' }}
                    </button>

                    {{-- Ban User --}}
                    <button wire:click="openBanModal({{ $post->user_id }})" 
                            class="btn btn-sm btn-light-danger">
                        <i class="ph ph-prohibit me-1"></i>Ban Utente
                    </button>

                    {{-- Delete Post --}}
                    <button wire:click="deletePost" 
                            wire:confirm="Eliminare definitivamente questo post?"
                            class="btn btn-sm btn-danger">
                        <i class="ph ph-trash me-1"></i>Elimina Post
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Ban Modal --}}
    @if($showBanModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-light-danger">
                        <h5 class="modal-title text-danger">
                            <i class="ph ph-prohibit me-2"></i>Ban Utente
                        </h5>
                        <button type="button" class="btn-close" wire:click="showBanModal = false"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Durata Ban <span class="text-danger">*</span></label>
                            <select wire:model="banDuration" class="form-select">
                                <option value="1day">1 Giorno</option>
                                <option value="7days">7 Giorni</option>
                                <option value="30days">30 Giorni</option>
                                <option value="permanent">Permanente</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Motivo <span class="text-danger">*</span></label>
                            <textarea wire:model="banReason" 
                                      class="form-control @error('banReason') is-invalid @enderror" 
                                      rows="4" 
                                      placeholder="Spiega il motivo del ban..."></textarea>
                            @error('banReason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="alert alert-border-warning" role="alert">
                            <i class="ph ph-warning me-2"></i>
                            L'utente non potrà più postare o commentare in questo subreddit
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button wire:click="showBanModal = false" class="btn btn-light-secondary">
                            Annulla
                        </button>
                        <button wire:click="banUser" class="btn btn-danger">
                            <i class="ph ph-prohibit me-2"></i>Conferma Ban
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
