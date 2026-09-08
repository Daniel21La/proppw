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
    ];

    protected $casts = [
        'asuransi_tambahan' => 'boolean',
        'pdp_consent' => 'boolean',
        'phone_verified' => 'boolean',
        'is_disputed' => 'boolean',
        'pdp_consent_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'tanggal_kembali_aktual' => 'datetime',
        'dokumen_purged_at' => 'datetime',
        'whatsapp_notified_at' => 'datetime',
        'email_notified_at' => 'datetime',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
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
