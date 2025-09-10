<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class ActivityService
{
    /**
     * Log an activity for a user
     */
    public static function log(
        User $user,
        string $type,
        string $subjectType,
        ?int $subjectId,
        string $action,
        ?string $description = null,
        ?array $metadata = null,
        ?Request $request = null
    ): Activity {
        $activityData = [
            'user_id' => $user->id,
            'type' => $type,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'action' => $action,
            'description' => $description,
            'metadata' => $metadata,
        ];

        if ($request) {
            $activityData['ip_address'] = $request->ip();
            $activityData['user_agent'] = $request->userAgent();
        }

        return Activity::create($activityData);
    }

    /**
     * Log a view activity
     */
    public static function logView(User $user, Model $subject, ?Request $request = null): Activity
    {
        $metadata = [
            'title' => $subject->title ?? $subject->name ?? 'Elemento',
            'url' => self::getSubjectUrl($subject),
        ];

        // Add thumbnail if available
        if (method_exists($subject, 'getThumbnailUrlAttribute') && $subject->getThumbnailUrlAttribute()) {
            $metadata['thumbnail'] = $subject->getThumbnailUrlAttribute();
        } elseif (method_exists($subject, 'getThumbnailUrl') && $subject->getThumbnailUrl()) {
            $metadata['thumbnail'] = $subject->getThumbnailUrl();
        } elseif (isset($subject->thumbnail_url) && $subject->thumbnail_url) {
            $metadata['thumbnail'] = $subject->thumbnail_url;
        } elseif (isset($subject->thumbnail) && $subject->thumbnail) {
            $metadata['thumbnail'] = $subject->thumbnail;
        } elseif (isset($subject->thumbnail_path) && $subject->thumbnail_path) {
            $metadata['thumbnail'] = $subject->thumbnail_path;
        }

        return self::log(
            $user,
            'view',
            $subject->getMorphClass(),
            $subject->id,
            'viewed',
            null,
            $metadata,
            $request
        );
    }

    /**
     * Log an upload activity
     */
    public static function logUpload(User $user, Model $subject, ?Request $request = null): Activity
    {
        return self::log(
            $user,
            'upload',
            $subject->getMorphClass(),
            $subject->id,
            'uploaded',
            null,
            [
                'title' => $subject->title ?? $subject->name ?? 'Elemento',
                'url' => self::getSubjectUrl($subject),
            ],
            $request
        );
    }

    /**
     * Log a comment activity
     */
    public static function logComment(User $user, Model $subject, ?Request $request = null): Activity
    {
        return self::log(
            $user,
            'comment',
            $subject->getMorphClass(),
            $subject->id,
            'commented_on',
            null,
            [
                'title' => $subject->title ?? $subject->name ?? 'Elemento',
                'url' => self::getSubjectUrl($subject),
            ],
            $request
        );
    }

    /**
     * Log a like activity
     */
    public static function logLike(User $user, Model $subject, ?Request $request = null): Activity
    {
        return self::log(
            $user,
            'like',
            $subject->getMorphClass(),
            $subject->id,
            'liked',
            null,
            [
                'title' => $subject->title ?? $subject->name ?? 'Elemento',
                'url' => self::getSubjectUrl($subject),
            ],
            $request
        );
    }

    /**
     * Log a create activity
     */
    public static function logCreate(User $user, Model $subject, ?Request $request = null): Activity
    {
        $metadata = [
            'title' => $subject->title ?? $subject->name ?? 'Elemento',
            'url' => self::getSubjectUrl($subject),
        ];

        // Add thumbnail if available
        if (method_exists($subject, 'getThumbnailUrlAttribute') && $subject->getThumbnailUrlAttribute()) {
            $metadata['thumbnail'] = $subject->getThumbnailUrlAttribute();
        } elseif (method_exists($subject, 'getThumbnailUrl') && $subject->getThumbnailUrl()) {
            $metadata['thumbnail'] = $subject->getThumbnailUrl();
        } elseif (isset($subject->thumbnail_url) && $subject->thumbnail_url) {
            $metadata['thumbnail'] = $subject->thumbnail_url;
        } elseif (isset($subject->thumbnail) && $subject->thumbnail) {
            $metadata['thumbnail'] = $subject->thumbnail;
        } elseif (isset($subject->thumbnail_path) && $subject->thumbnail_path) {
            $metadata['thumbnail'] = $subject->thumbnail_path;
        }

        return self::log(
            $user,
            'create',
            $subject->getMorphClass(),
            $subject->id,
            'created',
            null,
            $metadata,
            $request
        );
    }

    /**
     * Log an update activity
     */
    public static function logUpdate(User $user, Model $subject, ?Request $request = null): Activity
    {
        return self::log(
            $user,
            'update',
            $subject->getMorphClass(),
            $subject->id,
            'updated',
            null,
            [
                'title' => $subject->title ?? $subject->name ?? 'Elemento',
                'url' => self::getSubjectUrl($subject),
            ],
            $request
        );
    }

    /**
     * Log a delete activity
     */
    public static function logDelete(User $user, string $subjectType, int $subjectId, string $title, ?Request $request = null): Activity
    {
        return self::log(
            $user,
            'delete',
            $subjectType,
            $subjectId,
            'deleted',
            null,
            [
                'title' => $title,
            ],
            $request
        );
    }

    /**
     * Log a join activity
     */
    public static function logJoin(User $user, Model $subject, ?Request $request = null): Activity
    {
        return self::log(
            $user,
            'join',
            $subject->getMorphClass(),
            $subject->id,
            'joined',
            null,
            [
                'title' => $subject->title ?? $subject->name ?? 'Elemento',
                'url' => self::getSubjectUrl($subject),
            ],
            $request
        );
    }

    /**
     * Log a leave activity
     */
    public static function logLeave(User $user, Model $subject, ?Request $request = null): Activity
    {
        return self::log(
            $user,
            'leave',
            $subject->getMorphClass(),
            $subject->id,
            'left',
            null,
            [
                'title' => $subject->title ?? $subject->name ?? 'Elemento',
                'url' => self::getSubjectUrl($subject),
            ],
            $request
        );
    }

    /**
     * Log an accept activity
     */
    public static function logAccept(User $user, Model $subject, ?Request $request = null): Activity
    {
        return self::log(
            $user,
            'accept',
            $subject->getMorphClass(),
            $subject->id,
            'accepted',
            null,
            [
                'title' => $subject->title ?? $subject->name ?? 'Elemento',
                'url' => self::getSubjectUrl($subject),
            ],
            $request
        );
    }

    /**
     * Log a decline activity
     */
    public static function logDecline(User $user, Model $subject, ?Request $request = null): Activity
    {
        return self::log(
            $user,
            'decline',
            $subject->getMorphClass(),
            $subject->id,
            'declined',
            null,
            [
                'title' => $subject->title ?? $subject->name ?? 'Elemento',
                'url' => self::getSubjectUrl($subject),
            ],
            $request
        );
    }

    /**
     * Log a follow activity
     */
    public static function logFollow(User $user, User $followedUser, ?Request $request = null): Activity
    {
        return self::log(
            $user,
            'follow',
            'user',
            $followedUser->id,
            'followed',
            null,
            [
                'title' => $followedUser->getDisplayName(),
                'url' => route('user.show', $followedUser),
            ],
            $request
        );
    }

    /**
     * Log an unfollow activity
     */
    public static function logUnfollow(User $user, User $unfollowedUser, ?Request $request = null): Activity
    {
        return self::log(
            $user,
            'unfollow',
            'user',
            $unfollowedUser->id,
            'unfollowed',
            null,
            [
                'title' => $unfollowedUser->getDisplayName(),
                'url' => route('user.show', $unfollowedUser),
            ],
            $request
        );
    }

    /**
     * Get URL for a subject model
     */
    private static function getSubjectUrl(Model $subject): ?string
    {
        $morphClass = $subject->getMorphClass();

        return match($morphClass) {
            'App\\Models\\Video' => route('videos.show', $subject),
            'App\\Models\\Poem' => route('poems.show', $subject),
            'App\\Models\\Event' => route('events.show', $subject),
            'App\\Models\\Group' => route('groups.show', $subject),
            'App\\Models\\Article' => route('articles.show', $subject),
            'App\\Models\\Photo' => route('photos.show', $subject),
            'App\\Models\\Gig' => route('gigs.show', $subject),
            'App\\Models\\Comment' => self::getCommentUrl($subject),
            'App\\Models\\User' => route('user.show', $subject),
            default => null,
        };
    }

    /**
     * Get URL for a comment
     */
    private static function getCommentUrl(Model $comment): ?string
    {
        // Try to get the parent model URL
        if ($comment->commentable) {
            return self::getSubjectUrl($comment->commentable);
        }

        return null;
    }

    /**
     * Get recent activities for a user
     */
    public static function getRecentActivities(User $user, int $limit = 20): \Illuminate\Database\Eloquent\Collection
    {
        return $user->activities()
            ->with('subject')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get activities by type for a user
     */
    public static function getActivitiesByType(User $user, string $type, int $limit = 20): \Illuminate\Database\Eloquent\Collection
    {
        return $user->activities()
            ->ofType($type)
            ->with('subject')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
