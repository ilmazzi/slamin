<?php

namespace App\Http\Controllers;

use App\Models\Gig;
use App\Models\GigApplication;
use App\Models\Event;
use App\Models\Group;
use App\Models\GigPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class GigController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $showOrganizerSection = false;
        $userEvents = collect();

        // Se l'utente è autenticato e non è audience, mostra la sezione organizzatore
        if ($user && !$user->hasRole('audience')) {
            $showOrganizerSection = true;

            // Ottieni eventi dell'utente (organizzatore o partecipante accettato)
            $userEvents = Event::with('organizer')
                ->where('organizer_id', $user->id)
                ->orWhereHas('invitations', function($q) use ($user) {
                    $q->where('invited_user_id', $user->id)->where('status', 'accepted');
                })
                ->orWhereHas('requests', function($q) use ($user) {
                    $q->where('user_id', $user->id)->where('status', 'accepted');
                })
                ->orderBy('start_datetime', 'desc')
                ->get();
        }

        $query = Gig::with(['user', 'event', 'group'])
            ->withCount(['applications', 'pendingApplications', 'acceptedApplications']);

        // Filtri
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('requirements', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        // Filtri per evento
        if ($request->filled('event')) {
            $query->where('event_id', $request->event);
        }

        // Filtri per gruppo
        if ($request->filled('group')) {
            $query->where('group_id', $request->group);
        }

        if ($request->filled('location')) {
            $query->byLocation($request->location);
        }

        if ($request->boolean('remote')) {
            $query->remote();
        }

        if ($request->boolean('urgent')) {
            $query->urgent();
        }

        if ($request->boolean('featured')) {
            $query->featured();
        }

        // Ordinamento
        $sort = $request->get('sort', 'recent');
        switch ($sort) {
            case 'deadline':
                $query->orderBy('deadline', 'asc');
                break;
            case 'urgent':
                $query->orderBy('is_urgent', 'desc')->orderBy('created_at', 'desc');
                break;
            case 'featured':
                $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
                break;
            case 'compensation':
                $query->orderBy('compensation', 'desc');
                break;
            case 'applications':
                $query->orderBy('application_count', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $query->whereHas('event');

        $gigs = $query->paginate(12);

        // Statistiche
        $stats = [
            'total_gigs' => Gig::count(),
            'open_gigs_count' => Gig::open()->count(),
            'urgent_gigs_count' => Gig::urgent()->count(),
        ];

        return view('gigs.index', compact('gigs', 'stats', 'showOrganizerSection', 'userEvents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = Auth::user();

        // Solo utenti non audience possono creare gigs
        if ($user->hasRole('audience')) {
            return redirect()->route('gigs.index')->with('error', __('gigs.messages.audience_not_allowed'));
        }

        $events = Event::where('organizer_id', $user->id)
            ->orWhereHas('invitations', function($q) use ($user) {
                $q->where('invited_user_id', $user->id)->where('status', 'accepted');
            })
            ->orWhereHas('requests', function($q) use ($user) {
                $q->where('user_id', $user->id)->where('status', 'accepted');
            })
            ->get();

        $groups = Group::where('created_by', $user->id)
            ->orWhereHas('members', function($q) use ($user) {
                $q->where('user_id', $user->id)->whereIn('role', ['admin', 'moderator']);
            })
            ->get();

                // Se è specificato un evento, selezionalo e pre-compila i dati
        $selectedEvent = null;
        $prefilledData = [];

        if ($request->filled('event')) {
            $selectedEvent = Event::find($request->event);
            if ($selectedEvent) {
                $prefilledData = [
                    'event_id' => $selectedEvent->id,
                    'category' => $selectedEvent->category,
                    'location' => $selectedEvent->city . ', ' . $selectedEvent->country,
                ];
            }
        }

        return view('gigs.create', compact('selectedEvent', 'prefilledData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->hasRole('audience')) {
            return redirect()->route('gigs.index')->with('error', __('gigs.messages.audience_not_allowed'));
        }

        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'type' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'language' => 'nullable|string|max:255',
            'cachet_amount' => 'nullable|numeric|min:0',
            'cachet_currency' => 'nullable|string|max:3',
            'travel_max' => 'nullable|numeric|min:0',
            'accommodation_details' => 'nullable|string',
        ]);

        // Ottieni l'evento per pre-compilare alcuni campi
        $event = Event::find($validated['event_id']);

        // Prepara i dati per il gig
        $gigData = [
            'user_id' => $user->id,
            'event_id' => $validated['event_id'],
            'title' => $event->title . ' - ' . $validated['type'],
            'description' => 'Posizione di ' . $validated['type'] . ' per l\'evento ' . $event->title,
            'category' => $event->category,
            'type' => $validated['type'],
            'language' => $validated['language'] ?? 'italian',
            'location' => $event->city . ', ' . $event->country,
            'compensation' => $validated['cachet_amount'] ?
                $validated['cachet_amount'] . ' ' . ($validated['cachet_currency'] ?? 'EUR') :
                'Da definire',
            'requirements' => $validated['accommodation_details'] ?
                'Vitto e alloggio: ' . $validated['accommodation_details'] :
                null,
            'deadline' => $event->registration_deadline ?? now()->addDays(30),
            'max_applications' => $validated['quantity'],
            'is_remote' => false,
            'is_urgent' => false,
            'is_featured' => false,
        ];

        $gig = Gig::create($gigData);

        return redirect()->route('gigs.show', $gig)
            ->with('success', __('gigs.messages.gig_created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Gig $gig)
    {
        $user = Auth::user();

        if ($user && $user->hasRole('audience')) {
            return redirect()->route('gigs.index')->with('error', __('gigs.messages.audience_not_allowed'));
        }

        if (!$gig->canBeViewedBy($user)) {
            abort(403);
        }

        $gig->load(['user', 'event', 'group', 'applications.user']);

        $userApplication = null;
        if ($user) {
            $userApplication = $gig->applications()->where('user_id', $user->id)->first();
        }

        return view('gigs.show', compact('gig', 'userApplication'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gig $gig)
    {
        $user = Auth::user();

        if (!$gig->canBeEditedBy($user)) {
            abort(403);
        }

        $events = Event::where('organizer_id', $user->id)
            ->orWhereHas('invitations', function($q) use ($user) {
                $q->where('invited_user_id', $user->id)->where('status', 'accepted');
            })
            ->orWhereHas('requests', function($q) use ($user) {
                $q->where('user_id', $user->id)->where('status', 'accepted');
            })
            ->get();

        $groups = Group::where('created_by', $user->id)
            ->orWhereHas('members', function($q) use ($user) {
                $q->where('user_id', $user->id)->whereIn('role', ['admin', 'moderator']);
            })
            ->get();

        $positions = GigPosition::getPositionsForSelect();

        return view('gigs.edit', compact('gig', 'events', 'groups', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gig $gig)
    {
        $user = Auth::user();

        if (!$gig->canBeEditedBy($user)) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'compensation' => 'nullable|string|max:255',
            'deadline' => 'required|date',
            'event_id' => 'nullable|exists:events,id',
            'group_id' => 'nullable|exists:groups,id',
            'category' => 'required|string',
            'type' => 'required|string',
            'language' => 'required|string',
            'location' => 'nullable|string|max:255',
            'is_remote' => 'boolean',
            'is_urgent' => 'boolean',
            'is_featured' => 'boolean',
            'max_applications' => 'integer|min:1|max:100',
            'allow_group_admin_edit' => 'boolean',
        ]);

        $validated['is_remote'] = $request->boolean('is_remote');
        $validated['is_urgent'] = $request->boolean('is_urgent');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['allow_group_admin_edit'] = $request->boolean('allow_group_admin_edit');

        $gig->update($validated);

        return redirect()->route('gigs.show', $gig)
            ->with('success', __('gigs.messages.gig_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gig $gig)
    {
        $user = Auth::user();

        if (!$gig->canBeEditedBy($user)) {
            abort(403);
        }

        $gig->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('gigs.messages.gig_deleted')
            ]);
        }

        return redirect()->route('gigs.index')
            ->with('success', __('gigs.messages.gig_deleted'));
    }

    /**
     * Mostra i gigs dell'utente
     */
    public function myGigs()
    {
        $user = Auth::user();

        if ($user->hasRole('audience')) {
            return redirect()->route('gigs.index')->with('error', __('gigs.messages.audience_not_allowed'));
        }

        $gigs = Gig::where('user_id', $user->id)
            ->with(['event', 'group'])
            ->withCount(['applications', 'pendingApplications', 'acceptedApplications'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $stats = [
            'my_gigs_count' => Gig::where('user_id', $user->id)->count(),
            'open_gigs_count' => Gig::where('user_id', $user->id)->open()->count(),
            'urgent_gigs_count' => Gig::where('user_id', $user->id)->urgent()->count(),
        ];

        return view('gigs.my-gigs', compact('gigs', 'stats'));
    }

    /**
     * Mostra le candidature dell'utente
     */
    public function myApplications()
    {
        $user = Auth::user();

        if ($user->hasRole('audience')) {
            return redirect()->route('gigs.index')->with('error', __('gigs.messages.audience_not_allowed'));
        }

        $applications = GigApplication::where('user_id', $user->id)
            ->with(['gig.user', 'gig.event'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $stats = [
            'my_applications_count' => GigApplication::where('user_id', $user->id)->count(),
            'pending_applications_count' => GigApplication::where('user_id', $user->id)->pending()->count(),
            'accepted_applications_count' => GigApplication::where('user_id', $user->id)->accepted()->count(),
        ];

        return view('gigs.my-applications', compact('applications', 'stats'));
    }

    /**
     * Gestisci le candidature di un gig
     */
    public function manageApplications(Gig $gig)
    {
        $user = Auth::user();

        if (!$gig->canBeEditedBy($user)) {
            abort(403);
        }

        $applications = $gig->applications()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('gigs.manage-applications', compact('gig', 'applications'));
    }

    /**
     * Invia candidatura
     */
    public function apply(Request $request, Gig $gig)
    {
        $user = Auth::user();

        if ($user->hasRole('audience')) {
            return response()->json(['error' => __('gigs.messages.audience_not_allowed')], 403);
        }

        if (!$gig->can_apply) {
            return response()->json(['error' => __('gigs.applications.already_applied')], 400);
        }

        // Verifica se l'utente ha già candidato
        $existingApplication = $gig->applications()->where('user_id', $user->id)->first();
        if ($existingApplication) {
            return response()->json(['error' => __('gigs.applications.already_applied')], 400);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'experience' => 'nullable|string|max:1000',
            'portfolio' => 'nullable|string|max:500',
            'availability' => 'nullable|string|max:500',
            'compensation_expectation' => 'nullable|string|max:500',
        ]);

        $validated['user_id'] = $user->id;
        $validated['gig_id'] = $gig->id;

        $application = GigApplication::create($validated);

        // Invia notifica al proprietario del gig
        \App\Models\Notification::createGigApplication($application);

        return response()->json([
            'success' => true,
            'message' => __('gigs.applications.application_sent'),
            'application' => $application
        ]);
    }

    /**
     * Accetta candidatura
     */
    public function acceptApplication(GigApplication $application)
    {
        $user = Auth::user();
        $gig = $application->gig;

        if (!$gig->canBeEditedBy($user)) {
            abort(403);
        }

        if (!$application->canBeAccepted()) {
            return response()->json(['error' => 'Candidatura non può essere accettata'], 400);
        }

        $application->accept();

        // Invia notifica all'utente candidato
        \App\Models\Notification::createGigApplicationResponse($application, 'accepted');

        // Verifica se tutte le posizioni sono state coperte
        if ($gig->areAllPositionsFilled()) {
            // Chiudi automaticamente il gig se tutte le posizioni sono coperte
            $gig->update(['is_closed' => true]);

            // Invia notifica a tutti i candidati pendenti che il gig è stato chiuso
            \App\Models\Notification::createGigClosed($gig);
        }

        return response()->json([
            'success' => true,
            'message' => __('gigs.messages.application_accepted'),
            'gig_closed' => $gig->is_closed
        ]);
    }

    /**
     * Rifiuta candidatura
     */
    public function rejectApplication(GigApplication $application)
    {
        $user = Auth::user();
        $gig = $application->gig;

        if (!$gig->canBeEditedBy($user)) {
            abort(403);
        }

        if (!$application->canBeRejected()) {
            return response()->json(['error' => 'Candidatura non può essere rifiutata'], 400);
        }

        $application->reject();

        // Invia notifica all'utente candidato
        \App\Models\Notification::createGigApplicationResponse($application, 'rejected');

        return response()->json([
            'success' => true,
            'message' => __('gigs.messages.application_rejected')
        ]);
    }

    /**
     * Ritira candidatura
     */
    public function withdrawApplication(GigApplication $application)
    {
        $user = Auth::user();

        if ($application->user_id !== $user->id) {
            abort(403);
        }

        if (!$application->canBeWithdrawn()) {
            return response()->json(['error' => 'Candidatura non può essere ritirata'], 400);
        }

        $application->withdraw();

        // Invia notifica al proprietario del gig
        \App\Models\Notification::createGigApplicationWithdrawn($application);

        return response()->json([
            'success' => true,
            'message' => __('gigs.applications.application_withdrawn')
        ]);
    }

    /**
     * Chiudi gig
     */
    public function close(Gig $gig)
    {
        $user = Auth::user();

        if (!$gig->canBeEditedBy($user)) {
            abort(403);
        }

        $gig->close();

        // Invia notifica a tutti i candidati pendenti
        \App\Models\Notification::createGigClosed($gig);

        return response()->json([
            'success' => true,
            'message' => __('gigs.messages.gig_closed')
        ]);
    }

    /**
     * Riapri gig
     */
    public function reopen(Gig $gig)
    {
        $user = Auth::user();

        if (!$gig->canBeEditedBy($user)) {
            abort(403);
        }

        $gig->reopen();

        // Invia notifica a tutti i candidati precedenti
        \App\Models\Notification::createGigReopened($gig);

        return response()->json([
            'success' => true,
            'message' => __('gigs.messages.gig_reopened')
        ]);
    }

    /**
     * Invia messaggio globale
     */
    public function sendGlobalMessage(Request $request, Gig $gig)
    {
        $user = Auth::user();

        if (!$gig->canBeEditedBy($user)) {
            abort(403);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // Ottieni tutti gli utenti non-audience
        $users = \App\Models\User::whereDoesntHave('roles', function($query) {
            $query->where('name', 'audience');
        })->get();

        $sentCount = 0;
        foreach ($users as $recipient) {
            \App\Models\Notification::createGigGlobalMessage($gig, $validated['message'], $recipient);
            $sentCount++;
        }

        return response()->json([
            'success' => true,
            'message' => __('gigs.messages.global_message_sent', ['count' => $sentCount]),
            'sent_count' => $sentCount
        ]);
    }

    /**
     * Condividi il gig inviando notifiche a tutti gli utenti non-audience
     */
    public function share(Gig $gig)
    {
        $user = Auth::user();

        if (!$gig->canBeEditedBy($user)) {
            abort(403);
        }

        $recipientsCount = $gig->share();

        return response()->json([
            'success' => true,
            'message' => __('gigs.messages.gig_shared', ['count' => $recipientsCount]),
            'recipients_count' => $recipientsCount
        ]);
    }
}
