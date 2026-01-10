<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CurrentLocation>
 */
class CurrentLocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->city() . ' ' . fake()->randomElement(['Warehouse', 'Storage', 'Facility', 'Center']),
            'code' => 'LOC-' . fake()->unique()->numberBetween(1000, 9999),
            'description' => fake()->sentence(),
            'is_active' => fake()->boolean(90),
        ];
    }
}
