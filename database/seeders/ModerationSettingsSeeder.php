<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class ModerationSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $moderationSettings = [
            // Video
            [
                'key' => 'moderation.videos.auto_approve',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'moderation',
                'display_name' => 'Auto-approva Video',
                'description' => 'I video vengono approvati automaticamente senza moderazione manuale',
                'is_public' => false,
            ],
            [
                'key' => 'moderation.videos.require_moderation',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'moderation',
                'display_name' => 'Richiedi Moderazione Video',
                'description' => 'I video devono essere moderati prima della pubblicazione',
                'is_public' => false,
            ],
            [
                'key' => 'moderation.videos.max_pending_per_user',
                'value' => '5',
                'type' => 'integer',
                'group' => 'moderation',
                'display_name' => 'Max Video in Attesa per Utente',
                'description' => 'Numero massimo di video in attesa di moderazione per utente',
                'is_public' => false,
            ],

            // Poesie
            [
                'key' => 'moderation.poems.auto_approve',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'moderation',
                'display_name' => 'Auto-approva Poesie',
                'description' => 'Le poesie vengono approvate automaticamente senza moderazione manuale',
                'is_public' => false,
            ],
            [
                'key' => 'moderation.poems.require_moderation',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'moderation',
                'display_name' => 'Richiedi Moderazione Poesie',
                'description' => 'Le poesie devono essere moderate prima della pubblicazione',
                'is_public' => false,
            ],
            [
                'key' => 'moderation.poems.max_pending_per_user',
                'value' => '10',
                'type' => 'integer',
                'group' => 'moderation',
                'display_name' => 'Max Poesie in Attesa per Utente',
                'description' => 'Numero massimo di poesie in attesa di moderazione per utente',
                'is_public' => false,
            ],

            // Eventi
            [
                'key' => 'moderation.events.auto_approve',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'moderation',
                'display_name' => 'Auto-approva Eventi',
                'description' => 'Gli eventi vengono approvati automaticamente senza moderazione manuale',
                'is_public' => false,
            ],
            [
                'key' => 'moderation.events.require_moderation',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'moderation',
                'display_name' => 'Richiedi Moderazione Eventi',
                'description' => 'Gli eventi devono essere moderati prima della pubblicazione',
                'is_public' => false,
            ],
            [
                'key' => 'moderation.events.max_pending_per_user',
                'value' => '3',
                'type' => 'integer',
                'group' => 'moderation',
                'display_name' => 'Max Eventi in Attesa per Utente',
                'description' => 'Numero massimo di eventi in attesa di moderazione per utente',
                'is_public' => false,
            ],

            // Foto
            [
                'key' => 'moderation.photos.auto_approve',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'moderation',
                'display_name' => 'Auto-approva Foto',
                'description' => 'Le foto vengono approvate automaticamente senza moderazione manuale',
                'is_public' => false,
            ],
            [
                'key' => 'moderation.photos.require_moderation',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'moderation',
                'display_name' => 'Richiedi Moderazione Foto',
                'description' => 'Le foto devono essere moderate prima della pubblicazione',
                'is_public' => false,
            ],
            [
                'key' => 'moderation.photos.max_pending_per_user',
                'value' => '20',
                'type' => 'integer',
                'group' => 'moderation',
                'display_name' => 'Max Foto in Attesa per Utente',
                'description' => 'Numero massimo di foto in attesa di moderazione per utente',
                'is_public' => false,
            ],

            // Carousel
            [
                'key' => 'moderation.carousels.auto_approve',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'moderation',
                'display_name' => 'Auto-approva Carousel',
                'description' => 'I carousel vengono approvati automaticamente senza moderazione manuale',
                'is_public' => false,
            ],
            [
                'key' => 'moderation.carousels.require_moderation',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'moderation',
                'display_name' => 'Richiedi Moderazione Carousel',
                'description' => 'I carousel devono essere moderati prima della pubblicazione',
                'is_public' => false,
            ],

            // Commenti Video
            [
                'key' => 'moderation.video_comments.auto_approve',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'moderation',
                'display_name' => 'Auto-approva Commenti Video',
                'description' => 'I commenti ai video vengono approvati automaticamente',
                'is_public' => false,
            ],
            [
                'key' => 'moderation.video_comments.require_moderation',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'moderation',
                'display_name' => 'Richiedi Moderazione Commenti Video',
                'description' => 'I commenti ai video devono essere moderati prima della pubblicazione',
                'is_public' => false,
            ],

            // Commenti Poesie
            [
                'key' => 'moderation.poem_comments.auto_approve',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'moderation',
                'display_name' => 'Auto-approva Commenti Poesie',
                'description' => 'I commenti alle poesie vengono approvati automaticamente',
                'is_public' => false,
            ],
            [
                'key' => 'moderation.poem_comments.require_moderation',
                'value' => 'false',
                'type' => 'boolean',
                'group' => 'moderation',
                'display_name' => 'Richiedi Moderazione Commenti Poesie',
                'description' => 'I commenti alle poesie devono essere moderati prima della pubblicazione',
                'is_public' => false,
            ],

            // Impostazioni Generali
            [
                'key' => 'moderation.general.enable_moderation',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'moderation',
                'display_name' => 'Abilita Sistema Moderazione',
                'description' => 'Abilita il sistema di moderazione per tutti i contenuti',
                'is_public' => false,
            ],
            [
                'key' => 'moderation.general.notify_on_pending',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'moderation',
                'display_name' => 'Notifica Contenuti in Attesa',
                'description' => 'Invia notifiche quando ci sono contenuti in attesa di moderazione',
                'is_public' => false,
            ],
            [
                'key' => 'moderation.general.auto_reject_spam',
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'moderation',
                'display_name' => 'Auto-rifiuta Spam',
                'description' => 'Rifiuta automaticamente i contenuti rilevati come spam',
                'is_public' => false,
            ],
        ];

        foreach ($moderationSettings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('Impostazioni di moderazione inizializzate con successo!');
    }
}
