<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeasonalPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_mobil_id',
        'nama_event',
        'tarif_per_hari',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
    ];

    protected $casts = [
        'tarif_per_hari' => 'decimal:2',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean',
    ];

    public function mobil()
    {
        return $this->belongsTo(RentalMobil::class, 'rental_mobil_id');
    }

    /**
     * Get active seasonal rate for a car between start and end date if any
     */
    public static function getSeasonalPriceForCar($mobilId, $startDate, $endDate)
    {
        return static::where('is_active', true)
            ->where(function ($query) use ($mobilId) {
                $query->where('rental_mobil_id', $mobilId)
                    ->orWhereNull('rental_mobil_id');
            })
            ->where(function ($query) use ($startDate, $endDate) {
                // Overlaps with given rental window
                $query->where('tanggal_mulai', '<=', $endDate)
                    ->where('tanggal_selesai', '>=', $startDate);
            })
            ->orderByRaw('rental_mobil_id IS NOT NULL DESC') // Specific car rule takes priority
            ->first();
    }
}
