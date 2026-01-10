<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubCategory>
 */
class SubCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => fake()->words(2, true) . ' ' . fake()->randomElement(['Type A', 'Type B', 'Series', 'Model', 'Class']),
            'code' => 'SUB-' . fake()->unique()->numberBetween(1000, 9999),
            'description' => fake()->sentence(),
            'is_active' => fake()->boolean(90),
        ];
    }
}
