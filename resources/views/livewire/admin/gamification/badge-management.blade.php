<div>
    <div class="container-fluid">
        {{-- Navigation Tabs --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.gamification.badges') }}" class="btn btn-primary">
                        <i class="ph ph-trophy me-2"></i>Badge
                    </a>
                    <a href="{{ route('admin.gamification.user-badges') }}" class="btn btn-light-primary">
                        <i class="ph ph-users-three me-2"></i>Badge Utenti
                    </a>
                    <a href="{{ route('admin.gamification.levels') }}" class="btn btn-light-primary">
                        <i class="ph ph-chart-line me-2"></i>Livelli
                    </a>
                    <a href="{{ route('admin.gamification.leaderboards') }}" class="btn btn-light-primary">
                        <i class="ph ph-ranking me-2"></i>Classifiche
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="ph-duotone ph-trophy me-2"></i>
                            {{ __('gamification.badges_management') }}
                        </h4>
                        <button wire:click="create" class="btn btn-primary">
                            <i class="ph ph-plus me-2"></i>
                            {{ __('gamification.create_badge') }}
                        </button>
                    </div>

                    <div class="card-body">
                        @if($badges && $badges->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 60px;">Icona</th>
                                            <th>Nome</th>
                                            <th>Tipo</th>
                                            <th>Categoria</th>
                                            <th>Criterio</th>
                                            <th>Punti</th>
                                            <th>Stato</th>
                                            <th style="width: 200px;">Azioni</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($badges as $badge)
                                            <tr>
                                                <td>
                                                    <img src="{{ $badge->icon_url }}" alt="{{ $badge->name }}" 
                                                         style="width: 32px; height: 32px;">
                                                </td>
                                                <td>
                                                    <strong>{{ $badge->name }}</strong>
                                                    @if($badge->description)
                                                        <br><small class="text-muted">{{ Str::limit($badge->description, 50) }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge {{ $badge->type === 'portal' ? 'bg-light-primary' : 'bg-light-info' }}">
                                                        {{ __('gamification.type_' . $badge->type) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light-secondary">
                                                        {{ __('gamification.category_' . $badge->category) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-muted">{{ $badge->criteria_value }}x</span>
                                                    {{ __('gamification.criteria_' . $badge->criteria_type) }}
                                                </td>
                                                <td>
                                                    <span class="badge bg-gradient-warning">
                                                        <i class="ph ph-star-four me-1"></i>{{ $badge->points }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input type="checkbox" class="form-check-input" 
                                                               wire:click="toggleActive({{ $badge->id }})"
                                                               {{ $badge->is_active ? 'checked' : '' }}>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <button wire:click="edit({{ $badge->id }})" 
                                                                class="btn btn-sm btn-light-primary">
                                                            <i class="ph ph-pencil"></i>
                                                        </button>
                                                        <button wire:click="openAssignModal({{ $badge->id }})" 
                                                                class="btn btn-sm btn-light-info"
                                                                title="{{ __('gamification.assign_badge') }}">
                                                            <i class="ph ph-user-plus"></i>
                                                        </button>
                                                        <button wire:click="delete({{ $badge->id }})" 
                                                                class="btn btn-sm btn-light-danger"
                                                                onclick="return confirm('Sei sicuro di voler eliminare questo badge?')">
                                                            <i class="ph ph-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="ph-duotone ph-trophy f-s-60 text-muted mb-3"></i>
                                <h5 class="text-muted">{{ __('gamification.no_badges') }}</h5>
                                <p class="text-muted">Crea il tuo primo badge per iniziare!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Create/Edit Modal --}}
    @if($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $isEditing ? __('gamification.edit_badge') : __('gamification.create_badge') }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('gamification.badge_type') }} *</label>
                                    <select wire:model="type" class="form-select" required>
                                        <option value="portal">{{ __('gamification.type_portal') }}</option>
                                        <option value="event">{{ __('gamification.type_event') }}</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('gamification.badge_category') }} *</label>
                                    <select wire:model="category" class="form-select" required>
                                        <option value="videos">{{ __('gamification.category_videos') }}</option>
                                        <option value="articles">{{ __('gamification.category_articles') }}</option>
                                        <option value="poems">{{ __('gamification.category_poems') }}</option>
                                        <option value="photos">{{ __('gamification.category_photos') }}</option>
                                        <option value="likes">{{ __('gamification.category_likes') }}</option>
                                        <option value="comments">{{ __('gamification.category_comments') }}</option>
                                        <option value="posts">{{ __('gamification.category_posts') }}</option>
                                        <option value="event_participation">{{ __('gamification.category_event_participation') }}</option>
                                        <option value="event_wins">{{ __('gamification.category_event_wins') }}</option>
                                    </select>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">{{ __('gamification.badge_name') }} *</label>
                                    <input type="text" wire:model="name" class="form-control" required>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">{{ __('gamification.badge_description') }}</label>
                                    <textarea wire:model="description" class="form-control" rows="2"></textarea>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">{{ __('gamification.badge_criteria_type') }} *</label>
                                    <select wire:model="criteria_type" class="form-select" required>
                                        <option value="count">{{ __('gamification.criteria_count') }}</option>
                                        <option value="milestone">{{ __('gamification.criteria_milestone') }}</option>
                                        <option value="first_time">{{ __('gamification.criteria_first_time') }}</option>
                                        <option value="special">{{ __('gamification.criteria_special') }}</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">{{ __('gamification.badge_criteria_value') }} *</label>
                                    <input type="number" wire:model="criteria_value" class="form-control" min="1" required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">{{ __('gamification.badge_points') }} *</label>
                                    <input type="number" wire:model="points" class="form-control" min="0" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('gamification.badge_order') }}</label>
                                    <input type="number" wire:model="order" class="form-control" min="0">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('gamification.badge_icon') }}</label>
                                    <input type="file" wire:model="icon" class="form-control" accept="image/*">
                                    @if($existing_icon)
                                        <div class="mt-2">
                                            <img src="{{ asset($existing_icon) }}" alt="Current icon" style="width: 32px; height: 32px;">
                                            <small class="text-muted ms-2">Icona attuale</small>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-12 mb-3">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" wire:model="is_active" class="form-check-input" id="is_active">
                                        <label class="form-check-label" for="is_active">
                                            {{ __('gamification.badge_active') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="$set('showModal', false)">
                            {{ __('gamification.cancel') }}
                        </button>
                        <button type="button" class="btn btn-primary" wire:click="save">
                            <i class="ph ph-check me-2"></i>
                            {{ __('gamification.save_badge') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Assign Badge Modal --}}
    @if($showAssignModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('gamification.assign_to_user') }}</h5>
                        <button type="button" class="btn-close" wire:click="$set('showAssignModal', false)"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Cerca Utente *</label>
                            
                            @if($selectedUser)
                                {{-- Selected User Display --}}
                                <div class="card border">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $selectedUser['avatar'] }}" 
                                                     alt="{{ $selectedUser['display_name'] }}" 
                                                     class="rounded-circle me-3" 
                                                     style="width: 48px; height: 48px; object-fit: cover;">
                                                <div>
                                                    <h6 class="mb-0">{{ $selectedUser['display_name'] }}</h6>
                                                    <small class="text-muted">{{ $selectedUser['email'] }}</small>
                                                </div>
                                            </div>
                                            <button type="button" wire:click="clearSelectedUser" class="btn btn-sm btn-light-danger">
                                                <i class="ph ph-x"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @else
                                {{-- Search Input --}}
                                <div class="position-relative">
                                    <input type="text" 
                                           wire:model.live.debounce.300ms="userSearch" 
                                           class="form-control" 
                                           placeholder="Cerca per nome, nickname o email..."
                                           autocomplete="off">
                                    <div class="position-absolute top-50 end-0 translate-middle-y me-3">
                                        @if(strlen($userSearch) > 0)
                                            <button type="button" wire:click="$set('userSearch', '')" class="btn btn-sm p-0">
                                                <i class="ph ph-x text-muted"></i>
                                            </button>
                                        @else
                                            <i class="ph ph-magnifying-glass text-muted"></i>
                                        @endif
                                    </div>
                                </div>
                                
                                @if(strlen($userSearch) >= 2)
                                    {{-- Search Results --}}
                                    @if(count($searchResults) > 0)
                                        <div class="list-group mt-2" style="max-height: 300px; overflow-y: auto;">
                                            @foreach($searchResults as $result)
                                                <button type="button" 
                                                        wire:click="selectUser({{ $result['id'] }})" 
                                                        class="list-group-item list-group-item-action">
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $result['avatar'] }}" 
                                                             alt="{{ $result['display_name'] }}" 
                                                             class="rounded-circle me-3" 
                                                             style="width: 40px; height: 40px; object-fit: cover;">
                                                        <div>
                                                            <h6 class="mb-0">{{ $result['display_name'] }}</h6>
                                                            <small class="text-muted">{{ $result['email'] }}</small>
                                                        </div>
                                                    </div>
                                                </button>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-light mt-2 mb-0">
                                            <i class="ph ph-magnifying-glass me-2"></i>
                                            Nessun utente trovato per "{{ $userSearch }}"
                                        </div>
                                    @endif
                                @elseif(strlen($userSearch) > 0 && strlen($userSearch) < 2)
                                    <small class="text-muted d-block mt-2">Digita almeno 2 caratteri per cercare...</small>
                                @endif
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Note</label>
                            <textarea wire:model="assignNotes" class="form-control" rows="3" 
                                      placeholder="Note sull'assegnazione manuale (opzionale)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="$set('showAssignModal', false)">
                            {{ __('gamification.cancel') }}
                        </button>
                        <button type="button" 
                                class="btn btn-primary" 
                                wire:click="assignBadgeToUser"
                                @if(!$selectedUser) disabled @endif>
                            <i class="ph ph-check me-2"></i>
                            {{ __('gamification.assign_badge') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Toast Scripts --}}
    @script
    <script>
        Livewire.on('swal:success', (data) => {
            Swal.fire({
                icon: 'success',
                title: data[0].title || 'Successo!',
                text: data[0].text || '',
                confirmButtonText: 'OK',
                confirmButtonClass: 'btn btn-primary',
                timer: 3000
            });
        });

        Livewire.on('swal:warning', (data) => {
            Swal.fire({
                icon: 'warning',
                title: data[0].title || 'Attenzione',
                text: data[0].text || '',
                confirmButtonText: 'OK',
                confirmButtonClass: 'btn btn-warning'
            });
        });
    </script>
    @endscript
</div>
