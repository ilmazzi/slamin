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
        Schema::table('gigs', function (Blueprint $table) {
            // Tipo di gig: event o translation
            $table->enum('gig_type', ['event', 'translation'])->default('event')->after('id');

            // ID della poesia da tradurre (solo per gigs di traduzione)
            $table->unsignedBigInteger('poem_id')->nullable()->after('gig_type');

            // Lingue target per la traduzione (array JSON)
            $table->json('target_languages')->nullable()->after('poem_id');

            // Istruzioni specifiche per la traduzione
            $table->text('translation_instructions')->nullable()->after('target_languages');

            // Foreign key per poem_id
            $table->foreign('poem_id')->references('id')->on('poems')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gigs', function (Blueprint $table) {
            $table->dropForeign(['poem_id']);
            $table->dropColumn([
                'gig_type',
                'poem_id',
                'target_languages',
                'translation_instructions'
            ]);
        });
    }
};
