<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Transaksi;
use App\Models\VehicleInspection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DriverTaskController extends Controller
{
    /**
     * Display Driver Dashboard with assigned tasks & inspection forms
     */
    public function index()
    {
        $user = Auth::user();

        // Get bookings assigned to this driver or active bookings with drivers
        $tasks = Transaksi::with(['mobil', 'user'])
            ->whereNotNull('nama_sopir_assigned')
            ->where(function ($q) use ($user) {
                if ($user) {
                    $q->where('nama_sopir_assigned', 'like', '%' . $user->name . '%')
                      ->orWhere('user_id', $user->id)
                      ->orWhereRaw('1 = 1'); // Driver overview mode
                }
            })
            ->orderBy('tanggal_mulai', 'asc')
            ->get()
            ->map(function ($trx) {
                $inspections = VehicleInspection::where('transaksi_id', $trx->id)->get();

                return [
                    'id' => $trx->id,
                    'nomor_booking' => $trx->nomor_booking,
                    'pelanggan' => [
                        'nama' => $trx->user->name ?? $trx->nama_pelanggan_offline ?? 'Pelanggan VIP',
                        'no_hp' => $trx->no_hp_pelanggan ?? '-',
                    ],
                    'mobil' => $trx->mobil ? [
                        'id' => $trx->mobil->id,
                        'nama_mobil' => $trx->mobil->nama_mobil,
                        'nopol' => $trx->mobil->nopol,
                        'gambar_url' => $trx->mobil->gambar_url,
                    ] : null,
                    'lokasi_jemput' => $trx->lokasi_jemput,
                    'tanggal_mulai' => $trx->tanggal_mulai ? $trx->tanggal_mulai->format('d M Y') : '-',
                    'jam_mulai' => $trx->jam_mulai ?? '09:00',
                    'tanggal_selesai' => $trx->tanggal_selesai ? $trx->tanggal_selesai->format('d M Y') : '-',
                    'jam_selesai' => $trx->jam_selesai ?? '09:00',
                    'status' => $trx->status,
                    'catatan_sopir' => $trx->catatan_sopir,
                    'nama_sopir_assigned' => $trx->nama_sopir_assigned,
                    'no_hp_sopir_assigned' => $trx->no_hp_sopir_assigned,
                    'pre_trip' => $inspections->where('tipe_inspeksi', 'pre_trip')->first(),
                    'post_trip' => $inspections->where('tipe_inspeksi', 'post_trip')->first(),
                ];
            });

        return Inertia::render('Driver/Dashboard', [
            'tasks' => $tasks,
            'driverName' => $user->name ?? 'Driver Official',
        ]);
    }

    /**
     * Store Pre-trip / Post-trip Vehicle Inspection Checklist
     */
    public function storeInspection(Request $request)
    {
        $validated = $request->validate([
            'transaksi_id' => 'required|exists:transaksis,id',
            'tipe_inspeksi' => 'required|in:pre_trip,post_trip',
            'odometer' => 'required|integer|min:0',
            'level_bbm' => 'required|integer|min:0|max:100',
            'kondisi_fisik' => 'required|in:mulus,goresan_ringan,goresan_sedang,kerusakan_perlu_perhatian',
            'kebersihan_interior' => 'required|boolean',
            'ban_serap_dan_jack' => 'required|boolean',
            'catatan' => 'nullable|string|max:1000',
        ]);

        $trx = Transaksi::findOrFail($validated['transaksi_id']);

        $inspection = VehicleInspection::updateOrCreate(
            [
                'transaksi_id' => $trx->id,
                'tipe_inspeksi' => $validated['tipe_inspeksi'],
            ],
            [
                'driver_id' => Auth::id(),
                'odometer' => $validated['odometer'],
                'level_bbm' => $validated['level_bbm'],
                'kondisi_fisik' => $validated['kondisi_fisik'],
                'kebersihan_interior' => $validated['kebersihan_interior'],
                'ban_serap_dan_jack' => $validated['ban_serap_dan_jack'],
                'catatan' => $validated['catatan'] ?? null,
            ]
        );

        $label = $validated['tipe_inspeksi'] === 'pre_trip' ? 'Pre-trip (Sebelum Perjalanan)' : 'Post-trip (Setelan Perjalanan/Serah Terima)';

        AuditLog::record(
            'VEHICLE_INSPECTION_SUBMITTED',
            "Driver " . (Auth::user()->name ?? 'Official') . " mengisi checklist inspeksi kendaraan {$label} untuk booking #{$trx->nomor_booking} (Odometer: {$validated['odometer']} km, BBM: {$validated['level_bbm']}%)",
            'VehicleInspection',
            $inspection->id
        );

        return back()->with('success', "Checklist inspeksi {$label} berhasil disimpan!");
    }
}
