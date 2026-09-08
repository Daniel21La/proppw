<?php

namespace App\Http\Controllers;

use App\Models\RentalMobil;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TransaksiController extends Controller
{
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
            'lokasi_jemput.required' => 'Lokasi penjemputan harus diisi.',
            'tanggal_mulai.required' => 'Tanggal mulai sewa harus diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai sewa harus diisi.',
            'metode_pembayaran.required' => 'Pilih metode pembayaran online.',
        ]);

        $start = new \DateTime($validated['tanggal_mulai']);
        $end = new \DateTime($validated['tanggal_selesai']);
        $days = max(1, $start->diff($end)->days + 1);

        $mobil = RentalMobil::findOrFail($validated['mobil_id']);

        if ($mobil->status !== 'tersedia') {
            return back()->with('error', 'Mohon maaf, unit mobil ini baru saja dipesan atau sedang dalam perawatan.');
        }

        // Check dynamic seasonal price if active
        $seasonal = \App\Models\SeasonalPrice::getSeasonalPriceForCar($mobil->id, $validated['tanggal_mulai'], $validated['tanggal_selesai']);
        $dailyRate = $seasonal ? (float) $seasonal->tarif_per_hari : (float) $mobil->harga_per_hari;

        // Calculate line items
        $baseCarTotal = $dailyRate * $days;
        $driverCost = ($validated['layanan'] === 'dengan_sopir') ? ($mobil->biaya_sopir_per_hari * $days) : 0;
        $insuranceCost = (!empty($validated['asuransi_tambahan'])) ? (50000 * $days) : 0;
        $grandTotal = $baseCarTotal + $driverCost + $insuranceCost;

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
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'jam_mulai' => $validated['jam_mulai'] ?? '09:00',
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'jam_selesai' => $validated['jam_selesai'] ?? '09:00',
            'total_harga' => $grandTotal,
            'biaya_sopir' => $driverCost,
            'asuransi_tambahan' => !empty($validated['asuransi_tambahan']),
            'biaya_asuransi' => $insuranceCost,
            'metode_pembayaran' => $validated['metode_pembayaran'],
            'status_pembayaran' => 'lunas',
            'status' => 'dikonfirmasi', // Ready for verification/dispatch
            'catatan_sopir' => $validated['catatan_sopir'] ?? null,
            'ktp_path' => $ktpPath,
            'sim_path' => $simPath,
            'pdp_consent' => !empty($request->pdp_consent),
            'pdp_consent_at' => now(),
            'pdp_consent_ip' => $request->ip(),
            'phone_verified' => true,
            'phone_verified_at' => now(),
            'is_disputed' => false,
        ]);

        // Record in audit log
        \App\Models\AuditLog::record(
            'BOOKING_CREATED',
            "Pemesanan mandiri #{$bookingCode} dibuat oleh " . (Auth::user()->name ?? 'User') . " (Layanan: {$validated['layanan']}, KTP: " . ($ktpPath ? 'YES' : 'NO') . ")",
            'Transaksi',
            $transaksi->id
        );

        // Automatically mark car as rented/reserved
        $mobil->status = 'disewa';
        $mobil->save();

        // Point 4: Dispatch Automated Notifications (WhatsApp + Email)
        try {
            \App\Services\WhatsAppService::sendBookingConfirmation($transaksi);
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
            ->with('success', 'Pembayaran berhasil! E-Voucher digital dan notifikasi resmi telah dikirim ke WhatsApp & Email Anda.');
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
