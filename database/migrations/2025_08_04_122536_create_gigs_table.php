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
        Schema::create('gigs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->string('compensation')->nullable();
            $table->datetime('deadline');
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('group_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('category');
            $table->string('type');
            $table->string('language')->default('italian');
            $table->string('location')->nullable();
            $table->boolean('is_remote')->default(false);
            $table->boolean('is_urgent')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_closed')->default(false);
            $table->integer('max_applications')->default(10);
            $table->boolean('allow_group_admin_edit')->default(false);
            $table->integer('view_count')->default(0);
            $table->integer('like_count')->default(0);
            $table->integer('comment_count')->default(0);
            $table->integer('application_count')->default(0);
            $table->integer('accepted_applications_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category', 'type']);
            $table->index(['is_urgent', 'is_featured']);
            $table->index(['deadline', 'is_closed']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gigs');
    }
};
