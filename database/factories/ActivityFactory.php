<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'code' => 'ACT-' . fake()->unique()->numberBetween(10000, 99999),
            // 'description' => fake()->sentence(6),
            // 'notes' => fake()->boolean(70) ? fake()->paragraph() : null,
            // 'frequency' => fake()->numberBetween(1, 365),
            // 'is_active' => fake()->boolean(90),
        ];
    }
}
