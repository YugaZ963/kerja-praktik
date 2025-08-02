<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan seeder inventaris terlebih dahulu
        $this->call([
            InventoryTableSeeder::class,
            ProductsTableSeeder::class,
        ]);

        // User::factory(10)->create();

        // Buat pengguna test
        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => fake()->unique()->safeEmail()
        ]);
    }
}
