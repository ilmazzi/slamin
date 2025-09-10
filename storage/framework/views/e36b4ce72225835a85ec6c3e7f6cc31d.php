<?php $__env->startSection('css'); ?>
<style>
.task-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.task-overlay-content {
    background: white;
    border-radius: 8px;
    width: 90%;
    max-width: 800px;
    max-height: 90vh;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.task-overlay-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.task-overlay-header h4 {
    margin: 0;
    font-size: 1.25rem;
}

.task-overlay-header .btn-close {
    background: none;
    border: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.task-overlay-body {
    padding: 1.5rem;
    max-height: calc(90vh - 80px);
    overflow-y: auto;
}

.task-details-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
}

.task-main-info {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 8px;
}

.task-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.task-sidebar-section {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
}

.task-sidebar-section h6 {
    margin-bottom: 0.75rem;
    color: #495057;
    font-weight: 600;
}

.task-comments {
    margin-top: 2rem;
}

.comment-item {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    border-left: 4px solid #667eea;
}

.comment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.comment-author {
    font-weight: 600;
    color: #495057;
}

.comment-date {
    font-size: 0.875rem;
    color: #6c757d;
}

.comment-content {
    color: #495057;
    line-height: 1.5;
}

@media (max-width: 768px) {
    .task-details-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .task-overlay-content {
        width: 95%;
        margin: 1rem;
    }
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    
                </div>
                <h4 class="page-title">
                    <i class="ph ph-kanban me-2"></i>Gestione Task di Sviluppo
                </h4>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-2">
            <div class="card card-light-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph ph-list text-primary f-s-24"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Totali</h6>
                            <h4 class="mb-0"><?php echo e($stats['total_tasks']); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-light-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph ph-play-circle text-info f-s-24"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">In Corso</h6>
                            <h4 class="mb-0"><?php echo e($stats['in_progress_tasks']); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-light-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph ph-eye text-warning f-s-24"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">In Review</h6>
                            <h4 class="mb-0"><?php echo e($stats['review_tasks']); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-light-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph ph-check-circle text-success f-s-24"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Completati</h6>
                            <h4 class="mb-0"><?php echo e($stats['completed_tasks']); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-light-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph ph-warning text-warning f-s-24"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Scaduti</h6>
                            <h4 class="mb-0"><?php echo e($stats['overdue_tasks']); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card card-light-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph ph-calendar text-info f-s-24"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Oggi</h6>
                            <h4 class="mb-0"><?php echo e($stats['tasks_due_today']); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <?php echo e(__('common.kanban_board')); ?> -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><i class="ph ph-kanban me-2"></i>Gestione Task di Sviluppo</h4>
                        <p class="mb-0 opacity-75">Trascina le card per cambiare lo stato dei task</p>
                    </div>
                    <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                        <i class="ph ph-plus me-2"></i>Nuovo Task
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="kanban-board-container app-scroll">
                        <div class="board">
                            <!-- TODO COLUMN -->
                            <div class="board-column app-scroll" data-status="todo">
                                <div class="board-column-header">
                                    <i class="ph-fill ph-list-bullets me-2 f-s-16"></i> TODO
                                    <span class="badge bg-secondary ms-2"><?php echo e($data['todo_tasks']->count()); ?></span>
                                </div>
                                <div class="board-column-content-wrapper">
                                    <div class="board-column-content">
                                        <?php $__currentLoopData = $data['todo_tasks']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="board-item" data-task-id="<?php echo e($task->id); ?>" data-status="todo">
                                            <div class="board-item-content" onclick="openTaskDetails(<?php echo e($task->id); ?>)" style="cursor: pointer;">
                                                <!-- Image Preview -->
                                                <?php if($task->attachments && count(array_filter($task->attachments, fn($a) => $a['type'] === 'image')) > 0): ?>
                                                <div class="mb-2">
                                                    <?php
                                                        $firstImage = array_filter($task->attachments, fn($a) => $a['type'] === 'image')[0] ?? null;
                                                    ?>
                                                    <?php if($firstImage): ?>
                                                    <img src="<?php echo e(Storage::url($firstImage['path'])); ?>"
                                                         alt="Task image"
                                                         class="img-fluid rounded"
                                                         style="max-height: 80px; object-fit: cover; width: 100%;">
                                                    <?php endif; ?>
                                                </div>
                                                <?php endif; ?>

                                                <h6 class="mb-0"><?php echo e(Str::limit($task->title, 35)); ?></h6>
                                                <?php if($task->description): ?>
                                                <p class="text-muted mb-2"><?php echo e(Str::limit($task->description, 60)); ?></p>
                                                <?php endif; ?>
                                                <div class="board-footer">
                                                    <span class="badge text-bg-<?php echo e($task->getPriorityColor()); ?> f-s-14">
                                                        <i class="ph ph-flag me-1"></i><?php echo e(ucfirst($task->priority)); ?>

                                                    </span>
                                                    <?php if($task->assignedTo): ?>
                                                    <span class="badge text-bg-info f-s-14 ms-2">
                                                        <i class="ph ph-user me-1"></i><?php echo e(Str::limit($task->assignedTo->getDisplayName(), 15)); ?>

                                                    </span>
                                                    <?php endif; ?>
                                                    <?php if($task->due_date): ?>
                                                    <span class="badge text-bg-<?php echo e($task->isOverdue() ? 'danger' : 'warning'); ?> f-s-14 ms-2">
                                                        <i class="ph ph-calendar me-1"></i><?php echo e($task->due_date->format('M d')); ?>

                                                    </span>
                                                    <?php endif; ?>
                                                    <?php if($task->attachments && count($task->attachments) > 0): ?>
                                                    <span class="badge text-bg-warning f-s-14 ms-2">
                                                        <i class="ph ph-paperclip me-1"></i><?php echo e(count($task->attachments)); ?>

                                                    </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>

                            <!-- IN PROGRESS COLUMN -->
                            <div class="board-column app-scroll" data-status="in_progress">
                                <div class="board-column-header">
                                    <i class="ph-bold ph-chart-line-up me-2 f-s-16"></i> IN PROGRESS
                                    <span class="badge bg-primary ms-2"><?php echo e($data['in_progress_tasks']->count()); ?></span>
                                </div>
                                <div class="board-column-content-wrapper">
                                    <div class="board-column-content">
                                        <?php $__currentLoopData = $data['in_progress_tasks']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="board-item" data-task-id="<?php echo e($task->id); ?>" data-status="in_progress">
                                            <div class="board-item-content" onclick="openTaskDetails(<?php echo e($task->id); ?>)" style="cursor: pointer;">
                                                <!-- Image Preview -->
                                                <?php if($task->attachments && count(array_filter($task->attachments, fn($a) => $a['type'] === 'image')) > 0): ?>
                                                <div class="mb-2">
                                                    <?php
                                                        $firstImage = array_filter($task->attachments, fn($a) => $a['type'] === 'image')[0] ?? null;
                                                    ?>
                                                    <?php if($firstImage): ?>
                                                    <img src="<?php echo e(Storage::url($firstImage['path'])); ?>"
                                                         alt="Task image"
                                                         class="img-fluid rounded"
                                                         style="max-height: 80px; object-fit: cover; width: 100%;">
                                                    <?php endif; ?>
                                                </div>
                                                <?php endif; ?>

                                                <h6 class="mb-0"><?php echo e(Str::limit($task->title, 35)); ?></h6>
                                                <?php if($task->description): ?>
                                                <p class="text-muted mb-2"><?php echo e(Str::limit($task->description, 60)); ?></p>
                                                <?php endif; ?>
                                                <div class="board-footer">
                                                    <span class="badge text-bg-<?php echo e($task->getPriorityColor()); ?> f-s-14">
                                                        <i class="ph ph-flag me-1"></i><?php echo e(ucfirst($task->priority)); ?>

                                                    </span>
                                                    <?php if($task->assignedTo): ?>
                                                    <span class="badge text-bg-info f-s-14 ms-2">
                                                        <i class="ph ph-user me-1"></i><?php echo e(Str::limit($task->assignedTo->getDisplayName(), 15)); ?>

                                                    </span>
                                                    <?php endif; ?>
                                                    <?php if($task->progress_percentage > 0): ?>
                                                    <span class="badge text-bg-<?php echo e($task->getProgressBarColor()); ?> f-s-14 ms-2">
                                                        <i class="ph ph-percent me-1"></i><?php echo e($task->progress_percentage); ?>%
                                                    </span>
                                                    <?php endif; ?>
                                                    <?php if($task->attachments && count($task->attachments) > 0): ?>
                                                    <span class="badge text-bg-warning f-s-14 ms-2">
                                                        <i class="ph ph-paperclip me-1"></i><?php echo e(count($task->attachments)); ?>

                                                    </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>

                            <!-- REVIEW COLUMN -->
                            <div class="board-column app-scroll" data-status="review">
                                <div class="board-column-header">
                                    <i class="ph-bold ph-eye me-2 f-s-16"></i> REVIEW
                                    <span class="badge bg-warning ms-2"><?php echo e($data['review_tasks']->count()); ?></span>
                                </div>
                                <div class="board-column-content-wrapper">
                                    <div class="board-column-content">
                                        <?php $__currentLoopData = $data['review_tasks']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="board-item" data-task-id="<?php echo e($task->id); ?>" data-status="review">
                                            <div class="board-item-content" onclick="openTaskDetails(<?php echo e($task->id); ?>)" style="cursor: pointer;">
                                                <!-- Image Preview -->
                                                <?php if($task->attachments && count(array_filter($task->attachments, fn($a) => $a['type'] === 'image')) > 0): ?>
                                                <div class="mb-2">
                                                    <?php
                                                        $firstImage = array_filter($task->attachments, fn($a) => $a['type'] === 'image')[0] ?? null;
                                                    ?>
                                                    <?php if($firstImage): ?>
                                                    <img src="<?php echo e(Storage::url($firstImage['path'])); ?>"
                                                         alt="Task image"
                                                         class="img-fluid rounded"
                                                         style="max-height: 80px; object-fit: cover; width: 100%;">
                                                    <?php endif; ?>
                                                </div>
                                                <?php endif; ?>

                                                <h6 class="mb-0"><?php echo e(Str::limit($task->title, 35)); ?></h6>
                                                <?php if($task->description): ?>
                                                <p class="text-muted mb-2"><?php echo e(Str::limit($task->description, 60)); ?></p>
                                                <?php endif; ?>
                                                <div class="board-footer">
                                                    <span class="badge text-bg-<?php echo e($task->getPriorityColor()); ?> f-s-14">
                                                        <i class="ph ph-flag me-1"></i><?php echo e(ucfirst($task->priority)); ?>

                                                    </span>
                                                    <?php if($task->assignedTo): ?>
                                                    <span class="badge text-bg-info f-s-14 ms-2">
                                                        <i class="ph ph-user me-1"></i><?php echo e(Str::limit($task->assignedTo->getDisplayName(), 15)); ?>

                                                    </span>
                                                    <?php endif; ?>
                                                    <?php if($task->reviewedBy): ?>
                                                    <span class="badge text-bg-success f-s-14 ms-2">
                                                        <i class="ph ph-check me-1"></i><?php echo e(Str::limit($task->reviewedBy->getDisplayName(), 15)); ?>

                                                    </span>
                                                    <?php endif; ?>
                                                    <?php if($task->attachments && count($task->attachments) > 0): ?>
                                                    <span class="badge text-bg-warning f-s-14 ms-2">
                                                        <i class="ph ph-paperclip me-1"></i><?php echo e(count($task->attachments)); ?>

                                                    </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>

                            <!-- TESTING COLUMN -->
                            <div class="board-column app-scroll" data-status="testing">
                                <div class="board-column-header">
                                    <i class="ph-bold ph-check-square-offset me-2 f-s-16"></i> TESTING
                                    <span class="badge bg-info ms-2"><?php echo e($data['testing_tasks']->count()); ?></span>
                                </div>
                                <div class="board-column-content-wrapper">
                                    <div class="board-column-content">
                                        <?php $__currentLoopData = $data['testing_tasks']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="board-item" data-task-id="<?php echo e($task->id); ?>" data-status="testing">
                                            <div class="board-item-content" onclick="openTaskDetails(<?php echo e($task->id); ?>)" style="cursor: pointer;">
                                                <h6 class="mb-0"><?php echo e(Str::limit($task->title, 35)); ?></h6>
                                                <?php if($task->description): ?>
                                                <p class="text-muted mb-2"><?php echo e(Str::limit($task->description, 60)); ?></p>
                                                <?php endif; ?>
                                                <div class="board-footer">
                                                    <span class="badge text-bg-<?php echo e($task->getPriorityColor()); ?> f-s-14">
                                                        <i class="ph ph-flag me-1"></i><?php echo e(ucfirst($task->priority)); ?>

                                                    </span>
                                                    <?php if($task->assignedTo): ?>
                                                    <span class="badge text-bg-info f-s-14 ms-2">
                                                        <i class="ph ph-user me-1"></i><?php echo e(Str::limit($task->assignedTo->getDisplayName(), 15)); ?>

                                                    </span>
                                                    <?php endif; ?>
                                                    <?php if($task->estimated_hours): ?>
                                                    <span class="badge text-bg-secondary f-s-14 ms-2">
                                                        <i class="ph ph-clock me-1"></i><?php echo e($task->getEstimatedTimeFormatted()); ?>

                                                    </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>

                            <!-- DONE COLUMN -->
                            <div class="board-column app-scroll" data-status="done">
                                <div class="board-column-header">
                                    <i class="ph-bold ph-check-circle me-2 f-s-16"></i> DONE
                                    <span class="badge bg-success ms-2"><?php echo e($data['done_tasks']->count()); ?></span>
                                </div>
                                <div class="board-column-content-wrapper">
                                    <div class="board-column-content">
                                        <?php $__currentLoopData = $data['done_tasks']->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="board-item" data-task-id="<?php echo e($task->id); ?>" data-status="done">
                                            <div class="board-item-content" onclick="openTaskDetails(<?php echo e($task->id); ?>)" style="cursor: pointer;">
                                                <h6 class="mb-0"><?php echo e(Str::limit($task->title, 35)); ?></h6>
                                                <?php if($task->description): ?>
                                                <p class="text-muted mb-2"><?php echo e(Str::limit($task->description, 60)); ?></p>
                                                <?php endif; ?>
                                                <div class="board-footer">
                                                    <?php if($task->assignedTo): ?>
                                                    <span class="badge text-bg-info f-s-14">
                                                        <i class="ph ph-user me-1"></i><?php echo e(Str::limit($task->assignedTo->getDisplayName(), 15)); ?>

                                                    </span>
                                                    <?php endif; ?>
                                                    <?php if($task->completed_at): ?>
                                                    <span class="badge text-bg-success f-s-14 ms-2">
                                                        <i class="ph ph-check me-1"></i><?php echo e($task->completed_at->format('M d')); ?>

                                                    </span>
                                                    <?php endif; ?>
                                                    <?php if($task->actual_hours): ?>
                                                    <span class="badge text-bg-dark f-s-14 ms-2">
                                                        <i class="ph ph-timer me-1"></i><?php echo e($task->getActualTimeFormatted()); ?>

                                                    </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Task Details Overlay -->
<div id="taskDetailsOverlay" class="task-overlay" style="display: none;">
    <div class="task-overlay-content">
        <div class="task-overlay-header">
            <h4 id="taskDetailsTitle">Dettagli Task</h4>
            <button type="button" class="btn-close" onclick="closeTaskOverlay()"></button>
        </div>
        <div class="task-overlay-body" id="taskDetailsContent">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<!-- Add Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTaskModalLabel">
                    <i class="ph ph-plus-circle me-2"></i>Nuovo Task
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addTaskForm" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="title" class="form-label">Titolo *</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="priority" class="form-label">Priorità *</label>
                                <select class="form-select" id="priority" name="priority" required>
                                    <option value="">Seleziona priorità</option>
                                    <option value="low">Bassa</option>
                                    <option value="medium"><?php echo e(__('common.media_section')); ?></option>
                                    <option value="high">Alta</option>
                                    <option value="urgent">Urgente</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descrizione</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category" class="form-label">Categoria *</label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="">Seleziona categoria</option>
                                    <option value="frontend">Frontend</option>
                                    <option value="backend">Backend</option>
                                    <option value="database">Database</option>
                                    <option value="design">Design</option>
                                    <option value="testing">Testing</option>
                                    <option value="deployment">Deployment</option>
                                    <option value="documentation">Documentation</option>
                                    <option value="bug_fix">Bug Fix</option>
                                    <option value="feature">Feature</option>
                                    <option value="maintenance">Maintenance</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="notes" class="form-label">Note</label>
                                <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="assigned_to" class="form-label">Assegnato a</label>
                                <select class="form-select" id="assigned_to" name="assigned_to">
                                    <option value="">Seleziona utente</option>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>"><?php echo e($user->getDisplayName()); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="due_date" class="form-label">Data di scadenza</label>
                                <input type="date" class="form-control" id="due_date" name="due_date">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="estimated_hours" class="form-label">Ore stimate</label>
                                <input type="number" class="form-control" id="estimated_hours" name="estimated_hours" min="0" step="0.5">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status iniziale</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="todo">To Do</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="review">Review</option>
                                    <option value="testing">Testing</option>
                                    <option value="done">Done</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="attachments" class="form-label">Allegati</label>
                        <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                        <div class="form-text">Puoi selezionare più file</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ph ph-plus me-2"></i>Creare Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTaskModalLabel">
                    <i class="ph ph-pencil me-2"></i><?php echo e(__('permissions.modify')); ?> Task
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTaskForm" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input type="hidden" id="edit_task_id" name="task_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="edit_title" class="form-label">Titolo *</label>
                                <input type="text" class="form-control" id="edit_title" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_priority" class="form-label">Priorità *</label>
                                <select class="form-select" id="edit_priority" name="priority" required>
                                    <option value="low">Bassa</option>
                                    <option value="medium"><?php echo e(__('common.media_section')); ?></option>
                                    <option value="high">Alta</option>
                                    <option value="urgent">Urgente</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Descrizione</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_category" class="form-label">Categoria *</label>
                                <select class="form-select" id="edit_category" name="category" required>
                                    <option value="frontend">Frontend</option>
                                    <option value="backend">Backend</option>
                                    <option value="database">Database</option>
                                    <option value="design">Design</option>
                                    <option value="testing">Testing</option>
                                    <option value="deployment">Deployment</option>
                                    <option value="documentation">Documentation</option>
                                    <option value="bug_fix">Bug Fix</option>
                                    <option value="feature">Feature</option>
                                    <option value="maintenance">Maintenance</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_notes" class="form-label">Note</label>
                                <textarea class="form-control" id="edit_notes" name="notes" rows="2"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_assigned_to" class="form-label">Assegnato a</label>
                                <select class="form-select" id="edit_assigned_to" name="assigned_to">
                                    <option value="">Seleziona utente</option>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>"><?php echo e($user->getDisplayName()); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_due_date" class="form-label">Data di scadenza</label>
                                <input type="date" class="form-control" id="edit_due_date" name="due_date">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_estimated_hours" class="form-label">Ore stimate</label>
                                <input type="number" class="form-control" id="edit_estimated_hours" name="estimated_hours" min="0" step="0.5">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_actual_hours" class="form-label">Ore effettive</label>
                                <input type="number" class="form-control" id="edit_actual_hours" name="actual_hours" min="0" step="0.5">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_progress_percentage" class="form-label">Progresso (%)</label>
                                <input type="number" class="form-control" id="edit_progress_percentage" name="progress_percentage" min="0" max="100">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_attachments" class="form-label">Nuovi allegati</label>
                        <input type="file" class="form-control" id="edit_attachments" name="attachments[]" multiple>
                        <div class="form-text">Puoi selezionare più file</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ph ph-check me-2"></i>Salvare Modifiche
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<!-- <?php echo e(__('common.kanban_board')); ?> JS -->
<script src="<?php echo e(asset('assets/vendor/kanban_board/hammer.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/vendor/kanban_board/muuri.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/kanban_board.js')); ?>"></script>

<script>
// Simple notification function
function showNotification(message, type = 'info') {
    Swal.fire({
        title: type === 'success' ? 'Successo!' : 'Errore!',
        text: message,
        icon: type,
        timer: 3000,
        showConfirmButton: false
    });
}

// Open task details overlay
function openTaskDetails(taskId) {
    fetch(`/admin/kanban/task/${taskId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('taskDetailsContent').innerHTML = data.html;
                document.getElementById('taskDetailsOverlay').style.display = 'flex';
            } else {
                showNotification('<?php echo e(__('common.loading_error')); ?> dei dettagli del task', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('<?php echo e(__('common.loading_error')); ?> dei dettagli del task', 'error');
        });
}

// Close task details overlay
function closeTaskOverlay() {
    document.getElementById('taskDetailsOverlay').style.display = 'none';
}

// Edit task function
function editTask(taskId) {
    fetch(`/admin/kanban/task/${taskId}/edit`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Populate edit form
                document.getElementById('edit_task_id').value = taskId;
                document.getElementById('edit_title').value = data.task.title;
                document.getElementById('edit_description').value = data.task.description || '';
                document.getElementById('edit_priority').value = data.task.priority;
                document.getElementById('edit_category').value = data.task.category || 'feature';
                document.getElementById('edit_notes').value = data.task.notes || '';
                document.getElementById('edit_assigned_to').value = data.task.assigned_to || '';
                document.getElementById('edit_due_date').value = data.task.due_date || '';
                document.getElementById('edit_estimated_hours').value = data.task.estimated_hours || '';
                document.getElementById('edit_actual_hours').value = data.task.actual_hours || '';
                document.getElementById('edit_progress_percentage').value = data.task.progress_percentage || 0;

                // Show edit modal
                new bootstrap.Modal(document.getElementById('editTaskModal')).show();
            } else {
                showNotification('<?php echo e(__('common.loading_error')); ?> del task', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('<?php echo e(__('common.loading_error')); ?> del task', 'error');
        });
}

// Add comment function
function addComment(taskId) {
    const commentText = document.getElementById('commentText').value;
    if (!commentText.trim()) {
        showNotification('Inserisci un commento', 'warning');
        return;
    }

    fetch('/admin/kanban/task/comment', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            task_id: taskId,
            content: commentText
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('commentText').value = '';
            showNotification('Commento aggiunto con successo', 'success');
            // Refresh task details
            openTaskDetails(taskId);
        } else {
            showNotification('Errore nell\'aggiunta del commento', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Errore nell\'aggiunta del commento', 'error');
    });
}

// Add Task Form Handler
$('#addTaskForm').on('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const submitBtn = $(this).find('button[type="submit"]');
    const originalText = submitBtn.html();

    // Show loading state
    submitBtn.prop('disabled', true).html(`
        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
        Creazione in corso...
    `);

    $.ajax({
        url: '<?php echo e(route("admin.kanban.store-task")); ?>',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                $('#addTaskModal').modal('hide');
                $('#addTaskForm')[0].reset();
                showNotification('Task creato con successo!', 'success');

                // Refresh only the specific column where the task was added
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showNotification('Errore nella creazione del task: ' + response.message, 'error');
            }
        },
        error: function(xhr) {
            let errorMessage = 'Errore nella creazione del task';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            showNotification(errorMessage, 'error');
        },
        complete: function() {
            // Restore button state
            submitBtn.prop('disabled', false).html(originalText);
        }
    });
});

// Edit Task Form Handler
$('#editTaskForm').on('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const submitBtn = $(this).find('button[type="submit"]');
    const originalText = submitBtn.html();

    // Show loading state
    submitBtn.prop('disabled', true).html(`
        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
        Salvataggio in corso...
    `);

    $.ajax({
        url: '<?php echo e(route("admin.kanban.update-task")); ?>',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                $('#editTaskModal').modal('hide');
                showNotification('Task aggiornato con successo!', 'success');

                // Refresh the page to show updated data
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                showNotification('Errore nell\'aggiornamento del task: ' + response.message, 'error');
            }
        },
        error: function(xhr) {
            let errorMessage = 'Errore nell\'aggiornamento del task';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            showNotification(errorMessage, 'error');
        },
        complete: function() {
            // Restore button state
            submitBtn.prop('disabled', false).html(originalText);
        }
    });
});

// Overlay event handlers
$('#taskDetailsOverlay').on('click', function(e) {
    if (e.target === this) {
        closeTaskOverlay();
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/admin/kanban/index.blade.php ENDPATH**/ ?>