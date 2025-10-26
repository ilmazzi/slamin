<?php

namespace App\Helpers;

use App\Models\Poem;

class PoemImageHelper
{
    /**
     * Get the poem thumbnail URL with fallback
     *
     * @param Poem $poem
     * @return string
     */
    public static function getPoemImageUrl(Poem $poem): string
    {
        // Se la poesia ha un thumbnail_path (storage)
        if ($poem->thumbnail_path && \Storage::disk('public')->exists($poem->thumbnail_path)) {
            return asset('storage/' . $poem->thumbnail_path);
        }

        // Se ha un thumbnail (public path)
        if ($poem->thumbnail && file_exists(public_path($poem->thumbnail))) {
            return asset($poem->thumbnail);
        }

        // Fallback: placeholder configurabile dall'admin
        return PlaceholderHelper::getPoemPlaceholderUrl();
    }
}

