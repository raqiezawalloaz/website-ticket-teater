<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate([
            'email' => 'admin@gmail.com',
        ], [
            'name' => 'Admin Teater',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        $user = User::updateOrCreate([
            'email' => 'user@gmail.com',
        ], [
            'name' => 'User Teater',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        // Log to verify seeder execution
        Log::info("Admin created or updated: " . $admin->email);
        Log::info("User created or updated: " . $user->email);
    }
}
