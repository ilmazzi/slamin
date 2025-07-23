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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            
            // Informazioni base del task
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['todo', 'in_progress', 'review', 'testing', 'done'])->default('todo');
            
            // Categoria e tipo
            $table->enum('category', [
                'frontend', 'backend', 'database', 'design', 'testing', 'deployment',
                'documentation', 'bug_fix', 'feature', 'maintenance', 'optimization', 'security'
            ])->default('feature');
            
            // Assegnazione e responsabilità
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Date e scadenze
            $table->dateTime('due_date')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            
            // Stime e progresso
            $table->integer('estimated_hours')->nullable(); // Ore stimate
            $table->integer('actual_hours')->nullable(); // Ore effettive
            $table->integer('progress_percentage')->default(0); // Progresso 0-100
            
            // File e risorse
            $table->json('attachments')->nullable(); // File allegati
            $table->json('links')->nullable(); // Link esterni (GitHub, Figma, etc.)
            
            // Tags e etichette
            $table->json('tags')->nullable(); // Tags personalizzati
            
            // Note e commenti
            $table->text('notes')->nullable(); // Note generali
            $table->text('review_notes')->nullable(); // Note di review
            
            // Relazioni con altri task
            $table->foreignId('parent_task_id')->nullable()->constrained('tasks')->onDelete('set null'); // Task padre
            $table->json('dependencies')->nullable(); // ID dei task dipendenti
            
            // Metadati
            $table->string('version')->nullable(); // Versione del sito
            $table->string('branch')->nullable(); // Branch Git
            $table->string('commit_hash')->nullable(); // Commit hash
            
            $table->timestamps();
            
            // Indici per performance
            $table->index(['status', 'priority']);
            $table->index(['assigned_to', 'status']);
            $table->index(['category', 'status']);
            $table->index(['due_date']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
