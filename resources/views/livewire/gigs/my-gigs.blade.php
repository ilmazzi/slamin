<div class="page-content">
    <div class="container-fluid">
        
        <div class="page-title-box">
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                <div>
                    <h4 class="mb-2">{{ __('gigs.my_gigs') }}</h4>
                    <p class="text-muted mb-0">{{ __('gigs.my_gigs_description') }}</p>
                </div>
                <a href="{{ route('gigs.create') }}" class="btn btn-primary mt-3 mt-md-0">
                    <i class="ph ph-plus me-2"></i>{{ __('gigs.create_gig') }}
                </a>
            </div>
        </div>

        @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="ph ph-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

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
                            <p class="f-w-500 text-dark f-s-12 mb-1">{{ __('gigs.stats.my_gigs_count') }}</p>
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
                            <h4 class="text-success mb-1 f-w-600">{{ number_format($stats['open_gigs']) }}</h4>
                            <p class="f-w-500 text-dark f-s-12 mb-1">{{ __('gigs.stats.open_gigs') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card hover-effect equal-card">
                    <div class="card-body eshop-cards text-center pa-15">
                        <div class="bg-light-info h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                            <i class="ph ph-users f-s-18 text-info"></i>
                        </div>
                        <span class="ripple-effect"></span>
                        <div class="overflow-hidden">
                            <h4 class="text-info mb-1 f-w-600">{{ number_format($stats['total_applications']) }}</h4>
                            <p class="f-w-500 text-dark f-s-12 mb-1">{{ __('gigs.stats.total_applications') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card hover-effect equal-card">
                    <div class="card-body eshop-cards text-center pa-15">
                        <div class="bg-light-warning h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                            <i class="ph ph-clock f-s-18 text-warning"></i>
                        </div>
                        <span class="ripple-effect"></span>
                        <div class="overflow-hidden">
                            <h4 class="text-warning mb-1 f-w-600">{{ number_format($stats['pending_applications']) }}</h4>
                            <p class="f-w-500 text-dark f-s-12 mb-1">{{ __('gigs.stats.pending_applications') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    <button wire:click="$set('status_filter', 'all')" class="btn btn-sm {{ $status_filter === 'all' ? 'btn-primary' : 'btn-light-secondary' }}">
                        {{ __('gigs.filters.all') }}
                    </button>
                    <button wire:click="$set('status_filter', 'open')" class="btn btn-sm {{ $status_filter === 'open' ? 'btn-success' : 'btn-light-secondary' }}">
                        {{ __('gigs.status.open') }}
                    </button>
                    <button wire:click="$set('status_filter', 'closed')" class="btn btn-sm {{ $status_filter === 'closed' ? 'btn-danger' : 'btn-light-secondary' }}">
                        {{ __('gigs.status.closed') }}
                    </button>
                    <button wire:click="$set('status_filter', 'expired')" class="btn btn-sm {{ $status_filter === 'expired' ? 'btn-secondary' : 'btn-light-secondary' }}">
                        {{ __('gigs.status.expired') }}
                    </button>
                </div>
            </div>
        </div>

        {{-- Gigs List --}}
        <div class="row g-3">
            @forelse($gigs as $gig)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card hover-effect h-100">
                        <div class="card-body">
                            {{-- Status --}}
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @if($gig->is_closed)
                                    <span class="badge bg-light-danger text-danger">{{ __('gigs.status.closed') }}</span>
                                @elseif($gig->is_expired)
                                    <span class="badge bg-light-secondary text-secondary">{{ __('gigs.status.expired') }}</span>
                                @else
                                    <span class="badge bg-light-success text-success">{{ __('gigs.status.open') }}</span>
                                @endif
                                
                                @if($gig->is_urgent)
                                    <span class="badge bg-light-warning text-warning">
                                        <i class="ph ph-fire me-1"></i>{{ __('gigs.status.urgent') }}
                                    </span>
                                @endif
                            </div>

                            {{-- Title --}}
                            <h5 class="mb-2">{{ $gig->title }}</h5>

                            {{-- Meta --}}
                            <div class="d-flex flex-column gap-2 mb-3 f-s-13">
                                @if($gig->location)
                                    <div class="text-muted">
                                        <i class="ph ph-map-pin me-1"></i>{{ $gig->location }}
                                    </div>
                                @endif
                                <div class="text-muted">
                                    <i class="ph ph-calendar me-1"></i>{{ $gig->deadline->format('d/m/Y') }}
                                </div>
                                <div class="text-primary">
                                    <i class="ph ph-users me-1"></i>{{ $gig->applications_count }} {{ __('gigs.applications.applications') }}
                                    @if($gig->pendingApplications()->count() > 0)
                                        <span class="badge bg-warning ms-1">{{ $gig->pendingApplications()->count() }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="d-flex flex-wrap gap-2 mt-auto pt-3 border-top">
                                <a href="{{ route('gigs.show', $gig) }}" class="btn btn-sm btn-light-primary">
                                    <i class="ph ph-eye me-1"></i>{{ __('gigs.view') }}
                                </a>
                                <a href="{{ route('gigs.edit', $gig) }}" class="btn btn-sm btn-light-info">
                                    <i class="ph ph-pencil me-1"></i>{{ __('gigs.edit') }}
                                </a>
                                <a href="{{ route('gigs.manage-applications', $gig) }}" class="btn btn-sm btn-light-secondary">
                                    <i class="ph ph-users me-1"></i>{{ $gig->pendingApplications()->count() }}
                                </a>
                                @if($gig->is_closed)
                                    <button wire:click="reopenGig({{ $gig->id }})" class="btn btn-sm btn-light-success">
                                        <i class="ph ph-arrow-counter-clockwise me-1"></i>
                                    </button>
                                @else
                                    <button wire:click="closeGig({{ $gig->id }})" class="btn btn-sm btn-light-warning">
                                        <i class="ph ph-x-circle me-1"></i>
                                    </button>
                                @endif
                                <button onclick="confirm('{{ __('gigs.confirm_delete') }}') || event.stopImmediatePropagation()" 
                                        wire:click="deleteGig({{ $gig->id }})" 
                                        class="btn btn-sm btn-light-danger">
                                    <i class="ph ph-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="ph ph-briefcase f-s-48 text-muted mb-3 d-block"></i>
                            <h5 class="text-muted">{{ __('gigs.messages.no_my_gigs') }}</h5>
                            <p class="text-muted mb-3">{{ __('gigs.messages.no_my_gigs_description') }}</p>
                            <a href="{{ route('gigs.create') }}" class="btn btn-primary">
                                <i class="ph ph-plus me-2"></i>{{ __('gigs.create_gig') }}
                            </a>
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

