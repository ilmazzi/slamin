<div>
    <div class="container-fluid">
        <div class="page-title-box">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="page-title mb-0">
                    <i class="ph ph-image me-2"></i>{{ __('photos.upload_photo') }}
                </h4>
                <a href="{{ route('photos.index') }}" class="btn btn-light">
                    <i class="ph ph-arrow-left me-1"></i>{{ __('common.back') }}
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <form wire:submit.prevent="upload">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="ph ph-upload me-2"></i>{{ __('photos.upload_photo') }}
                            </h5>

                            <!-- File Input -->
                            <div class="mb-3">
                                <label for="image" class="form-label">
                                    {{ __('photos.select_image') }}
                                </label>
                                <input type="file" 
                                       class="form-control" 
                                       id="image" 
                                       wire:model="image" 
                                       accept="image/*">
                                @error('image')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image Preview -->
                            @if($image)
                                <div class="mb-3">
                                    <img src="{{ $image->temporaryUrl() }}" 
                                         alt="Preview" 
                                         class="img-fluid rounded" 
                                         style="max-height: 300px;">
                                </div>
                            @endif

                            <!-- Title -->
                            <div class="mb-3">
                                <label for="title" class="form-label">
                                    {{ __('photos.title') }} <span class="text-secondary">({{ __('common.optional') }})</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="title" 
                                       wire:model="title"
                                       placeholder="{{ __('photos.title_placeholder') }}">
                                @error('title')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    {{ __('photos.description') }} <span class="text-secondary">({{ __('common.optional') }})</span>
                                </label>
                                <textarea class="form-control" 
                                          id="description" 
                                          wire:model="description"
                                          rows="3"
                                          placeholder="{{ __('photos.description_placeholder') }}"></textarea>
                                @error('description')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="ph ph-upload me-2"></i>
                                    {{ __('photos.upload_button') }}
                                </button>
                            </div>
                        </div>
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
</div>



