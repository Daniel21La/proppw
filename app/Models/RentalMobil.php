<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RentalMobil extends Model
{
    use HasFactory;

    protected $table = 'rental_mobils';
    protected $primaryKey = 'id';

    protected $fillable = [
        'merk',
        'nama_mobil',
        'harga_per_hari',
        'gambar',
        'status',
    ];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'mobil_id');
    }

    public function getGambarUrlAttribute()
    {
        return $this->gambar
            ? asset('/gambar_mobil/' . $this->gambar)
            : asset('/gambar_mobil/default.jpg');
    }
}
