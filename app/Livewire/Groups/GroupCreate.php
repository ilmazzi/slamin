<?php

namespace App\Livewire\Groups;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Group;
use App\Models\User;
use App\Models\GroupInvitation;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GroupCreate extends Component
{
    use WithFileUploads;

    public $name = '';
    public $description = '';
    public $visibility = 'public';
    public $image;
    public $website = '';
    public $social_facebook = '';
    public $social_instagram = '';
    public $social_youtube = '';
    public $social_twitter = '';
    public $social_tiktok = '';
    public $social_linkedin = '';
    
    // User search
    public $userSearch = '';
    public $searchResults = [];
    public $invitedUsers = [];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:groups',
            'description' => 'nullable|string|max:1000',
            'visibility' => 'required|in:public,private',
            'image' => 'nullable|image|max:2048',
            'website' => 'nullable|url|max:255',
            'social_facebook' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
            'social_youtube' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_tiktok' => 'nullable|url|max:255',
            'social_linkedin' => 'nullable|url|max:255',
        ];
    }

    protected function messages()
    {
        return [
            'name.required' => __('groups.validation.name_required'),
            'name.unique' => __('groups.validation.name_unique'),
            'visibility.required' => __('groups.validation.visibility_required'),
            'image.image' => __('groups.validation.image_invalid'),
            'image.max' => __('groups.validation.image_too_large'),
            'website.url' => __('groups.validation.url_invalid'),
            'social_facebook.url' => __('groups.validation.url_invalid'),
            'social_instagram.url' => __('groups.validation.url_invalid'),
            'social_youtube.url' => __('groups.validation.url_invalid'),
            'social_twitter.url' => __('groups.validation.url_invalid'),
            'social_tiktok.url' => __('groups.validation.url_invalid'),
            'social_linkedin.url' => __('groups.validation.url_invalid'),
        ];
    }

    public function mount()
    {
        if (!Auth::user()->can('groups.create')) {
            abort(403, __('groups.no_permission_create'));
        }
    }

    public function updatedUserSearch()
    {
        if (strlen($this->userSearch) >= 2) {
            $this->searchUsers();
        } else {
            $this->searchResults = [];
        }
    }

    public function searchUsers()
    {
        $this->searchResults = User::where(function($query) {
                $query->where('name', 'like', "%{$this->userSearch}%")
                      ->orWhere('nickname', 'like', "%{$this->userSearch}%")
                      ->orWhere('email', 'like', "%{$this->userSearch}%");
            })
            ->where('id', '!=', Auth::id())
            ->whereNotIn('id', collect($this->invitedUsers)->pluck('id'))
            ->limit(10)
            ->get(['id', 'name', 'nickname', 'email'])
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->getDisplayName(),
                    'email' => $user->email,
                ];
            })->toArray();
    }

    public function addUserToInviteList($userId)
    {
        $user = User::find($userId);
        
        if (!$user) {
            $this->dispatch('notify', ['message' => __('groups.user_not_found'), 'type' => 'error']);
            return;
        }

        if (collect($this->invitedUsers)->contains('id', $userId)) {
            $this->dispatch('notify', ['message' => __('groups.user_already_invited'), 'type' => 'warning']);
            return;
        }

        $this->invitedUsers[] = [
            'id' => $user->id,
            'name' => $user->getDisplayName(),
            'email' => $user->email,
        ];

        $this->userSearch = '';
        $this->searchResults = [];
        
        $this->dispatch('notify', ['message' => __('groups.user_added_to_invites'), 'type' => 'success']);
    }

    public function removeInvitedUser($userId)
    {
        $this->invitedUsers = collect($this->invitedUsers)
            ->filter(fn($user) => $user['id'] != $userId)
            ->values()
            ->toArray();
    }

    public function removeImage()
    {
        $this->image = null;
    }

    public function save()
    {
        $this->validate();

        $group = new Group();
        $group->name = $this->name;
        $group->description = $this->description;
        $group->visibility = $this->visibility;
        $group->created_by = Auth::id();
        $group->website = $this->website;
        $group->social_facebook = $this->social_facebook;
        $group->social_instagram = $this->social_instagram;
        $group->social_youtube = $this->social_youtube;
        $group->social_twitter = $this->social_twitter;
        $group->social_tiktok = $this->social_tiktok;
        $group->social_linkedin = $this->social_linkedin;

        // Upload image
        if ($this->image) {
            $imagePath = $this->image->store('groups', 'public');
            $group->image = $imagePath;
        }

        $group->save();

        // Send invitations
        if (!empty($this->invitedUsers)) {
            foreach ($this->invitedUsers as $invitedUser) {
                $invitation = GroupInvitation::create([
                    'group_id' => $group->id,
                    'user_id' => $invitedUser['id'],
                    'invited_by' => Auth::id(),
                    'message' => __('groups.invitation_message', ['group_name' => $group->name]),
                    'expires_at' => now()->addDays(7),
                ]);

                Notification::createGroupInvitation($invitation);
            }
        }

        $message = __('groups.created_successfully');
        if (count($this->invitedUsers) > 0) {
            $message .= ' ' . __('groups.invitations_sent', ['count' => count($this->invitedUsers)]);
        }

        session()->flash('success', $message);
        
        return $this->redirect(route('groups.show', $group));
    }

    public function render()
    {
        return view('livewire.groups.group-create')
            ->extends('layout.master')
            ->section('main-content');
    }
}

