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
        Schema::create('event_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            
            // User can be null for guest participants
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Guest participant info (when user_id is null)
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('guest_phone')->nullable();
            $table->text('guest_bio')->nullable();
            
            $table->enum('registration_type', ['invited', 'requested', 'organizer_added', 'guest'])->default('requested');
            $table->enum('status', ['pending', 'confirmed', 'performed', 'disqualified', 'no_show'])->default('confirmed');
            $table->integer('performance_order')->nullable(); // Order of performance
            $table->timestamp('performance_time')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('added_by')->nullable()->constrained('users')->onDelete('set null'); // Who added this participant
            $table->timestamps();

            // Indexes
            $table->index('event_id');
            $table->index('user_id');
            $table->index('status');
            $table->index('performance_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_participants');
    }
};

