<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ActivityItem;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 50 activities, each with approximately 15 activity items
        Activity::factory()
            ->count(50)
            ->create()
            ->each(function ($activity) {
                // Create between 12-18 activity items for each activity (average ~15)
                ActivityItem::factory()
                    ->count(fake()->numberBetween(12, 18))
                    ->create([
                        'activity_id' => $activity->id,
                        'order' => function () use ($activity) {
                            return ActivityItem::where('activity_id', $activity->id)->count() + 1;
                        }
                    ]);
            });
    }
}
