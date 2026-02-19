<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            UserSeeder::class,
            SupplierSeeder::class,
            CurrentLocationSeeder::class,
            CategorySeeder::class,
            SubCategorySeeder::class,
            EquipmentSeeder::class,
            DisciplineSeeder::class,
            DisciplineItemSeeder::class,
            SettingSeeder::class,
            ActivitySeeder::class,
            ActivityItemSeeder::class,
        ]);
    }
}
