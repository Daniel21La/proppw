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
            User::create([
                'name' => 'Admin',
                'email' => 'adminrental@gmail.com',
                'role' => 'admin',
                'password' => bcrypt('password'),
            ]);
        }

        $mobils = [
            [
                'merk' => 'Audi',
                'nama_mobil' => 'Audi RS7 Sportback V8 Turbo',
                'nopol' => 'B 7777 RS',
                'tipe_kendaraan' => 'Sedan Premium',
                'kapasitas_penumpang' => 5,
                'transmisi' => 'Otomatis (Automatic)',
                'harga_per_hari' => 3500000,
                'biaya_sopir_per_hari' => 350000,
                'gambar' => 'audi_front_dark.jpg',
                'status' => 'tersedia',
            ],
            [
                'merk' => 'BMW',
                'nama_mobil' => 'BMW M4 Competition Coupe',
                'nopol' => 'B 444 M',
                'tipe_kendaraan' => 'Sports Coupe',
                'kapasitas_penumpang' => 4,
                'transmisi' => 'Otomatis (Automatic)',
                'harga_per_hari' => 4200000,
                'biaya_sopir_per_hari' => 400000,
                'gambar' => 'red_sports_car.jpg',
                'status' => 'tersedia',
            ],
            [
                'merk' => 'Porsche',
                'nama_mobil' => 'Porsche 911 Carrera S',
                'nopol' => 'B 911 POR',
                'tipe_kendaraan' => 'Supercar',
                'kapasitas_penumpang' => 2,
                'transmisi' => 'Otomatis (Automatic)',
                'harga_per_hari' => 7500000,
                'biaya_sopir_per_hari' => 500000,
                'gambar' => 'yellow_muscle_car.jpg',
                'status' => 'tersedia',
            ],
            [
                'merk' => 'Bentley',
                'nama_mobil' => 'Bentley Continental GT V8',
                'nopol' => 'B 1 BEN',
                'tipe_kendaraan' => 'Ultra Luxury Coupe',
                'kapasitas_penumpang' => 4,
                'transmisi' => 'Otomatis (Automatic)',
                'harga_per_hari' => 8500000,
                'biaya_sopir_per_hari' => 600000,
                'gambar' => 'bentley_mountain.jpg',
                'status' => 'tersedia',
            ],
            [
                'merk' => 'Toyota',
                'nama_mobil' => 'Alphard VIP Executive Lounge',
                'nopol' => 'B 1111 VIP',
                'tipe_kendaraan' => 'Luxury MPV',
                'kapasitas_penumpang' => 7,
                'transmisi' => 'Otomatis (Automatic)',
                'harga_per_hari' => 2800000,
                'biaya_sopir_per_hari' => 250000,
                'gambar' => 'car_top_down.jpg',
                'status' => 'tersedia',
            ],
            [
                'merk' => 'Toyota',
                'nama_mobil' => 'Fortuner 2.8 GR Sport 4x4',
                'nopol' => 'B 1544 FTR',
                'tipe_kendaraan' => 'SUV Executive',
                'kapasitas_penumpang' => 7,
                'transmisi' => 'Otomatis (Automatic)',
                'harga_per_hari' => 1250000,
                'biaya_sopir_per_hari' => 200000,
                'gambar' => 'Fortuner.jpeg',
                'status' => 'tersedia',
            ],
            [
                'merk' => 'Honda',
                'nama_mobil' => 'Brio RS Phoenix Orange',
                'nopol' => 'B 2810 BRO',
                'tipe_kendaraan' => 'City Car Compact',
                'kapasitas_penumpang' => 5,
                'transmisi' => 'Otomatis (Automatic)',
                'harga_per_hari' => 450000,
                'biaya_sopir_per_hari' => 150000,
                'gambar' => 'Brio RS Phoenix Orange.jpeg',
                'status' => 'tersedia',
            ],
            [
                'merk' => 'Toyota',
                'nama_mobil' => 'All New Avanza Veloz Q CVT',
                'nopol' => 'B 2099 VLZ',
                'tipe_kendaraan' => 'Family MPV',
                'kapasitas_penumpang' => 7,
                'transmisi' => 'Otomatis (Automatic)',
                'harga_per_hari' => 650000,
                'biaya_sopir_per_hari' => 175000,
                'gambar' => 'avanza.jpeg',
                'status' => 'tersedia',
            ],
        ];

        foreach ($mobils as $data) {
            RentalMobil::updateOrCreate(
                ['nama_mobil' => $data['nama_mobil']],
                $data
            );
        }
    }
}
