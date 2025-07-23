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
        Schema::create('task_comments', function (Blueprint $table) {
            $table->id();
            
            // Relazione con il task
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Contenuto del commento
            $table->text('content');
            $table->enum('type', ['comment', 'status_update', 'time_log', 'review'])->default('comment');
            
            // Metadati
            $table->json('metadata')->nullable(); // Dati aggiuntivi (ore lavorate, etc.)
            $table->boolean('is_internal')->default(false); // Commento interno vs pubblico
            
            $table->timestamps();
            
            // Indici
            $table->index(['task_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_comments');
    }
};
