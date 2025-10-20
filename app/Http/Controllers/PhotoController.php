<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    /**
     * Mostra una singola foto
     */
    public function show(Photo $photo)
    {
        return view('photos.show', compact('photo'));
    }

    /**
     * Salva una nuova foto (per API/JavaScript)
     */
    public function store(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:10240',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'alt_text' => 'nullable|string|max:255',
        ]);

        try {
            // Salva il file
            $path = $request->file('photo')->store('photos');
            
            // Salva nel database
            $photo = Photo::create([
                'user_id' => Auth::id(),
                'title' => $request->title ?: null,
                'description' => $request->description ?: null,
                'alt_text' => $request->alt_text ?: null,
                'image_path' => $path,
                'thumbnail_path' => null,
                'status' => 'approved',
                'moderation_status' => 'approved',
                'metadata' => [
                    'original_name' => $request->file('photo')->getClientOriginalName(),
                    'mime_type' => $request->file('photo')->getMimeType(),
                    'size' => $request->file('photo')->getSize(),
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Foto caricata con successo!',
                'photo' => $photo
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore durante il caricamento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Servire l'immagine di una foto
     */
    public function image(Photo $photo)
    {
        // Verifica che la foto sia approvata
        if (!$photo->isApproved()) {
            abort(404);
        }

        // Verifica che il file esista
        if (!Storage::exists($photo->image_path)) {
            abort(404);
        }

        // Ottieni il contenuto del file
        $file = Storage::get($photo->image_path);
        $mimeType = Storage::mimeType($photo->image_path);

        // Restituisci la risposta con l'immagine
        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Cache-Control', 'public, max-age=31536000'); // Cache per 1 anno
    }

    /**
     * Mostra il form di modifica
     */
    public function edit(Photo $photo)
    {
        $this->authorize('update', $photo);
        
        return view('photos.edit', compact('photo'));
    }

    /**
     * Aggiorna la foto
     */
    public function update(Request $request, Photo $photo)
    {
        $this->authorize('update', $photo);

        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'alt_text' => 'nullable|string|max:255',
        ]);

        $photo->update($request->only(['title', 'description', 'alt_text']));

        return redirect()->route('photos.show', $photo)
            ->with('success', 'Foto aggiornata con successo!');
    }

    /**
     * Elimina la foto
     */
    public function destroy(Photo $photo)
    {
        $this->authorize('delete', $photo);

        // Elimina il file fisico
        if (Storage::exists($photo->image_path)) {
            Storage::delete($photo->image_path);
        }

        if ($photo->thumbnail_path && Storage::exists($photo->thumbnail_path)) {
            Storage::delete($photo->thumbnail_path);
        }

        // Elimina il record dal database
        $photo->delete();

        return redirect()->route('photos.index')
            ->with('success', 'Foto eliminata con successo!');
    }
}