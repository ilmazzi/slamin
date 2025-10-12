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
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('points'); // Can be positive or negative
            $table->string('type'); // badge_earned, event_win, event_participation, manual_adjustment, etc.
            $table->string('source_type')->nullable(); // Polymorphic relation
            $table->unsignedBigInteger('source_id')->nullable();
            $table->text('description');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null'); // For manual adjustments
            $table->timestamps();

            // Indexes
            $table->index(['source_type', 'source_id']);
            $table->index('created_at');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_transactions');
    }
};

