<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Mostra il form per segnalare un contenuto
     */
    public function showReportForm(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'id' => 'required|integer'
        ]);

        $type = $request->type;
        $id = $request->id;

        // Ottieni il contenuto da segnalare
        $content = $this->getContent($type, $id);

        if (!$content) {
            return redirect()->back()->with('error', 'Contenuto non trovato');
        }

        // Verifica se l'utente ha già segnalato questo contenuto
        if ($content->isReportedByUser()) {
            return redirect()->back()->with('info', 'Hai già segnalato questo contenuto');
        }

        return view('reports.create', compact('content', 'type', 'id'));
    }

    /**
     * Salva la segnalazione
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'id' => 'required|integer',
            'reason' => 'required|string|in:spam,inappropriate,violence,harassment,copyright,misinformation,other',
            'description' => 'nullable|string|max:1000'
        ]);

        $type = $request->type;
        $id = $request->id;

        // Ottieni il contenuto da segnalare
        $content = $this->getContent($type, $id);

        if (!$content) {
            return response()->json(['success' => false, 'message' => 'Contenuto non trovato']);
        }

        // Verifica se l'utente ha già segnalato questo contenuto
        if ($content->isReportedByUser()) {
            return response()->json(['success' => false, 'message' => 'Hai già segnalato questo contenuto']);
        }

        // Crea la segnalazione
        $report = Report::create([
            'user_id' => Auth::id(),
            'reportable_type' => $this->getModelClass($type),
            'reportable_id' => $id,
            'reason' => $request->reason,
            'description' => $request->description,
            'status' => Report::STATUS_PENDING
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Segnalazione inviata con successo. Grazie per il tuo contributo!'
            ]);
        }

        return redirect()->back()->with('success', 'Segnalazione inviata con successo. Grazie per il tuo contributo!');
    }

    /**
     * Rimuovi la segnalazione dell'utente
     */
    public function remove(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'id' => 'required|integer'
        ]);

        $type = $request->type;
        $id = $request->id;

        // Ottieni il contenuto
        $content = $this->getContent($type, $id);

        if (!$content) {
            return response()->json(['success' => false, 'message' => 'Contenuto non trovato']);
        }

        // Ottieni la segnalazione dell'utente
        $report = $content->getUserReport();

        if (!$report) {
            return response()->json(['success' => false, 'message' => 'Nessuna segnalazione trovata']);
        }

        // Rimuovi la segnalazione
        $report->delete();

        return response()->json([
            'success' => true,
            'message' => 'Segnalazione rimossa con successo'
        ]);
    }

    /**
     * Ottiene il contenuto in base al tipo e ID
     */
    private function getContent(string $type, int $id)
    {
        $modelClass = $this->getModelClass($type);

        if (!$modelClass) {
            return null;
        }

        return $modelClass::find($id);
    }

    /**
     * Ottiene la classe del modello in base al tipo
     */
    private function getModelClass(string $type): ?string
    {
        $models = [
            'video' => \App\Models\Video::class,
            'poem' => \App\Models\Poem::class,
            'event' => \App\Models\Event::class,
            'photo' => \App\Models\Photo::class,
            'carousel' => \App\Models\Carousel::class,
            'video_comment' => \App\Models\VideoComment::class,
            'poem_comment' => \App\Models\PoemComment::class,
        ];

        return $models[$type] ?? null;
    }

    /**
     * Ottiene le ragioni disponibili per le segnalazioni
     */
    public static function getReasons(): array
    {
        return [
            Report::REASON_SPAM => 'Spam',
            Report::REASON_INAPPROPRIATE => 'Contenuto inappropriato',
            Report::REASON_VIOLENCE => 'Violenza',
            Report::REASON_HARASSMENT => 'Molestie',
            Report::REASON_COPYRIGHT => 'Violazione copyright',
            Report::REASON_MISINFORMATION => 'Disinformazione',
            Report::REASON_OTHER => 'Altro',
        ];
    }
}
