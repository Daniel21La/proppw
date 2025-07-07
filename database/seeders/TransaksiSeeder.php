<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaksi;
use App\Models\User;
use App\Models\RentalMobil;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('role', 'user')->first();
        $mobil = RentalMobil::first();


        
    }
}
