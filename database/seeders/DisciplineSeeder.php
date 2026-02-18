<?php

namespace Database\Seeders;

use App\Models\Discipline;
use Illuminate\Database\Seeder;

class DisciplineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $disciplines = [
            ['name' => 'AREA COMPLETION', 'code' => 'AP'],
            ['name' => 'ELECTRICAL', 'code' => 'EP'],
            ['name' => 'INSTRUMENT', 'code' => 'IP'],
            ['name' => 'MECHANICAL', 'code' => 'MP'],
            ['name' => 'SKID / PACKAGES', 'code' => 'DP'],
            ['name' => 'STORAGE', 'code' => 'TP'],
            ['name' => 'PIPING', 'code' => 'PP'],
            ['name' => 'SAFETY', 'code' => 'SP'],
        ];

        foreach ($disciplines as $discipline) {
            Discipline::firstOrCreate(
                ['code' => $discipline['code']],
                array_merge($discipline, ['is_active' => true])
            );
        }
    }
}
