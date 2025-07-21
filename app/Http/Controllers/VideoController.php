<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostra la pagina di riproduzione del video e incrementa le visualizzazioni
     */
    public function show(Video $video)
    {
        // Incrementa le visualizzazioni solo se l'utente non è il proprietario
        if (Auth::id() !== $video->user_id) {
            $video->increment('view_count');
        }

        return view('videos.show', compact('video'));
    }

    /**
     * Incrementa le visualizzazioni via AJAX
     */
    public function incrementViews(Request $request, Video $video)
    {
        // Debug: salva in un file
        file_put_contents(storage_path('views_debug.txt'), '=== INCREMENT VIEWS ===' . "\n", FILE_APPEND);
        file_put_contents(storage_path('views_debug.txt'), 'Video ID: ' . $video->id . "\n", FILE_APPEND);
        file_put_contents(storage_path('views_debug.txt'), 'User ID: ' . (auth()->id() ?? 'NULL') . "\n", FILE_APPEND);
        file_put_contents(storage_path('views_debug.txt'), 'Video User ID: ' . $video->user_id . "\n", FILE_APPEND);
        file_put_contents(storage_path('views_debug.txt'), 'Current views: ' . $video->view_count . "\n", FILE_APPEND);

        // TEMPORANEO: Incrementa sempre per il test
        $video->increment('view_count');
        file_put_contents(storage_path('views_debug.txt'), 'Views incremented! (TEST MODE)' . "\n", FILE_APPEND);

        // Ricarica il video per ottenere il nuovo conteggio
        $video->refresh();
        file_put_contents(storage_path('views_debug.txt'), 'New views: ' . $video->view_count . "\n", FILE_APPEND);

        return response()->json([
            'success' => true,
            'view_count' => $video->view_count
        ]);
    }

    /**
     * Mostra il video per la riproduzione
     */
    public function play(Video $video)
    {
        // Incrementa le visualizzazioni
        if (Auth::id() !== $video->user_id) {
            $video->increment('view_count');
        }

        return view('videos.play', compact('video'));
    }

    /**
     * Scarica il video (solo per il proprietario o admin)
     */
    public function download(Video $video)
    {
        $user = Auth::user();

        if ($user->id !== $video->user_id && !$user->isAdmin()) {
            abort(403, 'Non autorizzato');
        }

        if (!Storage::disk('public')->exists($video->file_path)) {
            abort(404, 'File non trovato');
        }

        return Storage::disk('public')->download($video->file_path, $video->title . '.mp4');
    }
}
