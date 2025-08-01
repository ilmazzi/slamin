<?php $__env->startSection('title', __('common.media_section') . ' - Slamin'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">
                <i class="ph ph-video-camera me-2"></i>
                <?php echo e(__('common.media_section')); ?>

            </h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li class="">
                    <a href="<?php echo e(route('dashboard')); ?>" class="f-s-14 f-w-500">
                        <span>
                            <i class="ph-duotone ph-house f-s-16"></i> <?php echo e(__('dashboard.dashboard')); ?>

                        </span>
                    </a>
                </li>
                <li class="active">
                    <a href="#" class="f-s-14 f-w-500"><?php echo e(__('common.media_section')); ?></a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Prima Riga: Video Più Popolare + 6 Video con Switch -->
    <div class="row mb-4">
        <!-- Video Più Popolare (Grande) -->
        <div class="col-lg-8">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-trophy me-2"></i>
                        Video Più Popolare
                    </h5>
                </div>
                <div class="card-body p-0">
                    <?php if($mostPopularVideo): ?>
                        <div class="position-relative">
                            <?php if($mostPopularVideo->thumbnail_url && $mostPopularVideo->thumbnail_url !== asset('assets/images/placeholder/placholder-1.jpg')): ?>
                                <div class="position-relative" style="cursor: pointer;" onclick="openVideoModal(<?php echo e($mostPopularVideo->id); ?>)">
                                    <img src="<?php echo e($mostPopularVideo->thumbnail_url); ?>" alt="<?php echo e($mostPopularVideo->title); ?>" class="card-img-top" style="height: 400px; object-fit: cover;">
                                    <div class="position-absolute top-50 start-50 translate-middle">
                                        <div class="play-button bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                                            <i class="ph-duotone ph-play f-s-32 text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                                        <small class="text-white f-s-12">
                                            <i class="ph-duotone ph-clock me-1"></i>
                                            <?php if($mostPopularVideo->duration && $mostPopularVideo->duration > 0): ?>
                                                <?php echo e($mostPopularVideo->formatted_duration); ?>

                                            <?php else: ?>
                                                <span title="<?php echo e(__('videos.duration_unavailable')); ?>">--:--</span>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                    <div class="position-absolute top-0 end-0 m-3">
                                        <span class="badge bg-dark f-s-12"><?php echo e($mostPopularVideo->view_count ?? $mostPopularVideo->views); ?> <?php echo e(__('profile.views')); ?></span>
                                    </div>
                                </div>
                            <?php elseif($mostPopularVideo->peertube_uuid): ?>
                                                            <div class="card-img-top video-preview bg-gradient-primary d-flex align-items-center justify-content-center position-relative"
                                 style="height: 400px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); cursor: pointer;"
                                 onclick="openVideoModal(<?php echo e($mostPopularVideo->id); ?>)">
                                    <div class="position-absolute top-50 start-50 translate-middle">
                                        <div class="play-button bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                                            <i class="ph-duotone ph-play f-s-32 text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                                        <small class="text-white f-s-12">
                                            <i class="ph-duotone ph-clock me-1"></i>
                                            <?php if($mostPopularVideo->duration && $mostPopularVideo->duration > 0): ?>
                                                <?php echo e($mostPopularVideo->formatted_duration); ?>

                                            <?php else: ?>
                                                <span title="<?php echo e(__('videos.duration_unavailable')); ?>">--:--</span>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                    <div class="position-absolute top-0 end-0 m-3">
                                        <span class="badge bg-dark f-s-12"><?php echo e($mostPopularVideo->view_count ?? $mostPopularVideo->views); ?> <?php echo e(__('profile.views')); ?></span>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="position-relative" style="cursor: pointer;" onclick="openVideoModal(<?php echo e($mostPopularVideo->id); ?>)">
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                                        <div class="text-center">
                                            <i class="ph-duotone ph-video-camera f-s-64 text-muted mb-3"></i>
                                            <div class="play-button bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                                                <i class="ph-duotone ph-play f-s-32 text-white"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="position-absolute top-0 end-0 m-3">
                                        <span class="badge bg-dark f-s-12"><?php echo e($mostPopularVideo->view_count ?? $mostPopularVideo->views); ?> <?php echo e(__('profile.views')); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title f-w-600 f-s-16 mb-2">
                                <a href="<?php echo e(route('videos.show', $mostPopularVideo)); ?>" class="text-decoration-none text-dark hover-text-primary" style="cursor: pointer;">
                                    <?php echo e($mostPopularVideo->title); ?>

                                </a>
                            </h5>
                            <?php if($mostPopularVideo->description): ?>
                                <p class="text-muted f-s-13 mb-3"><?php echo e(Str::limit($mostPopularVideo->description, 120)); ?></p>
                            <?php endif; ?>

                            <!-- Video Actions -->
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex gap-2">
                                    <?php if (isset($component)) { $__componentOriginal723641259025d9a0842581325b5584a2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal723641259025d9a0842581325b5584a2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.social-like-button','data' => ['content' => $mostPopularVideo,'type' => 'video']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('social-like-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($mostPopularVideo),'type' => 'video']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal723641259025d9a0842581325b5584a2)): ?>
