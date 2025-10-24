<div class="page-content">
    <div class="container-fluid">
        
        {{-- Page Title --}}
        <div class="page-title-box">
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                <div>
                    <h4 class="mb-2">{{ __('gigs.title') }}</h4>
                    <p class="text-muted mb-0">{{ __('gigs.browse_all') }}</p>
                </div>
                @auth
                    @if(!auth()->user()->hasRole('audience'))
                        <a href="{{ route('gigs.create') }}" class="btn btn-primary mt-3 mt-md-0">
                            <i class="ph ph-plus me-2"></i>{{ __('gigs.create_gig') }}
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        {{-- Stats --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card hover-effect equal-card">
                    <div class="card-body eshop-cards text-center pa-15">
                        <div class="bg-light-primary h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                            <i class="ph ph-briefcase f-s-18 text-primary"></i>
                        </div>
                        <span class="ripple-effect"></span>
                        <div class="overflow-hidden">
                            <h4 class="text-primary mb-1 f-w-600">{{ number_format($stats['total_gigs']) }}</h4>
                            <p class="f-w-500 text-dark f-s-12 mb-1">{{ __('gigs.stats.total_gigs') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card hover-effect equal-card">
                    <div class="card-body eshop-cards text-center pa-15">
                        <div class="bg-light-success h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                            <i class="ph ph-check-circle f-s-18 text-success"></i>
                        </div>
                        <span class="ripple-effect"></span>
                        <div class="overflow-hidden">
                            <h4 class="text-success mb-1 f-w-600">{{ number_format($stats['open_gigs_count']) }}</h4>
                            <p class="f-w-500 text-dark f-s-12 mb-1">{{ __('gigs.stats.open_gigs_count') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card hover-effect equal-card">
                    <div class="card-body eshop-cards text-center pa-15">
                        <div class="bg-light-warning h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                            <i class="ph ph-fire f-s-18 text-warning"></i>
                        </div>
                        <span class="ripple-effect"></span>
                        <div class="overflow-hidden">
                            <h4 class="text-warning mb-1 f-w-600">{{ number_format($stats['urgent_gigs_count']) }}</h4>
                            <p class="f-w-500 text-dark f-s-12 mb-1">{{ __('gigs.stats.urgent_gigs_count') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card hover-effect equal-card">
                    <div class="card-body eshop-cards text-center pa-15">
                        <div class="bg-light-info h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                            <i class="ph ph-star f-s-18 text-info"></i>
                        </div>
                        <span class="ripple-effect"></span>
                        <div class="overflow-hidden">
                            <h4 class="text-info mb-1 f-w-600">{{ number_format($stats['featured_gigs_count']) }}</h4>
                            <p class="f-w-500 text-dark f-s-12 mb-1">{{ __('gigs.stats.featured_gigs_count') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-3">{{ __('gigs.filters.title') }}</h5>
                
                <div class="row g-3">
                    {{-- Search --}}
                    <div class="col-12 col-md-6">
                        <input type="text" 
                               wire:model.live.debounce.500ms="search" 
                               class="form-control" 
                               placeholder="{{ __('gigs.filters.search') }}">
                    </div>

                    {{-- Category --}}
                    <div class="col-6 col-md-3">
                        <select wire:model.live="category" class="form-select">
                            <option value="">{{ __('gigs.filters.select_category') }}</option>
                            <option value="performance">{{ __('gigs.categories.performance') }}</option>
                            <option value="hosting">{{ __('gigs.categories.hosting') }}</option>
                            <option value="judging">{{ __('gigs.categories.judging') }}</option>
                            <option value="technical">{{ __('gigs.categories.technical') }}</option>
                            <option value="translation">{{ __('gigs.categories.translation') }}</option>
                            <option value="other">{{ __('gigs.categories.other') }}</option>
                        </select>
                    </div>

                    {{-- Type --}}
                    <div class="col-6 col-md-3">
                        <select wire:model.live="type" class="form-select">
                            <option value="">{{ __('gigs.filters.select_type') }}</option>
                            <option value="paid">{{ __('gigs.types.paid') }}</option>
                            <option value="volunteer">{{ __('gigs.types.volunteer') }}</option>
                            <option value="collaboration">{{ __('gigs.types.collaboration') }}</option>
                        </select>
                    </div>

                    {{-- Language --}}
                    <div class="col-6 col-md-3">
                        <select wire:model.live="language" class="form-select">
                            <option value="">{{ __('gigs.filters.select_language') }}</option>
                            <option value="it">{{ __('gigs.languages.italian') }}</option>
                            <option value="en">{{ __('gigs.languages.english') }}</option>
                            <option value="es">{{ __('gigs.languages.spanish') }}</option>
                            <option value="fr">{{ __('gigs.languages.french') }}</option>
                            <option value="de">{{ __('gigs.languages.german') }}</option>
                        </select>
                    </div>

                    {{-- Location --}}
                    <div class="col-6 col-md-3">
                        <input type="text" 
                               wire:model.live.debounce.500ms="location" 
                               class="form-control" 
                               placeholder="{{ __('gigs.filters.location') }}">
                    </div>

                    {{-- Event --}}
                    @if($events->count() > 0)
                    <div class="col-6 col-md-3">
                        <select wire:model.live="event_id" class="form-select">
                            <option value="">{{ __('gigs.filters.select_event') }}</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}">{{ $event->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    {{-- Group --}}
                    @if($groups->count() > 0)
                    <div class="col-6 col-md-3">
                        <select wire:model.live="group_id" class="form-select">
                            <option value="">{{ __('gigs.filters.select_group') }}</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    {{-- Checkboxes --}}
                    <div class="col-12">
                        <div class="d-flex flex-wrap gap-3">
                            <div class="form-check">
                                <input type="checkbox" wire:model.live="show_featured" class="form-check-input" id="show_featured">
                                <label class="form-check-label" for="show_featured">
                                    {{ __('gigs.filters.show_featured') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" wire:model.live="show_urgent" class="form-check-input" id="show_urgent">
                                <label class="form-check-label" for="show_urgent">
                                    {{ __('gigs.filters.show_urgent') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" wire:model.live="show_remote" class="form-check-input" id="show_remote">
                                <label class="form-check-label" for="show_remote">
                                    {{ __('gigs.filters.show_remote') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Clear Filters --}}
                    <div class="col-12">
                        <button wire:click="clearFilters" class="btn btn-light-secondary">
                            <i class="ph ph-x me-2"></i>{{ __('gigs.filters.clear_filters') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Gigs List --}}
        <div class="row g-3">
            @forelse($gigs as $gig)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card hover-effect h-100">
                        <div class="card-body">
                            {{-- Status Badges --}}
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @if($gig->is_urgent)
                                    <span class="badge bg-light-warning text-warning">
                                        <i class="ph ph-fire me-1"></i>{{ __('gigs.status.urgent') }}
                                    </span>
                                @endif
                                @if($gig->is_featured)
                                    <span class="badge bg-light-info text-info">
                                        <i class="ph ph-star me-1"></i>{{ __('gigs.status.featured') }}
                                    </span>
                                @endif
                                @if($gig->is_remote)
                                    <span class="badge bg-light-success text-success">
                                        <i class="ph ph-laptop me-1"></i>{{ __('gigs.remote') }}
                                    </span>
                                @endif
                            </div>

                            {{-- Title --}}
                            <h5 class="mb-2">{{ $gig->title }}</h5>

                            {{-- Description --}}
                            <p class="text-muted f-s-14 mb-3">
                                {{ Str::limit($gig->description, 100) }}
                            </p>

                            {{-- Meta Info --}}
                            <div class="d-flex flex-column gap-2 mb-3 f-s-13">
                                @if($gig->location)
                                    <div class="text-muted">
                                        <i class="ph ph-map-pin me-1"></i>{{ $gig->location }}
                                    </div>
                                @endif
                                @if($gig->compensation)
                                    <div class="text-success">
                                        <i class="ph ph-currency-euro me-1"></i>{{ $gig->compensation }}
                                    </div>
                                @endif
                                <div class="text-muted">
                                    <i class="ph ph-calendar me-1"></i>{{ $gig->deadline->format('d/m/Y') }}
                                </div>
                                @if($gig->applications_count > 0)
                                    <div class="text-primary">
                                        <i class="ph ph-users me-1"></i>{{ $gig->applications_count }} {{ __('gigs.applications.applications') }}
                                    </div>
                                @endif
                            </div>

                            {{-- Footer --}}
                            <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                                <div class="d-flex align-items-center gap-2">
                                    @if($gig->user->avatar)
                                        <img src="{{ $gig->user->avatar }}" class="rounded-circle" style="width: 24px; height: 24px;">
                                    @endif
                                    <span class="f-s-13 text-muted">{{ $gig->user->name }}</span>
                                </div>
                                <a href="{{ route('gigs.show', $gig) }}" class="btn btn-sm btn-light-primary">
                                    {{ __('gigs.actions.view') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="ph ph-briefcase f-s-48 text-muted mb-3 d-block"></i>
                            <h5 class="text-muted">{{ __('gigs.messages.no_gigs_found') }}</h5>
                            <p class="text-muted mb-0">{{ __('gigs.messages.no_gigs_description') }}</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $gigs->links() }}
        </div>

    </div>
</div>

