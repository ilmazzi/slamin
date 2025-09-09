<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['announcement', 'group']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['announcement', 'group']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $user = auth()->user();
    $canEdit = $announcement->author_id === $user?->id || $group->hasModerator($user);
    $canDelete = $canEdit;
    $canVote = $announcement->hasPoll() && $announcement->canUserVote($user);
?>

<div class="announcement-card card mb-3 <?php echo e($announcement->is_pinned ? 'border-warning' : ''); ?>">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <?php if($announcement->is_pinned): ?>
                <i class="ti ti-pin text-warning me-2" title="Annuncio pinnato"></i>
            <?php endif; ?>
            <h6 class="mb-0"><?php echo e($announcement->title); ?></h6>
            <span class="badge bg-secondary ms-2">
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
        </div>
        
        <?php if($canEdit || $canDelete): ?>
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                <i class="ti ti-dots-vertical"></i>
            </button>
            <ul class="dropdown-menu">
                <?php if($canEdit): ?>
                    <li>
                        <a class="dropdown-item" href="<?php echo e(route('groups.announcements.edit', [$group, $announcement])); ?>">
                            <i class="ti ti-edit me-2"></i>Modifica
                        </a>
                    </li>
                <?php endif; ?>
                <?php if($canDelete): ?>
                    <li>
                        <form action="<?php echo e(route('groups.announcements.destroy', [$group, $announcement])); ?>" 
                              method="POST" 
                              onsubmit="return confirm('Sei sicuro di voler eliminare questo annuncio?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="ti ti-trash me-2"></i>Elimina
                            </button>
                        </form>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="card-body">
        <div class="announcement-content">
            <?php echo nl2br(e($announcement->content)); ?>

        </div>
        
        <?php if($announcement->hasPoll()): ?>
        <div class="announcement-poll mt-3">
            <h6 class="mb-3">
                <i class="ti ti-chart-bar me-2"></i>
                Sondaggio
            </h6>
            
            <?php if($canVote): ?>
            <div class="poll-options">
                <?php $__currentLoopData = $announcement->poll_options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="form-check mb-2">
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
                        class="btn btn-primary btn-sm mt-2"
                        onclick="voteInPoll(<?php echo e($announcement->id); ?>)">
                    <i class="ti ti-vote me-1"></i>Vota
                </button>
            </div>
            <?php else: ?>
            <div class="poll-results">
                <?php $results = $announcement->getPollResults(); ?>
                <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="poll-result mb-2">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span><?php echo e($result['option']); ?></span>
                        <span class="text-muted"><?php echo e($result['votes']); ?> voti (<?php echo e($result['percentage']); ?>%)</span>
                    </div>
                    <div class="progress" style="height: 8px;">
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
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <?php if($announcement->expires_at): ?>
        <div class="announcement-expiry mt-3">
            <small class="text-muted">
                <i class="ti ti-clock me-1"></i>
                Scade il <?php echo e($announcement->expires_at->format('d/m/Y H:i')); ?>

            </small>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <div class="announcement-meta">
                <small class="text-muted">
                    <i class="ti ti-user me-1"></i>
                    <?php echo e($announcement->author->name); ?>

                    <span class="mx-2">•</span>
                    <i class="ti ti-calendar me-1"></i>
                    <?php echo e($announcement->created_at->format('d/m/Y H:i')); ?>

                </small>
            </div>
            
            <div class="announcement-actions">
                <a href="<?php echo e(route('groups.announcements.show', [$group, $announcement])); ?>" 
                   class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-eye me-1"></i>Leggi tutto
                </a>
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
<?php /**PATH C:\xampp\htdocs\slamin\resources\views/components/group-announcement-card.blade.php ENDPATH**/ ?>