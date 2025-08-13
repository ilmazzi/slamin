<?php $__env->startSection('title', __('dashboard.dashboard') . ' Moderazione'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 f-w-600">
                        <i class="ph-duotone ph-shield-check me-2"></i>
                        <?php echo e(__('dashboard.dashboard')); ?> Moderazione
                    </h4>
                    <p class="text-muted mb-0">Gestisci la moderazione di tutti i contenuti</p>
                </div>
                <div>
                    <a href="<?php echo e(route('admin.moderation.settings')); ?>" class="btn btn-outline-primary me-2">
                        <i class="ph-duotone ph-gear me-2"></i>
                        Impostazioni
                    </a>
                    <a href="<?php echo e(route('admin.moderation.pending')); ?>" class="btn btn-primary">
                        <i class="ph-duotone ph-list-checks me-2"></i>
                        Contenuti in Attesa
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiche -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card hover-effect equal-card">
                <div class="card-body text-center py-3">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-light-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="ph ph-video-camera f-s-20"></i>
                        </div>
                    </div>
                    <h6 class="mb-1">Video</h6>
                    <div class="d-flex gap-2 justify-content-center">
                        <span class="badge bg-warning"><?php echo e($stats['videos']['pending']); ?> in attesa</span>
                        <span class="badge bg-success"><?php echo e($stats['videos']['approved']); ?> approvati</span>
                        <span class="badge bg-danger"><?php echo e($stats['videos']['rejected']); ?> rifiutati</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card hover-effect equal-card">
                <div class="card-body text-center py-3">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-light-success d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="ph ph-book-open f-s-20"></i>
                        </div>
                    </div>
                    <h6 class="mb-1">Poesie</h6>
                    <div class="d-flex gap-2 justify-content-center">
                        <span class="badge bg-warning"><?php echo e($stats['poems']['pending']); ?> in attesa</span>
                        <span class="badge bg-success"><?php echo e($stats['poems']['approved']); ?> approvate</span>
                        <span class="badge bg-danger"><?php echo e($stats['poems']['rejected']); ?> rifiutate</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card hover-effect equal-card">
                <div class="card-body text-center py-3">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-light-info d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="ph ph-calendar f-s-20"></i>
                        </div>
                    </div>
                    <h6 class="mb-1">Eventi</h6>
                    <div class="d-flex gap-2 justify-content-center">
                        <span class="badge bg-warning"><?php echo e($stats['events']['pending']); ?> in attesa</span>
                        <span class="badge bg-success"><?php echo e($stats['events']['approved']); ?> approvati</span>
                        <span class="badge bg-danger"><?php echo e($stats['events']['rejected']); ?> rifiutati</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card hover-effect equal-card">
                <div class="card-body text-center py-3">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-light-warning d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="ph ph-image f-s-20"></i>
                        </div>
                    </div>
                    <h6 class="mb-1">Foto</h6>
                    <div class="d-flex gap-2 justify-content-center">
                        <span class="badge bg-warning"><?php echo e($stats['photos']['pending']); ?> in attesa</span>
                        <span class="badge bg-success"><?php echo e($stats['photos']['approved']); ?> approvate</span>
                        <span class="badge bg-danger"><?php echo e($stats['photos']['rejected']); ?> rifiutate</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card hover-effect equal-card">
                <div class="card-body text-center py-3">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-light-secondary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="ph ph-newspaper f-s-20"></i>
                        </div>
                    </div>
                    <h6 class="mb-1">Articoli</h6>
                    <div class="d-flex gap-2 justify-content-center">
                        <span class="badge bg-warning"><?php echo e($stats['articles']['pending']); ?> in attesa</span>
                        <span class="badge bg-success"><?php echo e($stats['articles']['approved']); ?> approvati</span>
                        <span class="badge bg-danger"><?php echo e($stats['articles']['rejected']); ?> rifiutati</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenuti in Attesa e Segnalazioni -->
    <div class="row">
        <!-- <?php echo e(__('common.video')); ?> in Attesa -->
        <?php if($pendingContent['videos']->count() > 0): ?>
        <div class="col-lg-6 mb-4">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
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
                    <div class="d-flex align-items-center mb-3 p-2 border rounded">
                        <div class="flex-shrink-0">
                            <img src="<?php echo e($video->thumbnail_url); ?>" alt="<?php echo e($video->title); ?>" class="rounded" style="width: 60px; height: 40px; object-fit: cover;">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1"><?php echo e(Str::limit($video->title, 30)); ?></h6>
                            <small class="text-muted"><?php echo e($video->user->name ?? 'N/A'); ?></small>
                        </div>
                        <div class="flex-shrink-0">
                            <button class="btn btn-sm btn-success me-1" onclick="approveContent('videos', <?php echo e($video->id); ?>)">
                                <i class="ph-duotone ph-check"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="rejectContent('videos', <?php echo e($video->id); ?>)">
                                <i class="ph-duotone ph-x"></i>
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
        <div class="col-lg-6 mb-4">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
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
                    <div class="d-flex align-items-center mb-3 p-2 border rounded">
                        <div class="flex-grow-1">
                            <h6 class="mb-1"><?php echo e(Str::limit($poem->title, 30)); ?></h6>
                            <small class="text-muted"><?php echo e($poem->user->name ?? 'N/A'); ?></small>
                        </div>
                        <div class="flex-shrink-0">
                            <button class="btn btn-sm btn-success me-1" onclick="approveContent('poems', <?php echo e($poem->id); ?>)">
                                <i class="ph-duotone ph-check"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="rejectContent('poems', <?php echo e($poem->id); ?>)">
                                <i class="ph-duotone ph-x"></i>
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
        <div class="col-lg-6 mb-4">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
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
                    <div class="d-flex align-items-center mb-3 p-2 border rounded">
                        <div class="flex-grow-1">
                            <h6 class="mb-1"><?php echo e(Str::limit($article->title, 30)); ?></h6>
                            <small class="text-muted"><?php echo e($article->user->name ?? 'N/A'); ?></small>
                        </div>
                        <div class="flex-shrink-0">
                            <button class="btn btn-sm btn-success me-1" onclick="approveContent('articles', <?php echo e($article->id); ?>)">
                                <i class="ph-duotone ph-check"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="rejectContent('articles', <?php echo e($article->id); ?>)">
                                <i class="ph-duotone ph-x"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Segnalazioni Recenti -->
        <?php if($reports->count() > 0): ?>
        <div class="col-lg-6 mb-4">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ph-duotone ph-flag me-2 text-warning"></i>
                            Segnalazioni Recenti
                        </h5>
                        <a href="<?php echo e(route('admin.moderation.pending', ['filter' => 'reports'])); ?>" class="btn btn-sm btn-outline-warning">
                            Vedi tutte
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php $__currentLoopData = $reports->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex align-items-start mb-3 p-3 border rounded hover-effect">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle bg-light-warning d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="ph ph-flag f-s-20"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="mb-1 f-w-600"><?php echo e(Str::limit($report->content_title, 40)); ?></h6>
                                <span class="badge bg-<?php echo e($report->status_class); ?>"><?php echo e($report->status_text); ?></span>
                            </div>
                            <p class="text-muted mb-2 f-s-14">
                                <i class="ph ph-warning-triangle me-2 f-s-16 text-warning"></i>
                                <?php echo e($report->reason_text); ?>

                            </p>
                            <p class="text-muted mb-2 f-s-14">
                                <i class="ph ph-user me-2 f-s-16 text-primary"></i>
                                <?php echo e($report->user->name ?? 'N/A'); ?>

                            </p>
                            <?php if($report->description): ?>
                            <p class="text-muted mb-2 f-s-14">
                                <i class="ph ph-chat-circle me-2 f-s-16 text-info"></i>
                                <?php echo e(Str::limit($report->description, 80)); ?>

                            </p>
                            <?php endif; ?>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="ph ph-calendar me-2 f-s-16 text-secondary"></i>
                                    <?php echo e($report->created_at->diffForHumans()); ?>

                                </small>
                                
                                <!-- Pulsanti di azione -->
                                <div class="d-flex gap-1">
                                    <button class="btn btn-sm btn-primary" onclick="viewReportedContent(<?php echo e($report->id); ?>)" title="Visualizza contenuto">
                                        <i class="ph ph-eye f-s-16"></i>
                                    </button>
                                    <a href="<?php echo e(route('moderation.conversation', $report->id)); ?>" class="btn btn-sm btn-info" title="Conversazione">
                                        <i class="ph ph-chat-circle f-s-16"></i>
                                    </a>
                                    <button class="btn btn-sm btn-success" onclick="approveReportedContent('<?php echo e($report->api_content_type); ?>', <?php echo e($report->reportable_id); ?>)" title="Approva contenuto">
                                        <i class="ph ph-check f-s-16"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="rejectReportedContent('<?php echo e($report->api_content_type); ?>', <?php echo e($report->reportable_id); ?>)" title="Rifiuta contenuto">
                                        <i class="ph ph-x f-s-16"></i>
                                    </button>
                                    <button class="btn btn-sm btn-info" onclick="viewReportDetails(<?php echo e($report->id); ?>)" title="Dettagli segnalazione">
                                        <i class="ph ph-magnifying-glass f-s-16"></i>
                                    </button>
                                    <button class="btn btn-sm btn-warning" onclick="handleReport(<?php echo e($report->id); ?>, 'investigate')" title="Metti in investigazione">
                                        <i class="ph ph-magnifying-glass-plus f-s-16"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal per note di moderazione -->
<div class="modal fade" id="moderationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Note di Moderazione</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <textarea id="moderationNotes" class="form-control" rows="3" placeholder="Inserisci note opzionali..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" id="confirmModeration">Conferma</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal per dettagli segnalazione -->
<div class="modal fade" id="reportDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dettagli Segnalazione</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="reportDetailsContent">
                <!-- Contenuto dinamico -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal per gestione segnalazione -->
<div class="modal fade" id="reportActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gestione Segnalazione</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Azione da eseguire:</label>
                    <select id="reportAction" class="form-select">
                        <option value="investigate">Metti in investigazione</option>
                        <option value="resolve">Risolta</option>
                        <option value="dismiss">Archiviata</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Note (opzionali):</label>
                    <textarea id="reportActionNotes" class="form-control" rows="3" placeholder="Inserisci note..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" id="confirmReportAction">Conferma</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal per visualizzare contenuto segnalato -->
<div class="modal fade" id="reportedContentModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ph ph-eye me-2"></i>
                    <span id="contentModalTitle">Visualizza Contenuto Segnalato</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="reportedContentBody">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Caricamento...</span>
                    </div>
                    <p class="mt-2">Caricamento contenuto...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                <button type="button" class="btn btn-success" id="approveFromModal" style="display: none;">
                    <i class="ph ph-check me-1"></i>Approva
                </button>
                <button type="button" class="btn btn-danger" id="rejectFromModal" style="display: none;">
                    <i class="ph ph-x me-1"></i>Rifiuta
                </button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let currentAction = null;
let currentType = null;
let currentId = null;
let currentReportId = null;

// Funzioni per contenuti normali
function approveContent(type, id) {
    currentAction = 'approve';
    currentType = type;
    currentId = id;
    $('#moderationNotes').val('');
    $('#moderationModal').modal('show');
}

function rejectContent(type, id) {
    currentAction = 'reject';
    currentType = type;
    currentId = id;
    $('#moderationNotes').val('');
    $('#moderationModal').modal('show');
}

// Funzioni per contenuti segnalati
function approveReportedContent(type, id) {
    currentAction = 'approve';
    currentType = type;
    currentId = id;
    $('#moderationNotes').val('');
    $('#moderationModal').modal('show');
}

function rejectReportedContent(type, id) {
    currentAction = 'reject';
    currentType = type;
    currentId = id;
    $('#moderationNotes').val('');
    $('#moderationModal').modal('show');
}

// Visualizza contenuto segnalato
function viewReportedContent(reportId) {
    $('#reportedContentModal').modal('show');
    
    // Reset modal content
    $('#contentModalTitle').text('Caricamento...');
    $('#reportedContentBody').html(`
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Caricamento...</span>
            </div>
            <p class="mt-2">Caricamento contenuto...</p>
        </div>
    `);
    $('#approveFromModal, #rejectFromModal').hide();
    
    // Carica i dettagli del contenuto
    $.ajax({
        url: '<?php echo e(route("admin.moderation.reports.details", ["report" => ":report"])); ?>'.replace(':report', reportId),
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const data = response.data;
                $('#contentModalTitle').text(data.content_title);
                
                let contentHtml = `
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="ph ph-${getContentIcon(data.content_type)} me-2"></i>
                                        ${data.content_title}
                                    </h6>
                                </div>
                                <div class="card-body">
                `;
                
                // Contenuto specifico per tipo
                if (data.content_type === 'Video' && data.video_url) {
                    contentHtml += `
                        <div class="ratio ratio-16x9 mb-3">
                            <video controls class="w-100">
                                <source src="${data.video_url}" type="video/mp4">
                                Il tuo browser non supporta il tag video.
                            </video>
                        </div>
                    `;
                } else if (data.content_type === 'Foto' && data.image_url) {
                    contentHtml += `
                        <div class="text-center mb-3">
                            <img src="${data.image_url}" class="img-fluid rounded" style="max-height: 400px;" alt="${data.content_title}">
                        </div>
                    `;
                } else if (data.content_thumbnail) {
                    contentHtml += `
                        <div class="text-center mb-3">
                            <img src="${data.content_thumbnail}" class="img-fluid rounded" style="max-height: 300px;" alt="${data.content_title}">
                        </div>
                    `;
                }
                

                
                if (data.content_body && data.content_body.trim() !== '') {
                    contentHtml += `
                        <div class="content-body">
                            ${formatContent(data.content_body, data.content_type)}
                        </div>
                    `;
                } else {
                    contentHtml += `
                        <div class="content-body">
                            <div class="alert alert-info">
                                <i class="ph ph-info me-2"></i>
                                <strong>Contenuto non disponibile</strong>
                                <p class="mb-0 mt-2">Il contenuto di questo elemento non è visualizzabile in questo formato.</p>
                            </div>
                        </div>
                    `;
                }
                
                contentHtml += `
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="ph ph-flag me-2 text-warning"></i>
                                        Dettagli Segnalazione
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>Motivo:</strong>
                                        <span class="badge bg-warning">${data.report_reason}</span>
                                    </div>
                                    ${data.report_description ? `
                                        <div class="mb-3">
                                            <strong>Descrizione:</strong>
                                            <p class="text-muted small">${data.report_description}</p>
                                        </div>
                                    ` : ''}
                                    <div class="mb-3">
                                        <strong>Segnalato da:</strong>
                                        <p class="text-muted small mb-1">${data.reporter_name}</p>
                                        <small class="text-muted">${data.reported_at}</small>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Status:</strong>
                                        <span class="badge bg-${getStatusColor(data.status)}">${data.status}</span>
                                    </div>
                                    ${data.author ? `
                                        <div class="mb-3">
                                            <strong>Autore:</strong>
                                            <p class="text-muted small">${data.author}</p>
                                        </div>
                                    ` : ''}
                                    ${data.content_url ? `
                                        <div class="mb-3">
                                            <a href="${data.content_url}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                <i class="ph ph-external-link me-1"></i>Vedi originale
                                            </a>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                $('#reportedContentBody').html(contentHtml);
                
                // Mostra i pulsanti di azione
                $('#approveFromModal, #rejectFromModal').show();
                
                // Salva i dati per i pulsanti
                $('#approveFromModal').data('type', data.content_type.toLowerCase() + 's');
                $('#approveFromModal').data('id', data.report_id);
                $('#rejectFromModal').data('type', data.content_type.toLowerCase() + 's');
                $('#rejectFromModal').data('id', data.report_id);
                
            } else {
                $('#reportedContentBody').html(`
                    <div class="text-center text-danger">
                        <i class="ph ph-warning f-s-48 mb-3"></i>
                        <h5>Errore</h5>
                        <p>${response.message}</p>
                    </div>
                `);
            }
        },
        error: function() {
            $('#reportedContentBody').html(`
                <div class="text-center text-danger">
                    <i class="ph ph-warning f-s-48 mb-3"></i>
                    <h5>Errore</h5>
                    <p>Errore durante il caricamento del contenuto</p>
                </div>
            `);
        }
    });
}