<?php $attributes = $__attributesOriginal723641259025d9a0842581325b5584a2; ?>
<?php unset($__attributesOriginal723641259025d9a0842581325b5584a2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal723641259025d9a0842581325b5584a2)): ?>
<?php $component = $__componentOriginal723641259025d9a0842581325b5584a2; ?>
<?php unset($__componentOriginal723641259025d9a0842581325b5584a2); ?>
<?php endif; ?>
                                    <?php if (isset($component)) { $__componentOriginal74a3c73fa2014a1304a7d68280593565 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal74a3c73fa2014a1304a7d68280593565 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.social-view-counter','data' => ['content' => $mostPopularVideo,'type' => 'video']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('social-view-counter'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($mostPopularVideo),'type' => 'video']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal74a3c73fa2014a1304a7d68280593565)): ?>
<?php $attributes = $__attributesOriginal74a3c73fa2014a1304a7d68280593565; ?>
<?php unset($__attributesOriginal74a3c73fa2014a1304a7d68280593565); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal74a3c73fa2014a1304a7d68280593565)): ?>
<?php $component = $__componentOriginal74a3c73fa2014a1304a7d68280593565; ?>
<?php unset($__componentOriginal74a3c73fa2014a1304a7d68280593565); ?>
<?php endif; ?>
                                    
                                </div>
                                <small class="text-muted">
                                    <i class="ph-duotone ph-calendar f-s-12 me-1"></i>
                                    <?php echo e($mostPopularVideo->created_at->format('d/m/Y')); ?>

                                </small>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <div class="bg-light-primary h-80 w-80 d-flex-center rounded-circle m-auto mb-3">
                                <i class="ph-duotone ph-video-camera-slash f-s-48 text-primary"></i>
                            </div>
                            <p class="text-muted f-s-16 mb-0">Nessun video disponibile</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- 6 Video con Switch Nuovi/Popolari (Piccolo) -->
        <div class="col-lg-4">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ph-duotone ph-video-camera me-2"></i>
                            Video
                        </h5>
                        <!-- Switch Nuovi/Popolari -->
                        <div class="d-flex align-items-center">
                            <span class="me-2 text-primary f-s-14 f-w-500" id="popularLabel" style="cursor: pointer;">Popolari</span>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="videoToggle" onchange="toggleVideoContent()">
                            </div>
                            <span class="ms-2 text-muted f-s-14 f-w-500" id="newLabel" style="cursor: pointer;">Nuovi</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Contenuto Popolari (Default) -->
                    <div id="popularVideos">
                        <?php if($popularVideos->count() > 0): ?>
                            <?php $__currentLoopData = $popularVideos->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                    <div class="flex-shrink-0 position-relative" style="cursor: pointer;" onclick="window.location.href='<?php echo e(route('videos.show', $video)); ?>'">
                                        <?php if($video->thumbnail_url && $video->thumbnail_url !== asset('assets/images/placeholder/placholder-1.jpg')): ?>
                                            <img src="<?php echo e($video->thumbnail_url); ?>" alt="<?php echo e($video->title); ?>" class="rounded" style="width: 80px; height: 60px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 80px; height: 60px;">
                                                <i class="ph-duotone ph-video-camera f-s-24 text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="position-absolute top-50 start-50 translate-middle">
                                            <i class="ph-duotone ph-play f-s-16 text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 f-w-600 f-s-13">
                                            <a href="<?php echo e(route('videos.show', $video)); ?>" class="text-decoration-none text-dark hover-text-primary" style="cursor: pointer;">
                                                <?php echo e(Str::limit($video->title, 40)); ?>

                                            </a>
                                        </h6>
                                        <div class="d-flex align-items-center gap-2">
                                            <small class="text-muted f-s-11">
                                                <i class="ph-duotone ph-eye me-1"></i><?php echo e($video->view_count ?? $video->views); ?>

                                            </small>
                                            <small class="text-muted f-s-11">
                                                <i class="ph-duotone ph-heart me-1"></i><?php echo e($video->like_count); ?>

                                            </small>
                                            <small class="text-muted f-s-11">
                                                <img src="<?php echo e(asset('assets/images/snap.png')); ?>" alt="Snap" style="width: 12px; height: 12px; filter: brightness(0) saturate(100%) invert(60%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(89%) contrast(86%);" class="me-1"><?php echo e($video->snap_count ?? 0); ?>

                                            </small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <div class="bg-light-warning h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                                    <i class="ph-duotone ph-video-camera-slash f-s-24 text-warning"></i>
                                </div>
                                <p class="text-muted f-s-14 mb-0">Nessun video popolare</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Contenuto Nuovi (Nascosto) -->
                    <div id="newVideos" style="display: none;">
                        <?php if($newVideos->count() > 0): ?>
                            <?php $__currentLoopData = $newVideos->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                    <div class="flex-shrink-0 position-relative" style="cursor: pointer;" onclick="window.location.href='<?php echo e(route('videos.show', $video)); ?>'">
                                        <?php if($video->thumbnail_url && $video->thumbnail_url !== asset('assets/images/placeholder/placholder-1.jpg')): ?>
                                            <img src="<?php echo e($video->thumbnail_url); ?>" alt="<?php echo e($video->title); ?>" class="rounded" style="width: 80px; height: 60px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 80px; height: 60px;">
                                                <i class="ph-duotone ph-video-camera f-s-24 text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div class="position-absolute top-50 start-50 translate-middle">
                                            <i class="ph-duotone ph-play f-s-16 text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 f-w-600 f-s-13">
                                            <a href="<?php echo e(route('videos.show', $video)); ?>" class="text-decoration-none text-dark hover-text-primary" style="cursor: pointer;">
                                                <?php echo e(Str::limit($video->title, 40)); ?>

                                            </a>
                                        </h6>
                                        <div class="d-flex align-items-center gap-2">
                                            <small class="text-muted f-s-11">
                                                <i class="ph-duotone ph-eye me-1"></i><?php echo e($video->view_count ?? $video->views); ?>

                                            </small>
                                            <small class="text-muted f-s-11">
                                                <i class="ph-duotone ph-heart me-1"></i><?php echo e($video->like_count); ?>

                                            </small>
                                            <small class="text-muted f-s-11">
                                                <img src="<?php echo e(asset('assets/images/snap.png')); ?>" alt="Snap" style="width: 12px; height: 12px; filter: brightness(0) saturate(100%) invert(60%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(89%) contrast(86%);" class="me-1"><?php echo e($video->snap_count ?? 0); ?>

                                            </small>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <div class="bg-light-info h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                                    <i class="ph-duotone ph-video-camera-slash f-s-24 text-info"></i>
                                </div>
                                <p class="text-muted f-s-14 mb-0">Nessun video nuovo</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Video Player Modal a Tutta Pagina -->
