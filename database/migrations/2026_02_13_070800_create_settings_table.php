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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            // IES (Company) Information
            $table->string('ies_name')->nullable();
            $table->text('ies_address')->nullable();
            $table->string('ies_contact_number')->nullable();
            $table->string('ies_logo')->nullable();
            $table->string('ies_vat_code')->nullable();
            $table->string('ies_email')->nullable();
            $table->string('ies_website_url')->nullable();
            $table->string('ies_slogan')->nullable();
            $table->string('ies_ceo_name')->nullable();

            // Client Information
            $table->string('client_name')->nullable();
            $table->text('client_address')->nullable();
            $table->string('client_logo')->nullable();
            $table->string('client_contact_number')->nullable();

            // Project Information
            $table->string('project_name')->nullable();
            $table->string('project_code')->nullable();

            $table->timestamps();
        });

        // Insert a single default row
        \App\Models\Setting::create();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
