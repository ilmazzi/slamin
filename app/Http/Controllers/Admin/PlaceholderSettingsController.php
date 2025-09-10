<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlaceholderSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlaceholderSettingsController extends Controller
{
    /**
     * Mostra le impostazioni dei placeholder
     */
    public function index()
    {
        $settings = PlaceholderSetting::getSettings();
        
        return view('admin.settings.placeholder', compact('settings'));
    }

    /**
     * Aggiorna le impostazioni dei placeholder
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'poem_placeholder_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'article_placeholder_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ], [
            'poem_placeholder_color.required' => 'Il colore per le poesie è obbligatorio.',
            'poem_placeholder_color.regex' => 'Il colore per le poesie deve essere un codice esadecimale valido (es. #ff0000).',
            'article_placeholder_color.required' => 'Il colore per gli articoli è obbligatorio.',
            'article_placeholder_color.regex' => 'Il colore per gli articoli deve essere un codice esadecimale valido (es. #ff0000).',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            PlaceholderSetting::updateSettings([
                'poem_placeholder_color' => $request->poem_placeholder_color,
                'article_placeholder_color' => $request->article_placeholder_color,
            ]);

            return redirect()->back()->with('success', 'Impostazioni placeholder aggiornate con successo!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Errore durante l\'aggiornamento delle impostazioni: ' . $e->getMessage())
                ->withInput();
        }
    }
}
