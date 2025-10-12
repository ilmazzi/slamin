<?php

namespace App\Notifications\Forum;

use App\Models\ForumReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PostReported extends Notification implements ShouldQueue
{
    use Queueable;

    public $report;

    public function __construct(ForumReport $report)
    {
        $this->report = $report;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $target = $this->report->target;
        $contentType = $target instanceof \App\Models\ForumPost ? 'post' : 'commento';
        $contentTitle = $target instanceof \App\Models\ForumPost 
            ? $target->title 
            : \Illuminate\Support\Str::limit($target->content, 50);

        return [
            'title' => 'Contenuto Segnalato',
            'message' => "Il tuo {$contentType} \"{$contentTitle}\" è stato segnalato da un utente.",
            'type' => 'forum_content_reported',
            'report_id' => $this->report->id,
            'target_type' => $this->report->target_type,
            'target_id' => $this->report->target_id,
            'reason' => $this->report->reason,
        ];
    }
}
