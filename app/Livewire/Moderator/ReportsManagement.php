<?php

namespace App\Livewire\Moderator;

use App\Models\ForumReport;
use App\Models\Subreddit;
use App\Notifications\Forum\ReportResolved;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class ReportsManagement extends Component
{
    use WithPagination;

    public $statusFilter = 'pending';
    public $selectedReport = null;
    public $showResolveModal = false;
    public $moderatorNotes = '';

    public function setFilter($type, $value)
    {
        $this->{$type} = $value;
        $this->resetPage();
    }

    public function viewReport($reportId)
    {
        $this->selectedReport = ForumReport::with(['reporter', 'target', 'handledBy'])
            ->findOrFail($reportId);
        $this->showResolveModal = true;
    }

    public function resolveReport($action)
    {
        if (!$this->selectedReport) return;

        $newStatus = $action === 'approved' ? 'resolved' : 'dismissed';

        $this->selectedReport->update([
            'status' => $newStatus,
            'handled_by' => Auth::id(),
            'handled_at' => now(),
            'moderator_notes' => $this->moderatorNotes,
        ]);

        // Notify reporter
        $this->selectedReport->reporter->notify(new ReportResolved($this->selectedReport, $action === 'approved'));

        // If approved (report is valid), take action on content
        if ($action === 'approved') {
            $target = $this->selectedReport->target;
            
            if ($target instanceof \App\Models\ForumPost) {
                $target->delete();
                $message = 'Post segnalato eliminato';
            } elseif ($target instanceof \App\Models\ForumComment) {
                $target->softDelete(Auth::user());
                $message = 'Commento segnalato eliminato';
            }
        } else {
            $message = 'Segnalazione respinta';
        }

        $this->showResolveModal = false;
        $this->selectedReport = null;
        $this->moderatorNotes = '';

        $this->dispatch('notify', [
            'message' => $message,
            'type' => 'success'
        ]);
    }

    public function render()
    {
        // Build query
        $query = ForumReport::with(['reporter', 'target', 'handledBy']);

        $query->where('status', $this->statusFilter);
        $query->latest();

        $reports = $query->paginate(20);

        return view('livewire.moderator.reports-management', [
            'reports' => $reports,
        ]);
    }
}
