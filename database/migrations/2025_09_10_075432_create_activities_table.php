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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 'view', 'upload', 'comment', 'like', 'create', 'update', 'delete', 'join', 'leave', etc.
            $table->string('subject_type'); // 'video', 'poem', 'article', 'event', 'group', 'comment', etc.
            $table->unsignedBigInteger('subject_id')->nullable(); // ID del soggetto dell'attività
            $table->string('action'); // 'viewed', 'uploaded', 'commented_on', 'liked', 'created', 'updated', etc.
            $table->text('description')->nullable(); // Descrizione dell'attività
            $table->json('metadata')->nullable(); // Dati aggiuntivi (titolo, URL, etc.)
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['type', 'created_at']);
            $table->index(['subject_type', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
