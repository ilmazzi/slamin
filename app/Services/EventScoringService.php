<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventRanking;
use App\Models\EventParticipant;
use App\Models\Badge;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventScoringService
{
    /**
     * Calculate rankings for an event
     */
    public function calculateRankings(Event $event): void
    {
        DB::beginTransaction();

        try {
            // Delete existing rankings
            $event->rankings()->delete();

            // Get all participants with scores
            $participants = $event->participants()
                ->with('scores')
                ->whereIn('status', ['performed', 'confirmed'])
                ->get();

            $rankings = [];

            foreach ($participants as $participant) {
                $scores = $participant->scores;
                
                if ($scores->count() === 0) {
                    continue; // Skip participants without scores
                }

                // Calculate total score based on scoring type
                $totalScore = $this->calculateTotalScore($scores, $event);
                $roundScores = $scores->pluck('score', 'round')->toArray();

                $rankings[] = [
                    'participant' => $participant,
                    'total_score' => $totalScore,
                    'round_scores' => $roundScores,
                ];
            }

            // Sort by total score (descending)
            usort($rankings, function ($a, $b) {
                return $b['total_score'] <=> $a['total_score'];
            });

            // Assign positions and save rankings
            foreach ($rankings as $position => $rankingData) {
                $badgeId = $this->getBadgeForPosition($position + 1);

                EventRanking::create([
                    'event_id' => $event->id,
                    'participant_id' => $rankingData['participant']->id,
                    'position' => $position + 1,
                    'total_score' => $rankingData['total_score'],
                    'round_scores' => $rankingData['round_scores'],
                    'badge_id' => $badgeId,
                    'badge_awarded' => false,
                ]);
            }

            DB::commit();

            Log::info('Event rankings calculated', [
                'event_id' => $event->id,
                'participants_ranked' => count($rankings)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error calculating rankings', [
                'event_id' => $event->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Award badges to winners
     */
    public function awardBadgesToWinners(Event $event): int
    {
        $badgesAwarded = 0;
        $badgeService = app(BadgeService::class);

        $rankings = $event->rankings()
            ->with(['participant.user', 'badge'])
            ->where('badge_awarded', false)
            ->whereNotNull('badge_id')
            ->get();

        foreach ($rankings as $ranking) {
            $participant = $ranking->participant;
            
            // Only award to registered users
            if (!$participant->user) {
                continue;
            }

            if ($ranking->badge && !$participant->user->badges()->where('badges.id', $ranking->badge->id)->exists()) {
                $badgeService->manuallyAwardBadge(
                    $participant->user,
                    $ranking->badge,
                    auth()->user(),
                    "Vinto {$ranking->position}° posto all'evento: {$event->title}"
                );

                $badgesAwarded++;
            }

            // Mark as awarded
            $ranking->badge_awarded = true;
            $ranking->save();

            // Award participation badge
            $this->awardParticipationBadge($participant->user, $event);
        }

        return $badgesAwarded;
    }

    /**
     * Calculate total score based on scoring type
     */
    protected function calculateTotalScore($scores, Event $event): float
    {
        $round = $event->rounds()->first();
        $scoringType = $round ? $round->scoring_type : 'average';

        return match($scoringType) {
            'sum' => $scores->sum('score'),
            'best_of' => $scores->max('score'),
            'average' => $scores->avg('score'),
            default => $scores->avg('score'),
        };
    }

    /**
     * Get badge ID for a position
     */
    protected function getBadgeForPosition(int $position): ?int
    {
        $badgeName = match($position) {
            1 => 'Campione - Oro',
            2 => 'Finalista - Argento',
            3 => 'Podio - Bronzo',
            default => null,
        };

        if (!$badgeName) {
            return null;
        }

        $badge = Badge::where('name', $badgeName)
            ->where('type', 'event')
            ->first();

        return $badge?->id;
    }

    /**
     * Award participation badge
     */
    protected function awardParticipationBadge($user, Event $event): void
    {
        if (!$user) {
            return;
        }

        $badgeService = app(BadgeService::class);
        $badgeService->checkAndAwardBadge($user, 'event_participation', $event);
    }
}

