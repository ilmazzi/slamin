@extends('layout.master')

@section('title', 'Impostazioni Upload - Admin')

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">
                <i class="ph ph-upload me-2"></i>
                Impostazioni Upload
            </h4>
            
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ph ph-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ph ph-warning-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Upload Settings Form -->
    <div class="row">
        <div class="col-12">
            <form id="uploadSettingsForm" method="POST" action="{{ route('admin.settings.upload.update') }}">
                @csrf

                <!-- Limiti Dimensione File -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph ph-file me-2"></i>
                            Limiti Dimensione File
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="profile_photo_max_size" class="form-label">
                                    Foto Profilo (KB)
                                </label>
                                <input type="number"
                                       class="form-control"
                                       id="profile_photo_max_size"
                                       name="settings[profile_photo_max_size]"
                                       value="{{ $uploadSettings['profile_photo_max_size'] ?? '5120' }}"
                                       min="1">
                                <div class="form-text">Dimensione massima per le foto profilo</div>
                            </div>
                            <div class="col-md-4">
                                <label for="video_max_size" class="form-label">
                                    Video (KB)
                                </label>
                                <input type="number"
                                       class="form-control"
                                       id="video_max_size"
                                       name="settings[video_max_size]"
                                       value="{{ $uploadSettings['video_max_size'] ?? '102400' }}"
                                       min="1">
                                <div class="form-text">Dimensione massima per i video</div>
                            </div>
                            <div class="col-md-4">
                                <label for="image_max_size" class="form-label">
                                    Immagini (KB)
                                </label>
                                <input type="number"
                                       class="form-control"
                                       id="image_max_size"
                                       name="settings[image_max_size]"
                                       value="{{ $uploadSettings['image_max_size'] ?? '10240' }}"
                                       min="1">
                                <div class="form-text">Dimensione massima per le immagini</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tipi di File Consentiti -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph ph-file-types me-2"></i>
                            Tipi di File Consentiti
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="allowed_image_types" class="form-label">
                                    Tipi di Immagine
                                </label>
                                <input type="text"
                                       class="form-control"
                                       id="allowed_image_types"
                                       name="settings[allowed_image_types]"
                                       value="{{ $uploadSettings['allowed_image_types'] ?? 'jpeg,jpg,png,gif,webp' }}"
                                       placeholder="jpeg,jpg,png,gif,webp">
                                <div class="form-text">Estensioni separate da virgola</div>
                            </div>
                            <div class="col-md-6">
                                <label for="allowed_video_types" class="form-label">
                                    Tipi di Video
                                </label>
                                <input type="text"
                                       class="form-control"
                                       id="allowed_video_types"
                                       name="settings[allowed_video_types]"
                                       value="{{ $uploadSettings['allowed_video_types'] ?? 'mp4,avi,mov,wmv,flv,webm' }}"
                                       placeholder="mp4,avi,mov,wmv,flv,webm">
                                <div class="form-text">Estensioni separate da virgola</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" id="saveBtn">
                                <i class="ph ph-floppy-disk me-2"></i>
                                Salva Impostazioni
                            </button>
                            <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
                                <i class="ph ph-arrow-left me-2"></i>
                                Torna alle Impostazioni
                            </a>
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
    const form = document.getElementById('uploadSettingsForm');
    const saveBtn = document.getElementById('saveBtn');
    const originalText = saveBtn.innerHTML;

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Show loading state
        saveBtn.innerHTML = '<i class="ph ph-spinner ph-spin me-2"></i>Salvataggio...';
        saveBtn.disabled = true;

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Success message
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Successo!',
                        text: data.message,
                        timer: 3000,
                        showConfirmButton: false
                    });
                } else {
                    alert(data.message);
                }
            } else {
                // Error message
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Errore!',
                        text: data.message,
                        footer: data.errors && Array.isArray(data.errors) ? data.errors.join('<br>') : ''
                    });
                } else {
                    alert('Errore: ' + data.message);
                }
            }
        })
        .catch(error => {
            console.error('Errore:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Errore!',
                    text: 'Errore durante il salvataggio: ' + (error.message || 'Errore sconosciuto')
                });
            } else {
                alert('Errore durante il salvataggio: ' + (error.message || 'Errore sconosciuto'));
            }
        })
        .finally(() => {
            // Reset button
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
        });
    });
});
</script>
@endpush
