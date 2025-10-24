<div>
    <div class="container-fluid">
        {{-- Event Info Header --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="card bg-gradient-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h4 class="text-white mb-1">{{ $event->title }}</h4>
                                <p class="mb-0 opacity-75">{{ __('events.scoring.score_entry') }}</p>
                            </div>
                            <a href="{{ route('events.scoring.dashboard', $event) }}" class="btn btn-light">
                                <i class="ph ph-arrow-left me-2"></i>{{ __('events.scoring.dashboard') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Lock Alert --}}
        @if($isLocked)
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-warning d-flex align-items-center">
                    <i class="ph ph-lock-simple f-s-24 me-3"></i>
                    <div>
                        <strong>{{ __('events.scoring.event_completed') }}</strong><br>
                        {{ __('events.scoring.the_rankings_have_been_generated') }}
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Navigation --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('events.scoring.dashboard', $event) }}" class="btn btn-light-primary">
                        <i class="ph ph-chart-pie me-2"></i>{{ __('events.scoring.dashboard') }}
                    </a>
                    <a href="{{ route('events.scoring.participants', $event) }}" class="btn btn-light-primary">
                        <i class="ph ph-users me-2"></i>{{ __('events.scoring.participants') }}
                    </a>
                    <a href="{{ route('events.scoring.scores', $event) }}" class="btn btn-primary">
                        <i class="ph ph-pencil-line me-2"></i>{{ __('events.scoring.scores') }}
                    </a>
                    <a href="{{ route('events.scoring.rankings', $event) }}" class="btn btn-light-primary">
                        <i class="ph ph-ranking me-2"></i>{{ __('events.scoring.rankings') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Round Management --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ph-duotone ph-timer me-2"></i>
                            {{ __('events.scoring.rounds') }} ({{ $rounds->count() }})
                        </h5>
                        <button wire:click="openRoundModal" class="btn btn-sm btn-primary">
                            <i class="ph ph-plus me-2"></i>{{ __('events.scoring.add_round') }}
                        </button>
                    </div>
                    <div class="card-body">
                        @if($rounds->count() > 0)
                            <div class="d-flex gap-2 flex-wrap mb-3">
                                @foreach($rounds as $round)
                                    <button wire:click="$set('selectedRound', {{ $round->round_number }})" 
                                            class="btn {{ $selectedRound == $round->round_number ? 'btn-primary' : 'btn-light-primary' }}">
                                        {{ $round->name }}
                                    </button>
                                @endforeach
                            </div>

                            <div class="d-flex gap-2">
                                @foreach($rounds as $round)
                                    @if($selectedRound == $round->round_number)
                                        <button wire:click="editRound({{ $round->id }})" class="btn btn-sm btn-light-warning">
                                            <i class="ph ph-pencil me-1"></i>{{ __('events.scoring.edit') }}
                                        </button>
                                        <button wire:click="deleteRound({{ $round->id }})" 
                                                class="btn btn-sm btn-light-danger"
                                                    onclick="return confirm('{{ __('events.scoring.confirm_delete_round') }}')">
                                            <i class="ph ph-trash me-1"></i>{{ __('events.scoring.delete') }}
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">{{ __('events.scoring.no_rounds_configured') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Score Entry --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph-duotone ph-pencil-line me-2"></i>
                            {{ __('events.scoring.scores') }} - {{ __('events.scoring.round') }} {{ $selectedRound }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($participants->count() > 0)
                            {{-- Mobile View --}}
                            <div class="d-lg-none">
                                <div class="row g-3">
                                    @foreach($participants as $participant)
                                        <div class="col-12">
                                            <div class="card border">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center gap-3 mb-3">
                                                        @if($participant->user)
                                                            <img src="{{ $participant->user->profile_photo_url ?? asset('assets/images/avatar/default-avatar.webp') }}" 
                                                                 alt="{{ $participant->display_name }}"
                                                                 class="rounded-circle"
                                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                                        @else
                                                            <div class="bg-light-secondary rounded-circle d-flex align-items-center justify-content-center"
                                                                 style="width: 50px; height: 50px;">
                                                                <i class="ph ph-user f-s-24 text-secondary"></i>
                                                            </div>
                                                        @endif
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-0">
                                                                @if($participant->performance_order)
                                                                    <span class="badge bg-light-primary me-2">#{{ $participant->performance_order }}</span>
                                                                @endif
                                                                {{ $participant->display_name }}
                                                            </h6>
                                                        </div>
                                                    </div>

                                                    <div class="mb-2">
                                                        <label class="form-label small">{{ __('events.scoring.score') }} (0.0 - 10.0)</label>
                                                        <div class="input-group">
                                                            <input type="number" 
                                                                   wire:model="scores.{{ $participant->id }}"
                                                                   class="form-control" 
                                                                   step="0.1" 
                                                                   min="0" 
                                                                   max="10"
                                                                   placeholder="{{ __('events.scoring.example') }}: 9.5">
                                                            <button wire:click="saveScore({{ $participant->id }})" 
                                                                    class="btn btn-primary">
                                                                <i class="ph ph-check"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Desktop Table --}}
                            <div class="table-responsive d-none d-lg-block">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 80px;">#</th>
                                            <th>{{ __('events.scoring.participant') }}</th>
                                            <th style="width: 200px;">{{ __('events.scoring.score') }} (0.0 - 10.0)</th>
                                            <th style="width: 100px;">{{ __('events.scoring.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($participants as $participant)
                                            <tr>
                                                <td>
                                                    @if($participant->performance_order)
                                                        <span class="badge bg-light-primary">#{{ $participant->performance_order }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($participant->user)
                                                            <img src="{{ $participant->user->profile_photo_url ?? asset('assets/images/avatar/default-avatar.webp') }}" 
                                                                 alt="{{ $participant->display_name }}"
                                                                 class="rounded-circle me-2"
                                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                                        @else
                                                            <div class="bg-light-secondary rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                                 style="width: 40px; height: 40px;">
                                                                <i class="ph ph-user text-secondary"></i>
                                                            </div>
                                                        @endif
                                                        <strong>{{ $participant->display_name }}</strong>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="number" 
                                                           wire:model="scores.{{ $participant->id }}"
                                                           class="form-control" 
                                                           step="0.1" 
                                                           min="0" 
                                                           max="10"
                                                           placeholder="Es: 9.5">
                                                </td>
                                                <td>
                                                    <button wire:click="saveScore({{ $participant->id }})" 
                                                            class="btn btn-primary">
                                                        <i class="ph ph-check me-1"></i>{{ __('events.scoring.save') }}
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="alert alert-light-info mt-3 mb-0">
                                <i class="ph ph-info me-2"></i>
                                <strong>{{ __('events.scoring.note') }}:</strong> {{ __('events.scoring.scores_are_saved_automatically') }}. {{ __('events.scoring.scale_0_0_10_0_with_one_decimal') }}.
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="ph-duotone ph-users f-s-60 text-muted mb-3"></i>
                                    <h5 class="text-muted">{{ __('events.scoring.no_participants') }}</h5>
                                <p class="text-muted">{{ __('events.scoring.add_participants_before_inserting_scores') }}</p>
                                <a href="{{ route('events.scoring.participants', $event) }}" class="btn btn-primary">
                                    <i class="ph ph-user-plus me-2"></i>{{ __('events.scoring.add_participants') }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Round Modal --}}
    @if($showRoundModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $editingRound ? __('events.scoring.edit_round') : __('events.scoring.add_round') }}</h5>
                        <button type="button" class="btn-close" wire:click="$set('showRoundModal', false)"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label">{{ __('events.scoring.round_number') }} *</label>
                                <input type="number" wire:model="round_number" class="form-control" min="1" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">{{ __('events.scoring.round_name') }} *</label>
                                <input type="text" wire:model="round_name" class="form-control" required>
                                <small class="text-muted">{{ __('events.scoring.example') }}: "{{ __('events.scoring.first_round') }}", "{{ __('events.scoring.semi_final') }}", "{{ __('events.scoring.final') }}"</small>
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ __('events.scoring.scoring_type') }} *</label>
                                <select wire:model="scoring_type" class="form-select">
                                    <option value="average">{{ __('events.scoring.average') }} ({{ __('events.scoring.recommended') }})</option>
                                    <option value="sum">{{ __('events.scoring.sum') }}</option>
                                    <option value="best_of">{{ __('events.scoring.best_of') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="$set('showRoundModal', false)">
                            {{ __('events.scoring.cancel') }}
                        </button>
                        <button type="button" class="btn btn-primary" wire:click="saveRound">
                            <i class="ph ph-check me-2"></i>{{ $editingRound ? __('events.scoring.update') : __('events.scoring.create') }} {{ __('events.scoring.round') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @script
    <script>
        Livewire.on('swal:success', (data) => {
            Swal.fire({
                icon: 'success',
                title: data[0].title || '{{ __('events.scoring.success') }}!',
                text: data[0].text || '',
                confirmButtonClass: 'btn btn-primary',
                timer: 2000,
                toast: true,
                position: 'top-end',
                showConfirmButton: false
            });
        });

        Livewire.on('swal:warning', (data) => {
            Swal.fire({
                icon: 'warning',
                title: data[0].title || '{{ __('events.scoring.warning') }}',
                text: data[0].text || '',
                confirmButtonClass: 'btn btn-warning'
            });
        });
    </script>
    @endscript
</div>
