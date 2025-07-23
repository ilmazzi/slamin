<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first admin user
        $admin = User::whereHas('roles', function($query) {
            $query->where('name', 'admin');
        })->first();

        if (!$admin) {
            $admin = User::first();
        }

        if (!$admin) {
            return;
        }

        // Create sample tasks
        $tasks = [
            [
                'title' => 'Implementare sistema di autenticazione',
                'description' => 'Creare sistema di login e registrazione con validazione completa',
                'priority' => 'high',
                'status' => 'todo',
                'category' => 'backend',
                'assigned_to' => $admin->id,
                'created_by' => $admin->id,
                'due_date' => now()->addDays(7),
                'estimated_hours' => 8,
                'progress_percentage' => 0,
                'notes' => 'Priorità alta per la sicurezza del sistema',
                'tags' => 'auth,security,backend'
            ],
            [
                'title' => 'Design responsive per mobile',
                'description' => 'Ottimizzare il design per dispositivi mobili e tablet',
                'priority' => 'medium',
                'status' => 'in_progress',
                'category' => 'frontend',
                'assigned_to' => $admin->id,
                'created_by' => $admin->id,
                'due_date' => now()->addDays(5),
                'estimated_hours' => 6,
                'progress_percentage' => 30,
                'notes' => 'Testare su diversi dispositivi',
                'tags' => 'responsive,mobile,design'
            ],
            [
                'title' => 'Ottimizzare query database',
                'description' => 'Analizzare e ottimizzare le query per migliorare le performance',
                'priority' => 'medium',
                'status' => 'review',
                'category' => 'database',
                'assigned_to' => $admin->id,
                'created_by' => $admin->id,
                'due_date' => now()->addDays(3),
                'estimated_hours' => 4,
                'progress_percentage' => 80,
                'notes' => 'Verificare indici e query complesse',
                'tags' => 'database,performance,optimization'
            ],
            [
                'title' => 'Test unitari per API',
                'description' => 'Scrivere test unitari per tutte le API endpoints',
                'priority' => 'low',
                'status' => 'testing',
                'category' => 'testing',
                'assigned_to' => $admin->id,
                'created_by' => $admin->id,
                'due_date' => now()->addDays(10),
                'estimated_hours' => 12,
                'progress_percentage' => 60,
                'notes' => 'Copertura test almeno 80%',
                'tags' => 'testing,api,unit-tests'
            ],
            [
                'title' => 'Aggiornare documentazione',
                'description' => 'Aggiornare la documentazione del progetto con le nuove funzionalità',
                'priority' => 'low',
                'status' => 'done',
                'category' => 'documentation',
                'assigned_to' => $admin->id,
                'created_by' => $admin->id,
                'due_date' => now()->subDays(2),
                'estimated_hours' => 3,
                'progress_percentage' => 100,
                'completed_at' => now()->subDays(1),
                'actual_hours' => 2.5,
                'notes' => 'Documentazione completata e pubblicata',
                'tags' => 'documentation,update'
            ],
            [
                'title' => 'Fix bug login mobile',
                'description' => 'Risolvere problema di login su dispositivi mobili',
                'priority' => 'urgent',
                'status' => 'todo',
                'category' => 'bug_fix',
                'assigned_to' => $admin->id,
                'created_by' => $admin->id,
                'due_date' => now()->addDays(1),
                'estimated_hours' => 2,
                'progress_percentage' => 0,
                'notes' => 'Bug critico segnalato da utenti',
                'tags' => 'bugfix,mobile,urgent'
            ],
            [
                'title' => 'Implementare notifiche push',
                'description' => 'Aggiungere sistema di notifiche push per eventi importanti',
                'priority' => 'high',
                'status' => 'in_progress',
                'category' => 'feature',
                'assigned_to' => $admin->id,
                'created_by' => $admin->id,
                'due_date' => now()->addDays(14),
                'estimated_hours' => 10,
                'progress_percentage' => 45,
                'notes' => 'Integrare con servizio esterno',
                'tags' => 'notifications,push,feature'
            ]
        ];

        foreach ($tasks as $taskData) {
            Task::create($taskData);
        }
    }
}
