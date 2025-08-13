<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\PoemSeeder;

class SeedPoems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:poems {--count=15 : Number of poems to create}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the database with sample poems including images';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🌹 Seeding poems with images...');
        
        try {
            $seeder = new PoemSeeder();
            $seeder->run();
            
            $this->info('✅ Poems seeded successfully!');
            $this->info('📝 Created 15 poems with beautiful images');
            $this->info('🎨 Images used from assets/images/blog-app/ and assets/images/placeholder/');
            
            // Mostra alcune poesie create
            $this->info('');
            $this->info('📖 Sample poems created:');
            $poems = \App\Models\Poem::latest()->take(5)->get(['title', 'thumbnail', 'is_featured']);
            foreach ($poems as $poem) {
                $status = $poem->is_featured ? '⭐ Featured' : '📄 Regular';
                $this->line("  • {$poem->title} ({$status}) - {$poem->thumbnail}");
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Error seeding poems: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
