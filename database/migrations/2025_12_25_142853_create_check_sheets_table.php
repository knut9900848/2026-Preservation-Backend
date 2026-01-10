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
        Schema::create('check_sheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->onDelete('cascade');
            $table->foreignId('equipment_id')->constrained('equipment')->onDelete('cascade');
            $table->string('activity_code')->nullable();
            $table->integer('current_round')->default(1);
            $table->string('sheet_number')->unique();
            $table->date('due_date')->nullable();
            $table->text('notes')->nullable();
            $table->tinyInteger('frequency')->default(0);
            $table->string('status')->nullable(); // Draft, Completed, Reviewed, Approved
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_sheets');
    }
};
