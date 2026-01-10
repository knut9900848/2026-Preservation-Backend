<?php

namespace Database\Seeders;

use App\Models\CurrentLocation;
use Illuminate\Database\Seeder;

class CurrentLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CurrentLocation::factory()->count(50)->create();
    }
}
