<div class="page-content">
    <div class="container-fluid">
        
        {{-- Back Button --}}
        <div class="mb-3">
            <a href="{{ route('gigs.index') }}" class="btn btn-light-secondary btn-sm">
                <i class="ph ph-arrow-left me-2"></i>{{ __('gigs.back_to_list') }}
            </a>
        </div>

        {{-- Flash Messages --}}
        @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="ph ph-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="ph ph-warning me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-3">
            {{-- Main Content --}}
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-body">
                        {{-- Status Badges --}}
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @if($gig->is_closed)
                                <span class="badge bg-light-danger text-danger">
                                    <i class="ph ph-x-circle me-1"></i>{{ __('gigs.status.closed') }}
                                </span>
                            @elseif($gig->is_expired)
                                <span class="badge bg-light-secondary text-secondary">
                                    <i class="ph ph-clock me-1"></i>{{ __('gigs.status.expired') }}
                                </span>
                            @else
                                <span class="badge bg-light-success text-success">
                                    <i class="ph ph-check-circle me-1"></i>{{ __('gigs.status.open') }}
                                </span>
                            @endif
                            
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
                                <span class="badge bg-light-primary text-primary">
                                    <i class="ph ph-laptop me-1"></i>{{ __('gigs.remote') }}
                                </span>
                            @endif
                        </div>

                        {{-- Title --}}
                        <h3 class="mb-3">{{ $gig->title }}</h3>

                        {{-- Description --}}
                        <div class="mb-4">
                            <h5 class="mb-2">{{ __('gigs.fields.description') }}</h5>
                            <p class="text-muted">{!! nl2br(e($gig->description)) !!}</p>
                        </div>

                        {{-- Requirements --}}
                        @if($gig->requirements)
                            <div class="mb-4">
                                <h5 class="mb-2">{{ __('gigs.fields.requirements') }}</h5>
                                <p class="text-muted">{!! nl2br(e($gig->requirements)) !!}</p>
                            </div>
                        @endif

                        {{-- Application Form --}}
                        @auth
                            @if(!auth()->user()->hasRole('audience') && $gig->canUserApply(auth()->user()) && !$userApplication)
                                @if($showApplicationForm)
                                    <div class="card bg-light-primary border-0 mb-4">
                                        <div class="card-body">
                                            <h5 class="mb-3">{{ __('gigs.applications.apply') }}</h5>
                                            
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('gigs.applications.message') }} *</label>
                                                <textarea wire:model="applicationMessage" class="form-control" rows="4" placeholder="{{ __('gigs.applications.message_placeholder') }}"></textarea>
                                                @error('applicationMessage') <span class="text-danger f-s-13">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">{{ __('gigs.applications.experience') }}</label>
                                                <textarea wire:model="applicationExperience" class="form-control" rows="3" placeholder="{{ __('gigs.applications.experience_placeholder') }}"></textarea>
                                                @error('applicationExperience') <span class="text-danger f-s-13">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">{{ __('gigs.applications.portfolio') }}</label>
                                                <input type="url" wire:model="applicationPortfolio" class="form-control" placeholder="{{ __('gigs.applications.portfolio_placeholder') }}">
                                                @error('applicationPortfolio') <span class="text-danger f-s-13">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="d-flex gap-2">
                                                <button wire:click="submitApplication" class="btn btn-primary">
                                                    <i class="ph ph-paper-plane-tilt me-2"></i>{{ __('gigs.applications.submit_application') }}
                                                </button>
                                                <button wire:click="toggleApplicationForm" class="btn btn-light-secondary">
                                                    {{ __('gigs.cancel') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <button wire:click="toggleApplicationForm" class="btn btn-primary mb-4">
                                        <i class="ph ph-paper-plane-tilt me-2"></i>{{ __('gigs.apply_gig') }}
                                    </button>
                                @endif
                            @endif

                            @if($userApplication)
                                <div class="alert alert-info">
                                    <i class="ph ph-info me-2"></i>{{ __('gigs.applications.already_applied') }}
                                    <span class="badge bg-light-info text-info ms-2">{{ __('gigs.applications.' . $userApplication->status) }}</span>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-warning">
                                <i class="ph ph-warning me-2"></i>{{ __('gigs.messages.login_to_apply') }}
                            </div>
                        @endauth

                    </div>
                </div>

                {{-- Owner Actions --}}
                @auth
                    @if($gig->canBeEditedBy(auth()->user()))
                        <div class="card mt-3">
                            <div class="card-body">
                                <h5 class="mb-3">{{ __('gigs.owner_actions') }}</h5>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('gigs.edit', $gig) }}" class="btn btn-light-primary">
                                        <i class="ph ph-pencil me-2"></i>{{ __('gigs.edit_gig') }}
                                    </a>
                                    <a href="{{ route('gigs.manage-applications', $gig) }}" class="btn btn-light-info">
                                        <i class="ph ph-users me-2"></i>{{ __('gigs.applications.manage_applications') }}
                                        @if($gig->pendingApplications()->count() > 0)
                                            <span class="badge bg-info ms-2">{{ $gig->pendingApplications()->count() }}</span>
                                        @endif
                                    </a>
                                    @if($gig->is_closed)
                                        <button wire:click="reopenGig" class="btn btn-light-success">
                                            <i class="ph ph-arrow-counter-clockwise me-2"></i>{{ __('gigs.actions.reopen_gig') }}
                                        </button>
                                    @else
                                        <button wire:click="closeGig" class="btn btn-light-warning">
                                            <i class="ph ph-x-circle me-2"></i>{{ __('gigs.actions.close_gig') }}
                                        </button>
                                    @endif
                                    <button wire:click="shareGig" class="btn btn-light-secondary">
                                        <i class="ph ph-share-network me-2"></i>{{ __('gigs.actions.share') }}
                                    </button>
                                    <button onclick="confirm('{{ __('gigs.confirm_delete') }}') || event.stopImmediatePropagation()" wire:click="deleteGig" class="btn btn-light-danger">
                                        <i class="ph ph-trash me-2"></i>{{ __('gigs.delete') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>

            {{-- Sidebar --}}
            <div class="col-12 col-lg-4">
                {{-- Info Card --}}
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="mb-3">{{ __('gigs.gig_info') }}</h5>
                        
                        <div class="d-flex flex-column gap-3">
                            @if($gig->category)
                                <div>
                                    <div class="text-muted f-s-13 mb-1">{{ __('gigs.fields.category') }}</div>
                                    <div>{{ __('gigs.categories.' . $gig->category) }}</div>
                                </div>
                            @endif

                            @if($gig->type)
                                <div>
                                    <div class="text-muted f-s-13 mb-1">{{ __('gigs.fields.type') }}</div>
                                    <div>{{ __('gigs.types.' . $gig->type) }}</div>
                                </div>
                            @endif

                            @if($gig->language)
                                <div>
                                    <div class="text-muted f-s-13 mb-1">{{ __('gigs.fields.language') }}</div>
                                    <div>{{ __('gigs.languages.' . $gig->language) }}</div>
                                </div>
                            @endif

                            @if($gig->location)
                                <div>
                                    <div class="text-muted f-s-13 mb-1">{{ __('gigs.fields.location') }}</div>
                                    <div><i class="ph ph-map-pin me-1"></i>{{ $gig->location }}</div>
                                </div>
                            @endif

                            @if($gig->compensation)
                                <div>
                                    <div class="text-muted f-s-13 mb-1">{{ __('gigs.fields.compensation') }}</div>
                                    <div class="text-success"><i class="ph ph-currency-euro me-1"></i>{{ $gig->compensation }}</div>
                                </div>
                            @endif

                            <div>
                                <div class="text-muted f-s-13 mb-1">{{ __('gigs.fields.deadline') }}</div>
                                <div><i class="ph ph-calendar me-1"></i>{{ $gig->deadline->format('d/m/Y H:i') }}</div>
                            </div>

                            @if($gig->max_applications)
                                <div>
                                    <div class="text-muted f-s-13 mb-1">{{ __('gigs.applications.max_positions') }}</div>
                                    <div>{{ $gig->application_count }} / {{ $gig->max_applications }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Organizer Card --}}
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="mb-3">{{ __('gigs.organizer') }}</h5>
                        
                        <div class="d-flex align-items-center gap-3 mb-3">
                            @if($gig->user->avatar)
                                <img src="{{ $gig->user->avatar }}" class="rounded-circle" style="width: 48px; height: 48px;">
                            @else
                                <div class="bg-light-primary rounded-circle d-flex-center" style="width: 48px; height: 48px;">
                                    <i class="ph ph-user f-s-20 text-primary"></i>
                                </div>
                            @endif
                            <div>
                                <div class="f-w-600">{{ $gig->user->name }}</div>
                                @if($gig->user->nickname)
                                    <div class="text-muted f-s-13">{{ '@' . $gig->user->nickname }}</div>
                                @endif
                            </div>
                        </div>

                        <a href="{{ route('user.show', $gig->user->id) }}" class="btn btn-light-primary w-100">
                            {{ __('gigs.view_profile') }}
                        </a>
                    </div>
                </div>

                {{-- Event/Group Card --}}
                @if($gig->event || $gig->group)
                    <div class="card">
                        <div class="card-body">
                            @if($gig->event)
                                <h5 class="mb-2">{{ __('gigs.related_event') }}</h5>
                                <p class="mb-2">{{ $gig->event->title }}</p>
                                <a href="{{ route('events.show', $gig->event) }}" class="btn btn-light-primary w-100">
                                    {{ __('gigs.view_event') }}
                                </a>
                            @endif

                            @if($gig->group)
                                <h5 class="mb-2 {{ $gig->event ? 'mt-3' : '' }}">{{ __('gigs.related_group') }}</h5>
                                <p class="mb-2">{{ $gig->group->name }}</p>
                                <a href="{{ route('groups.show', $gig->group) }}" class="btn btn-light-primary w-100">
                                    {{ __('gigs.view_group') }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
