<?php

namespace App\Livewire\Admin\Gamification;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\UserBadge;
use App\Models\Badge;
use App\Models\User;
use App\Services\BadgeService;

class UserBadges extends Component
{
    use WithPagination;

    public $filterBadge = '';
    public $filterUser = '';
    public $sortBy = 'earned_at';
    public $sortDirection = 'desc';

    public function updatingFilterBadge()
    {
        $this->resetPage();
    }

    public function updatingFilterUser()
    {
        $this->resetPage();
    }

    public function sortByColumn($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function removeBadge($userBadgeId)
    {
        $userBadge = UserBadge::findOrFail($userBadgeId);
        $user = $userBadge->user;
        $badge = $userBadge->badge;

        $badgeService = app(BadgeService::class);
        $result = $badgeService->removeBadge($user, $badge);

        if ($result) {
            $this->dispatch('swal:success', ['title' => 'Badge Rimosso!', 'text' => "Badge rimosso da {$user->getDisplayName()}!"]);
        } else {
            $this->dispatch('swal:error', ['title' => 'Errore', 'text' => 'Impossibile rimuovere il badge!']);
        }
    }

    public function render()
    {
        $query = UserBadge::with(['user', 'badge', 'awardedBy']);

        // Filter by badge
        if ($this->filterBadge) {
            $query->where('badge_id', $this->filterBadge);
        }

        // Filter by user (search)
        if ($this->filterUser) {
            $query->whereHas('user', function($q) {
                $q->where('name', 'like', '%' . $this->filterUser . '%')
                  ->orWhere('nickname', 'like', '%' . $this->filterUser . '%')
                  ->orWhere('email', 'like', '%' . $this->filterUser . '%');
            });
        }

        // Sort
        if ($this->sortBy === 'user') {
            $query->join('users', 'users.id', '=', 'user_badges.user_id')
                  ->orderBy('users.name', $this->sortDirection)
                  ->select('user_badges.*');
        } elseif ($this->sortBy === 'badge') {
            $query->join('badges', 'badges.id', '=', 'user_badges.badge_id')
                  ->orderBy('badges.name', $this->sortDirection)
                  ->select('user_badges.*');
        } else {
            $query->orderBy($this->sortBy, $this->sortDirection);
        }

        $userBadges = $query->paginate(20);
        $badges = Badge::active()->ordered()->get();

        return view('livewire.admin.gamification.user-badges', [
            'userBadges' => $userBadges,
            'badges' => $badges,
        ])->extends('layout.master')->section('main-content');
    }
}
