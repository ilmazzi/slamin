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
        
        // Elimina le tabelle chat vecchie nell'ordine corretto
        Schema::dropIfExists('chat_message_reactions');
        Schema::dropIfExists('chat_message_reads');
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_participants');
        Schema::dropIfExists('chat_rooms');
        
        // Riabilita i controlli delle foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Non possiamo ripristinare le tabelle perché non sappiamo la loro struttura originale
        // Se necessario, creare una migration separata per ricrearle
    }
};
