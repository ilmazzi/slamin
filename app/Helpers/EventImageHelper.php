<?php

namespace App\Helpers;

use App\Models\Event;

class EventImageHelper
{
    /**
     * Get the event image URL with fallback
     *
     * @param Event $event
     * @return string
     */
    public static function getEventImageUrl(Event $event): string
    {
        // Se l'evento ha un'immagine, usala
        if ($event->image_url && file_exists(public_path($event->image_url))) {
            return asset($event->image_url);
        }

        // Se l'evento ha un'immagine ma il path inizia con storage
        if ($event->image_url && str_starts_with($event->image_url, 'storage/')) {
            $storagePath = str_replace('storage/', '', $event->image_url);
            if (\Storage::disk('public')->exists($storagePath)) {
                return asset($event->image_url);
            }
        }

        // Altrimenti usa l'immagine di default (draghetto)
        return asset('assets/images/draghetto.png');
    }
}

