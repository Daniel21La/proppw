<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        if (!User::where('email', 'adminrental@gmail.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'adminrental@gmail.com',
                'role' => 'admin',
                'password' => Hash::make('password'), 
            ]);
        }

        if (!User::where('email', 'userbiasa@gmail.com')->exists()) {
            User::create([
                'name' => 'User',
                'email' => 'userbiasa@gmail.com',
                'role' => 'user',
                'password' => Hash::make('password'), // Ganti sesuai kebutuhan
            ]);
        }
    }
}
