<?php $__env->startSection('title', __('groups.group_members') . ' - ' . $group->name); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">
                            <i class="ph-duotone ph-users me-2 text-primary"></i>
                            <?php echo e(__('groups.group_members')); ?>

                        </h4>
                        <p class="text-muted mb-0"><?php echo e($group->name); ?></p>
                    </div>
                    <div class="d-flex gap-2">
                        <?php if($group->hasAdmin(auth()->user()) || auth()->user()->hasRole('admin')): ?>
                        <a href="<?php echo e(route('groups.invitations.create', $group)); ?>" class="btn btn-success">
                            <i class="ph-duotone ph-plus me-2"></i>
                            <?php echo e(__('groups.invite')); ?>

                        </a>
                        <?php endif; ?>
                        <a href="<?php echo e(route('groups.show', $group)); ?>" class="btn btn-light">
                            <i class="ph-duotone ph-arrow-left me-2"></i>
                            <?php echo e(__('common.back')); ?>

                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiche membri -->
    <div class="row mb-4">
        <div class="col-12 col-md-3">
            <div class="card card-light-primary">
                <div class="card-body text-center">
                    <i class="ph-duotone ph-users text-primary f-s-32 mb-2"></i>
                    <h4 class="text-primary mb-1"><?php echo e($group->getMembersCount()); ?></h4>
                    <p class="text-muted mb-0"><?php echo e(__('groups.total_members')); ?></p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card card-light-success">
                <div class="card-body text-center">
                    <i class="ph-duotone ph-crown text-success f-s-32 mb-2"></i>
                    <h4 class="text-success mb-1"><?php echo e($group->getAdmins()->count()); ?></h4>
                    <p class="text-muted mb-0"><?php echo e(__('groups.admins_count')); ?></p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card card-light-info">
                <div class="card-body text-center">
                    <i class="ph-duotone ph-shield-check text-info f-s-32 mb-2"></i>
                    <h4 class="text-info mb-1"><?php echo e($group->getModerators()->count()); ?></h4>
                    <p class="text-muted mb-0"><?php echo e(__('groups.moderators_count')); ?></p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card card-light-secondary">
                <div class="card-body text-center">
                    <i class="ph-duotone ph-user text-secondary f-s-32 mb-2"></i>
                    <h4 class="text-secondary mb-1"><?php echo e($group->members()->where('role', 'member')->count()); ?></h4>
                    <p class="text-muted mb-0"><?php echo e(__('groups.members_count_label')); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista membri -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><?php echo e(__('groups.group_members')); ?></h5>
                </div>
                <div class="card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="d-flex align-items-center mb-3 p-3 border rounded">
                        <div class="flex-shrink-0">
                            <img src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl($member->user)); ?>"
                                 alt="<?php echo e($member->user->getDisplayName()); ?>"
                                 class="rounded-circle"
                                 style="width: 60px; height: 60px; object-fit: cover;">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">
                                        <a href="<?php echo e(route('user.show', $member->user)); ?>" class="text-decoration-none hover-effect">
                                            <?php echo e($member->user->getDisplayName()); ?>

                                        </a>
                                    </h6>
                                    <p class="text-muted mb-1"><?php echo e($member->user->getPrivacySafeIdentifier()); ?></p>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-<?php echo e($member->role == 'admin' ? 'success' : ($member->role == 'moderator' ? 'info' : 'secondary')); ?>">
                                            <?php echo e(__('groups.role_' . $member->role)); ?>

                                        </span>
                                        <small class="text-muted">
                                            <?php echo e(__('groups.member_since')); ?> <?php echo e($member->joined_at->format('d/m/Y')); ?>

                                        </small>
                                        <?php if($member->invited_by): ?>
                                            <small class="text-muted">
                                                <?php echo e(__('groups.invited_by')); ?>

                                                <a href="<?php echo e(route('user.show', $member->invitedBy)); ?>" class="text-decoration-none hover-effect">
                                                    <?php echo e($member->invitedBy->getDisplayName()); ?>

                                                </a>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <?php if($group->hasAdmin(auth()->user()) || auth()->user()->hasRole('admin')): ?>
                                        <?php if($member->user_id !== auth()->id()): ?>
                                        <div class="dropdown">
                                            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="ph-duotone ph-dots-three-outline"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <?php if($member->role !== 'admin'): ?>
                                                <li>
                                                    <form action="<?php echo e(route('groups.members.promote', [$group, $member])); ?>" method="POST" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="ph-duotone ph-crown me-2"></i>
                                                            <?php echo e(__('groups.promote')); ?> <?php echo e(__('groups.role_admin')); ?>

                                                        </button>
                                                    </form>
                                                </li>
                                                <?php endif; ?>
                                                <?php if($member->role === 'member'): ?>
                                                <li>
                                                    <form action="<?php echo e(route('groups.members.promote-moderator', [$group, $member])); ?>" method="POST" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="ph-duotone ph-shield-check me-2"></i>
                                                            <?php echo e(__('groups.promote')); ?> <?php echo e(__('groups.role_moderator')); ?>

                                                        </button>
                                                    </form>
                                                </li>
                                                <?php endif; ?>
                                                <?php if($member->role === 'admin'): ?>
                                                <li>
                                                    <form action="<?php echo e(route('groups.members.demote', [$group, $member])); ?>" method="POST" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="ph-duotone ph-arrow-down me-2"></i>
                                                            <?php echo e(__('groups.demote')); ?> <?php echo e(__('groups.role_moderator')); ?>

                                                        </button>
                                                    </form>
                                                </li>
                                                <?php endif; ?>
                                                <?php if($member->role === 'moderator'): ?>
                                                <li>
                                                    <form action="<?php echo e(route('groups.members.demote-member', [$group, $member])); ?>" method="POST" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="ph-duotone ph-arrow-down me-2"></i>
                                                            <?php echo e(__('groups.demote')); ?> <?php echo e(__('groups.role_member')); ?>

                                                        </button>
                                                    </form>
                                                </li>
                                                <?php endif; ?>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="<?php echo e(route('groups.members.remove', [$group, $member])); ?>" method="POST" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="dropdown-item text-danger"
                                                                onclick="return confirm('<?php echo e(__('groups.confirm_remove_member')); ?>')">
                                                            <i class="ph-duotone ph-trash me-2"></i>
                                                            <?php echo e(__('groups.remove')); ?>

                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                        <?php else: ?>
                                        <span class="badge bg-primary"><?php echo e(__('groups.you')); ?></span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-5">
                        <i class="ph-duotone ph-users text-muted f-s-64 mb-3"></i>
                        <h5 class="text-muted"><?php echo e(__('groups.no_members')); ?></h5>
                        <?php if($group->hasAdmin(auth()->user()) || auth()->user()->hasRole('admin')): ?>
                        <p class="text-muted mb-3"><?php echo e(__('groups.invite_first_member')); ?></p>
                        <a href="<?php echo e(route('groups.invitations.create', $group)); ?>" class="btn btn-primary">
                            <i class="ph-duotone ph-plus me-2"></i>
                            <?php echo e(__('groups.invite')); ?>

                        </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Paginazione -->
    <?php if($members->hasPages()): ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <?php echo e($members->links()); ?>

                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/groups/members/index.blade.php ENDPATH**/ ?>