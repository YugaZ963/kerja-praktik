<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if not exists
        User::firstOrCreate(
            ['email' => 'admin@ravazka.com'],
            [
                'name' => 'Admin RAVAZKA',
                'email' => 'admin@ravazka.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Create regular user for testing
        User::firstOrCreate(
            ['email' => 'user@ravazka.com'],
            [
                'name' => 'User RAVAZKA',
                'email' => 'user@ravazka.com',
                'password' => Hash::make('user123'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin and User accounts created successfully!');
        $this->command->info('Admin: admin@ravazka.com / admin123');
        $this->command->info('User: user@ravazka.com / user123');
    }
}
