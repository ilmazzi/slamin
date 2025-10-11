@extends('layout.master')

@section('title', __('photos.upload_photo'))

@section('css')
<style>
.upload-zone {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 40px;
    text-align: center;
    transition: all 0.3s;
    cursor: pointer;
}
.upload-zone:hover,
.upload-zone.dragover {
    border-color: var(--primary-color);
    background-color: rgba(var(--primary-rgb), 0.05);
}
.preview-image {
    max-height: 400px;
    object-fit: contain;
}
</style>
@endsection

@section('main-content')
<div class="container-fluid">
    <!-- Header -->
    <div class="page-title-box">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="page-title mb-0">
                <i class="ph ph-image me-2"></i>{{ __('photos.upload_photo') }}
            </h4>
            <a href="{{ route('profile.show') }}" class="btn btn-light">
                <i class="ph ph-arrow-left me-1"></i>{{ __('common.back') }}
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form id="uploadPhotoForm" enctype="multipart/form-data">
                @csrf

                <!-- Upload Zone -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="upload-zone" id="uploadZone">
                            <input type="file" 
                                   id="photoInput" 
                                   name="image" 
                                   accept="image/*" 
                                   class="d-none"
                                   required>
                            
                            <div id="uploadPrompt">
                                <i class="ph ph-image text-primary" style="font-size: 64px;"></i>
                                <h5 class="mt-3">{{ __('photos.click_or_drag') }}</h5>
                                <p class="text-secondary mb-0">
                                    {{ __('photos.supported_formats') }}: JPG, PNG, GIF, WebP
                                    <br>
                                    {{ __('photos.max_size') }}: 10MB
                                </p>
                            </div>

                            <div id="imagePreview" class="d-none">
                                <img src="" alt="Preview" class="preview-image img-fluid rounded mb-3">
                                <button type="button" class="btn btn-light" onclick="resetUpload()">
                                    <i class="ph ph-arrow-counter-clockwise me-1"></i>{{ __('photos.change_image') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Photo Details -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="ph ph-info me-2"></i>{{ __('photos.photo_details') }}
                        </h5>

                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">
                                {{ __('photos.title') }} <span class="text-secondary">({{ __('common.optional') }})</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="title" 
                                   name="title"
                                   maxlength="255"
                                   placeholder="{{ __('photos.title_placeholder') }}">
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                {{ __('photos.description') }} <span class="text-secondary">({{ __('common.optional') }})</span>
                            </label>
                            <textarea class="form-control" 
                                      id="description" 
                                      name="description"
                                      rows="3"
                                      maxlength="1000"
                                      placeholder="{{ __('photos.description_placeholder') }}"></textarea>
                        </div>

                        <!-- Alt Text -->
                        <div class="mb-3">
                            <label for="alt_text" class="form-label">
                                {{ __('photos.alt_text') }} <span class="text-secondary">({{ __('common.optional') }})</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="alt_text" 
                                   name="alt_text"
                                   maxlength="255"
                                   placeholder="{{ __('photos.alt_text_placeholder') }}">
                            <small class="text-secondary">{{ __('photos.alt_text_help') }}</small>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                <i class="ph ph-upload me-2"></i>{{ __('photos.upload_button') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const uploadZone = document.getElementById('uploadZone');
    const photoInput = document.getElementById('photoInput');
    const uploadPrompt = document.getElementById('uploadPrompt');
    const imagePreview = document.getElementById('imagePreview');
    const form = document.getElementById('uploadPhotoForm');
    const submitBtn = document.getElementById('submitBtn');

    // Click to upload
    uploadZone.addEventListener('click', () => {
        if (!imagePreview.classList.contains('d-none')) return;
        photoInput.click();
    });

    // Drag & Drop
    uploadZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadZone.classList.add('dragover');
    });

    uploadZone.addEventListener('dragleave', () => {
        uploadZone.classList.remove('dragover');
    });

    uploadZone.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadZone.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            photoInput.files = files;
            previewImage(files[0]);
        }
    });

    // File input change
    photoInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            previewImage(e.target.files[0]);
        }
    });

    // Preview image
    function previewImage(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.querySelector('img').src = e.target.result;
            uploadPrompt.classList.add('d-none');
            imagePreview.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }

    // Reset upload
    window.resetUpload = function() {
        photoInput.value = '';
        imagePreview.querySelector('img').src = '';
        uploadPrompt.classList.remove('d-none');
        imagePreview.classList.add('d-none');
    };

    // Form submission
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (!photoInput.files.length) {
            Swal.fire({
                icon: 'warning',
                title: 'Attenzione',
                text: '{{ __("photos.please_select_image") }}'
            });
            return;
        }

        const formData = new FormData(form);
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>{{ __("photos.uploading") }}';

        try {
            const response = await fetch('{{ route("photos.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers.get('content-type'));

            if (!response.ok) {
                const errorText = await response.text();
                console.error('Server error response:', errorText);
                throw new Error(`Server error (${response.status})`);
            }

            const result = await response.json();
            console.log('Upload result:', result);

            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Caricata!',
                    text: '{{ __("photos.photo_uploaded") }}',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = result.redirect;
                });
            } else {
                // Gestisci errori di validazione
                if (result.errors) {
                    const errorMessages = Object.values(result.errors).flat().join('\n');
                    throw new Error(errorMessages);
                }
                throw new Error(result.message || '{{ __("photos.upload_error") }}');
            }
        } catch (error) {
            console.error('Upload error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Errore',
                text: error.message || '{{ __("photos.upload_error") }}'
            });
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="ph ph-upload me-2"></i>{{ __("photos.upload_button") }}';
        }
    });
});
</script>
@endpush

