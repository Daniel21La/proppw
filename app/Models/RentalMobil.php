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
        'nopol',
        'tipe_kendaraan',
        'kapasitas_penumpang',
        'transmisi',
        'harga_per_hari',
        'biaya_sopir_per_hari',
        'gambar',
        'status',
    ];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'mobil_id');
    }

    public function seasonalPrices()
    {
        return $this->hasMany(SeasonalPrice::class, 'rental_mobil_id');
    }

    public function getGambarUrlAttribute()
    {
        return $this->gambar
            ? asset('/gambar_mobil/' . $this->gambar)
            : asset('/images/audi_front_dark.jpg');
    }

    /**
     * Pengecekan overlap jadwal sewa armada berbasis timeline
     */
    public function hasScheduleOverlap(string $startDate, string $endDate, ?int $excludeTransaksiId = null): ?Transaksi
    {
        $query = Transaksi::where('mobil_id', $this->id)
            ->whereIn('status', ['pending', 'disetujui', 'dikonfirmasi', 'selesai'])
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('tanggal_mulai', [$startDate, $endDate])
                  ->orWhereBetween('tanggal_selesai', [$startDate, $endDate])
                  ->orWhere(function ($sub) use ($startDate, $endDate) {
                      $sub->where('tanggal_mulai', '<=', $startDate)
                          ->where('tanggal_selesai', '>=', $endDate);
                  });
            });

        if ($excludeTransaksiId) {
            $query->where('id', '!=', $excludeTransaksiId);
        }

        return $query->first();
    }
}
