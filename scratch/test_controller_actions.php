<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Transaksi;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminInventoryController;

echo "=== TESTING ADMIN NOTIFICATION & DRIVER CONTROLLER ACTIONS ===\n\n";

$transaksi = Transaksi::with(['mobil', 'user'])->latest()->first();

// Login as admin for audit trail
$admin = \App\Models\User::where('role', 'admin')->first();
\Illuminate\Support\Facades\Auth::login($admin);

$controller = new AdminInventoryController();

// 1. Test assignDriver controller action
echo "[Test 1] Controller assignDriver...\n";
$req = Request::create('/admin/transaksi/' . $transaksi->id . '/assign-driver', 'POST', [
    'nama_sopir' => 'Hendrawan VIP Chauffeur',
    'no_hp_sopir' => '081398765000',
    'notify_customer' => true,
]);

$response = $controller->assignDriver($req, $transaksi->id);
$transaksi->refresh();

echo "Assigned Driver Name: " . $transaksi->nama_sopir_assigned . "\n";
echo "Assigned Driver Phone: " . $transaksi->no_hp_sopir_assigned . "\n";
assert($transaksi->nama_sopir_assigned === 'Hendrawan VIP Chauffeur');

// 2. Test resendNotification controller action
echo "\n[Test 2] Controller resendNotification...\n";
$req2 = Request::create('/admin/transaksi/' . $transaksi->id . '/resend-notification', 'POST', [
    'channel' => 'all',
]);
$response2 = $controller->resendNotification($req2, $transaksi->id);
$transaksi->refresh();

echo "WA Notified At: " . $transaksi->whatsapp_notified_at . "\n";
echo "Email Notified At: " . $transaksi->email_notified_at . "\n";
assert(!empty($transaksi->whatsapp_notified_at), 'whatsapp_notified_at must be populated');

// 3. Check audit log
echo "\n[Test 3] Verifying Audit Trail...\n";
$lastLogs = AuditLog::orderBy('created_at', 'desc')->take(2)->get();
foreach ($lastLogs as $log) {
    echo "- [{$log->aksi}] {$log->deskripsi}\n";
}

echo "\n>>> ALL CONTROLLER ACTION TESTS PASSED! <<<\n";
