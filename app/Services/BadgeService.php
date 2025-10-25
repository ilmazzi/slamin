<?php

namespace App\Services;

use App\Models\User;
use App\Models\Badge;
use App\Models\UserBadge;
use App\Models\UserPoints;
use App\Models\PointTransaction;
use App\Models\GamificationLevel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\BadgeEarnedNotification;

class BadgeService
{
    /**
     * Check and award badge for a specific action
     *
     * @param User $user
     * @param string $category
     * @param mixed $source
     * @return array Array of newly earned badges
     */
    public function checkAndAwardBadge(User $user, string $category, $source = null): array
    {
        $earnedBadges = [];

        try {
            // Get user's current count for this category
            $currentCount = $this->getUserCountForCategory($user, $category);

            // Get all active badges for this category that user hasn't earned yet
            $availableBadges = Badge::active()
                ->portal()
                ->where('category', $category)
                ->whereNotIn('id', $user->badges()->pluck('badges.id'))
                ->ordered()
                ->get();

            foreach ($availableBadges as $badge) {
                // Check if user meets the criteria
                if ($this->meetsChallengeCriteria($badge, $currentCount)) {
                    $earnedBadge = $this->awardBadge($user, $badge, $source);
                    if ($earnedBadge) {
                        $earnedBadges[] = $earnedBadge;
                    }
                }
            }

            // Update progress for next badges
            if (!empty($earnedBadges)) {
                $this->updateProgressForCategory($user, $category);
            }

        } catch (\Exception $e) {
            Log::error('Error checking and awarding badge', [
                'user_id' => $user->id,
                'category' => $category,
                'error' => $e->getMessage()
            ]);
        }

        return $earnedBadges;
    }

