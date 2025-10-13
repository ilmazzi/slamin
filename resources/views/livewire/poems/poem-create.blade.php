<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">{{ __('poems.create.title') }}</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="ph ph-pen-nib text-primary me-2"></i>
                        {{ __('poems.create.subtitle') }}
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Success/Error Messages -->
                    @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="ti ti-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="ti ti-alert-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form wire:submit.prevent="save">
                        <div class="row">
                            <!-- Titolo -->
                            <div class="col-12 mb-3">
                                <label for="title" class="form-label">
                                    {{ __('poems.create.title_label') }} <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       id="title"
                                       class="form-control @error('title') is-invalid @enderror" 
                                       wire:model="title"
                                       placeholder="{{ __('poems.create.title_placeholder') }}">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Categoria e Lingua -->
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">
                                    {{ __('poems.create.category_label') }} <span class="text-danger">*</span>
                                </label>
                                <select id="category_id" 
                                        class="form-select @error('category_id') is-invalid @enderror"
                                        wire:model="category_id">
                                    <option value="">{{ __('poems.create.category_placeholder') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="language_id" class="form-label">
                                    {{ __('poems.create.language_label') }} <span class="text-danger">*</span>
                                </label>
                                <select id="language_id" 
                                        class="form-select @error('language_id') is-invalid @enderror"
                                        wire:model="language_id">
                                    <option value="">{{ __('poems.create.language_placeholder') }}</option>
                                    @foreach($languages as $language)
                                        <option value="{{ $language->id }}">{{ $language->name }}</option>
                                    @endforeach
                                </select>
                                @error('language_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contenuto -->
                            <div class="col-12 mb-3">
                                <label for="content" class="form-label">
                                    {{ __('poems.create.content_label') }} <span class="text-danger">*</span>
                                </label>
                                
                                <!-- Quill Editor Component -->
                                <livewire:components.quill-editor 
                                    wire:model="content"
                                    placeholder="{{ __('poems.create.content_placeholder') }}"
                                    height="300px"
                                    toolbar="poetry" />
                                
                                @error('content')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Immagine di copertina -->
                            <div class="col-12 mb-3">
                                <label for="featured_image" class="form-label">
                                    {{ __('poems.create.featured_image_label') }}
                                </label>
                                <input type="file" 
                                       id="featured_image"
                                       class="form-control @error('featured_image') is-invalid @enderror"
                                       wire:model="featured_image"
                                       accept="image/*">
                                
                                @if($featured_image)
                                    <div class="mt-2">
                                        <small class="text-muted">{{ __('poems.create.image_preview') }}:</small>
                                        <div class="mt-1">
                                            <img src="{{ $featured_image->temporaryUrl() }}" 
                                                 alt="Preview" 
                                                 class="img-thumbnail" 
                                                 style="max-width: 200px; max-height: 200px;">
                                        </div>
                                    </div>
                                @endif
                                
                                @error('featured_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tags -->
                            <div class="col-12 mb-4">
                                <label for="tags" class="form-label">
                                    {{ __('poems.create.tags_label') }}
                                </label>
                                <input type="text" 
                                       id="tags"
                                       class="form-control @error('tags') is-invalid @enderror"
                                       wire:model="tags"
                                       placeholder="{{ __('poems.create.tags_placeholder') }}">
                                <small class="form-text text-muted">
                                    {{ __('poems.create.tags_help') }}
                                </small>
                                @error('tags')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="button" 
                                    class="btn btn-light-secondary"
                                    wire:click="saveDraft"
                                    wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="saveDraft">
                                    <i class="ti ti-file-text me-1"></i>
                                    {{ __('poems.create.save_draft') }}
                                </span>
                                <span wire:loading wire:target="saveDraft">
                                    <div class="spinner-border spinner-border-sm me-1"></div>
                                    {{ __('poems.create.saving') }}
                                </span>
                            </button>

                            <button type="submit" 
                                    class="btn btn-primary"
                                    wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="save">
                                    <i class="ti ti-send me-1"></i>
                                    {{ __('poems.create.publish') }}
                                </span>
                                <span wire:loading wire:target="save">
                                    <div class="spinner-border spinner-border-sm me-1"></div>
                                    {{ __('poems.create.publishing') }}
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-save draft functionality (optional)
document.addEventListener('livewire:init', () => {
    let autoSaveTimeout;
    
    Livewire.on('content-updated', () => {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(() => {
            // Auto-save as draft every 30 seconds
            Livewire.dispatch('auto-save-draft');
        }, 30000);
    });
});
</script>
@endpush
