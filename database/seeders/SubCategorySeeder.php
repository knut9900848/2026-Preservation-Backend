<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all categories
        $categories = Category::all();

        // Create 3-5 subcategories for each category
        foreach ($categories as $category) {
            SubCategory::factory()
                ->count(rand(3, 5))
                ->create([
                    'category_id' => $category->id,
                ]);
        }
    }
}
