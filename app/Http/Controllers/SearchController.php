<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poem;
use App\Models\Event;
use App\Models\Video;
use App\Models\Gig;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SearchController extends Controller
{
    /**
     * Mostra la pagina di ricerca globale
     */
    public function index(Request $request)
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'all');

        $results = [];
        $totalResults = 0;

        if (!empty($query)) {
            $searchResults = $this->performGlobalSearch($query, $type);
            $results = $searchResults['results'];
            $totalResults = $searchResults['total'];
        }

        return view('search.index', compact('query', 'type', 'results', 'totalResults'));
    }

    /**
     * API endpoint per ricerca AJAX
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $type = $request->get('type', 'all');
        $limit = $request->get('limit', 10);

        if (empty($query) || strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'results' => [],
                'total' => 0,
                'message' => 'Inserisci almeno 2 caratteri per la ricerca'
            ]);
        }

        $searchResults = $this->performGlobalSearch($query, $type, $limit);

        return response()->json([
            'success' => true,
            'results' => $searchResults['results'],
            'total' => $searchResults['total'],
            'query' => $query,
            'type' => $type
        ]);
    }

    /**
     * Esegue la ricerca globale
     */
    private function performGlobalSearch($query, $type = 'all', $limit = 10)
    {
        $results = [];
        $total = 0;
        $user = Auth::user();

        // Ricerca poesie
        if ($type === 'all' || $type === 'poems') {
            $poemsQuery = Poem::with(['user'])
                ->published()
                ->where(function($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('content', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%")
                      ->orWhereJsonContains('tags', $query)
                      ->orWhereHas('user', function($userQ) use ($query) {
                          $userQ->where('name', 'like', "%{$query}%");
                      });
                });

            // Filtri di autorizzazione per poesie
            if ($user) {
                // Utente autenticato: può vedere poesie pubbliche + proprie (anche bozze)
                $poemsQuery->where(function($q) use ($user) {
                    $q->where('is_public', true)
                      ->orWhere('user_id', $user->id);
                });

                // Filtri di moderazione: solo contenuti approvati o propri
                $poemsQuery->where(function($q) use ($user) {
                    $q->where('moderation_status', 'approved')
                      ->orWhere('user_id', $user->id);
                });

                // Admin e moderatori possono vedere tutto (semplificato per evitare errori)
                // if ($user->hasAnyRole(['admin', 'moderator'])) {
                //     $poemsQuery = Poem::with(['user'])
                //         ->where(function($q) use ($query) {
                //             $q->where('title', 'like', "%{$query}%")
                //               ->orWhere('content', 'like', "%{$query}%")
                //               ->orWhere('description', 'like', "%{$query}%")
                //               ->orWhereJsonContains('tags', $query)
                //               ->orWhereHas('user', function($userQ) use ($query) {
                //                   $userQ->where('name', 'like', "%{$query}%");
                //               });
                //         });
                // }
            } else {
                // Utente non autenticato: solo poesie pubbliche e approvate
                $poemsQuery->where('is_public', true)
                          ->where('moderation_status', 'approved');
            }

            $poems = $poemsQuery
                ->orderBy('published_at', 'desc')
                ->limit($type === 'poems' ? $limit : 3)
                ->get();

            if ($poems->count() > 0) {
                $results['poems'] = [
                    'data' => $poems,
                    'count' => $poems->count(),
                    'total' => $poemsQuery->count()
                ];
                $total += $results['poems']['count'];
            }
        }

        // Ricerca eventi
        if ($type === 'all' || $type === 'events') {
            $eventsQuery = Event::with(['organizer'])
                ->where(function($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%")
                      ->orWhere('venue_name', 'like', "%{$query}%")
                      ->orWhere('city', 'like', "%{$query}%")
                      ->orWhereJsonContains('tags', $query)
                      ->orWhereHas('organizer', function($userQ) use ($query) {
                          $userQ->where('name', 'like', "%{$query}%");
                      });
                });

            // Filtri di autorizzazione per eventi
            if ($user) {
                // Utente autenticato: può vedere eventi pubblici + propri (anche privati)
                $eventsQuery->where(function($q) use ($user) {
                    $q->where('is_public', true)
                      ->orWhere('organizer_id', $user->id)
                      ->orWhere('venue_owner_id', $user->id);
                });
            } else {
                // Utente non autenticato: solo eventi pubblici
                $eventsQuery->where('is_public', true);
            }

            $events = $eventsQuery
                ->orderBy('start_datetime', 'desc')
                ->limit($type === 'events' ? $limit : 3)
                ->get();

            if ($events->count() > 0) {
                $results['events'] = [
                    'data' => $events,
                    'count' => $events->count(),
                    'total' => $eventsQuery->count()
                ];
                $total += $results['events']['count'];
            }
        }

        // Ricerca video
        if ($type === 'all' || $type === 'videos') {
            $videosQuery = Video::with(['user'])
                ->where(function($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%")
                      ->orWhereJsonContains('tags', $query)
                      ->orWhereHas('user', function($userQ) use ($query) {
                          $userQ->where('name', 'like', "%{$query}%");
                      });
                });

            // Filtri di autorizzazione per video
            if ($user) {
                // Utente autenticato: può vedere video pubblici + propri
                $videosQuery->where(function($q) use ($user) {
                    $q->where('is_public', true)
                      ->orWhere('user_id', $user->id);
                });
            } else {
                // Utente non autenticato: solo video pubblici
                $videosQuery->where('is_public', true);
            }

            $videos = $videosQuery
                ->orderBy('created_at', 'desc')
                ->limit($type === 'videos' ? $limit : 3)
                ->get();

            if ($videos->count() > 0) {
                $results['videos'] = [
                    'data' => $videos,
                    'count' => $videos->count(),
                    'total' => $videosQuery->count()
                ];
                $total += $results['videos']['count'];
            }
        }

        // Ricerca gig
        if ($type === 'all' || $type === 'gigs') {
            $gigsQuery = Gig::with(['user', 'event'])
                ->where('is_closed', false)
                ->where(function($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%")
                      ->orWhere('location', 'like', "%{$query}%")
                      ->orWhereHas('user', function($userQ) use ($query) {
                          $userQ->where('name', 'like', "%{$query}%");
                      });
                });

            // Filtri di autorizzazione per gig
            if ($user) {
                // Utente autenticato: può vedere gig aperti + propri (anche chiusi)
                $gigsQuery->where(function($q) use ($user) {
                    $q->where('is_closed', false)
                      ->orWhere('user_id', $user->id);
                });
            } else {
                // Utente non autenticato: solo gig aperti
                $gigsQuery->where('is_closed', false);
            }

            $gigs = $gigsQuery
                ->orderBy('created_at', 'desc')
                ->limit($type === 'gigs' ? $limit : 3)
                ->get();

            if ($gigs->count() > 0) {
                $results['gigs'] = [
                    'data' => $gigs,
                    'count' => $gigs->count(),
                    'total' => $gigsQuery->count()
                ];
                $total += $results['gigs']['count'];
            }
        }

        // Ricerca utenti (solo se autenticati)
        if ($user && ($type === 'all' || $type === 'users')) {
            $usersQuery = User::where('name', 'like', "%{$query}%")
                ->where('id', '!=', $user->id); // Escludi l'utente corrente

            // Filtri di autorizzazione per utenti
            // Per ora mostriamo tutti gli utenti (il campo is_public non esiste nella tabella users)
            // TODO: Implementare sistema di privacy profili se necessario

            $users = $usersQuery
                ->orderBy('name', 'asc')
                ->limit($type === 'users' ? $limit : 3)
                ->get();

            if ($users->count() > 0) {
                $results['users'] = [
                    'data' => $users,
                    'count' => $users->count(),
                    'total' => User::where('name', 'like', "%{$query}%")
                        ->where('id', '!=', Auth::id())
                        ->count()
                ];
                $total += $results['users']['count'];
            }
        }

        return [
            'results' => $results,
            'total' => $total
        ];
    }

    /**
     * Verifica se l'utente può visualizzare un contenuto specifico
     */
    private function canUserViewContent($user, $contentType, $content)
    {
        if (!$user) {
            // Utente non autenticato: solo contenuti pubblici
            return $content->is_public ?? true;
        }

        // Utente autenticato: controlli specifici per tipo
        switch ($contentType) {
            case 'poem':
                return $content->is_public || $content->user_id === $user->id;

            case 'event':
                return $content->is_public ||
                       $content->organizer_id === $user->id ||
                       $content->venue_owner_id === $user->id;

            case 'video':
                return $content->is_public || $content->user_id === $user->id;

            case 'gig':
                return !$content->is_closed || $content->user_id === $user->id;

            case 'user':
                return $content->is_public ?? true;

            default:
                return true;
        }
    }

    /**
     * Applica filtri di autorizzazione a una query
     */
    private function applyAuthorizationFilters($query, $user, $contentType, $ownerField = 'user_id')
    {
        if (!$user) {
            // Utente non autenticato: solo contenuti pubblici
            return $query->where('is_public', true);
        }

        // Utente autenticato: contenuti pubblici + propri
        return $query->where(function($q) use ($user, $ownerField) {
            $q->where('is_public', true)
              ->orWhere($ownerField, $user->id);
        });
    }
}
