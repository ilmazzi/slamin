<div>
    <!-- Notification Bell Button -->
    <li class="header-notification" wire:poll.15s="loadNotifications">
        <a class="d-flex-center position-relative header-icon"
           href="#"
           data-bs-toggle="offcanvas"
           data-bs-target="#notificationOffcanvas"
           aria-controls="notificationOffcanvas">
            <x-icon name="notification" size="24" />
            @if($unreadCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                </span>
            @endif
        </a>

        <!-- Offcanvas Notification Panel -->
        <div class="offcanvas offcanvas-end header-card"
             tabindex="-1"
             id="notificationOffcanvas"
             aria-labelledby="notificationOffcanvasLabel">
            
            <!-- Header -->
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="notificationOffcanvasLabel">
                    <x-icon name="notification" size="20" class="me-2" />{{ __('notifications.notifications') }}
                </h5>
                <div class="d-flex gap-2">
                    @if($unreadCount > 0)
                        <button class="btn btn-sm btn-primary"
                                wire:click="markAllAsRead()"
                                title="{{ __('notifications.mark_all_read') }}">
                            <i class="ph ph-check-circle"></i>
                        </button>
                    @endif
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="offcanvas"
                            aria-label="{{ __('header.close') }}">
                    </button>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="offcanvas-body app-scroll p-0">
                @if(count($notifications) > 0)
                    <div class="list-group list-group-flush">
                        @foreach($notifications as $notification)
                            <div class="list-group-item list-group-item-action {{ !$notification['is_read'] ? 'bg-light-primary' : '' }}"
                                 style="cursor: pointer;">
                                
                                <div class="d-flex gap-3 align-items-start">
                                    <!-- Avatar -->
                                    <div class="flex-shrink-0">
                                        <img src="{{ $notification['sender_avatar'] }}"
                                             alt="Avatar"
                                             class="rounded-circle"
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-grow-1 min-width-0"
                                         wire:click="markAsRead({{ $notification['id'] }})"
                                         onclick="window.location.href='{{ $notification['action_url'] }}'">
                                        
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <i class="{{ $notification['icon'] }} text-{{ $notification['color'] }}"></i>
                                            
                                            @if($notification['priority'] === 'urgent' || $notification['priority'] === 'high')
                                                <span class="badge bg-{{ $notification['color'] }} badge-sm">
                                                    {{ strtoupper($notification['priority']) }}
                                                </span>
                                            @endif
                                            
                                            @if(!$notification['is_read'])
                                                <span class="badge bg-primary badge-sm">{{ __('notifications.new') }}</span>
                                            @endif
                                        </div>

                                        <h6 class="mb-1 f-s-14 f-w-600">{{ $notification['title'] }}</h6>
                                        <p class="mb-1 f-s-13 text-muted">{{ $notification['message'] }}</p>
                                        
                                        <div class="d-flex align-items-center justify-content-between">
                                            <small class="text-muted f-s-12">
                                                <i class="ph ph-clock me-1"></i>{{ $notification['time_ago'] }}
                                            </small>
                                            
                                            @if($notification['action_text'])
                                                <span class="badge bg-light-{{ $notification['color'] }} text-{{ $notification['color'] }} f-s-11">
                                                    {{ $notification['action_text'] }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex-shrink-0">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light"
                                                    type="button"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                <i class="ph ph-dots-three-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                @if(!$notification['is_read'])
                                                    <li>
                                                        <button class="dropdown-item"
                                                                wire:click.stop="markAsRead({{ $notification['id'] }})">
                                                            <i class="ph ph-check me-2"></i>{{ __('notifications.mark_as_read') }}
                                                        </button>
                                                    </li>
                                                @endif
                                                <li>
                                                    <button class="dropdown-item text-danger"
                                                            wire:click.stop="deleteNotification({{ $notification['id'] }})">
                                                        <i class="ph ph-trash me-2"></i>{{ __('notifications.delete') }}
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center p-5">
                        <i class="ph ph-bell-slash text-muted" style="font-size: 48px;"></i>
                        <h6 class="text-muted mt-3">{{ __('notifications.no_notifications') }}</h6>
                        <p class="text-muted f-s-13">{{ __('notifications.notifications_placeholder') }}</p>
                    </div>
                @endif
            </div>

            <!-- Footer Actions -->
            @if(count($notifications) > 0)
                <div class="offcanvas-footer p-3 border-top">
                    <div class="d-grid gap-2">
                        <a href="{{ route('notifications.index') }}" class="btn btn-primary btn-sm">
                            <i class="ph ph-list me-1"></i>{{ __('notifications.view_all') }}
                        </a>
                        <button class="btn btn-secondary btn-sm"
                                wire:click="clearOld()">
                            <i class="ph ph-trash me-1"></i>{{ __('notifications.clear_old') }}
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </li>
</div>

@script
<script>
    // Listen for real-time notifications via Reverb
    Echo.private('App.Models.User.{{ Auth::id() }}')
        .listen('.notification.sent', (event) => {
            console.log('New notification received:', event);
            
            // Dispatch Livewire event to reload notifications
            $wire.dispatch('notification-sent', event);
            
            // Optional: Play sound
            // new Audio('/sounds/notification.mp3').play();
            
            // Optional: Show toast
            // Livewire.dispatch('show-toast', { message: event.title, type: 'info' });
        });
</script>
@endscript

