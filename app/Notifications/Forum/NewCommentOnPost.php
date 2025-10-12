<?php

namespace App\Notifications\Forum;

use App\Models\ForumComment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewCommentOnPost extends Notification implements ShouldQueue
{
    use Queueable;

    public $comment;

    public function __construct(ForumComment $comment)
    {
        $this->comment = $comment;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Nuovo Commento',
            'message' => "{$this->comment->user->name} ha commentato il tuo post \"{$this->comment->post->title}\"",
            'type' => 'forum_new_comment',
            'comment_id' => $this->comment->id,
            'post_id' => $this->comment->post_id,
            'subreddit_slug' => $this->comment->post->subreddit->slug,
            'commenter_id' => $this->comment->user_id,
        ];
    }
}
