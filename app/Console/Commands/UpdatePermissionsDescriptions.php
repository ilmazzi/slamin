<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UpdatePermissionsDescriptions extends Command
{
    protected $signature = 'permissions:update-descriptions';
    protected $description = 'Aggiorna le descrizioni di ruoli e permessi esistenti';

    public function handle()
    {
        $this->info('🔄 Aggiornamento descrizioni permessi...');

        // Update permissions with descriptions
        $permissionsData = [
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
                'description' => 'Permette di visualizzare tutte le richieste di partecipazione',
                'group' => 'requests'
            ],
            'send notifications' => [
                'display_name' => 'Invia Notifiche',
                'description' => 'Permette di inviare notifiche agli utenti',
                'group' => 'notifications'
            ],
            'view notifications' => [
                'display_name' => 'Visualizza Notifiche',
                'description' => 'Permette di visualizzare le proprie notifiche',
                'group' => 'notifications'
            ],
            'manage notifications' => [
                'display_name' => 'Gestisci Notifiche',
                'description' => 'Controllo completo su tutte le notifiche del sistema',
                'group' => 'notifications'
            ],
            'view analytics' => [
                'display_name' => 'Visualizza Analytics',
                'description' => 'Permette di visualizzare statistiche e analisi degli eventi',
                'group' => 'analytics'
            ],
            'export analytics' => [
                'display_name' => 'Esporta Analytics',
                'description' => 'Permette di esportare dati analitici in vari formati',
                'group' => 'analytics'
            ],
            'manage users' => [
                'display_name' => 'Gestisci Utenti',
                'description' => 'Controllo completo su tutti gli utenti del sistema',
                'group' => 'users'
            ],
            'view profiles' => [
                'display_name' => 'Visualizza Profili',
                'description' => 'Permette di visualizzare i profili degli altri utenti',
                'group' => 'users'
            ],
            'edit profiles' => [
                'display_name' => 'Modifica Profili',
                'description' => 'Permette di modificare il proprio profilo utente',
                'group' => 'users'
            ],
        ];

        foreach ($permissionsData as $permissionName => $data) {
            $permission = Permission::where('name', $permissionName)->first();
            if ($permission) {
                $permission->update($data);
                $this->line("✅ Aggiornato permesso: {$permissionName}");
            } else {
                $this->warn("⚠️ Permesso non trovato: {$permissionName}");
            }
        }

        $this->info('🔄 Aggiornamento descrizioni ruoli...');

        // Update roles with descriptions
        $rolesData = [
            'super-admin' => [
                'display_name' => 'Super Amministratore',
                'description' => 'Accesso completo a tutte le funzionalità del sistema. Può gestire utenti, eventi, permessi e configurazioni di sistema.'
            ],
            'admin' => [
                'display_name' => 'Amministratore',
                'description' => 'Amministratore del sistema con accesso a tutte le funzionalità principali. Gestisce eventi, utenti e contenuti.'
            ],
            'moderator' => [
                'display_name' => 'Moderatore',
                'description' => 'Modera contenuti e utenti. Può approvare/rifiutare richieste e gestire eventi.'
            ],
            'organizer' => [
                'display_name' => 'Organizzatore',
                'description' => 'Organizza e gestisce eventi Poetry Slam. Può creare eventi, inviare inviti e gestire partecipanti.'
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

        foreach ($rolesData as $roleName => $data) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->update($data);
                $this->line("✅ Aggiornato ruolo: {$roleName}");
            } else {
                $this->warn("⚠️ Ruolo non trovato: {$roleName}");
            }
        }

        // Add missing permissions if they don't exist
        $missingPermissions = [
            'manage permissions' => [
                'display_name' => 'Gestisci Permessi',
                'description' => 'Permette di gestire ruoli e permessi del sistema',
                'group' => 'system'
            ],
            'view system' => [
                'display_name' => 'Visualizza Sistema',
                'description' => 'Permette di visualizzare informazioni di sistema',
                'group' => 'system'
            ],
        ];

        foreach ($missingPermissions as $permissionName => $data) {
            if (!Permission::where('name', $permissionName)->exists()) {
                Permission::create(array_merge(['name' => $permissionName], $data));
                $this->line("✅ Creato nuovo permesso: {$permissionName}");
            }
        }

        // Add missing roles if they don't exist
        $missingRoles = [
            'admin' => [
                'display_name' => 'Amministratore',
                'description' => 'Amministratore del sistema con accesso a tutte le funzionalità principali. Gestisce eventi, utenti e contenuti.'
            ],
            'moderator' => [
                'display_name' => 'Moderatore',
                'description' => 'Modera contenuti e utenti. Può approvare/rifiutare richieste e gestire eventi.'
            ],
        ];

        foreach ($missingRoles as $roleName => $data) {
            if (!Role::where('name', $roleName)->exists()) {
                $role = Role::create(array_merge(['name' => $roleName], $data));

                // Assign permissions based on role
                switch ($roleName) {
                    case 'admin':
                        $role->givePermissionTo([
                            'create events', 'edit events', 'delete events', 'view events', 'manage events',
                            'send invitations', 'cancel invitations', 'approve requests', 'decline requests', 'view requests',
                            'send notifications', 'view notifications', 'manage notifications',
                            'view analytics', 'export analytics',
                            'manage users', 'view profiles', 'edit profiles',
                            'view system'
                        ]);
                        break;
                    case 'moderator':
                        $role->givePermissionTo([
                            'view events', 'edit events',
                            'approve requests', 'decline requests', 'view requests',
                            'send notifications', 'view notifications',
                            'view analytics',
                            'view profiles', 'edit profiles'
                        ]);
                        break;
                }

                $this->line("✅ Creato nuovo ruolo: {$roleName}");
            }
        }

        $this->info('✅ Aggiornamento completato con successo!');
        $this->info('📝 Tutti i ruoli e permessi ora hanno descrizioni dettagliate.');
    }
}
