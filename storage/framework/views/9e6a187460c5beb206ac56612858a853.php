<?php $__env->startSection('title', 'I Miei ' . __('common.video') . ' - Slamin'); ?>

<?php $__env->startSection('css'); ?>
<style>
#breadcrumb-nav {
    position: relative !important;
    z-index: 1 !important;
    background: transparent !important;
    width: auto !important;
    height: auto !important;
}

/* Stili per i pulsanti delle azioni */
.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 0.375rem;
}

.gap-2 {
    gap: 0.5rem !important;
}

/* Effetti per l'anteprima video */
.video-preview {
    transition: all 0.3s ease;
}

.video-preview:hover {
    transform: scale(1.02);
}

.video-preview:hover .play-button {
    background-color: #667eea !important;
    transform: scale(1.1);
}

.video-preview:hover .play-button i {
    color: white !important;
}

/* Effetti per thumbnail con play button */
.position-relative[onclick] {
    transition: all 0.3s ease;
}

.position-relative[onclick]:hover {
    transform: scale(1.02);
}

.position-relative[onclick]:hover .play-button {
    background-color: #667eea !important;
    transform: scale(1.1);
}

.position-relative[onclick]:hover .play-button i {
    color: white !important;
}

.play-button {
    transition: all 0.3s ease;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title"><?php echo e(__('profile.my_videos')); ?></h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li class="">
                    <a href="<?php echo e(route('dashboard')); ?>" class="f-s-14 f-w-500">
                        <span>
                            <i class="ph-duotone ph-house f-s-16"></i> <?php echo e(__('dashboard.dashboard')); ?>

                        </span>
                    </a>
                </li>
                <li class="">
                    <a href="<?php echo e(route('profile.show')); ?>" class="f-s-14 f-w-500"><?php echo e(__('profile.breadcrumb_profile')); ?></a>
                </li>
                <li class="active">
                    <a href="#" class="f-s-14 f-w-500"><?php echo e(__('profile.videos')); ?></a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Quick Navigation Links -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-navigation-arrow me-2"></i>
                        <?php echo e(__('videos.quick_navigation')); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <a href="<?php echo e(route('videos.show', ['video' => 1])); ?>" class="card card-light-primary hover-effect text-decoration-none">
                                <div class="card-body text-center py-3">
                                    <i class="ph-duotone ph-list f-s-30 text-primary mb-2"></i>
                                    <h6 class="mb-1"><?php echo e(__('videos.all_videos')); ?></h6>
                                    <small class="text-muted"><?php echo e(__('videos.view_all_videos')); ?></small>
                                </div>
                            </a>
                        </div>
                        <?php if(auth()->guard()->check()): ?>
                        <div class="col-lg-3 col-md-6">
                            <a href="<?php echo e(route('profile.videos')); ?>" class="card card-light-info hover-effect text-decoration-none">
                                <div class="card-body text-center py-3">
                                    <i class="ph-duotone ph-video-camera f-s-30 text-info mb-2"></i>
                                    <h6 class="mb-1"><?php echo e(__('videos.my_videos')); ?></h6>
                                    <small class="text-muted"><?php echo e(__('videos.view_my_videos')); ?></small>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="<?php echo e(route('videos.upload')); ?>" class="card card-light-success hover-effect text-decoration-none">
                                <div class="card-body text-center py-3">
                                    <i class="ph-duotone ph-upload f-s-30 text-success mb-2"></i>
                                    <h6 class="mb-1"><?php echo e(__('videos.upload_video')); ?></h6>
                                    <small class="text-muted"><?php echo e(__('videos.upload_new_video')); ?></small>
                                </div>
                            </a>
                        </div>
                        <?php endif; ?>
                        <div class="col-lg-3 col-md-6">
                            <a href="<?php echo e(route('gallery')); ?>" class="card card-light-warning hover-effect text-decoration-none">
                                <div class="card-body text-center py-3">
                                    <i class="ph-duotone ph-images f-s-30 text-warning mb-2"></i>
                                    <h6 class="mb-1"><?php echo e(__('videos.gallery')); ?></h6>
                                    <small class="text-muted"><?php echo e(__('videos.view_gallery')); ?></small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <?php if(session('success')): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ph-duotone ph-check-circle me-2"></i>
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ph-duotone ph-warning me-2"></i>
                <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Header Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0 f-w-600">Gestione <?php echo e(__('common.video')); ?></h4>
                <a href="<?php echo e(route('videos.upload')); ?>" class="btn btn-primary hover-effect">
                    <i class="ph ph-plus me-2"></i>Carica Nuovo <?php echo e(__('common.video')); ?>

                </a>
            </div>
        </div>
    </div>

    <!-- Videos Grid -->
    <div class="row">
        <?php if($videos->count() > 0): ?>
        <?php $__currentLoopData = $videos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card hover-effect">
                <div class="position-relative">
                    <?php if($video->thumbnail_url && $video->thumbnail_url !== asset('assets/images/placeholder/placholder-1.jpg')): ?>
                        <!-- <?php echo e(__('common.thumbnail')); ?> con overlay play -->
                        <div class="position-relative" style="cursor: pointer;" onclick="window.location.href='<?php echo e(route('videos.show', $video)); ?>'">
                            <img src="<?php echo e($video->thumbnail_url); ?>" alt="<?php echo e($video->title); ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                            <!-- Overlay play button -->
                            <div class="position-absolute top-50 start-50 translate-middle">
                                <div class="play-button bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; box-shadow: 0 4px 15px rgba(0,0,0,0.3); transition: all 0.3s ease;">
                                    <i class="ph-duotone ph-play f-s-24 text-primary"></i>
                                </div>
                            </div>
                            <!-- Duration overlay -->
                            <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                                <small class="text-white f-s-12">
                                    <i class="ph-duotone ph-clock me-1"></i>
                                    <?php if($video->duration && $video->duration > 0): ?>
                                        <?php echo e($video->formatted_duration); ?>

                                    <?php else: ?>
                                        <span title="<?php echo e(__('videos.duration_unavailable')); ?>">--:--</span>
                                    <?php endif; ?>
                                </small>
                            </div>
                        </div>
                    <?php elseif($video->peertube_uuid): ?>
                        <!-- Anteprima video con overlay play -->
                        <div class="card-img-top video-preview bg-gradient-primary d-flex align-items-center justify-content-center position-relative"
                             style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); cursor: pointer;"
                             onclick="window.location.href='<?php echo e(route('videos.show', $video)); ?>'">
                            <div class="position-absolute top-50 start-50 translate-middle">
                                <div class="play-button bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; box-shadow: 0 4px 15px rgba(0,0,0,0.3); transition: all 0.3s ease;">
                                    <i class="ph-duotone ph-play f-s-24 text-primary"></i>
                                </div>
                            </div>
                            <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                                <small class="text-white f-s-12">
                                    <i class="ph-duotone ph-clock me-1"></i>
                                    <?php if($video->duration && $video->duration > 0): ?>
                                        <?php echo e($video->formatted_duration); ?>

                                    <?php else: ?>
                                        <span title="<?php echo e(__('videos.duration_unavailable')); ?>">--:--</span>
                                    <?php endif; ?>
                                </small>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="ph-duotone ph-video-camera f-s-48 text-muted"></i>
                        </div>
                    <?php endif; ?>
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-dark f-s-11"><?php echo e($video->view_count ?? $video->views ?? 0); ?> visualizzazioni</span>
                    </div>
                    <div class="position-absolute top-0 start-0 m-2">
                        <?php if($video->is_public): ?>
                        <span class="badge bg-success f-s-11">Pubblico</span>
                        <?php else: ?>
                        <span class="badge bg-warning f-s-11">Privato</span>
                        <?php endif; ?>
                        <?php if($video->moderation_status): ?>
                        <span class="badge bg-<?php echo e($video->moderation_status === 'approved' ? 'success' : ($video->moderation_status === 'pending' ? 'warning' : 'danger')); ?> f-s-11 ms-1">
                            <?php echo e(ucfirst($video->moderation_status)); ?>

                        </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body pa-20">
                    <h5 class="card-title f-w-600 f-s-16 mb-2"><?php echo e($video->title); ?></h5>
                    <?php if($video->description): ?>
                    <p class="text-muted f-s-14 mb-3"><?php echo e(Str::limit($video->description, 100)); ?></p>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <small class="text-muted f-s-12"><?php echo e($video->created_at->format('d/m/Y')); ?></small>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary hover-effect btn-sm" onclick="editVideo(<?php echo e($video->id); ?>)" title="<?php echo e(__('permissions.modify')); ?>">
                                <i class="ph ph-pencil f-s-14"></i>
                            </button>
                            <button class="btn btn-danger hover-effect btn-sm" onclick="deleteVideo(<?php echo e($video->id); ?>)" title="Elimina">
                                <i class="ph ph-trash f-s-14"></i>
                            </button>
                            <?php if (isset($component)) { $__componentOriginalcab7032bfdfb17b0d85d7225950dd852 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcab7032bfdfb17b0d85d7225950dd852 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.report-button','data' => ['content' => $video,'type' => 'video','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('report-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($video),'type' => 'video','size' => 'sm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcab7032bfdfb17b0d85d7225950dd852)): ?>
