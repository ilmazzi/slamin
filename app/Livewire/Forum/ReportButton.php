<?php

namespace App\Livewire\Forum;

use App\Models\ForumReport;
use App\Notifications\Forum\PostReported;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ReportButton extends Component
{
    public $reportable;
    public $reportableType;
    public $reportableId;
    public $showModal = false;
    public $reason = '';
    public $description = '';

    public $reasons = [
        'spam' => 'Spam',
        'harassment' => 'Molestie o bullismo',
        'hate_speech' => 'Incitamento all\'odio',
        'inappropriate_content' => 'Contenuto inappropriato',
        'misinformation' => 'Disinformazione',
        'violence' => 'Violenza',
        'self_harm' => 'Autolesionismo',
        'other' => 'Altro',
    ];

    public function mount($reportable)
    {
        $this->reportable = $reportable;
        $this->reportableType = get_class($reportable);
        $this->reportableId = $reportable->id;
    }

    public function openModal()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->showModal = true;
    }

    public function submitReport()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate([
            'reason' => 'required|in:' . implode(',', array_keys($this->reasons)),
            'description' => 'required|string|min:10|max:1000',
        ]);

        $report = ForumReport::create([
            'reporter_id' => Auth::id(),
            'target_type' => $this->reportableType,
            'target_id' => $this->reportableId,
            'reason' => $this->reason,
            'description' => $this->description,
            'status' => 'pending',
        ]);

        // Notify content author
        $contentAuthor = $this->reportable->user;
        if ($contentAuthor->id !== Auth::id()) {
            $contentAuthor->notify(new PostReported($report));
        }

        $this->showModal = false;
        $this->reason = '';
        $this->description = '';

        // Dispatch browser event for SweetAlert
        $this->dispatch('swal:success', [
            'title' => 'Segnalazione Inviata!',
            'text' => 'Grazie per il tuo contributo. I moderatori revisioneranno questa segnalazione al più presto.',
        ]);
    }

    public function render()
    {
        return view('livewire.forum.report-button');
    }
}
