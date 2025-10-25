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
                <!-- Rotating Badges Section -->
                <div class="mb-5">
                    <h6 class="f-w-600 mb-3">
                        <i class="ph ph-arrows-clockwise me-2"></i>{{ __('profile.rotating_badges') }}
                        <span class="badge bg-secondary ms-2">{{ __('profile.max_3') }}</span>
                    </h6>
                    <p class="text-muted f-s-14 mb-3">{{ __('profile.rotating_badges_info') }}</p>
                    
                    <div class="row g-3">
                        <!-- Posizione 1 -->
                        <div class="col-md-4">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <strong>{{ __('profile.position') }} 1</strong>
                                    <small class="d-block">{{ __('profile.inner_orbit') }}</small>
                                </div>
                                <div class="card-body">
                                    <select class="form-select" wire:model.live="rotatingPosition1" wire:change="setRotatingPosition($event.target.value, 1)">
                                        <option value="">{{ __('profile.select_badge') }}</option>
                                        @foreach($badges as $userBadge)
                                            @if($userBadge->badge)
                                                <option value="{{ $userBadge->id }}">{{ $userBadge->badge->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @if($rotatingPosition1)
                                        @php $selectedBadge = $badges->firstWhere('id', $rotatingPosition1); @endphp
                                        @if($selectedBadge && $selectedBadge->badge)
                                            <div class="text-center mt-3">
                                                <img src="{{ $selectedBadge->badge->icon_url }}" 
                                                     alt="{{ $selectedBadge->badge->name }}"
                                                     style="width: 60px; height: 60px;">
                                                <p class="text-muted f-s-12 mt-2">{{ $selectedBadge->badge->name }}</p>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Posizione 2 -->
                        <div class="col-md-4">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <strong>{{ __('profile.position') }} 2</strong>
                                    <small class="d-block">{{ __('profile.middle_orbit') }}</small>
                                </div>
                                <div class="card-body">
                                    <select class="form-select" wire:model.live="rotatingPosition2" wire:change="setRotatingPosition($event.target.value, 2)">
                                        <option value="">{{ __('profile.select_badge') }}</option>
                                        @foreach($badges as $userBadge)
                                            @if($userBadge->badge)
                                                <option value="{{ $userBadge->id }}">{{ $userBadge->badge->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @if($rotatingPosition2)
                                        @php $selectedBadge = $badges->firstWhere('id', $rotatingPosition2); @endphp
                                        @if($selectedBadge && $selectedBadge->badge)
                                            <div class="text-center mt-3">
                                                <img src="{{ $selectedBadge->badge->icon_url }}" 
                                                     alt="{{ $selectedBadge->badge->name }}"
                                                     style="width: 60px; height: 60px;">
                                                <p class="text-muted f-s-12 mt-2">{{ $selectedBadge->badge->name }}</p>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Posizione 3 -->
                        <div class="col-md-4">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <strong>{{ __('profile.position') }} 3</strong>
                                    <small class="d-block">{{ __('profile.outer_orbit') }}</small>
                                </div>
                                <div class="card-body">
                                    <select class="form-select" wire:model.live="rotatingPosition3" wire:change="setRotatingPosition($event.target.value, 3)">
                                        <option value="">{{ __('profile.select_badge') }}</option>
                                        @foreach($badges as $userBadge)
                                            @if($userBadge->badge)
                                                <option value="{{ $userBadge->id }}">{{ $userBadge->badge->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @if($rotatingPosition3)
                                        @php $selectedBadge = $badges->firstWhere('id', $rotatingPosition3); @endphp
                                        @if($selectedBadge && $selectedBadge->badge)
                                            <div class="text-center mt-3">
                                                <img src="{{ $selectedBadge->badge->icon_url }}" 
                                                     alt="{{ $selectedBadge->badge->name }}"
                                                     style="width: 60px; height: 60px;">
                                                <p class="text-muted f-s-12 mt-2">{{ $selectedBadge->badge->name }}</p>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Badges Section -->
                <div class="mb-4">
                    <h6 class="f-w-600 mb-3">
                        <i class="ph ph-sidebar me-2"></i>{{ __('profile.sidebar_badges') }}
                        <span class="badge bg-secondary ms-2">{{ __('profile.max_3') }}</span>
                    </h6>
                    <p class="text-muted f-s-14 mb-3">{{ __('profile.sidebar_badges_info') }}</p>
                    
                    <div class="row g-3">
                        <!-- Posizione 1 -->
                        <div class="col-md-4">
                            <div class="card border-secondary">
                                <div class="card-header bg-secondary text-white">
                                    <strong>{{ __('profile.position') }} 1</strong>
                                </div>
                                <div class="card-body">
                                    <select class="form-select" wire:model.live="sidebarPosition1" wire:change="setSidebarPosition($event.target.value, 1)">
                                        <option value="">{{ __('profile.select_badge') }}</option>
                                        @foreach($badges as $userBadge)
                                            @if($userBadge->badge)
                                                <option value="{{ $userBadge->id }}">{{ $userBadge->badge->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @if($sidebarPosition1)
                                        @php $selectedBadge = $badges->firstWhere('id', $sidebarPosition1); @endphp
                                        @if($selectedBadge && $selectedBadge->badge)
                                            <div class="text-center mt-3">
                                                <img src="{{ $selectedBadge->badge->icon_url }}" 
                                                     alt="{{ $selectedBadge->badge->name }}"
                                                     style="width: 40px; height: 40px;">
                                                <p class="text-muted f-s-12 mt-2">{{ $selectedBadge->badge->name }}</p>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Posizione 2 -->
                        <div class="col-md-4">
                            <div class="card border-secondary">
                                <div class="card-header bg-secondary text-white">
                                    <strong>{{ __('profile.position') }} 2</strong>
                                </div>
                                <div class="card-body">
                                    <select class="form-select" wire:model.live="sidebarPosition2" wire:change="setSidebarPosition($event.target.value, 2)">
                                        <option value="">{{ __('profile.select_badge') }}</option>
                                        @foreach($badges as $userBadge)
                                            @if($userBadge->badge)
                                                <option value="{{ $userBadge->id }}">{{ $userBadge->badge->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @if($sidebarPosition2)
                                        @php $selectedBadge = $badges->firstWhere('id', $sidebarPosition2); @endphp
                                        @if($selectedBadge && $selectedBadge->badge)
                                            <div class="text-center mt-3">
                                                <img src="{{ $selectedBadge->badge->icon_url }}" 
                                                     alt="{{ $selectedBadge->badge->name }}"
                                                     style="width: 40px; height: 40px;">
                                                <p class="text-muted f-s-12 mt-2">{{ $selectedBadge->badge->name }}</p>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Posizione 3 -->
                        <div class="col-md-4">
                            <div class="card border-secondary">
                                <div class="card-header bg-secondary text-white">
                                    <strong>{{ __('profile.position') }} 3</strong>
                                </div>
                                <div class="card-body">
                                    <select class="form-select" wire:model.live="sidebarPosition3" wire:change="setSidebarPosition($event.target.value, 3)">
                                        <option value="">{{ __('profile.select_badge') }}</option>
                                        @foreach($badges as $userBadge)
                                            @if($userBadge->badge)
                                                <option value="{{ $userBadge->id }}">{{ $userBadge->badge->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @if($sidebarPosition3)
                                        @php $selectedBadge = $badges->firstWhere('id', $sidebarPosition3); @endphp
                                        @if($selectedBadge && $selectedBadge->badge)
                                            <div class="text-center mt-3">
                                                <img src="{{ $selectedBadge->badge->icon_url }}" 
                                                     alt="{{ $selectedBadge->badge->name }}"
                                                     style="width: 40px; height: 40px;">
                                                <p class="text-muted f-s-12 mt-2">{{ $selectedBadge->badge->name }}</p>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- All Badges List -->
                <div>
                    <h6 class="f-w-600 mb-3">
                        <i class="ph ph-trophy me-2"></i>{{ __('profile.all_badges') }}
                        <span class="badge bg-primary ms-2">{{ $badges->count() }}</span>
                    </h6>
                    <div class="row g-3">
                        @foreach($badges as $userBadge)
                            @if($userBadge->badge)
                                <div class="col-md-3 col-sm-6">
                                    <div class="card h-100">
                                        <div class="card-body text-center">
                                            <img src="{{ $userBadge->badge->icon_url }}" 
                                                 alt="{{ $userBadge->badge->name }}"
                                                 style="width: 60px; height: 60px;"
                                                 class="mb-2">
                                            <h6 class="f-s-14 f-w-600 mb-1">{{ $userBadge->badge->name }}</h6>
                                            <p class="text-muted f-s-12 mb-2">{{ $userBadge->badge->description }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge bg-warning f-s-11">
                                                    <i class="ph ph-star-four me-1"></i>{{ $userBadge->badge->points }}
                                                </span>
                                                <small class="text-muted f-s-11">{{ $userBadge->earned_at->format('d/m/Y') }}</small>
                                            </div>
                                            
                                            @if($userBadge->awarded_by)
                                                <div class="mt-2">
                                                    <span class="badge bg-warning w-100 f-s-10">
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
                </div>

                {{-- Info Box --}}
                <div class="alert alert-info mt-4">
                    <div class="d-flex align-items-start">
                        <i class="ph-duotone ph-info f-s-24 me-3"></i>
                        <div>
                            <strong>{{ __('profile.how_it_works') }}</strong>
                            <ul class="mb-0 mt-2">
                                <li>{{ __('profile.rotating_selection_info') }}</li>
                                <li>{{ __('profile.sidebar_selection_info') }}</li>
                                <li>{{ __('profile.position_order_info') }}</li>
                                <li>{{ __('profile.same_badge_allowed') }}</li>
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
        $wire.on('swal:success', (data) => {
            Swal.fire({
                icon: 'success',
                title: data[0].title || '{{ __('profile.success') }}',
                text: data[0].text || '',
                confirmButtonText: 'OK',
                confirmButtonClass: 'btn btn-primary',
                timer: 2000,
                toast: true,
                position: 'top-end',
                showConfirmButton: false
            });
        });

        $wire.on('swal:warning', (data) => {
            Swal.fire({
                icon: 'warning',
                title: data[0].title || '{{ __('profile.warning') }}',
                text: data[0].text || '',
                confirmButtonText: 'OK',
                confirmButtonClass: 'btn btn-warning'
            });
        });

        // Refresh sidebar when badges change
        $wire.on('refresh-sidebar', () => {
            setTimeout(() => {
                window.location.reload();
            }, 2500);
        });
    </script>
    @endscript
</div>
