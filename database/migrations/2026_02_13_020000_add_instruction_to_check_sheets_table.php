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
        Schema::table('check_sheets', function (Blueprint $table) {
            $table->text('instruction')->nullable()->default("Preservation Instructions:\nIf the Unit / Equipment is operational or under commissioning, DO NOT execute this check sheet and activities.\nIf any of the Activities are not able to be performed, explain in detail in the Remarks column & inform Preservation Supervisor / Coordinator.\nIf any of the equipment is found to be damaged, a punch list shall be raised.\nEnsure the preservation Label is filled & signed after preservation routine activity.")->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('check_sheets', function (Blueprint $table) {
            $table->dropColumn('instruction');
        });
    }
};
