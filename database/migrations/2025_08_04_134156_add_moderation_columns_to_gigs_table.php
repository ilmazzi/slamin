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
            $table->string('moderation_status')->default('pending')->after('accepted_applications_count');
            $table->text('moderation_notes')->nullable()->after('moderation_status');
            $table->foreignId('moderated_by')->nullable()->constrained('users')->onDelete('set null')->after('moderation_notes');
            $table->datetime('moderated_at')->nullable()->after('moderated_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gigs', function (Blueprint $table) {
            $table->dropForeign(['moderated_by']);
            $table->dropColumn(['moderation_status', 'moderation_notes', 'moderated_by', 'moderated_at']);
        });
    }
};
