<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\DisciplineItem;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['code' => 'EP-01-01', 'description' => 'Test', 'frequency' => 1],
            ['code' => 'EP-01-04', 'description' => 'Test', 'frequency' => 4],
            ['code' => 'EP-02-04', 'description' => 'Test', 'frequency' => 4],
            ['code' => 'EP-02-12', 'description' => 'Test', 'frequency' => 12],
            ['code' => 'EP-03-08', 'description' => 'Test', 'frequency' => 8],
            ['code' => 'EP-04-04', 'description' => 'Test', 'frequency' => 4],
            ['code' => 'EP-05-04', 'description' => 'Test', 'frequency' => 4],
            ['code' => 'EP-06-04', 'description' => 'Test', 'frequency' => 4],
            ['code' => 'EP-07-04', 'description' => 'Test', 'frequency' => 4],
            ['code' => 'EP-08-04', 'description' => 'Test', 'frequency' => 4],
            ['code' => 'EP-09-04', 'description' => 'Test', 'frequency' => 4],
            ['code' => 'EP-09-12', 'description' => 'Test', 'frequency' => 12],
            ['code' => 'EP-09-24', 'description' => 'Test', 'frequency' => 24],
            ['code' => 'IP-01-04', 'description' => 'Test', 'frequency' => 4],
            ['code' => 'IP-02-08', 'description' => 'Test', 'frequency' => 8],
            ['code' => 'IP-03-04', 'description' => 'Test', 'frequency' => 4],
            ['code' => 'IP-03-24', 'description' => 'Test', 'frequency' => 24],
            ['code' => 'MP-01-04', 'description' => 'Test', 'frequency' => 4],
            ['code' => 'MP-02-04', 'description' => 'Test', 'frequency' => 4],
            ['code' => 'MP-02-12', 'description' => 'Test', 'frequency' => 12],
            ['code' => 'MP-03-04', 'description' => 'Test', 'frequency' => 4],
            ['code' => 'MP-04-04', 'description' => 'Test', 'frequency' => 4],
            ['code' => 'MP-04-12', 'description' => 'Test', 'frequency' => 12],
            ['code' => 'MP-04-48', 'description' => 'Test', 'frequency' => 48],
            ['code' => 'MP-05-04', 'description' => 'Test', 'frequency' => 4],
            ['code' => 'MP-05-12', 'description' => 'Test', 'frequency' => 12],
            ['code' => 'MP-06-04', 'description' => 'Test', 'frequency' => 4],
            ['code' => 'MP-07-04', 'description' => 'Test', 'frequency' => 4],
            ['code' => 'MP-08-08', 'description' => 'Test', 'frequency' => 8],
            ['code' => 'MP-09-12', 'description' => 'Test', 'frequency' => 12],
            ['code' => 'MP-10-04', 'description' => 'Test', 'frequency' => 4],
            ['code' => 'MP-11-04', 'description' => 'Test', 'frequency' => 4],
            ['code' => 'MP-11-12', 'description' => 'Test', 'frequency' => 12],
            ['code' => 'MP-12-04', 'description' => 'Test', 'frequency' => 4],
            ['code' => 'MP-12-12', 'description' => 'Test', 'frequency' => 12],
            ['code' => 'MP-12-52', 'description' => 'Test', 'frequency' => 52],
            ['code' => 'MP-13-04', 'description' => 'Test', 'frequency' => 4],
            ['code' => 'MP-14-04', 'description' => 'Test', 'frequency' => 4],
            ['code' => 'TP-01-04', 'description' => 'Test', 'frequency' => 4],
            ['code' => 'PP-01-01', 'description' => 'Test', 'frequency' => 1],
            ['code' => 'PP-02-04', 'description' => 'Test', 'frequency' => 4],
            ['code' => 'SP-01-12', 'description' => 'Test', 'frequency' => 12],
            ['code' => 'SP-02-04', 'description' => 'Test', 'frequency' => 4],
            ['code' => 'SP-02-24', 'description' => 'Test', 'frequency' => 24],
            ['code' => 'AP-01-01', 'description' => 'Test', 'frequency' => 1],
            ['code' => 'DP-01-04', 'description' => 'Test', 'frequency' => 4],
            ['code' => 'DP-02-04', 'description' => 'Test', 'frequency' => 4],
            ['code' => 'DP-03-04', 'description' => 'Test', 'frequency' => 4],
            ['code' => 'DP-03-12', 'description' => 'Test', 'frequency' => 12],
            ['code' => 'DP-03-48', 'description' => 'Test', 'frequency' => 48],
            ['code' => 'DP-04-04', 'description' => 'Test', 'frequency' => 4],
            ['code' => 'DP-04-12', 'description' => 'Test', 'frequency' => 12],
        ];

        foreach ($items as $item) {
            // Extract discipline item code from activity code (e.g., "EP-01" from "EP-01-04")
            $disciplineItemCode = substr($item['code'], 0, 5);
            $disciplineItem = DisciplineItem::where('code', $disciplineItemCode)->first();

            if (!$disciplineItem) {
                continue;
            }

            Activity::firstOrCreate(
                ['code' => $item['code']],
                [
                    'discipline_id' => $disciplineItem->discipline_id,
                    'discipline_item_id' => $disciplineItem->id,
                    'description' => $item['description'],
                    'frequency' => $item['frequency'],
                    'is_active' => true,
                ]
            );
        }
    }
}
