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
        Schema::create('check_sheet_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('check_sheet_id')->constrained('check_sheets')->onDelete('cascade');
            $table->foreignId('generated_by')->constrained('users')->onDelete('cascade');
            $table->decimal('revision_number', 5, 1);
            $table->string('file_path');
            $table->string('file_name');
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('checksheet_status');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('check_sheet_id');
            $table->unique(['check_sheet_id', 'revision_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_sheet_reports');
    }
};
