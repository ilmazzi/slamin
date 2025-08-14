<?php $__env->startSection('title', __('dashboard.dashboard') . ' Moderazione'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Mobile-First Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                <div>
                    <h4 class="mb-0 f-w-600 f-s-18">
                        <i class="ph-duotone ph-shield-check me-2"></i>
                        <?php echo e(__('dashboard.dashboard')); ?> Moderazione
                    </h4>
                    <p class="text-muted mb-0 f-s-14">Gestisci la moderazione di tutti i contenuti</p>
                </div>
                <div class="d-flex flex-column flex-sm-row gap-2">
                    <a href="<?php echo e(route('admin.moderation.settings')); ?>" class="btn btn-outline-primary btn-sm">
                        <i class="ph-duotone ph-gear me-2"></i>
                        Impostazioni
                    </a>
                    <a href="<?php echo e(route('admin.moderation.pending')); ?>" class="btn btn-primary btn-sm">
                        <i class="ph-duotone ph-list-checks me-2"></i>
                        Contenuti in Attesa
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile-First Statistics -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card hover-effect equal-card">
                <div class="card-body text-center py-3">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-light-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="ph ph-video-camera f-s-20"></i>
                        </div>
                    </div>
                    <h6 class="mb-2 f-s-16 f-w-600">Video</h6>
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <span class="badge bg-warning f-s-12"><?php echo e($stats['videos']['pending']); ?> in attesa</span>
                        <span class="badge bg-success f-s-12"><?php echo e($stats['videos']['approved']); ?> approvati</span>
                        <span class="badge bg-danger f-s-12"><?php echo e($stats['videos']['rejected']); ?> rifiutati</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card hover-effect equal-card">
                <div class="card-body text-center py-3">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-light-success d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="ph ph-book-open f-s-20"></i>
                        </div>
                    </div>
                    <h6 class="mb-2 f-s-16 f-w-600">Poesie</h6>
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <span class="badge bg-warning f-s-12"><?php echo e($stats['poems']['pending']); ?> in attesa</span>
                        <span class="badge bg-success f-s-12"><?php echo e($stats['poems']['approved']); ?> approvate</span>
                        <span class="badge bg-danger f-s-12"><?php echo e($stats['poems']['rejected']); ?> rifiutate</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card hover-effect equal-card">
                <div class="card-body text-center py-3">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-light-info d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="ph ph-calendar f-s-20"></i>
                        </div>
                    </div>
                    <h6 class="mb-2 f-s-16 f-w-600">Eventi</h6>
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <span class="badge bg-warning f-s-12"><?php echo e($stats['events']['pending']); ?> in attesa</span>
                        <span class="badge bg-success f-s-12"><?php echo e($stats['events']['approved']); ?> approvati</span>
                        <span class="badge bg-danger f-s-12"><?php echo e($stats['events']['rejected']); ?> rifiutati</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card hover-effect equal-card">
                <div class="card-body text-center py-3">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-light-warning d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="ph ph-image f-s-20"></i>
                        </div>
                    </div>
                    <h6 class="mb-2 f-s-16 f-w-600">Foto</h6>
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <span class="badge bg-warning f-s-12"><?php echo e($stats['photos']['pending']); ?> in attesa</span>
                        <span class="badge bg-success f-s-12"><?php echo e($stats['photos']['approved']); ?> approvate</span>
                        <span class="badge bg-danger f-s-12"><?php echo e($stats['photos']['rejected']); ?> rifiutate</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card hover-effect equal-card">
                <div class="card-body text-center py-3">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-light-secondary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="ph ph-newspaper f-s-20"></i>
                        </div>
                    </div>
                    <h6 class="mb-2 f-s-16 f-w-600">Articoli</h6>
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <span class="badge bg-warning f-s-12"><?php echo e($stats['articles']['pending']); ?> in attesa</span>
                        <span class="badge bg-success f-s-12"><?php echo e($stats['articles']['approved']); ?> approvati</span>
                        <span class="badge bg-danger f-s-12"><?php echo e($stats['articles']['rejected']); ?> rifiutati</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card hover-effect equal-card">
                <div class="card-body text-center py-3">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-light-warning d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="ph ph-flag f-s-20"></i>
                        </div>
                    </div>
                    <h6 class="mb-2 f-s-16 f-w-600">Segnalazioni</h6>
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <span class="badge bg-warning f-s-12"><?php echo e($stats['reports']['pending']); ?> in attesa</span>
                        <span class="badge bg-info f-s-12"><?php echo e($stats['reports']['investigating']); ?> in analisi</span>
                        <span class="badge bg-success f-s-12"><?php echo e($stats['reports']['resolved']); ?> risolte</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile-First Pending Content and Reports -->
    <div class="row g-3">
        <!-- Video in Attesa -->
        <?php if($pendingContent['videos']->count() > 0): ?>
        <div class="col-12 col-lg-6">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                        <h5 class="mb-0 f-s-16 f-w-600">
                            <i class="ph-duotone ph-video-camera me-2"></i>
                            <?php echo e(__('common.video')); ?> in Attesa
                        </h5>
                        <a href="<?php echo e(route('admin.moderation.pending', ['type' => 'videos'])); ?>" class="btn btn-sm btn-outline-primary">
                            Vedi tutti
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php $__currentLoopData = $pendingContent['videos']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center mb-3 p-3 border rounded gap-2">
                        <div class="flex-shrink-0">
                            <img src="<?php echo e($video->thumbnail_url); ?>" alt="<?php echo e($video->title); ?>" class="rounded" style="width: 60px; height: 40px; object-fit: cover;">
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 f-s-14 f-w-600"><?php echo e(Str::limit($video->title, 30)); ?></h6>
                            <small class="text-muted f-s-12"><?php echo e($video->user->name ?? 'N/A'); ?></small>
                        </div>
                        <div class="flex-shrink-0 d-flex gap-1">
                            <button class="btn btn-sm btn-success" onclick="approveContent('videos', <?php echo e($video->id); ?>)" title="Approva">
                                <i class="ph-duotone ph-check f-s-14"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="rejectContent('videos', <?php echo e($video->id); ?>)" title="Rifiuta">
                                <i class="ph-duotone ph-x f-s-14"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Poesie in Attesa -->
        <?php if($pendingContent['poems']->count() > 0): ?>
        <div class="col-12 col-lg-6">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                        <h5 class="mb-0 f-s-16 f-w-600">
                            <i class="ph-duotone ph-book-open me-2"></i>
                            Poesie in Attesa
                        </h5>
                        <a href="<?php echo e(route('admin.moderation.pending', ['type' => 'poems'])); ?>" class="btn btn-sm btn-outline-primary">
                            Vedi tutti
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php $__currentLoopData = $pendingContent['poems']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $poem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center mb-3 p-3 border rounded gap-2">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 f-s-14 f-w-600"><?php echo e(Str::limit($poem->title, 30)); ?></h6>
                            <small class="text-muted f-s-12"><?php echo e($poem->user->name ?? 'N/A'); ?></small>
                        </div>
                        <div class="flex-shrink-0 d-flex gap-1">
                            <button class="btn btn-sm btn-success" onclick="approveContent('poems', <?php echo e($poem->id); ?>)" title="Approva">
                                <i class="ph-duotone ph-check f-s-14"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="rejectContent('poems', <?php echo e($poem->id); ?>)" title="Rifiuta">
                                <i class="ph-duotone ph-x f-s-14"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Eventi in Attesa -->
        <?php if($pendingContent['events']->count() > 0): ?>
        <div class="col-12 col-lg-6">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                        <h5 class="mb-0 f-s-16 f-w-600">
                            <i class="ph-duotone ph-calendar me-2"></i>
                            Eventi in Attesa
                        </h5>
                        <a href="<?php echo e(route('admin.moderation.pending', ['type' => 'events'])); ?>" class="btn btn-sm btn-outline-primary">
                            Vedi tutti
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php $__currentLoopData = $pendingContent['events']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center mb-3 p-3 border rounded gap-2">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 f-s-14 f-w-600"><?php echo e(Str::limit($event->title, 30)); ?></h6>
                            <small class="text-muted f-s-12"><?php echo e($event->organizer->name ?? 'N/A'); ?></small>
                        </div>
                        <div class="flex-shrink-0 d-flex gap-1">
                            <button class="btn btn-sm btn-success" onclick="approveContent('events', <?php echo e($event->id); ?>)" title="Approva">
                                <i class="ph-duotone ph-check f-s-14"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="rejectContent('events', <?php echo e($event->id); ?>)" title="Rifiuta">
                                <i class="ph-duotone ph-x f-s-14"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Foto in Attesa -->
        <?php if($pendingContent['photos']->count() > 0): ?>
        <div class="col-12 col-lg-6">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                        <h5 class="mb-0 f-s-16 f-w-600">
                            <i class="ph-duotone ph-image me-2"></i>
                            Foto in Attesa
                        </h5>
                        <a href="<?php echo e(route('admin.moderation.pending', ['type' => 'photos'])); ?>" class="btn btn-sm btn-outline-primary">
                            Vedi tutti
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php $__currentLoopData = $pendingContent['photos']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center mb-3 p-3 border rounded gap-2">
                        <div class="flex-shrink-0">
                            <img src="<?php echo e($photo->thumbnail_url); ?>" alt="<?php echo e($photo->title); ?>" class="rounded" style="width: 60px; height: 40px; object-fit: cover;">
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 f-s-14 f-w-600"><?php echo e(Str::limit($photo->title, 30)); ?></h6>
                            <small class="text-muted f-s-12"><?php echo e($photo->user->name ?? 'N/A'); ?></small>
                        </div>
                        <div class="flex-shrink-0 d-flex gap-1">
                            <button class="btn btn-sm btn-success" onclick="approveContent('photos', <?php echo e($photo->id); ?>)" title="Approva">
                                <i class="ph-duotone ph-check f-s-14"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="rejectContent('photos', <?php echo e($photo->id); ?>)" title="Rifiuta">
                                <i class="ph-duotone ph-x f-s-14"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Articoli in Attesa -->
        <?php if($pendingContent['articles']->count() > 0): ?>
        <div class="col-12 col-lg-6">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                        <h5 class="mb-0 f-s-16 f-w-600">
                            <i class="ph-duotone ph-newspaper me-2"></i>
                            Articoli in Attesa
                        </h5>
                        <a href="<?php echo e(route('admin.moderation.pending', ['type' => 'articles'])); ?>" class="btn btn-sm btn-outline-primary">
                            Vedi tutti
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php $__currentLoopData = $pendingContent['articles']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center mb-3 p-3 border rounded gap-2">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 f-s-14 f-w-600"><?php echo e(Str::limit($article->title, 30)); ?></h6>
                            <small class="text-muted f-s-12"><?php echo e($article->user->name ?? 'N/A'); ?></small>
                        </div>
                        <div class="flex-shrink-0 d-flex gap-1">
                            <button class="btn btn-sm btn-success" onclick="approveContent('articles', <?php echo e($article->id); ?>)" title="Approva">
                                <i class="ph-duotone ph-check f-s-14"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="rejectContent('articles', <?php echo e($article->id); ?>)" title="Rifiuta">
                                <i class="ph-duotone ph-x f-s-14"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Mobile-First Recent Reports -->
    <?php if(isset($reports) && $reports->count() > 0): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="mb-0 f-s-16 f-w-600">
                        <i class="ph-duotone ph-flag me-2"></i>
                        Segnalazioni Recenti
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <?php $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="border rounded p-3">
                                <div class="d-flex flex-column gap-2">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h6 class="mb-1 f-s-14 f-w-600"><?php echo e(Str::limit($report->reason, 40)); ?></h6>
                                        <span class="badge <?php echo e($report->status === 'pending' ? 'bg-warning' : ($report->status === 'resolved' ? 'bg-success' : 'bg-danger')); ?> f-s-11">
                                            <?php echo e(ucfirst($report->status)); ?>

                                        </span>
                                    </div>
                                    <small class="text-muted f-s-12">
                                        <?php echo e($report->reportable_type); ?> • <?php echo e($report->reporter->name ?? 'Anonimo'); ?>

                                    </small>
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewReport(<?php echo e($report->id); ?>)" title="Visualizza">
                                            <i class="ph-duotone ph-eye f-s-12"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" onclick="resolveReport(<?php echo e($report->id); ?>)" title="Risolve">
                                            <i class="ph-duotone ph-check-circle f-s-12"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Mobile-First Moderation Functions
document.addEventListener('DOMContentLoaded', function() {
    // Mobile-specific adjustments
    const isMobile = window.innerWidth < 768;

    if (isMobile) {
        // Adjust card spacing for mobile
        const cards = document.querySelectorAll('.card.hover-effect');
        cards.forEach(card => {
            card.classList.add('mb-3');
        });

        // Make buttons more touch-friendly on mobile
        const buttons = document.querySelectorAll('.btn-sm');
        buttons.forEach(btn => {
            btn.style.minHeight = '44px';
            btn.style.minWidth = '44px';
        });
    }

    // Responsive adjustments
    function adjustMobileLayout() {
        const isMobile = window.innerWidth < 768;
        const statsCards = document.querySelectorAll('.equal-card');

        if (isMobile) {
            statsCards.forEach(card => {
                card.classList.add('mb-3');
            });
        } else {
            statsCards.forEach(card => {
                card.classList.remove('mb-3');
            });
        }
    }

    // Initial adjustment
    adjustMobileLayout();

    // Adjust on resize
    window.addEventListener('resize', adjustMobileLayout);
});

function approveContent(type, id) {
    Swal.fire({
        title: 'Conferma Approvazione',
        text: 'Sei sicuro di voler approvare questo contenuto?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sì, Approva',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostra loading
            Swal.fire({
                title: 'Approvazione in corso...',
                text: 'Attendi mentre approvo il contenuto',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/admin/moderation/approve/${type}/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Approvato!',
                        text: data.message || 'Contenuto approvato con successo',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Errore!',
                        text: data.message || 'Errore durante l\'approvazione'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Errore di Connessione',
                    text: 'Impossibile connettersi al server. Riprova più tardi.'
                });
            });
        }
    });
}