// Funzioni helper
function getContentIcon(contentType) {
    const icons = {
        'Video': 'video-camera',
        'Foto': 'image',
        'Articolo': 'article',
        'Poesia': 'book-open',
        'Evento': 'calendar',
        'Commento': 'chat-circle'
    };
    return icons[contentType] || 'file-text';
}

function getStatusColor(status) {
    const colors = {
        'In attesa': 'warning',
        'In investigazione': 'info',
        'Risolta': 'success',
        'Archiviata': 'secondary'
    };
    return colors[status] || 'secondary';
}

function formatContent(content, contentType) {
    if (contentType === 'Poesia') {
        return `<pre class="poem-content">${content}</pre>`;
    } else if (contentType === 'Articolo') {
        // Per gli articoli, il contenuto è HTML e deve essere renderizzato
        return `<div class="article-content">${content}</div>`;
    } else {
        return `<p>${content}</p>`;
    }
}

// Visualizza dettagli segnalazione
function viewReportDetails(reportId) {
    $('#reportDetailsContent').html(`
        <div class="text-center">
            <i class="ph-duotone ph-flag f-s-48 text-warning mb-3"></i>
            <h5>Dettagli Segnalazione</h5>
            <p class="text-muted">Funzionalità in sviluppo. I dettagli completi saranno disponibili presto.</p>
        </div>
    `);
    $('#reportDetailsModal').modal('show');
}

