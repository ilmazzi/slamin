@extends('layout.master')

@section('title', __('groups.edit_group') . ' - ' . $group->name)

@section('main-content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="ph-duotone ph-pencil me-2 text-warning"></i>
                        {{ __('groups.edit_group') }}: {{ $group->name }}
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('groups.update', $group) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

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
                                   value="{{ old('name', $group->name) }}"
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
                                      placeholder="{{ __('groups.group_description_placeholder') }}">{{ old('description', $group->description) }}</textarea>
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
                            
                            <!-- Immagine attuale -->
                            @if($group->image)
                            <div class="mb-3">
                                <label class="form-label">{{ __('common.current_image') }}:</label>
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('storage/' . $group->image) }}" 
                                         alt="{{ $group->name }}" 
                                         class="rounded me-3" 
                                         style="width: 80px; height: 80px; object-fit: cover;">
                                    <div>
                                        <button type="button" class="btn btn-outline-danger btn-sm" 
                                                onclick="document.getElementById('remove_image').value = '1'; this.parentElement.parentElement.style.display = 'none';">
                                            <i class="ph-duotone ph-trash me-1"></i>
                                            {{ __('common.remove_image') }}
                                        </button>
                                        <input type="hidden" id="remove_image" name="remove_image" value="0">
                                    </div>
                                </div>
                            </div>
                            @endif

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
                                <option value="public" {{ old('visibility', $group->visibility) == 'public' ? 'selected' : '' }}>
                                    {{ __('groups.visibility_public') }}
                                </option>
                                <option value="private" {{ old('visibility', $group->visibility) == 'private' ? 'selected' : '' }}>
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
                            <button type="submit" class="btn btn-warning">
                                <i class="ph-duotone ph-check me-2"></i>
                                {{ __('groups.edit') }}
                            </button>
                            <a href="{{ route('groups.show', $group) }}" class="btn btn-light">
                                <i class="ph-duotone ph-arrow-left me-2"></i>
                                {{ __('common.cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Azioni pericolose -->
            @if($group->hasAdmin(auth()->user()) || auth()->user()->hasRole('admin'))
            <div class="card mt-4 border-danger">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0">
                        <i class="ph-duotone ph-warning me-2"></i>
                        {{ __('common.danger_zone') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-danger mb-1">{{ __('groups.delete') }}</h6>
                            <p class="text-muted mb-0">
                                {{ __('groups.delete_warning') }}
                            </p>
                        </div>
                        <button type="button" 
                                class="btn btn-outline-danger" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteGroupModal">
                            <i class="ph-duotone ph-trash me-2"></i>
                            {{ __('groups.delete') }}
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal di conferma eliminazione -->
@if($group->hasAdmin(auth()->user()) || auth()->user()->hasRole('admin'))
<div class="modal fade" id="deleteGroupModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="ph-duotone ph-warning me-2"></i>
                    {{ __('groups.confirm_delete') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('groups.delete_confirmation_text') }}</p>
                <ul class="text-muted">
                    <li>{{ __('groups.delete_confirmation_members') }}</li>
                    <li>{{ __('groups.delete_confirmation_events') }}</li>
                    <li>{{ __('groups.delete_confirmation_invitations') }}</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    {{ __('common.cancel') }}
                </button>
                <form action="{{ route('groups.destroy', $group) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="ph-duotone ph-trash me-2"></i>
                        {{ __('groups.delete') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection 