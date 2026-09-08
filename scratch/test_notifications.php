<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Transaksi;
use App\Models\RentalMobil;
use App\Models\User;
use App\Services\WhatsAppService;
use App\Mail\BookingConfirmationMail;
use Illuminate\Support\Facades\Mail;

echo "=== TESTING POINT 4 NOTIFICATION & VIP CHAUFFEUR SYSTEM ===\n\n";

// 1. Get or create a sample transaction
$transaksi = Transaksi::with(['mobil', 'user'])->latest()->first();

if (!$transaksi) {
    echo "No transaction found. Creating mock...\n";
    $user = User::first() ?? User::factory()->create();
    $mobil = RentalMobil::first();
    $transaksi = Transaksi::create([
        'user_id' => $user->id,
        'mobil_id' => $mobil->id,
        'nomor_booking' => 'RM-TEST-' . rand(1000, 9999),
        'no_hp_pelanggan' => '081234567890',
        'layanan' => 'dengan_sopir',
        'lokasi_jemput' => 'Hotel Indonesia Kempinski Jakarta',
        'tanggal_mulai' => now()->addDay()->toDateString(),
        'jam_mulai' => '09:00',
        'tanggal_selesai' => now()->addDays(3)->toDateString(),
        'jam_selesai' => '18:00',
        'total_harga' => 4500000,
        'biaya_sopir' => 750000,
        'metode_pembayaran' => 'QRIS Real-Time',
        'status_pembayaran' => 'lunas',
        'status' => 'dikonfirmasi',
        'sumber_pesanan' => 'online',
    ]);
}

if (empty($transaksi->no_hp_pelanggan)) {
    $transaksi->update(['no_hp_pelanggan' => '081234567890']);
    $transaksi->refresh();
}

echo "Testing on Booking: " . $transaksi->nomor_booking . "\n";
echo "No HP Pelanggan: " . $transaksi->no_hp_pelanggan . "\n";
echo "Layanan: " . $transaksi->layanan . "\n";

// 2. Test WhatsApp Service Booking Confirmation
echo "\n[Test 1] WhatsApp Booking Confirmation Dispatch...\n";
$waResult = WhatsAppService::sendBookingConfirmation($transaksi);
print_r($waResult);
assert($waResult['success'] === true, 'WA Booking Confirmation should succeed');

// 3. Test VIP Driver Assignment
echo "\n[Test 2] VIP Driver Assignment & WA Dispatch...\n";
$transaksi->update([
    'nama_sopir_assigned' => 'Budi Santoso (VIP Chauffeur)',
    'no_hp_sopir_assigned' => '081298765432',
]);
$driverResult = WhatsAppService::sendDriverAssigned($transaksi, 'Budi Santoso (VIP Chauffeur)', '081298765432');
print_r($driverResult);
assert($driverResult['success'] === true, 'Driver dispatch should succeed');

// 4. Test Email Rendering (Mailable)
echo "\n[Test 3] Rendering Luxury Dark HTML Email Mailable...\n";
$mailable = new BookingConfirmationMail($transaksi);
$renderedHtml = $mailable->render();
echo "HTML Length: " . strlen($renderedHtml) . " bytes\n";
assert(str_contains($renderedHtml, 'QUANTUM'), 'Rendered email should contain QUANTUM brand');
assert(str_contains($renderedHtml, $transaksi->nomor_booking), 'Rendered email should contain booking number');
assert(str_contains($renderedHtml, 'Budi Santoso'), 'Rendered email should contain assigned VIP driver');

// 5. Verify dedicated log file contents
echo "\n[Test 4] Verifying WhatsApp Notification Log File...\n";
$logPath = storage_path('logs/whatsapp_notifications.log');
if (file_exists($logPath)) {
    $logLines = file($logPath);
    $lastLines = array_slice($logLines, -3);
    echo "Last Log Entries:\n" . implode("", $lastLines) . "\n";
} else {
    echo "Warning: Log file not found at " . $logPath . "\n";
}

echo "\n>>> ALL POINT 4 TESTS PASSED SUCCESSFULLY! <<<\n";
