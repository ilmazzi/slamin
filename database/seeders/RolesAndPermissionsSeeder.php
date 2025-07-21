<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions with descriptions
        $permissions = [
            // Event permissions
            [
                'name' => 'create events',
                'display_name' => 'Crea Eventi',
                'description' => 'Permette di creare nuovi eventi Poetry Slam nel sistema',
                'group' => 'events'
            ],
            [
                'name' => 'edit events',
                'display_name' => 'Modifica Eventi',
                'description' => 'Permette di modificare eventi esistenti',
                'group' => 'events'
            ],
            [
                'name' => 'delete events',
                'display_name' => 'Elimina Eventi',
                'description' => 'Permette di eliminare eventi dal sistema',
                'group' => 'events'
            ],
            [
                'name' => 'view events',
                'display_name' => 'Visualizza Eventi',
                'description' => 'Permette di visualizzare tutti gli eventi disponibili',
                'group' => 'events'
            ],
            [
                'name' => 'manage events',
                'display_name' => 'Gestisci Eventi',
                'description' => 'Controllo completo su tutti gli aspetti degli eventi',
                'group' => 'events'
            ],

            // Invitation permissions
            [
                'name' => 'send invitations',
                'display_name' => 'Invia Inviti',
                'description' => 'Permette di inviare inviti per partecipare agli eventi',
                'group' => 'invitations'
            ],
            [
                'name' => 'accept invitations',
                'display_name' => 'Accetta Inviti',
                'description' => 'Permette di accettare inviti ricevuti',
                'group' => 'invitations'
            ],
            [
                'name' => 'decline invitations',
                'display_name' => 'Rifiuta Inviti',
                'description' => 'Permette di rifiutare inviti ricevuti',
                'group' => 'invitations'
            ],
            [
                'name' => 'cancel invitations',
                'display_name' => 'Cancella Inviti',
                'description' => 'Permette di cancellare inviti già inviati',
                'group' => 'invitations'
            ],

            // Request permissions
            [
                'name' => 'send requests',
                'display_name' => 'Invia Richieste',
                'description' => 'Permette di inviare richieste di partecipazione agli eventi',
                'group' => 'requests'
            ],
            [
                'name' => 'approve requests',
                'display_name' => 'Approva Richieste',
                'description' => 'Permette di approvare richieste di partecipazione',
                'group' => 'requests'
            ],
            [
                'name' => 'decline requests',
                'display_name' => 'Rifiuta Richieste',
                'description' => 'Permette di rifiutare richieste di partecipazione',
                'group' => 'requests'
            ],
            [
                'name' => 'view requests',
                'display_name' => 'Visualizza Richieste',
                'description' => 'Permette di visualizzare tutte le richieste di partecipazione',
                'group' => 'requests'
            ],

            // Notification permissions
            [
                'name' => 'send notifications',
                'display_name' => 'Invia Notifiche',
                'description' => 'Permette di inviare notifiche agli utenti',
                'group' => 'notifications'
            ],
            [
                'name' => 'view notifications',
                'display_name' => 'Visualizza Notifiche',
                'description' => 'Permette di visualizzare le proprie notifiche',
                'group' => 'notifications'
            ],
            [
                'name' => 'manage notifications',
                'display_name' => 'Gestisci Notifiche',
                'description' => 'Controllo completo su tutte le notifiche del sistema',
                'group' => 'notifications'
            ],

            // Analytics permissions
            [
                'name' => 'view analytics',
                'display_name' => 'Visualizza Analytics',
                'description' => 'Permette di visualizzare statistiche e analisi degli eventi',
                'group' => 'analytics'
            ],
            [
                'name' => 'export analytics',
                'display_name' => 'Esporta Analytics',
                'description' => 'Permette di esportare dati analitici in vari formati',
                'group' => 'analytics'
            ],

            // User management
            [
                'name' => 'manage users',
                'display_name' => 'Gestisci Utenti',
                'description' => 'Controllo completo su tutti gli utenti del sistema',
                'group' => 'users'
            ],
            [
                'name' => 'view profiles',
                'display_name' => 'Visualizza Profili',
                'description' => 'Permette di visualizzare i profili degli altri utenti',
                'group' => 'users'
            ],
            [
                'name' => 'edit profiles',
                'display_name' => 'Modifica Profili',
                'description' => 'Permette di modificare il proprio profilo utente',
                'group' => 'users'
            ],

            // System permissions
            [
                'name' => 'manage permissions',
                'display_name' => 'Gestisci Permessi',
                'description' => 'Permette di gestire ruoli e permessi del sistema',
                'group' => 'system'
            ],
            [
                'name' => 'view system',
                'display_name' => 'Visualizza Sistema',
                'description' => 'Permette di visualizzare informazioni di sistema',
                'group' => 'system'
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Create roles with descriptions
        $roles = [
            [
                'name' => 'super-admin',
                'display_name' => 'Super Amministratore',
                'description' => 'Accesso completo a tutte le funzionalità del sistema. Può gestire utenti, eventi, permessi e configurazioni di sistema.'
            ],
            [
                'name' => 'admin',
                'display_name' => 'Amministratore',
                'description' => 'Amministratore del sistema con accesso a tutte le funzionalità principali. Gestisce eventi, utenti e contenuti.'
            ],
            [
                'name' => 'moderator',
                'display_name' => 'Moderatore',
                'description' => 'Modera contenuti e utenti. Può approvare/rifiutare richieste e gestire eventi.'
            ],
            [
                'name' => 'organizer',
                'display_name' => 'Organizzatore',
                'description' => 'Organizza e gestisce eventi Poetry Slam. Può creare eventi, inviare inviti e gestire partecipanti.'
            ],
            [
                'name' => 'poet',
                'display_name' => 'Poeta/Artista',
                'description' => 'Artista che partecipa agli eventi Poetry Slam. Può visualizzare eventi, accettare inviti e gestire il proprio profilo.'
            ],
            [
                'name' => 'judge',
                'display_name' => 'Giudice',
                'description' => 'Giudica le performance negli eventi. Ha accesso alle analytics per valutazioni e può partecipare agli eventi.'
            ],
            [
                'name' => 'technician',
                'display_name' => 'Tecnico',
                'description' => 'Supporto tecnico per gli eventi. Gestisce aspetti tecnici e può partecipare agli eventi.'
            ],
            [
                'name' => 'venue-owner',
                'display_name' => 'Proprietario Sede',
                'description' => 'Gestisce le sedi degli eventi. Può visualizzare eventi e gestire informazioni sulla sede.'
            ],
            [
                'name' => 'host',
                'display_name' => 'Presentatore',
                'description' => 'Presenta e conduce gli eventi Poetry Slam. Può partecipare agli eventi e gestire il proprio profilo.'
            ],
            [
                'name' => 'user',
                'display_name' => 'Utente',
                'description' => 'Utente base del sistema. Può visualizzare eventi, notifiche e gestire il proprio profilo.'
            ],
        ];

        foreach ($roles as $roleData) {
            $role = Role::create($roleData);

            // Assign permissions based on role
            switch ($roleData['name']) {
                case 'super-admin':
                    $role->givePermissionTo(Permission::all());
                    break;

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

                case 'organizer':
                    $role->givePermissionTo([
                        'create events', 'edit events', 'delete events', 'view events', 'manage events',
                        'send invitations', 'cancel invitations',
                        'approve requests', 'decline requests', 'view requests',
                        'send notifications', 'view notifications',
                        'view analytics', 'export analytics',
                        'view profiles'
                    ]);
                    break;

                case 'poet':
                    $role->givePermissionTo([
                        'view events',
                        'accept invitations', 'decline invitations',
                        'send requests',
                        'view notifications',
                        'view profiles', 'edit profiles'
                    ]);
                    break;

                case 'judge':
                    $role->givePermissionTo([
                        'view events',
                        'accept invitations', 'decline invitations',
                        'send requests',
                        'view notifications',
                        'view analytics',
                        'view profiles', 'edit profiles'
                    ]);
                    break;

                case 'technician':
                    $role->givePermissionTo([
                        'view events',
                        'accept invitations', 'decline invitations',
                        'send requests',
                        'view notifications',
                        'view profiles', 'edit profiles'
                    ]);
                    break;

                case 'venue-owner':
                    $role->givePermissionTo([
                        'view events',
                        'accept invitations', 'decline invitations',
                        'view notifications',
                        'view profiles', 'edit profiles'
                    ]);
                    break;

                case 'host':
                    $role->givePermissionTo([
                        'view events',
                        'accept invitations', 'decline invitations',
                        'send requests',
                        'view notifications',
                        'view profiles', 'edit profiles'
                    ]);
                    break;

                case 'user':
                    $role->givePermissionTo([
                        'view events',
                        'view notifications',
                        'view profiles', 'edit profiles'
                    ]);
                    break;
            }
        }

        echo "✅ Roles and Permissions created successfully!\n";
        echo "📝 Created roles: " . implode(', ', array_column($roles, 'name')) . "\n";
        echo "🔐 Created " . count($permissions) . " permissions\n";
        echo "📋 All roles and permissions now have detailed descriptions!\n";
    }
}
