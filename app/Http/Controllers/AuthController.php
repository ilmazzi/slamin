<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Exception;
use App\Services\PeerTubeService;
use App\Services\LoggingService;
use App\Helpers\LanguageHelper;
use App\Events\UserLogged;
use App\Events\UserLoggedIn;

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

        // Recupera le lingue disponibili dinamicamente
        $languages = LanguageHelper::getAvailableLanguages();

        return view('auth.signup', compact('roles', 'languages'));
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
            'language' => 'required|string|in:' . implode(',', array_keys(LanguageHelper::getAvailableLanguages())),
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
        ], [
            'name.required' => __('auth.name_required'),
            'nickname.unique' => __('auth.nickname_unique'),
            'email.required' => __('auth.email_required'),
            'email.email' => __('auth.email_valid'),
            'email.unique' => __('auth.email_unique'),
            'password.required' => __('auth.password_required'),
            'password.min' => __('auth.password_min'),
            'password.confirmed' => __('auth.password_confirmed'),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Crea l'utente (senza verifica email iniziale)
            $user = User::create([
                'name' => $request->name,
                'nickname' => $request->nickname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => 'active',
                'email_verified_at' => null, // Non verificato inizialmente
            ]);

            // Assegna i ruoli selezionati
            $selectedRoles = $request->roles ?? [];

            // Se non ha selezionato ruoli, assegna 'audience' come default
            if (empty($selectedRoles)) {
                $selectedRoles = ['audience'];
            }

            // Assegna i ruoli
            $user->assignRole($selectedRoles);

            // Login automatico per permettere l'accesso alla pagina di verifica
            Auth::login($user);

            // Imposta la lingua nella sessione
            $request->session()->put('locale', $request->language);
            app()->setLocale($request->language);

            // Invia email di verifica
            try {
                $user->sendEmailVerificationNotification();
                Log::info('Email di verifica inviata con successo', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
            } catch (\Exception $e) {
                Log::error('Errore invio email di verifica', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'error' => $e->getMessage()
                ]);
            }

            // Salva i ruoli per la creazione PeerTube post-verifica
            $user->update([
                'peertube_roles' => json_encode($selectedRoles) // Salva i ruoli per dopo la verifica
            ]);

            // Log registration
            LoggingService::logAuth('register', [
                'user_id' => $user->id,
                'email' => $user->email,
                'roles' => $selectedRoles,
                'email_verification_sent' => true
            ], 'App\Models\User', $user->id);

            // Messaggio di registrazione con verifica email
            $roleText = count($selectedRoles) > 1 ?
                count($selectedRoles) . ' ruoli' :
                $this->getRoleDisplayName($selectedRoles[0]);

            $welcomeMessage = "🎉 Registrazione completata! Hai {$roleText} assegnati. Controlla la tua email per verificare l'account e accedere a tutte le funzionalità.";

            return redirect()->route('verification.notice')
                ->with('success', $welcomeMessage);

        } catch (Exception $e) {
            // Log registration error
            LoggingService::logError('registration_failed', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'roles' => $request->roles ?? []
            ]);

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

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Invia evento broadcast per utenti non admin
            // Temporaneamente disabilitato per evitare errori Pusher
            // broadcast(new UserLoggedIn($user))->toOthers();

            $message = $remember
                ? "Ti diamo il bentornato, {$user->name}! Ti ricorderemo per i prossimi accessi."
                : "Ti diamo il bentornato, {$user->name}!";

            return redirect()->route('dashboard')
                ->with('success', $message);
        }

        // Log failed login attempt
        LoggingService::logAuth('login_failed', [
            'email' => $request->email,
            'ip' => $request->ip()
        ]);

        return back()->withErrors([
            'email' => __('auth.credentials_invalid'),
        ])->onlyInput('email');
    }

    /**
     * Logout dell'utente
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        // Log logout before destroying session
        if ($user) {
            // Rimuovi lo stato online da Redis direttamente
            try {
                $key = 'online:user:' . $user->id;
                \Illuminate\Support\Facades\Redis::connection('default')->del($key);
                \Illuminate\Support\Facades\Log::info('Stato online rimosso al logout', [
                    'user_id' => $user->id,
                    'key' => $key
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Errore nel rimuovere stato online al logout', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }

            LoggingService::logAuth('logout', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip()
            ], 'App\Models\User', $user->id);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', __('auth.logout_success'));
    }

    /**
     * Ottieni il nome display per un ruolo
     */
    private function getRoleDisplayName($roleName)
    {
        $names = [
            'admin' => __('register.role_admin_name'),
            'moderator' => __('register.role_moderator_name'),
            'poet' => __('register.role_poet_name'),
            'organizer' => __('register.role_organizer_name'),
            'judge' => __('register.role_judge_name'),
            'venue_owner' => __('register.role_venue_owner_name'),
            'technician' => __('register.role_technician_name'),
            'audience' => __('register.role_audience_name'),
        ];

        return $names[$roleName] ?? ucfirst($roleName);
    }

    /**
     * Ottieni la descrizione per un ruolo
     */
    private function getRoleDescription($roleName)
    {
        $descriptions = [
            'admin' => __('register.role_admin_description'),
            'moderator' => __('register.role_moderator_description'),
            'poet' => __('register.role_poet_description'),
            'organizer' => __('register.role_organizer_description'),
            'judge' => __('register.role_judge_description'),
            'venue_owner' => __('register.role_venue_owner_description'),
            'technician' => __('register.role_technician_description'),
            'audience' => __('register.role_audience_description'),
        ];

        return $descriptions[$roleName] ?? __('register.role_special_description');
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

    /**
     * Mostra la pagina per richiedere il reset della password
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Invia il link di reset password via email
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => __('auth.email_required'),
            'email.email' => __('auth.email_invalid'),
            'email.exists' => __('auth.email_not_found')
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __('auth.reset_link_sent'));
        } else {
            return back()->withErrors(['email' => __('auth.reset_link_failed')]);
        }
    }

    /**
     * Mostra la pagina per resettare la password
     */
    public function showResetPassword(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Resetta la password dell'utente
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed'
        ], [
            'email.required' => __('auth.email_required'),
            'email.email' => __('auth.email_invalid'),
            'email.exists' => __('auth.email_not_found'),
            'password.required' => __('auth.password_required'),
            'password.min' => __('auth.password_min'),
            'password.confirmed' => __('auth.password_confirmed')
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new \Illuminate\Auth\Events\PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __('auth.password_reset_success'));
        } else {
            return back()->withErrors(['email' => __('auth.password_reset_failed')]);
        }
    }
}