<div class="custom-modal" id="videoPlayerModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; background: rgba(0,0,0,0.85); backdrop-filter: blur(15px);">
    <div class="modal-content" style="position: relative; width: 100%; height: 100%; display: flex; flex-direction: column;">
        <div class="modal-header" style="background: rgba(0,0,0,0.8); border-bottom: 1px solid rgba(255,255,255,0.1); padding: 1rem; display: flex; justify-content: space-between; align-items: center;">
            <h5 class="modal-title text-white" id="videoPlayerModalLabel">Video Player</h5>
            <button type="button" class="btn-close btn-close-white" onclick="closeVideoModal()" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="flex: 1; padding: 0; position: relative;">
                <!-- Loading indicator -->
                <div class="text-center position-absolute top-50 start-50 translate-middle" id="modalVideoLoading">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Caricamento video...</span>
                    </div>
                    <p class="mt-2 text-white">Caricamento video...</p>
                </div>

                <!-- Error message -->
                <div class="alert alert-danger position-absolute top-50 start-50 translate-middle" id="modalVideoError" style="display: none; z-index: 1000;">
                    <i class="ph-duotone ph-warning f-s-16 me-2"></i>
                    <span id="modalErrorMessage">Errore nel caricamento del video</span>
                </div>

                <!-- Video Container -->
                <div class="video-container position-relative d-flex align-items-center justify-content-center" id="modalVideoContainer" style="display: none; padding: 20px;">
                    <div class="w-100" style="max-width: 1200px;">
                        <div class="video-container position-relative">
                            <!-- Video Player HTML5 Nativo -->
                            <video
                                id="modalVideoPlayer"
                                class="w-100"
                                style="height: 500px; max-height: 500px; object-fit: cover; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.6); background: #000;"
                                preload="metadata"
                                controls>
                                Il tuo browser non supporta la riproduzione video.
                            </video>

                            <!-- Snap Markers sulla Progress Bar del Player -->
                            <div class="snap-markers-overlay position-absolute" id="modalSnapMarkers" style="bottom: 0; left: 0; right: 0; height: 40px; pointer-events: none;">
                                <!-- Snap markers verranno aggiunti dinamicamente -->
                            </div>
                        </div>
                    </div>

                    <!-- Pulsante per creare snap con scritta sotto -->
                    <div class="position-absolute" id="modalFloatingSnapButton" style="opacity: 1; transition: opacity 0.3s ease; z-index: 10000; top: 20px; right: 20px; display: flex; flex-direction: column; align-items: center; gap: 8px;">
                        <button type="button" class="btn btn-gradient-success hover-effect rounded-circle shadow-lg"
                                style="width: 60px; height: 60px;"
                                onclick="toggleSnapForm()">
                            <img src="<?php echo e(asset('assets/images/snap.png')); ?>" alt="Snap" style="width: 28px; height: 28px; filter: brightness(0) invert(1);">
                        </button>
                        <div class="snap-label" style="color: white; font-size: 11px; text-align: center; white-space: nowrap; text-shadow: 0 1px 2px rgba(0,0,0,0.8); font-weight: 500;">
                            Crea snap
                        </div>
                    </div>

                    <!-- Form inline per creare snap -->
                    <div class="position-absolute" id="modalSnapForm" style="display: none; z-index: 10001; top: 20px; right: 20px; background: rgba(0,0,0,0.9); border-radius: 12px; padding: 20px; min-width: 300px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1);">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-white mb-0">Crea Snap</h6>
                            <button type="button" class="btn-close btn-close-white" onclick="toggleSnapForm()"></button>
                        </div>
                        <form id="inlineSnapForm">
                            <div class="mb-3">
                                <label for="inlineSnapTitle" class="form-label text-white" style="font-size: 12px;">Titolo (opzionale)</label>
                                <input type="text" class="form-control form-control-sm" id="inlineSnapTitle"  style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white;">
                            </div>
                            <div class="mb-3">
                                <label for="inlineSnapDescription" class="form-label text-white" style="font-size: 12px;">Descrizione (opzionale)</label>
                                <textarea class="form-control form-control-sm" id="inlineSnapDescription" rows="2"  style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; resize: none;"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white" style="font-size: 12px;">Timestamp: <span id="inlineCurrentTime" class="text-warning">00:00</span></label>
                                <input type="hidden" id="inlineSnapTimestamp" value="0">
                                <input type="hidden" id="inlineSnapVideoId" value="">
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-secondary" onclick="toggleSnapForm()">Annulla</button>
                                <button type="button" class="btn btn-sm btn-primary" onclick="createInlineSnap()">Crea Snap</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* Stili per il modal video */
