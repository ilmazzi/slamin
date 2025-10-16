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
        // Disabilita i controlli delle foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Elenco di tutte le possibili tabelle chat da eliminare
        $chatTables = [
            'chat_actions',
            'chat_attachments',
            'chat_conversations',
            'chat_groups',
            'chat_message_reads',
            'chat_messages',
            'chat_participants',
            'chat_rooms',
            'chat_message_reactions',
            'wirechat_actions',
            'wirechat_attachments',
            'wirechat_conversations',
            'wirechat_groups',
            'wirechat_messages',
            'wirechat_participants',
        ];

        foreach ($chatTables as $table) {
            if (Schema::hasTable($table)) {
                Schema::dropIfExists($table);
                echo "Tabella {$table} eliminata\n";
            }
        }

        // Riabilita i controlli delle foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Non possiamo ricreare le tabelle senza sapere la loro struttura originale
        // Questo metodo rimane vuoto intenzionalmente
    }
};
