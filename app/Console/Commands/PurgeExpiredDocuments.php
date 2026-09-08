<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Models\Transaksi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PurgeExpiredDocuments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'documents:purge-expired {--dry-run : Only show matching records without deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus otomatis berkas KTP & SIM 30 hari setelah unit kembali sesuai kepatuhan UU PDP No. 27/2022 (Kecuali unit dalam status sengketa / is_disputed)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $this->info("Memulai pengecekan berkas dokumen KTP/SIM kedaluwarsa (UU PDP 30 Hari)..." . ($dryRun ? " [DRY-RUN]" : ""));

        $thresholdDate = now()->subDays(30);

        // Find eligible transactions:
        // 1. Status sewa 'selesai'
        // 2. Unit sudah kembali >= 30 hari yang lalu
        // 3. is_disputed = false (FREEZE PURGE PROTECTION)
        // 4. Dokumen belum pernah di-purge
        // 5. Masih memiliki ktp_path atau sim_path
        $transactions = Transaksi::where('status', 'selesai')
            ->where('is_disputed', false)
            ->whereNull('dokumen_purged_at')
            ->where(function ($q) {
                $q->whereNotNull('ktp_path')
                  ->orWhereNotNull('sim_path');
            })
            ->where(function ($q) use ($thresholdDate) {
                $q->where('tanggal_kembali_aktual', '<=', $thresholdDate)
                  ->orWhere(function ($sub) use ($thresholdDate) {
                      $sub->whereNull('tanggal_kembali_aktual')
                          ->where('updated_at', '<=', $thresholdDate);
                  });
            })
            ->get();

        if ($transactions->isEmpty()) {
            $this->info("Tidak ada dokumen yang memenuhi kriteria penghapusan (Semua berkas aman atau dalam masa retensi aktif).");
            return Command::SUCCESS;
        }

        $this->warn("Ditemukan {$transactions->count()} transaksi dengan dokumen kedaluwarsa yang akan dihapus:");

        $purgedCount = 0;

        foreach ($transactions as $trx) {
            $this->line("- Booking #{$trx->nomor_booking} (ID: {$trx->id}) | KTP: {$trx->ktp_path} | SIM: {$trx->sim_path}");

            if (!$dryRun) {
                // Delete physical files
                if ($trx->ktp_path) {
                    $this->deleteFile($trx->ktp_path);
                }
                if ($trx->sim_path) {
                    $this->deleteFile($trx->sim_path);
                }

                $trx->update([
                    'ktp_path' => null,
                    'sim_path' => null,
                    'dokumen_purged_at' => now(),
                ]);

                AuditLog::record(
                    'AUTO_PURGE_UU_PDP',
                    "Berkas KTP/SIM booking #{$trx->nomor_booking} dihapus permanen otomatis setelah masa retensi 30 hari unit kembali berakhir (Kepatuhan UU PDP No. 27/2022).",
                    'Transaksi',
                    $trx->id
                );

                $purgedCount++;
            }
        }

        if ($dryRun) {
            $this->info("[DRY RUN SELESAI] {$transactions->count()} berkas terdeteksi siap dihapus tanpa perubahan fisik.");
        } else {
            $this->info("Selesai! {$purgedCount} transaksi telah dipurging dokumennya secara permanen demi kepatuhan UU PDP.");
        }

        return Command::SUCCESS;
    }

    private function deleteFile(?string $relativePath): void
    {
        if (!$relativePath) return;

        $paths = [
            storage_path('app/' . $relativePath),
            storage_path('app/private/' . $relativePath),
        ];

        foreach ($paths as $path) {
            if (File::exists($path)) {
                File::delete($path);
            }
        }
    }
}
