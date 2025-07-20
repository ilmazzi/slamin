<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;

class PoetrySlamTestController extends Controller
{
    /**
     * Dashboard di test per mostrare il sistema poetry slam
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        $data = [
            'totalUsers' => User::count(),
            'totalRoles' => Role::count(),
            'totalPermissions' => Permission::count(),
            'userRoles' => $user ? $user->getDisplayRoles() : [],
            'userPermissions' => $user ? $user->getAllPermissions()->pluck('name')->toArray() : [],
            'allRoles' => Role::with('permissions')->get(),
            'recentUsers' => User::with('roles')->latest()->limit(5)->get(),
        ];

        return view('poetry_slam_test.dashboard', $data);
    }

    /**
     * Lista utenti con ruoli per testing
     */
    public function users()
    {
        $users = User::with('roles')->paginate(10);
        $roles = Role::all();

        return view('poetry_slam_test.users', compact('users', 'roles'));
    }

    /**
     * Test dei permessi in tempo reale
     */
    public function permissions()
    {
        $user = Auth::user();
        $allPermissions = Permission::all()->groupBy(function($permission) {
            return explode('.', $permission->name)[0]; // Group by module (events, users, etc.)
        });

        return view('poetry_slam_test.permissions', compact('user', 'allPermissions'));
    }

    /**
     * Pagina per testare login con utenti diversi
     */
    public function loginTest()
    {
        // Crea utenti di test se non esistono
        $this->createTestUsers();

        $testUsers = [
            ['email' => 'admin@poetryslam.com', 'role' => 'admin', 'password' => 'password'],
            ['email' => 'poet@poetryslam.com', 'role' => 'poet', 'password' => 'password'],
            ['email' => 'organizer@poetryslam.com', 'role' => 'organizer', 'password' => 'password'],
            ['email' => 'judge@poetryslam.com', 'role' => 'judge', 'password' => 'password'],
            ['email' => 'venue@poetryslam.com', 'role' => 'venue_owner', 'password' => 'password'],
            ['email' => 'tech@poetryslam.com', 'role' => 'technician', 'password' => 'password'],
            ['email' => 'audience@poetryslam.com', 'role' => 'audience', 'password' => 'password'],
        ];

        return view('poetry_slam_test.login_test', compact('testUsers'));
    }

    /**
     * Login veloce per test (NON per produzione!)
     */
    public function quickLogin(Request $request)
    {
        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        if ($user) {
            Auth::login($user);
                        $roleText = count($user->getDisplayRoles()) > 0 ? $user->getDisplayRoles()[0] : 'no role';
            return redirect()->route('poetry.test.dashboard')
                ->with('success', "Logged in as {$user->name} ({$roleText})");
        }

        return redirect()->back()->with('error', 'User not found');
    }

    /**
     * Logout per test
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('poetry.test.login')->with('success', 'Logged out successfully');
    }

    /**
     * Assegna ruolo a utente (per testing)
     */
    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name'
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->back()->with('success', "Role {$request->role} assigned to {$user->name}");
    }

    /**
     * Test specifico per un permesso
     */
    public function testPermission(Request $request)
    {
        $permission = $request->input('permission');
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        $hasPermission = $user->can($permission);

        return response()->json([
            'user' => $user->name,
            'permission' => $permission,
            'has_access' => $hasPermission,
            'roles' => $user->getDisplayRoles(),
        ]);
    }

    /**
     * Crea utenti di test per ogni ruolo
     */
    private function createTestUsers()
    {
        $testUsers = [
            ['name' => 'Admin User', 'email' => 'admin@poetryslam.com', 'role' => 'admin'],
            ['name' => 'Poet Artist', 'email' => 'poet@poetryslam.com', 'role' => 'poet'],
            ['name' => 'Event Organizer', 'email' => 'organizer@poetryslam.com', 'role' => 'organizer'],
            ['name' => 'Competition Judge', 'email' => 'judge@poetryslam.com', 'role' => 'judge'],
            ['name' => 'Venue Owner', 'email' => 'venue@poetryslam.com', 'role' => 'venue_owner'],
            ['name' => 'Sound Technician', 'email' => 'tech@poetryslam.com', 'role' => 'technician'],
            ['name' => 'Audience Member', 'email' => 'audience@poetryslam.com', 'role' => 'audience'],
        ];

        foreach ($testUsers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password'),
                    'status' => 'active'
                ]
            );

            if (!$user->hasRole($userData['role'])) {
                $user->assignRole($userData['role']);
            }
        }
    }
}
