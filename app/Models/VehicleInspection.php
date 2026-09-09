<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleInspection extends Model
{
    use HasFactory;

    protected $table = 'vehicle_inspections';

    protected $fillable = [
        'transaksi_id',
        'driver_id',
        'tipe_inspeksi',
        'odometer',
        'level_bbm',
        'kondisi_fisik',
        'kebersihan_interior',
        'ban_serap_dan_jack',
        'catatan',
    ];

    protected $casts = [
        'kebersihan_interior' => 'boolean',
        'ban_serap_dan_jack' => 'boolean',
        'odometer' => 'integer',
        'level_bbm' => 'integer',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}
