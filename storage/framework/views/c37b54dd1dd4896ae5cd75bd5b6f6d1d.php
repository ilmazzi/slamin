<?php $__env->startSection('title', 'Dashboard Moderazione'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title f-s-18 f-w-600">Dashboard Moderazione</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="<?php echo e(route('dashboard')); ?>" class="f-s-14 f-w-500">
                            <span><i class="ph-duotone ph-gauge f-s-16"></i> <?php echo e(__('dashboard.dashboard')); ?></span>
                        </a>
                    </li>
                    <li class="active">
                        <span class="f-s-14 f-w-500">
                            <i class="ph-duotone ph-shield-check f-s-16"></i> Moderazione
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Header Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                    <div>
                        <h5 class="mb-1 f-w-600 f-s-16">
                            <i class="ph-duotone ph-shield-check me-2"></i>
                            Gestisci la moderazione di tutti i contenuti
                        </h5>
                        <p class="text-muted mb-0 f-s-14">Approva o rifiuta i contenuti degli utenti</p>
                    </div>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <button class="btn btn-outline-info btn-sm" onclick="showModerationHelp()">
                            <i class="ph-duotone ph-question me-2"></i>
                            Guida Moderazione
                        </button>
                        <a href="<?php echo e(route('admin.moderation.settings')); ?>" class="btn btn-outline-primary btn-sm">
                            <i class="ph-duotone ph-gear me-2"></i>
                            Impostazioni
                        </a>
                        <a href="<?php echo e(route('admin.moderation.index', ['type' => 'all', 'status' => 'pending'])); ?>" class="btn btn-primary btn-sm">
                            <i class="ph-duotone ph-list-checks me-2"></i>
                            Contenuti in Attesa
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <?php if(isset($stats)): ?>
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
        <?php endif; ?>

        <!-- Filters - SEMPRE visibili -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card hover-effect">
                    <div class="card-header">
                        <h6 class="mb-0 f-s-16 f-w-600">
                            <i class="ph-duotone ph-funnel me-2"></i>
                            Cerca Contenuti
                        </h6>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="<?php echo e(route('admin.moderation.index')); ?>" class="row g-3">
                            <div class="col-12 col-sm-6 col-lg-3">
                                <label class="form-label f-s-14 f-w-500">Tipo di Contenuto</label>
                                <select name="type" class="form-select">
                                    <option value="all" <?php echo e(isset($type) && $type == 'all' ? 'selected' : ''); ?>>Tutti i contenuti</option>
                                    <option value="videos" <?php echo e(isset($type) && $type == 'videos' ? 'selected' : ''); ?>>Video</option>
                                    <option value="poems" <?php echo e(isset($type) && $type == 'poems' ? 'selected' : ''); ?>>Poesie</option>
                                    <option value="events" <?php echo e(isset($type) && $type == 'events' ? 'selected' : ''); ?>>Eventi</option>
                                    <option value="photos" <?php echo e(isset($type) && $type == 'photos' ? 'selected' : ''); ?>>Foto</option>
                                    <option value="articles" <?php echo e(isset($type) && $type == 'articles' ? 'selected' : ''); ?>>Articoli</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <label class="form-label f-s-14 f-w-500">Status</label>
                                <select name="status" class="form-select">
                                    <option value="pending" <?php echo e(isset($status) && $status == 'pending' ? 'selected' : ''); ?>>In Attesa</option>
                                    <option value="approved" <?php echo e(isset($status) && $status == 'approved' ? 'selected' : ''); ?>>Approvati</option>
                                    <option value="rejected" <?php echo e(isset($status) && $status == 'rejected' ? 'selected' : ''); ?>>Rifiutati</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <label class="form-label f-s-14 f-w-500">Ordina per</label>
                                <select name="sort" class="form-select">
                                    <option value="newest" <?php echo e(isset($sort) && $sort == 'newest' ? 'selected' : ''); ?>>Più recenti</option>
                                    <option value="oldest" <?php echo e(isset($sort) && $sort == 'oldest' ? 'selected' : ''); ?>>Più vecchi</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <label class="form-label f-s-14 f-w-500">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="ph-duotone ph-magnifying-glass me-1"></i>
                                        Cerca
                                    </button>
                                    <a href="<?php echo e(route('admin.moderation.index')); ?>" class="btn btn-light btn-sm">
                                        <i class="ph-duotone ph-arrow-clockwise me-1"></i>
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php if(isset($contents)): ?>
        <!-- Content List -->

        <!-- Content List -->
        <div class="row g-3">
            <?php $__empty_1 = true; $__currentLoopData = $contents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $content): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-12 col-lg-6">
                <div class="card hover-effect">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 f-s-16 f-w-600"><?php echo e(ucfirst($content->type ?? 'Contenuto')); ?></h6>
                            <span class="badge <?php echo e($content->status == 'pending' ? 'bg-warning' : ($content->status == 'approved' ? 'bg-success' : 'bg-danger')); ?> f-s-12">
                                <?php echo e(ucfirst($content->status ?? 'Sconosciuto')); ?>

                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="mb-2"><?php echo e(Str::limit($content->title ?? $content->content ?? 'N/A', 50)); ?></h6>
                        <small class="text-muted d-block mb-3">
                            <i class="ph-duotone ph-user me-1"></i>
                            <?php echo e($content->user->name ?? 'N/A'); ?> •
                            <i class="ph-duotone ph-calendar me-1"></i>
                            <?php echo e($content->created_at->diffForHumans()); ?>

                        </small>

                        <div class="d-flex gap-2">
                            <button class="btn btn-success btn-sm" onclick="approveContent('<?php echo e($content->type); ?>', <?php echo e($content->id); ?>)">
                                <i class="ph-duotone ph-check f-s-14 me-1"></i>
                                Approva
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="rejectContent('<?php echo e($content->type); ?>', <?php echo e($content->id); ?>)">
                                <i class="ph-duotone ph-x f-s-14 me-1"></i>
                                Rifiuta
                            </button>
                            <button class="btn btn-primary btn-sm" onclick="viewContent('<?php echo e($content->type); ?>', <?php echo e($content->id); ?>)">
                                <i class="ph-duotone ph-eye f-s-14"></i>
                            </button>
                        </div>

                        <?php if(isset($content->reports_count) && $content->reports_count > 0): ?>
                        <div class="alert alert-warning py-2 mt-3 mb-0">
                            <small class="f-s-12">
                                <i class="ph-duotone ph-flag me-1"></i>
                                <?php echo e($content->reports_count); ?> segnalazione<?php echo e($content->reports_count > 1 ? 'i' : ''); ?>

                            </small>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ph-duotone ph-check-circle f-s-48 text-success mb-3"></i>
                        <h5 class="mb-0 f-s-18 f-w-600">Nessun contenuto trovato</h5>
                        <p class="text-muted f-s-14">Prova a modificare i filtri di ricerca</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php else: ?>
        <!-- Pending Content Preview -->
        <?php if(isset($pendingContent)): ?>
        <div class="row g-3">
            <?php $__currentLoopData = ['videos', 'poems', 'events', 'photos', 'articles']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contentType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(isset($pendingContent[$contentType]) && $pendingContent[$contentType]->count() > 0): ?>
                <div class="col-12 col-lg-6">
                    <div class="card hover-effect">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 f-s-16 f-w-600"><?php echo e(ucfirst($contentType)); ?> in Attesa</h6>
                                <a href="<?php echo e(route('admin.moderation.index', ['type' => $contentType, 'status' => 'pending'])); ?>" class="btn btn-sm btn-outline-primary">
                                    Vedi tutti
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php $__currentLoopData = $pendingContent[$contentType]->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                                <div>
                                    <h6 class="mb-1 f-s-14 f-w-600"><?php echo e(Str::limit($item->title ?? $item->content ?? 'N/A', 30)); ?></h6>
                                    <small class="text-muted f-s-12"><?php echo e($item->user->name ?? 'N/A'); ?></small>
                                </div>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-sm btn-success" onclick="approveContent('<?php echo e($contentType); ?>', <?php echo e($item->id); ?>)" title="Approva">
                                        <i class="ph-duotone ph-check f-s-12"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="rejectContent('<?php echo e($contentType); ?>', <?php echo e($item->id); ?>)" title="Rifiuta">
                                        <i class="ph-duotone ph-x f-s-12"></i>
                                    </button>
                                    <button class="btn btn-sm btn-primary" onclick="viewContent('<?php echo e($contentType); ?>', <?php echo e($item->id); ?>)" title="Visualizza">
                                        <i class="ph-duotone ph-eye f-s-12"></i>
                                    </button>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>

        <!-- Recent Reports -->
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
                            <?php $__currentLoopData = $reports->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="border rounded p-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="badge bg-secondary me-2"><?php echo e(getContentTypeName($report->reportable_type)); ?></span>
                                        <span class="badge bg-warning"><?php echo e($report->status); ?></span>
                                    </div>
                                    <h6 class="mb-1 f-s-14 f-w-600"><?php echo e(Str::limit($report->content_title ?? $report->reason, 40)); ?></h6>
                                    <small class="text-muted f-s-12 d-block mb-2">
                                        <?php echo e($report->reason); ?> • <?php echo e($report->user->name ?? 'Anonimo'); ?>

                                    </small>
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-sm btn-primary" onclick="viewReportContent(<?php echo e($report->id); ?>)" title="Visualizza Contenuto">
                                            <i class="ph-duotone ph-eye f-s-12"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" onclick="investigateReport(<?php echo e($report->id); ?>)" title="Investiga">
                                            <i class="ph-duotone ph-magnifying-glass f-s-12"></i>
                                        </button>
                                        <button class="btn btn-sm btn-success" onclick="resolveReport(<?php echo e($report->id); ?>)" title="Risolvi">
                                            <i class="ph-duotone ph-check-circle f-s-12"></i>
                                        </button>
                                        <button class="btn btn-sm btn-secondary" onclick="dismissReport(<?php echo e($report->id); ?>)" title="Respingi">
                                            <i class="ph-duotone ph-x-circle f-s-12"></i>
                                        </button>
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
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php
function getContentTypeName($type) {
    switch ($type) {
        case 'App\Models\Video':
            return 'Video';
        case 'App\Models\Poem':
            return 'Poesia';
        case 'App\Models\Event':
            return 'Evento';
        case 'App\Models\Photo':
            return 'Foto';
        case 'App\Models\Article':
            return 'Articolo';
        default:
            return 'Contenuto';
    }
}
?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile adjustments
    if (window.innerWidth < 768) {
        const buttons = document.querySelectorAll('.btn-sm');
        buttons.forEach(btn => {
            btn.style.minHeight = '44px';
            btn.style.minWidth = '44px';
        });
    }
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

