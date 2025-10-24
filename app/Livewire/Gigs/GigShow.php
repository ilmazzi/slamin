<?php

namespace App\Livewire\Gigs;

use App\Models\Gig;
use App\Models\GigApplication;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class GigShow extends Component
{
    public Gig $gig;
    public $showApplicationForm = false;
    public $applicationMessage = '';
    public $applicationExperience = '';
    public $applicationPortfolio = '';
    public $applicationAvailability = '';
    public $applicationCompensationExpectation = '';

    public function mount($gigId)
    {
        $this->gig = Gig::with(['user', 'event', 'group', 'applications.user'])
            ->findOrFail($gigId);
    }

    public function toggleApplicationForm()
    {
        if (!Auth::check()) {
            session()->flash('error', __('gigs.messages.login_to_interact'));
            return redirect()->route('login');
        }

        if (Auth::user()->hasRole('audience')) {
            session()->flash('error', __('gigs.messages.audience_not_allowed'));
            return;
        }

        $this->showApplicationForm = !$this->showApplicationForm;
    }

    public function submitApplication()
    {
        if (!Auth::check()) {
            session()->flash('error', __('gigs.messages.login_to_interact'));
            return redirect()->route('login');
        }

        if (!$this->gig->canUserApply(Auth::user())) {
            session()->flash('error', __('gigs.applications.cannot_apply'));
            return;
        }

        $this->validate([
            'applicationMessage' => 'required|min:10|max:1000',
            'applicationExperience' => 'nullable|max:1000',
            'applicationPortfolio' => 'nullable|url|max:500',
            'applicationAvailability' => 'nullable|max:500',
            'applicationCompensationExpectation' => 'nullable|max:200',
        ]);

        GigApplication::create([
            'gig_id' => $this->gig->id,
            'user_id' => Auth::id(),
            'message' => $this->applicationMessage,
            'experience' => $this->applicationExperience,
            'portfolio_url' => $this->applicationPortfolio,
            'availability' => $this->applicationAvailability,
            'compensation_expectation' => $this->applicationCompensationExpectation,
            'status' => 'pending',
        ]);

        $this->gig->increment('application_count');

        session()->flash('success', __('gigs.applications.application_sent'));
        
        $this->reset(['applicationMessage', 'applicationExperience', 'applicationPortfolio', 'applicationAvailability', 'applicationCompensationExpectation', 'showApplicationForm']);
        
        $this->gig->refresh();
    }

    public function closeGig()
    {
        if (!Auth::check() || !$this->gig->canBeEditedBy(Auth::user())) {
            session()->flash('error', __('gigs.messages.unauthorized'));
            return;
        }

        $this->gig->close();
        session()->flash('success', __('gigs.messages.gig_closed'));
        $this->gig->refresh();
    }

    public function reopenGig()
    {
        if (!Auth::check() || !$this->gig->canBeEditedBy(Auth::user())) {
            session()->flash('error', __('gigs.messages.unauthorized'));
            return;
        }

        $this->gig->reopen();
        session()->flash('success', __('gigs.messages.gig_reopened'));
        $this->gig->refresh();
    }

    public function shareGig()
    {
        if (!Auth::check() || !$this->gig->canBeEditedBy(Auth::user())) {
            session()->flash('error', __('gigs.messages.unauthorized'));
            return;
        }

        $count = $this->gig->share();
        session()->flash('success', __('gigs.messages.gig_shared', ['count' => $count]));
    }

    public function deleteGig()
    {
        if (!Auth::check() || !$this->gig->canBeEditedBy(Auth::user())) {
            session()->flash('error', __('gigs.messages.unauthorized'));
            return;
        }

        $this->gig->delete();
        session()->flash('success', __('gigs.messages.gig_deleted'));
        return redirect()->route('gigs.my-gigs');
    }

    public function getUserApplicationProperty()
    {
        if (!Auth::check()) {
            return null;
        }

        return $this->gig->applications()
            ->where('user_id', Auth::id())
            ->first();
    }

    public function render()
    {
        return view('livewire.gigs.gig-show', [
            'userApplication' => $this->userApplication,
        ])->extends('layout.master')->section('main-content');
    }
}

