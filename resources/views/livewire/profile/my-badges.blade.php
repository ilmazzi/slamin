<div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="ph-duotone ph-trophy me-2"></i>
                {{ __('profile.my_badges') }}
            </h5>
            <p class="text-muted small mb-0">
                {{ __('profile.manage_badges_description') }}
            </p>
        </div>

        <div class="card-body">
            @if($badges && $badges->count() > 0)
                <div class="row g-3">
                    @foreach($badges as $userBadge)
                        @if($userBadge->badge)
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card h-100 {{ ($userBadge->show_in_sidebar || $userBadge->show_in_profile) ? 'border-primary' : 'border' }}">
                                    <div class="card-body">
                                        <div class="text-center mb-3">
                                            <img src="{{ $userBadge->badge->icon_url }}" 
                                                 alt="{{ $userBadge->badge->name }}"
                                                 style="width: 80px; height: 80px;">
                                        </div>

                                        <h6 class="card-title text-center mb-2">{{ $userBadge->badge->name }}</h6>
                                        
                                        @if($userBadge->badge->description)
                                            <p class="text-muted small text-center mb-3">{{ $userBadge->badge->description }}</p>
                                        @endif

                                        <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                                            <span class="badge bg-warning">
                                                <i class="ph ph-star-four me-1"></i>{{ $userBadge->badge->points }} {{ __('profile.points') }}
                                            </span>
                                            <div class="text-end">
                                                <small class="text-muted d-block">{{ $userBadge->earned_at->format('d/m/Y') }}</small>
                                                <small class="text-muted">{{ $userBadge->earned_at->diffForHumans() }}</small>
                                            </div>
                                        </div>

                                        {{-- Rotating Badges Toggle --}}
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <label class="form-label small mb-0">
                                                    <i class="ph ph-arrows-clockwise me-1"></i>{{ __('profile.show_rotating') }}
                                                    <span class="badge bg-secondary ms-1">{{ __('profile.max_3') }}</span>
                                                </label>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" 
                                                           class="form-check-input" 
                                                           id="rotating_{{ $userBadge->id }}"
                                                           wire:change="toggleRotating({{ $userBadge->id }})"
                                                           {{ $userBadge->show_in_profile ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                            @if($userBadge->show_in_profile)
                                                <input type="number" 
                                                       wire:model.blur="rotatingOrders.{{ $userBadge->id }}"
                                                       wire:change="updateRotatingOrder({{ $userBadge->id }}, $event.target.value)"
                                                       class="form-control form-control-sm mt-2" 
                                                       min="0" 
                                                       max="2"
                                                       placeholder="{{ __('profile.order_rotating') }}">
                                            @endif
                                        </div>

                                        {{-- Sidebar Toggle --}}
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <label class="form-label small mb-0">
                                                    <i class="ph ph-sidebar me-1"></i>{{ __('profile.show_sidebar') }}
                                                    <span class="badge bg-secondary ms-1">{{ __('profile.max_3') }}</span>
                                                </label>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" 
                                                           class="form-check-input" 
                                                           id="sidebar_{{ $userBadge->id }}"
                                                           wire:change="toggleSidebar({{ $userBadge->id }})"
                                                           {{ $userBadge->show_in_sidebar ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                            @if($userBadge->show_in_sidebar)
                                                <input type="number" 
                                                       wire:model.blur="sidebarOrders.{{ $userBadge->id }}"
                                                       wire:change="updateSidebarOrder({{ $userBadge->id }}, $event.target.value)"
                                                       class="form-control form-control-sm mt-2" 
                                                       min="0" 
                                                       max="2"
                                                       placeholder="{{ __('profile.order_sidebar') }}">
                                            @endif
                                        </div>

                                        {{-- Manually Awarded Badge Info --}}
                                        @if($userBadge->awarded_by)
                                            <div class="mt-2">
                                                <span class="badge bg-warning w-100">
                                                    <i class="ph ph-user me-1"></i>
                                                    {{ __('profile.awarded_by') }} {{ $userBadge->awardedBy->getDisplayName() }}
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
                <div class="alert alert-info mt-4">
                    <div class="d-flex align-items-start">
                        <i class="ph-duotone ph-info f-s-24 me-3"></i>
                        <div>
                            <strong>{{ __('profile.how_it_works') }}</strong>
                            <ul class="mb-0 mt-2">
                                <li><strong>{{ __('profile.rotating_badges') }}:</strong> {{ __('profile.rotating_badges_info') }}</li>
                                <li><strong>{{ __('profile.sidebar_badges') }}:</strong> {{ __('profile.sidebar_badges_info') }}</li>
                                <li>{{ __('profile.different_badges_info') }}</li>
                                <li>{{ __('profile.order_info') }}</li>
                                <li>{{ __('profile.toggle_info') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="ph-duotone ph-trophy f-s-60 text-muted mb-3"></i>
                    <h5 class="text-muted">{{ __('profile.no_badges_earned') }}</h5>
                    <p class="text-muted">{{ __('profile.no_badges_description') }}</p>
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

        Livewire.on('swal:warning', (data) => {
            Swal.fire({
                icon: 'warning',
                title: data[0].title || 'Attenzione',
                text: data[0].text || '',
                confirmButtonText: 'OK',
                confirmButtonClass: 'btn btn-warning'
            });
        });

        // Refresh sidebar when badges change
        Livewire.on('refresh-sidebar', () => {
            // Reload the page after a short delay to show the toast first
            setTimeout(() => {
                window.location.reload();
            }, 2500);
        });
    </script>
    @endscript
</div>
