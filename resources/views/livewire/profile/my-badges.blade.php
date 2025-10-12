<div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="ph-duotone ph-trophy me-2"></i>
                {{ __('gamification.my_badges') }}
            </h5>
            <p class="text-muted small mb-0">
                Gestisci i tuoi badge: scegli quali mostrare nel profilo e sidebar
            </p>
        </div>

        <div class="card-body">
            @if($badges && $badges->count() > 0)
                <div class="row g-3">
                    @foreach($badges as $userBadge)
                        @if($userBadge->badge)
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 {{ $userBadge->is_featured ? 'border-primary' : 'border' }}">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start justify-content-between mb-3">
                                            <img src="{{ $userBadge->badge->icon_url }}" 
                                                 alt="{{ $userBadge->badge->name }}"
                                                 style="width: 64px; height: 64px;">
                                            
                                            {{-- Toggle Featured --}}
                                            <div class="form-check form-switch">
                                                <input type="checkbox" 
                                                       class="form-check-input" 
                                                       id="featured_{{ $userBadge->id }}"
                                                       wire:change="toggleFeatured({{ $userBadge->id }})"
                                                       {{ $userBadge->is_featured ? 'checked' : '' }}>
                                                <label class="form-check-label" for="featured_{{ $userBadge->id }}">
                                                    <small>{{ $userBadge->is_featured ? 'Visibile' : 'Nascosto' }}</small>
                                                </label>
                                            </div>
                                        </div>

                                        <h6 class="card-title mb-2">{{ $userBadge->badge->name }}</h6>
                                        
                                        @if($userBadge->badge->description)
                                            <p class="text-muted small mb-2">{{ $userBadge->badge->description }}</p>
                                        @endif

                                        <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top">
                                            <div>
                                                <span class="badge bg-gradient-warning">
                                                    <i class="ph ph-star-four me-1"></i>{{ $userBadge->badge->points }} punti
                                                </span>
                                            </div>
                                            <div class="text-end">
                                                <small class="text-muted d-block">
                                                    <i class="ph ph-calendar me-1"></i>
                                                    {{ $userBadge->earned_at->format('d/m/Y') }}
                                                </small>
                                                <small class="text-muted">
                                                    {{ $userBadge->earned_at->diffForHumans() }}
                                                </small>
                                            </div>
                                        </div>

                                        {{-- Display Order (only for featured badges) --}}
                                        @if($userBadge->is_featured)
                                            <div class="mt-3 pt-2 border-top">
                                                <label class="form-label small mb-1">Ordine di visualizzazione</label>
                                                <input type="number" 
                                                       wire:model.blur="displayOrders.{{ $userBadge->id }}"
                                                       wire:change="updateDisplayOrder({{ $userBadge->id }}, $event.target.value)"
                                                       class="form-control form-control-sm" 
                                                       min="0" 
                                                       placeholder="0">
                                                <small class="text-muted">Numeri più bassi appaiono per primi</small>
                                            </div>
                                        @endif

                                        {{-- Manually Awarded Badge Info --}}
                                        @if($userBadge->awarded_by)
                                            <div class="mt-2">
                                                <span class="badge bg-light-warning">
                                                    <i class="ph ph-user me-1"></i>
                                                    Assegnato da {{ $userBadge->awardedBy->getDisplayName() }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                {{-- Info Box --}}
                <div class="alert alert-light mt-4">
                    <div class="d-flex align-items-start">
                        <i class="ph-duotone ph-info f-s-24 me-3 text-primary"></i>
                        <div>
                            <strong>Come funziona?</strong>
                            <ul class="mb-0 mt-2">
                                <li>Attiva il toggle per mostrare un badge nella tua sidebar e profilo</li>
                                <li>Puoi mostrare fino a 3 badge contemporaneamente</li>
                                <li>Usa "Ordine di visualizzazione" per decidere quale appare per primo</li>
                                <li>I numeri più bassi hanno priorità (0 appare prima di 1, ecc.)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="ph-duotone ph-trophy f-s-60 text-muted mb-3"></i>
                    <h5 class="text-muted">{{ __('gamification.no_badges_earned') }}</h5>
                    <p class="text-muted">Inizia a guadagnare badge completando azioni sul portale!</p>
                </div>
            @endif
        </div>
    </div>

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
                timer: 2000,
                toast: true,
                position: 'top-end',
                showConfirmButton: false
            });
        });
    </script>
    @endscript
</div>
