<?php

namespace App\Livewire\Gigs;

use App\Models\Gig;
use App\Models\Event;
use App\Models\Group;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class GigEdit extends Component
{
    public Gig $gig;
    public $title;
    public $description;
    public $requirements;
    public $compensation;
    public $deadline;
    public $category;
    public $type;
    public $language;
    public $location;
    public $is_remote;
    public $is_urgent;
    public $is_featured;
    public $max_applications;
    public $event_id;
    public $group_id;
    public $allow_group_admin_edit;

    public function mount($gigId)
    {
        $this->gig = Gig::findOrFail($gigId);

        if (!Auth::check() || !$this->gig->canBeEditedBy(Auth::user())) {
            session()->flash('error', __('gigs.messages.unauthorized'));
            return redirect()->route('gigs.index');
        }

        $this->loadGigData();
    }

    public function loadGigData()
    {
        $this->title = $this->gig->title;
        $this->description = $this->gig->description;
        $this->requirements = $this->gig->requirements;
        $this->compensation = $this->gig->compensation;
        $this->deadline = $this->gig->deadline?->format('Y-m-d\TH:i');
        $this->category = $this->gig->category;
        $this->type = $this->gig->type;
        $this->language = $this->gig->language;
        $this->location = $this->gig->location;
        $this->is_remote = $this->gig->is_remote;
        $this->is_urgent = $this->gig->is_urgent;
        $this->is_featured = $this->gig->is_featured;
        $this->max_applications = $this->gig->max_applications;
        $this->event_id = $this->gig->event_id;
        $this->group_id = $this->gig->group_id;
        $this->allow_group_admin_edit = $this->gig->allow_group_admin_edit;
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

        $this->gig->update([
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
        ]);

        session()->flash('success', __('gigs.messages.gig_updated'));
        return redirect()->route('gigs.show', $this->gig);
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
        return view('livewire.gigs.gig-edit', [
            'events' => $this->events,
            'groups' => $this->groups,
        ])->extends('layout.master')->section('main-content');
    }
}

