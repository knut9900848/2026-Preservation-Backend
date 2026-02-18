<?php

namespace Database\Factories;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ActivityItem>
 */
class ActivityItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'activity_id' => Activity::factory(),
            'description' => fake()->sentence(),
            'remark' => fake()->paragraph(),
            'order' => fake()->numberBetween(1, 20),
            'is_active' => fake()->boolean(95),
        ];
    }
}