// Gestione segnalazione con modal elegante
function handleReport(reportId, action) {
    currentReportId = reportId;
    $('#reportAction').val(action);
    $('#reportActionNotes').val('');
    $('#reportActionModal').modal('show');
}

// Conferma azione segnalazione
$('#confirmReportAction').click(function() {
    const action = $('#reportAction').val();
    const notes = $('#reportActionNotes').val();

    $.ajax({
        url: '<?php echo e(route("admin.moderation.reports.handle", ["report" => ":report"])); ?>'.replace(':report', currentReportId),
        method: 'POST',
        data: {
            action: action,
            notes: notes,
            _token: '<?php echo e(csrf_token()); ?>'
        },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Successo!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Errore!',
                    text: response.message
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Errore!',
                text: 'Errore durante l\'operazione'
            });
        }
    });

    $('#reportActionModal').modal('hide');
});

// Event listener per i pulsanti del modal contenuto
$('#approveFromModal').click(function() {
    const type = $(this).data('type');
    const id = $(this).data('id');
    approveReportedContent(type, id);
    $('#reportedContentModal').modal('hide');
});

$('#rejectFromModal').click(function() {
    const type = $(this).data('type');
    const id = $(this).data('id');
    rejectReportedContent(type, id);
    $('#reportedContentModal').modal('hide');
});

// Conferma moderazione contenuto
$('#confirmModeration').click(function() {
    const notes = $('#moderationNotes').val();
    let url;

    if (currentAction === 'approve') {
        url = '<?php echo e(route("admin.moderation.approve", ["type" => ":type", "id" => ":id"])); ?>'
            .replace(':type', currentType)
            .replace(':id', currentId);
    } else if (currentAction === 'reject') {
        url = '<?php echo e(route("admin.moderation.reject", ["type" => ":type", "id" => ":id"])); ?>'
            .replace(':type', currentType)
            .replace(':id', currentId);
    }

    $.ajax({
        url: url,
        method: 'POST',
        data: {
            notes: notes,
            _token: '<?php echo e(csrf_token()); ?>'
        },
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Successo!',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Errore!',
                    text: response.message
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Errore!',
                text: 'Errore durante l\'operazione'
            });
        }
    });

    $('#moderationModal').modal('hide');
});
</script>
<?php $__env->stopPush(); ?>

<!-- <?php echo e(__('common.kanban_board')); ?> JS -->
<script src="<?php echo e(asset('assets/js/kanban_board.js')); ?>?v=<?php echo e(time()); ?>"></script>



<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/admin/moderation/index.blade.php ENDPATH**/ ?>