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
        'nomor_booking',
        'layanan',
        'lokasi_jemput',
        'tanggal_mulai',
        'jam_mulai',
        'tanggal_selesai',
        'jam_selesai',
        'total_harga',
        'biaya_sopir',
        'asuransi_tambahan',
        'biaya_asuransi',
        'metode_pembayaran',
        'status_pembayaran',
        'status',
        'catatan_sopir',
        'sumber_pesanan',
        'nama_pelanggan_offline',
        'no_hp_offline',
        'catatan_admin',
        'no_hp_pelanggan',
        'ktp_path',
        'sim_path',
        'pdp_consent',
        'pdp_consent_at',
        'pdp_consent_ip',
        'phone_verified',
        'phone_verified_at',
        'is_disputed',
        'dispute_reason',
        'tanggal_kembali_aktual',
        'dokumen_purged_at',
        'whatsapp_notified_at',
        'email_notified_at',
        'nama_sopir_assigned',
        'no_hp_sopir_assigned',
        // Point 5 Fields
        'extra_hours',
        'biaya_extra_hours',
        'jarak_pengantaran_km',
        'biaya_pengantaran',
        'menit_terlambat',
        'denda_keterlambatan',
        'status_denda',
        'is_extended',
        'extended_from_transaksi_id',
        'terms_agreed',
        'terms_agreed_at',
    ];

    protected $casts = [
        'asuransi_tambahan' => 'boolean',
        'pdp_consent' => 'boolean',
        'phone_verified' => 'boolean',
        'is_disputed' => 'boolean',
        'is_extended' => 'boolean',
        'terms_agreed' => 'boolean',
        'pdp_consent_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'terms_agreed_at' => 'datetime',
        'tanggal_kembali_aktual' => 'datetime',
        'dokumen_purged_at' => 'datetime',
        'whatsapp_notified_at' => 'datetime',
        'email_notified_at' => 'datetime',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'jarak_pengantaran_km' => 'float',
        'extra_hours' => 'integer',
        'biaya_extra_hours' => 'integer',
        'biaya_pengantaran' => 'integer',
        'menit_terlambat' => 'integer',
        'denda_keterlambatan' => 'integer',
    ];

    /**
     * Hitung denda keterlambatan berdasarkan waktu pengembalian aktual
     * Kebijakan:
     * - Grace period 30 menit (toleransi bebas denda)
     * - 31 - 180 menit (Tier 1): Rp 50.000/jam proporsional per menit
     * - > 180 menit (Tier 2): Biaya 1 hari sewa penuh
     */
    public function hitungDendaKeterlambatan(?\DateTimeInterface $actualTime = null): array
    {
        $actual = $actualTime ? \Carbon\Carbon::parse($actualTime) : now();

        $endDateStr = $this->tanggal_selesai instanceof \DateTimeInterface 
            ? $this->tanggal_selesai->format('Y-m-d') 
            : $this->tanggal_selesai;

        $scheduled = \Carbon\Carbon::parse($endDateStr . ' ' . ($this->jam_selesai ?: '09:00'));

        $diffMinutes = $scheduled->diffInMinutes($actual, false);

        // Not late or within grace period
        if ($diffMinutes <= 30) {
            return [
                'is_late' => false,
                'minutes_late' => max(0, $diffMinutes),
                'tier' => 'grace_period',
                'fine_amount' => 0,
                'message' => $diffMinutes <= 0 
                    ? 'Unit dikembalikan tepat waktu atau sebelum jadwal berakhir.' 
                    : 'Pengembalian dalam masa tenggang toleransi (grace period 30 menit). Bebas denda.',
            ];
        }

        // Tier 1: 31 s/d 180 menit (Rp 50.000 / jam proporsional per menit)
        if ($diffMinutes <= 180) {
            $hourlyRate = 50000;
            $fine = (int) round(($diffMinutes / 60) * $hourlyRate);

            return [
                'is_late' => true,
                'minutes_late' => $diffMinutes,
                'tier' => 'tier_1',
                'fine_amount' => $fine,
                'message' => "Terlambat {$diffMinutes} menit. Dikenakan denda proporsional Rp " . number_format($hourlyRate, 0, ',', '.') . "/jam.",
            ];
        }

        // Tier 2: Lebih dari 3 jam (Dikenakan 1 hari sewa penuh)
        $dailyRate = $this->mobil ? (float) $this->mobil->harga_per_hari : 350000;
        // Check seasonal price if available
        $seasonal = \App\Models\SeasonalPrice::getSeasonalPriceForCar($this->mobil_id, $endDateStr, $endDateStr);
        if ($seasonal) {
            $dailyRate = (float) $seasonal->tarif_per_hari;
        }

        $hours = floor($diffMinutes / 60);
        $remMinutes = $diffMinutes % 60;

        return [
            'is_late' => true,
            'minutes_late' => $diffMinutes,
            'tier' => 'tier_2',
            'fine_amount' => (int) $dailyRate,
            'message' => "Terlambat lebih dari 3 jam ({$hours} jam {$remMinutes} menit). Otomatis dihitung sebagai sewa tambahan 1 hari penuh (Rp " . number_format($dailyRate, 0, ',', '.') . ").",
        ];
    }

    /**
     * Relasi ke transaksi induk jika ini adalah extend
     */
    public function parentTransaksi()
    {
        return $this->belongsTo(Transaksi::class, 'extended_from_transaksi_id');
    }

    /**
     * Relasi ke transaksi perpanjangan
     */
    public function extensions()
    {
        return $this->hasMany(Transaksi::class, 'extended_from_transaksi_id');
    }

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
