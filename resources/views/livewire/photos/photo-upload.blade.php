<div>
    <div class="container-fluid">
        <!-- Header -->
        <div class="page-title-box">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="page-title mb-0">
                    <i class="ph ph-image me-2"></i>{{ __('photos.upload_photo') }}
                </h4>
                <a href="{{ route('photos.index') }}" class="btn btn-light">
                    <i class="ph ph-arrow-left me-1"></i>{{ __('media.back') }}
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <form wire:submit.prevent="save">
                    <!-- Upload Zone -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="upload-zone" 
                                 x-data="{ 
                                     isDragging: false,
                                     handleDragOver(e) { e.preventDefault(); this.isDragging = true; },
                                     handleDragLeave(e) { e.preventDefault(); this.isDragging = false; },
                                     handleDrop(e) { 
                                         e.preventDefault(); 
                                         this.isDragging = false; 
                                         if (e.dataTransfer.files.length > 0) {
                                             @this.set('photo', e.dataTransfer.files[0]);
                                         }
                                     }
                                 }"
                                 @dragover="handleDragOver"
                                 @dragleave="handleDragLeave"
                                 @drop="handleDrop"
                                 :class="{ 'dragover': isDragging }"
                                 onclick="document.getElementById('photoInput').click()"
                                 style="border: 2px dashed #dee2e6; border-radius: 8px; padding: 40px; text-align: center; transition: all 0.3s; cursor: pointer;"
                                 wire:loading.class="opacity-50"
                                 wire:target="photo">
                                
                                <input type="file" 
                                       wire:model="photo" 
                                       accept="image/*" 
                                       class="d-none"
                                       id="photoInput">
                                
                                @if($showPreview && $previewUrl)
                                    <!-- Preview Image -->
                                    <div class="mb-3">
                                        <img src="{{ $previewUrl }}" 
                                             alt="Preview" 
                                             class="img-fluid rounded"
                                             style="max-height: 300px; max-width: 100%;">
                                    </div>
                                    <button type="button" 
                                            wire:click="removeImage"
                                            class="btn btn-outline-danger btn-sm">
                                        <i class="ph ph-x me-1"></i>{{ __('media.remove_image') }}
                                    </button>
                                @else
                                    <!-- Upload Icon and Text -->
                                    <div class="mt-3">
                                        <i class="ph ph-image text-primary" style="font-size: 64px;"></i>
                                        <h5 class="mt-3">{{ __('photos.click_or_drag') }}</h5>
                                        <p class="text-secondary mb-0">
                                            {{ __('photos.supported_formats') }}: JPG, PNG, GIF, WebP
                                            <br>
                                            {{ __('photos.max_size') }}: 10MB
                                        </p>
                                    </div>
                                @endif
                            </div>

                            @error('photo') 
                                <div class="text-danger mt-2">
                                    <i class="ph ph-warning me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Photo Details Form -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="ph ph-info me-2"></i>{{ __('media.photo_details') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label">{{ __('media.title_optional') }}</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="title"
                                           wire:model="title"
                                           placeholder="{{ __('media.title_placeholder') }}">
                                    @error('title') 
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="alt_text" class="form-label">{{ __('media.alt_text_optional') }}</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="alt_text"
                                           wire:model="alt_text"
                                           placeholder="{{ __('media.alt_text_placeholder') }}">
                                    @error('alt_text') 
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">{{ __('media.description_optional') }}</label>
                                <textarea class="form-control" 
                                          id="description"
                                          wire:model="description"
                                          rows="3"
                                          placeholder="{{ __('media.description_placeholder') }}"></textarea>
                                @error('description') 
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Upload Progress -->
                    @if($isUploading)
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ph ph-upload text-primary me-2"></i>
                                    <span class="fw-medium">{{ __('media.uploading') }}</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                         role="progressbar" 
                                         style="width: {{ $uploadProgress }}%"
                                         aria-valuenow="{{ $uploadProgress }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                <small class="text-muted mt-1 d-block">{{ $uploadProgress }}% {{ __('media.complete') }}</small>
                            </div>
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('photos.index') }}" class="btn btn-light">
                            <i class="ph ph-arrow-left me-1"></i>Annulla
                        </a>
                        
                        <button type="submit" 
                                class="btn btn-primary"
                                wire:loading.attr="disabled"
                                wire:target="save"
                                @if(!$photo) disabled @endif>
                            <span wire:loading.remove wire:target="save">
                                <i class="ph ph-upload me-1"></i>{{ __('media.upload_photo_button') }}
                            </span>
                            <span wire:loading wire:target="save">
                                <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                                Caricamento...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ph ph-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ph ph-warning me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif
    </div>

    <style>
    .upload-zone:hover {
        border-color: var(--primary-color) !important;
        background-color: rgba(var(--primary-rgb), 0.05);
    }

    .upload-zone.dragover {
        border-color: var(--primary-color) !important;
        background-color: rgba(var(--primary-rgb), 0.1);
        transform: scale(1.02);
    }

    .progress {
        border-radius: 4px;
    }

    .progress-bar {
        transition: width 0.3s ease;
    }

    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
    }

    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        transition: box-shadow 0.15s ease-in-out;
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
    }
    </style>
</div>