function rejectContent(type, id) {
    Swal.fire({
        title: 'Motivo del Rifiuto',
        input: 'textarea',
        inputLabel: 'Inserisci il motivo del rifiuto',
        inputPlaceholder: 'Spiega perché questo contenuto è stato rifiutato...',
        inputAttributes: {
            'aria-label': 'Motivo del rifiuto'
        },
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Rifiuta',
        cancelButtonText: 'Annulla',
        inputValidator: (value) => {
            if (!value || value.trim().length < 10) {
                return 'Il motivo deve essere di almeno 10 caratteri';
            }
        }
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            // Mostra loading
            Swal.fire({
                title: 'Rifiuto in corso...',
                text: 'Attendi mentre rifiuto il contenuto',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/admin/moderation/reject/${type}/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({ reason: result.value.trim() })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Rifiutato!',
                        text: data.message || 'Contenuto rifiutato con successo',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Errore!',
                        text: data.message || 'Errore durante il rifiuto'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Errore di Connessione',
                    text: 'Impossibile connettersi al server. Riprova più tardi.'
                });
            });
        }
    });
}

function viewReport(reportId) {
    Swal.fire({
        title: 'Dettagli Segnalazione',
        text: 'Funzionalità in sviluppo. I dettagli completi saranno disponibili presto.',
        icon: 'info',
        confirmButtonColor: '#007bff'
    });
}

function resolveReport(reportId) {
    Swal.fire({
        title: 'Conferma Risoluzione',
        text: 'Sei sicuro di voler risolvere questa segnalazione?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sì, Risolvi',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostra loading
            Swal.fire({
                title: 'Risoluzione in corso...',
                text: 'Attendi mentre risolvo la segnalazione',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/admin/moderation/reports/${reportId}/handle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({
                    action: 'resolve',
                    notes: 'Segnalazione risolta dall\'amministratore'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Risolto!',
                        text: data.message || 'Segnalazione risolta con successo',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Errore!',
                        text: data.message || 'Errore durante la risoluzione'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Errore di Connessione',
                    text: 'Impossibile connettersi al server. Riprova più tardi.'
                });
            });
        }
    });
}
</script>
<?php $__env->stopPush(); ?>



<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/admin/moderation/index.blade.php ENDPATH**/ ?>