<?php

namespace App\Livewire\Groups;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public function updatedGroupSearch()
    {
        $this->resetPage('groupsPage');
    }

    public function updatedGroupFilter()
    {
        $this->resetPage('groupsPage');
    }

    public function updatedUserSearch()
    {
        $this->resetPage('usersPage');
    }

    public function updatedUserFilter()
    {
        $this->resetPage('usersPage');
    }

    public function clearGroupFilters()
    {
        $this->groupSearch = '';
        $this->groupFilter = '';
        $this->resetPage('groupsPage');
    }

    public function clearUserFilters()
    {
        $this->userSearch = '';
        $this->userFilter = '';
        $this->resetPage('usersPage');
    }

    public function getGroupsProperty()
    {
        $user = Auth::user();
        $query = Group::query();

        Log::info('GroupIndex - Loading groups', [
            'user_id' => $user?->id,
            'filter' => $this->groupFilter,
            'search' => $this->groupSearch,
        ]);

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
                    if ($user && $user->hasRole('admin')) {
                        $query->where('visibility', 'private');
                    }
                    break;
            }
        } else {
            // Se nessun filtro, mostra tutti i gruppi pubblici + i gruppi privati di cui l'utente è membro
            if ($user) {
                $query->where(function($q) use ($user) {
                    $q->where('visibility', 'public')
                      ->orWhereHas('members', function($q2) use ($user) {
                          $q2->where('user_id', $user->id);
                      });
                });
            } else {
                // Guest: solo gruppi pubblici
                $query->where('visibility', 'public');
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

        $results = $query->with(['creator', 'members.user'])
                    ->withCount('members')
                    ->paginate(12, ['*'], 'groupsPage');

        Log::info('GroupIndex - Groups loaded', [
            'total' => $results->total(),
            'current_page' => $results->currentPage(),
        ]);

        return $results;
    }

    public function getUsersProperty()
    {
        $currentUserId = Auth::id();
        $query = User::query();

        Log::info('GroupIndex - Loading users', [
            'current_user_id' => $currentUserId,
            'filter' => $this->userFilter,
            'search' => $this->userSearch,
        ]);

        // Apply filters
        if ($this->userFilter) {
            switch ($this->userFilter) {
                case 'poets':
                    $query->whereHas('roles', function($q) {
                        $q->where('name', 'poet');
                    });
                    break;
                case 'organizers':
                    $query->whereHas('roles', function($q) {
                        $q->where('name', 'organizer');
                    });
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

        $users = $query->with(['roles'])
                    ->withCount([
                        'poems as poems_count',
                        'articles as articles_count'
                    ])
                    ->paginate(12, ['*'], 'usersPage');

        Log::info('GroupIndex - Users loaded', [
            'total' => $users->total(),
            'current_page' => $users->currentPage(),
        ]);

        // Add follow status and total interactions for each user
        $users->getCollection()->transform(function($user) use ($currentUserId) {
            // Calculate total interactions (likes + comments given)
            $likesGiven = DB::table('unified_likes')
                ->where('user_id', $user->id)
                ->count();
            
            $commentsGiven = DB::table('unified_comments')
                ->where('user_id', $user->id)
                ->count();
            
            $user->total_interactions = $likesGiven + $commentsGiven;
            
            // Add follow status if user is authenticated
            if ($currentUserId) {
                $user->is_followed_by_current_user = DB::table('followers')
                    ->where('follower_id', $currentUserId)
                    ->where('following_id', $user->id)
                    ->exists();
            } else {
                $user->is_followed_by_current_user = false;
            }
            
            return $user;
        });

        return $users;
    }

    public function render()
    {
        return view('livewire.groups.group-index', [
                'groups' => $this->groups,
                'users' => $this->users,
            ])
            ->extends('layout.master')
            ->section('main-content');
    }
}
