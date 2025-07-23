<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use App\Models\Video;
use App\Services\ThumbnailService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SystemSettingsController extends Controller
{
    /**
     * Mostra la pagina delle impostazioni di sistema
     */
    public function index()
    {
        $groups = [
            'upload' => 'Limiti Upload',
            'video' => 'Limiti Video',
            'system' => 'Impostazioni'
        ];

        $settings = [];
        foreach ($groups as $group => $displayName) {
            $settings[$group] = SystemSetting::getGroup($group);
        }

        return view('admin.settings.index', compact('settings', 'groups'));
    }

    /**
     * Aggiorna le impostazioni
     */
    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'required'
        ]);

        $updated = 0;
        $errors = [];

        foreach ($request->settings as $key => $value) {
            try {
                $setting = SystemSetting::where('key', $key)->first();

                if (!$setting) {
                    $errors[] = "Impostazione '{$key}' non trovata";
                    continue;
                }

                // Valida il valore in base al tipo
                $validatedValue = $this->validateValue($value, $setting->type);

                if ($validatedValue === false) {
                    $errors[] = "Valore non valido per '{$setting->display_name}'";
                    continue;
                }

                $setting->value = is_array($validatedValue) ? json_encode($validatedValue) : (string) $validatedValue;
                $setting->save();

                // Pulisce la cache
                Cache::forget("system_setting_{$key}");
                $updated++;

            } catch (\Exception $e) {
                $errors[] = "Errore nell'aggiornamento di '{$key}': " . $e->getMessage();
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => count($errors) === 0,
                'message' => count($errors) === 0
                    ? "Impostazioni aggiornate con successo ({$updated} modificate)"
                    : "Errore nell'aggiornamento: " . implode(', ', $errors),
                'errors' => $errors
            ]);
        }

        if (count($errors) === 0) {
            return redirect()->back()->with('success', "Impostazioni aggiornate con successo ({$updated} modificate)");
        } else {
            return redirect()->back()->withErrors($errors);
        }
    }

    /**
     * Valida il valore in base al tipo
     */
    private function validateValue($value, string $type)
    {
        switch ($type) {
            case 'integer':
                return is_numeric($value) ? (int) $value : false;

            case 'boolean':
                if (is_bool($value)) return $value;
                if (is_string($value)) {
                    $value = strtolower($value);
                    return in_array($value, ['true', '1', 'yes', 'on']) ? true :
                           (in_array($value, ['false', '0', 'no', 'off']) ? false : false);
                }
                return false;

            case 'json':
                if (is_array($value)) return $value;
                if (is_string($value)) {
                    $decoded = json_decode($value, true);
                    return json_last_error() === JSON_ERROR_NONE ? $decoded : false;
                }
                return false;

            case 'float':
                return is_numeric($value) ? (float) $value : false;

            default:
                return is_string($value) ? $value : false;
        }
    }

    /**
     * Reimposta le impostazioni ai valori di default
     */
    public function reset()
    {
        try {
            SystemSetting::initializeDefaults();

            return response()->json([
                'success' => true,
                'message' => 'Impostazioni reimpostate ai valori di default'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore nel reset delle impostazioni: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ottiene le impostazioni in formato JSON (per API)
     */
    public function getSettings()
    {
        $settings = SystemSetting::where('is_public', true)
            ->get()
            ->keyBy('key')
            ->map(function ($setting) {
                return [
                    'value' => SystemSetting::castValue($setting->value, $setting->type),
                    'type' => $setting->type
                ];
            });

        return response()->json($settings);
    }

    /**
     * Gestisce la generazione delle thumbnail
     */
    public function manageThumbnails(Request $request, ThumbnailService $thumbnailService)
    {
        $action = $request->input('action');

        switch ($action) {
            case 'generate_missing':
                return $this->generateMissingThumbnails($request, $thumbnailService);

            case 'regenerate_all':
                return $this->regenerateAllThumbnails($request, $thumbnailService);

            case 'get_stats':
                return $this->getThumbnailStats();

            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Azione non valida'
                ]);
        }
    }

    /**
     * Genera thumbnail mancanti
     */
    private function generateMissingThumbnails(Request $request, ThumbnailService $thumbnailService)
    {
        try {
            $videos = Video::where('moderation_status', 'approved')
                          ->where(function($query) {
                              $query->whereNull('thumbnail_path')
                                    ->orWhere('thumbnail_path', '');
                          })
                          ->get();

            $success = 0;
            $errors = 0;
            $results = [];

                        foreach ($videos as $video) {
                try {
                    // Usa il metodo con fallback che garantisce sempre una thumbnail
                    $thumbnailPath = $thumbnailService->generateThumbnailWithFallback($video);

                    $video->update(['thumbnail_path' => $thumbnailPath]);
                    $success++;
                    $results[] = [
                        'video_id' => $video->id,
                        'title' => $video->title,
                        'status' => 'success',
                        'thumbnail' => $thumbnailPath
                    ];

                } catch (\Exception $e) {
                    $errors++;
                    $results[] = [
                        'video_id' => $video->id,
                        'title' => $video->title,
                        'status' => 'error',
                        'error' => $e->getMessage()
                    ];
                }
            }

            Log::info("Thumbnail generation completed: {$success} success, {$errors} errors");

            return response()->json([
                'success' => true,
                'message' => "Generazione completata: {$success} successi, {$errors} errori",
                'data' => [
                    'total_processed' => $videos->count(),
                    'success' => $success,
                    'errors' => $errors,
                    'results' => $results
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Error in generateMissingThumbnails: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Errore durante la generazione: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Rigenera tutte le thumbnail
     */
    private function regenerateAllThumbnails(Request $request, ThumbnailService $thumbnailService)
    {
        try {
            $videos = Video::where('moderation_status', 'approved')->get();

            $success = 0;
            $errors = 0;
            $results = [];

                        foreach ($videos as $video) {
                try {
                    // Elimina thumbnail esistenti
                    if ($video->thumbnail_path) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($video->thumbnail_path);
                    }

                    // Usa il metodo con fallback che garantisce sempre una thumbnail
                    $thumbnailPath = $thumbnailService->generateThumbnailWithFallback($video);

                    $video->update(['thumbnail_path' => $thumbnailPath]);
                    $success++;
                    $results[] = [
                        'video_id' => $video->id,
                        'title' => $video->title,
                        'status' => 'success',
                        'thumbnail' => $thumbnailPath
                    ];

                } catch (\Exception $e) {
                    $errors++;
                    $results[] = [
                        'video_id' => $video->id,
                        'title' => $video->title,
                        'status' => 'error',
                        'error' => $e->getMessage()
                    ];
                }
            }

            Log::info("Thumbnail regeneration completed: {$success} success, {$errors} errors");

            return response()->json([
                'success' => true,
                'message' => "Rigenerazione completata: {$success} successi, {$errors} errori",
                'data' => [
                    'total_processed' => $videos->count(),
                    'success' => $success,
                    'errors' => $errors,
                    'results' => $results
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Error in regenerateAllThumbnails: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Errore durante la rigenerazione: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Ottiene statistiche delle thumbnail
     */
    private function getThumbnailStats()
    {
        $totalVideos = Video::where('moderation_status', 'approved')->count();
        $videosWithThumbnail = Video::where('moderation_status', 'approved')
                                   ->whereNotNull('thumbnail_path')
                                   ->where('thumbnail_path', '!=', '')
                                   ->count();
        $videosWithoutThumbnail = $totalVideos - $videosWithThumbnail;

        return response()->json([
            'success' => true,
            'data' => [
                'total_videos' => $totalVideos,
                'with_thumbnail' => $videosWithThumbnail,
                'without_thumbnail' => $videosWithoutThumbnail,
                'percentage' => $totalVideos > 0 ? round(($videosWithThumbnail / $totalVideos) * 100, 2) : 0
            ]
        ]);
    }
}
