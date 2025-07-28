<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Wishlist;
use App\Services\LoggingService;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostra la wishlist dell'utente
     */
    public function index()
    {
        $user = auth()->user();
        $wishlistedEvents = $user->wishlistedEvents()
            ->where('start_datetime', '>=', now())
            ->orderBy('start_datetime', 'asc')
            ->paginate(12);

        return view('wishlist.index', compact('wishlistedEvents'));
    }

    /**
     * Aggiungi evento alla wishlist
     */
    public function add(Request $request, Event $event)
    {
        $user = auth()->user();

        // Verifica se l'evento è già nella wishlist
        if ($user->wishlistedEvents()->where('event_id', $event->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Evento già nella wishlist'
            ]);
        }

        // Aggiungi alla wishlist
        Wishlist::create([
            'user_id' => $user->id,
            'event_id' => $event->id
        ]);

        // Log dell'azione
        LoggingService::logUser('wishlist_add', [
            'event_id' => $event->id,
            'event_title' => $event->title
        ], 'Event', $event->id);

        return response()->json([
            'success' => true,
            'message' => 'Evento aggiunto alla wishlist',
            'in_wishlist' => true
        ]);
    }

    /**
     * Rimuovi evento dalla wishlist
     */
    public function remove(Request $request, Event $event)
    {
        $user = auth()->user();

        // Rimuovi dalla wishlist
        Wishlist::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->delete();

        // Log dell'azione
        LoggingService::logUser('wishlist_remove', [
            'event_id' => $event->id,
            'event_title' => $event->title
        ], 'Event', $event->id);

        return response()->json([
            'success' => true,
            'message' => 'Evento rimosso dalla wishlist',
            'in_wishlist' => false
        ]);
    }

    /**
     * Toggle wishlist (aggiungi/rimuovi)
     */
    public function toggle(Request $request, Event $event)
    {
        $user = auth()->user();
        $inWishlist = $user->wishlistedEvents()->where('event_id', $event->id)->exists();

        if ($inWishlist) {
            return $this->remove($request, $event);
        } else {
            return $this->add($request, $event);
        }
    }

    /**
     * Verifica se un evento è nella wishlist
     */
    public function check(Request $request, Event $event)
    {
        $user = auth()->user();
        $inWishlist = $user->wishlistedEvents()->where('event_id', $event->id)->exists();

        return response()->json([
            'in_wishlist' => $inWishlist
        ]);
    }

    /**
     * Ottieni tutti gli eventi nella wishlist per il calendario
     */
    public function calendar()
    {
        $user = auth()->user();
        $wishlistedEvents = $user->wishlistedEvents()
            ->where('start_datetime', '>=', now()->subMonths(1))
            ->where('start_datetime', '<=', now()->addMonths(6))
            ->get();

        $events = $wishlistedEvents->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start_datetime->format('Y-m-d H:i:s'),
                'end' => $event->end_datetime->format('Y-m-d H:i:s'),
                'url' => route('events.show', $event),
                'backgroundColor' => '#ff6b6b', // Colore rosso per wishlist
                'borderColor' => '#ff5252',
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'type' => 'wishlist',
                    'venue' => $event->venue_name,
                    'category' => $event->category
                ]
            ];
        });

        return response()->json($events);
    }
}
