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
        Schema::dropIfExists('check_sheet_user');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('check_sheet_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('check_sheet_id')->constrained('check_sheets')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Ensure unique combination
            $table->unique(['check_sheet_id', 'user_id']);
        });
    }
};
