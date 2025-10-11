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
            if (!Schema::hasColumn('events', 'moderation_status')) {
                $table->enum('moderation_status', ['pending', 'approved', 'rejected'])->default('pending')->after('status');
            }
            
            if (!Schema::hasColumn('events', 'moderation_notes')) {
                $table->text('moderation_notes')->nullable()->after('moderation_status');
            }
            
            if (!Schema::hasColumn('events', 'moderated_by')) {
                $table->foreignId('moderated_by')->nullable()->constrained('users')->onDelete('set null')->after('moderation_notes');
            }
            
            if (!Schema::hasColumn('events', 'moderated_at')) {
                $table->timestamp('moderated_at')->nullable()->after('moderated_by');
            }
        });

        // Add indexes separately to check if they exist
        if (!$this->indexExists('events', 'events_moderation_status_index')) {
            Schema::table('events', function (Blueprint $table) {
                $table->index(['moderation_status']);
            });
        }
        
        if (!$this->indexExists('events', 'events_moderated_by_index')) {
            Schema::table('events', function (Blueprint $table) {
                $table->index(['moderated_by']);
            });
        }
    }

    /**
     * Check if an index exists on a table
     */
    private function indexExists(string $table, string $index): bool
    {
        $connection = Schema::getConnection();
        $databaseName = $connection->getDatabaseName();
        
        $result = $connection->select(
            "SELECT COUNT(*) as count FROM information_schema.statistics 
             WHERE table_schema = ? AND table_name = ? AND index_name = ?",
            [$databaseName, $table, $index]
        );
        
        return $result[0]->count > 0;
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
