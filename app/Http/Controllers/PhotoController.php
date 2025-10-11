<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PhotoController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
        $this->middleware('auth')->except(['index', 'show', 'getComments', 'getPhotoData', 'getUserPhotos']);
    }

    /**
     * Mostra le foto di un utente
     */
    public function index(Request $request, $userId = null)
    {
        $user = $userId ? \App\Models\User::findOrFail($userId) : Auth::user();
        $photos = $user->photos()->approved()->orderBy('created_at', 'desc')->get();

        return view('photos.index', compact('user', 'photos'));
    }

    /**
     * Mostra il form di upload
     */
    public function create()
    {
        return view('photos.create');
    }

    /**
     * Salva una nuova foto
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'alt_text' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('image');

            // Valida il file
            if (!$this->imageService->validateImage($file)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File non valido'
                ], 422);
            }

            // Organizza le foto per utente
            $userPath = $this->imageService->organizeUserPhotos(Auth::id());

            // Converte e salva l'immagine principale
            $imageData = $this->imageService->convertAndSaveImage($file, $userPath);

            // Crea il thumbnail
            $thumbnailPath = $userPath . '/thumbnails/' . $imageData['filename'];
            $thumbnailData = $this->imageService->createThumbnail($imageData['path'], $thumbnailPath);

            // Salva nel database
            $photo = Photo::create([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'alt_text' => $request->alt_text,
                'image_path' => $imageData['path'],
                'thumbnail_path' => $thumbnailData['path'],
                'status' => 'approved', // Per ora approvate automaticamente
                'metadata' => [
                    'width' => $imageData['width'],
                    'height' => $imageData['height'],
                    'size' => $imageData['size'],
                    'mime_type' => $imageData['mime_type'],
                    'thumbnail_width' => $thumbnailData['width'],
                    'thumbnail_height' => $thumbnailData['height'],
                ]
            ]);

            return response()->json([
                'success' => true,
                'photo' => $photo->load('user'),
                'message' => 'Foto caricata con successo',
                'redirect' => route('photos.show', $photo)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore durante il caricamento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostra una foto specifica
     */
    public function show(Photo $photo)
    {
        $photo->incrementViewCount();

        // Carica le foto correlate (stesso utente, escludendo la foto corrente)
        $relatedPhotos = Photo::where('user_id', $photo->user_id)
            ->where('id', '!=', $photo->id)
            ->approved()
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        return view('photos.show', compact('photo', 'relatedPhotos'));
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
     * Aggiorna una foto
     */
    public function update(Request $request, Photo $photo)
    {
        $this->authorize('update', $photo);

        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'alt_text' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $photo->update($request->only(['title', 'description', 'alt_text']));

        return redirect()->route('photos.show', $photo)->with('success', 'Foto aggiornata con successo');
    }

    /**
     * Elimina una foto
     */
    public function destroy(Photo $photo)
    {
        $this->authorize('delete', $photo);

        try {
            // Elimina i file
            $this->imageService->deleteImage($photo->image_path, $photo->thumbnail_path);

            // Elimina dal database
            $photo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Foto eliminata con successo'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'eliminazione: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API per ottenere le foto di un utente
     */
    public function getUserPhotos($userId)
    {
        $user = \App\Models\User::findOrFail($userId);
        $photos = $user->photos()->approved()->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'photos' => $photos->map(function ($photo) {
                return [
                    'id' => $photo->id,
                    'title' => $photo->title,
                    'description' => $photo->description,
                    'image_url' => $photo->image_url,
                    'thumbnail_url' => $photo->thumbnail_url,
                    'alt_text' => $photo->alt_text,
                    'created_at' => $photo->created_at,
                    'like_count' => $photo->like_count,
                    'view_count' => $photo->view_count,
                ];
            })
        ]);
    }

    /**
     * API per ottenere i dati di una foto
     */
    public function getPhotoData(Photo $photo)
    {
        if (!$photo->isApproved()) {
            return response()->json([
                'success' => false,
                'message' => 'Foto non disponibile'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'photo' => [
                'id' => $photo->id,
                'title' => $photo->title,
                'description' => $photo->description,
                'image_url' => $photo->image_url,
                'thumbnail_url' => $photo->thumbnail_url,
                'alt_text' => $photo->alt_text,
                'created_at' => $photo->created_at,
                'like_count' => $photo->like_count,
                'view_count' => $photo->view_count,
                'user' => [
                    'id' => $photo->user->id,
                    'name' => $photo->user->name,
                    'username' => $photo->user->username,
                ]
            ]
        ]);
    }

    /**
     * Ottiene i commenti di una foto per API
     */
    public function getComments(Photo $photo)
    {
        $comments = $photo->approvedComments()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($comment) {
                $comment->user->avatar_url = \App\Helpers\AvatarHelper::getUserAvatarUrl($comment->user);
                return $comment;
            });

        return response()->json([
            'success' => true,
            'comments' => $comments
        ]);
    }
}
