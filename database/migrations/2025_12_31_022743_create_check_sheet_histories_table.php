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
        Schema::create('check_sheet_histories', function (Blueprint $table) {
            $table->id();

            // CheckSheet being tracked
            $table->foreignId('check_sheet_id')->constrained('check_sheets')->onDelete('cascade');

            // User who performed the action
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Action performed (completed, reviewed, approved, rejected)
            $table->string('action');

            // Status before and after the action
            $table->string('from_status')->nullable();
            $table->string('to_status');

            // Additional metadata (optional notes, reasons, etc.)
            $table->json('metadata')->nullable();

            $table->timestamps();

            // Indexes for performance
            $table->index('check_sheet_id');
            $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_sheet_histories');
    }
};
