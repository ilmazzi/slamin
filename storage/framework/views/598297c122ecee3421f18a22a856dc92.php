

<!-- Footer Section starts-->
<footer>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9 col-12">
                <p class="footer-text f-w-600 mb-0"><?php echo __('common.copyright'); ?></p>
            </div>
            <div class="col-md-3">
                <div class="footer-text text-end">
                    <a class="f-w-500 text-primary" href="mailto:teqlathemes@gmail.com"> Need Help <i
                            class="ti ti-help"></i></a>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Footer Section ends-->
<?php if(auth()->guard()->check()): ?>
<script>
// Real-time Notifications System
class NotificationManager {
    constructor() {
        this.notifications = [];
        this.unreadCount = 0;
        this.isLoading = false;
        this.refreshInterval = null;

        this.init();
    }

    init() {
        // Load initial notifications
        this.loadNotifications();

        // Set up auto-refresh every 30 seconds
        this.refreshInterval = setInterval(() => {
            this.loadNotifications(true);
        }, 30000);

        // Load notifications when opening the dropdown
        document.getElementById('notificationTrigger').addEventListener('click', () => {
            this.loadNotifications();
        });

        // Handle page visibility change for battery optimization
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                clearInterval(this.refreshInterval);
            } else {
                this.refreshInterval = setInterval(() => {
                    this.loadNotifications(true);
                }, 30000);
                this.loadNotifications(true);
            }
        });
    }

    async loadNotifications(silent = false) {
        if (this.isLoading) return;

        this.isLoading = true;

        if (!silent) {
            this.showLoading();
        }

        try {
            const response = await fetch('/notifications/api/dropdown', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Network response was not ok');

            const data = await response.json();
            this.notifications = data.notifications;
            this.unreadCount = data.unread_count;

            this.updateUI();
            this.updateBadge();

        } catch (error) {
            console.error('Error loading notifications:', error);
            if (!silent) {
                this.showError();
            }
        } finally {
            this.isLoading = false;
            this.hideLoading();
        }
    }

    updateUI() {
        const container = document.getElementById('notificationsList');
        const emptyState = document.getElementById('notificationsEmpty');
        const footer = document.getElementById('notificationsFooter');

        if (this.notifications.length === 0) {
            container.style.display = 'none';
            emptyState.style.display = 'block';
            footer.style.display = 'none';
        } else {
            container.style.display = 'block';
            emptyState.style.display = 'none';
            footer.style.display = 'block';

            container.innerHTML = this.notifications.map(notification =>
                this.renderNotification(notification)
            ).join('');

            // Attach event listeners
            this.attachEventListeners();
        }

        // Update count in header
        document.getElementById('notificationCount').textContent = this.unreadCount;
    }

    renderNotification(notification) {
        const isUnread = !notification.is_read;
        const timeAgo = notification.created_at || 'Ora';

        // Controlla se è una notifica di invito a evento
        const isEventInvitation = notification.type === 'event_invitation';

        // Estrai event_id e invitation_id dai dati della notifica se disponibili
        let eventId = null;
        let invitationId = null;

        if (notification.data && isEventInvitation) {
            try {
                const data = typeof notification.data === 'string' ? JSON.parse(notification.data) : notification.data;
                eventId = data.event_id;
                invitationId = data.invitation_id;
            } catch (e) {

            }
        }

                return `
            <div class="d-flex align-items-start p-3 border-bottom ${isUnread ? 'bg-light-primary' : ''}" data-notification-id="${notification.id}">
                <div class="flex-shrink-0 me-3">
                    ${notification.sender_avatar ? `
                        <img src="${notification.sender_avatar}" alt="Avatar" class="h-40 w-40 rounded-circle object-fit-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="bg-light-${notification.color.replace('text-', '')} h-40 w-40 d-flex-center rounded-circle" style="display: none;">
                            <i class="${notification.icon} f-s-16 text-${notification.color.replace('text-', '')}"></i>
                        </div>
                    ` : `
                        <div class="bg-light-${notification.color.replace('text-', '')} h-40 w-40 d-flex-center rounded-circle">
                            <i class="${notification.icon} f-s-16 text-${notification.color.replace('text-', '')}"></i>
                        </div>
                    `}
                </div>
                <div class="flex-grow-1 pe-2">
                    <h6 class="mb-1 f-s-14 ${isUnread ? 'fw-bold' : ''}">${notification.title}</h6>
                    <p class="mb-1 f-s-13 text-muted">${notification.message}</p>

                    ${isEventInvitation && eventId && invitationId ? `
                        <div class="mt-2 d-flex gap-1">
                            <form action="/events/${eventId}/invitations/${invitationId}/accept" method="POST" class="d-inline invitation-form" data-invitation-id="${invitationId}">
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                <input type="hidden" name="_method" value="PATCH">
                                <button type="submit" class="btn btn-success btn-sm" title="Accetta">
                                    <i class="ph ph-check f-s-12"></i>
                                </button>
                            </form>
                            <form action="/events/${eventId}/invitations/${invitationId}/decline" method="POST" class="d-inline invitation-form" data-invitation-id="${invitationId}">
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                <input type="hidden" name="_method" value="PATCH">
                                <button type="submit" class="btn btn-danger btn-sm" title="Rifiuta">
                                    <i class="ph ph-x f-s-12"></i>
                                </button>
                            </form>
                        </div>
                    ` : ''}

                    ${!isEventInvitation && notification.action_url ? `
                        <div class="mt-2">
                            <a href="${notification.action_url}" class="btn btn-primary btn-sm">
                                ${notification.action_text || 'Visualizza'}
                            </a>
                        </div>
                    ` : ''}
                </div>
                <div class="flex-shrink-0">
                    <div class="dropdown">
                        <button class="btn btn-link btn-sm p-1" data-bs-toggle="dropdown">
                            <i class="ph ph-dots-three-vertical f-s-14"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            ${isUnread ? `
                                <li><a class="dropdown-item" href="#" onclick="markNotificationRead(${notification.id})">
                                    <i class="ph ph-check me-2"></i>Segna come letta
                                </a></li>
                            ` : `
                                <li><a class="dropdown-item" href="#" onclick="markNotificationUnread(${notification.id})">
                                    <i class="ph ph-arrow-counter-clockwise me-2"></i>Segna come non letta
                                </a></li>
                            `}
                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteNotification(${notification.id})">
                                <i class="ph ph-trash me-2"></i>Elimina
                            </a></li>
                        </ul>
                    </div>
                    <div class="mt-1">
                        <span class="badge bg-light-${notification.color.replace('text-', '')} text-primary f-s-10">${timeAgo}</span>
                    </div>
                </div>
            </div>
        `;
    }

    updateBadge() {
        const badge = document.getElementById('notificationBadge');
        const icon = document.getElementById('notificationIcon');

        if (this.unreadCount > 0) {
            badge.textContent = this.unreadCount > 99 ? '99+' : this.unreadCount;
            badge.style.display = 'block';

            // Cambia l'icona in bell.gif quando ci sono notifiche
            icon.src = '<?php echo e(asset("assets/images/bell.gif")); ?>';

            // Add pulse animation for new notifications
            badge.classList.add('animate__animated', 'animate__pulse');
            setTimeout(() => {
                badge.classList.remove('animate__animated', 'animate__pulse');
            }, 1000);
        } else {
            badge.style.display = 'none';

            // Cambia l'icona in bell.png quando non ci sono notifiche
            icon.src = '<?php echo e(asset("assets/images/bell.png")); ?>';
        }
    }

    showLoading() {
        document.getElementById('notificationsLoading').style.display = 'block';
        document.getElementById('notificationsList').style.display = 'none';
        document.getElementById('notificationsEmpty').style.display = 'none';
    }

    hideLoading() {
        document.getElementById('notificationsLoading').style.display = 'none';
    }

    showError() {
        const container = document.getElementById('notificationsList');
        container.innerHTML = `
            <div class="text-center p-4">
                <i class="ph ph-warning-circle display-4 text-warning mb-3"></i>
                <h6 class="text-muted"><?php echo e(__('common.loading_error')); ?></h6>
                <button class="btn btn-outline-primary btn-sm" onclick="notificationManager.loadNotifications()">
                    <i class="ph ph-arrow-clockwise me-1"></i>Riprova
                </button>
            </div>
        `;
        container.style.display = 'block';
    }

    attachEventListeners() {
        // Handle notification clicks
        document.querySelectorAll('[data-notification-id]').forEach(item => {
            item.addEventListener('click', (e) => {
                if (e.target.closest('.dropdown')) return;

                const notificationId = item.dataset.notificationId;
                const notification = this.notifications.find(n => n.id == notificationId);

                if (notification && notification.action_url) {
                    // Mark as read and navigate
                    this.markAsRead(notificationId);
                    window.location.href = notification.action_url;
                }
            });
        });

        // Handle invitation forms in notifications
        document.querySelectorAll('.invitation-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const invitationId = this.getAttribute('data-invitation-id');
                const notificationRow = this.closest('[data-notification-id]');

                // Nascondi immediatamente la notifica
                if (notificationRow) {
                    notificationRow.style.opacity = '0.5';
                    notificationRow.style.pointerEvents = 'none';
                }

                // Disabilita i pulsanti per evitare doppi click
                const buttons = this.querySelectorAll('button');
                buttons.forEach(button => {
                    button.disabled = true;
                    button.innerHTML = '<i class="ph ph-spinner ph-spin f-s-12"></i>';
                });
            });
        });
    }

    async markAsRead(notificationId) {
        try {
            await fetch(`/notifications/${notificationId}/read`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            });

            // Update local state
            const notification = this.notifications.find(n => n.id == notificationId);
            if (notification && !notification.is_read) {
                notification.is_read = true;
                this.unreadCount--;
                this.updateUI();
                this.updateBadge();
            }
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }

    async markAllAsRead() {
        try {
            const response = await fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                this.notifications.forEach(n => n.is_read = true);
                this.unreadCount = 0;
                this.updateUI();
                this.updateBadge();

                this.showToast('Tutte le notifiche sono state segnate come lette', 'success');
            }
        } catch (error) {
            console.error('Error marking all notifications as read:', error);
            this.showToast('Errore nell\'operazione', 'error');
        }
    }

    async deleteNotification(notificationId) {
        try {
            await fetch(`/notifications/${notificationId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            // Remove from local state
            this.notifications = this.notifications.filter(n => n.id != notificationId);
            const wasUnread = this.notifications.find(n => n.id == notificationId && !n.is_read);
            if (wasUnread) this.unreadCount--;

            this.updateUI();
            this.updateBadge();

        } catch (error) {
            console.error('Error deleting notification:', error);
        }
    }

    showToast(message, type) {
        // Simple toast notification
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(toast);

        setTimeout(() => toast.remove(), 5000);
    }

    timeAgo(date) {
        const now = new Date();
        const diff = now - date;
        const minutes = Math.floor(diff / 60000);

        if (minutes < 1) return 'Ora';
        if (minutes < 60) return `${minutes}m fa`;

        const hours = Math.floor(minutes / 60);
        if (hours < 24) return `${hours}h fa`;

        const days = Math.floor(hours / 24);
        if (days < 7) return `${days}g fa`;

        return date.toLocaleDateString('it-IT');
    }

    destroy() {
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
        }
    }
}

// Global functions for notification actions
async function markNotificationRead(notificationId) {
    await notificationManager.markAsRead(notificationId);
}

async function markNotificationUnread(notificationId) {
    try {
        await fetch(`/notifications/${notificationId}/unread`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        notificationManager.loadNotifications();
    } catch (error) {
        console.error('Error:', error);
    }
}

async function deleteNotification(notificationId) {
    if (confirm('Sei sicuro di voler eliminare questa notifica?')) {
        await notificationManager.deleteNotification(notificationId);
    }
}

async function markAllNotificationsRead() {
    await notificationManager.markAllAsRead();
}

async function clearOldNotifications() {
    if (confirm('Eliminare tutte le notifiche lette più vecchie di 30 giorni?')) {
        try {
            await fetch('/notifications/cleanup', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            notificationManager.loadNotifications();
            notificationManager.showToast('<?php echo e(__('notifications.notifications')); ?> vecchie eliminate', 'success');
        } catch (error) {
            notificationManager.showToast('Errore nell\'operazione', 'error');
        }
    }
}

// Initialize notification manager when DOM is ready
let notificationManager;
document.addEventListener('DOMContentLoaded', function() {
    notificationManager = new NotificationManager();
});

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (notificationManager) {
        notificationManager.destroy();
    }
});

// No custom CSS - using template classes only
</script>
<?php endif; ?>

<!-- Notification Test (Development Only) -->
<?php if(app()->environment('local')): ?>
<script>
    // Test notification function (development only)
    function testNotification() {
        fetch('/notifications/test', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        }).then(() => {
            if (window.notificationManager) {
                notificationManager.loadNotifications();
            }
        });
    }

    // Add test button in development
    document.addEventListener('DOMContentLoaded', function() {
        if (window.location.hostname === 'localhost' || window.location.hostname.includes('127.0.0.1')) {
            const testBtn = document.createElement('button');
            testBtn.innerHTML = '<i class="ph ph-bell-ringing"></i> Test Notification';
            testBtn.className = 'btn btn-outline-warning btn-sm position-fixed';
            testBtn.style.cssText = 'bottom: 20px; left: 20px; z-index: 9999;';
            testBtn.onclick = testNotification;
            document.body.appendChild(testBtn);
        }
    });
</script>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\slamin\resources\views/layout/footer.blade.php ENDPATH**/ ?>