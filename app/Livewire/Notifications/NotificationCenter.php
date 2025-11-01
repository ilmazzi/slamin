<?php

namespace App\Livewire\Notifications;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationCenter extends Component
{
    public $notifications = [];
    public $unreadCount = 0;
    public $showDropdown = false;

    public function mount()
    {
        $this->loadNotifications();
    }

    #[On('notification-sent')]
    public function handleNewNotification($notificationData)
    {
        // Reload notifications when new one arrives
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $this->notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'action_url' => $notification->action_url,
                    'action_text' => $notification->action_text,
                    'priority' => $notification->priority,
                    'is_read' => $notification->is_read,
                    'created_at' => $notification->created_at,
                    'time_ago' => $notification->created_at->diffForHumans(),
                    'data' => $notification->data,
                    'sender_avatar' => $this->getSenderAvatar($notification),
                    'icon' => $this->getNotificationIcon($notification->type),
                    'color' => $this->getNotificationColor($notification->priority),
                ];
            })
            ->toArray();

        $this->unreadCount = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification && $notification->user_id === Auth::id()) {
            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
            $this->loadNotifications();
        }
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        $this->loadNotifications();
    }

    public function deleteNotification($notificationId)
    {
        $notification = Notification::find($notificationId);
        if ($notification && $notification->user_id === Auth::id()) {
            $notification->delete();
            $this->loadNotifications();
        }
    }

    public function clearOld()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', true)
            ->where('created_at', '<', now()->subDays(30))
            ->delete();
        $this->loadNotifications();
    }

    protected function getSenderAvatar($notification)
    {
        $data = $notification->data;
        
        // Extract sender user ID from notification data
        $senderId = null;
        
        if (isset($data['inviter_id'])) {
            $senderId = $data['inviter_id'];
        } elseif (isset($data['sender_id'])) {
            $senderId = $data['sender_id'];
        } elseif (isset($data['user_id'])) {
            $senderId = $data['user_id'];
        } elseif (isset($data['liker_id'])) {
            $senderId = $data['liker_id'];
        } elseif (isset($data['commenter_id'])) {
            $senderId = $data['commenter_id'];
        } elseif (isset($data['follower_id'])) {
            $senderId = $data['follower_id'];
        }

        if ($senderId) {
            $sender = \App\Models\User::find($senderId);
            if ($sender) {
                return \App\Helpers\AvatarHelper::getUserAvatarUrl($sender);
            }
        }

        // Default system avatar
        return asset('assets/images/logo.png');
    }

    protected function getNotificationIcon($type)
    {
        return match($type) {
            // Eventi
            Notification::TYPE_EVENT_INVITATION => 'ph-envelope',
            Notification::TYPE_INVITATION_ACCEPTED => 'ph-check-circle',
            Notification::TYPE_INVITATION_DECLINED => 'ph-x-circle',
            Notification::TYPE_NEW_REQUEST => 'ph-hand-waving',
            Notification::TYPE_REQUEST_ACCEPTED => 'ph-check-circle',
            Notification::TYPE_REQUEST_DECLINED => 'ph-x-circle',
            Notification::TYPE_REQUEST_CANCELLED => 'ph-arrow-u-up-left',
            Notification::TYPE_EVENT_UPDATE => 'ph-arrows-clockwise',
            Notification::TYPE_EVENT_CANCELLED => 'ph-x-circle',
            Notification::TYPE_EVENT_REMINDER => 'ph-bell-ringing',
            
            // Gruppi
            Notification::TYPE_GROUP_INVITATION => 'ph-users-three',
            Notification::TYPE_GROUP_INVITATION_ACCEPTED => 'ph-check-circle',
            Notification::TYPE_GROUP_INVITATION_DECLINED => 'ph-x-circle',
            Notification::TYPE_GROUP_JOIN_REQUEST => 'ph-user-plus',
            Notification::TYPE_GROUP_JOIN_REQUEST_ACCEPTED => 'ph-check-circle',
            Notification::TYPE_GROUP_JOIN_REQUEST_DECLINED => 'ph-x-circle',
            Notification::TYPE_GROUP_MEMBER_JOINED => 'ph-user-circle-plus',
            Notification::TYPE_GROUP_MEMBER_LEFT => 'ph-user-circle-minus',
            Notification::TYPE_GROUP_ROLE_CHANGED => 'ph-user-gear',
            Notification::TYPE_GROUP_ANNOUNCEMENT_CREATED => 'ph-megaphone',
            Notification::TYPE_PUBLIC_GROUP_ANNOUNCEMENT_CREATED => 'ph-megaphone',
            
            // Gigs
            Notification::TYPE_GIG_APPLICATION => 'ph-briefcase',
            Notification::TYPE_GIG_APPLICATION_ACCEPTED => 'ph-check-circle',
            Notification::TYPE_GIG_APPLICATION_REJECTED => 'ph-x-circle',
            Notification::TYPE_GIG_APPLICATION_WITHDRAWN => 'ph-arrow-u-up-left',
            Notification::TYPE_GIG_CLOSED => 'ph-lock',
            Notification::TYPE_GIG_REOPENED => 'ph-lock-open',
            Notification::TYPE_GIG_SHARED => 'ph-share-network',
            Notification::TYPE_GIG_GLOBAL_MESSAGE => 'ph-chat-circle-text',
            
            // Social
            Notification::TYPE_CONTENT_LIKED => 'ph-heart',
            Notification::TYPE_CONTENT_COMMENTED => 'ph-chat-circle',
            Notification::TYPE_COMMENT_LIKED => 'ph-heart',
            Notification::TYPE_VIDEO_SNAPPED => 'ph-hand-clap',
            
            // Moderazione
            Notification::TYPE_CONTENT_REPORTED => 'ph-warning',
            Notification::TYPE_MODERATION_RESPONSE => 'ph-shield-check',
            Notification::TYPE_MODERATION_UPDATE => 'ph-shield-warning',
            
            // Chat
            Notification::TYPE_CHAT_MESSAGE => 'ph-chat-circle-dots',
            
            // Disponibilità
            Notification::TYPE_AVAILABILITY_REQUEST => 'ph-calendar-check',
            Notification::TYPE_AVAILABILITY_RESPONSE => 'ph-calendar-check',
            
            // Traduzioni
            Notification::TYPE_TRANSLATION_PROPOSAL => 'ph-translate',
            Notification::TYPE_TRANSLATION_ACCEPTED => 'ph-check-circle',
            Notification::TYPE_TRANSLATION_REJECTED => 'ph-x-circle',
            Notification::TYPE_TRANSLATION_COUNTER => 'ph-arrows-left-right',
            Notification::TYPE_TRANSLATION_MESSAGE => 'ph-chat-circle',
            Notification::TYPE_TRANSLATION_SUBMITTED => 'ph-paper-plane-tilt',
            Notification::TYPE_TRANSLATION_APPROVED => 'ph-seal-check',
            
            default => 'ph-bell',
        };
    }

    protected function getNotificationColor($priority)
    {
        return match($priority) {
            Notification::PRIORITY_URGENT => 'danger',
            Notification::PRIORITY_HIGH => 'warning',
            Notification::PRIORITY_NORMAL => 'primary',
            Notification::PRIORITY_LOW => 'secondary',
            default => 'primary',
        };
    }

    public function render()
    {
        return view('livewire.notifications.notification-center');
    }
}

