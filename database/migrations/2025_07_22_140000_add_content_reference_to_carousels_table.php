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
        Schema::table('carousels', function (Blueprint $table) {
            // Campi per contenuti esistenti
            $table->string('content_type')->nullable()->after('end_date'); // 'video', 'event', 'user', 'snap'
            $table->unsignedBigInteger('content_id')->nullable()->after('content_type'); // ID del contenuto referenziato
            $table->string('content_title')->nullable()->after('content_id'); // Titolo del contenuto (cache)
            $table->string('content_description')->nullable()->after('content_title'); // Descrizione del contenuto (cache)
            $table->string('content_image_url')->nullable()->after('content_description'); // URL immagine del contenuto (cache)
            $table->string('content_url')->nullable()->after('content_image_url'); // URL del contenuto (cache)

            // Indici per performance
            $table->index(['content_type', 'content_id']);
            $table->index('content_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carousels', function (Blueprint $table) {
            $table->dropIndex(['content_type', 'content_id']);
            $table->dropIndex('content_type');
            $table->dropColumn([
                'content_type',
                'content_id',
                'content_title',
                'content_description',
                'content_image_url',
                'content_url'
            ]);
        });
    }
};
