

<?php $__env->startSection('title', $announcement->title . ' - ' . $group->name); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="page-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="page-title">
                            <?php if($announcement->is_pinned): ?>
                                <i class="ti ti-pin text-warning me-2" title="Annuncio pinnato"></i>
                            <?php endif; ?>
                            <?php echo e($announcement->title); ?>

                        </h1>
                        <p class="page-description">
                            Annuncio di <?php echo e($announcement->author->name); ?> per <?php echo e($group->name); ?>

                        </p>
                    </div>
                    <div>
                        <a href="<?php echo e(route('groups.announcements.index', $group)); ?>" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i>Torna alla bacheca
                        </a>
                        <?php
                            $user = auth()->user();
                            $canEdit = $announcement->author_id === $user?->id || $group->hasModerator($user);
                        ?>
                        <?php if($canEdit): ?>
                            <a href="<?php echo e(route('groups.announcements.edit', [$group, $announcement])); ?>" class="btn btn-primary">
                                <i class="ti ti-edit me-1"></i>Modifica
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Annuncio -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-secondary me-2">
                                        <?php switch($announcement->visibility):
                                            case ('public'): ?>
                                                Pubblico
                                                <?php break; ?>
                                            <?php case ('members_only'): ?>
                                                Solo membri
                                                <?php break; ?>
                                            <?php case ('admins_only'): ?>
                                                Solo admin
                                                <?php break; ?>
                                        <?php endswitch; ?>
                                    </span>
                                    <?php if($announcement->hasPoll()): ?>
                                        <span class="badge bg-info">
                                            <i class="ti ti-chart-bar me-1"></i>Sondaggio
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="announcement-meta">
                                    <small class="text-muted">
                                        <i class="ti ti-calendar me-1"></i>
                                        <?php echo e($announcement->created_at->format('d/m/Y H:i')); ?>

                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <!-- Contenuto -->
                            <div class="announcement-content mb-4">
                                <?php echo nl2br(e($announcement->content)); ?>

                            </div>
                            
                            <!-- Sondaggio -->
                            <?php if($announcement->hasPoll()): ?>
                            <div class="announcement-poll">
                                <h6 class="mb-3">
                                    <i class="ti ti-chart-bar me-2"></i>
                                    Sondaggio
                                </h6>
                                
                                <?php
                                    $user = auth()->user();
                                    $canVote = $announcement->canUserVote($user);
                                ?>
                                
                                <?php if($canVote): ?>
                                <div class="poll-options">
                                    <?php $__currentLoopData = $announcement->poll_options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="poll_option_<?php echo e($announcement->id); ?>" 
                                               value="<?php echo e($index); ?>" 
                                               id="poll_<?php echo e($announcement->id); ?>_<?php echo e($index); ?>">
                                        <label class="form-check-label" for="poll_<?php echo e($announcement->id); ?>_<?php echo e($index); ?>">
                                            <?php echo e($option); ?>

                                        </label>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <button type="button" 
                                            class="btn btn-primary"
                                            onclick="voteInPoll(<?php echo e($announcement->id); ?>)">
                                        <i class="ti ti-vote me-1"></i>Vota
                                    </button>
                                </div>
                                <?php else: ?>
                                <div class="poll-results">
                                    <?php $results = $announcement->getPollResults(); ?>
                                    <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="poll-result mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-medium"><?php echo e($result['option']); ?></span>
                                            <span class="text-muted"><?php echo e($result['votes']); ?> voti (<?php echo e($result['percentage']); ?>%)</span>
                                        </div>
                                        <div class="progress" style="height: 12px;">
                                            <div class="progress-bar" 
                                                 role="progressbar" 
                                                 style="width: <?php echo e($result['percentage']); ?>%"
                                                 aria-valuenow="<?php echo e($result['percentage']); ?>" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    
                                    <?php
                                        $totalVotes = array_sum(array_column($results, 'votes'));
                                    ?>
                                    <div class="mt-3">
                                        <small class="text-muted">
                                            <i class="ti ti-users me-1"></i>
                                            Totale voti: <?php echo e($totalVotes); ?>

                                        </small>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Scadenza -->
                            <?php if($announcement->expires_at): ?>
                            <div class="announcement-expiry mt-4">
                                <div class="alert alert-info">
                                    <i class="ti ti-clock me-2"></i>
                                    <strong>Scadenza:</strong> Questo annuncio scadrà il <?php echo e($announcement->expires_at->format('d/m/Y H:i')); ?>

                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="announcement-author">
                                    <div class="d-flex align-items-center">
                                        <img src="<?php echo e($announcement->author->profile_photo_url); ?>" 
                                             alt="<?php echo e($announcement->author->name); ?>" 
                                             class="rounded-circle me-2" 
                                             style="width: 32px; height: 32px;">
                                        <div>
                                            <div class="fw-medium"><?php echo e($announcement->author->name); ?></div>
                                            <small class="text-muted">
                                                <?php if($group->hasAdmin($announcement->author)): ?>
                                                    <i class="ti ti-crown me-1"></i>Amministratore
                                                <?php elseif($group->hasModerator($announcement->author)): ?>
                                                    <i class="ti ti-shield me-1"></i>Moderatore
                                                <?php else: ?>
                                                    <i class="ti ti-user me-1"></i>Membro
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="announcement-actions">
                                    <?php if($canEdit): ?>
                                        <a href="<?php echo e(route('groups.announcements.edit', [$group, $announcement])); ?>" 
                                           class="btn btn-sm btn-warning me-2">
                                            <i class="ti ti-edit me-1"></i>Modifica
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php
                                        $canDelete = $announcement->author_id === $user?->id || $group->hasModerator($user);
                                    ?>
                                    <?php if($canDelete): ?>
                                        <form action="<?php echo e(route('groups.announcements.destroy', [$group, $announcement])); ?>" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Sei sicuro di voler eliminare questo annuncio?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="ti ti-trash me-1"></i>Elimina
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Info gruppo -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="ti ti-info-circle me-2"></i>
                                Informazioni gruppo
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <img src="<?php echo e($group->image ? asset('storage/' . $group->image) : asset('assets/images/groups/default-group.webp')); ?>" 
                                     alt="<?php echo e($group->name); ?>" 
                                     class="rounded me-3" 
                                     style="width: 48px; height: 48px; object-fit: cover;">
                                <div>
                                    <h6 class="mb-0"><?php echo e($group->name); ?></h6>
                                    <small class="text-muted"><?php echo e($group->getMembersCount()); ?> membri</small>
                                </div>
                            </div>
                            
                            <a href="<?php echo e(route('groups.show', $group)); ?>" class="btn btn-primary btn-sm w-100">
                                <i class="ti ti-eye me-1"></i>Vedi gruppo
                            </a>
                        </div>
                    </div>

                    <!-- Social links -->
                    <?php if (isset($component)) { $__componentOriginal2d1b76dfc152c08a6bc56c8cae31f509 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2d1b76dfc152c08a6bc56c8cae31f509 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.group-social-links','data' => ['group' => $group]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('group-social-links'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['group' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($group)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2d1b76dfc152c08a6bc56c8cae31f509)): ?>
<?php $attributes = $__attributesOriginal2d1b76dfc152c08a6bc56c8cae31f509; ?>
<?php unset($__attributesOriginal2d1b76dfc152c08a6bc56c8cae31f509); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2d1b76dfc152c08a6bc56c8cae31f509)): ?>
<?php $component = $__componentOriginal2d1b76dfc152c08a6bc56c8cae31f509; ?>
<?php unset($__componentOriginal2d1b76dfc152c08a6bc56c8cae31f509); ?>
<?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function voteInPoll(announcementId) {
    const selectedOption = document.querySelector(`input[name="poll_option_${announcementId}"]:checked`);
    
    if (!selectedOption) {
        alert('Seleziona un\'opzione prima di votare');
        return;
    }
    
    const optionIndex = selectedOption.value;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '<?php echo e(csrf_token()); ?>';
    
    fetch(`/groups/<?php echo e($group->id); ?>/announcements/${announcementId}/vote`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            option_index: parseInt(optionIndex)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Ricarica la pagina per mostrare i risultati
            location.reload();
        } else {
            alert('Errore durante il voto: ' + (data.error || 'Errore sconosciuto'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Errore durante il voto');
    });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/groups/announcements/show.blade.php ENDPATH**/ ?>