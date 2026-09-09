<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\RentalMobil;
use App\Models\SeasonalPrice;
use App\Models\Transaksi;
use App\Services\GoogleMapsService;
use App\Services\WhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingApiController extends Controller
{
    /**
     * Get authenticated user's booking history
     */
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $query = Transaksi::with(['mobil'])
            ->orderBy('created_at', 'desc');

        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->get()->map(function ($trx) {
            return [
                'id' => $trx->id,
                'nomor_booking' => $trx->nomor_booking,
                'mobil' => $trx->mobil ? [
                    'id' => $trx->mobil->id,
                    'nama_mobil' => $trx->mobil->nama_mobil,
                    'merk' => $trx->mobil->merk,
                    'nopol' => $trx->mobil->nopol,
                    'gambar_url' => $trx->mobil->gambar_url,
                ] : null,
                'layanan' => $trx->layanan,
                'lokasi_jemput' => $trx->lokasi_jemput,
                'tanggal_mulai' => $trx->tanggal_mulai ? $trx->tanggal_mulai->format('Y-m-d') : null,
                'jam_mulai' => $trx->jam_mulai,
                'tanggal_selesai' => $trx->tanggal_selesai ? $trx->tanggal_selesai->format('Y-m-d') : null,
                'jam_selesai' => $trx->jam_selesai,
                'total_harga' => (int) $trx->total_harga,
                'status' => $trx->status,
                'status_pembayaran' => $trx->status_pembayaran,
                'nama_sopir_assigned' => $trx->nama_sopir_assigned,
                'no_hp_sopir_assigned' => $trx->no_hp_sopir_assigned,
                'created_at' => $trx->created_at->toIso8601String(),
            ];
        });

        return response()->json([
            'success' => true,
            'count' => $bookings->count(),
            'data' => $bookings,
        ]);
    }

    /**
     * Get detail of single booking
     */
    public function show($id): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $trx = Transaksi::with(['mobil', 'user'])->find($id);

        if (!$trx) {
            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan.',
            ], 404);
        }

        if ($user->role !== 'admin' && $trx->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki hak akses ke pesanan ini.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $trx->id,
                'nomor_booking' => $trx->nomor_booking,
                'user' => [
                    'id' => $trx->user->id ?? null,
                    'name' => $trx->user->name ?? $trx->nama_pelanggan_offline ?? 'Pelanggan',
                    'email' => $trx->user->email ?? null,
                ],
                'mobil' => $trx->mobil ? [
                    'id' => $trx->mobil->id,
                    'nama_mobil' => $trx->mobil->nama_mobil,
                    'merk' => $trx->mobil->merk,
                    'nopol' => $trx->mobil->nopol,
                    'transmisi' => $trx->mobil->transmisi,
                    'gambar_url' => $trx->mobil->gambar_url,
                ] : null,
                'no_hp_pelanggan' => $trx->no_hp_pelanggan,
                'layanan' => $trx->layanan,
                'lokasi_jemput' => $trx->lokasi_jemput,
                'jarak_pengantaran_km' => (float) $trx->jarak_pengantaran_km,
                'biaya_pengantaran' => (int) $trx->biaya_pengantaran,
                'tanggal_mulai' => $trx->tanggal_mulai ? $trx->tanggal_mulai->format('Y-m-d') : null,
                'jam_mulai' => $trx->jam_mulai,
                'tanggal_selesai' => $trx->tanggal_selesai ? $trx->tanggal_selesai->format('Y-m-d') : null,
                'jam_selesai' => $trx->jam_selesai,
                'extra_hours' => (int) $trx->extra_hours,
                'biaya_extra_hours' => (int) $trx->biaya_extra_hours,
                'biaya_sopir' => (int) $trx->biaya_sopir,
                'asuransi_tambahan' => (bool) $trx->asuransi_tambahan,
                'biaya_asuransi' => (int) $trx->biaya_asuransi,
                'total_harga' => (int) $trx->total_harga,
                'metode_pembayaran' => $trx->metode_pembayaran,
                'status_pembayaran' => $trx->status_pembayaran,
                'status' => $trx->status,
                'nama_sopir_assigned' => $trx->nama_sopir_assigned,
                'no_hp_sopir_assigned' => $trx->no_hp_sopir_assigned,
                'is_disputed' => (bool) $trx->is_disputed,
                'dispute_reason' => $trx->dispute_reason,
                'dokumen_purged_at' => $trx->dokumen_purged_at ? $trx->dokumen_purged_at->toIso8601String() : null,
                'created_at' => $trx->created_at->toIso8601String(),
            ],
        ]);
    }

    /**
     * Create new booking via API
     */
    public function store(Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $validated = $request->validate([
            'mobil_id' => 'required|exists:rental_mobils,id',
            'no_hp_pelanggan' => 'required|string|max:30',
            'layanan' => 'required|in:tanpa_sopir,dengan_sopir',
            'lokasi_jemput' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'jam_mulai' => 'nullable|string',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jam_selesai' => 'nullable|string',
            'extra_hours' => 'nullable|integer|min:0|max:12',
            'asuransi_tambahan' => 'nullable|boolean',
            'metode_pembayaran' => 'required|string',
            'catatan_sopir' => 'nullable|string|max:500',
        ]);

        $mobil = RentalMobil::findOrFail($validated['mobil_id']);

        if ($mobil->status === 'pemeliharaan') {
            return response()->json([
                'success' => false,
                'message' => 'Unit kendaraan sedang dalam pemeliharaan rutin.',
            ], 422);
        }

        // Overlap verification
        $conflict = Transaksi::where('mobil_id', $mobil->id)
            ->whereIn('status', ['pending', 'disetujui', 'selesai'])
            ->where(function ($q) use ($validated) {
                $q->whereBetween('tanggal_mulai', [$validated['tanggal_mulai'], $validated['tanggal_selesai']])
                  ->orWhereBetween('tanggal_selesai', [$validated['tanggal_mulai'], $validated['tanggal_selesai']])
                  ->orWhere(function ($sub) use ($validated) {
                      $sub->where('tanggal_mulai', '<=', $validated['tanggal_mulai'])
                          ->where('tanggal_selesai', '>=', $validated['tanggal_selesai']);
                  });
            })
            ->first();

        if ($conflict) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal armada mengalami benturan (unit sudah terpesan untuk tanggal tersebut).',
            ], 422);
        }

        $days = (int) max(1, \Carbon\Carbon::parse($validated['tanggal_mulai'])->diffInDays(\Carbon\Carbon::parse($validated['tanggal_selesai'])) + 1);

        $seasonal = SeasonalPrice::getSeasonalPriceForCar($mobil->id, $validated['tanggal_mulai'], $validated['tanggal_selesai']);
        $dailyRate = $seasonal ? (float) $seasonal->tarif_per_hari : (float) $mobil->harga_per_hari;

        $extraHours = (int) ($validated['extra_hours'] ?? 0);
        $biayaExtraHours = $extraHours * 35000;

        $deliveryCalc = GoogleMapsService::calculateDelivery($validated['lokasi_jemput']);
        $jarakKm = $deliveryCalc['distance_km'];
        $biayaPengantaran = $deliveryCalc['delivery_cost'];

        $baseCarTotal = $dailyRate * $days;
        $driverCost = ($validated['layanan'] === 'dengan_sopir') ? ($mobil->biaya_sopir_per_hari * $days) : 0;
        $insuranceCost = (!empty($validated['asuransi_tambahan'])) ? (50000 * $days) : 0;
        $grandTotal = $baseCarTotal + $driverCost + $insuranceCost + $biayaExtraHours + $biayaPengantaran;

        $bookingCode = 'RM-' . date('Y') . '-' . rand(10000, 99999);

        $transaksi = Transaksi::create([
            'user_id' => $user->id,
            'mobil_id' => $mobil->id,
            'nomor_booking' => $bookingCode,
            'no_hp_pelanggan' => $validated['no_hp_pelanggan'],
            'layanan' => $validated['layanan'],
            'lokasi_jemput' => $validated['lokasi_jemput'],
            'jarak_pengantaran_km' => $jarakKm,
            'biaya_pengantaran' => $biayaPengantaran,
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'jam_mulai' => $validated['jam_mulai'] ?? '09:00',
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'jam_selesai' => $validated['jam_selesai'] ?? '09:00',
            'extra_hours' => $extraHours,
            'biaya_extra_hours' => $biayaExtraHours,
            'total_harga' => $grandTotal,
            'biaya_sopir' => $driverCost,
            'asuransi_tambahan' => !empty($validated['asuransi_tambahan']),
            'biaya_asuransi' => $insuranceCost,
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'status_pembayaran' => 'lunas',
            'status' => 'dikonfirmasi',
            'catatan_sopir' => $validated['catatan_sopir'] ?? null,
            'pdp_consent' => true,
            'pdp_consent_at' => now(),
            'pdp_consent_ip' => $request->ip(),
            'terms_agreed' => true,
            'terms_agreed_at' => now(),
            'phone_verified' => true,
            'phone_verified_at' => now(),
            'is_disputed' => false,
        ]);

        AuditLog::record(
            'API_BOOKING_CREATED',
            "Pemesanan via API #{$bookingCode} oleh {$user->name} (Total: Rp " . number_format($grandTotal, 0, ',', '.') . ")",
            'Transaksi',
            $transaksi->id
        );

        $mobil->status = 'disewa';
        $mobil->save();

        try {
            WhatsAppService::sendBookingConfirmation($transaksi);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning("API WA dispatch error: " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Pemesanan berhasil dibuat via API v1.',
            'data' => [
                'id' => $transaksi->id,
                'nomor_booking' => $transaksi->nomor_booking,
                'total_harga' => (int) $transaksi->total_harga,
                'status' => $transaksi->status,
                'invoice_url' => url("/transaksi/{$transaksi->id}/invoice"),
            ],
        ], 201);
    }
}
