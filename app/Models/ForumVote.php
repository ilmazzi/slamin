<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ForumVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'voteable_type',
        'voteable_id',
        'vote_type',
    ];

    /**
     * User relationship
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Voteable relationship (polymorphic - ForumPost or ForumComment)
     */
    public function voteable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Handle vote and update counters
     */
    public static function handleVote(User $user, Model $voteable, string $voteType): array
    {
        $existingVote = static::where('user_id', $user->id)
            ->where('voteable_type', get_class($voteable))
            ->where('voteable_id', $voteable->id)
            ->first();

        if ($existingVote) {
            if ($existingVote->vote_type === $voteType) {
                // Remove vote (un-upvote or un-downvote)
                $existingVote->delete();
                
                if ($voteType === 'upvote') {
                    $voteable->decrement('upvotes');
                } else {
                    $voteable->decrement('downvotes');
                }

                $action = 'removed';
            } else {
                // Change vote (upvote to downvote or vice versa)
                $existingVote->update(['vote_type' => $voteType]);
                
                if ($voteType === 'upvote') {
                    $voteable->increment('upvotes');
                    $voteable->decrement('downvotes');
                } else {
                    $voteable->decrement('upvotes');
                    $voteable->increment('downvotes');
                }

                $action = 'changed';
            }
        } else {
            // New vote
            static::create([
                'user_id' => $user->id,
                'voteable_type' => get_class($voteable),
                'voteable_id' => $voteable->id,
                'vote_type' => $voteType,
            ]);

            if ($voteType === 'upvote') {
                $voteable->increment('upvotes');
            } else {
                $voteable->increment('downvotes');
            }

            $action = 'added';
        }

        // Update score
        $voteable->refresh();
        $voteable->updateScore();

        return [
            'action' => $action,
            'upvotes' => $voteable->upvotes,
            'downvotes' => $voteable->downvotes,
            'score' => $voteable->score,
        ];
    }
}
