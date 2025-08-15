

<?php $__env->startSection('title', 'Dettagli Log - Admin'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">Dettagli Log</h4>
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
                        <a href="#" class="f-s-14 f-w-500">Dettagli</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Informazioni Principali -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="card hover-effect">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="ph ph-info-circle me-2"></i>Informazioni Log
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>ID:</strong></td>
                                        <td><?php echo e($log->id); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Data/Ora:</strong></td>
                                        <td><?php echo e($log->created_at->format('d/m/Y H:i:s')); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Livello:</strong></td>
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
                                    </tr>
                                    <tr>
                                        <td><strong>Categoria:</strong></td>
                                        <td><span class="badge bg-light-primary"><?php echo e($log->category); ?></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Azione:</strong></td>
                                        <td><code><?php echo e($log->action); ?></code></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Descrizione:</strong></td>
                                        <td><?php echo e($log->description); ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>Utente:</strong></td>
                                        <td>
                                            <?php if($log->user): ?>
                                                <div class="d-flex align-items-center">
                                                    <img src="<?php echo e($log->user->profile_photo_url ?? '/assets/images/avatar/default.png'); ?>"
                                                         class="rounded-circle me-2" width="32" height="32">
                                                    <div>
                                                        <div class="fw-semibold"><?php echo e($log->user->name); ?></div>
                                                        <small class="text-muted"><?php echo e($log->user->getPrivacySafeIdentifier()); ?></small>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted">Guest</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>IP Address:</strong></td>
                                        <td><code><?php echo e($log->ip_address); ?></code></td>
                                    </tr>
                                    <tr>
                                        <td><strong>URL:</strong></td>
                                        <td>
                                            <?php if($log->url): ?>
                                                <a href="<?php echo e($log->url); ?>" target="_blank" class="text-truncate d-inline-block" style="max-width: 200px;">
                                                    <?php echo e($log->url); ?>

                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Metodo:</strong></td>
                                        <td><span class="badge bg-light-secondary"><?php echo e($log->method); ?></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>User Agent:</strong></td>
                                        <td>
                                            <?php if($log->user_agent): ?>
                                                <small class="text-muted"><?php echo e(Str::limit($log->user_agent, 50)); ?></small>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Azioni Rapide -->
                <div class="card hover-effect mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="ph ph-gear me-2"></i>Azioni
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="<?php echo e(route('admin.logs.activity')); ?>" class="btn btn-primary">
                                <i class="ph ph-arrow-left me-1"></i>Torna ai Log
                            </a>
                            <?php if($log->related_model && $log->related_id): ?>
                                <a href="#" class="btn btn-info" onclick="viewRelatedModel('<?php echo e($log->related_model); ?>', <?php echo e($log->related_id); ?>)">
                                    <i class="ph ph-link me-1"></i>Visualizza Risorsa
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Statistiche -->
                <div class="card hover-effect">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="ph ph-chart-bar me-2"></i>Statistiche
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Log simili (stessa azione):</span>
                            <span class="badge bg-primary"><?php echo e(\App\Models\ActivityLog::where('action', $log->action)->count()); ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Log utente (24h):</span>
                            <span class="badge bg-info"><?php echo e(\App\Models\ActivityLog::where('user_id', $log->user_id)->where('created_at', '>=', now()->subDay())->count()); ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Errori oggi:</span>
                            <span class="badge bg-danger"><?php echo e(\App\Models\ActivityLog::where('level', 'error')->where('created_at', '>=', now()->startOfDay())->count()); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dettagli Tecnici -->
        <?php if($log->details && is_array($log->details) && count($log->details) > 0): ?>
        <div class="card hover-effect">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="ph ph-code me-2"></i>Dettagli Tecnici
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Chiave</th>
                                <th>Valore</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $log->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><strong><?php echo e($key); ?></strong></td>
                                    <td>
                                        <?php if(is_array($value) || is_object($value)): ?>
                                            <pre class="mb-0" style="max-height: 200px; overflow-y: auto;"><?php echo e(json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
                                        <?php elseif(is_bool($value)): ?>
                                            <span class="badge bg-<?php echo e($value ? 'success' : 'danger'); ?>"><?php echo e($value ? 'true' : 'false'); ?></span>
                                        <?php elseif(is_numeric($value)): ?>
                                            <code><?php echo e($value); ?></code>
                                        <?php else: ?>
                                            <span><?php echo e($value); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Log Simili -->
        <div class="card hover-effect mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="ph ph-list me-2"></i>Log Simili (Ultimi 10)
                </h6>
            </div>
            <div class="card-body">
                <?php
                    $similarLogs = \App\Models\ActivityLog::where('action', $log->action)
                        ->where('id', '!=', $log->id)
                        ->orderBy('created_at', 'desc')
                        ->limit(10)
                        ->get();
                ?>

                <?php if($similarLogs->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Data/Ora</th>
                                    <th>Utente</th>
                                    <th>Livello</th>
                                    <th>IP</th>
                                    <th>Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $similarLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $similarLog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <small><?php echo e($similarLog->created_at->format('d/m/Y H:i:s')); ?></small>
                                        </td>
                                        <td>
                                            <?php if($similarLog->user): ?>
                                                <span class="text-primary"><?php echo e($similarLog->user->name); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">Guest</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php switch($similarLog->level):
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
                                                    <span class="badge bg-secondary"><?php echo e($similarLog->level); ?></span>
                                            <?php endswitch; ?>
                                        </td>
                                        <td>
                                            <small class="text-muted"><?php echo e($similarLog->ip_address); ?></small>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('admin.logs.show', $similarLog->id)); ?>" class="btn btn-sm btn-light">
                                                <i class="ph ph-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-0">Nessun log simile trovato</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>

<script>
    function viewRelatedModel(model, id) {
        // Implementa la logica per visualizzare la risorsa correlata
        // Questo dipende dal tipo di modello
        const routes = {
            'User': '/admin/users/',
            'Event': '/admin/events/',
            'Video': '/admin/videos/',
            'Poem': '/admin/poems/'
        };

        if (routes[model]) {
            window.open(routes[model] + id, '_blank');
        } else {
            alert('Risorsa non supportata: ' + model);
        }
    }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/admin/logs/show.blade.php ENDPATH**/ ?>