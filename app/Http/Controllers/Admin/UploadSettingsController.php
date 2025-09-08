<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UploadSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostra la pagina delle impostazioni di upload
     */
    public function index()
    {
        // Verifica che l'utente sia admin
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Accesso negato');
        }

        $uploadSettings = SystemSetting::where('group', 'upload')->get()->mapWithKeys(function ($setting) {
            $value = $setting->value;

            // Gestisci i valori JSON che potrebbero essere stati salvati come stringhe
            if (is_string($value) && $this->isJson($value)) {
                $decoded = json_decode($value, true);
                if (isset($decoded['value'])) {
                    // Se il valore è ancora JSON, estrai il valore interno
                    if (is_string($decoded['value']) && $this->isJson($decoded['value'])) {
                        $innerDecoded = json_decode($decoded['value'], true);
                        if (isset($innerDecoded['value'])) {
                            $value = $innerDecoded['value'];
                        } else {
                            $value = $decoded['value'];
                        }
                    } else {
                        $value = $decoded['value'];
                    }
                }
            }

            return [$setting->key => $value];
        });

        // Assicuriamoci che tutti i valori siano stringhe pulite
        foreach ($uploadSettings as $key => $value) {
            if (is_array($value)) {
                $uploadSettings[$key] = json_encode($value);
            } elseif (is_null($value)) {
                $uploadSettings[$key] = '';
            } else {
                $uploadSettings[$key] = (string) $value;
            }
        }

        return view('admin.settings.upload', compact('uploadSettings'));
    }

    /**
     * Aggiorna le impostazioni di upload
     */
    public function update(Request $request)
    {
        // Verifica che l'utente sia admin
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Accesso negato');
        }

        $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable'
        ]);

        $updated = 0;
        $errors = [];

        foreach ($request->settings as $key => $value) {
            try {
                $setting = SystemSetting::where('key', $key)->first();

                if (!$setting) {
                    // Crea l'impostazione se non esiste
                    $setting = new SystemSetting();
                    $setting->key = $key;
                    $setting->group = 'upload';
                    $setting->type = 'string';
                    $setting->display_name = ucfirst(str_replace('_', ' ', $key));
                    $setting->description = '';
                }

                // Valida il valore in base al tipo
                $validatedValue = $this->validateValue($value, $setting->type);

                if ($validatedValue === false) {
                    $errors[] = "Valore non valido per '{$setting->display_name}'";
                    continue;
                }

                // Salva sempre come stringa pulita per evitare JSON annidati
                $setting->value = (string) $validatedValue;
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
                    ? "Impostazioni di upload aggiornate con successo ({$updated} modificate)"
                    : "Errore nell'aggiornamento: " . implode(', ', $errors),
                'errors' => count($errors) > 0 ? $errors : []
            ]);
        }

        if (count($errors) === 0) {
            return redirect()->back()->with('success', "Impostazioni di upload aggiornate con successo ({$updated} modificate)");
        } else {
            return redirect()->back()->withErrors($errors);
        }
    }

    /**
     * Controlla se una stringa è JSON valido
     */
    private function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Valida il valore in base al tipo
     */
    private function validateValue($value, string $type)
    {
        switch ($type) {
            case 'integer':
                return is_numeric($value) ? (int) $value : false;
            case 'float':
                return is_numeric($value) ? (float) $value : false;
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            case 'json':
                $decoded = json_decode($value, true);
                return json_last_error() === JSON_ERROR_NONE ? $decoded : false;
            case 'string':
            default:
                return (string) $value;
        }
    }
}
