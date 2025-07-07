<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RentalMobil;
use App\Models\User;

class RentalMobilSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin',
                'email' => 'adminrental@gmail.com',
                'role' => 'admin',
                'password' => bcrypt('admin123'),
            ]);
        }

        
        }
    }

