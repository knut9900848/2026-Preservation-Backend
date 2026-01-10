<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\CurrentLocation;
use App\Models\SubCategory;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Equipment>
 */
class EquipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true) . ' Equipment',
            'tag_no' => 'EQ-' . fake()->unique()->numberBetween(10000, 99999),
            'category_id' => Category::inRandomOrder()->first()?->id,
            'sub_category_id' => SubCategory::inRandomOrder()->first()?->id,
            'supplier_id' => Supplier::inRandomOrder()->first()?->id,
            'current_location_id' => CurrentLocation::inRandomOrder()->first()?->id,
            'serial_number' => 'SN-' . fake()->unique()->bothify('??##########'),
            'description' => fake()->sentence(),
            'is_active' => fake()->boolean(90),
        ];
    }
}
