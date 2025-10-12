<?php

namespace App\Livewire\Admin\Gamification;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Badge;
use App\Models\User;
use App\Services\BadgeService;
use Illuminate\Support\Facades\Storage;

class BadgeManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $badges;
    public $badge;
    public $isEditing = false;
    public $showModal = false;
    
    // Badge form fields
    public $type = 'portal';
    public $name;
    public $description;
    public $category;
    public $criteria_type = 'count';
    public $criteria_value = 1;
    public $points = 10;
    public $order = 0;
    public $is_active = true;
    public $icon;
    public $existing_icon;

    // Manual assignment
    public $showAssignModal = false;
    public $selectedBadgeId;
    public $userId;
    public $userSearch = '';
    public $searchResults = [];
    public $selectedUser = null;
    public $assignNotes;

    protected $rules = [
        'type' => 'required|in:portal,event',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'category' => 'required|string',
        'criteria_type' => 'required|in:count,milestone,first_time,streak,special',
        'criteria_value' => 'required|integer|min:1',
        'points' => 'required|integer|min:0',
        'order' => 'required|integer|min:0',
        'is_active' => 'boolean',
        'icon' => 'nullable|image|max:1024',
    ];

    public function mount()
    {
        $this->loadBadges();
    }

    public function loadBadges()
    {
        $this->badges = Badge::orderBy('type')->orderBy('category')->orderBy('order')->get();
    }

    public function create()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function edit($badgeId)
    {
        $badge = Badge::findOrFail($badgeId);
        
        $this->badge = $badge;
        $this->type = $badge->type;
        $this->name = $badge->name;
        $this->description = $badge->description;
        $this->category = $badge->category;
        $this->criteria_type = $badge->criteria_type;
        $this->criteria_value = $badge->criteria_value;
        $this->points = $badge->points;
        $this->order = $badge->order;
        $this->is_active = $badge->is_active;
        $this->existing_icon = $badge->icon_path;
        
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $iconPath = $this->existing_icon;
        
        if ($this->icon) {
            $iconPath = $this->icon->store('badges', 'public');
            
            // Delete old icon if exists and not default
            if ($this->existing_icon && $this->existing_icon !== 'assets/images/draghetto.png') {
                Storage::disk('public')->delete($this->existing_icon);
            }
        }

        $data = [
            'type' => $this->type,
            'name' => $this->name,
            'description' => $this->description,
            'category' => $this->category,
            'criteria_type' => $this->criteria_type,
            'criteria_value' => $this->criteria_value,
            'points' => $this->points,
            'order' => $this->order,
            'is_active' => $this->is_active,
            'icon_path' => $iconPath ?: 'assets/images/draghetto.png',
        ];

        if ($this->isEditing) {
            $this->badge->update($data);
            $this->dispatch('swal:success', ['title' => 'Successo!', 'text' => 'Badge aggiornato con successo!']);
        } else {
            Badge::create($data);
            $this->dispatch('swal:success', ['title' => 'Successo!', 'text' => 'Badge creato con successo!']);
        }

        $this->loadBadges();
        $this->showModal = false;
        $this->resetForm();
    }

    public function delete($badgeId)
    {
        $badge = Badge::findOrFail($badgeId);
        
        // Delete icon if not default
        if ($badge->icon_path && $badge->icon_path !== 'assets/images/draghetto.png') {
            Storage::disk('public')->delete($badge->icon_path);
        }
        
        $badge->delete();
        
        $this->dispatch('swal:success', ['title' => 'Successo!', 'text' => 'Badge eliminato con successo!']);
        $this->loadBadges();
    }

    public function toggleActive($badgeId)
    {
        $badge = Badge::findOrFail($badgeId);
        $badge->is_active = !$badge->is_active;
        $badge->save();
        
        $this->loadBadges();
        $this->dispatch('swal:success', ['title' => 'Successo!', 'text' => 'Stato badge aggiornato!']);
    }

    public function openAssignModal($badgeId)
    {
        $this->selectedBadgeId = $badgeId;
        $this->userId = null;
        $this->userSearch = '';
        $this->searchResults = [];
        $this->selectedUser = null;
        $this->assignNotes = '';
        $this->showAssignModal = true;
    }

    public function updatedUserSearch()
    {
        if (strlen($this->userSearch) >= 2) {
            $this->searchResults = User::where(function($query) {
                $query->where('name', 'like', '%' . $this->userSearch . '%')
                      ->orWhere('nickname', 'like', '%' . $this->userSearch . '%')
                      ->orWhere('email', 'like', '%' . $this->userSearch . '%');
            })
            ->limit(10)
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'nickname' => $user->nickname,
                    'email' => $user->email,
                    'display_name' => $user->getDisplayName(),
                    'avatar' => $user->profile_photo_url ?? asset('assets/images/avatar/default-avatar.webp'),
                ];
            });
        } else {
            $this->searchResults = [];
        }
    }

    public function selectUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $this->selectedUser = [
                'id' => $user->id,
                'name' => $user->name,
                'nickname' => $user->nickname,
                'email' => $user->email,
                'display_name' => $user->getDisplayName(),
                'avatar' => $user->profile_photo_url ?? asset('assets/images/avatar/default-avatar.webp'),
            ];
            $this->userId = $user->id;
            $this->userSearch = $user->getDisplayName();
            $this->searchResults = [];
        }
    }

    public function clearSelectedUser()
    {
        $this->selectedUser = null;
        $this->userId = null;
        $this->userSearch = '';
        $this->searchResults = [];
    }

    public function assignBadgeToUser()
    {
        $this->validate([
            'userId' => 'required|exists:users,id',
            'assignNotes' => 'nullable|string',
        ]);

        $user = User::findOrFail($this->userId);
        $badge = Badge::findOrFail($this->selectedBadgeId);
        $admin = auth()->user();

        $badgeService = app(BadgeService::class);
        $result = $badgeService->manuallyAwardBadge($user, $badge, $admin, $this->assignNotes);

        if ($result) {
            $this->dispatch('swal:success', ['title' => 'Badge Assegnato!', 'text' => "Badge assegnato con successo a {$user->getDisplayName()}!"]);
        } else {
            $this->dispatch('swal:warning', ['title' => 'Attenzione', 'text' => 'L\'utente ha già questo badge!']);
        }

        $this->showAssignModal = false;
        $this->clearSelectedUser();
    }

    private function resetForm()
    {
        $this->reset(['name', 'description', 'category', 'criteria_value', 'points', 'order', 'icon', 'existing_icon', 'assignNotes', 'userId', 'userSearch', 'searchResults', 'selectedUser']);
        $this->type = 'portal';
        $this->criteria_type = 'count';
        $this->is_active = true;
    }

    public function render()
    {
        return view('livewire.admin.gamification.badge-management');
    }
}
