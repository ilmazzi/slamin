@extends('layout.master')

@section('title', __('gigs.edit_gig'))

@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="page-title-box">
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0 small">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}" class="text-decoration-none">
                                <i class="ph ph-house me-1"></i>{{ __('common.home') }}
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('gigs.index') }}" class="text-decoration-none">
                                <i class="ph ph-briefcase me-1"></i>{{ __('gigs.title') }}
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('gigs.show', $gig) }}" class="text-decoration-none">
                                {{ $gig->title }}
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            {{ __('common.edit') }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">{{ __('gigs.edit_gig') }}</h4>

                        <form action="{{ route('gigs.update', $gig) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Titolo -->
                                <div class="col-md-12 mb-3">
                                    <label for="title" class="form-label">{{ __('gigs.fields.title') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                           id="title" name="title" value="{{ old('title', $gig->title) }}"
                                           placeholder="{{ __('gigs.placeholders.title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('gigs.help.title') }}</small>
                                </div>

                                <!-- Descrizione -->
                                <div class="col-md-12 mb-3">
                                    <label for="description" class="form-label">{{ __('gigs.fields.description') }} <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="4"
                                              placeholder="{{ __('gigs.placeholders.description') }}" required>{{ old('description', $gig->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('gigs.help.description') }}</small>
                                </div>

                                <!-- Requisiti -->
                                <div class="col-md-12 mb-3">
                                    <label for="requirements" class="form-label">{{ __('gigs.fields.requirements') }}</label>
                                    <textarea class="form-control @error('requirements') is-invalid @enderror"
                                              id="requirements" name="requirements" rows="3"
                                              placeholder="{{ __('gigs.placeholders.requirements') }}">{{ old('requirements', $gig->requirements) }}</textarea>
                                    @error('requirements')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('gigs.help.requirements') }}</small>
                                </div>

                                <!-- Compensazione e Scadenza -->
                                <div class="col-md-6 mb-3">
                                    <label for="compensation" class="form-label">{{ __('gigs.fields.compensation') }}</label>
                                    <input type="text" class="form-control @error('compensation') is-invalid @enderror"
                                           id="compensation" name="compensation" value="{{ old('compensation', $gig->compensation) }}"
                                           placeholder="{{ __('gigs.placeholders.compensation') }}">
                                    @error('compensation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('gigs.help.compensation') }}</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="deadline" class="form-label">{{ __('gigs.fields.deadline') }} <span class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control @error('deadline') is-invalid @enderror"
                                           id="deadline" name="deadline" value="{{ old('deadline', $gig->deadline ? $gig->deadline->format('Y-m-d\TH:i') : '') }}" required>
                                    @error('deadline')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('gigs.help.deadline') }}</small>
                                </div>

                                <!-- Località e Max Candidature -->
                                <div class="col-md-6 mb-3">
                                    <label for="location" class="form-label">{{ __('gigs.fields.location') }}</label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror"
                                           id="location" name="location" value="{{ old('location', $gig->location) }}"
                                           placeholder="{{ __('gigs.placeholders.location') }}">
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('gigs.help.location') }}</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="max_applications" class="form-label">{{ __('gigs.fields.max_applications') }}</label>
                                    <input type="number" class="form-control @error('max_applications') is-invalid @enderror"
                                           id="max_applications" name="max_applications" value="{{ old('max_applications', $gig->max_applications) }}"
                                           min="1" max="100">
                                    @error('max_applications')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('gigs.help.max_applications') }}</small>
                                </div>

                                <!-- Categoria e Tipo -->
                                <div class="col-md-6 mb-3">
                                    <label for="category" class="form-label">{{ __('gigs.fields.category') }} <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                        <option value="">{{ __('gigs.filters.select_category') }}</option>
                                        @foreach(__('gigs.categories') as $key => $category)
                                            <option value="{{ $key }}" {{ old('category', $gig->category) == $key ? 'selected' : '' }}>
                                                {{ $category }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="type" class="form-label">{{ __('gigs.fields.type') }} <span class="text-danger">*</span></label>
                                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">{{ __('gigs.filters.select_type') }}</option>
                                        @foreach($positions as $key => $type)
                                            <option value="{{ $key }}" {{ old('type', $gig->type) == $key ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Lingua -->
                                <div class="col-md-6 mb-3">
                                    <label for="language" class="form-label">{{ __('gigs.fields.language') }} <span class="text-danger">*</span></label>
                                    <select class="form-select @error('language') is-invalid @enderror" id="language" name="language" required>
                                        <option value="">{{ __('gigs.filters.select_language') }}</option>
                                        @foreach(__('gigs.languages') as $key => $language)
                                            <option value="{{ $key }}" {{ old('language', $gig->language) == $key ? 'selected' : '' }}>
                                                {{ $language }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('language')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Evento e Gruppo -->
                                <div class="col-md-6 mb-3">
                                    <label for="event_id" class="form-label">{{ __('gigs.fields.event') }}</label>
                                    <select class="form-select @error('event_id') is-invalid @enderror" id="event_id" name="event_id">
                                        <option value="">{{ __('gigs.filters.select_event') }}</option>
                                        @foreach($events as $event)
                                            <option value="{{ $event->id }}" {{ old('event_id', $gig->event_id) == $event->id ? 'selected' : '' }}>
                                                {{ $event->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('event_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('gigs.help.event') }}</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="group_id" class="form-label">{{ __('gigs.fields.group') }}</label>
                                    <select class="form-select @error('group_id') is-invalid @enderror" id="group_id" name="group_id">
                                        <option value="">{{ __('gigs.filters.select_group') }}</option>
                                        @foreach($groups as $group)
                                            <option value="{{ $group->id }}" {{ old('group_id', $gig->group_id) == $group->id ? 'selected' : '' }}>
                                                {{ $group->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('group_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">{{ __('gigs.help.group') }}</small>
                                </div>
                            </div>

                            <!-- Opzioni -->
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="mb-3">{{ __('gigs.create.publication_options') }}</h5>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_remote" name="is_remote"
                                               value="1" {{ old('is_remote', $gig->is_remote) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_remote">
                                            {{ __('gigs.fields.is_remote') }}
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">{{ __('gigs.help.is_remote') }}</small>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_urgent" name="is_urgent"
                                               value="1" {{ old('is_urgent', $gig->is_urgent) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_urgent">
                                            {{ __('gigs.fields.is_urgent') }}
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">{{ __('gigs.help.is_urgent') }}</small>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                               value="1" {{ old('is_featured', $gig->is_featured) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">
                                            {{ __('gigs.fields.is_featured') }}
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">{{ __('gigs.help.is_featured') }}</small>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="allow_group_admin_edit" name="allow_group_admin_edit"
                                               value="1" {{ old('allow_group_admin_edit', $gig->allow_group_admin_edit) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="allow_group_admin_edit">
                                            {{ __('gigs.fields.allow_group_admin_edit') }}
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">{{ __('gigs.help.allow_group_admin_edit') }}</small>
                                </div>
                            </div>

                            <!-- Azioni -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('gigs.show', $gig) }}" class="btn btn-secondary">
                                            <i class="ph ph-arrow-left me-2"></i>{{ __('common.cancel') }}
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ph ph-check me-2"></i>{{ __('gigs.edit_gig') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
