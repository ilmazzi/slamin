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
        Schema::create('unified_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('likeable_type');
            $table->unsignedBigInteger('likeable_id');
            $table->timestamps();

            // Indici per performance
            $table->unique(['user_id', 'likeable_type', 'likeable_id'], 'unified_likes_unique');
            $table->index(['likeable_type', 'likeable_id'], 'unified_likes_morph');
            $table->index('user_id', 'unified_likes_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unified_likes');
    }
};
