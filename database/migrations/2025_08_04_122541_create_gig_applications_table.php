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
        Schema::create('gig_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gig_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('message');
            $table->text('experience')->nullable();
            $table->text('portfolio')->nullable();
            $table->text('availability')->nullable();
            $table->text('compensation_expectation')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'withdrawn'])->default('pending');
            $table->datetime('accepted_at')->nullable();
            $table->datetime('rejected_at')->nullable();
            $table->datetime('withdrawn_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['gig_id', 'user_id']);
            $table->index(['gig_id', 'status']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gig_applications');
    }
};
