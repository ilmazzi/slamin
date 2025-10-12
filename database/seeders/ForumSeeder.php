<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Subreddit;
use App\Models\ForumPost;
use App\Models\ForumComment;
use App\Models\User;

class ForumSeeder extends Seeder
{
    public function run(): void
    {
        $admin = DB::table('users')->where('email', 'davide.mazzitelli84@gmail.com')->first();
        
        if (!$admin) {
            $admin = DB::table('users')->first();
        }

        if (!$admin) {
            $this->command->error('No users found! Cannot create subreddits.');
            return;
        }

        // Create initial subreddits
        $poetrySubreddit = Subreddit::where('slug', 'poetry')->first();
        $slamSubreddit = Subreddit::where('slug', 'poetry-slam')->first();
        $critiqueSubreddit = Subreddit::where('slug', 'poetry-critique')->first();

        if (!$poetrySubreddit) {
            $poetrySubreddit = Subreddit::create([
                'name' => 'Poetry',
                'slug' => 'poetry',
                'description' => 'Discussioni generali su poesia, poeti e opere poetiche',
                'rules' => "1. Rispetta tutti gli utenti\n2. Contenuti solo inerenti alla poesia\n3. Evita spam",
                'color' => '#e74c3c',
                'created_by' => $admin->id,
                'is_active' => true,
                'is_private' => false,
            ]);

        }
        
        if (!$slamSubreddit) {
            $slamSubreddit = Subreddit::create([
                'name' => 'Poetry Slam',
                'slug' => 'poetry-slam',
                'description' => 'Eventi, performance e tutto sul Poetry Slam',
                'rules' => "1. Contenuti solo su Poetry Slam\n2. Condividi video e foto\n3. Supporta i poeti",
                'color' => '#f39c12',
                'created_by' => $admin->id,
                'is_active' => true,
                'is_private' => false,
            ]);

        }
        
        if (!$critiqueSubreddit) {
            $critiqueSubreddit = Subreddit::create([
                'name' => 'Poetry Critique',
                'slug' => 'poetry-critique',
                'description' => 'Feedback costruttivo sulle poesie',
                'rules' => "1. Feedback costruttivo\n2. Rispetta il lavoro altrui\n3. Sii specifico",
                'color' => '#27ae60',
                'created_by' => $admin->id,
                'is_active' => true,
                'is_private' => false,
            ]);

        }
        
        $this->command->info('✅ Subreddits ready');
        $this->command->info('ℹ️  Forum pronto (senza post di esempio - partiamo da zero!)');
    }
}