<?php $attributes = $__attributesOriginalcab7032bfdfb17b0d85d7225950dd852; ?>
<?php unset($__attributesOriginalcab7032bfdfb17b0d85d7225950dd852); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcab7032bfdfb17b0d85d7225950dd852)): ?>
<?php $component = $__componentOriginalcab7032bfdfb17b0d85d7225950dd852; ?>
<?php unset($__componentOriginalcab7032bfdfb17b0d85d7225950dd852); ?>
<?php endif; ?>
                        </div>
                    </div>

                    <?php if(!($video->file_path || $video->peertube_uuid)): ?>
                        <div class="alert alert-warning mb-0">
                            <i class="ph ph-warning me-2"></i>
                            <small><?php echo e(__('videos.video_unavailable')); ?></small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center pa-40">
                    <i class="ph-duotone ph-video-camera f-s-64 text-muted mb-3"></i>
                    <h5 class="mb-3">Nessun video caricato</h5>
                    <p class="text-muted mb-4">Non hai ancora caricato nessun video. Inizia subito caricando il tuo primo video!</p>
                    <a href="<?php echo e(route('videos.upload')); ?>" class="btn btn-primary hover-effect">
                        <i class="ph ph-plus me-2"></i>Carica il tuo primo video
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if($videos->hasPages()): ?>
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                <?php echo e($videos->links()); ?>

            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Upload <?php echo e(__('common.video')); ?> Modal -->
