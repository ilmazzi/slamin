<?php

namespace App\Http\Controllers;

use App\Models\UserLanguage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserLanguageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostra la pagina per gestire le lingue
     */
    public function index()
    {
        $user = Auth::user();
        $languages = $user->languages()->orderBy('language_name')->get();

        return view('profile.languages.index', compact('languages'));
    }

    /**
     * Mostra il form per aggiungere una nuova lingua
     */
    public function create()
    {
        $worldLanguages = \App\Providers\LanguageServiceProvider::getAllWorldLanguages();

        return view('profile.languages.create', compact('worldLanguages'));
    }

    /**
     * Salva una nuova lingua
     */
    public function store(Request $request)
    {
        $request->validate([
            'language_name' => 'required|string|max:255',
            'language_code' => 'required|string|max:5',
            'type' => ['required', Rule::in(['native', 'spoken', 'written'])],
            'level' => ['nullable', Rule::in(['excellent', 'good', 'poor'])],
        ]);

        // Se è madrelingua, il livello deve essere null
        if ($request->type === 'native') {
            $request->merge(['level' => null]);
        } else {
            // Se è parlato o scritto, il livello è obbligatorio
            $request->validate([
                'level' => 'required|in:excellent,good,poor',
            ]);
        }

        // Verifica che non esista già questa combinazione
        $existing = UserLanguage::where('user_id', Auth::id())
            ->where('language_code', $request->language_code)
            ->where('type', $request->type)
            ->first();

        if ($existing) {
            return back()->withErrors([
                'language' => __('languages.already_exists')
            ])->withInput();
        }

        UserLanguage::create([
            'user_id' => Auth::id(),
            'language_name' => $request->language_name,
            'language_code' => $request->language_code,
            'type' => $request->type,
            'level' => $request->level,
        ]);

        return redirect()->route('profile.languages.index')
            ->with('success', __('languages.added_successfully'));
    }

    /**
     * Mostra il form per modificare una lingua
     */
    public function edit(UserLanguage $userLanguage)
    {
        // Verifica che la lingua appartenga all'utente corrente
        if ($userLanguage->user_id !== Auth::id()) {
            abort(403);
        }

        $worldLanguages = \App\Providers\LanguageServiceProvider::getAllWorldLanguages();

        return view('profile.languages.edit', compact('userLanguage', 'worldLanguages'));
    }

    /**
     * Aggiorna una lingua esistente
     */
    public function update(Request $request, UserLanguage $userLanguage)
    {
        // Verifica che la lingua appartenga all'utente corrente
        if ($userLanguage->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'language_name' => 'required|string|max:255',
            'language_code' => 'required|string|max:5',
            'type' => ['required', Rule::in(['native', 'spoken', 'written'])],
            'level' => ['nullable', Rule::in(['excellent', 'good', 'poor'])],
        ]);

        // Se è madrelingua, il livello deve essere null
        if ($request->type === 'native') {
            $request->merge(['level' => null]);
        } else {
            // Se è parlato o scritto, il livello è obbligatorio
            $request->validate([
                'level' => 'required|in:excellent,good,poor',
            ]);
        }

        // Verifica che non esista già questa combinazione (escludendo quella corrente)
        $existing = UserLanguage::where('user_id', Auth::id())
            ->where('language_code', $request->language_code)
            ->where('type', $request->type)
            ->where('id', '!=', $userLanguage->id)
            ->first();

        if ($existing) {
            return back()->withErrors([
                'language' => __('languages.already_exists')
            ])->withInput();
        }

        $userLanguage->update([
            'language_name' => $request->language_name,
            'language_code' => $request->language_code,
            'type' => $request->type,
            'level' => $request->level,
        ]);

        return redirect()->route('profile.languages.index')
            ->with('success', __('languages.updated_successfully'));
    }

    /**
     * Elimina una lingua
     */
    public function destroy(UserLanguage $userLanguage)
    {
        // Verifica che la lingua appartenga all'utente corrente
        if ($userLanguage->user_id !== Auth::id()) {
            abort(403);
        }

        $userLanguage->delete();

        return redirect()->route('profile.languages.index')
            ->with('success', __('languages.deleted_successfully'));
    }

    /**
     * API per cercare lingue (per autocomplete)
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Lista delle lingue più comuni
        $languages = [
            ['name' => 'Italiano', 'code' => 'it'],
            ['name' => 'English', 'code' => 'en'],
            ['name' => 'Français', 'code' => 'fr'],
            ['name' => 'Español', 'code' => 'es'],
            ['name' => 'Deutsch', 'code' => 'de'],
            ['name' => 'Português', 'code' => 'pt'],
            ['name' => 'Русский', 'code' => 'ru'],
            ['name' => '中文', 'code' => 'zh'],
            ['name' => '日本語', 'code' => 'ja'],
            ['name' => '한국어', 'code' => 'ko'],
            ['name' => 'العربية', 'code' => 'ar'],
            ['name' => 'हिन्दी', 'code' => 'hi'],
            ['name' => 'Nederlands', 'code' => 'nl'],
            ['name' => 'Svenska', 'code' => 'sv'],
            ['name' => 'Norsk', 'code' => 'no'],
            ['name' => 'Dansk', 'code' => 'da'],
            ['name' => 'Suomi', 'code' => 'fi'],
            ['name' => 'Polski', 'code' => 'pl'],
            ['name' => 'Čeština', 'code' => 'cs'],
            ['name' => 'Magyar', 'code' => 'hu'],
            ['name' => 'Română', 'code' => 'ro'],
            ['name' => 'Български', 'code' => 'bg'],
            ['name' => 'Ελληνικά', 'code' => 'el'],
            ['name' => 'Türkçe', 'code' => 'tr'],
            ['name' => 'עברית', 'code' => 'he'],
            ['name' => 'فارسی', 'code' => 'fa'],
            ['name' => 'اردو', 'code' => 'ur'],
            ['name' => 'বাংলা', 'code' => 'bn'],
            ['name' => 'தமிழ்', 'code' => 'ta'],
            ['name' => 'తెలుగు', 'code' => 'te'],
            ['name' => 'मराठी', 'code' => 'mr'],
            ['name' => 'ગુજરાતી', 'code' => 'gu'],
            ['name' => 'ಕನ್ನಡ', 'code' => 'kn'],
            ['name' => 'മലയാളം', 'code' => 'ml'],
            ['name' => 'ਪੰਜਾਬੀ', 'code' => 'pa'],
            ['name' => 'ଓଡ଼ିଆ', 'code' => 'or'],
            ['name' => 'অসমীয়া', 'code' => 'as'],
        ];

        $filtered = collect($languages)->filter(function ($language) use ($query) {
            return stripos($language['name'], $query) !== false;
        })->take(10);

        return response()->json($filtered->values());
    }
}
