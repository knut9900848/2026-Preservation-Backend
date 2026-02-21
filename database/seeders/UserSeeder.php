<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create specific user: Steve Yoo
        User::firstOrCreate(
            ['email' => 'ies@ies.com'],
            [
                'name' => 'Steve Yoo',
                'email_verified_at' => now(),
                'phone' => '010-2558-9835',
                'date_of_birth' => '1980-03-04',
                'job_start_date' => '2015-06-08',
                'job_end_date' => null,
                'password' => Hash::make('1111'),
                'is_active' => true,
            ]
        );

        // Create 10 additional users with faker data
        for ($i = 1; $i <= 10; $i++) {
            $email = fake()->unique()->safeEmail();
            User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => fake()->name(),
                    'email_verified_at' => now(),
                    'phone' => fake()->phoneNumber(),
                    'date_of_birth' => fake()->date('Y-m-d', '-25 years'),
                    'job_start_date' => fake()->dateTimeBetween('-5 years', '-1 year')->format('Y-m-d'),
                    'job_end_date' => fake()->boolean(30) ? fake()->dateTimeBetween('now', '+2 years')->format('Y-m-d') : null,
                    'password' => Hash::make('password'),
                    'is_active' => fake()->boolean(90),
                ]
            );
        }
    }
}
