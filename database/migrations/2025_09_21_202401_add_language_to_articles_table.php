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
        Schema::table('articles', function (Blueprint $table) {
            $table->string('language', 10)->default('it')->after('content');
            $table->string('original_language', 10)->default('it')->after('language');
            $table->boolean('is_news')->default(false)->after('featured');
            $table->boolean('needs_translation')->default(false)->after('is_news');
            $table->json('translation_status')->nullable()->after('needs_translation');
            $table->timestamp('translated_at')->nullable()->after('translation_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn([
                'language',
                'original_language',
                'is_news',
                'needs_translation',
                'translation_status',
                'translated_at'
            ]);
        });
    }
};
