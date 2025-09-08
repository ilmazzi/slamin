<?php

namespace App\Http\Controllers;

use App\Models\Gig;
use App\Models\GigApplication;
use App\Models\PoemTranslation;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PoemTranslationController extends Controller
{
    /**
     * Mostra il form per creare una traduzione
     */
    public function create(GigApplication $application)
    {
        // Verifica che l'utente sia il traduttore accettato
        if ($application->user_id !== Auth::id() || $application->status !== 'accepted') {
            abort(403);
        }

        // Verifica che sia un gig di traduzione
        if ($application->gig->gig_type !== 'translation') {
            abort(404);
        }

        $application->load(['gig.poem', 'gig.user']);

        return view('translations.create-translation', compact('application'));
    }

    /**
     * Salva una nuova traduzione
     */
    public function store(Request $request, GigApplication $application)
    {
        // Verifica che l'utente sia il traduttore accettato
        if ($application->user_id !== Auth::id() || $application->status !== 'accepted') {
            abort(403);
        }

        // Verifica che sia un gig di traduzione
        if ($application->gig->gig_type !== 'translation') {
            abort(404);
        }

        $request->validate([
            'language' => 'required|string|max:10',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'translator_notes' => 'nullable|string|max:1000',
        ]);

        // Verifica che la lingua sia tra quelle richieste
        $targetLanguages = $application->gig->target_languages ?? [];
        if (!in_array($request->language, $targetLanguages)) {
            return back()->withErrors(['language' => 'La lingua selezionata non è tra quelle richieste per questo gig.']);
        }

        // Verifica che non esista già una traduzione per questa lingua
        $existingTranslation = PoemTranslation::where('gig_id', $application->gig_id)
            ->where('language', $request->language)
            ->where('translator_id', Auth::id())
            ->first();

        if ($existingTranslation) {
            return back()->withErrors(['language' => 'Esiste già una traduzione per questa lingua.']);
        }

        DB::beginTransaction();
        try {
            $translation = PoemTranslation::create([
                'gig_id' => $application->gig_id,
                'poem_id' => $application->gig->poem_id,
                'translator_id' => Auth::id(),
                'language' => $request->language,
                'title' => $request->title,
                'content' => $request->content,
                'translator_notes' => $request->translator_notes,
                'status' => 'draft',
                'final_compensation' => $application->gig->compensation,
            ]);

            // Crea notifica per l'autore della poesia
            Notification::create([
                'user_id' => $application->gig->user_id,
                'type' => 'translation_submitted',
                'title' => 'Traduzione completata!',
                'message' => "Il traduttore ha completato la traduzione di '{$application->gig->poem->title}' in {$request->language}.",
                'data' => [
                    'translation_id' => $translation->id,
                    'gig_id' => $application->gig_id,
                    'poem_title' => $application->gig->poem->title,
                    'language' => $request->language,
                ],
                'is_read' => false,
            ]);

            DB::commit();

            return redirect()->route('translations.show', $application->gig)
                ->with('success', 'Traduzione salvata con successo!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Errore durante il salvataggio della traduzione.']);
        }
    }

    /**
     * Mostra una traduzione specifica
     */
    public function show(PoemTranslation $translation)
    {
        $translation->load(['poem', 'translator', 'gig']);

        // Verifica che l'utente abbia accesso alla traduzione
        if (!in_array(Auth::id(), [$translation->poem->user_id, $translation->translator_id])) {
            abort(403);
        }

        return view('translations.show-translation', compact('translation'));
    }

    /**
     * Mostra il form per modificare una traduzione
     */
    public function edit(PoemTranslation $translation)
    {
        // Verifica che l'utente sia il traduttore
        if ($translation->translator_id !== Auth::id()) {
            abort(403);
        }

        // Verifica che la traduzione sia modificabile
        if (!in_array($translation->status, ['draft', 'rejected'])) {
            abort(403, 'Questa traduzione non può essere modificata.');
        }

        $translation->load(['poem', 'gig']);

        return view('translations.edit-translation', compact('translation'));
    }

    /**
     * Aggiorna una traduzione
     */
    public function update(Request $request, PoemTranslation $translation)
    {
        // Verifica che l'utente sia il traduttore
        if ($translation->translator_id !== Auth::id()) {
            abort(403);
        }

        // Verifica che la traduzione sia modificabile
        if (!in_array($translation->status, ['draft', 'rejected'])) {
            abort(403, 'Questa traduzione non può essere modificata.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'translator_notes' => 'nullable|string|max:1000',
        ]);

        $translation->update([
            'title' => $request->title,
            'content' => $request->content,
            'translator_notes' => $request->translator_notes,
            'status' => 'draft', // Torna in bozza dopo la modifica
        ]);

        return redirect()->route('translations.show-translation', $translation)
            ->with('success', 'Traduzione aggiornata con successo!');
    }

    /**
     * Invia una traduzione per l'approvazione
     */
    public function submit(PoemTranslation $translation)
    {
        // Verifica che l'utente sia il traduttore
        if ($translation->translator_id !== Auth::id()) {
            abort(403);
        }

        // Verifica che la traduzione sia in bozza
        if ($translation->status !== 'draft') {
            abort(403, 'Questa traduzione non può essere inviata.');
        }

        DB::beginTransaction();
        try {
            $translation->update(['status' => 'submitted']);

            // Crea notifica per l'autore della poesia
            Notification::create([
                'user_id' => $translation->poem->user_id,
                'type' => 'translation_submitted',
                'title' => 'Traduzione inviata per approvazione',
                'message' => "Il traduttore ha inviato la traduzione di '{$translation->poem->title}' in {$translation->language} per l'approvazione.",
                'data' => [
                    'translation_id' => $translation->id,
                    'gig_id' => $translation->gig_id,
                    'poem_title' => $translation->poem->title,
                    'language' => $translation->language,
                ],
                'is_read' => false,
            ]);

            DB::commit();

            return redirect()->route('translations.show-translation', $translation)
                ->with('success', 'Traduzione inviata per l\'approvazione!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Errore durante l\'invio della traduzione.']);
        }
    }

    /**
     * Approva una traduzione
     */
    public function approve(PoemTranslation $translation)
    {
        // Verifica che l'utente sia l'autore della poesia
        if ($translation->poem->user_id !== Auth::id()) {
            abort(403);
        }

        // Verifica che la traduzione sia in attesa di approvazione
        if ($translation->status !== 'submitted') {
            abort(403, 'Questa traduzione non può essere approvata.');
        }

        DB::beginTransaction();
        try {
            $translation->update([
                'status' => 'approved',
                'completed_at' => now(),
            ]);

            // Crea notifica per il traduttore
            Notification::create([
                'user_id' => $translation->translator_id,
                'type' => 'translation_approved',
                'title' => 'Traduzione approvata!',
                'message' => "La tua traduzione di '{$translation->poem->title}' in {$translation->language} è stata approvata!",
                'data' => [
                    'translation_id' => $translation->id,
                    'gig_id' => $translation->gig_id,
                    'poem_title' => $translation->poem->title,
                    'language' => $translation->language,
                ],
                'is_read' => false,
            ]);

            DB::commit();

            return redirect()->route('translations.show-translation', $translation)
                ->with('success', 'Traduzione approvata con successo!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Errore durante l\'approvazione della traduzione.']);
        }
    }

    /**
     * Rifiuta una traduzione
     */
    public function reject(Request $request, PoemTranslation $translation)
    {
        // Verifica che l'utente sia l'autore della poesia
        if ($translation->poem->user_id !== Auth::id()) {
            abort(403);
        }

        // Verifica che la traduzione sia in attesa di approvazione
        if ($translation->status !== 'submitted') {
            abort(403, 'Questa traduzione non può essere rifiutata.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $translation->update([
                'status' => 'rejected',
                'translator_notes' => $translation->translator_notes . "\n\nMotivo del rifiuto: " . $request->rejection_reason,
            ]);

            // Crea notifica per il traduttore
            Notification::create([
                'user_id' => $translation->translator_id,
                'type' => 'translation_rejected',
                'title' => 'Traduzione rifiutata',
                'message' => "La tua traduzione di '{$translation->poem->title}' in {$translation->language} è stata rifiutata. Controlla i commenti per i dettagli.",
                'data' => [
                    'translation_id' => $translation->id,
                    'gig_id' => $translation->gig_id,
                    'poem_title' => $translation->poem->title,
                    'language' => $translation->language,
                    'rejection_reason' => $request->rejection_reason,
                ],
                'is_read' => false,
            ]);

            DB::commit();

            return redirect()->route('translations.show-translation', $translation)
                ->with('success', 'Traduzione rifiutata. Il traduttore può modificarla e reinviarla.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Errore durante il rifiuto della traduzione.']);
        }
    }

    /**
     * Mostra le traduzioni dell'utente
     */
    public function myTranslations()
    {
        $translations = PoemTranslation::where('translator_id', Auth::id())
            ->with(['poem', 'gig'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('translations.my-translations-list', compact('translations'));
    }
}
