<?php

namespace App\Http\Controllers;

use App\Models\Gig;
use App\Models\GigApplication;
use App\Models\Poem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TranslationController extends Controller
{
    /**
     * Mostra tutti i gigs di traduzione disponibili
     */
    public function index(Request $request)
    {
        $query = Gig::translationGigs()
            ->with(['poem', 'user', 'applications'])
            ->open();

        // Filtri
        if ($request->filled('language')) {
            $query->whereJsonContains('target_languages', $request->language);
        }

        if ($request->filled('poem_id')) {
            $query->where('poem_id', $request->poem_id);
        }

        // Ordinamento
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'deadline':
                $query->orderBy('deadline', 'asc');
                break;
            case 'compensation':
                $query->orderBy('compensation', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $translationGigs = $query->paginate(12);

        return view('translations.index', compact('translationGigs'));
    }

    /**
     * Mostra un gig di traduzione specifico
     */
    public function show(Gig $gig)
    {
        // Verifica che sia un gig di traduzione
        if ($gig->gig_type !== 'translation') {
            abort(404);
        }

        $gig->load(['poem', 'user', 'applications.user']);

        return view('translations.show', compact('gig'));
    }

    /**
     * Mostra il form per creare un nuovo gig di traduzione
     */
    public function create(Request $request)
    {
        $poemId = $request->get('poem_id');
        $poem = null;

        if ($poemId) {
            $poem = Poem::findOrFail($poemId);
            // Verifica che l'utente sia il proprietario della poesia
            if ($poem->user_id !== Auth::id()) {
                abort(403);
            }
        }

        return view('translations.create', compact('poem'));
    }

    /**
     * Salva un nuovo gig di traduzione
     */
    public function store(Request $request)
    {
        $request->validate([
            'poem_id' => 'required|exists:poems,id',
            'target_languages' => 'required|array|min:1',
            'target_languages.*' => 'required|string|max:10',
            'translation_instructions' => 'nullable|string|max:1000',
            'compensation' => 'required|numeric|min:0',
            'deadline' => 'required|date|after:today',
        ]);

        // Verifica che l'utente sia il proprietario della poesia
        $poem = Poem::findOrFail($request->poem_id);
        if ($poem->user_id !== Auth::id()) {
            abort(403);
        }

        // Verifica che non esista già un gig di traduzione per questa poesia
        $existingGig = Gig::translationGigs()
            ->where('poem_id', $request->poem_id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingGig) {
            return back()->withErrors(['poem_id' => 'Esiste già un gig di traduzione per questa poesia.']);
        }

        DB::beginTransaction();
        try {
            $gig = Gig::create([
                'title' => "Traduzione: {$poem->title}",
                'description' => "Traduzione della poesia '{$poem->title}' in " . implode(', ', $request->target_languages),
                'requirements' => $request->translation_instructions ?? 'Traduzione accurata e fedele al testo originale.',
                'compensation' => $request->compensation,
                'deadline' => $request->deadline,
                'user_id' => Auth::id(),
                'category' => 'translation',
                'type' => 'translation',
                'gig_type' => 'translation',
                'poem_id' => $request->poem_id,
                'target_languages' => $request->target_languages,
                'translation_instructions' => $request->translation_instructions,
                'status' => 'active',
            ]);

            DB::commit();

            return redirect()->route('translations.show', $gig)
                ->with('success', 'Gig di traduzione creato con successo!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Errore durante la creazione del gig di traduzione.']);
        }
    }

    /**
     * Mostra i gigs di traduzione dell'utente
     */
    public function myTranslations()
    {
        $translationGigs = Gig::translationGigs()
            ->where('user_id', Auth::id())
            ->with(['poem', 'applications.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('translations.my-translations', compact('translationGigs'));
    }

    /**
     * Mostra le candidature dell'utente per traduzioni
     */
    public function myApplications()
    {
        $applications = GigApplication::where('user_id', Auth::id())
            ->whereHas('gig', function ($query) {
                $query->translationGigs();
            })
            ->with(['gig.poem', 'gig.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('translations.my-applications', compact('applications'));
    }
}
