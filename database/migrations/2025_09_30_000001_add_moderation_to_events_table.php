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
        Schema::table('events', function (Blueprint $table) {
            $table->enum('moderation_status', ['pending', 'approved', 'rejected'])->default('pending')->after('status');
            $table->text('moderation_notes')->nullable()->after('moderation_status');
            $table->foreignId('moderated_by')->nullable()->constrained('users')->onDelete('set null')->after('moderation_notes');
            $table->timestamp('moderated_at')->nullable()->after('moderated_by');

            // Indici per performance
            $table->index(['moderation_status']);
            $table->index(['moderated_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex(['moderation_status']);
            $table->dropIndex(['moderated_by']);
            $table->dropForeign(['moderated_by']);
            $table->dropColumn(['moderation_status', 'moderation_notes', 'moderated_by', 'moderated_at']);
        });
    }
};
