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
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('Nessun utente trovato. Creazione di un utente di esempio...');
            $user = User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);
            $users = collect([$user]);
        }

        $categories = [
            'frontend', 'backend', 'database', 'design', 'testing', 
            'deployment', 'documentation', 'bug_fix', 'feature', 
            'maintenance', 'optimization', 'security'
        ];

        $priorities = ['low', 'medium', 'high', 'urgent'];
        $statuses = ['todo', 'in_progress', 'review', 'testing', 'done'];

        $taskTitles = [
            'Implementare sistema di autenticazione',
            'Creare dashboard responsive',
            'Ottimizzare query database',
            'Design nuovo logo aziendale',
            'Testare funzionalità di upload',
            'Deploy su server di produzione',
            'Scrivere documentazione API',
            'Risolvere bug nel login',
            'Aggiungere notifiche push',
            'Aggiornare dipendenze di sicurezza',
            'Migliorare performance frontend',
            'Implementare sistema di backup',
            'Creare template email',
            'Testare compatibilità mobile',
            'Configurare CI/CD pipeline'
        ];

        $taskDescriptions = [
            'Implementare un sistema di autenticazione sicuro con JWT tokens',
            'Creare una dashboard responsive che funzioni su tutti i dispositivi',
            'Ottimizzare le query del database per migliorare le performance',
            'Designare un nuovo logo aziendale che rappresenti i valori dell\'azienda',
            'Testare la funzionalità di upload file con diversi formati',
            'Deployare l\'applicazione sul server di produzione',
            'Scrivere la documentazione completa delle API',
            'Risolvere il bug che impedisce il login su alcuni browser',
            'Aggiungere notifiche push per gli aggiornamenti importanti',
            'Aggiornare le dipendenze per risolvere vulnerabilità di sicurezza',
            'Migliorare le performance del frontend ottimizzando il caricamento',
            'Implementare un sistema di backup automatico dei dati',
            'Creare template email professionali per le comunicazioni',
            'Testare la compatibilità dell\'app su dispositivi mobile',
            'Configurare la pipeline CI/CD per il deployment automatico'
        ];

        for ($i = 0; $i < 20; $i++) {
            $status = $statuses[array_rand($statuses)];
            $priority = $priorities[array_rand($priorities)];
            $category = $categories[array_rand($categories)];
            $titleIndex = array_rand($taskTitles);
            
            $task = Task::create([
                'title' => $taskTitles[$titleIndex],
                'description' => $taskDescriptions[$titleIndex],
                'priority' => $priority,
                'status' => $status,
                'category' => $category,
                'assigned_to' => $users->random()->id,
                'created_by' => $users->random()->id,
                'due_date' => now()->addDays(rand(1, 30)),
                'estimated_hours' => rand(1, 8),
                'progress_percentage' => $status === 'done' ? 100 : rand(0, 90),
                'attachments' => [], // Per ora senza immagini
                'tags' => ['web', 'development', 'feature'],
            ]);

            // Aggiungi date specifiche in base allo status
            if ($status === 'in_progress' || $status === 'review' || $status === 'testing' || $status === 'done') {
                $task->started_at = now()->subDays(rand(1, 10));
                $task->save();
            }

            if ($status === 'done') {
                $task->completed_at = now()->subDays(rand(1, 5));
                $task->save();
            }
        }

        $this->command->info('Task di esempio creati con successo!');
    }
}
