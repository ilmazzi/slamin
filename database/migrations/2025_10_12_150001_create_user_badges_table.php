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
        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('badge_id')->constrained()->onDelete('cascade');
            $table->timestamp('earned_at');
            $table->json('metadata')->nullable(); // Extra info about how badge was earned
            $table->integer('progress')->default(0); // Progress towards next badge in same category (0-100)
            $table->foreignId('awarded_by')->nullable()->constrained('users')->onDelete('set null'); // For manual assignments
            $table->text('admin_notes')->nullable(); // Notes for manual assignments
            $table->timestamps();

            // Indexes
            $table->unique(['user_id', 'badge_id']); // Can't earn same badge twice
            $table->index('earned_at');
            $table->index('awarded_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_badges');
    }
};

