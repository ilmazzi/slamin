<div class="page-content">
    <div class="container-fluid">
        
        <div class="page-title-box">
            <h4 class="mb-2">{{ __('gigs.applications.my_applications') }}</h4>
            <p class="text-muted mb-0">{{ __('gigs.applications.my_applications_description') }}</p>
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
                            <i class="ph ph-paper-plane-tilt f-s-18 text-primary"></i>
                        </div>
                        <span class="ripple-effect"></span>
                        <div class="overflow-hidden">
                            <h4 class="text-primary mb-1 f-w-600">{{ number_format($stats['total_applications']) }}</h4>
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
                    <button wire:click="$set('status_filter', 'withdrawn')" class="btn btn-sm {{ $status_filter === 'withdrawn' ? 'btn-secondary' : 'btn-light-secondary' }}">
                        {{ __('gigs.applications.withdrawn') }}
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
                                <div class="col-12 col-md-8">
                                    {{-- Status --}}
                                    <div class="mb-2">
                                        @if($application->status === 'pending')
                                            <span class="badge bg-light-warning text-warning">{{ __('gigs.applications.pending') }}</span>
                                        @elseif($application->status === 'accepted')
                                            <span class="badge bg-light-success text-success">{{ __('gigs.applications.accepted') }}</span>
                                        @elseif($application->status === 'rejected')
                                            <span class="badge bg-light-danger text-danger">{{ __('gigs.applications.rejected') }}</span>
                                        @elseif($application->status === 'withdrawn')
                                            <span class="badge bg-light-secondary text-secondary">{{ __('gigs.applications.withdrawn') }}</span>
                                        @endif
                                    </div>

                                    {{-- Gig Title --}}
                                    <h5 class="mb-2">{{ $application->gig->title }}</h5>

                                    {{-- Message --}}
                                    <p class="text-muted f-s-14 mb-3">{{ Str::limit($application->message, 150) }}</p>

                                    {{-- Meta --}}
                                    <div class="d-flex flex-wrap gap-3 f-s-13 text-muted">
                                        @if($application->gig->location)
                                            <div><i class="ph ph-map-pin me-1"></i>{{ $application->gig->location }}</div>
                                        @endif
                                        <div><i class="ph ph-calendar me-1"></i>{{ $application->created_at->format('d/m/Y') }}</div>
                                        @if($application->gig->compensation)
                                            <div class="text-success"><i class="ph ph-currency-euro me-1"></i>{{ $application->gig->compensation }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <div class="d-flex flex-column gap-2 h-100 justify-content-center">
                                        <a href="{{ route('gigs.show', $application->gig) }}" class="btn btn-light-primary w-100">
                                            <i class="ph ph-eye me-2"></i>{{ __('gigs.view_gig') }}
                                        </a>
                                        @if($application->status === 'pending')
                                            <button onclick="confirm('{{ __('gigs.applications.confirm_withdraw') }}') || event.stopImmediatePropagation()" 
                                                    wire:click="withdrawApplication({{ $application->id }})" 
                                                    class="btn btn-light-danger w-100">
                                                <i class="ph ph-x me-2"></i>{{ __('gigs.applications.withdraw') }}
                                            </button>
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
                            <i class="ph ph-paper-plane-tilt f-s-48 text-muted mb-3 d-block"></i>
                            <h5 class="text-muted">{{ __('gigs.messages.no_my_applications') }}</h5>
                            <p class="text-muted mb-3">{{ __('gigs.messages.no_my_applications_description') }}</p>
                            <a href="{{ route('gigs.index') }}" class="btn btn-primary">
                                <i class="ph ph-magnifying-glass me-2"></i>{{ __('gigs.browse_gigs') }}
                            </a>
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

