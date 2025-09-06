

<?php $__env->startSection('title', 'Log Attività - Admin'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">Log di Attività</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="<?php echo e(route('dashboard')); ?>" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-house f-s-16"></i> Dashboard
                            </span>
                        </a>
                    </li>
                    <li class="">
                        <a href="<?php echo e(route('admin.logs.index')); ?>" class="f-s-14 f-w-500">Logs</a>
                    </li>
                    <li class="active">
                        <a href="#" class="f-s-14 f-w-500">Attività</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Statistiche -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card hover-effect equal-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="widget-icon bg-light-primary text-primary">
                                    <i class="ph ph-list"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Totale Log</h6>
                                <h4 class="mb-0"><?php echo e($stats['total']); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $__currentLoopData = $stats['by_level']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $levelStat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-xl-3 col-md-6">
                    <div class="card hover-effect equal-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="widget-icon bg-light-<?php echo e($levelStat->level == 'error' ? 'danger' : ($levelStat->level == 'warning' ? 'warning' : 'info')); ?> text-<?php echo e($levelStat->level == 'error' ? 'danger' : ($levelStat->level == 'warning' ? 'warning' : 'info')); ?>">
                                        <i class="ph ph-<?php echo e($levelStat->level == 'error' ? 'warning' : ($levelStat->level == 'warning' ? 'warning-circle' : 'info')); ?>"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1"><?php echo e(ucfirst($levelStat->level)); ?></h6>
                                    <h4 class="mb-0"><?php echo e($levelStat->count); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Filtri -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">Periodo</label>
                        <select name="hours" class="form-select">
                            <option value="1" <?php echo e($hours == 1 ? 'selected' : ''); ?>>1 ora</option>
                            <option value="6" <?php echo e($hours == 6 ? 'selected' : ''); ?>>6 ore</option>
                            <option value="24" <?php echo e($hours == 24 ? 'selected' : ''); ?>>24 ore</option>
                            <option value="168" <?php echo e($hours == 168 ? 'selected' : ''); ?>>1 settimana</option>
                            <option value="0" <?php echo e($hours == 0 ? 'selected' : ''); ?>>Tutti</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Livello</label>
                        <select name="level" class="form-select">
                            <option value="all" <?php echo e($level == 'all' ? 'selected' : ''); ?>>Tutti</option>
                            <option value="critical" <?php echo e($level == 'critical' ? 'selected' : ''); ?>>Critical</option>
                            <option value="error" <?php echo e($level == 'error' ? 'selected' : ''); ?>>Error</option>
                            <option value="warning" <?php echo e($level == 'warning' ? 'selected' : ''); ?>>Warning</option>
                            <option value="info" <?php echo e($level == 'info' ? 'selected' : ''); ?>>Info</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Categoria</label>
                        <select name="category" class="form-select">
                            <option value="all" <?php echo e($category == 'all' ? 'selected' : ''); ?>>Tutte</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($cat); ?>" <?php echo e($category == $cat ? 'selected' : ''); ?>><?php echo e($cat); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Utente</label>
                        <select name="user" class="form-select">
                            <option value="all" <?php echo e($user == 'all' ? 'selected' : ''); ?>>Tutti</option>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($userData['id']); ?>" <?php echo e($user == $userData['id'] ? 'selected' : ''); ?>><?php echo e($userData['name']); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="ph ph-magnifying-glass me-1"></i>Filtra
                        </button>
                        <a href="<?php echo e(route('admin.logs.activity')); ?>" class="btn btn-light me-2">
                            <i class="ph ph-arrow-clockwise me-1"></i>Reset
                        </a>
                        <a href="<?php echo e(route('admin.logs.download', ['type' => 'activity', 'hours' => $hours, 'level' => $level])); ?>" class="btn btn-success">
                            <i class="ph ph-download me-1"></i>Scarica
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabella Log -->
        <div class="card hover-effect">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">
                    <i class="ph ph-list me-2"></i>Log di Attività
                </h6>
                <div class="d-flex gap-2">
                    <span class="badge bg-light-primary"><?php echo e($logs->total()); ?> log totali</span>
                </div>
            </div>
            <div class="card-body">
                <?php if($logs->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Data/Ora</th>
                                    <th>Livello</th>
                                    <th>Categoria</th>
                                    <th>Azione</th>
                                    <th>Utente</th>
                                    <th>Descrizione</th>
                                    <th>IP</th>
                                    <th>Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <small><?php echo e($log->created_at->format('d/m/Y H:i:s')); ?></small>
                                        </td>
                                        <td>
                                            <?php switch($log->level):
                                                case ('critical'): ?>
                                                    <span class="badge bg-danger">Critical</span>
                                                    <?php break; ?>
                                                <?php case ('error'): ?>
                                                    <span class="badge bg-danger">Error</span>
                                                    <?php break; ?>
                                                <?php case ('warning'): ?>
                                                    <span class="badge bg-warning">Warning</span>
                                                    <?php break; ?>
                                                <?php case ('info'): ?>
                                                    <span class="badge bg-info">Info</span>
                                                    <?php break; ?>
                                                <?php default: ?>
                                                    <span class="badge bg-secondary"><?php echo e($log->level); ?></span>
                                            <?php endswitch; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-light-primary"><?php echo e($log->category); ?></span>
                                        </td>
                                        <td>
                                            <code><?php echo e($log->action); ?></code>
                                        </td>
                                        <td>
                                            <?php if($log->user): ?>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-2">
                                                        <img src="<?php echo e($log->user->profile_photo_url ?? '/assets/images/avatar/default.png'); ?>"
                                                             class="rounded-circle" width="24" height="24">
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold"><?php echo e($log->user->name); ?></div>
                                                        <small class="text-muted"><?php echo e($log->user->getPrivacySafeIdentifier()); ?></small>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted">Guest</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span title="<?php echo e($log->description); ?>">
                                                <?php echo e(Str::limit($log->description, 60)); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?php echo e($log->ip_address); ?></small>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('admin.logs.show', $log->id)); ?>" class="btn btn-sm btn-light" title="Visualizza dettagli">
                                                <i class="ph ph-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginazione -->
                    <div class="d-flex justify-content-center mt-4">
                        <?php echo e($logs->appends(request()->query())->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="ph ph-check-circle text-success" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">Nessun log trovato</h5>
                        <p class="text-muted">Non ci sono log di attività per i filtri selezionati</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Grafico Categorie -->
        <?php if($stats['by_category']->count() > 0): ?>
        <div class="card hover-effect mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="ph ph-chart-pie me-2"></i>Log per Categoria
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php $__currentLoopData = $stats['by_category']->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoryStat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="d-flex justify-content-between align-items-center p-3 border rounded">
                                <div>
                                    <h6 class="mb-1"><?php echo e($categoryStat->category); ?></h6>
                                    <small class="text-muted"><?php echo e($categoryStat->count); ?> log</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-primary"><?php echo e(round(($categoryStat->count / $stats['total']) * 100, 1)); ?>%</span>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/admin/logs/activity.blade.php ENDPATH**/ ?>