<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = 'transaksis';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'mobil_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'total_harga',
        'status',
    ];

    /**
     * Relasi ke user (yang memesan).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke mobil yang disewa.
     */
    public function mobil()
    {
        return $this->belongsTo(RentalMobil::class, 'mobil_id', 'id');
    }
}
