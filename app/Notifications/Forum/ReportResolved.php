<?php

namespace App\Notifications\Forum;

use App\Models\ForumReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ReportResolved extends Notification implements ShouldQueue
{
    use Queueable;

    public $report;
    public $approved;

    public function __construct(ForumReport $report, bool $approved)
    {
        $this->report = $report;
        $this->approved = $approved;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $target = $this->report->target;
        $contentType = $target ? ($target instanceof \App\Models\ForumPost ? 'post' : 'commento') : 'contenuto';
        
        if ($this->approved) {
            $title = 'Segnalazione Approvata';
            $message = "La tua segnalazione è stata approvata. Il {$contentType} segnalato è stato rimosso.";
        } else {
            $title = 'Segnalazione Respinta';
            $message = "La tua segnalazione è stata revisionata e respinta. Il {$contentType} non viola le regole del forum.";
        }

        return [
            'title' => $title,
            'message' => $message,
            'type' => 'forum_report_resolved',
            'report_id' => $this->report->id,
            'approved' => $this->approved,
            'moderator_notes' => $this->report->moderator_notes,
        ];
    }
}
