<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function __construct()
    {
        // Middleware will be applied in routes
    }

    /**
     * Mostra il pannello principale di gestione permessi
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        $users = User::with('roles')->get();

        $stats = [
            'total_roles' => $roles->count(),
            'total_permissions' => $permissions->count(),
            'total_users' => $users->count(),
            'roles_with_permissions' => $roles->filter(function($role) {
                return $role->permissions->count() > 0;
            })->count(),
        ];

        return view('permissions.index', compact('roles', 'permissions', 'users', 'stats'));
    }

    /**
     * Mostra la gestione dei ruoli
     */
    public function roles()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();

        return view('permissions.roles', compact('roles', 'permissions'));
    }

    /**
     * Mostra la gestione dei permessi
     */
    public function permissions()
    {
        $permissions = Permission::all();
        $roles = Role::all();

        return view('permissions.permissions', compact('permissions', 'roles'));
    }

    /**
     * Mostra la gestione degli utenti e loro ruoli
     */
    public function users()
    {
        $users = User::with('roles', 'permissions')->paginate(20);
        $roles = Role::all();
        $permissions = Permission::all();

        return view('permissions.users', compact('users', 'roles', 'permissions'));
    }

    /**
     * API endpoint per ottenere dati dei ruoli
     */
    public function rolesIndex(Request $request)
    {
        $roleId = $request->get('role_id');
        $role = Role::with('permissions')->find($roleId);

        if (!$role) {
            return response()->json(['error' => 'Ruolo non trovato'], 404);
        }

        return response()->json([
            'role' => $role,
            'permissions' => $role->permissions
        ]);
    }

    /**
     * Mostra il form di modifica ruolo
     */
    public function editRole(Role $role)
    {
        $permissions = Permission::all()->groupBy('group');
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return response()->json([
            'success' => true,
            'role' => $role,
            'permissions' => $permissions,
            'role_permissions' => $rolePermissions
        ]);
    }

    /**
     * Crea un nuovo ruolo
     */
    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'array'
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
            ]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ruolo creato con successo!',
                'role' => $role->load('permissions')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Errore durante la creazione del ruolo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Aggiorna un ruolo esistente
     */
    public function updateRole(Request $request, Role $role)
    {
        $request->validate([
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'permissions' => 'array'
        ]);

        DB::beginTransaction();
        try {
            $role->update([
                'display_name' => $request->display_name,
                'description' => $request->description,
            ]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ruolo aggiornato con successo!',
                'role' => $role->load('permissions')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'aggiornamento del ruolo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Elimina un ruolo
     */
    public function deleteRole(Role $role)
    {
        if ($role->users()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Impossibile eliminare il ruolo: ci sono utenti che lo utilizzano.'
            ], 400);
        }

        try {
            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Ruolo eliminato con successo!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'eliminazione del ruolo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API endpoint per ottenere dati degli utenti
     */
    public function usersIndex(Request $request)
    {
        $userId = $request->get('user_id');
        $roleId = $request->get('role_id');

        if ($userId) {
            $user = User::with('roles', 'permissions')->find($userId);
            if (!$user) {
                return response()->json(['error' => 'Utente non trovato'], 404);
            }
            return response()->json(['user' => $user]);
        }

        if ($roleId) {
            $role = Role::with('users')->find($roleId);
            if (!$role) {
                return response()->json(['error' => 'Ruolo non trovato'], 404);
            }
            return response()->json(['users' => $role->users]);
        }

        return response()->json(['error' => 'Parametro user_id o role_id richiesto'], 400);
    }

    /**
     * API endpoint per ottenere dati dei permessi
     */
    public function permissionsIndex(Request $request)
    {
        $permissionId = $request->get('permission_id');
        $permission = Permission::with('roles')->find($permissionId);

        if (!$permission) {
            return response()->json(['error' => 'Permesso non trovato'], 404);
        }

        return response()->json([
            'permission' => $permission,
            'roles' => $permission->roles
        ]);
    }

    /**
     * API endpoint per ottenere dati di un utente
     */
    public function getUser(User $user)
    {
        $user->load(['roles', 'permissions']);
        
        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Mostra il form di modifica permesso
     */
    public function editPermission(Permission $permission)
    {
        $roles = Role::all();
        $permissionRoles = $permission->roles->pluck('name')->toArray();

        return response()->json([
            'success' => true,
            'permission' => $permission,
            'roles' => $roles,
            'permission_roles' => $permissionRoles
        ]);
    }

    /**
     * Crea un nuovo permesso
     */
    public function storePermission(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'group' => 'nullable|string|max:100'
        ]);

        try {
            $permission = Permission::create([
                'name' => $request->name,
                'display_name' => $request->display_name,
                'description' => $request->description,
                'group' => $request->group,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permesso creato con successo!',
                'permission' => $permission
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore durante la creazione del permesso: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Aggiorna un permesso esistente
     */
    public function updatePermission(Request $request, Permission $permission)
    {
        $request->validate([
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'group' => 'nullable|string|max:100'
        ]);

        try {
            $permission->update([
                'display_name' => $request->display_name,
                'description' => $request->description,
                'group' => $request->group,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permesso aggiornato con successo!',
                'permission' => $permission
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'aggiornamento del permesso: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Elimina un permesso
     */
    public function deletePermission(Permission $permission)
    {
        if ($permission->roles()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Impossibile eliminare il permesso: è assegnato a dei ruoli.'
            ], 400);
        }

        try {
            $permission->delete();

            return response()->json([
                'success' => true,
                'message' => 'Permesso eliminato con successo!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'eliminazione del permesso: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assegna ruoli a un utente
     */
    public function assignUserRoles(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'nullable|array'
        ]);

        try {
            // Debug: log prima dell'assegnazione
            Log::info('Before syncRoles - User roles:', ['user_id' => $user->id, 'current_roles' => $user->roles->pluck('name')->toArray()]);
            Log::info('Request all data:', $request->all());
            Log::info('Roles to assign:', ['roles' => $request->roles]);
            
            // Gestiamo il caso in cui roles non viene inviato (nessun checkbox selezionato)
            $rolesToAssign = [];
            if ($request->has('roles') && is_array($request->roles)) {
                $rolesToAssign = $request->roles;
            }
            Log::info('Roles to assign (processed):', ['roles' => $rolesToAssign]);
            
            $user->syncRoles($rolesToAssign);
            $user->load('roles');
            
            // Debug: log dopo l'assegnazione
            Log::info('After syncRoles - User roles:', ['user_id' => $user->id, 'current_roles' => $user->roles->pluck('name')->toArray()]);

            return response()->json([
                'success' => true,
                'message' => 'Ruoli assegnati con successo!',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('Error in assignUserRoles:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'assegnazione dei ruoli: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assegna permessi diretti a un utente
     */
    public function assignUserPermissions(Request $request, User $user)
    {
        $request->validate([
            'permissions' => 'array'
        ]);

        try {
            $user->syncPermissions($request->permissions ?? []);

            return response()->json([
                'success' => true,
                'message' => 'Permessi assegnati con successo!',
                'user' => $user->load('permissions')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'assegnazione dei permessi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ottieni statistiche per il dashboard
     */
    public function getStats()
    {
        $stats = [
            'total_roles' => Role::count(),
            'total_permissions' => Permission::count(),
            'total_users' => User::count(),
            'roles_with_permissions' => Role::has('permissions')->count(),
            'users_with_roles' => User::has('roles')->count(),
            'recent_activities' => $this->getRecentActivities(),
        ];

        return response()->json($stats);
    }

    /**
     * Ottieni attività recenti
     */
    private function getRecentActivities()
    {
        // TODO: Implementare sistema di log attività
        return [];
    }

    /**
     * Elimina un utente e tutti i suoi dati associati
     */
    public function deleteUser(User $user)
    {
        if ($user->hasRole('admin') && User::role('admin')->count() <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Impossibile eliminare l\'ultimo amministratore.'
            ], 400);
        }

        try {
            // 1. Elimina account PeerTube se esiste
            if ($user->hasPeerTubeAccount()) {
                $this->deletePeerTubeAccount($user);
            }

            // 2. Elimina file associati
            $this->deleteUserFiles($user);

            // 3. Elimina l'utente dal database (cascade eliminerà tutti i dati associati)
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Utente eliminato con successo!'
            ]);

        } catch (\Exception $e) {
            Log::error('Errore durante l\'eliminazione utente', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'eliminazione: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Elimina l'account PeerTube dell'utente
     */
    private function deletePeerTubeAccount(User $user)
    {
        try {
            if (!$user->peertube_user_id) {
                return;
            }

            $peerTubeService = new \App\Services\PeerTubeService();
            
            if (!$peerTubeService->isConfigured()) {
                Log::warning('PeerTube non configurato, impossibile eliminare account per utente ' . $user->id);
                return;
            }

            // Elimina l'utente da PeerTube
            $peerTubeService->deleteUser($user->peertube_user_id);
            
            Log::info('Account PeerTube eliminato per utente ' . $user->id . ' (PeerTube User ID: ' . $user->peertube_user_id . ')');

        } catch (\Exception $e) {
            Log::warning('Errore eliminazione account PeerTube per utente ' . $user->id . ': ' . $e->getMessage());
            // Non blocchiamo l'eliminazione dell'utente se fallisce l'eliminazione PeerTube
        }
    }

    /**
     * Elimina tutti i file associati all'utente
     */
    private function deleteUserFiles(User $user)
    {
        try {
            // Elimina foto profilo
            if ($user->profile_photo) {
                \Storage::disk('public')->delete($user->profile_photo);
                Log::info('Foto profilo eliminata per utente ' . $user->id . ': ' . $user->profile_photo);
            }

            // Elimina immagine banner
            if ($user->banner_image) {
                \Storage::disk('public')->delete($user->banner_image);
                Log::info('Banner eliminato per utente ' . $user->id . ': ' . $user->banner_image);
            }

            // Elimina video dell'utente
            foreach ($user->videos as $video) {
                if ($video->video_path) {
                    \Storage::disk('public')->delete($video->video_path);
                }
                if ($video->thumbnail_path && !filter_var($video->thumbnail_path, FILTER_VALIDATE_URL)) {
                    \Storage::disk('public')->delete($video->thumbnail_path);
                }
            }

            // Elimina foto dell'utente
            foreach ($user->photos as $photo) {
                if ($photo->photo_path) {
                    \Storage::disk('public')->delete($photo->photo_path);
                }
            }

            Log::info('File eliminati per utente ' . $user->id);

        } catch (\Exception $e) {
            Log::warning('Errore eliminazione file per utente ' . $user->id . ': ' . $e->getMessage());
            // Non blocchiamo l'eliminazione dell'utente se fallisce l'eliminazione file
        }
    }
}
