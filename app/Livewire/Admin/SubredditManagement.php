<?php

namespace App\Livewire\Admin;

use App\Models\Subreddit;
use App\Models\ForumModerator;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class SubredditManagement extends Component
{
    use WithPagination;

    public $showModal = false;
    public $editMode = false;
    public $subredditId = null;

    // Form fields
    public $name = '';
    public $slug = '';
    public $description = '';
    public $rules = '';
    public $color = '#007bff';
    public $is_active = true;
    public $is_private = false;

    // Moderators
    public $showModeratorsModal = false;
    public $currentSubreddit = null;
    public $moderators = [];
    public $searchUser = '';
    public $searchResults = [];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:subreddits,slug,' . $this->subredditId,
            'description' => 'required|string|max:500',
            'rules' => 'nullable|string|max:2000',
            'color' => 'required|string|max:7',
            'is_active' => 'boolean',
            'is_private' => 'boolean',
        ];
    }

    public function updatedName($value)
    {
        if (!$this->editMode) {
            $this->slug = Str::slug($value);
        }
    }

    public function create()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $subreddit = Subreddit::findOrFail($id);
        
        $this->subredditId = $subreddit->id;
        $this->name = $subreddit->name;
        $this->slug = $subreddit->slug;
        $this->description = $subreddit->description;
        $this->rules = $subreddit->rules;
        $this->color = $subreddit->color;
        $this->is_active = $subreddit->is_active;
        $this->is_private = $subreddit->is_private;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'rules' => $this->rules,
            'color' => $this->color,
            'is_active' => $this->is_active,
            'is_private' => $this->is_private,
        ];

        if ($this->editMode) {
            $subreddit = Subreddit::findOrFail($this->subredditId);
            $subreddit->update($data);
            $message = 'Subreddit aggiornato con successo!';
        } else {
            $data['created_by'] = auth()->id();
            Subreddit::create($data);
            $message = 'Subreddit creato con successo!';
        }

        $this->showModal = false;
        $this->resetForm();

        $this->dispatch('notify', [
            'message' => $message,
            'type' => 'success'
        ]);
    }

    public function toggleActive($id)
    {
        $subreddit = Subreddit::findOrFail($id);
        $subreddit->update(['is_active' => !$subreddit->is_active]);

        $this->dispatch('notify', [
            'message' => 'Stato subreddit aggiornato!',
            'type' => 'success'
        ]);
    }

    public function manageModerators($id)
    {
        $this->currentSubreddit = Subreddit::with('moderators.user')->findOrFail($id);
        $this->moderators = $this->currentSubreddit->moderators->toArray();
        $this->showModeratorsModal = true;
        $this->searchUser = '';
        $this->searchResults = [];
    }

    public function updatedSearchUser($value)
    {
        if (strlen($value) >= 2) {
            $this->searchResults = User::where('name', 'like', '%' . $value . '%')
                ->orWhere('email', 'like', '%' . $value . '%')
                ->take(5)
                ->get()
                ->toArray();
        } else {
            $this->searchResults = [];
        }
    }

    public function addModerator($userId)
    {
        $exists = ForumModerator::where('subreddit_id', $this->currentSubreddit->id)
            ->where('user_id', $userId)
            ->exists();

        if (!$exists) {
            ForumModerator::create([
                'subreddit_id' => $this->currentSubreddit->id,
                'user_id' => $userId,
                'added_by' => auth()->id(),
            ]);

            $this->dispatch('notify', [
                'message' => 'Moderatore aggiunto con successo!',
                'type' => 'success'
            ]);

            // Reload moderators
            $this->currentSubreddit->load('moderators.user');
            $this->moderators = $this->currentSubreddit->moderators->toArray();
            $this->searchUser = '';
            $this->searchResults = [];
        }
    }

    public function removeModerator($moderatorId)
    {
        ForumModerator::findOrFail($moderatorId)->delete();

        $this->dispatch('notify', [
            'message' => 'Moderatore rimosso!',
            'type' => 'success'
        ]);

        // Reload moderators
        $this->currentSubreddit->load('moderators.user');
        $this->moderators = $this->currentSubreddit->moderators->toArray();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function closeModeratorsModal()
    {
        $this->showModeratorsModal = false;
        $this->currentSubreddit = null;
        $this->moderators = [];
        $this->searchUser = '';
        $this->searchResults = [];
    }

    private function resetForm()
    {
        $this->subredditId = null;
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->rules = '';
        $this->color = '#007bff';
        $this->is_active = true;
        $this->is_private = false;
        $this->resetErrorBag();
    }

    public function render()
    {
        $subreddits = Subreddit::withCount(['posts', 'subscribers'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.subreddit-management', [
            'subreddits' => $subreddits
        ])->extends('layout.master')->section('main-content');
    }
}
