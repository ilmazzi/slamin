<?php

namespace App\Notifications;

use App\Models\GroupAnnouncement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PublicGroupAnnouncementCreated extends Notification implements ShouldQueue
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
        
        $message = "Nuovo annuncio pubblico da {$group->name}: \"{$this->announcement->title}\"";
        
        if ($this->announcement->hasPoll()) {
            $message .= " (con sondaggio)";
        }

        return [
            'type' => 'public_group_announcement_created',
            'title' => 'Nuovo Annuncio Pubblico',
            'message' => $message,
            'group_id' => $group->id,
            'announcement_id' => $this->announcement->id,
            'author_id' => $author->id,
            'group_name' => $group->name,
            'announcement_title' => $this->announcement->title,
            'has_poll' => $this->announcement->hasPoll(),
            'url' => route('groups.announcements.show', [$group, $this->announcement])
        ];
    }
}
