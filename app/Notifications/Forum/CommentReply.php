<?php

namespace App\Notifications\Forum;

use App\Models\ForumComment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class CommentReply extends Notification implements ShouldQueue
{
    use Queueable;

    public $reply;

    public function __construct(ForumComment $reply)
    {
        $this->reply = $reply;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Risposta al tuo Commento',
            'message' => "{$this->reply->user->name} ha risposto al tuo commento nel post \"{$this->reply->post->title}\"",
            'type' => 'forum_comment_reply',
            'reply_id' => $this->reply->id,
            'post_id' => $this->reply->post_id,
            'subreddit_slug' => $this->reply->post->subreddit->slug,
            'replier_id' => $this->reply->user_id,
        ];
    }
}
