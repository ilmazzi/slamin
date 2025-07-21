<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UpdatePermissionDescriptions extends Command
{
    protected $signature = 'permissions:update-descriptions';
    protected $description = 'Aggiorna le descrizioni delle permission e ruoli esistenti';

    public function handle()
    {
        $this->info('=== AGGIORNAMENTO DESCRIZIONI PERMISSION E RUOLI ===');

        // Aggiorna le descrizioni delle permission
        $this->updatePermissions();

        // Aggiorna le descrizioni dei ruoli
        $this->updateRoles();

        $this->info('✅ Aggiornamento completato con successo!');
    }

    private function updatePermissions()
    {
        $this->info('📝 Aggiornamento descrizioni permission...');

        $permissions = [
            // Event permissions
            'create events' => [
                'display_name' => 'Crea Eventi',
                'description' => 'Permette di creare nuovi eventi Poetry Slam nel sistema',
                'group' => 'events'
            ],
            'edit events' => [
                'display_name' => 'Modifica Eventi',
                'description' => 'Permette di modificare eventi esistenti',
                'group' => 'events'
            ],
            'delete events' => [
                'display_name' => 'Elimina Eventi',
                'description' => 'Permette di eliminare eventi dal sistema',
                'group' => 'events'
            ],
            'view events' => [
                'display_name' => 'Visualizza Eventi',
                'description' => 'Permette di visualizzare tutti gli eventi disponibili',
                'group' => 'events'
            ],
            'manage events' => [
                'display_name' => 'Gestisci Eventi',
                'description' => 'Controllo completo su tutti gli aspetti degli eventi',
                'group' => 'events'
            ],

            // Invitation permissions
            'send invitations' => [
                'display_name' => 'Invia Inviti',
                'description' => 'Permette di inviare inviti per partecipare agli eventi',
                'group' => 'invitations'
            ],
            'accept invitations' => [
                'display_name' => 'Accetta Inviti',
                'description' => 'Permette di accettare inviti ricevuti',
                'group' => 'invitations'
            ],
            'decline invitations' => [
                'display_name' => 'Rifiuta Inviti',
                'description' => 'Permette di rifiutare inviti ricevuti',
                'group' => 'invitations'
            ],
            'cancel invitations' => [
                'display_name' => 'Cancella Inviti',
                'description' => 'Permette di cancellare inviti già inviati',
                'group' => 'invitations'
            ],

            // Request permissions
            'send requests' => [
                'display_name' => 'Invia Richieste',
                'description' => 'Permette di inviare richieste di partecipazione agli eventi',
                'group' => 'requests'
            ],
            'approve requests' => [
                'display_name' => 'Approva Richieste',
                'description' => 'Permette di approvare richieste di partecipazione',
                'group' => 'requests'
            ],
            'decline requests' => [
                'display_name' => 'Rifiuta Richieste',
                'description' => 'Permette di rifiutare richieste di partecipazione',
                'group' => 'requests'
            ],
            'view requests' => [
                'display_name' => 'Visualizza Richieste',
                'description' => 'Permette di visualizzare le richieste di partecipazione',
                'group' => 'requests'
            ],

            // Notification permissions
            'send notifications' => [
                'display_name' => 'Invia Notifiche',
                'description' => 'Permette di inviare notifiche agli utenti',
                'group' => 'notifications'
            ],
            'view notifications' => [
                'display_name' => 'Visualizza Notifiche',
                'description' => 'Permette di visualizzare le notifiche',
                'group' => 'notifications'
            ],
            'manage notifications' => [
                'display_name' => 'Gestisci Notifiche',
                'description' => 'Controllo completo sulle notifiche del sistema',
                'group' => 'notifications'
            ],

            // Analytics permissions
            'view analytics' => [
                'display_name' => 'Visualizza Analytics',
                'description' => 'Permette di visualizzare le statistiche del sistema',
                'group' => 'analytics'
            ],
            'export analytics' => [
                'display_name' => 'Esporta Analytics',
                'description' => 'Permette di esportare i dati analytics',
                'group' => 'analytics'
            ],

            // User management permissions
            'manage users' => [
                'display_name' => 'Gestisci Utenti',
                'description' => 'Controllo completo sugli utenti del sistema',
                'group' => 'users'
            ],
            'view profiles' => [
                'display_name' => 'Visualizza Profili',
                'description' => 'Permette di visualizzare i profili degli utenti',
                'group' => 'users'
            ],
            'edit profiles' => [
                'display_name' => 'Modifica Profili',
                'description' => 'Permette di modificare i profili degli utenti',
                'group' => 'users'
            ],

            // System permissions
            'view system' => [
                'display_name' => 'Visualizza Sistema',
                'description' => 'Permette di visualizzare le informazioni del sistema',
                'group' => 'system'
            ],
            'manage system' => [
                'display_name' => 'Gestisci Sistema',
                'description' => 'Controllo completo sul sistema',
                'group' => 'system'
            ],
        ];

        foreach ($permissions as $permissionName => $data) {
            $permission = Permission::where('name', $permissionName)->first();
            if ($permission) {
                $permission->update($data);
                $this->line("✅ Aggiornata permission: {$permissionName}");
            } else {
                $this->warn("⚠️  Permission non trovata: {$permissionName}");
            }
        }
    }

    private function updateRoles()
    {
        $this->info('👥 Aggiornamento descrizioni ruoli...');

        $roles = [
            'super-admin' => [
                'display_name' => 'Super Amministratore',
                'description' => 'Controllo completo su tutto il sistema. Può gestire tutti gli aspetti della piattaforma.'
            ],
            'admin' => [
                'display_name' => 'Amministratore',
                'description' => 'Gestisce il sistema e gli utenti. Ha accesso completo alle funzionalità amministrative.'
            ],
            'moderator' => [
                'display_name' => 'Moderatore',
                'description' => 'Modera contenuti e utenti. Può approvare/rifiutare richieste e gestire eventi.'
            ],
            'organizer' => [
                'display_name' => 'Organizzatore',
                'description' => 'Organizza eventi Poetry Slam. Può creare, modificare e gestire eventi e inviti.'
            ],
            'poet' => [
                'display_name' => 'Poeta/Artista',
                'description' => 'Artista che partecipa agli eventi Poetry Slam. Può visualizzare eventi, accettare inviti e gestire il proprio profilo.'
            ],
            'judge' => [
                'display_name' => 'Giudice',
                'description' => 'Giudica le performance negli eventi. Ha accesso alle analytics per valutazioni e può partecipare agli eventi.'
            ],
            'technician' => [
                'display_name' => 'Tecnico',
                'description' => 'Supporto tecnico per gli eventi. Gestisce aspetti tecnici e può partecipare agli eventi.'
            ],
            'venue-owner' => [
                'display_name' => 'Proprietario Sede',
                'description' => 'Gestisce le sedi degli eventi. Può visualizzare eventi e gestire informazioni sulla sede.'
            ],
            'host' => [
                'display_name' => 'Presentatore',
                'description' => 'Presenta e conduce gli eventi Poetry Slam. Può partecipare agli eventi e gestire il proprio profilo.'
            ],
            'user' => [
                'display_name' => 'Utente',
                'description' => 'Utente base del sistema. Può visualizzare eventi, notifiche e gestire il proprio profilo.'
            ],
        ];

        foreach ($roles as $roleName => $data) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->update($data);
                $this->line("✅ Aggiornato ruolo: {$roleName}");
            } else {
                $this->warn("⚠️  Ruolo non trovato: {$roleName}");
            }
        }
    }
}
