<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\SystemSetting;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Impostazioni per i like
        SystemSetting::updateOrCreate(
            ['key' => 'social_enable_likes'],
            [
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'social',
                'display_name' => 'Abilita Like',
                'description' => 'Permette agli utenti di mettere like ai contenuti',
                'is_public' => false,
            ]
        );

        SystemSetting::updateOrCreate(
            ['key' => 'social_likeable_content'],
            [
                'value' => json_encode(['video', 'photo', 'poem', 'article', 'event', 'comment']),
                'type' => 'json',
                'group' => 'social',
                'display_name' => 'Contenuti Likeabili',
                'description' => 'Tipi di contenuto su cui è possibile mettere like',
                'is_public' => false,
            ]
        );

        // Impostazioni per i commenti
        SystemSetting::updateOrCreate(
            ['key' => 'social_enable_comments'],
            [
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'social',
                'display_name' => 'Abilita Commenti',
                'description' => 'Permette agli utenti di commentare i contenuti',
                'is_public' => false,
            ]
        );

        SystemSetting::updateOrCreate(
            ['key' => 'social_commentable_content'],
            [
                'value' => json_encode(['video', 'photo', 'poem', 'article', 'event']),
                'type' => 'json',
                'group' => 'social',
                'display_name' => 'Contenuti Commentabili',
                'description' => 'Tipi di contenuto su cui è possibile commentare',
                'is_public' => false,
            ]
        );

        SystemSetting::updateOrCreate(
            ['key' => 'social_auto_approve_comments'],
            [
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'social',
                'display_name' => 'Approvazione Automatica Commenti',
                'description' => 'I commenti vengono approvati automaticamente',
                'is_public' => false,
            ]
        );

        // Impostazioni per le notifiche
        SystemSetting::updateOrCreate(
            ['key' => 'social_enable_notifications'],
            [
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'social',
                'display_name' => 'Abilita Notifiche Social',
                'description' => 'Invia notifiche per like e commenti',
                'is_public' => false,
            ]
        );

        SystemSetting::updateOrCreate(
            ['key' => 'social_notification_types'],
            [
                'value' => json_encode(['like', 'comment', 'snap']),
                'type' => 'json',
                'group' => 'social',
                'display_name' => 'Tipi di Notifica',
                'description' => 'Tipi di interazione che generano notifiche',
                'is_public' => false,
            ]
        );

        // Impostazioni per le visualizzazioni
        SystemSetting::updateOrCreate(
            ['key' => 'social_enable_views'],
            [
                'value' => 'true',
                'type' => 'boolean',
                'group' => 'social',
                'display_name' => 'Abilita Visualizzazioni',
                'description' => 'Traccia le visualizzazioni dei contenuti',
                'is_public' => false,
            ]
        );

        SystemSetting::updateOrCreate(
            ['key' => 'social_viewable_content'],
            [
                'value' => json_encode(['video', 'photo', 'poem', 'article', 'event']),
                'type' => 'json',
                'group' => 'social',
                'display_name' => 'Contenuti Tracciabili',
                'description' => 'Tipi di contenuto per cui tracciare le visualizzazioni',
                'is_public' => false,
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $socialSettings = [
            'social_enable_likes',
            'social_likeable_content',
            'social_enable_comments',
            'social_commentable_content',
            'social_auto_approve_comments',
            'social_enable_notifications',
            'social_notification_types',
            'social_enable_views',
            'social_viewable_content',
        ];

        SystemSetting::whereIn('key', $socialSettings)->delete();
    }
};
