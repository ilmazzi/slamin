<?php

namespace App\Traits\Chat;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\Chat\Action;

/**
 * Trait Actionable
 */
trait Actionable
{
    /**
     * Actions - that were performed on this model
     */
    public function actions(): MorphMany
    {
        return $this->morphMany(Action::class, 'actionable', 'actionable_type', 'actionable_id', 'id');
    }
}
