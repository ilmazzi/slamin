<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['roles', 'permissions'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $roles = Role::all();
        $permissions = Permission::all();

        $stats = [
            'total' => User::count(),
            'active' => User::where('status', 'active')->count(),
            'inactive' => User::where('status', 'inactive')->count(),
            'admins' => User::role('admin')->count(),
        ];

        return view('admin.users.index', compact('users', 'roles', 'permissions', 'stats'));
    }

    public function show(User $user)
    {
        $user->load(['roles', 'permissions']);
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'nickname' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive',
            'roles' => 'array',
            'permissions' => 'array',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'nickname' => $request->nickname,
            'status' => $request->status,
        ]);

        // Sync roles
        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        // Sync direct permissions
        if ($request->has('permissions')) {
            $user->syncPermissions($request->permissions);
        }

        return response()->json([
            'success' => true,
            'message' => __('admin.user_updated_successfully')
        ]);
    }

    public function destroy(User $user)
    {
        if ($user->hasRole('admin') && User::role('admin')->count() <= 1) {
            return response()->json([
                'success' => false,
                'message' => __('admin.cannot_delete_last_admin')
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
                'message' => __('admin.user_deleted_successfully')
            ]);

        } catch (\Exception $e) {
            \Log::error('Errore durante l\'eliminazione utente', [
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

    public function bulkAssign(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'roles' => 'array',
            'permissions' => 'array',
        ]);

        $users = User::whereIn('id', $request->user_ids)->get();

        foreach ($users as $user) {
            if ($request->has('roles')) {
                $user->syncRoles($request->roles);
            }
            if ($request->has('permissions')) {
                $user->syncPermissions($request->permissions);
            }
        }

        return response()->json([
            'success' => true,
            'message' => __('admin.bulk_assignment_completed')
        ]);
    }

    public function export()
    {
        $users = User::with(['roles', 'permissions'])->get();
        
        $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, ['ID', 'Nome', 'Email', 'Nickname', 'Ruoli', 'Permessi Diretti', 'Stato', 'Data Registrazione']);
            
            // Data
            foreach ($users as $user) {
                $roles = $user->roles->pluck('display_name')->implode(', ');
                $permissions = $user->permissions->pluck('display_name')->implode(', ');
                
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->nickname ?? 'N/A',
                    $roles,
                    $permissions,
                    $user->status,
                    $user->created_at->format('d/m/Y H:i')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
                \Log::warning('PeerTube non configurato, impossibile eliminare account per utente ' . $user->id);
                return;
            }

            // Elimina l'utente da PeerTube
            $peerTubeService->deleteUser($user->peertube_user_id);
            
            \Log::info('Account PeerTube eliminato per utente ' . $user->id . ' (PeerTube User ID: ' . $user->peertube_user_id . ')');

        } catch (\Exception $e) {
            \Log::warning('Errore eliminazione account PeerTube per utente ' . $user->id . ': ' . $e->getMessage());
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
                \Log::info('Foto profilo eliminata per utente ' . $user->id . ': ' . $user->profile_photo);
            }

            // Elimina immagine banner
            if ($user->banner_image) {
                \Storage::disk('public')->delete($user->banner_image);
                \Log::info('Banner eliminato per utente ' . $user->id . ': ' . $user->banner_image);
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

            \Log::info('File eliminati per utente ' . $user->id);

        } catch (\Exception $e) {
            \Log::warning('Errore eliminazione file per utente ' . $user->id . ': ' . $e->getMessage());
            // Non blocchiamo l'eliminazione dell'utente se fallisce l'eliminazione file
        }
    }
} 