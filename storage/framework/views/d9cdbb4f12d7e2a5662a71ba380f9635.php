

<?php $__env->startSection('title', __('profile.following') . ' - ' . $user->getDisplayName()); ?>

<?php $__env->startSection('main-content'); ?>
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title"><?php echo e(__('profile.following')); ?> - <?php echo e($user->getDisplayName()); ?></h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="<?php echo e(route('home')); ?>" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-house f-s-16"></i> <?php echo e(__('dashboard.dashboard')); ?>

                            </span>
                        </a>
                    </li>
                    <li class="">
                        <a href="<?php echo e(route('user.show', $user)); ?>" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-user f-s-16"></i> <?php echo e($user->getDisplayName()); ?>

                            </span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#" class="f-s-14 f-w-500"><?php echo e(__('profile.following')); ?></a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Statistiche -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <h4 class="text-primary"><?php echo e($following->total()); ?></h4>
                                <p class="text-muted"><?php echo e(__('profile.following')); ?></p>
                            </div>
                            <div class="col-md-4">
                                <h4 class="text-success"><?php echo e($user->followers_count); ?></h4>
                                <p class="text-muted"><?php echo e(__('profile.followers')); ?></p>
                            </div>
                            <div class="col-md-4">
                                <h4 class="text-info"><?php echo e($user->videos_count + $user->photos_count + $user->poems_count); ?></h4>
                                <p class="text-muted">Contenuti</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista Following -->
        <div class="row">
            <?php $__empty_1 = true; $__currentLoopData = $following; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $followedUser): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-12 col-sm-6 col-lg-4 mb-4">
                <div class="card hover-effect h-100">
                    <div class="card-body text-center">
                        <!-- Avatar -->
                        <div class="mb-3">
                            <?php if($followedUser->profile_photo): ?>
                                <img src="<?php echo e($followedUser->profile_photo_url); ?>" 
                                     alt="<?php echo e($followedUser->getDisplayName()); ?>" 
                                     class="rounded-circle" 
                                     width="80" 
                                     height="80"
                                     style="object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                                     style="width: 80px; height: 80px;">
                                    <span class="text-white fw-bold f-s-20"><?php echo e(substr($followedUser->getDisplayName(), 0, 2)); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Nome e Info -->
                        <h5 class="card-title f-w-600 mb-2">
                            <a href="<?php echo e(route('user.show', $followedUser)); ?>" class="text-decoration-none">
                                <?php echo e($followedUser->getDisplayName()); ?>

                            </a>
                        </h5>
                        
                        <?php if($followedUser->nickname && $followedUser->nickname !== $followedUser->name): ?>
                        <p class="text-muted f-s-14 mb-2"><?php echo e($followedUser->nickname); ?></p>
                        <?php endif; ?>

                        <!-- Statistiche -->
                        <div class="row text-center mb-3">
                            <div class="col-4">
                                <div class="f-s-12 text-muted">Video</div>
                                <div class="fw-bold"><?php echo e($followedUser->videos_count); ?></div>
                            </div>
                            <div class="col-4">
                                <div class="f-s-12 text-muted">Foto</div>
                                <div class="fw-bold"><?php echo e($followedUser->photos_count); ?></div>
                            </div>
                            <div class="col-4">
                                <div class="f-s-12 text-muted">Poesie</div>
                                <div class="fw-bold"><?php echo e($followedUser->poems_count); ?></div>
                            </div>
                        </div>

                        <!-- Azioni -->
                        <div class="d-flex justify-content-center gap-2">
                            <a href="<?php echo e(route('user.show', $followedUser)); ?>" class="btn btn-primary btn-sm">
                                <i class="ph-duotone ph-user me-1"></i>
                                Profilo
                            </a>
                            <?php if(auth()->guard()->check()): ?>
                            <button type="button" 
                                    class="btn <?php echo e($followedUser->is_followed_by_current_user ?? false ? 'btn-success' : 'btn-outline-primary'); ?> btn-sm" 
                                    onclick="followUser(<?php echo e($followedUser->id); ?>)" 
                                    id="followBtn<?php echo e($followedUser->id); ?>">
                                <i class="ti <?php echo e($followedUser->is_followed_by_current_user ?? false ? 'ti-user-check' : 'ti-user'); ?> me-1"></i>
                                <span id="followText<?php echo e($followedUser->id); ?>">
                                    <?php echo e($followedUser->is_followed_by_current_user ?? false ? 'Following' : 'Follow'); ?>

                                </span>
                            </button>
                            <?php else: ?>
                            <div class="btn btn-outline-secondary btn-sm" style="opacity: 0.6;">
                                <i class="ti ti-user me-1"></i>
                                Follow
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ph-duotone ph-users f-s-48 text-muted mb-3"></i>
                        <h5 class="text-muted"><?php echo e(__('profile.no_following')); ?></h5>
                        <p class="text-muted">Questo utente non sta seguendo nessuno al momento.</p>
                        <a href="<?php echo e(route('home')); ?>" class="btn btn-primary">
                            <i class="ph-duotone ph-house me-2"></i>
                            Esplora la piattaforma
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Paginazione -->
        <?php if($following->hasPages()): ?>
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    <?php echo e($following->appends(request()->query())->links()); ?>

                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function followUser(userId) {
    // Verifica se l'utente è autenticato
    const isAuthenticated = <?php echo e(auth()->check() ? 'true' : 'false'); ?>;
    
    if (!isAuthenticated) {
        window.location.href = '<?php echo e(route("login")); ?>';
        return;
    }

    const button = document.getElementById('followBtn' + userId);
    const text = document.getElementById('followText' + userId);
    
    // Disabilita il pulsante durante la richiesta
    button.disabled = true;
    
    fetch('/api/follow/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            user_id: userId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Aggiorna il pulsante
            if (data.following) {
                button.innerHTML = '<i class="ti ti-user-check me-1"></i><span id="followText' + userId + '">Following</span>';
                button.classList.remove('btn-outline-primary');
                button.classList.add('btn-success');
            } else {
                button.innerHTML = '<i class="ti ti-user me-1"></i><span id="followText' + userId + '">Follow</span>';
                button.classList.remove('btn-success');
                button.classList.add('btn-outline-primary');
            }
            
            // Mostra notifica
            Swal.fire({
                icon: 'success',
                title: 'Successo!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            Swal.fire('Errore', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Errore connessione follow:', error);
        Swal.fire('Errore', 'Errore durante l\'operazione', 'error');
    })
    .finally(() => {
        button.disabled = false;
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/profile/following.blade.php ENDPATH**/ ?>