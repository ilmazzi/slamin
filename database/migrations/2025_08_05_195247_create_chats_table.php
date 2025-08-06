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
            $table->string('name')->nullable(); // Per chat di gruppo
            $table->enum('type', ['private', 'group'])->default('private');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->text('description')->nullable(); // Per chat di gruppo
            $table->string('avatar')->nullable(); // Per chat di gruppo
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Index per performance
            $table->index(['type', 'is_active']);
            $table->index('created_by');
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