.custom-modal {
    background: rgba(0,0,0,0.95) !important;
    backdrop-filter: blur(20px) !important;
}

/* Stili per il pulsante snap nel modal */
#modalFloatingSnapButton {
    z-index: 99999 !important;
}

#modalFloatingSnapButton .btn {
    z-index: 99999 !important;
}

#modalFloatingSnapButton .snap-label {
    color: white !important;
    font-size: 11px !important;
    text-align: center !important;
    white-space: nowrap !important;
    text-shadow: 0 1px 2px rgba(0,0,0,0.8) !important;
    font-weight: 500 !important;
}

/* Stili per i placeholder nel form snap */
#inlineSnapTitle::placeholder,
#inlineSnapDescription::placeholder {
    color: rgba(255,255,255,0.7) !important;
    opacity: 1 !important;
}

#inlineSnapTitle:focus::placeholder,
#inlineSnapDescription:focus::placeholder {
    color: rgba(255,255,255,0.5) !important;
}

/* Responsive per schermi piccoli */
@media (max-width: 768px) {
    #modalVideoContainer {
        padding: 10px !important;
    }
}

/* Responsive per schermi molto piccoli */
@media (max-width: 480px) {
    #modalVideoContainer {
        padding: 5px !important;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Variabili globali per il modal
let modalVideoPlayer = null;
let modalCurrentVideoTime = 0;
let modalVideoDuration = 0;
let modalSnaps = [];

function toggleVideoContent() {
    const toggle = document.getElementById('videoToggle');
    const popularVideos = document.getElementById('popularVideos');
    const newVideos = document.getElementById('newVideos');
    const popularLabel = document.getElementById('popularLabel');
    const newLabel = document.getElementById('newLabel');

    if (toggle.checked) {
        // Mostra nuovi
        popularVideos.style.display = 'none';
        newVideos.style.display = 'block';
        popularLabel.classList.remove('text-primary');
        popularLabel.classList.add('text-muted');
        newLabel.classList.remove('text-muted');
        newLabel.classList.add('text-primary');
    } else {
        // Mostra popolari
        popularVideos.style.display = 'block';
        newVideos.style.display = 'none';
        popularLabel.classList.remove('text-muted');
        popularLabel.classList.add('text-primary');
        newLabel.classList.remove('text-primary');
        newLabel.classList.add('text-muted');
    }
}

// Funzione per aprire il modal video
function openVideoModal(videoId) {
    console.log('🎬 Apertura modal video per ID:', videoId);

    // Mostra il modal personalizzato
    const modal = document.getElementById('videoPlayerModal');
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden'; // Previene lo scroll

    // Carica il video
    loadVideoInModal(videoId);
}

// Funzione per chiudere il modal video
function closeVideoModal() {
    const modal = document.getElementById('videoPlayerModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto'; // Ripristina lo scroll
    
    // Ferma il video se in riproduzione
    const videoPlayer = document.getElementById('modalVideoPlayer');
    if (videoPlayer && !videoPlayer.paused) {
        videoPlayer.pause();
    }
    
    // Reset variabili
    modalCurrentVideoTime = 0;
    modalVideoDuration = 0;
    modalSnaps = [];
}

// Funzione per caricare il video nel modal
async function loadVideoInModal(videoId) {
    const loadingDiv = document.getElementById('modalVideoLoading');
    const errorDiv = document.getElementById('modalVideoError');
    const containerDiv = document.getElementById('modalVideoContainer');
    const videoPlayer = document.getElementById('modalVideoPlayer');
    const peerTubePlayer = document.getElementById('modalPeerTubePlayer');

    // Mostra loading
    loadingDiv.style.display = 'block';
    errorDiv.style.display = 'none';
    containerDiv.style.display = 'none';

    try {
        console.log('🎬 Caricamento video nel modal per ID:', videoId);

        // Prima ottieni i dati del video
        const videoResponse = await fetch(`/api/videos/${videoId}`);
        const videoData = await videoResponse.json();

        if (!videoData.success) {
            throw new Error(videoData.message || 'Errore nel caricamento del video');
        }

        const video = videoData.video;

        // Imposta il titolo del modal
        document.getElementById('videoPlayerModalLabel').textContent = video.title;

        // Usa sempre il player HTML5 nativo
        videoPlayer.style.display = 'block';

        console.log('🔗 Richiesta URL diretto per video ID:', videoId);

        // Ottieni l'URL diretto del video da PeerTube
        const urlResponse = await fetch(`/videos/${videoId}/peertube-url`);
        const urlData = await urlResponse.json();

        // Gestisci il caso in cui il video è ancora in elaborazione
        if (urlData.status === 'processing') {
            throw new Error('Il video è ancora in elaborazione su PeerTube. Riprova tra qualche minuto.');
        }

        if (urlData.success && urlData.files && urlData.files.length > 0) {
            // Usa il primo file disponibile (migliore qualità)
            const videoFile = urlData.files[0];
            console.log('✅ URL video ottenuto:', videoFile.url);

            // Crea l'elemento source
            const source = document.createElement('source');
            source.src = videoFile.url;
            source.type = 'video/mp4';

            // Rimuovi eventuali source esistenti e aggiungi quello nuovo
            videoPlayer.innerHTML = '';
            videoPlayer.appendChild(source);

            // Forza il caricamento del video
            videoPlayer.load();
        } else {
            throw new Error('Nessuna sorgente video disponibile');
        }

        // Imposta l'ID del video per le funzioni snap
        videoPlayer.setAttribute('data-video-id', video.id);

        // Carica gli snap
        loadSnapsForModal(videoId);

        // Nascondi loading e mostra video
        loadingDiv.style.display = 'none';
        containerDiv.style.display = 'block';

        // Inizializza il player
        initializeModalVideoPlayer(video);

    } catch (error) {
        console.error('❌ Errore caricamento video nel modal:', error);
        loadingDiv.style.display = 'none';
        errorDiv.style.display = 'block';
        document.getElementById('modalErrorMessage').textContent = error.message;
    }
}

// Funzione per caricare gli snap nel modal
function loadSnapsForModal(videoId) {
    console.log('🎯 Caricamento snap per video ID:', videoId);
    
    fetch(`/api/videos/${videoId}/snaps`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                modalSnaps = data.snaps || [];
                console.log('✅ Snap caricati nel modal:', modalSnaps.length);
                updateModalSnapMarkers();
            } else {
                console.log('⚠️ Nessun snap trovato per il video');
                modalSnaps = [];
                updateModalSnapMarkers();
            }
        })
        .catch(error => {
            console.error('❌ Errore caricamento snap:', error);
            modalSnaps = [];
            updateModalSnapMarkers();
        });
}

