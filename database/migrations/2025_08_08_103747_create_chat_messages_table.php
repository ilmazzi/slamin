<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_room_id');
            $table->unsignedBigInteger('sender_id');
            $table->text('content');
            $table->string('message_type')->default('text'); // 'text', 'image', 'file', 'audio', 'video'
            $table->string('file_path')->nullable(); // Percorso file per messaggi non testuali
            $table->string('file_name')->nullable(); // Nome originale del file
            $table->string('file_size')->nullable(); // Dimensione file in bytes
            $table->string('file_type')->nullable(); // MIME type del file
            $table->json('metadata')->nullable(); // Metadati aggiuntivi (durata audio/video, dimensioni immagine, etc.)
            $table->unsignedBigInteger('reply_to')->nullable(); // ID messaggio a cui si risponde
            $table->boolean('is_edited')->default(false);
            $table->timestamp('edited_at')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

            // Indici
            $table->index(['chat_room_id', 'created_at']);
            $table->index('sender_id');
            $table->index('message_type');
            $table->index('reply_to');
            $table->foreign('chat_room_id')->references('id')->on('chat_rooms')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reply_to')->references('id')->on('chat_messages')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
