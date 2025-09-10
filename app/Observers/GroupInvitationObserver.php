<?php

namespace App\Observers;

use App\Models\GroupInvitation;
use App\Services\ActivityService;

class GroupInvitationObserver
{
    /**
     * Handle the GroupInvitation "created" event.
     */
    public function created(GroupInvitation $groupInvitation): void
    {
        // Log activity for the inviter
        if ($groupInvitation->invitedBy) {
            ActivityService::log(
                $groupInvitation->invitedBy,
                'invite',
                'App\\Models\\Group',
                $groupInvitation->group_id,
                'invited',
                null,
                [
                    'title' => $groupInvitation->group->name ?? 'Gruppo',
                    'url' => route('groups.show', $groupInvitation->group),
                    'invited_user' => $groupInvitation->user->getDisplayName() ?? 'Utente',
                ],
                request()
            );
        }
    }

    /**
     * Handle the GroupInvitation "updated" event.
     */
    public function updated(GroupInvitation $groupInvitation): void
    {
        // Log activity for status changes
        if ($groupInvitation->wasChanged('status')) {
            $user = $groupInvitation->user;
            $action = match($groupInvitation->status) {
                'accepted' => 'accepted',
                'declined' => 'declined',
                default => 'updated'
            };

            if ($user) {
                ActivityService::log(
                    $user,
                    $action === 'accepted' ? 'accept' : 'decline',
                    'App\\Models\\Group',
                    $groupInvitation->group_id,
                    $action,
                    null,
                    [
                        'title' => $groupInvitation->group->name ?? 'Gruppo',
                        'url' => route('groups.show', $groupInvitation->group),
                    ],
                    request()
                );
            }
        }
    }

    /**
     * Handle the GroupInvitation "deleted" event.
     */
    public function deleted(GroupInvitation $groupInvitation): void
    {
        //
    }

    /**
     * Handle the GroupInvitation "restored" event.
     */
    public function restored(GroupInvitation $groupInvitation): void
    {
        //
    }

    /**
     * Handle the GroupInvitation "force deleted" event.
     */
    public function forceDeleted(GroupInvitation $groupInvitation): void
    {
        //
    }
}
