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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            // Polymorphic relationship - can comment on any model
            $table->morphs('commentable');

            // User who created the comment
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Comment content
            $table->text('content');

            // Hierarchical reply structure - parent comment
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade');

            // Soft deletes for keeping comment history
            $table->softDeletes();

            $table->timestamps();

            // Index for parent_id (morphs() already creates index for commentable)
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
