<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Log;

class SocialSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Mostra la pagina delle impostazioni social
     */
    public function index()
    {
        $socialSettings = SystemSetting::where('group', 'social')->get();
        
        return view('admin.social-settings.index', compact('socialSettings'));
    }

    /**
     * Aggiorna le impostazioni social
     */
    public function update(Request $request)
    {
        $request->validate([
            'social_enable_likes' => 'boolean',
            'social_likeable_content' => 'array',
            'social_enable_comments' => 'boolean',
            'social_commentable_content' => 'array',
            'social_auto_approve_comments' => 'boolean',
            'social_enable_notifications' => 'boolean',
            'social_notification_types' => 'array',
            'social_enable_views' => 'boolean',
            'social_viewable_content' => 'array',
        ]);

        try {
            // Aggiorna le impostazioni
            $this->updateSetting('social_enable_likes', $request->boolean('social_enable_likes'), 'boolean');
            $this->updateSetting('social_likeable_content', $request->input('social_likeable_content', []), 'json');
            $this->updateSetting('social_enable_comments', $request->boolean('social_enable_comments'), 'boolean');
            $this->updateSetting('social_commentable_content', $request->input('social_commentable_content', []), 'json');
            $this->updateSetting('social_auto_approve_comments', $request->boolean('social_auto_approve_comments'), 'boolean');
            $this->updateSetting('social_enable_notifications', $request->boolean('social_enable_notifications'), 'boolean');
            $this->updateSetting('social_notification_types', $request->input('social_notification_types', []), 'json');
            $this->updateSetting('social_enable_views', $request->boolean('social_enable_views'), 'boolean');
            $this->updateSetting('social_viewable_content', $request->input('social_viewable_content', []), 'json');

            Log::info('Impostazioni social aggiornate', [
                'admin_id' => auth()->id(),
                'settings' => $request->all()
            ]);

            return redirect()->back()->with('success', 'Impostazioni social aggiornate con successo');

        } catch (\Exception $e) {
            Log::error('Errore aggiornamento impostazioni social', [
                'error' => $e->getMessage(),
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Errore durante l\'aggiornamento delle impostazioni');
        }
    }

    /**
     * Toggle di una singola impostazione
     */
    public function toggleFeature(Request $request)
    {
        $request->validate([
            'setting' => 'required|string',
            'value' => 'required|boolean',
        ]);

        try {
            $setting = $request->input('setting');
            $value = $request->boolean('value');

            $this->updateSetting($setting, $value, 'boolean');

            Log::info('Impostazione social toggled', [
                'admin_id' => auth()->id(),
                'setting' => $setting,
                'value' => $value
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Impostazione aggiornata con successo'
            ]);

        } catch (\Exception $e) {
            Log::error('Errore toggle impostazione social', [
                'error' => $e->getMessage(),
                'admin_id' => auth()->id(),
                'setting' => $request->input('setting')
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'aggiornamento'
            ], 500);
        }
    }

    /**
     * Ottieni le impostazioni social via API
     */
    public function getSettings()
    {
        try {
            $settings = SystemSetting::where('group', 'social')
                ->get()
                ->keyBy('key')
                ->map(function ($setting) {
                    return [
                        'value' => SystemSetting::castValue($setting->value, $setting->type),
                        'type' => $setting->type,
                        'display_name' => $setting->display_name,
                        'description' => $setting->description,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $settings
            ]);

        } catch (\Exception $e) {
            Log::error('Errore recupero impostazioni social', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore durante il recupero delle impostazioni'
            ], 500);
        }
    }

    /**
     * Resetta le impostazioni social ai valori di default
     */
    public function reset()
    {
        try {
            $defaultSettings = [
                'social_enable_likes' => true,
                'social_likeable_content' => ['video', 'photo', 'poem', 'article', 'event', 'comment'],
                'social_enable_comments' => true,
                'social_commentable_content' => ['video', 'photo', 'poem', 'article', 'event'],
                'social_auto_approve_comments' => true,
                'social_enable_notifications' => true,
                'social_notification_types' => ['like', 'comment', 'snap'],
                'social_enable_views' => true,
                'social_viewable_content' => ['video', 'photo', 'poem', 'article', 'event'],
            ];

            foreach ($defaultSettings as $key => $value) {
                $type = is_array($value) ? 'json' : 'boolean';
                $this->updateSetting($key, $value, $type);
            }

            Log::info('Impostazioni social resettate ai valori di default', [
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()->with('success', 'Impostazioni social resettate ai valori di default');

        } catch (\Exception $e) {
            Log::error('Errore reset impostazioni social', [
                'error' => $e->getMessage(),
                'admin_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Errore durante il reset delle impostazioni');
        }
    }

    /**
     * Aggiorna una singola impostazione
     */
    private function updateSetting(string $key, $value, string $type): void
    {
        SystemSetting::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : (string) $value,
                'type' => $type,
                'group' => 'social',
            ]
        );
    }
}
