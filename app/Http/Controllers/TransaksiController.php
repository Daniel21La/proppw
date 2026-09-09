<?php

namespace App\Http\Controllers;

use App\Models\RentalMobil;
use App\Models\Transaksi;
use App\Services\GoogleMapsService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TransaksiController extends Controller
{
    /**
     * Endpoint Async Real-Time Pengecekan Ketersediaan Tanggal Armada (Fase 2 Timeline Engine)
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'mobil_id' => 'required|exists:rental_mobils,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $mobil = RentalMobil::find($request->input('mobil_id'));

        if (!$mobil) {
            return response()->json([
                'available' => false,
                'message' => 'Unit kendaraan tidak ditemukan.',
            ], 422);
        }

        if ($mobil->status === 'pemeliharaan') {
            return response()->json([
                'available' => false,
                'message' => 'Unit sedang dalam masa pemeliharaan/servis rutin.',
            ]);
        }

        $conflict = $mobil->hasScheduleOverlap(
            $request->input('tanggal_mulai'),
            $request->input('tanggal_selesai')
        );

        if ($conflict) {
            $startDate = $conflict->tanggal_mulai instanceof \DateTimeInterface 
                ? $conflict->tanggal_mulai->format('d/m/Y') 
                : $conflict->tanggal_mulai;
            $endDate = $conflict->tanggal_selesai instanceof \DateTimeInterface 
                ? $conflict->tanggal_selesai->format('d/m/Y') 
                : $conflict->tanggal_selesai;

            return response()->json([
                'available' => false,
                'message' => "Jadwal Bentrok! Mobil telah dipesan untuk rentang tanggal tersebut ({$startDate} s/d {$endDate}). Silakan pilih tanggal atau mobil lain.",
                'conflicting_booking' => [
                    'nomor_booking' => $conflict->nomor_booking,
                    'tanggal_mulai' => $startDate,
                    'tanggal_selesai' => $endDate,
                ],
            ]);
        }

        return response()->json([
            'available' => true,
            'message' => 'Unit Tersedia pada Tanggal Ini',
        ]);
    }

    public function index()
    {
        $transaksis = Transaksi::where('user_id', Auth::id())
            ->with('mobil')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('User/Transactions/Index', [
            'transaksis' => $transaksis,
        ]);
    }

    public function create(Request $request)
    {
        $mobils = RentalMobil::where('status', 'tersedia')
            ->orderBy('harga_per_hari')
            ->get();

        $selectedMobilId = $request->query('mobil_id');
        $layanan = $request->query('layanan', 'lepas_kunci');
        $lokasi = $request->query('lokasi', '');

        return Inertia::render('User/Transactions/Create', [
            'mobils' => $mobils,
            'selectedMobilId' => $selectedMobilId ? (int) $selectedMobilId : null,
            'initialLayanan' => $layanan,
            'initialLokasi' => $lokasi,
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'mobil_id' => 'required|exists:rental_mobils,id',
            'layanan' => 'required|in:lepas_kunci,dengan_sopir',
            'no_hp_pelanggan' => 'required|string|min:9|max:16',
            'lokasi_jemput' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'nullable|string',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jam_selesai' => 'nullable|string',
            'asuransi_tambahan' => 'nullable|boolean',
            'metode_pembayaran' => 'required|string',
            'catatan_sopir' => 'nullable|string|max:500',
            // Point 5: Extra hours & Google Maps distance & explicit T&C consent
            'extra_hours' => 'nullable|integer|min:0|max:12',
            'jarak_pengantaran_km' => 'nullable|numeric|min:0',
            'biaya_pengantaran' => 'nullable|integer|min:0',
            'terms_agreed' => 'required|accepted',
        ];

        // Specific legal & document verification for Lepas Kunci (Self-Drive)
        if ($request->input('layanan') === 'lepas_kunci') {
            $rules['ktp'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:5120';
            $rules['sim'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:5120';
            $rules['pdp_consent'] = 'required|accepted';
        }

        $validated = $request->validate($rules, [
            'mobil_id.required' => 'Pilih mobil yang ingin disewa.',
            'no_hp_pelanggan.required' => 'Nomor WhatsApp wajib diisi untuk konfirmasi dan OTP.',
            'ktp.required' => 'Foto e-KTP wajib diunggah untuk layanan sewa lepas kunci.',
            'sim.required' => 'Foto SIM A wajib diunggah untuk layanan sewa lepas kunci.',
            'pdp_consent.accepted' => 'Anda wajib menyetujui pemrosesan data pribadi (UU PDP No. 27/2022).',
            'terms_agreed.accepted' => 'Anda wajib menyetujui Syarat & Ketentuan serta Kebijakan Keterlambatan dan Pembatalan.',
            'lokasi_jemput.required' => 'Lokasi penjemputan harus diisi.',
            'tanggal_mulai.required' => 'Tanggal mulai sewa harus diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai sewa harus diisi.',
            'metode_pembayaran.required' => 'Pilih metode pembayaran online.',
        ]);

        $start = new \DateTime($validated['tanggal_mulai']);
        $end = new \DateTime($validated['tanggal_selesai']);
        $days = max(1, $start->diff($end)->days + 1);

        return DB::transaction(function () use ($request, $validated, $days) {
            // Pessimistic Lock on Car Record to prevent concurrent race condition
            $mobil = RentalMobil::where('id', $validated['mobil_id'])->lockForUpdate()->firstOrFail();

            if ($mobil->status === 'pemeliharaan') {
                return back()->with('error', 'Mohon maaf, unit mobil ini sedang dalam perawatan/servis rutin.');
            }

            // Timeline-Based Overlap Check
            $conflict = $mobil->hasScheduleOverlap($validated['tanggal_mulai'], $validated['tanggal_selesai']);
            if ($conflict) {
                return back()->with('error', 'Mohon maaf, unit mobil ini telah dipesan oleh pelanggan lain pada tanggal pilihan Anda (Booking #' . $conflict->nomor_booking . '). Silakan pilih tanggal atau armada lain.');
            }

            // Check dynamic seasonal price if active
            $seasonal = \App\Models\SeasonalPrice::getSeasonalPriceForCar($mobil->id, $validated['tanggal_mulai'], $validated['tanggal_selesai']);
            $dailyRate = $seasonal ? (float) $seasonal->tarif_per_hari : (float) $mobil->harga_per_hari;

            // Point 5D: Extra hours calculation (Rp 35.000 / jam)
            $extraHours = (int) ($validated['extra_hours'] ?? 0);
            $extraHourRate = 35000;
            $biayaExtraHours = $extraHours * $extraHourRate;

            // Point 5C: Google Maps Distance calculation verification
            $deliveryCalc = GoogleMapsService::calculateDelivery($validated['lokasi_jemput']);
            $jarakKm = $validated['jarak_pengantaran_km'] ?? $deliveryCalc['distance_km'];
            $biayaPengantaran = isset($validated['biaya_pengantaran']) ? (int) $validated['biaya_pengantaran'] : $deliveryCalc['delivery_cost'];

            // Calculate total line items
            $baseCarTotal = $dailyRate * $days;
            $driverCost = ($validated['layanan'] === 'dengan_sopir') ? ($mobil->biaya_sopir_per_hari * $days) : 0;
            $insuranceCost = (!empty($validated['asuransi_tambahan'])) ? (50000 * $days) : 0;
            $grandTotal = $baseCarTotal + $driverCost + $insuranceCost + $biayaExtraHours + $biayaPengantaran;

            // Secure Document Storage on Private Disk
            $ktpPath = null;
            $simPath = null;

            if ($request->hasFile('ktp')) {
                $ktpPath = $request->file('ktp')->store('documents/ktp', 'local');
            }

            if ($request->hasFile('sim')) {
                $simPath = $request->file('sim')->store('documents/sim', 'local');
            }

            // Generate unique booking code e.g. RM-2026-92841
            $bookingCode = 'RM-' . date('Y') . '-' . rand(10000, 99999);

            $transaksi = Transaksi::create([
                'user_id' => Auth::id(),
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
                'ktp_path' => $ktpPath,
                'sim_path' => $simPath,
                'pdp_consent' => !empty($request->pdp_consent),
                'pdp_consent_at' => now(),
                'pdp_consent_ip' => $request->ip(),
                'terms_agreed' => true,
                'terms_agreed_at' => now(),
                'phone_verified' => true,
                'phone_verified_at' => now(),
                'is_disputed' => false,
            ]);

            // Record in audit log
            \App\Models\AuditLog::record(
                'BOOKING_CREATED',
                "Pemesanan mandiri #{$bookingCode} dibuat oleh " . (Auth::user()->name ?? 'User') . " (Layanan: {$validated['layanan']}, Extra Hours: +{$extraHours}h, Biaya Antar: Rp " . number_format($biayaPengantaran, 0, ',', '.') . ")",
                'Transaksi',
                $transaksi->id
            );

            // Automatically mark car as rented/reserved
            $mobil->status = 'disewa';
            $mobil->save();

            // Dispatch Automated Notifications (WhatsApp + Email)
            try {
                WhatsAppService::sendBookingConfirmation($transaksi);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning("Automated WA dispatch error: " . $e->getMessage());
            }

            try {
                $customerEmail = Auth::user()->email ?? null;
                if ($customerEmail) {
                    \Illuminate\Support\Facades\Mail::to($customerEmail)->send(new \App\Mail\BookingConfirmationMail($transaksi));
                    $transaksi->update(['email_notified_at' => now()]);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning("Automated Email dispatch error: " . $e->getMessage());
            }

            return redirect()->route('Transaksi.show', $transaksi->id)
                ->with('success', 'Pembayaran berhasil! E-Voucher digital dan rincian biaya transparan telah diterbitkan.');
        });
    }

    /**
     * Point 5C: Google Maps Distance & Delivery Cost Live API
     */
    public function calculateDeliveryCost(Request $request)
    {
        $request->validate([
            'lokasi_jemput' => 'required|string|max:255',
        ]);

        $result = GoogleMapsService::calculateDelivery($request->input('lokasi_jemput'));

        return response()->json($result);
    }

    /**
     * Point 5B: Check Extend Feasibility & Schedule Conflicts
     */
    public function checkExtend(Request $request, $id)
    {
        $transaksi = Transaksi::with('mobil')->findOrFail($id);

        if (Auth::user()->role !== 'admin' && $transaksi->user_id !== Auth::id()) {
            return response()->json(['allowed' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $request->validate([
            'durasi_hari' => 'required|integer|min:1|max:30',
        ]);

        $durasiHari = (int) $request->input('durasi_hari');

        // Check minimal 3 hours notice before scheduled return
        $endDateStr = $transaksi->tanggal_selesai instanceof \DateTimeInterface 
            ? $transaksi->tanggal_selesai->format('Y-m-d') 
            : $transaksi->tanggal_selesai;

        $scheduledEnd = \Carbon\Carbon::parse($endDateStr . ' ' . ($transaksi->jam_selesai ?: '09:00'));

        if (now()->diffInHours($scheduledEnd, false) < 3 && now()->greaterThan($scheduledEnd->subHours(3))) {
            return response()->json([
                'allowed' => false,
                'message' => 'Permintaan perpanjangan sewa wajib diajukan minimal 3 jam sebelum jadwal pengembalian yang disepakati.',
            ]);
        }

        // Calculate prospective extended date
        $currentEnd = \Carbon\Carbon::parse($endDateStr);
        $newEnd = $currentEnd->copy()->addDays($durasiHari);
        $newEndDateStr = $newEnd->format('Y-m-d');

        // Check collision query: is this same car booked by anyone else during the extended window?
        $conflict = Transaksi::where('mobil_id', $transaksi->mobil_id)
            ->where('id', '!=', $transaksi->id)
            ->whereNotIn('status', ['dibatalkan', 'ditolak'])
            ->where(function ($query) use ($currentEnd, $newEnd) {
                $query->where('tanggal_mulai', '<=', $newEnd->format('Y-m-d'))
                      ->where('tanggal_selesai', '>=', $currentEnd->copy()->addDay()->format('Y-m-d'));
            })
            ->first();

        if ($conflict) {
            $conflictDate = $conflict->tanggal_mulai instanceof \DateTimeInterface 
                ? $conflict->tanggal_mulai->format('d/m/Y') 
                : $conflict->tanggal_mulai;

            return response()->json([
                'allowed' => false,
                'has_conflict' => true,
                'message' => "Mobil {$transaksi->mobil->nama_mobil} sudah dipesan untuk tanggal {$conflictDate}, extend tidak dapat diproses demi kenyamanan pelanggan berikutnya.",
            ]);
        }

        // Calculate additional extension fee
        $seasonal = \App\Models\SeasonalPrice::getSeasonalPriceForCar($transaksi->mobil_id, $currentEnd->copy()->addDay()->format('Y-m-d'), $newEndDateStr);
        $dailyRate = $seasonal ? (float) $seasonal->tarif_per_hari : (float) $transaksi->mobil->harga_per_hari;

        $additionalCarCost = $dailyRate * $durasiHari;
        $additionalDriverCost = ($transaksi->layanan === 'dengan_sopir') 
            ? ($transaksi->mobil->biaya_sopir_per_hari * $durasiHari) 
            : 0;
        $additionalInsurance = ($transaksi->asuransi_tambahan) ? (50000 * $durasiHari) : 0;
        $totalAdditionalCost = $additionalCarCost + $additionalDriverCost + $additionalInsurance;

        return response()->json([
            'allowed' => true,
            'has_conflict' => false,
            'durasi_hari' => $durasiHari,
            'old_end_date' => $currentEnd->format('d/m/Y'),
            'new_end_date' => $newEnd->format('d/m/Y'),
            'daily_rate' => $dailyRate,
            'additional_car_cost' => $additionalCarCost,
            'additional_driver_cost' => $additionalDriverCost,
            'additional_insurance_cost' => $additionalInsurance,
            'total_additional_cost' => $totalAdditionalCost,
            'message' => 'Jadwal armada tersedia. Tagihan perpanjangan siap diproses.',
        ]);
    }

    /**
     * Point 5B: Apply & Confirm Rental Extension
     */
    public function applyExtend(Request $request, $id)
    {
        $transaksi = Transaksi::with('mobil', 'user')->findOrFail($id);

        if (Auth::user()->role !== 'admin' && $transaksi->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'durasi_hari' => 'required|integer|min:1|max:30',
            'metode_pembayaran' => 'required|string',
        ]);

        $durasiHari = (int) $request->input('durasi_hari');

        $endDateStr = $transaksi->tanggal_selesai instanceof \DateTimeInterface 
            ? $transaksi->tanggal_selesai->format('Y-m-d') 
            : $transaksi->tanggal_selesai;

        $currentEnd = \Carbon\Carbon::parse($endDateStr);
        $newEnd = $currentEnd->copy()->addDays($durasiHari);
        $newEndDateStr = $newEnd->format('Y-m-d');

        // Re-verify conflict
        $conflict = Transaksi::where('mobil_id', $transaksi->mobil_id)
            ->where('id', '!=', $transaksi->id)
            ->whereNotIn('status', ['dibatalkan', 'ditolak'])
            ->where(function ($query) use ($currentEnd, $newEnd) {
                $query->where('tanggal_mulai', '<=', $newEnd->format('Y-m-d'))
                      ->where('tanggal_selesai', '>=', $currentEnd->copy()->addDay()->format('Y-m-d'));
            })
            ->first();

        if ($conflict) {
            return back()->with('error', "Permintaan perpanjangan gagal: Unit mobil telah dipesan oleh pelanggan lain pada jadwal tersebut.");
        }

        // Calculate additional cost
        $seasonal = \App\Models\SeasonalPrice::getSeasonalPriceForCar($transaksi->mobil_id, $currentEnd->copy()->addDay()->format('Y-m-d'), $newEndDateStr);
        $dailyRate = $seasonal ? (float) $seasonal->tarif_per_hari : (float) $transaksi->mobil->harga_per_hari;

        $additionalCarCost = $dailyRate * $durasiHari;
        $additionalDriverCost = ($transaksi->layanan === 'dengan_sopir') 
            ? ($transaksi->mobil->biaya_sopir_per_hari * $durasiHari) 
            : 0;
        $additionalInsurance = ($transaksi->asuransi_tambahan) ? (50000 * $durasiHari) : 0;
        $totalAdditionalCost = $additionalCarCost + $additionalDriverCost + $additionalInsurance;

        $oldFormattedDate = $currentEnd->format('d/m/Y');
        $newFormattedDate = $newEnd->format('d/m/Y');

        // Update transaction
        $transaksi->tanggal_selesai = $newEndDateStr;
        $transaksi->total_harga += $totalAdditionalCost;
        if ($transaksi->layanan === 'dengan_sopir') {
            $transaksi->biaya_sopir += $additionalDriverCost;
        }
        if ($transaksi->asuransi_tambahan) {
            $transaksi->biaya_asuransi += $additionalInsurance;
        }
        $transaksi->is_extended = true;
        $transaksi->save();

        // Audit Trail
        \App\Models\AuditLog::record(
            'RENTAL_EXTENDED',
            "Perpanjangan sewa booking #{$transaksi->nomor_booking} (+{$durasiHari} hari) hingga {$newFormattedDate}. Biaya tambahan: Rp " . number_format($totalAdditionalCost, 0, ',', '.') . " (" . $request->input('metode_pembayaran') . ")",
            'Transaksi',
            $transaksi->id
        );

        // Send WhatsApp confirmation
        try {
            WhatsAppService::sendExtensionConfirmation($transaksi, $oldFormattedDate, $newFormattedDate, $totalAdditionalCost);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning("Extension WA dispatch failed: " . $e->getMessage());
        }

        return back()->with('success', "Perpanjangan sewa armada berhasil disetujui hingga {$newFormattedDate}. E-Voucher digital telah diperbarui!");
    }

    public function show($id)
    {
        $transaksi = Transaksi::with(['mobil', 'user'])->findOrFail($id);

        // Security check: only the owner or admin can view
        if (Auth::user()->role !== 'admin' && $transaksi->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        return Inertia::render('User/Transactions/Show', [
            'transaksi' => $transaksi,
        ]);
    }

    public function cancel($id)
    {
        $transaksi = Transaksi::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'disetujui'])
            ->findOrFail($id);

        $transaksi->status = 'dibatalkan';
        $transaksi->save();

        // Release car
        $mobil = $transaksi->mobil;
        if ($mobil) {
            $mobil->status = 'tersedia';
            $mobil->save();
        }

        return back()->with('success', 'Pemesanan berhasil dibatalkan dan unit telah dilepas.');
    }
}
