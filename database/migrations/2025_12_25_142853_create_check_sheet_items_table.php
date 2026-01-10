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
        Schema::create('check_sheet_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipment')->onDelete('cascade');
            $table->foreignId('check_sheet_id')->constrained()->onDelete('cascade');
            $table->text('activity')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(0); // 0: Rejected, 1: completed, 2: 'Holding', 3: 'N/A'
            $table->text('remarks')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_sheet_items');
    }
};
