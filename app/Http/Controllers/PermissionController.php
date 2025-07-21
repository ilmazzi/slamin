<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'roles' => 'array'
        ]);

        try {
            $user->syncRoles($request->roles ?? []);

            return response()->json([
                'success' => true,
                'message' => 'Ruoli assegnati con successo!',
                'user' => $user->load('roles')
            ]);
        } catch (\Exception $e) {
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
}
