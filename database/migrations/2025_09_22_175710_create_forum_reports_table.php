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
        Schema::create('forum_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('users')->onDelete('cascade');
            $table->string('target_type'); // 'App\Models\ForumPost' o 'App\Models\ForumComment'
            $table->unsignedBigInteger('target_id');
            $table->enum('reason', [
                'spam', 'harassment', 'hate_speech', 'inappropriate_content',
                'misinformation', 'violence', 'self_harm', 'other'
            ]);
            $table->text('description')->nullable(); // Descrizione aggiuntiva
            $table->enum('status', ['pending', 'reviewed', 'resolved', 'dismissed'])->default('pending');
            $table->foreignId('handled_by')->nullable()->constrained('users'); // Moderatore che ha gestito
            $table->text('moderator_notes')->nullable();
            $table->timestamp('handled_at')->nullable();
            $table->timestamps();

            $table->index(['target_type', 'target_id']);
            $table->index(['reporter_id', 'created_at']);
            $table->index(['status', 'created_at']);
            $table->index('handled_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_reports');
    }
};
