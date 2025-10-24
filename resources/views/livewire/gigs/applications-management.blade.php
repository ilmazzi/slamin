<div class="page-content">
    <div class="container-fluid">
        
        <div class="mb-3">
            <a href="{{ route('gigs.show', $gig) }}" class="btn btn-light-secondary btn-sm">
                <i class="ph ph-arrow-left me-2"></i>{{ __('gigs.back_to_gig') }}
            </a>
        </div>

        <div class="page-title-box">
            <h4 class="mb-2">{{ __('gigs.applications.manage_applications') }}</h4>
            <p class="text-muted mb-0">{{ $gig->title }}</p>
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
                            <i class="ph ph-users f-s-18 text-primary"></i>
                        </div>
                        <span class="ripple-effect"></span>
                        <div class="overflow-hidden">
                            <h4 class="text-primary mb-1 f-w-600">{{ number_format($stats['total']) }}</h4>
                            <p class="f-w-500 text-dark f-s-12 mb-1">{{ __('gigs.applications.total') }}</p>
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
                            <h4 class="text-warning mb-1 f-w-600">{{ number_format($stats['pending']) }}</h4>
                            <p class="f-w-500 text-dark f-s-12 mb-1">{{ __('gigs.applications.pending') }}</p>
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
                            <h4 class="text-success mb-1 f-w-600">{{ number_format($stats['accepted']) }}</h4>
                            <p class="f-w-500 text-dark f-s-12 mb-1">{{ __('gigs.applications.accepted') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card hover-effect equal-card">
                    <div class="card-body eshop-cards text-center pa-15">
                        <div class="bg-light-danger h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                            <i class="ph ph-x-circle f-s-18 text-danger"></i>
                        </div>
                        <span class="ripple-effect"></span>
                        <div class="overflow-hidden">
                            <h4 class="text-danger mb-1 f-w-600">{{ number_format($stats['rejected']) }}</h4>
                            <p class="f-w-500 text-dark f-s-12 mb-1">{{ __('gigs.applications.rejected') }}</p>
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
                    <button wire:click="$set('status_filter', 'pending')" class="btn btn-sm {{ $status_filter === 'pending' ? 'btn-warning' : 'btn-light-secondary' }}">
                        {{ __('gigs.applications.pending') }}
                    </button>
                    <button wire:click="$set('status_filter', 'accepted')" class="btn btn-sm {{ $status_filter === 'accepted' ? 'btn-success' : 'btn-light-secondary' }}">
                        {{ __('gigs.applications.accepted') }}
                    </button>
                    <button wire:click="$set('status_filter', 'rejected')" class="btn btn-sm {{ $status_filter === 'rejected' ? 'btn-danger' : 'btn-light-secondary' }}">
                        {{ __('gigs.applications.rejected') }}
                    </button>
                </div>
            </div>
        </div>

        {{-- Applications List --}}
        <div class="row g-3">
            @forelse($applications as $application)
                <div class="col-12">
                    <div class="card hover-effect">
                        <div class="card-body">
                            <div class="row g-3">
                                {{-- Applicant Info --}}
                                <div class="col-12 col-md-3">
                                    <div class="d-flex flex-column align-items-center text-center">
                                        @if($application->user->avatar)
                                            <img src="{{ $application->user->avatar }}" class="rounded-circle mb-2" style="width: 64px; height: 64px;">
                                        @else
                                            <div class="bg-light-primary rounded-circle d-flex-center mb-2" style="width: 64px; height: 64px;">
                                                <i class="ph ph-user f-s-24 text-primary"></i>
                                            </div>
                                        @endif
                                        <h6 class="mb-1">{{ $application->user->name }}</h6>
                                        @if($application->user->nickname)
                                            <p class="text-muted f-s-13 mb-2">{{ '@' . $application->user->nickname }}</p>
                                        @endif
                                        <a href="{{ route('user.show', $application->user) }}" class="btn btn-sm btn-light-primary w-100">
                                            {{ __('gigs.view_profile') }}
                                        </a>
                                    </div>
                                </div>

                                {{-- Application Details --}}
                                <div class="col-12 col-md-6">
                                    {{-- Status --}}
                                    <div class="mb-2">
                                        @if($application->status === 'pending')
                                            <span class="badge bg-light-warning text-warning">{{ __('gigs.applications.pending') }}</span>
                                        @elseif($application->status === 'accepted')
                                            <span class="badge bg-light-success text-success">{{ __('gigs.applications.accepted') }}</span>
                                        @elseif($application->status === 'rejected')
                                            <span class="badge bg-light-danger text-danger">{{ __('gigs.applications.rejected') }}</span>
                                        @endif
                                        <span class="text-muted f-s-13 ms-2">{{ $application->created_at->diffForHumans() }}</span>
                                    </div>

                                    {{-- Message --}}
                                    <div class="mb-3">
                                        <h6 class="mb-2">{{ __('gigs.applications.message') }}</h6>
                                        <p class="text-muted f-s-14">{{ $application->message }}</p>
                                    </div>

                                    {{-- Experience --}}
                                    @if($application->experience)
                                        <div class="mb-3">
                                            <h6 class="mb-2">{{ __('gigs.applications.experience') }}</h6>
                                            <p class="text-muted f-s-14">{{ $application->experience }}</p>
                                        </div>
                                    @endif

                                    {{-- Portfolio --}}
                                    @if($application->portfolio_url)
                                        <div class="mb-3">
                                            <h6 class="mb-2">{{ __('gigs.applications.portfolio') }}</h6>
                                            <a href="{{ $application->portfolio_url }}" target="_blank" class="text-primary">
                                                <i class="ph ph-link me-1"></i>{{ __('gigs.applications.view_portfolio') }}
                                            </a>
                                        </div>
                                    @endif

                                    {{-- Availability --}}
                                    @if($application->availability)
                                        <div class="mb-3">
                                            <h6 class="mb-2">{{ __('gigs.applications.availability') }}</h6>
                                            <p class="text-muted f-s-14">{{ $application->availability }}</p>
                                        </div>
                                    @endif

                                    {{-- Compensation Expectation --}}
                                    @if($application->compensation_expectation)
                                        <div>
                                            <h6 class="mb-2">{{ __('gigs.applications.compensation_expectation') }}</h6>
                                            <p class="text-success f-s-14">{{ $application->compensation_expectation }}</p>
                                        </div>
                                    @endif
                                </div>

                                {{-- Actions --}}
                                <div class="col-12 col-md-3">
                                    <div class="d-flex flex-column gap-2">
                                        @if($application->status === 'pending')
                                            <button wire:click="acceptApplication({{ $application->id }})" class="btn btn-success w-100">
                                                <i class="ph ph-check me-2"></i>{{ __('gigs.applications.accept') }}
                                            </button>
                                            <button wire:click="rejectApplication({{ $application->id }})" class="btn btn-light-danger w-100">
                                                <i class="ph ph-x me-2"></i>{{ __('gigs.applications.reject') }}
                                            </button>
                                        @elseif($application->status === 'accepted')
                                            <div class="alert alert-success mb-0">
                                                <i class="ph ph-check-circle me-2"></i>{{ __('gigs.applications.accepted') }}
                                            </div>
                                        @elseif($application->status === 'rejected')
                                            <div class="alert alert-danger mb-0">
                                                <i class="ph ph-x-circle me-2"></i>{{ __('gigs.applications.rejected') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="ph ph-users f-s-48 text-muted mb-3 d-block"></i>
                            <h5 class="text-muted">{{ __('gigs.applications.no_applications') }}</h5>
                            <p class="text-muted mb-0">{{ __('gigs.applications.no_applications_description') }}</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $applications->links() }}
        </div>

    </div>
</div>

