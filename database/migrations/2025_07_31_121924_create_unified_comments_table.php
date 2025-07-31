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
        Schema::create('unified_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('commentable_type');
            $table->unsignedBigInteger('commentable_id');
            $table->text('content');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->foreignId('parent_id')->nullable()->constrained('unified_comments')->onDelete('cascade'); // Per risposte
            $table->timestamps();

            // Indici per performance
            $table->index(['commentable_type', 'commentable_id'], 'unified_comments_morph');
            $table->index(['status'], 'unified_comments_status');
            $table->index(['user_id'], 'unified_comments_user');
            $table->index(['parent_id'], 'unified_comments_parent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unified_comments');
    }
};
