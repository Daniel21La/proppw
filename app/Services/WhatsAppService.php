<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Normalize Indonesian phone number to international format 628...
     */
    public static function formatNumber(string $phone): string
    {
        $cleaned = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($cleaned, '0')) {
            return '62' . substr($cleaned, 1);
        }
        if (str_starts_with($cleaned, '8')) {
            return '62' . $cleaned;
        }
        return $cleaned;
    }

    /**
     * Send OTP Verification Code via WhatsApp
     */
    public static function sendOtp(string $phoneNumber, string $otp): array
    {
        $formattedPhone = self::formatNumber($phoneNumber);
        $message = "*QUANTUM STREAMLINE — KODE VERIFIKASI*\n\n"
            . "Kode OTP Anda adalah: *{$otp}*\n\n"
            . "Berlaku selama 5 menit. JANGAN bagikan kode ini kepada siapapun demi keamanan reservasi rental armada Anda.\n\n"
            . "_Layanan Otentikasi Resmi Quantum Streamline_";

        $token = config('services.fonnte.token') ?? env('FONNTE_TOKEN');

        if (!empty($token)) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => $token,
                ])->post('https://api.fonnte.com/send', [
                    'target' => $formattedPhone,
                    'message' => $message,
                ]);

                if ($response->successful()) {
                    return [
                        'success' => true,
                        'message' => 'Kode OTP berhasil dikirim ke WhatsApp Anda.',
                        'provider' => 'Fonnte',
                    ];
                }
            } catch (\Exception $e) {
                Log::warning("Fonnte WA dispatch failed: {$e->getMessage()}");
            }
        }

        // Fallback / Development Simulation mode
        $logMessage = "[WHATSAPP OTP SIMULATION] To: {$formattedPhone} | Code: {$otp} | Message: " . str_replace("\n", " ", $message);
        Log::info($logMessage);

        // Also write to a dedicated accessible debug file for instant inspection
        $debugFile = storage_path('logs/whatsapp_otp.log');
        file_put_contents($debugFile, date('[Y-m-d H:i:s] ') . $logMessage . PHP_EOL, FILE_APPEND);

        return [
            'success' => true,
            'simulated' => true,
            'message' => 'Kode OTP berhasil disimulasikan dan dikirim (Log aktif).',
            'debug_otp' => app()->environment('local', 'testing') ? $otp : null,
        ];
    }

    /**
     * Send Automated Booking Confirmation & Digital E-Voucher via WhatsApp
     */
    public static function sendBookingConfirmation(\App\Models\Transaksi $transaksi): array
    {
        $phone = $transaksi->no_hp_pelanggan ?: ($transaksi->no_hp_offline ?: ($transaksi->user->telepon ?? ''));
        if (empty($phone)) {
            return ['success' => false, 'message' => 'Nomor WhatsApp pelanggan tidak ditemukan.'];
        }

        $formattedPhone = self::formatNumber($phone);
        $carName = $transaksi->mobil ? ($transaksi->mobil->merk . ' ' . $transaksi->mobil->nama_mobil) : 'Armada Pilihan';
        $layananText = $transaksi->layanan === 'dengan_sopir' ? 'DENGAN SOPIR' : 'LEPAS KUNCI (SELF-DRIVE)';
        $totalFormatted = 'Rp ' . number_format($transaksi->total_harga, 0, ',', '.');
        $voucherUrl = url('/transaksi/' . $transaksi->id);

        $driverInfo = '';
        if ($transaksi->layanan === 'dengan_sopir') {
            if ($transaksi->nama_sopir_assigned) {
                $driverInfo = "\n👤 *Kontak Driver Resmi:*\n"
                    . "• Nama: {$transaksi->nama_sopir_assigned}\n"
                    . "• No. HP: {$transaksi->no_hp_sopir_assigned}\n"
                    . "Driver kami akan menghubungi Anda 1 jam sebelum waktu penjemputan.\n";
            } else {
                $driverInfo = "\n👤 *Status Driver:* Tim operasional sedang mengalokasikan driver terbaik untuk Anda.\n";
            }
        }

        $message = "✨ *QUANTUM STREAMLINE — RESERVASI DIKONFIRMASI* ✨\n\n"
            . "Halo *" . ($transaksi->user->name ?? $transaksi->nama_pelanggan_offline ?? 'Pelanggan Terhormat') . "*,\n"
            . "Pemesanan armada luxury Anda telah berhasil diverifikasi dan terkonfirmasi di sistem kami.\n\n"
            . "📋 *RINCIAN RESERVASI:*\n"
            . "• No. Booking: *{$transaksi->nomor_booking}*\n"
            . "• Unit Armada: *{$carName}*\n"
            . "• Layanan: *{$layananText}*\n"
            . "• Mulai: *{$transaksi->tanggal_mulai->format('d M Y')} {$transaksi->jam_mulai} WIB*\n"
            . "• Selesai: *{$transaksi->tanggal_selesai->format('d M Y')} {$transaksi->jam_selesai} WIB*\n"
            . "• Lokasi Jemput: *{$transaksi->lokasi_jemput}*\n"
            . "• Total Biaya: *{$totalFormatted}* (Status: *LUNAS*)\n"
            . $driverInfo . "\n"
            . "🎟️ *E-VOUCHER RESMI:*\n"
            . "Tunjukkan E-Voucher digital ini saat serah terima unit:\n"
            . "👉 {$voucherUrl}\n\n"
            . "📞 *Hotline Layanan 24/7:*\n"
            . "Butuh bantuan darurat / penyesuaian jadwal? Hubungi CS kami di +62 812-3456-7890.\n\n"
            . "_Terima kasih telah memilih Quantum Streamline._";

        $token = config('services.fonnte.token') ?? env('FONNTE_TOKEN');

        if (!empty($token)) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => $token,
                ])->post('https://api.fonnte.com/send', [
                    'target' => $formattedPhone,
                    'message' => $message,
                ]);

                if ($response->successful()) {
                    $transaksi->update(['whatsapp_notified_at' => now()]);
                    return [
                        'success' => true,
                        'message' => 'Notifikasi E-Voucher WhatsApp berhasil dikirim.',
                        'provider' => 'Fonnte',
                    ];
                }
            } catch (\Exception $e) {
                Log::warning("Fonnte booking confirmation dispatch failed: {$e->getMessage()}");
            }
        }

        // Development / Fallback Logging
        $logEntry = "[WHATSAPP BOOKING CONFIRMATION] To: {$formattedPhone} | Booking: {$transaksi->nomor_booking} | Message: " . str_replace("\n", " ", $message);
        Log::info($logEntry);

        $debugFile = storage_path('logs/whatsapp_notifications.log');
        file_put_contents($debugFile, date('[Y-m-d H:i:s] ') . $logEntry . PHP_EOL, FILE_APPEND);

        $transaksi->update(['whatsapp_notified_at' => now()]);

        return [
            'success' => true,
            'simulated' => true,
            'message' => 'Notifikasi WhatsApp berhasil disimulasikan & dicatat di log.',
        ];
    }

    /**
     * Send Driver Assignment Notification via WhatsApp
     */
    public static function sendDriverAssigned(\App\Models\Transaksi $transaksi, string $driverName, string $driverPhone): array
    {
        $phone = $transaksi->no_hp_pelanggan ?: ($transaksi->no_hp_offline ?: ($transaksi->user->telepon ?? ''));
        if (empty($phone)) {
            return ['success' => false, 'message' => 'Nomor WhatsApp tidak ditemukan.'];
        }

        $formattedPhone = self::formatNumber($phone);
        $message = "🚗 *QUANTUM STREAMLINE — DETAIL DRIVER PENJEMPUTAN*\n\n"
            . "Halo *" . ($transaksi->user->name ?? 'Pelanggan') . "*,\n"
            . "Driver resmi Anda untuk Booking *#{$transaksi->nomor_booking}* telah siap:\n\n"
            . "• Nama Driver: *{$driverName}*\n"
            . "• No. WhatsApp/Telp: *{$driverPhone}*\n"
            . "• Unit Armada: *{$transaksi->mobil->nama_mobil}* (" . ($transaksi->mobil->nopol ?? 'Mulus & Wangi') . ")\n"
            . "• Lokasi Penjemputan: *{$transaksi->lokasi_jemput}*\n\n"
            . "Driver kami akan standby tepat waktu di lokasi. Selamat menikmati perjalanan Anda!";

        $token = config('services.fonnte.token') ?? env('FONNTE_TOKEN');

        if (!empty($token)) {
            try {
                Http::withHeaders(['Authorization' => $token])->post('https://api.fonnte.com/send', [
                    'target' => $formattedPhone,
                    'message' => $message,
                ]);
            } catch (\Exception $e) {
                Log::warning("Fonnte driver assign dispatch failed: {$e->getMessage()}");
            }
        }

        $debugFile = storage_path('logs/whatsapp_notifications.log');
        file_put_contents($debugFile, date('[Y-m-d H:i:s] ') . "[DRIVER ASSIGNED] To: {$formattedPhone} | Driver: {$driverName} ({$driverPhone})" . PHP_EOL, FILE_APPEND);

        return ['success' => true, 'message' => 'Notifikasi detail driver terkirim.'];
    }

    /**
     * Point 5A: Send Late Return Notice via WhatsApp
     */
    public static function sendLateReturnNotice(\App\Models\Transaksi $transaksi, int $menitTerlambat, int $denda): array
    {
        $phone = $transaksi->no_hp_pelanggan ?: ($transaksi->no_hp_offline ?: ($transaksi->user->telepon ?? ''));
        if (empty($phone)) {
            return ['success' => false, 'message' => 'Nomor WhatsApp tidak ditemukan.'];
        }

        $formattedPhone = self::formatNumber($phone);
        $dendaFormatted = 'Rp ' . number_format($denda, 0, ',', '.');
        $hours = floor($menitTerlambat / 60);
        $mins = $menitTerlambat % 60;
        $timeStr = ($hours > 0 ? "{$hours} Jam " : "") . "{$mins} Menit";

        $message = "⚠️ *PERINGATAN KETERLAMBATAN PENGEMBALIAN UNIT*\n\n"
            . "Halo *" . ($transaksi->user->name ?? 'Pelanggan') . "*,\n"
            . "Unit kendaraan *{$transaksi->mobil->nama_mobil}* (Booking #{$transaksi->nomor_booking}) terdeteksi melewati jadwal pengembalian resmi.\n\n"
            . "• Waktu Terlambat: *{$timeStr}*\n"
            . "• Tagihan Denda Keterlambatan: *{$dendaFormatted}*\n\n"
            . "Sesuai ketentuan kontrak & transparansi biaya Poin 5, denda ini dihitung otomatis oleh sistem. Mohon segera serahkan unit ke petugas kami untuk menghindari akumulasi biaya sewa tambahan.\n\n"
            . "_Layanan Pelanggan Quantum Streamline_";

        $token = config('services.fonnte.token') ?? env('FONNTE_TOKEN');
        if (!empty($token)) {
            try {
                Http::withHeaders(['Authorization' => $token])->post('https://api.fonnte.com/send', [
                    'target' => $formattedPhone,
                    'message' => $message,
                ]);
            } catch (\Exception $e) {
                Log::warning("Fonnte late return notice failed: {$e->getMessage()}");
            }
        }

        $debugFile = storage_path('logs/whatsapp_notifications.log');
        file_put_contents($debugFile, date('[Y-m-d H:i:s] ') . "[LATE RETURN NOTICE] To: {$formattedPhone} | Late: {$timeStr} | Fine: {$dendaFormatted}" . PHP_EOL, FILE_APPEND);

        return ['success' => true, 'message' => 'Notifikasi keterlambatan terkirim.'];
    }

    /**
     * Point 5B: Send Rental Extension Confirmation via WhatsApp
     */
    public static function sendExtensionConfirmation(\App\Models\Transaksi $transaksi, string $oldEndDate, string $newEndDate, int $additionalCost): array
    {
        $phone = $transaksi->no_hp_pelanggan ?: ($transaksi->no_hp_offline ?: ($transaksi->user->telepon ?? ''));
        if (empty($phone)) {
            return ['success' => false, 'message' => 'Nomor WhatsApp tidak ditemukan.'];
        }

        $formattedPhone = self::formatNumber($phone);
        $costFormatted = 'Rp ' . number_format($additionalCost, 0, ',', '.');

        $message = "✨ *PERPANJANGAN SEWA BERHASIL — QUANTUM STREAMLINE*\n\n"
            . "Halo *" . ($transaksi->user->name ?? 'Pelanggan') . "*,\n"
            . "Permintaan perpanjangan sewa armada *{$transaksi->mobil->nama_mobil}* (Booking #{$transaksi->nomor_booking}) telah disetujui & diverifikasi lunas:\n\n"
            . "• Jadwal Semula: *{$oldEndDate}*\n"
            . "• Jadwal Baru Hingga: *{$newEndDate}*\n"
            . "• Biaya Tambahan Perpanjangan: *{$costFormatted}*\n\n"
            . "Unit kendaraan tetap ter-booking aman khusus untuk Anda. Terima kasih atas kepercayaan Anda berkendara bersama kami!";

        $token = config('services.fonnte.token') ?? env('FONNTE_TOKEN');
        if (!empty($token)) {
            try {
                Http::withHeaders(['Authorization' => $token])->post('https://api.fonnte.com/send', [
                    'target' => $formattedPhone,
                    'message' => $message,
                ]);
            } catch (\Exception $e) {
                Log::warning("Fonnte extension notice failed: {$e->getMessage()}");
            }
        }

        $debugFile = storage_path('logs/whatsapp_notifications.log');
        file_put_contents($debugFile, date('[Y-m-d H:i:s] ') . "[EXTENSION CONFIRMED] To: {$formattedPhone} | Until: {$newEndDate} | Cost: {$costFormatted}" . PHP_EOL, FILE_APPEND);

        return ['success' => true, 'message' => 'Notifikasi perpanjangan terkirim.'];
    }
}

