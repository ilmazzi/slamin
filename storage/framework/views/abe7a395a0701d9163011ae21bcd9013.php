<?php $__env->startSection('title', __('notifications.notifications') . ' - Slamin'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title"><?php echo e(__('notifications.notifications')); ?></h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li class="">
                    <a href="<?php echo e(route('dashboard')); ?>" class="f-s-14 f-w-500">
                        <span>
                            <i class="ph-duotone ph-house f-s-16"></i> <?php echo e(__('dashboard.dashboard')); ?>

                        </span>
                    </a>
                </li>
                <li class="active">
                    <a href="#" class="f-s-14 f-w-500"><?php echo e(__('notifications.notifications')); ?></a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <?php if(session('success')): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ph-duotone ph-check-circle me-2"></i>
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ph-duotone ph-warning me-2"></i>
                <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Header Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 f-w-600">Le Mie <?php echo e(__('notifications.notifications')); ?></h4>
                    <p class="text-muted mb-0"><?php echo e(__('notifications.manage_notifications')); ?></p>
                </div>
                <div class="d-flex gap-2">
                    <button id="markAllReadBtn" class="btn btn-outline-primary hover-effect">
                        <i class="ph-duotone ph-check-circle me-2"></i>Segna Tutte Come <?php echo e(__('notifications.read')); ?>

                    </button>
                    <button id="cleanupBtn" class="btn btn-outline-danger hover-effect">
                        <i class="ph-duotone ph-trash me-2"></i>Pulisci Vecchie
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card eshop-card hover-effect">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="icon-box bg-primary">
                                <i class="ph-duotone ph-bell text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Totale <?php echo e(__('notifications.notifications')); ?></h6>
                            <h4 class="mb-0 f-w-600"><?php echo e($notifications->total()); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card eshop-card hover-effect">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="icon-box bg-warning">
                                <i class="ph-duotone ph-envelope text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1"><?php echo e(__('notifications.unread')); ?></h6>
                            <h4 class="mb-0 f-w-600"><?php echo e($unreadCount); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card eshop-card hover-effect">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="icon-box bg-success">
                                <i class="ph-duotone ph-check-circle text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1"><?php echo e(__('notifications.read')); ?></h6>
                            <h4 class="mb-0 f-w-600"><?php echo e($notifications->total() - $unreadCount); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card eshop-card hover-effect">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="icon-box bg-info">
                                <i class="ph-duotone ph-calendar text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1"><?php echo e(__('notifications.last_7_days')); ?></h6>
                            <h4 class="mb-0 f-w-600"><?php echo e($notifications->where('created_at', '>=', now()->subDays(7))->count()); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="row">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="mb-0">Lista <?php echo e(__('notifications.notifications')); ?></h5>
                </div>
                <div class="card-body p-0">
                    <?php if($notifications->count() > 0): ?>
                        <div class="list-group list-group-flush">
                            <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="list-group-item notification-item <?php echo e($notification->is_read ? 'read' : 'unread'); ?>" data-notification-id="<?php echo e($notification->id); ?>">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="icon-box <?php echo e($notification->is_read ? 'bg-light' : 'bg-' . $notification->color); ?>">
                                            <i class="ph-duotone <?php echo e($notification->icon); ?> <?php echo e($notification->is_read ? 'text-muted' : 'text-white'); ?>"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-1 f-w-600 <?php echo e($notification->is_read ? 'text-muted' : ''); ?>">
                                                <?php echo e($notification->title); ?>

                                                <?php if(!$notification->is_read): ?>
                                                    <span class="badge bg-danger ms-2"><?php echo e(__('notifications.new')); ?></span>
                                                <?php endif; ?>
                                                <?php if($notification->priority_badge): ?>
                                                    <span class="badge <?php echo e($notification->priority_badge); ?> ms-2"><?php echo e(ucfirst($notification->priority)); ?></span>
                                                <?php endif; ?>
                                            </h6>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="ph-duotone ph-dots-three"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <?php if($notification->is_read): ?>
                                                        <li><a class="dropdown-item mark-unread-btn" href="#" data-id="<?php echo e($notification->id); ?>">
                                                            <i class="ph-duotone ph-envelope me-2"></i>Segna come non letta
                                                        </a></li>
                                                    <?php else: ?>
                                                        <li><a class="dropdown-item mark-read-btn" href="#" data-id="<?php echo e($notification->id); ?>">
                                                            <i class="ph-duotone ph-check-circle me-2"></i>Segna come letta
                                                        </a></li>
                                                    <?php endif; ?>
                                                    <?php if($notification->action_url): ?>
                                                        <li><a class="dropdown-item" href="<?php echo e($notification->action_url); ?>">
                                                            <i class="ph-duotone ph-arrow-right me-2"></i><?php echo e($notification->action_text ?? 'Visualizza'); ?>

                                                        </a></li>
                                                    <?php endif; ?>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-danger delete-notification-btn" href="#" data-id="<?php echo e($notification->id); ?>">
                                                            <i class="ph-duotone ph-trash me-2"></i>Elimina
                                                        </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <p class="mb-2 <?php echo e($notification->is_read ? 'text-muted' : ''); ?>"><?php echo e($notification->message); ?></p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="ph-duotone ph-clock me-1"></i>
                                                <?php echo e($notification->created_at->diffForHumans()); ?>

                                            </small>
                                            <?php if($notification->action_url): ?>
                                                <a href="<?php echo e($notification->action_url); ?>" class="btn btn-sm btn-primary">
                                                    <?php echo e($notification->action_text ?? 'Visualizza'); ?>

                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <!-- Pagination -->
                        <div class="card-footer">
                            <?php echo e($notifications->links()); ?>

                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <div class="icon-box bg-light mx-auto mb-3">
                                <i class="ph-duotone ph-bell text-muted f-s-48"></i>
                            </div>
                            <h5 class="text-muted"><?php echo e(__('notifications.no_notifications')); ?></h5>
                            <p class="text-muted">Non hai ancora ricevuto notifiche.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSRF Token for AJAX -->
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Mark as read
    document.querySelectorAll('.mark-read-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const notificationId = this.dataset.id;
            markAsRead(notificationId);
        });
    });

    // Mark as unread
    document.querySelectorAll('.mark-unread-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const notificationId = this.dataset.id;
            markAsUnread(notificationId);
        });
    });

    // Delete notification
    document.querySelectorAll('.delete-notification-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const notificationId = this.dataset.id;
            deleteNotification(notificationId);
        });
    });

    // Mark all as read
    document.getElementById('markAllReadBtn').addEventListener('click', function() {
        markAllAsRead();
    });

    // Cleanup old notifications
    document.getElementById('cleanupBtn').addEventListener('click', function() {
        cleanupNotifications();
    });

    function markAsRead(notificationId) {
        fetch(`/notifications/${notificationId}/read`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const item = document.querySelector(`[data-notification-id="${notificationId}"]`);
                item.classList.remove('unread');
                item.classList.add('read');
                updateNotificationCount();
                showNotification(data.message, 'success');
            }
        })
        .catch(error => {
            showNotification('Errore durante l\'operazione', 'error');
        });
    }

    function markAsUnread(notificationId) {
        fetch(`/notifications/${notificationId}/unread`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const item = document.querySelector(`[data-notification-id="${notificationId}"]`);
                item.classList.remove('read');
                item.classList.add('unread');
                updateNotificationCount();
                showNotification(data.message, 'success');
            }
        })
        .catch(error => {
            showNotification('Errore durante l\'operazione', 'error');
        });
    }

    function deleteNotification(notificationId) {
        if (confirm('Sei sicuro di voler eliminare questa notifica?')) {
            fetch(`/notifications/${notificationId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const item = document.querySelector(`[data-notification-id="${notificationId}"]`);
                    item.remove();
                    updateNotificationCount();
                    showNotification(data.message, 'success');
                }
            })
            .catch(error => {
                showNotification('Errore durante l\'eliminazione', 'error');
            });
        }
    }

    function markAllAsRead() {
        fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelectorAll('.notification-item').forEach(item => {
                    item.classList.remove('unread');
                    item.classList.add('read');
                });
                updateNotificationCount();
                showNotification(data.message, 'success');
            }
        })
        .catch(error => {
            showNotification('Errore durante l\'operazione', 'error');
        });
    }

    function cleanupNotifications() {
        if (confirm('Sei sicuro di voler eliminare le notifiche più vecchie di 30 giorni?')) {
            fetch('/notifications/cleanup', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                showNotification('Errore durante la pulizia', 'error');
            });
        }
    }

    function updateNotificationCount() {
        // Update sidebar notification count
        const sidebarBadge = document.querySelector('.sidebar .badge-notification');
        if (sidebarBadge) {
            const unreadCount = document.querySelectorAll('.notification-item.unread').length;
            if (unreadCount > 0) {
                sidebarBadge.textContent = unreadCount;
                sidebarBadge.style.display = 'inline';
            } else {
                sidebarBadge.style.display = 'none';
            }
        }
    }

    function showNotification(message, type) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: type === 'success' ? 'Successo!' : 'Errore!',
                text: message,
                icon: type,
                timer: 3000,
                showConfirmButton: false
            });
        } else {
            alert(message);
        }
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/notifications/index.blade.php ENDPATH**/ ?>
