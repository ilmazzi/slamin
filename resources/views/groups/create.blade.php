@extends('layout.master')

@section('title', __('groups.create_group'))

@section('main-content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="ph-duotone ph-plus-circle me-2 text-primary"></i>
                        {{ __('groups.create_group') }}
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('groups.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Nome del gruppo -->
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="ph-duotone ph-tag me-1"></i>
                                {{ __('groups.name') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   placeholder="{{ __('groups.group_name_placeholder') }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Descrizione -->
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="ph-duotone ph-text-aa me-1"></i>
                                {{ __('groups.description') }}
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4"
                                      placeholder="{{ __('groups.group_description_placeholder') }}">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Immagine del gruppo -->
                        <div class="mb-3">
                            <label for="image" class="form-label">
                                <i class="ph-duotone ph-image me-1"></i>
                                {{ __('groups.image') }}
                            </label>
                            <input type="file" 
                                   class="form-control @error('image') is-invalid @enderror" 
                                   id="image" 
                                   name="image"
                                   accept="image/*">
                            <div class="form-text">
                                {{ __('common.image_help_text') }}
                            </div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Visibilità -->
                        <div class="mb-4">
                            <label for="visibility" class="form-label">
                                <i class="ph-duotone ph-eye me-1"></i>
                                {{ __('groups.visibility') }} <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('visibility') is-invalid @enderror" 
                                    id="visibility" 
                                    name="visibility" 
                                    required>
                                <option value="">{{ __('common.select_option') }}</option>
                                <option value="public" {{ old('visibility') == 'public' ? 'selected' : '' }}>
                                    {{ __('groups.visibility_public') }}
                                </option>
                                <option value="private" {{ old('visibility') == 'private' ? 'selected' : '' }}>
                                    {{ __('groups.visibility_private') }}
                                </option>
                            </select>
                            <div class="form-text">
                                <strong>{{ __('groups.visibility_public') }}:</strong> 
                                {{ __('groups.tips.public_visibility') }}
                            </div>
                            <div class="form-text">
                                <strong>{{ __('groups.visibility_private') }}:</strong> 
                                {{ __('groups.tips.private_visibility') }}
                            </div>
                            @error('visibility')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pulsanti -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ph-duotone ph-check me-2"></i>
                                {{ __('groups.create') }}
                            </button>
                            <a href="{{ route('groups.index') }}" class="btn btn-light">
                                <i class="ph-duotone ph-arrow-left me-2"></i>
                                {{ __('common.cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Suggerimenti -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph-duotone ph-lightbulb me-2 text-warning"></i>
                        {{ __('common.tips') }}
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="ph-duotone ph-check-circle text-success me-2"></i>
                            {{ __('groups.tips.create_group') }}
                        </li>
                        <li class="mb-2">
                            <i class="ph-duotone ph-check-circle text-success me-2"></i>
                            {{ __('groups.tips.invite_members') }}
                        </li>
                        <li class="mb-2">
                            <i class="ph-duotone ph-check-circle text-success me-2"></i>
                            {{ __('groups.tips.manage_permissions') }}
                        </li>
                        <li>
                            <i class="ph-duotone ph-check-circle text-success me-2"></i>
                            {{ __('groups.tips.group_events') }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 