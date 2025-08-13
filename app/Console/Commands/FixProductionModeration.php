<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Report;
use App\Models\ModerationConversation;
use App\Models\ModerationMessage;

class FixProductionModeration extends Command
{
    protected $signature = 'fix:production-moderation';
    protected $description = 'Fix production moderation system issues';
    
    private $systemUserId;

    public function handle()
    {
        $this->info('🔧 Fixing Production Moderation System');
        $this->newLine();

        // 1. Verifica e crea utente sistema
        $this->info('1️⃣ Checking system user...');
        $this->fixSystemUser();

        // 2. Ricrea conversazioni mancanti
        $this->info('2️⃣ Recreating missing conversations...');
        $this->recreateConversations();

        // 3. Verifica sistema
        $this->info('3️⃣ Verifying system...');
        $this->verifySystem();

        $this->newLine();
        $this->info('✅ Production moderation system fixed!');
    }

    /**
     * Verifica e crea l'utente sistema se mancante
     */
    private function fixSystemUser(): void
    {
        // Prima cerca un utente sistema esistente
        $systemUser = User::where('email', 'sistema@slamin.local')->first();
        
        if (!$systemUser) {
            $this->info('📝 Creating system user...');
            
            // Prova a creare con ID 1, se non funziona usa auto-increment
            try {
                $systemUser = User::create([
                    'id' => 1,
                    'name' => 'Sistema',
                    'email' => 'sistema@slamin.local',
                    'password' => bcrypt('system_password_' . time()),
                    'email_verified_at' => now(),
                    'is_active' => false,
                ]);
            } catch (\Exception $e) {
                // Se ID 1 esiste già, crea con auto-increment
                $systemUser = User::create([
                    'name' => 'Sistema',
                    'email' => 'sistema@slamin.local',
                    'password' => bcrypt('system_password_' . time()),
                    'email_verified_at' => now(),
                    'is_active' => false,
                ]);
            }
            
            $this->info("✅ System user created with ID: {$systemUser->id}");
        } else {
            $this->info("✅ System user already exists with ID: {$systemUser->id}");
        }
        
        // Salva l'ID dell'utente sistema per usarlo nei messaggi
        $this->systemUserId = $systemUser->id;
    }

    /**
     * Ricrea le conversazioni mancanti
     */
    private function recreateConversations(): void
    {
        // Elimina conversazioni esistenti che potrebbero essere corrotte
        ModerationMessage::query()->delete();
        ModerationConversation::query()->delete();
        
        $this->info('🗑️ Cleared existing conversations and messages');
        
        // Ricrea conversazioni per tutte le segnalazioni
        $reports = Report::all();
        
        if ($reports->isEmpty()) {
            $this->info('ℹ️ No reports found');
            return;
        }

        $this->info("📝 Creating conversations for {$reports->count()} reports...");
        
        $bar = $this->output->createProgressBar($reports->count());
        $bar->start();
        
        foreach ($reports as $report) {
            try {
                // Crea la conversazione
                $conversation = ModerationConversation::createForReport($report);
                
                // Crea il messaggio di sistema iniziale
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
        $this->info('✅ Conversations recreated successfully');
    }

    /**
     * Verifica che il sistema sia funzionante
     */
    private function verifySystem(): void
    {
        $this->info('🔍 Verifying system components...');
        
        // Verifica utente sistema
        $systemUser = User::find(1);
        if ($systemUser) {
            $this->info("✅ System user exists: {$systemUser->name}");
        } else {
            $this->error("❌ System user missing!");
        }
        
        // Verifica modelli
        $reportsCount = Report::count();
        $conversationsCount = ModerationConversation::count();
        $messagesCount = ModerationMessage::count();
        
        $this->line("  - Reports: {$reportsCount}");
        $this->line("  - Conversations: {$conversationsCount}");
        $this->line("  - Messages: {$messagesCount}");
        
        // Verifica che ogni report abbia una conversazione
        $reportsWithoutConversation = Report::whereDoesntHave('conversation')->count();
        if ($reportsWithoutConversation > 0) {
            $this->error("❌ {$reportsWithoutConversation} reports without conversations");
        } else {
            $this->info("✅ All reports have conversations");
        }
        
        $this->info('✅ System verification completed');
    }
}
