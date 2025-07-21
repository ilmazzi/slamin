<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SystemSetting;

class TestSystemSettings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:system-settings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa le impostazioni di sistema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Test Impostazioni Sistema');
        $this->newLine();

        // Test lettura impostazioni
        $this->info('📖 Test Lettura Impostazioni:');
        $this->line('  - Profile photo max size: ' . SystemSetting::get('profile_photo_max_size', 'N/A') . ' KB');
        $this->line('  - Video max size: ' . SystemSetting::get('video_max_size', 'N/A') . ' KB');
        $this->line('  - Default video limit: ' . SystemSetting::get('default_video_limit', 'N/A'));
        $this->line('  - Premium video limit: ' . SystemSetting::get('premium_video_limit', 'N/A'));
        $this->line('  - Maintenance mode: ' . (SystemSetting::get('maintenance_mode', false) ? 'ON' : 'OFF'));
        $this->line('  - Registration enabled: ' . (SystemSetting::get('registration_enabled', true) ? 'ON' : 'OFF'));

        $this->newLine();

        // Test scrittura impostazioni
        $this->info('✏️  Test Scrittura Impostazioni:');

        $originalValue = SystemSetting::get('default_video_limit', 3);
        $this->line('  - Valore originale default_video_limit: ' . $originalValue);

        // Cambia temporaneamente il valore
        SystemSetting::set('default_video_limit', 5, 'integer');
        $newValue = SystemSetting::get('default_video_limit', 3);
        $this->line('  - Nuovo valore default_video_limit: ' . $newValue);

        // Ripristina il valore originale
        SystemSetting::set('default_video_limit', $originalValue, 'integer');
        $restoredValue = SystemSetting::get('default_video_limit', 3);
        $this->line('  - Valore ripristinato default_video_limit: ' . $restoredValue);

        $this->newLine();

        // Test gruppi
        $this->info('📁 Test Gruppi Impostazioni:');
        $uploadSettings = SystemSetting::getGroup('upload');
        $this->line('  - Impostazioni upload: ' . count($uploadSettings) . ' elementi');

        $videoSettings = SystemSetting::getGroup('video');
        $this->line('  - Impostazioni video: ' . count($videoSettings) . ' elementi');

        $systemSettings = SystemSetting::getGroup('system');
        $this->line('  - Impostazioni sistema: ' . count($systemSettings) . ' elementi');

        $this->newLine();

        // Test cache
        $this->info('💾 Test Cache:');
        $this->line('  - Prima lettura: ' . SystemSetting::get('profile_photo_max_size', 'N/A'));
        $this->line('  - Seconda lettura (dalla cache): ' . SystemSetting::get('profile_photo_max_size', 'N/A'));

        $this->newLine();
        $this->info('✅ Test completato con successo!');

        return Command::SUCCESS;
    }
}