    /**
     * Manually award a badge to a user (admin action)
     *
     * @param User $user
     * @param Badge $badge
     * @param User $awardedBy
     * @param string|null $notes
     * @return UserBadge|null
     */
    public function manuallyAwardBadge(User $user, Badge $badge, User $awardedBy, ?string $notes = null): ?UserBadge
    {
        try {
            // Check if user already has this badge
            if ($user->badges()->where('badges.id', $badge->id)->exists()) {
                return null;
            }

            DB::beginTransaction();

            // Award the badge
            $userBadge = UserBadge::create([
                'user_id' => $user->id,
                'badge_id' => $badge->id,
                'earned_at' => now(),
                'metadata' => ['manually_awarded' => true],
                'awarded_by' => $awardedBy->id,
                'admin_notes' => $notes,
            ]);

            // Award points
            $this->awardPoints($user, $badge->points, 'badge_earned', $badge, 'Badge assegnato manualmente: ' . $badge->name);

            // Update user badges count
            $this->updateUserBadgesCount($user);

            // Recalculate level
            $this->calculateAndUpdateUserLevel($user);

            DB::commit();

            // Send notification
            $user->notify(new BadgeEarnedNotification($badge));

            // Emit Livewire event for full-screen notification
            $this->emitBadgeEarnedEvent($user, $badge);

            return $userBadge;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error manually awarding badge', [
                'user_id' => $user->id,
                'badge_id' => $badge->id,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Remove a badge from a user (admin action)
     *
     * @param User $user
     * @param Badge $badge
     * @return bool
     */
    public function removeBadge(User $user, Badge $badge): bool
    {
        try {
            DB::beginTransaction();

            $userBadge = UserBadge::where('user_id', $user->id)
                ->where('badge_id', $badge->id)
                ->first();

            if (!$userBadge) {
                return false;
            }

            // Remove the badge
            $userBadge->delete();

            // Deduct points
            $this->awardPoints($user, -$badge->points, 'badge_removed', $badge, 'Badge rimosso: ' . $badge->name);

            // Update user badges count
            $this->updateUserBadgesCount($user);

            // Recalculate level
            $this->calculateAndUpdateUserLevel($user);

            DB::commit();

            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error removing badge', [
                'user_id' => $user->id,
                'badge_id' => $badge->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Award a badge to a user
     *
     * @param User $user
     * @param Badge $badge
     * @param mixed $source
     * @return UserBadge|null
     */
    protected function awardBadge(User $user, Badge $badge, $source = null): ?UserBadge
    {
        try {
            DB::beginTransaction();

            $metadata = [];
            if ($source) {
                $metadata = [
                    'source_type' => get_class($source),
                    'source_id' => $source->id ?? null,
                ];
            }

            $userBadge = UserBadge::create([
                'user_id' => $user->id,
                'badge_id' => $badge->id,
                'earned_at' => now(),
                'metadata' => $metadata,
            ]);

            // Award points
            $this->awardPoints($user, $badge->points, 'badge_earned', $badge, 'Badge guadagnato: ' . $badge->name);

            // Update user badges count
            $this->updateUserBadgesCount($user);

            // Recalculate level
            $this->calculateAndUpdateUserLevel($user);

            DB::commit();

            // Send notification
            $user->notify(new BadgeEarnedNotification($badge));

            // Emit Livewire event for full-screen notification
            $this->emitBadgeEarnedEvent($user, $badge);

            Log::info('Badge awarded', [
                'user_id' => $user->id,
                'badge_id' => $badge->id,
                'badge_name' => $badge->name
            ]);

            return $userBadge;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error awarding badge', [
                'user_id' => $user->id,
                'badge_id' => $badge->id,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Award points to a user
     *
     * @param User $user
     * @param int $points
     * @param string $type
     * @param mixed $source
     * @param string $description
     * @return void
     */
    public function awardPoints(User $user, int $points, string $type, $source = null, string $description = ''): void
    {
        try {
            // Get or create user points record
            $userPoints = $user->userPoints()->firstOrCreate(
                ['user_id' => $user->id],
                ['total_points' => 0, 'portal_points' => 0, 'event_points' => 0, 'level' => 1]
            );

            // Update points
            $userPoints->total_points += $points;

            // Separate portal and event points
            if (in_array($type, ['event_win', 'event_participation'])) {
                $userPoints->event_points += $points;
            } else {
                $userPoints->portal_points += $points;
            }

            $userPoints->save();

            // Create transaction record
            PointTransaction::create([
                'user_id' => $user->id,
                'points' => $points,
                'type' => $type,
                'source_type' => $source ? get_class($source) : null,
                'source_id' => $source && isset($source->id) ? $source->id : null,
                'description' => $description,
            ]);

            Log::info('Points awarded', [
                'user_id' => $user->id,
                'points' => $points,
                'type' => $type
            ]);

        } catch (\Exception $e) {
            Log::error('Error awarding points', [
                'user_id' => $user->id,
                'points' => $points,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Calculate and update user level based on points and badges
     *
     * @param User $user
     * @return void
     */
    public function calculateAndUpdateUserLevel(User $user): void
    {
        $userPoints = $user->userPoints()->first();
        
        if (!$userPoints) {
            return;
        }

        $totalPoints = $userPoints->total_points;
        $badgesCount = $userPoints->badges_count;

        // Get all levels ordered by required points
        $levels = GamificationLevel::ordered()->get();

        $newLevel = 1;

        foreach ($levels as $level) {
            // Check if user meets both points and badges requirements
            if ($totalPoints >= $level->required_points && $badgesCount >= $level->required_badges) {
                $newLevel = $level->level;
            } else {
                break; // Stop when user doesn't meet requirements
            }
        }

        // Update user level if changed
        if ($userPoints->level !== $newLevel) {
            $oldLevel = $userPoints->level;
            $userPoints->level = $newLevel;
            $userPoints->save();

            Log::info('User level updated', [
                'user_id' => $user->id,
                'old_level' => $oldLevel,
                'new_level' => $newLevel
            ]);
        }
    }

    /**
     * Get user's current count for a category
     *
     * @param User $user
     * @param string $category
     * @return int
     */
    protected function getUserCountForCategory(User $user, string $category): int
    {
        return match($category) {
            'videos' => $user->videos()->count(),
            'articles' => $user->articles()->count(),
            'poems' => $user->poems()->count(),
            'photos' => $user->photos()->count(),
            'likes' => $user->likes()->count(),
            'comments' => $user->comments()->count(),
            'posts' => $user->forumPosts()->count() ?? 0,
            'event_participation' => $user->eventParticipations()->where('status', 'performed')->count(),
            default => 0,
        };
    }

    /**
     * Check if user meets badge criteria
     *
     * @param Badge $badge
     * @param int $currentCount
     * @return bool
     */
    protected function meetsChallengeCriteria(Badge $badge, int $currentCount): bool
    {
        return match($badge->criteria_type) {
            'count', 'milestone' => $currentCount >= $badge->criteria_value,
            'first_time' => $currentCount >= 1,
            default => false,
        };
    }

    /**
     * Update progress towards next badges in category
     *
     * @param User $user
     * @param string $category
     * @return void
     */
    protected function updateProgressForCategory(User $user, string $category): void
    {
        $currentCount = $this->getUserCountForCategory($user, $category);

        // Get next badge in this category
        $nextBadge = Badge::active()
            ->portal()
            ->where('category', $category)
            ->whereNotIn('id', $user->badges()->pluck('badges.id'))
            ->ordered()
            ->first();

        if (!$nextBadge) {
            return; // No more badges in this category
        }

        // Get user's latest badge in this category
        $latestUserBadge = $user->userBadges()
            ->whereHas('badge', function ($query) use ($category) {
                $query->where('category', $category);
            })
            ->latest('earned_at')
            ->first();

        if ($latestUserBadge) {
            // Calculate progress (0-100)
            $previousValue = $latestUserBadge->badge->criteria_value;
            $nextValue = $nextBadge->criteria_value;
            $range = $nextValue - $previousValue;
            $progress = $range > 0 ? min(100, (int) ((($currentCount - $previousValue) / $range) * 100)) : 100;

            $latestUserBadge->progress = max(0, $progress);
            $latestUserBadge->save();
        }
    }

    /**
     * Update user badges count cache
     *
     * @param User $user
     * @return void
     */
    protected function updateUserBadgesCount(User $user): void
    {
        $userPoints = $user->userPoints()->first();
        
        if ($userPoints) {
            $userPoints->badges_count = $user->badges()->count();
            $userPoints->save();
        }
    }

    /**
     * Get user progress for a category
     *
     * @param User $user
     * @param string $category
     * @return array
     */
    public function getUserProgress(User $user, string $category): array
    {
        $currentCount = $this->getUserCountForCategory($user, $category);

        // Get all badges in category (earned and not earned)
        $allBadges = Badge::active()
            ->portal()
            ->where('category', $category)
            ->ordered()
            ->get();

        $earnedBadges = $user->badges()->where('category', $category)->get();
        $earnedBadgeIds = $earnedBadges->pluck('id')->toArray();

        // Get next badge to unlock
        $nextBadge = $allBadges->first(function ($badge) use ($earnedBadgeIds) {
            return !in_array($badge->id, $earnedBadgeIds);
        });

        $progress = 0;
        if ($nextBadge) {
            $lastEarnedBadge = $earnedBadges->last();
            $previousValue = $lastEarnedBadge ? $lastEarnedBadge->criteria_value : 0;
            $nextValue = $nextBadge->criteria_value;
            $range = $nextValue - $previousValue;
            $progress = $range > 0 ? min(100, (int) ((($currentCount - $previousValue) / $range) * 100)) : 0;
        }

        return [
            'current_count' => $currentCount,
            'earned_badges' => $earnedBadges,
            'next_badge' => $nextBadge,
            'progress' => $progress,
            'all_badges' => $allBadges,
        ];
    }

    /**
     * Emit Livewire event for badge earned notification
     *
     * @param User $user
     * @param Badge $badge
     * @return void
     */
    protected function emitBadgeEarnedEvent(User $user, Badge $badge): void
    {
        try {
            // Get user's updated stats
            $userPoints = UserPoints::where('user_id', $user->id)->first();
            $totalPoints = $userPoints ? $userPoints->total_points : 0;
            $currentLevel = $userPoints ? $userPoints->level : 1;
            
            // Calculate previous level (before this badge)
            $previousTotalPoints = $totalPoints - $badge->points;
            
            // Get all levels to determine previous level
            $levels = GamificationLevel::where('required_points', '<=', $previousTotalPoints)
                ->orderBy('level', 'desc')
                ->first();
            
            $previousLevel = $levels ? $levels->level : 1;
            
            // Check if user leveled up with this badge
            $leveledUp = $currentLevel > $previousLevel;
            
            // Prepare badge data for event
            $badgeData = [
                'badge' => [
                    'id' => $badge->id,
                    'name' => $badge->name,
                    'description' => $badge->description,
                    'icon_url' => $badge->icon_url,
                    'points' => $badge->points,
                    'category' => $badge->category,
                ],
                'points' => $badge->points,
                'level' => $currentLevel,
                'previous_level' => $previousLevel,
                'leveled_up' => $leveledUp,
            ];
            
            // Store badge data in session for component to pick up
            // The component that triggered the badge (e.g., SocialLikeButton) will dispatch the event
            session()->put('badge_earned', $badgeData);
            
            Log::info('Badge earned event emitted', [
                'user_id' => $user->id,
                'badge_id' => $badge->id,
                'level' => $currentLevel,
                'leveled_up' => $leveledUp,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error emitting badge earned event', [
                'user_id' => $user->id,
                'badge_id' => $badge->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

