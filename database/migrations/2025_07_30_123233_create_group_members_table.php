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
        Schema::create('group_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('role', ['admin', 'moderator', 'member'])->default('member');
            $table->unsignedBigInteger('invited_by')->nullable(); // Chi ha invitato questo membro
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();
            
            // Indici
            $table->index('group_id');
            $table->index('user_id');
            $table->index('role');
            $table->index(['group_id', 'user_id']);
            $table->index(['group_id', 'role']);
            
            // Foreign keys
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('invited_by')->references('id')->on('users')->onDelete('set null');
            
            // Unico: un utente può essere membro di un gruppo una sola volta
            $table->unique(['group_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_members');
    }
};
