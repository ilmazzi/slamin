<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\UserBadge;
use App\Models\Badge;

class BadgeDisplayWallGrid extends Component
{
    public $user;
    public $earnedBadges = [];
    public $lockedBadges = [];
    public $selectedBadge = null;
    public $selectedUserBadgeId = null;
    public $showInProfile = false;
    public $profileOrder = null;
    public $showInSidebar = false;
    public $sidebarOrder = null;

    public function mount($user)
    {
        $this->user = $user;
        $this->loadBadges();
    }

    public function loadBadges()
    {
        // Get earned badges
        $this->earnedBadges = UserBadge::where('user_id', $this->user->id)
            ->with('badge')
            ->orderBy('earned_at', 'desc')
            ->get();

        // Get ALL locked badges (not just 12)
        $earnedBadgeIds = $this->earnedBadges->pluck('badge_id')->toArray();
        
        $this->lockedBadges = Badge::active()
            ->whereNotIn('id', $earnedBadgeIds)
            ->orderBy('category')
            ->orderBy('criteria_value')
            ->get(); // Show ALL locked badges
    }

    public function selectBadge($userBadgeId)
    {
        $userBadge = UserBadge::with('badge')->find($userBadgeId);
        
        if ($userBadge && $userBadge->user_id === $this->user->id) {
            $this->selectedUserBadgeId = $userBadgeId;
            $this->selectedBadge = $userBadge;
            $this->showInProfile = (bool) $userBadge->show_in_profile;
            $this->profileOrder = $userBadge->profile_order;
            $this->showInSidebar = (bool) $userBadge->show_in_sidebar;
            $this->sidebarOrder = $userBadge->sidebar_order;
        }
    }
    
    public function toggleProfile()
    {
        if (!$this->selectedUserBadgeId) return;
        
        $userBadge = UserBadge::with('badge')->find($this->selectedUserBadgeId);
        
        if ($userBadge && $userBadge->user_id === $this->user->id) {
            \Log::info('Toggle Profile BEFORE', [
                'badge_id' => $userBadge->id,
                'current_show' => $userBadge->show_in_profile,
                'current_order' => $userBadge->profile_order,
                'this_show' => $this->showInProfile
            ]);
            
            // If enabling, find next available position (1-3)
            if (!$this->showInProfile) {
                $existingProfileBadges = UserBadge::where('user_id', $this->user->id)
                    ->where('show_in_profile', true)
                    ->count();
                
                if ($existingProfileBadges >= 3) {
                    session()->flash('error', 'Puoi mostrare massimo 3 badge nel profilo!');
                    return;
                }
                
                $this->profileOrder = $existingProfileBadges + 1;
                $userBadge->update([
                    'show_in_profile' => true,
                    'profile_order' => $this->profileOrder
                ]);
                $this->showInProfile = true;
            } else {
                // Disabling - set to 0 instead of null
                $userBadge->update([
                    'show_in_profile' => false,
                    'profile_order' => 0
                ]);
                $this->showInProfile = false;
                $this->profileOrder = 0;
            }
            
            // Reload fresh data
            $userBadge = UserBadge::with('badge')->find($this->selectedUserBadgeId);
            $this->selectedBadge = $userBadge;
            $this->showInProfile = (bool) $userBadge->show_in_profile;
            $this->profileOrder = $userBadge->profile_order;
            
            \Log::info('Toggle Profile AFTER', [
                'badge_id' => $userBadge->id,
                'new_show' => $userBadge->show_in_profile,
                'new_order' => $userBadge->profile_order,
                'this_show' => $this->showInProfile,
                'this_order' => $this->profileOrder
            ]);
            
            $this->loadBadges();
            session()->flash('success', 'Badge aggiornato!');
        }
    }
    
    public function toggleSidebar()
    {
        if (!$this->selectedUserBadgeId) return;
        
        $userBadge = UserBadge::find($this->selectedUserBadgeId);
        
        if ($userBadge && $userBadge->user_id === $this->user->id) {
            // If enabling, check if there's already one (max 1)
            if (!$this->showInSidebar) {
                $existingSidebarBadges = UserBadge::where('user_id', $this->user->id)
                    ->where('show_in_sidebar', true)
                    ->count();
                
                if ($existingSidebarBadges >= 1) {
                    session()->flash('error', 'Puoi mostrare solo 1 badge nella sidebar!');
                    return;
                }
                
                $this->sidebarOrder = 1;
                $userBadge->update([
                    'show_in_sidebar' => true,
                    'sidebar_order' => $this->sidebarOrder
                ]);
                $this->showInSidebar = true;
            } else {
                // Disabling - set to 0 instead of null
                $userBadge->update([
                    'show_in_sidebar' => false,
                    'sidebar_order' => 0
                ]);
                $this->showInSidebar = false;
                $this->sidebarOrder = 0;
            }
            
            $this->loadBadges();
            
            // Reload the selected badge to update the modal state
            $userBadge->refresh();
            $this->selectedBadge = $userBadge;
            
            session()->flash('success', 'Badge aggiornato!');
        }
    }

    public function closeDetails()
    {
        $this->selectedBadge = null;
    }

    public function render()
    {
        return view('livewire.profile.badge-display-wall-grid');
    }
}
