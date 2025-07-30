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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // Nome della chat (per chat di gruppo)
            $table->enum('type', ['private', 'group', 'general'])->default('private');
            $table->unsignedBigInteger('group_id')->nullable(); // Per chat di gruppo specifico
            $table->unsignedBigInteger('created_by')->nullable(); // Chi ha creato la chat
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();

            // Indici
            $table->index(['type', 'group_id']);
            $table->index('created_by');
            $table->index('last_message_at');

            // Foreign keys
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
