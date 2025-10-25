@extends('layout.master')

@section('title', __('videos.upload_video'))

@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">{{ __('videos.upload_video') }}</h4>
                
            </div>
        </div>

        <!-- Upload Status -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-light-info">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12 col-md-6 mb-3 mb-md-0">
                                <h6 class="mb-2">
                                    <i class="ph-duotone ph-upload f-s-16 me-2"></i>
                                    {{ __('videos.upload_status') }}
                                </h6>
                                <p class="mb-0 text-muted">
                                    {{ __('videos.videos_remaining') }}: <strong>{{ $user->remaining_video_uploads }}</strong>
                                </p>
                            </div>
                            <div class="col-12 col-md-6">
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

        <!-- Error Alert -->
        @if($errors->any())
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-start">
                        <i class="ph-duotone ph-warning-circle f-s-24 me-3 mt-1"></i>
                        <div class="flex-grow-1">
                            <h5 class="alert-heading mb-2">
                                <i class="ph ph-x-circle me-2"></i>Errore durante l'upload
                            </h5>
                            @foreach($errors->all() as $error)
                                <p class="mb-0">{{ $error }}</p>
                            @endforeach
                            <hr>
                            <p class="mb-0 small">
                                <i class="ph ph-info me-1"></i>
                                Se il problema persiste, verifica:
                            </p>
                            <ul class="mb-0 small mt-2">
                                <li>Di avere un account PeerTube attivo</li>
                                <li>Che il file video sia in un formato supportato</li>
                                <li>Che la dimensione del file non superi 100MB</li>
                                <li>La tua connessione internet</li>
                            </ul>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Success Alert -->
        @if(session('success'))
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-start">
                        <i class="ph-duotone ph-check-circle f-s-24 me-3 mt-1 text-success"></i>
                        <div class="flex-grow-1">
                            <h5 class="alert-heading mb-2">
                                <i class="ph ph-check-circle me-2"></i>Upload completato!
                            </h5>
                            <p class="mb-0">{{ session('success') }}</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Info Alert -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-info">
                    <div class="d-flex align-items-start">
                        <i class="ph-duotone ph-info f-s-20 me-3 mt-1"></i>
                        <div>
                            <h6 class="alert-heading mb-2">{{ __('videos.upload_info') }}</h6>
                            <p class="mb-2">Il tuo video verrà caricato su PeerTube e sarà disponibile a breve una volta completata la finalizzazione.</p>
                            <ul class="mb-0 small">
                                <li>{{ __('videos.supported_formats') }}</li>
                                <li>{{ __('videos.max_size') }}</li>
                                <li>{{ __('videos.processing_time') }}</li>
                            </ul>
                        </div>
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

                            <!-- {{ __('common.video') }} File Upload with Integrated Progress -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <label for="videoFile" class="form-label">{{ __('videos.video_file') }} *</label>
                                    <div class="upload-area border-2 border-dashed border-secondary rounded p-4 text-center position-relative" id="uploadArea">
                                        <!-- Upload State -->
                                        <div id="uploadState">
                                            <i class="ph-duotone ph-cloud-arrow-up f-s-48 text-muted mb-3"></i>
                                            <h6 class="mb-2">{{ __('videos.drag_drop_video') }}</h6>
                                            <p class="text-muted mb-3">{{ __('videos.supported_formats') }}: MP4, AVI, MOV, MKV, WEBM, FLV</p>
                                            <p class="text-muted f-s-12">{{ __('videos.max_size') }}: 100MB</p>
                                            <input type="file" name="video_file" id="videoFile" accept="video/*" class="d-none" required>
                                            <button type="button" class="btn btn-outline-success" onclick="document.getElementById('videoFile').click()">
                                                <i class="ph-duotone ph-folder-open me-2"></i>{{ __('videos.select_file') }}
                                            </button>
                                        </div>

                                        <!-- Progress State (Hidden by default) -->
                                        <div id="progressState" style="display: none;">
                                            <div class="d-flex align-items-center justify-content-center mb-3">
                                                <div class="spinner-border spinner-border-sm text-success me-3" role="status">
                                                    <span class="visually-hidden">{{ __('videos.loading') }}</span>
                                                </div>
                                                <h6 class="mb-0" id="progressTitle">{{ __('videos.preparing_upload') }}</h6>
                                            </div>
                                            <div class="progress mb-3" style="height: 12px;">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" id="progressBar" style="width: 0%"></div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <small class="text-muted" id="progressText">Inizializzazione...</small>
                                                <small class="text-muted" id="progressPercent">0%</small>
                                            </div>
                                            <div id="progressDetails" style="display: none;">
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <small class="text-muted d-block">
                                                            <i class="ph-duotone ph-clock me-1"></i>
                                                            {{ __('media.elapsed') }}: <span id="elapsedTime">00:00</span>
                                                            <span class="f-s-10">({{ __('media.mm_ss') }})</span>
                                                        </small>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted d-block">
                                                            <i class="ph-duotone ph-timer me-1"></i>
                                                            {{ __('media.remaining') }}: <span id="estimatedTime">--:--</span>
                                                            <span class="f-s-10">({{ __('media.mm_ss') }})</span>
                                                        </small>
                                                    </div>
                                                    <div class="col-12">
                                                        <small class="text-muted d-block">
                                                            <i class="ph-duotone ph-wifi me-1"></i>
                                                            {{ __('media.connection') }}: <span id="connectionType">--</span>
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- File Info -->
                                    <div id="fileInfo" class="mt-3" style="display: none;">
                                        <div class="alert alert-success">
                                            <div class="d-flex align-items-center">
                                                <i class="ph-duotone ph-video-camera f-s-16 me-2"></i>
                                                <div class="flex-grow-1">
                                                    <strong id="fileName"></strong>
                                                    <br>
                                                    <small class="text-muted" id="fileSize"></small>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile()">
                                                    <i class="ph-duotone ph-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- {{ __('common.video') }} Details - Mobile First Layout -->
                            <div class="row">
                                <!-- Title -->
                                <div class="col-12 mb-3">
                                    <label for="title" class="form-label">{{ __('videos.title') }} *</label>
                                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required maxlength="255">
                                    <div class="form-text">{{ __('videos.title_help') }}</div>
                                </div>

                                <!-- Description -->
                                <div class="col-12 mb-3">
                                    <label for="description" class="form-label">{{ __('videos.description') }}</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" maxlength="1000">{{ old('description') }}</textarea>
                                    <div class="form-text">{{ __('videos.description_help') }}</div>
                                </div>

                                <!-- Tags -->
                                <div class="col-12 mb-3">
                                    <label for="tags" class="form-label">{{ __('videos.tags') }}</label>
                                    <input type="text" class="form-control" id="tags" name="tags" value="{{ old('tags') }}" placeholder="{{ __('videos.tags_placeholder') }}">
                                    <div class="form-text">{{ __('videos.tags_help') }}</div>
                                </div>

                                <!-- {{ __('common.thumbnail') }} -->
                                <div class="col-12 mb-3">
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
                                            <img src="" alt="{{ __('common.thumbnail') }}" class="img-fluid rounded" id="thumbnailImg">
                                            <button type="button" class="btn btn-sm btn-outline-danger position-absolute top-0 end-0 m-1" onclick="removeThumbnail()">
                                                <i class="ph-duotone ph-x"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Privacy -->
                                <div class="col-12 mb-3">
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

                            <!-- Submit Buttons -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex flex-column flex-sm-row justify-content-between gap-2">
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
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const uploadArea = document.getElementById('uploadArea');
    const uploadState = document.getElementById('uploadState');
    const progressState = document.getElementById('progressState');

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

    // Form submission with integrated progress
    uploadForm.addEventListener('submit', function(e) {
        e.preventDefault();

        // Show progress in upload area
        uploadState.style.display = 'none';
        progressState.style.display = 'block';
        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="ph-duotone ph-spinner f-s-16 me-1"></i>{{ __("videos.loading") }}';

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
        const progressPercent = document.getElementById('progressPercent');
        const progressDetails = document.getElementById('progressDetails');
        const elapsedTime = document.getElementById('elapsedTime');
        const estimatedTime = document.getElementById('estimatedTime');
        const connectionTypeElement = document.getElementById('connectionType');

        // Show connection type immediately
        const connectionLabels = {
            'slow-2g': 'Molto Lenta',
            '2g': 'Lenta',
            '3g': '{{ __('common.media_section') }}',
            '4g': 'Veloce',
            '5g': 'Molto Veloce'
        };
        connectionTypeElement.textContent = connectionLabels[connectionType] || 'Standard';

        // Show details after 3 seconds
        setTimeout(() => {
            progressDetails.style.display = 'block';
        }, 3000);

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

                remainingSeconds = Math.max(0, Math.floor(remainingSeconds));
                const estMinutes = Math.floor(remainingSeconds / 60);
                const estSeconds = Math.floor(remainingSeconds % 60);
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
            body: formData,
            redirect: 'manual' // Gestisci i redirect manualmente
        })
        .then(async response => {
            clearInterval(timeInterval);

            // Se è un redirect, segui il redirect
            if (response.type === 'opaqueredirect' || response.status === 302 || response.status === 301) {
                // Complete the progress
                progressTitle.textContent = 'Completato!';
                progressText.textContent = '{{ __('common.video') }} caricato con successo';
                progressBar.style.width = '100%';
                progressPercent.textContent = '100%';
                progressBar.classList.remove('progress-bar-animated');

                // Redirect dopo breve pausa
                setTimeout(() => {
                    window.location.href = response.url || '{{ route("profile.videos") }}';
                }, 1500);
                return;
            }

            // Se non è un redirect di successo, c'è un errore
            if (!response.ok) {
                throw new Error('Errore nel server durante l\'upload');
            }

            // Risposta JSON (non dovrebbe succedere con Laravel redirect)
            const data = await response.json();
            
            if (data.success) {
                // Success
                progressTitle.textContent = 'Completato!';
                progressText.textContent = '{{ __('common.video') }} caricato con successo';
                progressBar.style.width = '100%';
                progressPercent.textContent = '100%';
                progressBar.classList.remove('progress-bar-animated');

                setTimeout(() => {
                    window.location.href = '{{ route("profile.videos") }}';
                }, 1500);
            } else {
                throw new Error(data.message || 'Errore sconosciuto');
            }
        })
        .catch(error => {
            clearInterval(timeInterval);

            // Mostra errore nella progress bar
            progressTitle.textContent = 'Errore!';
            progressText.textContent = 'Upload fallito';
            progressBar.classList.remove('bg-success', 'progress-bar-animated');
            progressBar.classList.add('bg-danger');

            // Mostra messaggio di errore dettagliato
            setTimeout(() => {
                // Reset UI
                progressState.style.display = 'none';
                uploadState.style.display = 'block';
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="ph-duotone ph-upload me-1"></i>{{ __('videos.upload_video') }}';

                // Mostra alert di errore
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Errore durante l\'upload',
                        html: `
                            <p>${error.message}</p>
                            <hr>
                            <small class="text-muted">
                                <strong>Cosa fare:</strong><br>
                                • Verifica la tua connessione internet<br>
                                • Controlla che il file sia valido<br>
                                • Ricarica la pagina e riprova<br>
                                • Se il problema persiste, contatta l'amministratore
                            </small>
                        `,
                        confirmButtonText: 'Riprova',
                        confirmButtonColor: '#dc3545'
                    });
                } else {
                    alert('❌ Errore: ' + error.message + '\n\nRicarica la pagina e riprova.');
                }
            }, 1000);
        });
    });

    // {{ __('common.thumbnail') }} handling
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
