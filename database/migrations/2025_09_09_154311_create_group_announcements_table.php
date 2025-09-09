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
        Schema::create('group_announcements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('author_id');
            $table->string('title');
            $table->text('content');
            $table->enum('visibility', ['public', 'members_only', 'admins_only'])->default('members_only');
            $table->boolean('is_pinned')->default(false);
            $table->boolean('has_poll')->default(false);
            $table->json('poll_options')->nullable(); // Opzioni del sondaggio
            $table->json('poll_votes')->nullable(); // Voti del sondaggio
            $table->timestamp('expires_at')->nullable(); // Scadenza annuncio
            $table->timestamps();
            
            // Indici
            $table->index(['group_id', 'visibility']);
            $table->index(['group_id', 'is_pinned']);
            $table->index('author_id');
            
            // Foreign keys
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_announcements');
    }
};
