<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class InvoiceController extends Controller
{
    /**
     * Render Digital E-Voucher & Official Invoice Print-Friendly View
     */
    public function show(Request $request, $id): Response
    {
        $transaksi = Transaksi::with(['mobil', 'user'])->findOrFail($id);

        // Security check: Only transaction owner or Admin can view invoice
        $user = Auth::user();
        if (!$user) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        if ($user->role !== 'admin' && $transaksi->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses ke E-Voucher / Invoice ini.');
        }

        return Inertia::render('User/Transactions/Invoice', [
            'transaksi' => [
                'id' => $transaksi->id,
                'nomor_booking' => $transaksi->nomor_booking,
                'user' => [
                    'name' => $transaksi->user->name ?? $transaksi->nama_pelanggan_offline ?? 'Pelanggan VIP',
                    'email' => $transaksi->user->email ?? '-',
                    'phone' => $transaksi->no_hp_pelanggan ?? '-',
                ],
                'mobil' => $transaksi->mobil ? [
                    'id' => $transaksi->mobil->id,
                    'nama_mobil' => $transaksi->mobil->nama_mobil,
                    'merk' => $transaksi->mobil->merk,
                    'nopol' => $transaksi->mobil->nopol,
                    'tipe_kendaraan' => $transaksi->mobil->tipe_kendaraan,
                    'transmisi' => $transaksi->mobil->transmisi,
                    'harga_per_hari' => $transaksi->mobil->harga_per_hari,
                    'biaya_sopir_per_hari' => $transaksi->mobil->biaya_sopir_per_hari,
                    'gambar' => $transaksi->mobil->gambar,
                    'gambar_url' => $transaksi->mobil->gambar_url,
                ] : null,
                'layanan' => $transaksi->layanan,
                'lokasi_jemput' => $transaksi->lokasi_jemput,
                'jarak_pengantaran_km' => $transaksi->jarak_pengantaran_km,
                'biaya_pengantaran' => $transaksi->biaya_pengantaran,
                'tanggal_mulai' => $transaksi->tanggal_mulai ? $transaksi->tanggal_mulai->format('d M Y') : '-',
                'jam_mulai' => $transaksi->jam_mulai ?? '09:00',
                'tanggal_selesai' => $transaksi->tanggal_selesai ? $transaksi->tanggal_selesai->format('d M Y') : '-',
                'jam_selesai' => $transaksi->jam_selesai ?? '09:00',
                'extra_hours' => $transaksi->extra_hours,
                'biaya_extra_hours' => $transaksi->biaya_extra_hours,
                'biaya_sopir' => $transaksi->biaya_sopir,
                'asuransi_tambahan' => $transaksi->asuransi_tambahan,
                'biaya_asuransi' => $transaksi->biaya_asuransi,
                'total_harga' => $transaksi->total_harga,
                'metode_pembayaran' => $transaksi->metode_pembayaran,
                'status_pembayaran' => $transaksi->status_pembayaran,
                'status' => $transaksi->status,
                'nama_sopir_assigned' => $transaksi->nama_sopir_assigned,
                'no_hp_sopir_assigned' => $transaksi->no_hp_sopir_assigned,
                'is_disputed' => $transaksi->is_disputed,
                'dokumen_purged_at' => $transaksi->dokumen_purged_at ? $transaksi->dokumen_purged_at->format('d M Y H:i') : null,
                'created_at' => $transaksi->created_at->format('d M Y H:i'),
            ],
            'company' => [
                'name' => 'QUANTUM STREAMLINE RENT CAR',
                'tagline' => 'Premium Car Rental & Luxury Fleet Logistics',
                'address' => 'Jl. Kebon Jeruk No. 88, Jakarta Barat 11530',
                'phone' => '+62 812-3456-7890',
                'email' => 'support@quantumrent.co.id',
                'website' => 'https://quantumrent.co.id',
            ],
        ]);
    }
}
