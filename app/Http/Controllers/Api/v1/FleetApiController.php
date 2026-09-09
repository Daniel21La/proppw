<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\RentalMobil;
use App\Models\SeasonalPrice;
use App\Models\Transaksi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FleetApiController extends Controller
{
    /**
     * Display listing of available vehicles catalog
     */
    public function index(Request $request): JsonResponse
    {
        $query = RentalMobil::query();

        if ($request->filled('merk')) {
            $query->where('merk', 'like', '%' . $request->merk . '%');
        }

        if ($request->filled('tipe_kendaraan')) {
            $query->where('tipe_kendaraan', $request->tipe_kendaraan);
        }

        if ($request->filled('transmisi')) {
            $query->where('transmisi', $request->transmisi);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $vehicles = $query->orderBy('nama_mobil', 'asc')->get()->map(function ($car) {
            return [
                'id' => $car->id,
                'merk' => $car->merk,
                'nama_mobil' => $car->nama_mobil,
                'nopol' => $car->nopol,
                'tipe_kendaraan' => $car->tipe_kendaraan,
                'kapasitas_penumpang' => $car->kapasitas_penumpang,
                'transmisi' => $car->transmisi,
                'harga_per_hari' => (int) $car->harga_per_hari,
                'biaya_sopir_per_hari' => (int) $car->biaya_sopir_per_hari,
                'gambar_url' => $car->gambar_url,
                'status' => $car->status,
            ];
        });

        return response()->json([
            'success' => true,
            'count' => $vehicles->count(),
            'data' => $vehicles,
        ]);
    }

    /**
     * Display vehicle detail
     */
    public function show($id): JsonResponse
    {
        $car = RentalMobil::with('seasonalPrices')->find($id);

        if (!$car) {
            return response()->json([
                'success' => false,
                'message' => 'Kendaraan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $car->id,
                'merk' => $car->merk,
                'nama_mobil' => $car->nama_mobil,
                'nopol' => $car->nopol,
                'tipe_kendaraan' => $car->tipe_kendaraan,
                'kapasitas_penumpang' => $car->kapasitas_penumpang,
                'transmisi' => $car->transmisi,
                'harga_per_hari' => (int) $car->harga_per_hari,
                'biaya_sopir_per_hari' => (int) $car->biaya_sopir_per_hari,
                'gambar_url' => $car->gambar_url,
                'status' => $car->status,
                'seasonal_prices' => $car->seasonalPrices->map(fn($sp) => [
                    'id' => $sp->id,
                    'nama_event' => $sp->nama_event,
                    'tanggal_mulai' => $sp->tanggal_mulai,
                    'tanggal_selesai' => $sp->tanggal_selesai,
                    'tarif_per_hari' => (int) $sp->tarif_per_hari,
                ]),
            ],
        ]);
    }

    /**
     * Check real-time date availability for a vehicle
     */
    public function checkAvailability(Request $request, $id): JsonResponse
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $car = RentalMobil::find($id);

        if (!$car) {
            return response()->json([
                'success' => false,
                'message' => 'Kendaraan tidak ditemukan.',
            ], 404);
        }

        if ($car->status === 'pemeliharaan') {
            return response()->json([
                'success' => true,
                'available' => false,
                'reason' => 'Unit sedang dalam masa perawatan/pemeliharaan rutin.',
            ]);
        }

        // Check for date overlaps with non-cancelled bookings
        $conflict = Transaksi::where('mobil_id', $id)
            ->whereIn('status', ['pending', 'disetujui', 'selesai'])
            ->where(function ($q) use ($request) {
                $q->whereBetween('tanggal_mulai', [$request->tanggal_mulai, $request->tanggal_selesai])
                  ->orWhereBetween('tanggal_selesai', [$request->tanggal_mulai, $request->tanggal_selesai])
                  ->orWhere(function ($sub) use ($request) {
                      $sub->where('tanggal_mulai', '<=', $request->tanggal_mulai)
                          ->where('tanggal_selesai', '>=', $request->tanggal_selesai);
                  });
            })
            ->first();

        if ($conflict) {
            return response()->json([
                'success' => true,
                'available' => false,
                'reason' => "Unit tidak tersedia pada tanggal pilihan (Telah terpesan pada booking #{$conflict->nomor_booking}).",
                'conflicting_booking' => [
                    'nomor_booking' => $conflict->nomor_booking,
                    'tanggal_mulai' => $conflict->tanggal_mulai,
                    'tanggal_selesai' => $conflict->tanggal_selesai,
                ],
            ]);
        }

        // Calculate rate estimation
        $startDate = $request->tanggal_mulai;
        $endDate = $request->tanggal_selesai;
        $days = (int) max(1, \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1);

        $seasonal = SeasonalPrice::getSeasonalPriceForCar($id, $startDate, $endDate);
        $dailyRate = $seasonal ? (int) $seasonal->tarif_per_hari : (int) $car->harga_per_hari;

        return response()->json([
            'success' => true,
            'available' => true,
            'duration_days' => $days,
            'rate_per_day' => $dailyRate,
            'is_seasonal' => $seasonal ? true : false,
            'seasonal_event' => $seasonal ? $seasonal->nama_event : null,
            'estimated_total' => $dailyRate * $days,
        ]);
    }
}
