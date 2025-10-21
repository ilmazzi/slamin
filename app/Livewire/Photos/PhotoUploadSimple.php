<?php

namespace App\Livewire\Photos;

use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use App\Models\Photo;
use App\Services\ImageService;
use Illuminate\Support\Facades\Auth;

class PhotoUploadSimple extends Component
{
    use WithFileUploads;

    public $image;
    public $title = '';
    public $description = '';

    public function upload()
    {
        $this->validate([
            'image' => 'required|image|max:10240',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            // Organizza le foto per utente
            $imageService = app(ImageService::class);
            $userPath = $imageService->organizeUserPhotos(Auth::id());

            // Converte e salva l'immagine principale
            $imageData = $imageService->convertAndSaveImage($this->image, $userPath);

            // Crea il thumbnail
            $thumbnailPath = $userPath . '/thumbnails/' . $imageData['filename'];
            $thumbnailData = $imageService->createThumbnail($imageData['path'], $thumbnailPath);

            // Salva nel database
            $photo = Photo::create([
                'user_id' => Auth::id(),
                'title' => $this->title ?: null,
                'description' => $this->description ?: null,
                'alt_text' => null,
                'image_path' => $imageData['path'],
                'thumbnail_path' => $thumbnailData['path'],
                'status' => 'approved',
                'moderation_status' => 'approved',
                'metadata' => [
                    'width' => $imageData['width'],
                    'height' => $imageData['height'],
                    'size' => $imageData['size'],
                    'original_name' => $this->image->getClientOriginalName(),
                    'mime_type' => $imageData['mime_type'],
                ],
            ]);

            // Reset form
            $this->reset(['image', 'title', 'description']);

            // Messaggio di successo
            session()->flash('success', 'Foto caricata con successo!');

            // Redirect alla galleria foto
            return redirect()->route('photos.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Errore durante il caricamento: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.photos.photo-upload-simple');
    }
}



