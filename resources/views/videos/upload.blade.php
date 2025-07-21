@extends('layout.master')

@section('title', __('videos.upload_video'))

@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">{{ __('videos.upload_video') }}</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-house f-s-16"></i> {{ __('dashboard.dashboard') }}
                            </span>
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('videos.index') }}" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-video-camera f-s-16"></i> {{ __('videos.videos') }}
                            </span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#" class="f-s-14 f-w-500">{{ __('videos.upload_video') }}</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Upload Status -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-light-info">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h6 class="mb-2">
                                    <i class="ph-duotone ph-upload f-s-16 me-2"></i>
                                    {{ __('videos.upload_status') }}
                                </h6>
                                <p class="mb-0 text-muted">
                                    {{ __('videos.videos_remaining') }}: <strong>{{ $user->remaining_video_uploads }}</strong>
                                </p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <div class="progress mb-2" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: {{ ($user->current_video_count / $user->current_video_limit) * 100 }}%"></div>
                                </div>
                                <small class="text-muted">
                                    {{ $user->current_video_count }} / {{ $user->current_video_limit }} {{ __('videos.videos_used') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Progress (Hidden by default) -->
        <div class="row mb-4" id="uploadProgress" style="display: none;">
            <div class="col-12">
                <div class="card card-light-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="spinner-border spinner-border-sm text-success me-3" role="status">
                                <span class="visually-hidden">Caricamento...</span>
                            </div>
                            <h6 class="mb-0">Caricamento video in corso...</h6>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" id="progressBar" style="width: 0%"></div>
                        </div>
                        <small class="text-muted mt-2 d-block" id="progressText">Preparazione upload...</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Form -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-video-camera f-s-16 me-2"></i>
                            {{ __('videos.upload_form') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('videos.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                            @csrf

                            <!-- Video File Upload -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <label for="videoFile" class="form-label">{{ __('videos.video_file') }} *</label>
                                    <div class="upload-area border-2 border-dashed border-secondary rounded p-4 text-center" id="uploadArea">
                                        <i class="ph-duotone ph-cloud-arrow-up f-s-48 text-muted mb-3"></i>
                                        <h6 class="mb-2">Trascina qui il tuo video o clicca per selezionare</h6>
                                        <p class="text-muted mb-3">{{ __('videos.supported_formats') }}: MP4, AVI, MOV, MKV, WEBM, FLV</p>
                                        <p class="text-muted f-s-12">{{ __('videos.max_size') }}: 100MB</p>
                                        <input type="file" name="video_file" id="videoFile" accept="video/*" class="d-none" required>
                                        <button type="button" class="btn btn-outline-success" onclick="document.getElementById('videoFile').click()">
                                            <i class="ph-duotone ph-folder-open me-2"></i>Seleziona File
                                        </button>
                                    </div>
                                    <div id="fileInfo" class="mt-3" style="display: none;">
                                        <div class="alert alert-success">
                                            <div class="d-flex align-items-center">
                                                <i class="ph-duotone ph-video-camera f-s-16 me-2"></i>
                                                <div>
                                                    <strong id="fileName"></strong>
                                                    <br>
                                                    <small class="text-muted" id="fileSize"></small>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-danger ms-auto" onclick="removeFile()">
                                                    <i class="ph-duotone ph-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Video Details -->
                            <div class="row">
                                <div class="col-md-8">
                                    <!-- Title -->
                                    <div class="mb-3">
                                        <label for="title" class="form-label">{{ __('videos.title') }} *</label>
                                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required maxlength="255">
                                        <div class="form-text">{{ __('videos.title_help') }}</div>
                                    </div>

                                    <!-- Description -->
                                    <div class="mb-3">
                                        <label for="description" class="form-label">{{ __('videos.description') }}</label>
                                        <textarea class="form-control" id="description" name="description" rows="4" maxlength="1000">{{ old('description') }}</textarea>
                                        <div class="form-text">{{ __('videos.description_help') }}</div>
                                    </div>

                                    <!-- Tags -->
                                    <div class="mb-3">
                                        <label for="tags" class="form-label">{{ __('videos.tags') }}</label>
                                        <input type="text" class="form-control" id="tags" name="tags" value="{{ old('tags') }}" placeholder="{{ __('videos.tags_placeholder') }}">
                                        <div class="form-text">{{ __('videos.tags_help') }}</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <!-- Thumbnail -->
                                    <div class="mb-3">
                                        <label for="thumbnail" class="form-label">{{ __('videos.thumbnail') }}</label>
                                        <div class="thumbnail-upload" id="thumbnailArea">
                                            <div class="thumbnail-placeholder text-center p-3 border rounded">
                                                <i class="ph-duotone ph-image f-s-24 text-muted mb-2"></i>
                                                <p class="text-muted small mb-2">{{ __('videos.thumbnail_help') }}</p>
                                                <button type="button" class="btn btn-sm btn-outline-success" onclick="document.getElementById('thumbnail').click()">
                                                    {{ __('videos.select_thumbnail') }}
                                                </button>
                                                <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="d-none">
                                            </div>
                                            <div class="thumbnail-preview d-none" id="thumbnailPreview">
                                                <img src="" alt="Thumbnail" class="img-fluid rounded" id="thumbnailImg">
                                                <button type="button" class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-1" onclick="removeThumbnail()">
                                                    <i class="ph-duotone ph-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Privacy -->
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('videos.privacy') }}</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_public" id="public" value="1" {{ old('is_public', '1') == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="public">
                                                <i class="ph-duotone ph-globe f-s-14 me-1"></i>
                                                {{ __('videos.public') }}
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_public" id="private" value="0" {{ old('is_public') == '0' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="private">
                                                <i class="ph-duotone ph-lock f-s-14 me-1"></i>
                                                {{ __('videos.private') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <a href="{{ route('videos.index') }}" class="btn btn-secondary">
                                            <i class="ph-duotone ph-arrow-left me-1"></i>
                                            {{ __('common.cancel') }}
                                        </a>
                                        <button type="submit" class="btn btn-success" id="submitBtn" disabled>
                                            <i class="ph-duotone ph-upload me-1"></i>
                                            {{ __('videos.upload_video') }}
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const videoFile = document.getElementById('videoFile');
    const submitBtn = document.getElementById('submitBtn');
    const uploadForm = document.getElementById('uploadForm');
    const uploadProgress = document.getElementById('uploadProgress');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const uploadArea = document.getElementById('uploadArea');

    // Drag and drop functionality
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('border-success');
        uploadArea.classList.remove('border-secondary');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('border-success');
        uploadArea.classList.add('border-secondary');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('border-success');
        uploadArea.classList.add('border-secondary');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            videoFile.files = files;
            handleFileSelect();
        }
    });

    // File selection
    videoFile.addEventListener('change', handleFileSelect);

    function handleFileSelect() {
        if (videoFile.files.length > 0) {
            const file = videoFile.files[0];
            const size = (file.size / (1024 * 1024)).toFixed(2);

            fileName.textContent = file.name;
            fileSize.textContent = `${size} MB`;
            fileInfo.style.display = 'block';
            submitBtn.disabled = false;
        } else {
            fileInfo.style.display = 'none';
            submitBtn.disabled = true;
        }
    }

    function removeFile() {
        videoFile.value = '';
        fileInfo.style.display = 'none';
        submitBtn.disabled = true;
    }

    // Form submission with progress
    uploadForm.addEventListener('submit', function(e) {
        e.preventDefault();

        // Show progress
        uploadProgress.style.display = 'block';
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="ph-duotone ph-spinner f-s-16 me-1"></i>Caricamento...';

        // Simulate progress
        let progress = 0;
        const progressInterval = setInterval(function() {
            progress += Math.random() * 15;
            if (progress > 90) progress = 90;
            progressBar.style.width = progress + '%';
            progressText.textContent = `Caricamento... ${Math.round(progress)}%`;
        }, 200);

        // Submit form
        const formData = new FormData(uploadForm);

        fetch(uploadForm.action, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            clearInterval(progressInterval);
            progressBar.style.width = '100%';
            progressText.textContent = 'Completato!';

            if (response.redirected) {
                window.location.href = response.url;
            } else {
                return response.json();
            }
        })
        .catch(error => {
            clearInterval(progressInterval);
            uploadProgress.style.display = 'none';
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="ph-duotone ph-upload me-1"></i>{{ __('videos.upload_video') }}';

            alert('Errore durante il caricamento: ' + error.message);
        });
    });

    // Thumbnail handling
    const thumbnail = document.getElementById('thumbnail');
    const thumbnailPreview = document.getElementById('thumbnailPreview');
    const thumbnailImg = document.getElementById('thumbnailImg');
    const thumbnailPlaceholder = document.querySelector('.thumbnail-placeholder');

    thumbnail.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            const file = e.target.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                thumbnailImg.src = e.target.result;
                thumbnailPlaceholder.style.display = 'none';
                thumbnailPreview.classList.remove('d-none');
            };

            reader.readAsDataURL(file);
        }
    });

    window.removeThumbnail = function() {
        thumbnail.value = '';
        thumbnailPreview.classList.add('d-none');
        thumbnailPlaceholder.style.display = 'block';
    };

    window.removeFile = removeFile;
});
</script>
