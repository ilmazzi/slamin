<?php

namespace App\Livewire\Gigs;

use App\Models\GigApplication;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Auth;

class MyApplications extends Component
{
    use WithPagination;

    #[Url]
    public $status_filter = 'all';

    public function mount()
    {
        if (!Auth::check()) {
            session()->flash('error', __('gigs.messages.login_required'));
            return redirect()->route('login');
        }
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function withdrawApplication($applicationId)
    {
        $application = GigApplication::findOrFail($applicationId);
        
        if ($application->user_id === Auth::id() && $application->status === 'pending') {
            $application->update(['status' => 'withdrawn']);
            $application->gig->decrement('application_count');
            
            session()->flash('success', __('gigs.applications.application_withdrawn'));
        }
    }

    public function getMyApplicationsProperty()
    {
        $query = GigApplication::query()
            ->with(['gig.user', 'gig.event', 'gig.group'])
            ->where('user_id', Auth::id());

        if ($this->status_filter !== 'all') {
            $query->where('status', $this->status_filter);
        }

        $query->orderBy('created_at', 'desc');

        return $query->paginate(12);
    }

    public function getStatsProperty()
    {
        return [
            'total_applications' => GigApplication::where('user_id', Auth::id())->count(),
            'pending' => GigApplication::where('user_id', Auth::id())->where('status', 'pending')->count(),
            'accepted' => GigApplication::where('user_id', Auth::id())->where('status', 'accepted')->count(),
            'rejected' => GigApplication::where('user_id', Auth::id())->where('status', 'rejected')->count(),
        ];
    }

    public function render()
    {
        return view('livewire.gigs.my-applications', [
            'applications' => $this->myApplications,
            'stats' => $this->stats,
        ])->extends('layout.master')->section('main-content');
    }
}

