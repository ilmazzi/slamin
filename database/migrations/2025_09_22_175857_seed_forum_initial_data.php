<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Inserisci configurazioni iniziali del forum (solo se non esistono)
        if (DB::table('forum_settings')->count() === 0) {
            DB::table('forum_settings')->insert([
            [
                'key' => 'comment_depth_limit',
                'value' => '3',
                'description' => 'Maximum depth for nested comments',
                'type' => 'integer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'max_image_size',
                'value' => '5242880', // 5MB in bytes
                'description' => 'Maximum image upload size in bytes',
                'type' => 'integer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'allowed_post_types',
                'value' => '["text", "link", "image"]',
                'description' => 'Allowed post types',
                'type' => 'json',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'require_approval_new_subreddit',
                'value' => 'false',
                'description' => 'Require admin approval for new subreddits',
                'type' => 'boolean',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'enable_notifications',
                'value' => 'true',
                'description' => 'Enable forum notifications',
                'type' => 'boolean',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'search_include_comments',
                'value' => 'true',
                'description' => 'Include comments in search results',
                'type' => 'boolean',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'translation_enabled',
                'value' => 'true',
                'description' => 'Enable comment translation feature',
                'type' => 'boolean',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'translation_api',
                'value' => 'google',
                'description' => 'Translation API provider (google, deepl)',
                'type' => 'string',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            ]);
        }

        // Crea subreddit iniziali per poetry (solo se non esistono)
        if (DB::table('subreddits')->count() === 0) {
            // Trova il primo admin o utente usando Spatie Permission
            $adminUser = DB::table('users')
                ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('roles.name', 'admin')
                ->where('model_has_roles.model_type', 'App\\Models\\User')
                ->select('users.*')
                ->first();

            if (!$adminUser) {
                $adminUser = DB::table('users')->first();
            }

            if ($adminUser) {
                DB::table('subreddits')->insert([
                    [
                        'name' => 'Poetry',
                        'slug' => 'poetry',
                        'description' => 'Discussioni generali su poesia, poeti e opere poetiche. Condividi le tue poesie preferite e parla di tutto ciò che riguarda il mondo della poesia.',
                        'rules' => "1. Rispetta tutti gli utenti\n2. Contenuti solo inerenti alla poesia\n3. Evita spam e contenuti offensivi\n4. Usa titoli descrittivi per i post",
                        'color' => '#e74c3c',
                        'created_by' => $adminUser->id,
                        'is_active' => true,
                        'is_private' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'name' => 'Poetry Slam',
                        'slug' => 'poetry-slam',
                        'description' => 'Eventi, performance e tutto ciò che riguarda il Poetry Slam. Condividi video, foto e discussioni sui tuoi slam preferiti.',
                        'rules' => "1. Contenuti solo su Poetry Slam\n2. Rispetta le regole degli eventi\n3. Condividi video e foto degli slam\n4. Supporta i poeti emergenti",
                        'color' => '#f39c12',
                        'created_by' => $adminUser->id,
                        'is_active' => true,
                        'is_private' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'name' => 'Poetry Critique',
                        'slug' => 'poetry-critique',
                        'description' => 'Feedback costruttivo e critiche sulle poesie. Un luogo sicuro per migliorare le proprie opere e aiutare altri poeti.',
                        'rules' => "1. Feedback sempre costruttivo\n2. Rispetta il lavoro degli altri\n3. Sii specifico nei commenti\n4. Evita critiche distruttive",
                        'color' => '#27ae60',
                        'created_by' => $adminUser->id,
                        'is_active' => true,
                        'is_private' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     * 
     * ATTENZIONE: Non eseguiamo truncate per evitare di perdere dati importanti
     * Se necessario, eliminare manualmente i dati seed dal database
     */
    public function down(): void
    {
        // NON fare truncate o delete - potrebbe causare perdita di dati critici
        // Se necessario rollback, eliminare manualmente:
        // DB::table('forum_settings')->whereIn('key', [...])->delete();
        // DB::table('subreddits')->whereIn('slug', ['poetry', 'poetry-slam', 'poetry-critique'])->delete();
    }
};