function viewContent(type, id) {
    // Mostra loading
    Swal.fire({
        title: 'Caricamento...',
        text: 'Sto caricando il contenuto',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Fetch del contenuto
    fetch(`/admin/moderation/content/${type}/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const contentType = getContentTypeFromClass(data.content.type);

                let contentHtml = `
                    <div class="text-start">
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-secondary me-2">${contentType}</span>
                            <span class="badge bg-${getStatusColor(data.content.status)}">${data.content.status}</span>
                        </div>

                        <h5 class="mb-3 f-w-600">${data.content.title}</h5>

                        ${data.content.description ? `
                            <div class="mb-3">
                                <h6 class="f-s-14 f-w-500 mb-2">Descrizione:</h6>
                                <p class="text-muted f-s-14">${data.content.description}</p>
                            </div>
                        ` : ''}

                        ${data.content.content ? `
                            <div class="mb-3">
                                <h6 class="f-s-14 f-w-500 mb-2">Contenuto:</h6>
                                <div class="border rounded p-3 bg-light">
                                    <p class="f-s-14 mb-0">${data.content.content}</p>
                                </div>
                            </div>
                        ` : ''}

                        ${data.content.url ? `
                            <div class="mb-3">
                                <h6 class="f-s-14 f-w-500 mb-2">URL:</h6>
                                <a href="${data.content.url}" target="_blank" class="text-primary f-s-14">${data.content.url}</a>
                            </div>
                        ` : ''}

                        ${data.content.location ? `
                            <div class="mb-3">
                                <h6 class="f-s-14 f-w-500 mb-2">Luogo:</h6>
                                <p class="text-muted f-s-14">${data.content.location}</p>
                            </div>
                        ` : ''}

                        ${data.content.start_date ? `
                            <div class="mb-3">
                                <h6 class="f-s-14 f-w-500 mb-2">Date Evento:</h6>
                                <p class="text-muted f-s-14">
                                    <i class="ph-duotone ph-calendar me-1"></i>
                                    ${data.content.start_date}${data.content.end_date ? ` - ${data.content.end_date}` : ''}
                                </p>
                            </div>
                        ` : ''}

                        <div class="border-top pt-3">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">
                                        <i class="ph-duotone ph-user me-1"></i>
                                        ${data.content.author}
                                    </small>
                                </div>
                                <div class="col-6 text-end">
                                    <small class="text-muted">
                                        <i class="ph-duotone ph-calendar me-1"></i>
                                        ${data.content.created_at}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                Swal.fire({
                    title: 'Contenuto',
                    html: contentHtml,
                    width: '700px',
                    confirmButtonColor: '#007bff',
                    confirmButtonText: 'Chiudi',
                    showCloseButton: true
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Errore!',
                    text: data.message || 'Impossibile caricare il contenuto'
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

function viewReport(reportId) {
    Swal.fire({
        title: 'Dettagli Segnalazione',
        text: 'Funzionalità in sviluppo. I dettagli completi saranno disponibili presto.',
        icon: 'info',
        confirmButtonColor: '#007bff'
    });
}

function viewReportContent(reportId) {
    // Mostra loading
    Swal.fire({
        title: 'Caricamento...',
        text: 'Sto caricando il contenuto',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Fetch del contenuto del report
    fetch(`/admin/moderation/reports/${reportId}/content`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let contentHtml = '';

                if (data.content) {
                    const contentType = getContentTypeFromClass(data.content.type);

                    contentHtml = `
                        <div class="text-start">
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-secondary me-2">${contentType}</span>
                                <span class="badge bg-${getStatusColor(data.content.status)}">${data.content.status}</span>
                            </div>

                            <h5 class="mb-3 f-w-600">${data.content.title}</h5>

                            ${data.content.description ? `
                                <div class="mb-3">
                                    <h6 class="f-s-14 f-w-500 mb-2">Descrizione:</h6>
                                    <p class="text-muted f-s-14">${data.content.description}</p>
                                </div>
                            ` : ''}

                            ${data.content.content ? `
                                <div class="mb-3">
                                    <h6 class="f-s-14 f-w-500 mb-2">Contenuto:</h6>
                                    <div class="border rounded p-3 bg-light">
                                        <p class="f-s-14 mb-0">${data.content.content}</p>
                                    </div>
                                </div>
                            ` : ''}

                            ${data.content.url ? `
                                <div class="mb-3">
                                    <h6 class="f-s-14 f-w-500 mb-2">URL:</h6>
                                    <a href="${data.content.url}" target="_blank" class="text-primary f-s-14">${data.content.url}</a>
                                </div>
                            ` : ''}

                            ${data.content.location ? `
                                <div class="mb-3">
                                    <h6 class="f-s-14 f-w-500 mb-2">Luogo:</h6>
                                    <p class="text-muted f-s-14">${data.content.location}</p>
                                </div>
                            ` : ''}

                            ${data.content.start_date ? `
                                <div class="mb-3">
                                    <h6 class="f-s-14 f-w-500 mb-2">Date Evento:</h6>
                                    <p class="text-muted f-s-14">
                                        <i class="ph-duotone ph-calendar me-1"></i>
                                        ${data.content.start_date}${data.content.end_date ? ` - ${data.content.end_date}` : ''}
                                    </p>
                                </div>
                            ` : ''}

                            <div class="border-top pt-3">
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted">
                                            <i class="ph-duotone ph-user me-1"></i>
                                            ${data.content.author}
                                        </small>
                                    </div>
                                    <div class="col-6 text-end">
                                        <small class="text-muted">
                                            <i class="ph-duotone ph-calendar me-1"></i>
                                            ${data.content.created_at}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    contentHtml = '<p class="text-muted">Contenuto non trovato o eliminato.</p>';
                }

                Swal.fire({
                    title: 'Contenuto Segnalato',
                    html: contentHtml,
                    width: '700px',
                    confirmButtonColor: '#007bff',
                    confirmButtonText: 'Chiudi',
                    showCloseButton: true
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Errore!',
                    text: data.message || 'Impossibile caricare il contenuto'
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

function getContentTypeFromClass(className) {
    switch (className) {
        case 'App\\Models\\Video':
            return 'Video';
        case 'App\\Models\\Poem':
            return 'Poesia';
        case 'App\\Models\\Event':
            return 'Evento';
        case 'App\\Models\\Photo':
            return 'Foto';
        case 'App\\Models\\Article':
            return 'Articolo';
        default:
            return 'Contenuto';
    }
}

function getStatusColor(status) {
    switch (status) {
        case 'pending':
            return 'warning';
        case 'approved':
            return 'success';
        case 'rejected':
            return 'danger';
        default:
            return 'secondary';
    }
}

function showModerationHelp() {
    Swal.fire({
        title: 'Guida alla Moderazione',
        html: `
            <div class="text-start">
                <h6 class="mb-3 f-w-600">Processo di Moderazione:</h6>

                <div class="mb-3">
                    <h6 class="f-s-14 f-w-500 mb-2">📋 Contenuti in Attesa:</h6>
                    <p class="f-s-14 text-muted mb-2">I contenuti nuovi vengono automaticamente messi "in attesa" di moderazione.</p>
                    <ul class="f-s-14 text-muted">
                        <li><strong>Approva:</strong> Il contenuto diventa pubblico e visibile a tutti</li>
                        <li><strong>Rifiuta:</strong> Il contenuto viene rimosso e non pubblicato</li>
                        <li><strong>Visualizza:</strong> Apre un modal per vedere il contenuto completo</li>
                    </ul>
                </div>

                <div class="mb-3">
                    <h6 class="f-s-14 f-w-500 mb-2">🚩 Segnalazioni:</h6>
                    <p class="f-s-14 text-muted mb-2">Gli utenti possono segnalare contenuti inappropriati.</p>
                    <ul class="f-s-14 text-muted">
                        <li><strong>Visualizza Contenuto:</strong> Vedi il contenuto segnalato</li>
                        <li><strong>Investiga:</strong> Metti la segnalazione "in analisi" per esaminarla meglio</li>
                        <li><strong>Risolvi:</strong> Accetta la segnalazione e rifiuta il contenuto</li>
                        <li><strong>Respingi:</strong> Rifiuta la segnalazione (il contenuto rimane)</li>
                    </ul>
                </div>

                <div class="mb-3">
                    <h6 class="f-s-14 f-w-500 mb-2">⚙️ Filtri di Ricerca:</h6>
                    <p class="f-s-14 text-muted mb-2">Usa i filtri per trovare contenuti specifici:</p>
                    <ul class="f-s-14 text-muted">
                        <li><strong>Tipo:</strong> Video, Poesie, Eventi, Foto, Articoli</li>
                        <li><strong>Status:</strong> In attesa, Approvati, Rifiutati</li>
                        <li><strong>Ordinamento:</strong> Più recenti, Più vecchi</li>
                    </ul>
                </div>
            </div>
        `,
        width: '600px',
        confirmButtonColor: '#007bff',
        confirmButtonText: 'Ho capito!',
        showCloseButton: true
    });
}

function investigateReport(reportId) {
    Swal.fire({
        title: 'Conferma Investigazione',
        text: 'Sei sicuro di voler mettere questa segnalazione sotto investigazione?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sì, Investiga',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            handleReportAction(reportId, 'investigate');
        }
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
            handleReportAction(reportId, 'resolve');
        }
    });
}

function dismissReport(reportId) {
    Swal.fire({
        title: 'Conferma Respingimento',
        text: 'Sei sicuro di voler respingere questa segnalazione?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#6c757d',
        cancelButtonColor: '#dc3545',
        confirmButtonText: 'Sì, Respingi',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            handleReportAction(reportId, 'dismiss');
        }
    });
}

function handleReportAction(reportId, action) {
    const actionTexts = {
        'investigate': 'Investigazione in corso...',
        'resolve': 'Risoluzione in corso...',
        'dismiss': 'Respingimento in corso...'
    };

    Swal.fire({
        title: actionTexts[action],
        text: 'Attendi mentre elaboro la segnalazione',
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
            action: action,
            notes: `Segnalazione ${action === 'investigate' ? 'messa sotto investigazione' : action === 'resolve' ? 'risolta' : 'respinta'} dall'amministratore`
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Completato!',
                text: data.message || `Segnalazione ${action === 'investigate' ? 'messa sotto investigazione' : action === 'resolve' ? 'risolta' : 'respinta'} con successo`,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Errore!',
                text: data.message || `Errore durante la ${action === 'investigate' ? 'investigazione' : action === 'resolve' ? 'risoluzione' : 'respingimento'}`
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
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/admin/moderation/index.blade.php ENDPATH**/ ?>