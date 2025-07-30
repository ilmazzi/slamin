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
            $table->unsignedBigInteger('chat_id');
            $table->unsignedBigInteger('user_id');
            $table->text('message');
            $table->string('file_path')->nullable(); // Percorso del file caricato
            $table->string('file_name')->nullable(); // Nome originale del file
            $table->string('file_type')->nullable(); // Tipo MIME del file
            $table->integer('file_size')->nullable(); // Dimensione in bytes
            $table->boolean('is_system_message')->default(false); // Per messaggi di sistema
            $table->boolean('is_edited')->default(false);
            $table->timestamp('edited_at')->nullable();
            $table->timestamps();

            // Indici
            $table->index('chat_id');
            $table->index('user_id');
            $table->index('created_at');
            $table->index(['chat_id', 'created_at']);

            // Foreign keys
            $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
