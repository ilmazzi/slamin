<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageService;

class ProfileEdit extends Component
{
    use WithFileUploads;

    public $user;
    
    // Profile fields
    public $name;
    public $nickname;
    public $email;
    public $bio;
    public $location;
    public $website;
    public $birth_date;
    public $phone;
    
    // Social links
    public $social_facebook;
    public $social_instagram;
    public $social_twitter;
    public $social_youtube;
    public $social_linkedin;
    
    // Avatar and banner
    public $avatar;
    public $banner;
    public $avatarPreview;
    public $bannerPreview;
    
    // Privacy settings
    public $is_public;
    public $show_email;
    public $show_phone;
    public $show_birth_date;

    protected $rules = [
        'name' => 'required|string|max:255',
        'nickname' => 'required|string|max:50|unique:users,nickname',
        'email' => 'required|email|unique:users,email',
        'bio' => 'nullable|string|max:1000',
        'location' => 'nullable|string|max:255',
        'website' => 'nullable|url|max:255',
        'birth_date' => 'nullable|date|before:today',
        'phone' => 'nullable|string|max:20',
        'social_facebook' => 'nullable|url|max:255',
        'social_instagram' => 'nullable|url|max:255',
        'social_twitter' => 'nullable|url|max:255',
        'social_youtube' => 'nullable|url|max:255',
        'social_linkedin' => 'nullable|url|max:255',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        'is_public' => 'boolean',
        'show_email' => 'boolean',
        'show_phone' => 'boolean',
        'show_birth_date' => 'boolean',
    ];

    public function mount()
    {
        $this->user = Auth::user();
        $this->loadUserData();
    }

    public function loadUserData()
    {
        $this->name = $this->user->name;
        $this->nickname = $this->user->nickname;
        $this->email = $this->user->email;
        $this->bio = $this->user->bio;
        $this->location = $this->user->location;
        $this->website = $this->user->website;
        $this->birth_date = $this->user->birth_date?->format('Y-m-d');
        $this->phone = $this->user->phone;
        
        $this->social_facebook = $this->user->social_facebook;
        $this->social_instagram = $this->user->social_instagram;
        $this->social_twitter = $this->user->social_twitter;
        $this->social_youtube = $this->user->social_youtube;
        $this->social_linkedin = $this->user->social_linkedin;
        
        $this->is_public = $this->user->is_public;
        $this->show_email = $this->user->show_email;
        $this->show_phone = $this->user->show_phone;
        $this->show_birth_date = $this->user->show_birth_date;
    }

    public function updatedAvatar()
    {
        $this->validateOnly('avatar');
        if ($this->avatar) {
            $this->avatarPreview = $this->avatar->temporaryUrl();
        }
    }

    public function updatedBanner()
    {
        $this->validateOnly('banner');
        if ($this->banner) {
            $this->bannerPreview = $this->banner->temporaryUrl();
        }
    }

    public function removeAvatar()
    {
        $this->avatar = null;
        $this->avatarPreview = null;
    }

    public function removeBanner()
    {
        $this->banner = null;
        $this->bannerPreview = null;
    }

    public function save()
    {
        // Update validation rules for current user
        $this->rules['nickname'] = 'required|string|max:50|unique:users,nickname,' . $this->user->id;
        $this->rules['email'] = 'required|email|unique:users,email,' . $this->user->id;

        $this->validate();

        $data = [
            'name' => $this->name,
            'nickname' => $this->nickname,
            'email' => $this->email,
            'bio' => $this->bio,
            'location' => $this->location,
            'website' => $this->website,
            'birth_date' => $this->birth_date,
            'phone' => $this->phone,
            'social_facebook' => $this->social_facebook,
            'social_instagram' => $this->social_instagram,
            'social_twitter' => $this->social_twitter,
            'social_youtube' => $this->social_youtube,
            'social_linkedin' => $this->social_linkedin,
            'is_public' => $this->is_public,
            'show_email' => $this->show_email,
            'show_phone' => $this->show_phone,
            'show_birth_date' => $this->show_birth_date,
        ];

        // Handle avatar upload
        if ($this->avatar) {
            $avatarPath = $this->avatar->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
            
            // Generate thumbnail
            $imageService = app(ImageService::class);
            $thumbnailPath = $imageService->createThumbnail($avatarPath, 150, 150);
            $data['avatar_thumbnail'] = $thumbnailPath;
        }

        // Handle banner upload
        if ($this->banner) {
            $bannerPath = $this->banner->store('banners', 'public');
            $data['banner'] = $bannerPath;
        }

        $this->user->update($data);

        session()->flash('success', __('profile.updated_successfully'));
        
        // Redirect to profile
        return redirect()->route('profile.show');
    }

    public function render()
    {
        return view('livewire.profile.profile-edit');
    }
}


