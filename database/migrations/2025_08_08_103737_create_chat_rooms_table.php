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
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // Nome per chat di gruppo
            $table->text('description')->nullable(); // Descrizione per chat di gruppo
            $table->string('type')->default('private'); // 'private' o 'group'
            $table->string('avatar')->nullable(); // Avatar per chat di gruppo
            $table->unsignedBigInteger('created_by')->nullable(); // Creatore della chat di gruppo
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indici
            $table->index(['type', 'is_active']);
            $table->index('last_message_at');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_rooms');
    }
};
