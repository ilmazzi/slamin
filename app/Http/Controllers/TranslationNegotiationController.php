<?php

namespace App\Http\Controllers;

use App\Models\GigApplication;
use App\Models\PoemTranslationNegotiation;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TranslationNegotiationController extends Controller
{
    /**
     * Mostra la chat di negoziazione per una candidatura
     */
    public function show(GigApplication $application)
    {
        // Verifica che l'utente abbia accesso alla negoziazione
        if (!in_array(Auth::id(), [$application->gig->user_id, $application->user_id])) {
            abort(403);
        }

        // Verifica che sia un gig di traduzione
        if ($application->gig->gig_type !== 'translation') {
            abort(404);
        }

        $application->load(['gig.poem', 'user', 'gig.user']);

        // Carica i messaggi della negoziazione
        $negotiations = $application->translationNegotiations()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        // Marca i messaggi come letti per l'utente corrente
        $application->translationNegotiations()
            ->where('user_id', '!=', Auth::id())
            ->update(['is_read' => true]);

        return view('translations.negotiation', compact('application', 'negotiations'));
    }

    /**
     * Invia un messaggio nella negoziazione
     */
    public function store(Request $request, GigApplication $application)
    {
        // Verifica che l'utente abbia accesso alla negoziazione
        if (!in_array(Auth::id(), [$application->gig->user_id, $application->user_id])) {
            abort(403);
        }

        // Verifica che sia un gig di traduzione
        if ($application->gig->gig_type !== 'translation') {
            abort(404);
        }

        $request->validate([
            'message_type' => 'required|in:proposal,accept,reject,counter,info',
            'message' => 'required|string|max:1000',
            'proposed_compensation' => 'nullable|numeric|min:0',
            'proposed_deadline' => 'nullable|date|after:today',
        ]);

        DB::beginTransaction();
        try {
            $negotiation = PoemTranslationNegotiation::create([
                'gig_application_id' => $application->id,
                'user_id' => Auth::id(),
                'message_type' => $request->message_type,
                'message' => $request->message,
                'proposed_compensation' => $request->proposed_compensation,
                'proposed_deadline' => $request->proposed_deadline,
                'is_read' => false,
            ]);

            // Crea notifica per l'altro utente
            $otherUserId = $application->gig->user_id === Auth::id()
                ? $application->user_id
                : $application->gig->user_id;

            $notificationType = $this->getNotificationType($request->message_type);
            $notificationMessage = $this->getNotificationMessage($request->message_type, $application->gig->poem->title);

            Notification::create([
                'user_id' => $otherUserId,
                'type' => $notificationType,
                'title' => 'Nuovo messaggio nella negoziazione',
                'message' => $notificationMessage,
                'action_url' => route('translations.negotiation.show', $application),
                'data' => [
                    'gig_application_id' => $application->id,
                    'negotiation_id' => $negotiation->id,
                    'gig_id' => $application->gig_id,
                    'poem_title' => $application->gig->poem->title,
                ],
                'is_read' => false,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Messaggio inviato con successo',
                'negotiation' => $negotiation->load('user')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'invio del messaggio'
            ], 500);
        }
    }

    /**
     * Accetta una proposta di traduzione
     */
    public function acceptProposal(GigApplication $application)
    {
        // Verifica che l'utente sia il proprietario del gig
        if ($application->gig->user_id !== Auth::id()) {
            abort(403);
        }

        // Verifica che sia un gig di traduzione
        if ($application->gig->gig_type !== 'translation') {
            abort(404);
        }

        DB::beginTransaction();
        try {
            // Accetta la candidatura
            $application->update([
                'status' => 'accepted',
                'accepted_at' => now(),
            ]);

            // Rifiuta tutte le altre candidature per questo gig
            $application->gig->applications()
                ->where('id', '!=', $application->id)
                ->update([
                    'status' => 'rejected',
                    'rejected_at' => now(),
                ]);

            // Chiudi il gig
            $application->gig->update([
                'is_closed' => true,
                'status' => 'completed',
            ]);

            // Crea notifica per il traduttore
            Notification::create([
                'user_id' => $application->user_id,
                'type' => 'translation_accepted',
                'title' => 'Candidatura accettata!',
                'message' => "La tua candidatura per tradurre '{$application->gig->poem->title}' è stata accettata!",
                'action_url' => route('translations.negotiation.show', $application),
                'data' => [
                    'gig_application_id' => $application->id,
                    'gig_id' => $application->gig_id,
                    'poem_title' => $application->gig->poem->title,
                ],
                'is_read' => false,
            ]);

            DB::commit();

            // Reindirizza al pagamento invece che alla negoziazione
            return redirect()->route('translations.payment.show', $application)
                ->with('success', 'Proposta accettata! Procedi con il pagamento per completare l\'ordine.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Errore durante l\'accettazione della proposta.']);
        }
    }

    /**
     * Rifiuta una proposta di traduzione
     */
    public function rejectProposal(GigApplication $application)
    {
        // Verifica che l'utente sia il proprietario del gig
        if ($application->gig->user_id !== Auth::id()) {
            abort(403);
        }

        // Verifica che sia un gig di traduzione
        if ($application->gig->gig_type !== 'translation') {
            abort(404);
        }

        DB::beginTransaction();
        try {
            // Rifiuta la candidatura
            $application->update([
                'status' => 'rejected',
                'rejected_at' => now(),
            ]);

            // Crea notifica per il traduttore
            Notification::create([
                'user_id' => $application->user_id,
                'type' => 'translation_rejected',
                'title' => 'Candidatura rifiutata',
                'message' => "La tua candidatura per tradurre '{$application->gig->poem->title}' è stata rifiutata.",
                'action_url' => route('gigs.my-applications'),
                'data' => [
                    'gig_application_id' => $application->id,
                    'gig_id' => $application->gig_id,
                    'poem_title' => $application->gig->poem->title,
                ],
                'is_read' => false,
            ]);

            DB::commit();

            return redirect()->route('translations.negotiation', $application)
                ->with('success', 'Proposta rifiutata.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Errore durante il rifiuto della proposta.']);
        }
    }

    /**
     * Ottiene il tipo di notifica basato sul tipo di messaggio
     */
    private function getNotificationType($messageType)
    {
        return match($messageType) {
            'proposal' => 'translation_proposal',
            'accept' => 'translation_accepted',
            'reject' => 'translation_rejected',
            'counter' => 'translation_counter',
            default => 'translation_message',
        };
    }

    /**
     * Ottiene il messaggio di notifica basato sul tipo
     */
    private function getNotificationMessage($messageType, $poemTitle)
    {
        return match($messageType) {
            'proposal' => "Nuova proposta per tradurre '{$poemTitle}'",
            'accept' => "Proposta accettata per '{$poemTitle}'",
            'reject' => "Proposta rifiutata per '{$poemTitle}'",
            'counter' => "Controproposta per '{$poemTitle}'",
            default => "Nuovo messaggio per '{$poemTitle}'",
        };
    }
}
