<?php

namespace App\Notifications\Forum;

use App\Models\ForumPost;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PostModerated extends Notification implements ShouldQueue
{
    use Queueable;

    public $post;
    public $action;
    public $moderatorName;

    public function __construct(ForumPost $post, string $action, string $moderatorName)
    {
        $this->post = $post;
        $this->action = $action;
        $this->moderatorName = $moderatorName;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $messages = [
            'sticky' => "Il tuo post \"{$this->post->title}\" è stato fissato in alto da {$this->moderatorName}",
            'unsticky' => "Il tuo post \"{$this->post->title}\" è stato rimosso dai post fissati",
            'locked' => "Il tuo post \"{$this->post->title}\" è stato bloccato. Non sono ammessi nuovi commenti.",
            'unlocked' => "Il tuo post \"{$this->post->title}\" è stato sbloccato",
            'archived' => "Il tuo post \"{$this->post->title}\" è stato archiviato",
            'deleted' => "Il tuo post \"{$this->post->title}\" è stato eliminato da un moderatore",
        ];

        return [
            'title' => 'Azione Moderatore',
            'message' => $messages[$this->action] ?? "Il tuo post è stato modificato da un moderatore",
            'type' => 'forum_post_moderated',
            'action' => $this->action,
            'post_id' => $this->post->id,
            'subreddit_slug' => $this->post->subreddit->slug,
        ];
    }
}
