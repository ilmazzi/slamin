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
        Schema::table('poems', function (Blueprint $table) {
            // Campi per il sistema di traduzione
            $table->string('original_language', 10)->default('it')->after('language');
            $table->foreignId('translated_from')->nullable()->constrained('poems')->onDelete('set null')->after('original_language');
            $table->decimal('translation_price', 8, 2)->nullable()->after('translated_from');
            $table->boolean('translation_available')->default(false)->after('translation_price');
            $table->json('translation_requests')->nullable()->after('translation_available');
            
            // Campi per il sistema di moderazione avanzato
            $table->enum('poem_type', ['free_verse', 'sonnet', 'haiku', 'limerick', 'other'])->default('free_verse')->after('category');
            $table->integer('word_count')->default(0)->after('poem_type');
            $table->boolean('is_draft')->default(false)->after('is_featured');
            $table->timestamp('draft_saved_at')->nullable()->after('is_draft');
            
            // Campi per analytics e social
            $table->integer('share_count')->default(0)->after('comment_count');
            $table->integer('bookmark_count')->default(0)->after('share_count');
            $table->json('seo_meta')->nullable()->after('bookmark_count');
            $table->string('slug')->unique()->nullable()->after('title');
            
            // Campi per monetizzazione futura
            $table->boolean('is_premium')->default(false)->after('is_draft');
            $table->decimal('price', 8, 2)->nullable()->after('is_premium');
            $table->json('donation_info')->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('poems', function (Blueprint $table) {
            $table->dropForeign(['translated_from']);
            $table->dropColumn([
                'original_language',
                'translated_from', 
                'translation_price',
                'translation_available',
                'translation_requests',
                'poem_type',
                'word_count',
                'is_draft',
                'draft_saved_at',
                'share_count',
                'bookmark_count',
                'seo_meta',
                'slug',
                'is_premium',
                'price',
                'donation_info'
            ]);
        });
    }
};
