<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Converte e salva un'immagine in formato WebP
     */
    public function convertAndSaveImage(UploadedFile $file, string $path, int $quality = 80): array
    {
        // Genera un nome file unico
        $filename = Str::uuid() . '.webp';
        $fullPath = $path . '/' . $filename;

        // Crea il manager per Intervention Image
        $manager = new ImageManager(new Driver());

        // Crea l'immagine con Intervention Image
        $image = $manager->read($file);

        // Imposta la risoluzione a 72 DPI
        $image->resize($image->width(), $image->height());

        // Converte in WebP con qualità specificata
        $webpData = $image->toWebp($quality);

        // Salva nel filesystem
        Storage::disk('public')->put($fullPath, $webpData);

        return [
            'path' => $fullPath,
            'filename' => $filename,
            'width' => $image->width(),
            'height' => $image->height(),
            'size' => strlen($webpData),
            'mime_type' => 'image/webp'
        ];
    }

    /**
     * Crea un thumbnail dell'immagine
     */
    public function createThumbnail(string $imagePath, string $thumbnailPath, int $width = 300, int $height = 300): array
    {
        // Crea il manager per Intervention Image
        $manager = new ImageManager(new Driver());

        // Carica l'immagine originale
        $image = $manager->read(Storage::disk('public')->path($imagePath));

        // Ridimensiona per il thumbnail
        $image->cover($width, $height);

        // Converte in WebP
        $webpData = $image->toWebp(80);

        // Salva il thumbnail
        Storage::disk('public')->put($thumbnailPath, $webpData);

        return [
            'path' => $thumbnailPath,
            'width' => $image->width(),
            'height' => $image->height(),
            'size' => strlen($webpData)
        ];
    }

    /**
     * Organizza le foto per utente
     */
    public function organizeUserPhotos(int $userId): string
    {
        return "photos/users/{$userId}";
    }

    /**
     * Elimina un'immagine e il suo thumbnail
     */
    public function deleteImage(string $imagePath, ?string $thumbnailPath = null): bool
    {
        $deleted = true;

        // Elimina l'immagine principale
        if (Storage::disk('public')->exists($imagePath)) {
            $deleted = $deleted && Storage::disk('public')->delete($imagePath);
        }

        // Elimina il thumbnail se esiste
        if ($thumbnailPath && Storage::disk('public')->exists($thumbnailPath)) {
            $deleted = $deleted && Storage::disk('public')->delete($thumbnailPath);
        }

        return $deleted;
    }

    /**
     * Valida un file immagine
     */
    public function validateImage(UploadedFile $file): bool
    {
        $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 10 * 1024 * 1024; // 10MB

        return in_array($file->getMimeType(), $allowedMimes) && $file->getSize() <= $maxSize;
    }
}