// Funzione per inizializzare il player del modal
function initializeModalVideoPlayer(video) {
    const videoPlayer = document.getElementById('modalVideoPlayer');
    modalVideoDuration = video.duration || 60;
    modalVideoPlayer = videoPlayer;

    // Event listeners per il player HTML5
    videoPlayer.addEventListener('loadedmetadata', function() {
        console.log('🎬 Video caricato nel modal - Durata:', videoPlayer.duration);
        modalVideoDuration = videoPlayer.duration || modalVideoDuration;
        updateModalSnapMarkers();
    });

    videoPlayer.addEventListener('timeupdate', function() {
        modalCurrentVideoTime = videoPlayer.currentTime;
    });

    videoPlayer.addEventListener('durationchange', function() {
        console.log('🔄 Durata video cambiata nel modal:', videoPlayer.duration);
        modalVideoDuration = videoPlayer.duration;
        updateModalSnapMarkers();
    });

    videoPlayer.addEventListener('canplay', function() {
        console.log('▶️ Video nel modal pronto per la riproduzione');
    });

    videoPlayer.addEventListener('error', function() {
        console.error('❌ Errore nel video del modal:', videoPlayer.error);
        const errorDiv = document.getElementById('modalVideoError');
        if (errorDiv) {
            errorDiv.style.display = 'block';
            document.getElementById('modalErrorMessage').textContent = 'Errore nella riproduzione del video. Riprova più tardi.';
        }
    });

    // Pulsante snap sempre visibile
    const snapButton = document.getElementById('modalFloatingSnapButton');
    if (snapButton) {
        snapButton.style.opacity = '1';
    }
}

