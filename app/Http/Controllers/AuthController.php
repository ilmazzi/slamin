<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

use Exception;

class AuthController extends Controller
{
    /**
     * Mostra la pagina di registrazione con selezione multi-ruolo
     */
    public function showSignup()
    {
        // Mostriamo i ruoli principali per il signup pubblico (incluso venue_owner)
        $roles = Role::whereIn('name', ['poet', 'organizer', 'venue_owner', 'audience'])->get()->map(function($role) {
            return [
                'name' => $role->name,
                'display_name' => $this->getRoleDisplayName($role->name),
                'description' => $this->getRoleDescription($role->name),
                'permissions_count' => $role->permissions->count(),
                'icon' => $this->getRoleIcon($role->name),
                'color' => $this->getRoleColor($role->name),
                'is_primary' => in_array($role->name, ['poet', 'organizer', 'audience']),
            ];
        });

                return view('auth.signup', compact('roles'));
    }



    /**
     * Processa la registrazione dell'utente
     */
    public function processSignup(Request $request)
    {
        // Validazione
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
        ], [
            'name.required' => 'Il nome è obbligatorio',
            'nickname.unique' => 'Questo nickname è già in uso',
            'email.required' => 'L\'email è obbligatoria',
            'email.email' => 'Inserisci un\'email valida',
            'email.unique' => 'Questa email è già registrata',
            'password.required' => 'La password è obbligatoria',
            'password.min' => 'La password deve essere di almeno 8 caratteri',
            'password.confirmed' => 'Le password non coincidono',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Crea l'utente
            $user = User::create([
                'name' => $request->name,
                'nickname' => $request->nickname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => 'active',
            ]);

            // Assegna i ruoli selezionati
            $selectedRoles = $request->roles ?? [];

            // Se non ha selezionato ruoli, assegna 'audience' come default
            if (empty($selectedRoles)) {
                $selectedRoles = ['audience'];
            }

            // Assegna i ruoli
            $user->assignRole($selectedRoles);



            // Login automatico
            Auth::login($user);

            // Messaggio di benvenuto personalizzato
            $roleText = count($selectedRoles) > 1 ?
                count($selectedRoles) . ' ruoli' :
                $this->getRoleDisplayName($selectedRoles[0]);

            $welcomeMessage = "🎉 Ti diamo il benvenuto in Slamin! Hai {$roleText} attivi.";

            return redirect()->route('dashboard')
                ->with('success', $welcomeMessage);

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Errore durante la registrazione: ' . $e->getMessage()])
                ->withInput();
        }
    }



    /**
     * Login semplice per testing
     */
    public function showLogin()
    {
        $roles = Role::all();
        return view('auth.login', compact('roles'));
    }

    /**
     * Processa il login
     */
    public function processLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            return redirect()->route('dashboard')
                ->with('success', "Ti diamo il bentornato, {$user->name}!");
        }

        return back()->withErrors([
            'email' => 'Credenziali non valide.',
        ])->onlyInput('email');
    }

    /**
     * Logout dell'utente
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logout effettuato con successo!');
    }

    /**
     * Ottieni il nome display per un ruolo
     */
    private function getRoleDisplayName($roleName)
    {
        $names = [
            'admin' => 'Amministratore',
            'moderator' => 'Community Manager',
            'poet' => 'Poeta/Artista',
            'organizer' => 'Event Manager',
            'judge' => 'Giudice Competizioni',
            'venue_owner' => 'Proprietario Venue',
            'technician' => 'Tecnico Professionista',
            'audience' => 'Pubblico/Fan',
        ];

        return $names[$roleName] ?? ucfirst($roleName);
    }

    /**
     * Ottieni la descrizione per un ruolo
     */
    private function getRoleDescription($roleName)
    {
        $descriptions = [
            'admin' => 'Controllo completo della piattaforma Slamin',
            'moderator' => 'Moderazione contenuti e gestione community',
            'poet' => 'Partecipazione eventi, pubblicazione contenuti artistici',
            'organizer' => 'Creazione e gestione eventi slam',
            'judge' => 'Giudizio competizioni e valutazione performance',
            'venue_owner' => 'Gestione locali e prenotazioni spazi',
            'technician' => 'Supporto tecnico audio/video per eventi',
            'audience' => 'Partecipazione come pubblico, votazioni e interazioni',
        ];

        return $descriptions[$roleName] ?? 'Ruolo speciale per la community';
    }

    /**
     * Ottieni l'icona per un ruolo
     */
    private function getRoleIcon($roleName)
    {
        $icons = [
            'admin' => '👑',
            'moderator' => '🛡️',
            'poet' => '🎤',
            'organizer' => '🎪',
            'judge' => '👨‍⚖️',
            'venue_owner' => '🏛️',
            'technician' => '🔧',
            'audience' => '👥',
        ];

        return $icons[$roleName] ?? '🎭';
    }

    /**
     * Ottieni il colore per un ruolo
     */
    private function getRoleColor($roleName)
    {
        $colors = [
            'admin' => 'danger',
            'moderator' => 'warning',
            'poet' => 'primary',
            'organizer' => 'success',
            'judge' => 'info',
            'venue_owner' => 'secondary',
            'technician' => 'dark',
            'audience' => 'light',
        ];

        return $colors[$roleName] ?? 'primary';
    }
}
