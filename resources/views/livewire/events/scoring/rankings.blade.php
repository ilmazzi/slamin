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
                                <p class="mb-0 opacity-75">{{ __('events.scoring.final_rankings') }}</p>
                            </div>
                            <a href="{{ route('events.scoring.dashboard', $event) }}" class="btn btn-light">
                                <i class="ph ph-arrow-left me-2"></i>{{ __('events.scoring.dashboard') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                    <a href="{{ route('events.scoring.scores', $event) }}" class="btn btn-light-primary">
                        <i class="ph ph-pencil-line me-2"></i>{{ __('events.scoring.scores') }}
                    </a>
                    <a href="{{ route('events.scoring.rankings', $event) }}" class="btn btn-primary">
                        <i class="ph ph-ranking me-2"></i>{{ __('events.scoring.rankings') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        @if($canCalculate || $rankings->count() > 0)
        <div class="row mb-3">
            <div class="col-12">
                @if($event->status === 'completed')
                    {{-- Event Completed --}}
                    <div class="card border-success">
                        <div class="card-body text-center py-4">
                            <i class="ph-duotone ph-check-circle f-s-60 text-success mb-3"></i>
                            <h4 class="text-success mb-2">🎊 {{ __('events.scoring.event_completed') }}</h4>
                            <p class="text-muted mb-0">
                                {{ __('events.scoring.final_rankings_published') }}
                            </p>
                        </div>
                    </div>
                @else
                    {{-- Active Event Actions --}}
                    <div class="card">
                        <div class="card-body">
                            {{-- Stats Info --}}
                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <div class="p-3 bg-light-info rounded text-center">
                                        <i class="ph-duotone ph-users f-s-30 text-info mb-2"></i>
                                        <h5 class="mb-0">{{ $stats['with_scores'] }}/{{ $stats['total_participants'] }}</h5>
                                        <small class="text-muted">{{ __('events.scoring.with_scores') }}</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light-success rounded text-center">
                                        <i class="ph-duotone ph-trophy f-s-30 text-success mb-2"></i>
                                        <h5 class="mb-0">{{ $stats['badges_awarded'] }}</h5>
                                        <small class="text-muted">{{ __('events.scoring.badges_awarded') }}</small>
                                    </div>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    @if($canCalculate)
                                        <div class="card bg-light-warning h-100">
                                            <div class="card-body text-center">
                                                <i class="ph-duotone ph-calculator f-s-40 text-warning mb-2"></i>
                                                <h6 class="mb-2">{{ __('events.scoring.calculate_partial_rankings') }}</h6>
                                                <p class="text-muted small mb-3">{{ __('events.scoring.update_rankings_without_closing_event') }}</p>
                                                <button wire:click="calculatePartialRankings" class="btn btn-warning w-100">
                                                    <i class="ph ph-calculator me-2"></i>{{ __('events.scoring.calculate_partial_rankings') }}
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-12 col-md-6">
                                    @if($canCalculate && $stats['with_scores'] > 0)
                                        <div class="card bg-light-success h-100">
                                            <div class="card-body text-center">
                                                <i class="ph-duotone ph-trophy f-s-40 text-success mb-2"></i>
                                                <h6 class="mb-2">{{ __('events.scoring.finalize_event') }}</h6>
                                                <p class="text-muted small mb-3">{{ __('events.scoring.finalize_event') }}</p>
                                                <button onclick="confirmFinalize()" class="btn btn-success w-100">
                                                    <i class="ph ph-check-circle me-2"></i>{{ __('events.scoring.terminate_event') }}
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if(!$canCalculate || $stats['with_scores'] === 0)
                                <div class="alert alert-light-info mt-3 mb-0">
                                    <i class="ph ph-info me-2"></i>
                                    {{ __('events.scoring.insert_scores_before_generating_rankings') }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Rankings --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph-duotone ph-ranking me-2"></i>
                                {{ __('events.scoring.final_rankings') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($rankings->count() > 0)
                            {{-- Mobile View --}}
                            <div class="d-lg-none">
                                <div class="row g-3">
                                    @foreach($rankings as $ranking)
                                        <div class="col-12">
                                            <div class="card {{ $ranking->position <= 3 ? 'border-warning' : 'border' }}">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center gap-3">
                                                        {{-- Position Badge --}}
                                                        <div class="flex-shrink-0">
                                                            <div class="badge {{ $ranking->position <= 3 ? 'bg-gradient-warning' : 'bg-light-secondary' }} rounded-circle"
                                                                 style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                                                                {{ $ranking->medal ?: $ranking->position }}
                                                            </div>
                                                        </div>

                                                        {{-- Participant Info --}}
                                                        @if($ranking->participant)
                                                        <div class="flex-grow-1">
                                                            <div class="d-flex align-items-center mb-2">
                                                                @if($ranking->participant->user)
                                                                    <img src="{{ $ranking->participant->user->profile_photo_url ?? asset('assets/images/avatar/default-avatar.webp') }}" 
                                                                         alt="{{ $ranking->participant->display_name }}"
                                                                         class="rounded-circle me-2"
                                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                                @endif
                                                                <div>
                                                                    <h6 class="mb-0">{{ $ranking->participant->display_name }}</h6>
                                                                    @if($ranking->participant->isGuest())
                                                                        <span class="badge bg-light-warning">{{ __('events.scoring.guest') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <div class="d-flex gap-2 flex-wrap">
                                                                <span class="badge bg-gradient-warning">
                                                                    <i class="ph ph-star-four me-1"></i>{{ number_format($ranking->total_score, 1) }} {{ __('events.scoring.points') }}
                                                                </span>
                                                                
                                                                @if($ranking->badge)
                                                                    <span class="badge {{ $ranking->badge_awarded ? 'bg-light-success' : 'bg-light-secondary' }}">
                                                                        <i class="ph ph-trophy me-1"></i>{{ $ranking->badge->name }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @endif
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
                                            <th style="width: 80px;">Pos.</th>
                                            <th>{{ __('events.scoring.participant_name') }}</th>
                                            <th>{{ __('events.scoring.round_scores') }}</th>
                                            <th style="width: 150px;">{{ __('events.scoring.total_score') }}</th>
                                            <th>{{ __('events.scoring.badge') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rankings as $ranking)
                                            <tr class="{{ $ranking->position <= 3 ? 'table-warning' : '' }}">
                                                <td>
                                                    <div class="badge {{ $ranking->position <= 3 ? 'bg-gradient-warning' : 'bg-light-secondary' }} rounded-circle"
                                                         style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                                                        {{ $ranking->medal ?: $ranking->position }}
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($ranking->participant)
                                                        <div class="d-flex align-items-center">
                                                            @if($ranking->participant->user)
                                                                <img src="{{ $ranking->participant->user->profile_photo_url ?? asset('assets/images/avatar/default-avatar.webp') }}" 
                                                                     alt="{{ $ranking->participant->display_name }}"
                                                                     class="rounded-circle me-2"
                                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                                            @else
                                                                <div class="bg-light-secondary rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                                     style="width: 40px; height: 40px;">
                                                                    <i class="ph ph-user text-secondary"></i>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <strong>{{ $ranking->participant->display_name }}</strong>
                                                                @if($ranking->participant->isGuest())
                                                                    <br><span class="badge bg-light-warning">{{ __('events.scoring.guest') }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($ranking->round_scores)
                                                        <div class="d-flex gap-2 flex-wrap">
                                                            @foreach($ranking->round_scores as $round => $score)
                                                                <span class="badge bg-light-info">
                                                                    T{{ $round }}: {{ number_format($score, 1) }} {{ __('events.scoring.points') }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <h5 class="mb-0">
                                                        <span class="badge bg-gradient-warning">
                                                            {{ number_format($ranking->total_score, 1) }}
                                                        </span>
                                                    </h5>
                                                </td>
                                                <td>
                                                    @if($ranking->badge)
                                                        <div class="d-flex align-items-center gap-2">
                                                            <img src="{{ $ranking->badge->icon_url }}" 
                                                                 alt="{{ $ranking->badge->name }}"
                                                                 style="width: 32px; height: 32px;">
                                                            <div>
                                                                <strong class="d-block">{{ $ranking->badge->name }}</strong>
                                                                @if($ranking->badge_awarded)
                                                                    <span class="badge bg-light-success">
                                                                        <i class="ph ph-check me-1"></i>{{ __('events.scoring.assigned') }}
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-light-secondary">
                                                                        <i class="ph ph-clock me-1"></i>{{ __('events.scoring.to_assign') }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="ph-duotone ph-ranking f-s-60 text-muted mb-3"></i>
                                <h5 class="text-muted">{{ __('gamification.no_rankings') }}</h5>
                                
                                @if($canCalculate)
                                    <p class="text-muted mb-3">{{ __('events.scoring.you_have_participants_with_scores') }}</p>
                                    <button wire:click="calculatePartialRankings" class="btn btn-warning">
                                        <i class="ph ph-calculator me-2"></i>{{ __('events.scoring.calculate_partial_rankings') }}
                                    </button>
                                @else
                                    <p class="text-muted mb-3">{{ __('events.scoring.insert_scores_before_generating_rankings') }}</p>
                                    <a href="{{ route('events.scoring.scores', $event) }}" class="btn btn-primary">
                                        <i class="ph ph-pencil-line me-2"></i>{{ __('events.scoring.go_to_scores') }}
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        function confirmFinalize() {
            Swal.fire({
                title: '{{ __('events.scoring.confirm_finalize_event') }}?',
                html: `
                    <div class="text-start">
                        <p class="mb-3">{{ __('events.scoring.this_action_will_complete_the_event') }}:</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="ph ph-check text-success me-2"></i> {{ __('events.scoring.calculate_final_rankings') }}</li>
                            <li class="mb-2"><i class="ph ph-trophy text-warning me-2"></i> {{ __('events.scoring.assign_badges_to_winners') }}</li>
                            <li class="mb-2"><i class="ph ph-lock text-info me-2"></i> {{ __('events.scoring.close_event') }}</li>
                            <li class="mb-2"><i class="ph ph-eye text-primary me-2"></i> {{ __('events.scoring.publish_results') }}</li>
                        </ul>
                        <p class="text-danger mb-0"><strong>{{ __('common.warning') }}:</strong> {{ __('events.scoring.you_will_not_be_able_to_modify_scores') }}</p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '{{ __('events.scoring.yes_finalize') }}',
                cancelButtonText: '{{ __('events.scoring.cancel') }}',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-light-secondary',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('finalizeEvent');
                }
            });
        }

        Livewire.on('swal:success', (data) => {
            Swal.fire({
                icon: 'success',
                title: data[0].title || '{{ __('common.success') }}',
                text: data[0].text || '',
                confirmButtonClass: 'btn btn-primary',
                timer: 3000
            });
        });

        Livewire.on('swal:error', (data) => {
            Swal.fire({
                icon: 'error',
                title: data[0].title || '{{ __('events.scoring.error') }}',
                text: data[0].text || '',
                confirmButtonClass: 'btn btn-danger'
            });
        });

        Livewire.on('redirect-after-delay', (data) => {
            setTimeout(() => {
                window.location.href = data[0].url;
            }, data[0].delay || 3000);
        });
    </script>
    @endscript
</div>