<div class="modal fade" id="uploadVideoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header card-light-primary">
                <h5 class="modal-title f-w-600">
                    <i class="ph ph-video-camera me-2"></i>
                    Carica Nuovo <?php echo e(__('common.video')); ?>

                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadVideoForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label f-w-600">Titolo del <?php echo e(__('common.video')); ?> *</label>
                                <input type="text" class="form-control" name="title" required>
                                <small class="text-muted f-s-12">Un titolo accattivante per il tuo video</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label f-w-600">Visibilità</label>
                                <select class="form-select" name="is_public">
                                    <option value="1">Pubblico</option>
                                    <option value="0">Privato</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label f-w-600">Descrizione</label>
                        <textarea class="form-control" name="description" rows="3"
                                  placeholder="Descrivi il contenuto del video, il contesto, le emozioni..."></textarea>
                        <small class="text-muted f-s-12">Racconta la storia dietro il tuo video</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label f-w-600">URL del <?php echo e(__('common.video')); ?> *</label>
                        <input type="url" class="form-control" name="video_url" required
                               placeholder="https://youtube.com/watch?v=... o https://vimeo.com/...">
                        <small class="text-muted f-s-12">Supporta YouTube e Vimeo</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label f-w-600"><?php echo e(__('common.thumbnail')); ?> (Opzionale)</label>
                        <input type="file" class="form-control" name="thumbnail" accept="image/*">
                        <small class="text-muted f-s-12">Se non carichi un'immagine, verrà usata quella di YouTube</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary hover-effect" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary hover-effect">
                        <i class="ph ph-upload me-2"></i><?php echo e(__('common.upload_video')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View <?php echo e(__('common.video')); ?> Modal -->
<div class="modal fade" id="viewVideoModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header card-light-info">
                <h5 class="modal-title f-w-600" id="videoModalTitle">
                    <i class="ph ph-play me-2"></i>
                    Visualizza <?php echo e(__('common.video')); ?>

                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="ratio ratio-16x9">
                    <iframe id="videoIframe" src="" frameborder="0" allowfullscreen></iframe>
                </div>
                <div class="mt-3">
                    <h6 id="videoTitle" class="f-w-600"></h6>
                    <p id="videoDescription" class="text-muted f-s-14"></p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted f-s-12" id="videoDate"></small>
                        <span class="badge bg-info f-s-12" id="videoViews"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function showUploadModal() {
    $('#uploadVideoModal').modal('show');
}

function editVideo(videoId) {
    // Implementazione modifica video
    Swal.fire('Info', 'Funzionalità modifica video in sviluppo', 'info');
}

function viewVideo(videoId) {
    // Carica i dati del video via AJAX
    fetch(`/api/videos/${videoId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('videoModalTitle').textContent = data.title;
            document.getElementById('videoTitle').textContent = data.title;
            document.getElementById('videoDescription').textContent = data.description || 'Nessuna descrizione';
            document.getElementById('videoDate').textContent = new Date(data.created_at).toLocaleDateString('it-IT');
            document.getElementById('videoViews').textContent = `${data.views} visualizzazioni`;
            document.getElementById('videoIframe').src = data.embed_url;
            $('#viewVideoModal').modal('show');
        })
        .catch(error => {
            Swal.fire('Errore', 'Impossibile caricare il video', 'error');
        });
}

function deleteVideo(videoId) {
    Swal.fire({
        title: 'Sei sicuro?',
        text: "Questa azione non può essere annullata!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sì, elimina!',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`<?php echo e(route('profile.videos.delete', '')); ?>/${videoId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire('Eliminato!', data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Errore!', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Errore eliminazione video:', error);
                Swal.fire('Errore!', 'Errore durante l\'eliminazione del video. Riprova.', 'error');
            });
        }
    });
}

// Upload <?php echo e(__('common.video')); ?> Form Handler
$('#uploadVideoForm').on('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    // Show loading
    Swal.fire({
        title: 'Caricamento...',
        text: 'Sto caricando il tuo video',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('<?php echo e(route("videos.upload")); ?>', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Successo!', data.message, 'success').then(() => {
                location.reload();
            });
        } else {
            Swal.fire('Errore!', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Errore', 'Errore durante il caricamento', 'error');
    });
});

// Hide loader as fallback
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const loader = document.querySelector('.loader-wrapper');
        if (loader) {
            loader.style.display = 'none';
        }
    }, 1000);
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/profile/videos.blade.php ENDPATH**/ ?>