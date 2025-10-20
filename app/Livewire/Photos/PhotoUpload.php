<?php

namespace App\Livewire\Photos;

use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use App\Models\Photo;
use App\Services\ImageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PhotoUpload extends Component
{
    use WithFileUploads;

    public $photo;
    public $title = '';
    public $description = '';
    public $alt_text = '';
    public $isUploading = false;
    public $uploadProgress = 0;
    public $previewUrl = '';
    public $showPreview = false;

    

    public function mount()
    {
        // Inizializzazione
    }

    public function updatedPhoto()
    {
        $this->validateOnly('photo', [
            'photo' => 'image|max:10240', // 10MB Max
        ]);

        if ($this->photo) {
            $this->previewUrl = $this->photo->temporaryUrl();
            $this->showPreview = true;
        }
    }

    public function removeImage()
    {
        $this->photo = null;
        $this->previewUrl = '';
        $this->showPreview = false;
        $this->resetErrorBag('photo');
    }

   /* public function upload()
    {
        $this->validate([
            'image' => 'required|image|max:10240', // 10MB Max
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'alt_text' => 'nullable|string|max:255',
        ]);

        $this->isUploading = true;
        $this->uploadProgress = 0;

        try {
            $this->uploadProgress = 25;

            // Organizza le foto per utente
            $imageService = app(ImageService::class);
            $userPath = $imageService->organizeUserPhotos(Auth::id());

            $this->uploadProgress = 50;

            // Converte e salva l'immagine principale
            $imageData = $imageService->convertAndSaveImage($this->image, $userPath);

            $this->uploadProgress = 75;

            // Crea il thumbnail
            $thumbnailPath = $userPath . '/thumbnails/' . $imageData['filename'];
            $thumbnailData = $imageService->createThumbnail($imageData['path'], $thumbnailPath);

            $this->uploadProgress = 90;

            // Salva nel database
            $photo = Photo::create([
                'user_id' => Auth::id(),
                'title' => $this->title ?: null,
                'description' => $this->description ?: null,
                'alt_text' => $this->alt_text ?: null,
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

            $this->uploadProgress = 100;

            // Reset form
            $this->reset(['image', 'title', 'description', 'alt_text']);
            $this->isUploading = false;
            $this->uploadProgress = 0;

            // Messaggio di successo
            session()->flash('success', 'Foto caricata con successo!');

            // Redirect alla galleria foto
            return redirect()->route('photos.index');

        } catch (\Exception $e) {
            $this->isUploading = false;
            $this->uploadProgress = 0;
            
            session()->flash('error', 'Errore durante il caricamento: ' . $e->getMessage());
        }
    } */

   public function save(){
    $this->validate([
        'photo' => 'required|image|max:10240',
        'title' => 'nullable|string|max:255',
        'description' => 'nullable|string|max:1000',
        'alt_text' => 'nullable|string|max:255',
    ]);

    $this->isUploading = true;
    $this->uploadProgress = 0;

    try {
        $this->uploadProgress = 25;

        // Salva il file
        $path = $this->photo->store('photos');
        
        $this->uploadProgress = 50;

        // Salva nel database
        $photo = Photo::create([
            'user_id' => Auth::id(),
            'title' => $this->title ?: null,
            'description' => $this->description ?: null,
            'alt_text' => $this->alt_text ?: null,
            'image_path' => $path,
            'thumbnail_path' => null,
            'status' => 'approved',
            'moderation_status' => 'approved',
            'metadata' => [
                'original_name' => $this->photo->getClientOriginalName(),
                'mime_type' => $this->photo->getMimeType(),
                'size' => $this->photo->getSize(),
            ],
        ]);

        $this->uploadProgress = 100;

        // Reset form
        $this->reset(['photo', 'title', 'description', 'alt_text', 'previewUrl', 'showPreview']);
        $this->isUploading = false;
        $this->uploadProgress = 0;

        session()->flash('success', 'Foto caricata con successo!');
        
        return redirect()->route('photos.index');

    } catch (\Exception $e) {
        $this->isUploading = false;
        $this->uploadProgress = 0;
        
        session()->flash('error', 'Errore durante il caricamento: ' . $e->getMessage());
    }
   }

    public function render()
    {
        return view('livewire.photos.photo-upload');
    }
}
