<?php

namespace App\Notifications;

use App\Models\GroupAnnouncement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class GroupAnnouncementCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public $announcement;

    /**
     * Create a new notification instance.
     */
    public function __construct(GroupAnnouncement $announcement)
    {
        $this->announcement = $announcement;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $group = $this->announcement->group;
        $author = $this->announcement->author;
        
        // Determina il messaggio in base alla visibilità
        $visibilityText = match($this->announcement->visibility) {
            'public' => 'pubblico',
            'members_only' => 'per i membri',
            'admins_only' => 'per gli amministratori',
            default => ''
        };

        $message = "Nuovo annuncio {$visibilityText} in {$group->name}: \"{$this->announcement->title}\"";
        
        if ($this->announcement->hasPoll()) {
            $message .= " (con sondaggio)";
        }

        return [
            'type' => 'group_announcement_created',
            'title' => 'Nuovo Annuncio',
            'message' => $message,
            'group_id' => $group->id,
            'announcement_id' => $this->announcement->id,
            'author_id' => $author->id,
            'group_name' => $group->name,
            'announcement_title' => $this->announcement->title,
            'visibility' => $this->announcement->visibility,
            'has_poll' => $this->announcement->hasPoll(),
            'url' => route('groups.announcements.show', [$group, $this->announcement])
        ];
    }
}
