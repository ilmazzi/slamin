<div class="page-content">
    <div class="container-fluid">
        
        <div class="page-title-box">
            <h4>{{ __('gigs.create_gig') }}</h4>
            <p class="text-muted">{{ __('gigs.create_description') }}</p>
        </div>

        <form wire:submit="save">
            <div class="row g-3">
                
                {{-- Main Content --}}
                <div class="col-12 col-lg-8">
                    
                    {{-- Basic Info --}}
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="mb-3">{{ __('gigs.basic_info') }}</h5>
                            
                            <div class="mb-3">
                                <label class="form-label">{{ __('gigs.fields.title') }} *</label>
                                <input type="text" wire:model="title" class="form-control" placeholder="{{ __('gigs.placeholders.title') }}">
                                @error('title') <span class="text-danger f-s-13">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('gigs.fields.description') }} *</label>
                                <textarea wire:model="description" class="form-control" rows="6" placeholder="{{ __('gigs.placeholders.description') }}"></textarea>
                                <small class="text-muted">{{ __('gigs.help.description') }}</small>
                                @error('description') <span class="text-danger f-s-13 d-block">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('gigs.fields.requirements') }}</label>
                                <textarea wire:model="requirements" class="form-control" rows="4" placeholder="{{ __('gigs.placeholders.requirements') }}"></textarea>
                                <small class="text-muted">{{ __('gigs.help.requirements') }}</small>
                                @error('requirements') <span class="text-danger f-s-13 d-block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Details --}}
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="mb-3">{{ __('gigs.gig_details') }}</h5>
                            
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label">{{ __('gigs.fields.category') }} *</label>
                                    <select wire:model="category" class="form-select">
                                        <option value="">{{ __('gigs.select_category') }}</option>
                                        <option value="performance">{{ __('gigs.categories.performance') }}</option>
                                        <option value="hosting">{{ __('gigs.categories.hosting') }}</option>
                                        <option value="judging">{{ __('gigs.categories.judging') }}</option>
                                        <option value="technical">{{ __('gigs.categories.technical') }}</option>
                                        <option value="translation">{{ __('gigs.categories.translation') }}</option>
                                        <option value="other">{{ __('gigs.categories.other') }}</option>
                                    </select>
                                    @error('category') <span class="text-danger f-s-13">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">{{ __('gigs.fields.type') }} *</label>
                                    <select wire:model="type" class="form-select">
                                        <option value="">{{ __('gigs.select_type') }}</option>
                                        <option value="paid">{{ __('gigs.types.paid') }}</option>
                                        <option value="volunteer">{{ __('gigs.types.volunteer') }}</option>
                                        <option value="collaboration">{{ __('gigs.types.collaboration') }}</option>
                                    </select>
                                    @error('type') <span class="text-danger f-s-13">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">{{ __('gigs.fields.language') }}</label>
                                    <select wire:model="language" class="form-select">
                                        <option value="">{{ __('gigs.select_language') }}</option>
                                        <option value="it">{{ __('gigs.languages.italian') }}</option>
                                        <option value="en">{{ __('gigs.languages.english') }}</option>
                                        <option value="es">{{ __('gigs.languages.spanish') }}</option>
                                        <option value="fr">{{ __('gigs.languages.french') }}</option>
                                        <option value="de">{{ __('gigs.languages.german') }}</option>
                                        <option value="pt">{{ __('gigs.languages.portuguese') }}</option>
                                    </select>
                                    @error('language') <span class="text-danger f-s-13">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">{{ __('gigs.fields.deadline') }} *</label>
                                    <input type="datetime-local" wire:model="deadline" class="form-control">
                                    <small class="text-muted">{{ __('gigs.help.deadline') }}</small>
                                    @error('deadline') <span class="text-danger f-s-13 d-block">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">{{ __('gigs.fields.compensation') }}</label>
                                    <input type="text" wire:model="compensation" class="form-control" placeholder="{{ __('gigs.placeholders.compensation') }}">
                                    <small class="text-muted">{{ __('gigs.help.compensation') }}</small>
                                    @error('compensation') <span class="text-danger f-s-13 d-block">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">{{ __('gigs.fields.max_applications') }}</label>
                                    <input type="number" wire:model="max_applications" class="form-control" min="1" max="100" placeholder="{{ __('gigs.unlimited') }}">
                                    <small class="text-muted">{{ __('gigs.help.max_applications') }}</small>
                                    @error('max_applications') <span class="text-danger f-s-13 d-block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Location --}}
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="mb-3">{{ __('gigs.location_info') }}</h5>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" wire:model.live="is_remote" class="form-check-input" id="is_remote">
                                    <label class="form-check-label" for="is_remote">
                                        {{ __('gigs.fields.is_remote') }}
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-1">{{ __('gigs.help.is_remote') }}</small>
                            </div>

                            @if(!$is_remote)
                                <div>
                                    <label class="form-label">{{ __('gigs.fields.location') }}</label>
                                    <input type="text" wire:model="location" class="form-control" placeholder="{{ __('gigs.placeholders.location') }}">
                                    <small class="text-muted">{{ __('gigs.help.location') }}</small>
                                    @error('location') <span class="text-danger f-s-13 d-block">{{ $message }}</span> @enderror
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- Sidebar --}}
                <div class="col-12 col-lg-4">
                    
                    {{-- Options --}}
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="mb-3">{{ __('gigs.options') }}</h5>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" wire:model="is_urgent" class="form-check-input" id="is_urgent">
                                    <label class="form-check-label" for="is_urgent">
                                        {{ __('gigs.fields.is_urgent') }}
                                    </label>
                                </div>
                                <small class="text-muted d-block mt-1">{{ __('gigs.help.is_urgent') }}</small>
                            </div>

                            @if(auth()->user()->hasRole('admin'))
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" wire:model="is_featured" class="form-check-input" id="is_featured">
                                        <label class="form-check-label" for="is_featured">
                                            {{ __('gigs.fields.is_featured') }}
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-1">{{ __('gigs.help.is_featured') }}</small>
                                </div>
                            @endif

                            @if($group_id)
                                <div>
                                    <div class="form-check">
                                        <input type="checkbox" wire:model="allow_group_admin_edit" class="form-check-input" id="allow_group_admin_edit">
                                        <label class="form-check-label" for="allow_group_admin_edit">
                                            {{ __('gigs.fields.allow_group_admin_edit') }}
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-1">{{ __('gigs.help.allow_group_admin_edit') }}</small>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Event/Group --}}
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="mb-3">{{ __('gigs.associations') }}</h5>
                            
                            @if($events->count() > 0)
                                <div class="mb-3">
                                    <label class="form-label">{{ __('gigs.fields.event') }}</label>
                                    <select wire:model.live="event_id" class="form-select">
                                        <option value="">{{ __('gigs.select_event') }}</option>
                                        @foreach($events as $event)
                                            <option value="{{ $event->id }}">{{ $event->title }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">{{ __('gigs.help.event') }}</small>
                                    @error('event_id') <span class="text-danger f-s-13 d-block">{{ $message }}</span> @enderror
                                </div>
                            @endif

                            @if($groups->count() > 0)
                                <div>
                                    <label class="form-label">{{ __('gigs.fields.group') }}</label>
                                    <select wire:model.live="group_id" class="form-select">
                                        <option value="">{{ __('gigs.select_group') }}</option>
                                        @foreach($groups as $group)
                                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">{{ __('gigs.help.group') }}</small>
                                    @error('group_id') <span class="text-danger f-s-13 d-block">{{ $message }}</span> @enderror
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="card">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary w-100 mb-2">
                                <i class="ph ph-check me-2"></i>{{ __('gigs.create_gig') }}
                            </button>
                            <a href="{{ route('gigs.index') }}" class="btn btn-light-secondary w-100">
                                {{ __('gigs.cancel') }}
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </form>

    </div>
</div>
