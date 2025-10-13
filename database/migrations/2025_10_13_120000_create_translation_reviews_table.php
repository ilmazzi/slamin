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
        Schema::create('translation_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('language', 10); // es: 'en', 'fr', 'de'
            $table->string('file', 100); // es: 'admin', 'poems', 'events'
            $table->string('key', 255); // es: 'welcome_message', 'create.title'
            $table->boolean('is_reviewed')->default(false);
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('notes')->nullable(); // Note del traduttore
            $table->timestamps();

            // Indici per performance
            $table->unique(['language', 'file', 'key']);
            $table->index(['language', 'file', 'is_reviewed']);
            $table->index('reviewed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translation_reviews');
    }
};