// Funzione per aggiornare i marker degli snap nel modal
function updateModalSnapMarkers() {
    const markersContainer = document.getElementById('modalSnapMarkers');
    if (!markersContainer) return;
    
    markersContainer.innerHTML = '';

    if (!modalSnaps || modalSnaps.length === 0) return;

    console.log('🎯 Aggiornamento snap markers nel modal - Snap:', modalSnaps.length, 'Durata:', modalVideoDuration);

    // Raggruppa gli snap per timestamp
    const snapsByTimestamp = {};
    modalSnaps.forEach(snap => {
        if (!snapsByTimestamp[snap.timestamp]) {
            snapsByTimestamp[snap.timestamp] = [];
        }
        snapsByTimestamp[snap.timestamp].push(snap);
    });

    // Crea i marker
    Object.keys(snapsByTimestamp).forEach(timestamp => {
        const snapsAtTime = snapsByTimestamp[timestamp];
        const snapCount = snapsAtTime.length;
        const firstSnap = snapsAtTime[0];

        const percentage = (timestamp / modalVideoDuration) * 100;
        const leftPosition = percentage + '%';

        const marker = document.createElement('div');
        marker.className = 'snap-marker position-absolute';
        marker.style.cssText = `left: ${leftPosition}; transform: translateX(-50%); pointer-events: auto; cursor: pointer;`;
        marker.setAttribute('data-timestamp', timestamp);
        marker.onclick = () => seekToTimeInModal(timestamp);
        marker.title = `${firstSnap.title || 'Snap'} (${snapCount} snap)`;

        marker.innerHTML = `
            <div class="snap-indicator bg-success rounded-circle d-flex align-items-center justify-content-center"
                 style="width: 30px; height: 30px; border: 2px solid white; box-shadow: 0 3px 6px rgba(0,0,0,0.4);">
                <img src="<?php echo e(asset('assets/images/snap.png')); ?>" alt="Snap" style="width: 16px; height: 16px; filter: brightness(0) invert(1);">
            </div>
            ${snapCount > 1 ? `
                <div class="position-absolute top-0 end-0 bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center"
                     style="width: 24px; height: 24px; font-size: 12px; font-weight: bold; transform: translate(30%, -30%);">
                    ${snapCount}
                </div>
            ` : ''}
            <div class="snap-tooltip position-absolute bottom-100 start-50 translate-middle-x mb-1 bg-dark text-white rounded p-2"
                 style="font-size: 11px; white-space: nowrap; opacity: 0; transition: opacity 0.2s ease; pointer-events: none;">
                <strong>${firstSnap.title || 'Snap'}</strong>
                ${snapCount > 1 ? `<br><small>+${snapCount - 1} altri</small>` : ''}
            </div>
        `;

        markersContainer.appendChild(marker);
    });

    console.log('✅ Snap markers aggiornati nel modal - Posizionati sulla barra di progressione');

    // Aggiungi event listeners per i tooltip come nella pagina video
    const snapMarkers = markersContainer.querySelectorAll('.snap-marker');
    snapMarkers.forEach(marker => {
        const tooltip = marker.querySelector('.snap-tooltip');
        if (tooltip) {
            marker.addEventListener('mouseenter', function() {
                tooltip.style.opacity = '1';
            });
            marker.addEventListener('mouseleave', function() {
                tooltip.style.opacity = '0';
            });
        }
    });
}

