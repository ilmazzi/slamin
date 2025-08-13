<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ProductionModerationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Setting up production moderation system...');

        // 1. Configurazione Auto-Approval
        $this->setupAutoApprovalSettings();

        // 2. Configurazione Notifiche
        $this->setupNotificationSettings();

        // 3. Configurazione Moderazione
        $this->setupModerationSettings();

        // 4. Verifica Ruoli e Permessi
        $this->verifyRolesAndPermissions();

        $this->command->info('✅ Production moderation system configured successfully!');
    }

    /**
     * Configura le impostazioni di auto-approval
     */
    private function setupAutoApprovalSettings(): void
    {
        $this->command->info('📋 Setting up auto-approval settings...');

        $autoApprovalSettings = [
            'moderation.videos.auto_approve' => false,
            'moderation.poems.auto_approve' => false,
            'moderation.events.auto_approve' => false,
            'moderation.photos.auto_approve' => false,
            'moderation.articles.auto_approve' => false,
            'moderation.carousels.auto_approve' => false,
            'moderation.video_comments.auto_approve' => false,
            'moderation.poem_comments.auto_approve' => false,
        ];

        foreach ($autoApprovalSettings as $key => $value) {
            SystemSetting::set($key, $value);
            $this->command->line("  - {$key}: " . ($value ? 'Enabled' : 'Disabled'));
        }
    }

    /**
     * Configura le impostazioni delle notifiche
     */
    private function setupNotificationSettings(): void
    {
        $this->command->info('📧 Setting up notification settings...');

        $notificationSettings = [
            'moderation.email_notifications' => true,
            'moderation.items_per_page' => 20,
            'moderation.reports_retention_days' => 30,
        ];

        foreach ($notificationSettings as $key => $value) {
            SystemSetting::set($key, $value);
            $this->command->line("  - {$key}: {$value}");
        }
    }

    /**
     * Configura le impostazioni di moderazione
     */
    private function setupModerationSettings(): void
    {
        $this->command->info('🛡️ Setting up moderation settings...');

        $moderationSettings = [
            'moderation.enabled' => true,
            'moderation.require_approval' => true,
            'moderation.auto_delete_rejected' => false,
            'moderation.retention_days' => 90,
        ];

        foreach ($moderationSettings as $key => $value) {
            SystemSetting::set($key, $value);
            $this->command->line("  - {$key}: {$value}");
        }
    }

    /**
     * Verifica e crea ruoli e permessi necessari
     */
    private function verifyRolesAndPermissions(): void
    {
        $this->command->info('👥 Verifying roles and permissions...');

        // Verifica ruolo Admin
        $adminRole = Role::firstOrCreate(['name' => 'admin'], [
            'display_name' => 'Amministratore',
            'description' => 'Amministratore del sistema con tutti i permessi'
        ]);

        // Verifica ruolo Moderator
        $moderatorRole = Role::firstOrCreate(['name' => 'moderator'], [
            'display_name' => 'Moderatore',
            'description' => 'Moderatore con permessi di moderazione contenuti'
        ]);

        // Permessi di moderazione
        $moderationPermissions = [
            'moderation.view' => 'Visualizzare dashboard moderazione',
            'moderation.approve' => 'Approvare contenuti',
            'moderation.reject' => 'Rifiutare contenuti',
            'moderation.investigate' => 'Mettere in investigazione',
            'moderation.settings' => 'Gestire impostazioni moderazione',
            'moderation.reports' => 'Gestire segnalazioni',
            'moderation.conversations' => 'Gestire conversazioni moderazione',
        ];

        foreach ($moderationPermissions as $permission => $description) {
            Permission::firstOrCreate(['name' => $permission], [
                'display_name' => $description,
                'description' => $description
            ]);
        }

        // Assegna tutti i permessi di moderazione al ruolo admin
        $adminRole->givePermissionTo(array_keys($moderationPermissions));

        // Assegna permessi di moderazione al ruolo moderator (escluso settings)
        $moderatorPermissions = array_filter(
            array_keys($moderationPermissions),
            fn($perm) => $perm !== 'moderation.settings'
        );
        $moderatorRole->givePermissionTo($moderatorPermissions);

        $this->command->line("  - Admin role: {$adminRole->display_name}");
        $this->command->line("  - Moderator role: {$moderatorRole->display_name}");
        $this->command->line("  - Permissions created: " . count($moderationPermissions));
    }
}
