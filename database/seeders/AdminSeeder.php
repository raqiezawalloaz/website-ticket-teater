<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate([
            'email' => 'admin@gmail.com',
        ], [
            'name' => 'Admin Teater',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::firstOrCreate([
            'email' => 'user@gmail.com',
        ], [
            'name' => 'User Teater',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);
    }
}
