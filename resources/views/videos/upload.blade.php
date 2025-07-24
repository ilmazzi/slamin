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
                            <h6 class="mb-0" id="progressTitle">Preparazione upload...</h6>
                        </div>
                        <div class="progress" style="height: 12px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" id="progressBar" style="width: 0%"></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small class="text-muted" id="progressText">Inizializzazione...</small>
                            <small class="text-muted" id="progressPercent">0%</small>
                        </div>
                        <div class="mt-3" id="progressDetails" style="display: none;">
                            <div class="row">
                                <div class="col-md-4">
                                    <small class="text-muted">
                                        <i class="ph-duotone ph-clock me-1"></i>
                                        Tempo trascorso: <span id="elapsedTime">00:00</span>
                                    </small>
                                </div>
                                <div class="col-md-4 text-center">
                                    <small class="text-muted">
                                        <i class="ph-duotone ph-wifi me-1"></i>
                                        Connessione: <span id="connectionType">--</span>
                                    </small>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <small class="text-muted">
                                        <i class="ph-duotone ph-timer me-1"></i>
                                        Tempo stimato: <span id="estimatedTime">--:--</span>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Form -->
        <div class="row">
            <div class="col-12">
                <!-- Info Alert -->
                <div class="alert alert-info mb-4">
                    <div class="d-flex align-items-start">
                        <i class="ph-duotone ph-info f-s-20 me-3 mt-1"></i>
                        <div>
                            <h6 class="alert-heading mb-2">Informazioni sull'upload</h6>
                            <p class="mb-2">Il tuo video verrà caricato su PeerTube e sarà disponibile a breve una volta completata la finalizzazione.</p>
                            <ul class="mb-0 small">
                                <li>Formati supportati: MP4, AVI, MOV, MKV, WEBM, FLV</li>
                                <li>Dimensione massima: 100MB</li>
                                <li>Tempo di elaborazione: 2-5 minuti (dipende dalla dimensione)</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
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

    // Form submission with realistic progress
    uploadForm.addEventListener('submit', function(e) {
        e.preventDefault();

        // Show progress
        uploadProgress.style.display = 'block';
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="ph-duotone ph-spinner f-s-16 me-1"></i>Caricamento...';

                // Initialize progress tracking
        const startTime = Date.now();
        let currentPhase = 0;

        // Get connection speed and calculate realistic estimates
        const connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
        const file = videoFile.files[0];
        const fileSizeMB = file.size / (1024 * 1024);

        // Connection speed multipliers (seconds per MB)
        const speedMultipliers = {
            'slow-2g': 15,    // Very slow: 15 seconds per MB
            '2g': 12,         // Slow: 12 seconds per MB
            '3g': 8,          // Medium: 8 seconds per MB
            '4g': 4,          // Fast: 4 seconds per MB
            '5g': 2           // Very fast: 2 seconds per MB
        };

        // Get connection type or estimate based on user agent
        let connectionType = '4g'; // Default
        if (connection) {
            connectionType = connection.effectiveType || connection.type || '4g';
        } else {
            // Fallback: estimate based on user agent or assume 4g
            const userAgent = navigator.userAgent.toLowerCase();
            if (userAgent.includes('mobile') || userAgent.includes('android')) {
                connectionType = '3g'; // Assume slower for mobile
            }
        }

        const uploadSpeedMultiplier = speedMultipliers[connectionType] || 4;
        const processingMultiplier = 3; // Processing time is less dependent on connection

        // Calculate realistic durations
        const baseUploadTime = Math.max(5000, fileSizeMB * uploadSpeedMultiplier * 1000);
        const baseProcessingTime = Math.max(10000, fileSizeMB * processingMultiplier * 1000);

        const phases = [
            { name: 'Preparazione file...', progress: 5, duration: 2000 },
            { name: 'Connessione a PeerTube...', progress: 15, duration: 3000 },
            { name: 'Upload file in corso...', progress: 60, duration: baseUploadTime },
            { name: 'Elaborazione video...', progress: 85, duration: baseProcessingTime },
            { name: 'Finalizzazione...', progress: 95, duration: 5000 }
        ];

        const progressTitle = document.getElementById('progressTitle');
        const progressText = document.getElementById('progressText');
        const progressPercent = document.getElementById('progressPercent');
        const progressDetails = document.getElementById('progressDetails');
        const elapsedTime = document.getElementById('elapsedTime');
        const estimatedTime = document.getElementById('estimatedTime');
        const connectionTypeElement = document.getElementById('connectionType');

        // Show connection type immediately
        const connectionLabels = {
            'slow-2g': 'Molto Lenta',
            '2g': 'Lenta',
            '3g': 'Media',
            '4g': 'Veloce',
            '5g': 'Molto Veloce'
        };
        connectionTypeElement.textContent = connectionLabels[connectionType] || 'Standard';

        // Show details after 5 seconds
        setTimeout(() => {
            progressDetails.style.display = 'block';
        }, 5000);

        // Update elapsed time
        const timeInterval = setInterval(() => {
            const elapsed = Math.floor((Date.now() - startTime) / 1000);
            const minutes = Math.floor(elapsed / 60);
            const seconds = elapsed % 60;
            elapsedTime.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }, 1000);

        // Phase-based progress simulation
        function updateProgress() {
            if (currentPhase < phases.length) {
                const phase = phases[currentPhase];
                progressTitle.textContent = phase.name;
                progressText.textContent = phase.name;
                progressBar.style.width = phase.progress + '%';
                progressPercent.textContent = phase.progress + '%';

                                // Estimate remaining time based on connection speed and file size
                const elapsedSeconds = Math.floor((Date.now() - startTime) / 1000);

                // Calculate total estimated time based on connection speed
                const uploadTime = fileSizeMB * uploadSpeedMultiplier;
                const processingTime = fileSizeMB * processingMultiplier;
                const totalEstimatedSeconds = uploadTime + processingTime + 30; // 30 seconds for setup

                // Adjust based on current phase
                let remainingSeconds;
                if (currentPhase <= 2) {
                    // Still in upload phase
                    remainingSeconds = totalEstimatedSeconds - elapsedSeconds;
                } else if (currentPhase === 3) {
                    // In processing phase
                    remainingSeconds = (processingTime + 30) - (elapsedSeconds - (uploadTime + 30));
                } else {
                    // In finalization phase
                    remainingSeconds = 30 - (elapsedSeconds - (uploadTime + processingTime + 30));
                }

                                remainingSeconds = Math.max(0, remainingSeconds);
                const estMinutes = Math.floor(remainingSeconds / 60);
                const estSeconds = remainingSeconds % 60;
                estimatedTime.textContent = `${estMinutes.toString().padStart(2, '0')}:${estSeconds.toString().padStart(2, '0')}`;

                currentPhase++;

                if (currentPhase < phases.length) {
                    setTimeout(updateProgress, phase.duration);
                }
            }
        }

        // Start progress simulation
        updateProgress();

        // Submit form
        const formData = new FormData(uploadForm);

        fetch(uploadForm.action, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            clearInterval(timeInterval);

            // Complete the progress
            progressTitle.textContent = 'Completato!';
            progressText.textContent = 'Video caricato con successo';
            progressBar.style.width = '100%';
            progressPercent.textContent = '100%';
            progressBar.classList.remove('progress-bar-animated');

            // Redirect after a short delay
            setTimeout(() => {
                if (response.redirected) {
                    window.location.href = response.url;
                } else {
                    return response.json();
                }
            }, 1500);
        })
        .catch(error => {
            clearInterval(timeInterval);
            uploadProgress.style.display = 'none';
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="ph-duotone ph-upload me-1"></i>{{ __('videos.upload_video') }}';

            // Show error with SweetAlert if available
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Errore durante il caricamento',
                    text: error.message,
                    confirmButtonText: 'OK'
                });
            } else {
                alert('Errore durante il caricamento: ' + error.message);
            }
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
