<?php

namespace App\Livewire\Gigs;

use App\Models\Gig;
use App\Models\Event;
use App\Models\Group;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class GigCreation extends Component
{
    public $title = '';
    public $description = '';
    public $requirements = '';
    public $compensation = '';
    public $deadline = '';
    public $category = '';
    public $type = '';
    public $language = '';
    public $location = '';
    public $is_remote = false;
    public $is_urgent = false;
    public $is_featured = false;
    public $max_applications = null;
    public $event_id = null;
    public $group_id = null;
    public $allow_group_admin_edit = false;

    public function mount()
    {
        if (!Auth::check()) {
            session()->flash('error', __('gigs.messages.login_required'));
            return redirect()->route('login');
        }

        if (Auth::user()->hasRole('audience')) {
            session()->flash('error', __('gigs.messages.audience_not_allowed'));
            return redirect()->route('gigs.index');
        }
    }

    public function rules()
    {
        return [
            'title' => 'required|string|min:5|max:255',
            'description' => 'required|string|min:20|max:5000',
            'requirements' => 'nullable|string|max:2000',
            'compensation' => 'nullable|string|max:255',
            'deadline' => 'required|date|after:now',
            'category' => 'required|in:performance,hosting,judging,technical,translation,other',
            'type' => 'required|in:paid,volunteer,collaboration',
            'language' => 'nullable|in:it,en,es,fr,de,pt',
            'location' => 'nullable|string|max:255',
            'is_remote' => 'boolean',
            'is_urgent' => 'boolean',
            'is_featured' => 'boolean',
            'max_applications' => 'nullable|integer|min:1|max:100',
            'event_id' => 'nullable|exists:events,id',
            'group_id' => 'nullable|exists:groups,id',
            'allow_group_admin_edit' => 'boolean',
        ];
    }

    public function save()
    {
        $this->validate();

        $gig = Gig::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'description' => $this->description,
            'requirements' => $this->requirements,
            'compensation' => $this->compensation,
            'deadline' => $this->deadline,
            'category' => $this->category,
            'type' => $this->type,
            'language' => $this->language,
            'location' => $this->location,
            'is_remote' => $this->is_remote,
            'is_urgent' => $this->is_urgent,
            'is_featured' => $this->is_featured && Auth::user()->hasRole('admin'),
            'max_applications' => $this->max_applications,
            'event_id' => $this->event_id,
            'group_id' => $this->group_id,
            'allow_group_admin_edit' => $this->allow_group_admin_edit,
            'is_closed' => false,
            'application_count' => 0,
            'accepted_applications_count' => 0,
        ]);

        session()->flash('success', __('gigs.messages.gig_created'));
        return redirect()->route('gigs.show', $gig);
    }

    public function getEventsProperty()
    {
        return Event::where('start_datetime', '>=', now())
            ->orderBy('start_datetime')
            ->take(50)
            ->get();
    }

    public function getGroupsProperty()
    {
        return Auth::user()->groups()
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('livewire.gigs.gig-creation', [
            'events' => $this->events,
            'groups' => $this->groups,
        ])->extends('layout.master')->section('main-content');
    }
}

