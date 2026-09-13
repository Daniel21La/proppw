<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RentalMobil;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class LaporanExportController extends Controller
{
    /**
     * Export Admin Laporan to CSV / Excel sheet.
     */
    public function exportExcel(Request $request)
    {
        $transaksis = Transaksi::with(['mobil', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'Laporan_Keuangan_Quantum_Streamline_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($transaksis) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM for Microsoft Excel auto-detecting Indonesian character encoding
            fputs($file, "\xEF\xBB\xBF");

            // Header row
            fputcsv($file, [
                'No. Booking',
                'Tanggal Booking',
                'Nama Pelanggan',
                'Email Pelanggan',
                'No. HP Pelanggan',
                'Armada Mobil',
                'Merk / Transmisi',
                'Nomor Polisi',
                'Tanggal Mulai',
                'Tanggal Selesai',
                'Jenis Layanan',
                'Status Transaksi',
                'Total Harga (IDR)',
            ]);

            foreach ($transaksis as $t) {
                fputcsv($file, [
                    $t->nomor_booking ?? ('#TRX-' . $t->id),
                    $t->created_at ? $t->created_at->format('Y-m-d H:i') : '-',
                    $t->user->name ?? 'Pelanggan',
                    $t->user->email ?? '-',
                    $t->user->phone ?? '-',
                    $t->mobil->nama_mobil ?? 'Unit Dihapus',
                    ($t->mobil->merk ?? '-') . ' (' . ($t->mobil->transmisi ?? '-') . ')',
                    $t->mobil->nopol ?? '-',
                    $t->tanggal_mulai ?? '-',
                    $t->tanggal_selesai ?? '-',
                    $t->layanan === 'dengan_sopir' ? 'Dengan Sopir' : 'Lepas Kunci',
                    strtoupper($t->status ?? 'PENDING'),
                    (float) ($t->total_harga ?? 0),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export Executive Print-Friendly PDF View for Admin Report.
     */
    public function exportPdf(Request $request)
    {
        $transaksi = Transaksi::with(['mobil', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $keuangan = $transaksi->where('status', 'disetujui')->sum('total_harga');
        $stok_tersedia = RentalMobil::where('status', 'tersedia')->count();
        $stok_disewa = RentalMobil::where('status', 'disewa')->count();
        $total_mobil = RentalMobil::count();
        $approvedTransactions = $transaksi->where('status', 'disetujui');

        $company = [
            'name' => 'QUANTUM STREAMLINE LUXURY CAR RENTAL',
            'address' => 'Menara Sudirman Lt. 18, Jl. Jend. Sudirman No. 88, Jakarta Selatan 12190',
            'phone' => '+62 812-3456-7890',
            'email' => 'finance@quantumstreamline.com',
            'website' => 'https://quantumstreamline.com',
        ];

        return view('admin.exports.laporan_pdf', compact(
            'transaksi',
            'approvedTransactions',
            'keuangan',
            'stok_tersedia',
            'stok_disewa',
            'total_mobil',
            'company'
        ));
    }
}
