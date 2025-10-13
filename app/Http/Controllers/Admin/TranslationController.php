<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Translation;
use App\Models\TranslationQueue;
use App\Helpers\TranslationHelper;
use App\Helpers\AutoTranslationHelper;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Translation::query();

        // Filtri
        if ($request->filled('group')) {
            $query->group($request->group);
        }

        if ($request->filled('locale')) {
            $query->locale($request->locale);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->key($search)
                  ->orWhere('value', 'like', "%{$search}%");
            });
        }

        // Ordinamento
        $sortBy = $request->get('sort', 'group_name');
        $sortDirection = $request->get('direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        $translations = $query->paginate(20);

        // Statistiche
        $stats = [
            'total' => Translation::count(),
            'groups' => Translation::distinct('group_name')->count('group_name'),
            'locales' => Translation::distinct('locale')->count('locale'),
            'recent' => Translation::where('updated_at', '>=', now()->subDays(7))->count(),
        ];

        // Gruppi disponibili
        $groups = Translation::distinct('group_name')
            ->pluck('group_name')
            ->sort()
            ->values();

        // Locales disponibili
        $locales = Translation::distinct('locale')
            ->pluck('locale')
            ->sort()
            ->values();

        return view('admin.translations.index', compact(
            'translations',
            'stats',
            'groups',
            'locales'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $groups = Translation::distinct('group_name')
            ->pluck('group_name')
            ->sort()
            ->values();

        $locales = ['it', 'en', 'es', 'fr', 'de', 'pt', 'ru'];

        return view('admin.translations.create', compact('groups', 'locales'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'group_name' => 'required|string|max:50',
            'key_name' => 'required|string|max:100',
            'locale' => 'required|string|max:5',
            'value' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $key = $request->group_name . '.' . $request->key_name;
            $result = TranslationHelper::set($key, $request->value, $request->locale);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => __('admin_general.translation_created_success')
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => __('admin_general.translation_created_error')
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin_general.translation_created_error') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Translation $translation)
    {
        return view('admin.translations.show', compact('translation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Translation $translation)
    {
        $groups = Translation::distinct('group_name')
            ->pluck('group_name')
            ->sort()
            ->values();

        $locales = ['it', 'en', 'es', 'fr', 'de', 'pt', 'ru'];

        return view('admin.translations.edit', compact('translation', 'groups', 'locales'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Translation $translation): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'group_name' => 'required|string|max:50',
            'key_name' => 'required|string|max:100',
            'locale' => 'required|string|max:5',
            'value' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Se cambia la chiave, elimina la vecchia e crea la nuova
            if ($translation->group_name !== $request->group_name ||
                $translation->key_name !== $request->key_name ||
                $translation->locale !== $request->locale) {

                TranslationHelper::delete($translation->full_key, $translation->locale);
                $key = $request->group_name . '.' . $request->key_name;
                $result = TranslationHelper::set($key, $request->value, $request->locale);
            } else {
                $result = $translation->update($request->only(['value']));
            }

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => __('admin_general.translation_updated_success')
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => __('admin_general.translation_updated_error')
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin_general.translation_updated_error') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Translation $translation): JsonResponse
    {
        try {
            $result = TranslationHelper::delete($translation->full_key, $translation->locale);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => __('admin_general.translation_deleted_success')
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => __('admin_general.translation_deleted_error')
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin_general.translation_deleted_error') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sincronizza le traduzioni da file a database
     */
    public function syncFromFile(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'group' => 'required|string',
            'locale' => 'required|string|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $count = TranslationHelper::syncFromFile($request->group, $request->locale);

            return response()->json([
                'success' => true,
                'message' => __('admin.translations_synced_success', ['count' => $count])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin_general.translations_synced_error') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sincronizza le traduzioni da database a file
     */
    public function syncToFile(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'group' => 'required|string',
            'locale' => 'required|string|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = TranslationHelper::syncToFile($request->group, $request->locale);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => __('admin_general.translations_synced_to_file_success')
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => __('admin_general.translations_synced_to_file_error')
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin_general.translations_synced_to_file_error') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Gestisce la coda delle traduzioni
     */
    public function queue(Request $request)
    {
        $query = TranslationQueue::query();

        // Filtri
        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->pending();
            } elseif ($request->status === 'processed') {
                $query->processed();
            }
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('context')) {
            $query->context($request->context);
        }

        $queue = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = AutoTranslationHelper::getQueueStats();

        return view('admin.translations.queue', compact('queue', 'stats'));
    }

    /**
     * Converte un elemento della coda in traduzione
     */
    public function convertFromQueue(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:translation_queue,id',
            'group' => 'required|string|max:50',
            'key' => 'required|string|max:100',
            'locale' => 'required|string|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = AutoTranslationHelper::convertToTranslation(
                $request->id,
                $request->group,
                $request->key,
                $request->locale
            );

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => __('admin_general.translation_converted_success')
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => __('admin_general.translation_converted_error')
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin_general.translation_converted_error') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Pulisce la cache delle traduzioni
     */
    public function clearCache(): JsonResponse
    {
        try {
            TranslationHelper::clearAllCache();

            return response()->json([
                'success' => true,
                'message' => __('admin_general.cache_cleared_success')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin_general.cache_cleared_error') . ': ' . $e->getMessage()
            ], 500);
        }
    }
}
