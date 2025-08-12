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
            $table->enum('moderation_status', ['pending', 'approved', 'rejected'])->default('pending')->after('status');
            $table->boolean('is_public')->default(true)->after('moderation_status');
            $table->text('moderation_notes')->nullable()->after('is_public');
            $table->foreignId('moderated_by')->nullable()->constrained('users')->after('moderation_notes');
            $table->timestamp('moderated_at')->nullable()->after('moderated_by');

            // Indici per moderazione
            $table->index(['moderation_status', 'is_public']);
            $table->index('moderated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['moderated_by']);
            $table->dropIndex(['moderation_status', 'is_public']);
            $table->dropIndex(['moderated_by']);

            $table->dropColumn([
                'moderation_status',
                'is_public',
                'moderation_notes',
                'moderated_by',
                'moderated_at'
            ]);
        });
    }
};
