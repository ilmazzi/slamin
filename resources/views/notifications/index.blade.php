@extends('layout.master')

@section('title', 'Notifiche - Slamin')

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">Notifiche</h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li class="">
                    <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                        <span>
                            <i class="ph-duotone ph-house f-s-16"></i> Dashboard
                        </span>
                    </a>
                </li>
                <li class="active">
                    <a href="#" class="f-s-14 f-w-500">Notifiche</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ph-duotone ph-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ph-duotone ph-warning me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
    @endif

    <!-- Header Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 f-w-600">Le Mie Notifiche</h4>
                    <p class="text-muted mb-0">Gestisci tutte le tue notifiche</p>
                </div>
                <div class="d-flex gap-2">
                    <button id="markAllReadBtn" class="btn btn-outline-primary hover-effect">
                        <i class="ph-duotone ph-check-circle me-2"></i>Segna Tutte Come Lette
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
                            <h6 class="mb-1">Totale Notifiche</h6>
                            <h4 class="mb-0 f-w-600">{{ $notifications->total() }}</h4>
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
                            <h6 class="mb-1">Non Lette</h6>
                            <h4 class="mb-0 f-w-600">{{ $unreadCount }}</h4>
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
                            <h6 class="mb-1">Lette</h6>
                            <h4 class="mb-0 f-w-600">{{ $notifications->total() - $unreadCount }}</h4>
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
                            <h6 class="mb-1">Ultimi 7 Giorni</h6>
                            <h4 class="mb-0 f-w-600">{{ $notifications->where('created_at', '>=', now()->subDays(7))->count() }}</h4>
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
                    <h5 class="mb-0">Lista Notifiche</h5>
                </div>
                <div class="card-body p-0">
                    @if($notifications->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($notifications as $notification)
                            <div class="list-group-item notification-item {{ $notification->is_read ? 'read' : 'unread' }}" data-notification-id="{{ $notification->id }}">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="icon-box {{ $notification->is_read ? 'bg-light' : 'bg-' . $notification->color }}">
                                            <i class="ph-duotone {{ $notification->icon }} {{ $notification->is_read ? 'text-muted' : 'text-white' }}"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-1 f-w-600 {{ $notification->is_read ? 'text-muted' : '' }}">
                                                {{ $notification->title }}
                                                @if(!$notification->is_read)
                                                    <span class="badge bg-danger ms-2">Nuova</span>
                                                @endif
                                                @if($notification->priority_badge)
                                                    <span class="badge {{ $notification->priority_badge }} ms-2">{{ ucfirst($notification->priority) }}</span>
                                                @endif
                                            </h6>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="ph-duotone ph-dots-three"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if($notification->is_read)
                                                        <li><a class="dropdown-item mark-unread-btn" href="#" data-id="{{ $notification->id }}">
                                                            <i class="ph-duotone ph-envelope me-2"></i>Segna come non letta
                                                        </a></li>
                                                    @else
                                                        <li><a class="dropdown-item mark-read-btn" href="#" data-id="{{ $notification->id }}">
                                                            <i class="ph-duotone ph-check-circle me-2"></i>Segna come letta
                                                        </a></li>
                                                    @endif
                                                    @if($notification->action_url)
                                                        <li><a class="dropdown-item" href="{{ $notification->action_url }}">
                                                            <i class="ph-duotone ph-arrow-right me-2"></i>{{ $notification->action_text ?? 'Visualizza' }}
                                                        </a></li>
                                                    @endif
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-danger delete-notification-btn" href="#" data-id="{{ $notification->id }}">
                                                            <i class="ph-duotone ph-trash me-2"></i>Elimina
                                                        </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <p class="mb-2 {{ $notification->is_read ? 'text-muted' : '' }}">{{ $notification->message }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="ph-duotone ph-clock me-1"></i>
                                                {{ $notification->created_at->diffForHumans() }}
                                            </small>
                                            @if($notification->action_url)
                                                <a href="{{ $notification->action_url }}" class="btn btn-sm btn-primary">
                                                    {{ $notification->action_text ?? 'Visualizza' }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="card-footer">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="icon-box bg-light mx-auto mb-3">
                                <i class="ph-duotone ph-bell text-muted f-s-48"></i>
                            </div>
                            <h5 class="text-muted">Nessuna notifica</h5>
                            <p class="text-muted">Non hai ancora ricevuto notifiche.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSRF Token for AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('script')
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
@endsection
