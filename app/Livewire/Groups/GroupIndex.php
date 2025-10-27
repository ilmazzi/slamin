<?php

namespace App\Livewire\Groups;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GroupIndex extends Component
{
    use WithPagination;

    // Active Tab
    public $activeTab = 'groups'; // 'groups' or 'users'

    // Groups Filters
    public $groupSearch = '';
    public $groupFilter = '';
    public $groupSort = 'created_at';
    public $groupOrder = 'desc';

    // Users Filters
    public $userSearch = '';
    public $userFilter = '';
    public $userSort = 'created_at';
    public $userOrder = 'desc';

    protected $queryString = [
        'activeTab' => ['except' => 'groups'],
        'groupSearch' => ['except' => '', 'as' => 'gs'],
        'groupFilter' => ['except' => '', 'as' => 'gf'],
        'userSearch' => ['except' => '', 'as' => 'us'],
        'userFilter' => ['except' => '', 'as' => 'uf'],
    ];

    public function mount()
    {
        // Inizializzazione
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function clearGroupFilters()
    {
        $this->groupSearch = '';
        $this->groupFilter = '';
        $this->resetPage();
    }

    public function clearUserFilters()
    {
        $this->userSearch = '';
        $this->userFilter = '';
        $this->resetPage();
    }

    public function getGroupsProperty()
    {
        $user = Auth::user();
        $query = Group::query();

        // Apply filters
        if ($this->groupFilter) {
            switch ($this->groupFilter) {
                case 'my_groups':
                    $query->whereHas('members', function($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
                    break;
                case 'my_admin_groups':
                    $query->whereHas('members', function($q) use ($user) {
                        $q->where('user_id', $user->id)->where('role', 'admin');
                    });
                    break;
                case 'public':
                    $query->where('visibility', 'public');
                    break;
                case 'private':
                    if ($user->hasRole('admin')) {
                        $query->where('visibility', 'private');
                    }
                    break;
            }
        }

        // Apply search
        if ($this->groupSearch) {
            $query->where(function($q) {
                $q->where('name', 'like', "%{$this->groupSearch}%")
                  ->orWhere('description', 'like', "%{$this->groupSearch}%");
            });
        }

        // Apply sorting
        $query->orderBy($this->groupSort, $this->groupOrder);

        return $query->with(['creator', 'members.user'])
                    ->paginate(6, ['*'], 'groupsPage');
    }

    public function getUsersProperty()
    {
        $query = User::query();

        // Apply filters
        if ($this->userFilter) {
            switch ($this->userFilter) {
                case 'poets':
                    $query->role('poet');
                    break;
                case 'organizers':
                    $query->role('organizer');
                    break;
                case 'active':
                    $query->where('is_online', true);
                    break;
            }
        }

        // Apply search
        if ($this->userSearch) {
            $query->where(function($q) {
                $q->where('name', 'like', "%{$this->userSearch}%")
                  ->orWhere('nickname', 'like', "%{$this->userSearch}%")
                  ->orWhere('bio', 'like', "%{$this->userSearch}%");
            });
        }

        // Apply sorting
        $query->orderBy($this->userSort, $this->userOrder);

        return $query->with(['roles'])
                    ->paginate(6, ['*'], 'usersPage');
    }

    public function render()
    {
        return view('livewire.groups.group-index')
            ->extends('layout.master')
            ->section('main-content');
    }
}
