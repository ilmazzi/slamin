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
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->string('stripe_subscription_id')->nullable(); // ID abbonamento Stripe
            $table->string('stripe_customer_id')->nullable(); // ID cliente Stripe
            $table->datetime('start_date'); // Data inizio
            $table->datetime('end_date'); // Data fine
            $table->enum('status', ['active', 'cancelled', 'expired', 'past_due'])->default('active');
            $table->integer('video_limit_override')->nullable(); // Override limite video (admin)
            $table->text('notes')->nullable(); // Note admin
            $table->timestamps();

            // Indici per performance
            $table->index(['user_id', 'status']);
            $table->index(['end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_subscriptions');
    }
};
