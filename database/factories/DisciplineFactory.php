<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Discipline>
 */
class DisciplineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $disciplines = [
            'AREA COMPLETION',
            'ELECTRICAL',
            'INSTRUMENT',
            'MECHANICAL',
            'SKID / PACKAGES',
            'STORAGE',
            'PIPING',
            'SAFETY',
        ];

        return [
            'name' => fake()->unique()->randomElement($disciplines),
            'code' => 'DIS-' . fake()->unique()->numberBetween(1000, 9999),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
