<?php

namespace App\Livewire\Admin\Gamification;

use Livewire\Component;
use App\Models\UserPoints;
use App\Models\Badge;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LeaderboardsDashboard extends Component
{
    public $topByPoints;
    public $topByLevel;
    public $topByBadges;
    public $recentBadges;
    public $stats;

    public function mount()
    {
        $this->loadLeaderboards();
    }

    public function loadLeaderboards()
    {
        // Top users by points
        $this->topByPoints = UserPoints::with('user')
            ->orderByDesc('total_points')
            ->limit(10)
            ->get();

        // Top users by level
        $this->topByLevel = UserPoints::with('user')
            ->orderByDesc('level')
            ->orderByDesc('total_points')
            ->limit(10)
            ->get();

        // Top users by badges count
        $this->topByBadges = UserPoints::with('user')
            ->orderByDesc('badges_count')
            ->orderByDesc('total_points')
            ->limit(10)
            ->get();

        // Recent badges earned
        $this->recentBadges = \App\Models\UserBadge::with(['user', 'badge'])
            ->latest('earned_at')
            ->limit(10)
            ->get();

        // Overall stats
        $this->stats = [
            'total_badges_awarded' => \App\Models\UserBadge::count(),
            'total_points_awarded' => UserPoints::sum('total_points'),
            'total_active_users' => UserPoints::where('total_points', '>', 0)->count(),
            'avg_points_per_user' => UserPoints::where('total_points', '>', 0)->avg('total_points'),
        ];
    }

    public function render()
    {
        return view('livewire.admin.gamification.leaderboards-dashboard')
            ->extends('layout.master')
            ->section('main-content');
    }
}
