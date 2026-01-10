<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            'Electrical Equipment',
            'Mechanical Equipment',
            'Safety Equipment',
            'Testing Equipment',
            'Measurement Tools',
            'Hand Tools',
            'Power Tools',
            'Laboratory Equipment',
            'Office Equipment',
            'Industrial Machinery',
        ];

        return [
            'name' => fake()->unique()->randomElement($categories),
            'code' => 'CAT-' . fake()->unique()->numberBetween(1000, 9999),
            'description' => fake()->sentence(),
            'is_active' => fake()->boolean(90),
        ];
    }
}