// Funzione per saltare al tempo specifico nel modal
function seekToTimeInModal(timestamp) {
    if (modalVideoPlayer) {
        modalVideoPlayer.currentTime = timestamp;
        modalVideoPlayer.play();
    }
}

// Funzione per mostrare/nascondere il form inline degli snap
function toggleSnapForm() {
    const snapForm = document.getElementById('modalSnapForm');
    const snapButton = document.getElementById('modalFloatingSnapButton');
    
    if (snapForm.style.display === 'none') {
        // Mostra il form
        snapForm.style.display = 'block';
        snapButton.style.display = 'none';
        
        // Aggiorna il tempo corrente
        updateInlineSnapTime();
        
        console.log('🎯 Form snap aperto');
    } else {
        // Nascondi il form
        snapForm.style.display = 'none';
        snapButton.style.display = 'flex';
        
        // Pulisci i campi
        document.getElementById('inlineSnapTitle').value = '';
        document.getElementById('inlineSnapDescription').value = '';
        
        console.log('🎯 Form snap chiuso');
    }
}

// Funzione per aggiornare il tempo nel form inline
function updateInlineSnapTime() {
    const currentTimeElement = document.getElementById('inlineCurrentTime');
    const timestampElement = document.getElementById('inlineSnapTimestamp');
    const videoIdElement = document.getElementById('inlineSnapVideoId');

    if (currentTimeElement && timestampElement && videoIdElement && modalVideoPlayer) {
        const currentTime = Math.floor(modalVideoPlayer.currentTime);
        currentTimeElement.textContent = formatTimestamp(currentTime);
        timestampElement.value = currentTime;
        videoIdElement.value = modalVideoPlayer.getAttribute('data-video-id');
    }
}

