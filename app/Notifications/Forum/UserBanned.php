<?php

namespace App\Notifications\Forum;

use App\Models\ForumBan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class UserBanned extends Notification implements ShouldQueue
{
    use Queueable;

    public $ban;

    public function __construct(ForumBan $ban)
    {
        $this->ban = $ban;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $subredditName = $this->ban->subreddit ? "r/{$this->ban->subreddit->name}" : "tutto il forum";
        
        if ($this->ban->type === 'permanent') {
            $duration = 'permanentemente';
        } else {
            $duration = "fino al " . $this->ban->expires_at->format('d/m/Y H:i');
        }

        return [
            'title' => 'Sei Stato Bannato',
            'message' => "Sei stato bannato da {$subredditName} {$duration}. Motivo: {$this->ban->reason}",
            'type' => 'forum_user_banned',
            'ban_id' => $this->ban->id,
            'subreddit_id' => $this->ban->subreddit_id,
            'ban_type' => $this->ban->type,
            'expires_at' => $this->ban->expires_at,
        ];
    }
}
