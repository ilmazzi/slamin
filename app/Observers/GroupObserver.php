<?php

namespace App\Observers;

use App\Models\Group;
use App\Models\GroupMember;

class GroupObserver
{
    /**
     * Handle the Group "created" event.
     */
    public function created(Group $group): void
    {
        // Aggiungi automaticamente il creatore come admin del gruppo
        if ($group->created_by && !$group->members()->where('user_id', $group->created_by)->exists()) {
            GroupMember::create([
                'group_id' => $group->id,
                'user_id' => $group->created_by,
                'role' => 'admin',
                'joined_at' => $group->created_at,
            ]);
        }
    }

    /**
     * Handle the Group "updated" event.
     */
    public function updated(Group $group): void
    {
        //
    }

    /**
     * Handle the Group "deleted" event.
     */
    public function deleted(Group $group): void
    {
        // Rimuovi tutti i membri del gruppo quando viene eliminato
        $group->members()->delete();
    }

    /**
     * Handle the Group "restored" event.
     */
    public function restored(Group $group): void
    {
        //
    }

    /**
     * Handle the Group "force deleted" event.
     */
    public function forceDeleted(Group $group): void
    {
        // Rimuovi tutti i membri del gruppo quando viene eliminato definitivamente
        $group->members()->forceDelete();
    }
} 