// Funzione per formattare il timestamp
function formatTimestamp(seconds) {
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = seconds % 60;
    return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
}

// Funzione per creare lo snap dal form inline
function createInlineSnap() {
    const title = document.getElementById('inlineSnapTitle').value.trim();
    const timestamp = parseInt(document.getElementById('inlineSnapTimestamp').value);
    const videoId = document.getElementById('inlineSnapVideoId').value;

    console.log('🎯 Creazione snap inline - title:', title, 'timestamp:', timestamp, 'videoId:', videoId);

    if (timestamp < 0 || !videoId) {
        console.log('❌ Validazione fallita - timestamp:', timestamp, 'videoId:', videoId);
        return;
    }

    fetch(`/api/videos/${videoId}/snaps`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({ title: title, timestamp: timestamp })
    })
    .then(response => {
        if (response.status === 401) {
            // Utente non autenticato
            return response.json().then(data => {
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    showErrorMessage('Devi essere autenticato per creare uno snap');
                }
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            console.log('✅ Snap creato con successo:', data.snap);

            // Chiudi il form
            toggleSnapForm();

            // Ricarica gli snap nel modal video
            loadVideoSnaps(videoId);
            
            // Mostra un messaggio di successo
            showSuccessMessage('Snap creato con successo!');
        } else {
            console.log('❌ Errore nella creazione dello snap:', data);
            showErrorMessage(data.message || 'Errore nella creazione dello snap. Riprova.');
        }
    })
    .catch(error => {
        console.error('❌ Errore nella creazione dello snap:', error);
        showErrorMessage('Errore nella creazione dello snap. Riprova.');
    });
}

// Funzione per mostrare messaggio di successo
function showSuccessMessage(message) {
    const successDiv = document.createElement('div');
    successDiv.className = 'position-fixed';
    successDiv.style.cssText = 'top: 20px; right: 20px; z-index: 10002; background: rgba(40, 167, 69, 0.9); color: white; padding: 12px 20px; border-radius: 8px; font-size: 14px; backdrop-filter: blur(10px);';
    successDiv.textContent = message;
    document.body.appendChild(successDiv);
    
    setTimeout(() => {
        successDiv.remove();
    }, 3000);
}

// Funzione per caricare gli snap di un video
function loadVideoSnaps(videoId) {
    console.log('🎯 Ricaricamento snap per video ID:', videoId);
    
    fetch(`/api/videos/${videoId}/snaps`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                modalSnaps = data.snaps || [];
                console.log('✅ Snap ricaricati nel modal:', modalSnaps.length);
                updateModalSnapMarkers();
            } else {
                console.log('⚠️ Nessun snap trovato per il video');
                modalSnaps = [];
                updateModalSnapMarkers();
            }
        })
        .catch(error => {
            console.error('❌ Errore ricaricamento snap:', error);
            modalSnaps = [];
            updateModalSnapMarkers();
        });
}

// Funzione per mostrare messaggio di errore
function showErrorMessage(message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'position-fixed';
    errorDiv.style.cssText = 'top: 20px; right: 20px; z-index: 10002; background: rgba(220, 53, 69, 0.9); color: white; padding: 12px 20px; border-radius: 8px; font-size: 14px; backdrop-filter: blur(10px);';
    errorDiv.textContent = message;
    document.body.appendChild(errorDiv);
    
    setTimeout(() => {
        errorDiv.remove();
    }, 3000);
}

// Event listeners per i label
document.addEventListener('DOMContentLoaded', function() {
    const popularLabel = document.getElementById('popularLabel');
    const newLabel = document.getElementById('newLabel');
    const toggle = document.getElementById('videoToggle');

    popularLabel.addEventListener('click', function() {
        toggle.checked = false;
        toggleVideoContent();
    });

    newLabel.addEventListener('click', function() {
        toggle.checked = true;
        toggleVideoContent();
    });

    // Gestione chiusura modal video con ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('videoPlayerModal');
            if (modal && modal.style.display === 'block') {
                closeVideoModal();
            }
        }
    });

    // Gestione click fuori dal modal per chiudere
    document.getElementById('videoPlayerModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeVideoModal();
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/media/index.blade.php ENDPATH**/ ?>