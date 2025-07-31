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
        Schema::create('unified_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('viewable_type');
            $table->unsignedBigInteger('viewable_id');
            $table->timestamps();

            // Indici per performance
            $table->unique(['user_id', 'viewable_type', 'viewable_id'], 'unified_views_unique');
            $table->index(['viewable_type', 'viewable_id'], 'unified_views_morph');
            $table->index('user_id', 'unified_views_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unified_views');
    }
};
