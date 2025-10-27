<?php

namespace App\Livewire\Groups;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class GroupEdit extends Component
{
    use WithFileUploads;

    public Group $group;
    public $name = '';
    public $description = '';
    public $visibility = '';
    public $image;
    public $existingImage = '';
    public $website = '';
    public $social_facebook = '';
    public $social_instagram = '';
    public $social_youtube = '';
    public $social_twitter = '';
    public $social_tiktok = '';
    public $social_linkedin = '';
    public $removeExistingImage = false;

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('groups')->ignore($this->group->id)],
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

    public function mount(Group $group)
    {
        $user = Auth::user();

        if (!$user->isAdminOf($group) && !$user->hasRole('admin')) {
            abort(403, __('groups.no_permission_edit'));
        }

        $this->group = $group;
        $this->name = $group->name;
        $this->description = $group->description;
        $this->visibility = $group->visibility;
        $this->existingImage = $group->image;
        $this->website = $group->website;
        $this->social_facebook = $group->social_facebook;
        $this->social_instagram = $group->social_instagram;
        $this->social_youtube = $group->social_youtube;
        $this->social_twitter = $group->social_twitter;
        $this->social_tiktok = $group->social_tiktok;
        $this->social_linkedin = $group->social_linkedin;
    }

    public function removeImage()
    {
        $this->image = null;
    }

    public function removeExistingImageConfirm()
    {
        $this->removeExistingImage = true;
        $this->existingImage = null;
    }

    public function save()
    {
        $this->validate();

        $this->group->name = $this->name;
        $this->group->description = $this->description;
        $this->group->visibility = $this->visibility;
        $this->group->website = $this->website;
        $this->group->social_facebook = $this->social_facebook;
        $this->group->social_instagram = $this->social_instagram;
        $this->group->social_youtube = $this->social_youtube;
        $this->group->social_twitter = $this->social_twitter;
        $this->group->social_tiktok = $this->social_tiktok;
        $this->group->social_linkedin = $this->social_linkedin;

        // Remove existing image if requested
        if ($this->removeExistingImage && $this->group->image) {
            Storage::disk('public')->delete($this->group->image);
            $this->group->image = null;
        }

        // Upload new image
        if ($this->image) {
            // Delete old image
            if ($this->group->image) {
                Storage::disk('public')->delete($this->group->image);
            }

            $imagePath = $this->image->store('groups', 'public');
            $this->group->image = $imagePath;
        }

        $this->group->save();

        session()->flash('success', __('groups.updated_successfully'));
        
        return $this->redirect(route('groups.show', $this->group));
    }

    public function render()
    {
        return view('livewire.groups.group-edit')
            ->extends('layout.master')
            ->section('main-content');
    }
}

