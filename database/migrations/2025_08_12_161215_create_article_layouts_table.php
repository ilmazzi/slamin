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
        Schema::create('article_layouts', function (Blueprint $table) {
            $table->id();
            $table->string('position'); // banner, column1, column2, horizontal1, horizontal2, sidebar1-5
            $table->foreignId('article_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable(); // Per configurazioni aggiuntive
            $table->timestamps();
            
            $table->unique(['position', 'order']);
            $table->index(['is_active', 'order']);
            $table->index('position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_layouts');
    }
};
