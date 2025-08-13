<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Report;
use App\Models\ModerationConversation;
use App\Models\ModerationMessage;

class DeployModerationSystem extends Command
{
    protected $signature = 'deploy:moderation {--force : Force deployment without confirmation}';
    protected $description = 'Deploy moderation system to production';

    public function handle()
    {
        $this->info('🚀 Deploying Moderation System to Production');
        $this->newLine();

        if (!$this->option('force')) {
            if (!$this->confirm('Are you sure you want to deploy the moderation system to production?')) {
                $this->info('❌ Deployment cancelled');
                return;
            }
        }

        $this->info('📋 Starting deployment process...');
        $this->newLine();

        // 1. Verifica migrazioni
        $this->info('1️⃣ Checking migrations...');
        $this->checkMigrations();

        // 2. Esegui seeder
        $this->info('2️⃣ Running production seeder...');
        $this->runSeeder();

        // 3. Crea conversazioni mancanti
        $this->info('3️⃣ Creating missing conversations...');
        $this->createMissingConversations();

        // 4. Verifica sistema
        $this->info('4️⃣ Verifying system...');
        $this->verifySystem();

        $this->newLine();
        $this->info('✅ Moderation system deployed successfully!');
        $this->newLine();
        
        $this->info('📋 Next steps:');
        $this->line('  - Test the moderation dashboard: /admin/moderation');
        $this->line('  - Test conversation system: /moderation/conversation/{report_id}');
        $this->line('  - Configure auto-approval settings if needed');
        $this->line('  - Assign moderator roles to users');
    }

    /**
     * Verifica che tutte le migrazioni siano eseguite
     */
    private function checkMigrations(): void
    {
        $pendingMigrations = \Artisan::call('migrate:status');
        
        if (str_contains($pendingMigrations, 'Pending')) {
            $this->error('❌ There are pending migrations!');
            $this->line('Run: php artisan migrate');
            exit(1);
        }
        
        $this->info('✅ All migrations are up to date');
    }

    /**
     * Esegue il seeder di produzione
     */
    private function runSeeder(): void
    {
        try {
            \Artisan::call('db:seed', ['--class' => 'ProductionModerationSeeder']);
            $this->info('✅ Production seeder completed');
        } catch (\Exception $e) {
            $this->error('❌ Error running seeder: ' . $e->getMessage());
            exit(1);
        }
    }

    /**
     * Crea conversazioni mancanti per segnalazioni esistenti
     */
    private function createMissingConversations(): void
    {
        $reports = Report::whereDoesntHave('conversation')->get();
        
        if ($reports->isEmpty()) {
            $this->info('✅ All reports already have conversations');
            return;
        }

        $this->info("📝 Creating conversations for {$reports->count()} reports...");
        
        $bar = $this->output->createProgressBar($reports->count());
        $bar->start();
        
        foreach ($reports as $report) {
            try {
                $conversation = ModerationConversation::createForReport($report);
                
                ModerationMessage::createSystemMessage(
                    $conversation,
                    "Conversazione aperta per la segnalazione del contenuto \"{$report->reportable_title}\""
                );
                
                $bar->advance();
            } catch (\Exception $e) {
                $this->error("\n❌ Error for report {$report->id}: " . $e->getMessage());
            }
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('✅ Conversations created successfully');
    }

    /**
     * Verifica che il sistema sia funzionante
     */
    private function verifySystem(): void
    {
        $this->info('🔍 Verifying system components...');
        
        // Verifica modelli
        $reportsCount = Report::count();
        $conversationsCount = ModerationConversation::count();
        $messagesCount = ModerationMessage::count();
        
        $this->line("  - Reports: {$reportsCount}");
        $this->line("  - Conversations: {$conversationsCount}");
        $this->line("  - Messages: {$messagesCount}");
        
        // Verifica route
        $this->info('🔗 Verifying routes...');
        $routes = [
            'admin.moderation.index' => '/admin/moderation',
            'moderation.conversation' => '/moderation/conversation/{report}',
        ];
        
        foreach ($routes as $name => $path) {
            try {
                $url = route($name, ['report' => 1]);
                $this->line("  - {$name}: ✅");
            } catch (\Exception $e) {
                $this->error("  - {$name}: ❌");
            }
        }
        
        // Verifica configurazioni
        $this->info('⚙️ Verifying settings...');
        $settings = [
            'moderation.videos.auto_approve',
            'moderation.email_notifications',
            'moderation.enabled',
        ];
        
        foreach ($settings as $setting) {
            $value = \App\Models\SystemSetting::get($setting);
            $this->line("  - {$setting}: " . ($value ? 'Enabled' : 'Disabled'));
        }
        
        $this->info('✅ System verification completed');
    }
}
