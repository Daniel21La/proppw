<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\RentalMobil;
use App\Models\SeasonalPrice;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminInventoryController extends Controller
{
    /**
     * 1. Update Unit Status (Tersedia, Disewa, Maintenance) Real-Time
     */
    public function updateCarStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:tersedia,disewa,maintenance',
            'alasan' => 'nullable|string|max:255',
        ]);

        $mobil = RentalMobil::findOrFail($id);
        $oldStatus = $mobil->status;
        $newStatus = $request->status;

        $mobil->status = $newStatus;
        $mobil->save();

        $desc = "Mengubah status unit {$mobil->nama_mobil} ({$mobil->nopol}) dari [{$oldStatus}] ke [{$newStatus}].";
        if ($request->filled('alasan')) {
            $desc .= " Catatan: {$request->alasan}";
        }

        AuditLog::record('UPDATE_STATUS_MOBIL', $desc, 'RentalMobil', $mobil->id);

        return back()->with('success', "Status armada {$mobil->nama_mobil} berhasil diperbarui menjadi {$newStatus}.");
    }

    /**
     * 2. Seasonal Prices Management
     */
    public function seasonalPricesIndex()
    {
        $prices = SeasonalPrice::with('mobil')
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        $mobils = RentalMobil::select('id', 'nama_mobil', 'merk', 'harga_per_hari')->get();

        return Inertia::render('Admin/SeasonalPrices/Index', [
            'prices' => $prices,
            'mobils' => $mobils,
        ]);
    }

    public function storeSeasonalPrice(Request $request)
    {
        $validated = $request->validate([
            'rental_mobil_id' => 'nullable|exists:rental_mobils,id',
            'nama_event' => 'required|string|max:100',
            'tarif_per_hari' => 'required|numeric|min:0',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $seasonal = SeasonalPrice::create($validated);

        $target = $seasonal->rental_mobil_id
            ? "unit " . RentalMobil::find($seasonal->rental_mobil_id)->nama_mobil
            : "seluruh armada";

        AuditLog::record(
            'CREATE_SEASONAL_PRICE',
            "Membuat tarif musiman '{$seasonal->nama_event}' (Rp " . number_format($seasonal->tarif_per_hari, 0, ',', '.') . "/hari) untuk {$target} ({$seasonal->tanggal_mulai->format('d/m/Y')} s/d {$seasonal->tanggal_selesai->format('d/m/Y')}).",
            'SeasonalPrice',
            $seasonal->id
        );

        return back()->with('success', "Tarif musiman '{$seasonal->nama_event}' berhasil diterbitkan.");
    }

    public function deleteSeasonalPrice($id)
    {
        $seasonal = SeasonalPrice::findOrFail($id);
        $name = $seasonal->nama_event;
        $seasonal->delete();

        AuditLog::record('DELETE_SEASONAL_PRICE', "Menghapus aturan tarif musiman '{$name}'.", 'SeasonalPrice', $id);

        return back()->with('success', "Aturan tarif musiman '{$name}' telah dihapus.");
    }

    /**
     * 3. Availability Calendar & Collision Detection (Anti Double-Booking)
     */
    public function calendarIndex()
    {
        $mobils = RentalMobil::with(['transaksis' => function ($query) {
            $query->whereNotIn('status', ['dibatalkan'])
                ->select('id', 'mobil_id', 'nomor_booking', 'sumber_pesanan', 'nama_pelanggan_offline', 'tanggal_mulai', 'tanggal_selesai', 'status', 'total_harga', 'user_id')
                ->with('user:id,name');
        }])->get();

        return Inertia::render('Admin/Calendar/Index', [
            'mobils' => $mobils,
        ]);
    }

    public function storeOfflineBooking(Request $request)
    {
        $validated = $request->validate([
            'mobil_id' => 'required|exists:rental_mobils,id',
            'nama_pelanggan_offline' => 'required|string|max:150',
            'no_hp_offline' => 'required|string|max:30',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'layanan' => 'required|in:lepas_kunci,dengan_sopir',
            'catatan_admin' => 'nullable|string|max:500',
        ]);

        $mobil = RentalMobil::findOrFail($validated['mobil_id']);

        if ($mobil->status === 'maintenance') {
            return back()->with('error', "Armada {$mobil->nama_mobil} sedang dalam masa MAINTENANCE dan tidak dapat dibooking.");
        }

        // COLLISION DETECTION: Check if unit is already booked within these dates
        $conflict = Transaksi::where('mobil_id', $mobil->id)
            ->whereNotIn('status', ['dibatalkan'])
            ->where(function ($query) use ($validated) {
                $query->where('tanggal_mulai', '<=', $validated['tanggal_selesai'])
                    ->where('tanggal_selesai', '>=', $validated['tanggal_mulai']);
            })
            ->first();

        if ($conflict) {
            $existingInfo = $conflict->nomor_booking . " (" . $conflict->tanggal_mulai->format('d/m/Y') . " - " . $conflict->tanggal_selesai->format('d/m/Y') . ")";
            return back()->with('error', "BENTROK JADWAL (Anti Double-Booking): Unit {$mobil->nama_mobil} sudah memiliki pemesanan aktif pada rentang tanggal tersebut [{$existingInfo}].");
        }

        // Calculate days
        $start = new \DateTime($validated['tanggal_mulai']);
        $end = new \DateTime($validated['tanggal_selesai']);
        $days = max(1, $start->diff($end)->days + 1);

        // Check seasonal rate
        $seasonal = SeasonalPrice::getSeasonalPriceForCar($mobil->id, $validated['tanggal_mulai'], $validated['tanggal_selesai']);
        $ratePerDay = $seasonal ? $seasonal->tarif_per_hari : $mobil->harga_per_hari;

        $driverFee = ($validated['layanan'] === 'dengan_sopir') ? ($mobil->biaya_sopir_per_hari * $days) : 0;
        $grandTotal = ($ratePerDay * $days) + $driverFee;

        $bookingCode = 'RM-OFF-' . date('Y') . '-' . rand(1000, 9999);

        $transaksi = Transaksi::create([
            'user_id' => Auth::id(), // Admin creator
            'mobil_id' => $mobil->id,
            'nomor_booking' => $bookingCode,
            'sumber_pesanan' => 'offline',
            'nama_pelanggan_offline' => $validated['nama_pelanggan_offline'],
            'no_hp_offline' => $validated['no_hp_offline'],
            'layanan' => $validated['layanan'],
            'lokasi_jemput' => 'Garasi Quantum Streamline (Walk-In/Offline)',
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'jam_mulai' => '09:00',
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'jam_selesai' => '18:00',
            'total_harga' => $grandTotal,
            'biaya_sopir' => $driverFee,
            'metode_pembayaran' => 'Cash / EDC Kantor',
            'status_pembayaran' => 'lunas',
            'status' => 'dikonfirmasi',
            'catatan_admin' => $validated['catatan_admin'],
        ]);

        AuditLog::record(
            'INPUT_OFFLINE_BOOKING',
            "Membuat reservasi offline manual {$bookingCode} untuk tamu '{$validated['nama_pelanggan_offline']}' pada unit {$mobil->nama_mobil} ({$days} hari). Total: Rp " . number_format($grandTotal, 0, ',', '.'),
            'Transaksi',
            $transaksi->id
        );

        return back()->with('success', "Reservasi offline {$bookingCode} berhasil disimpan. Kalender armada telah terisi.");
    }

    /**
     * 4. 4-Stage Transaction Status Management
     * States: 'baru', 'dikonfirmasi', 'berjalan', 'selesai', 'dibatalkan'
     */
    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:baru,dikonfirmasi,berjalan,selesai,dibatalkan',
            'catatan_admin' => 'nullable|string|max:500',
        ]);

        $transaksi = Transaksi::with('mobil')->findOrFail($id);
        $oldStatus = $transaksi->status;
        $newStatus = $request->status;

        $transaksi->status = $newStatus;
        if ($request->filled('catatan_admin')) {
            $transaksi->catatan_admin = $request->catatan_admin;
        }
        $transaksi->save();

        // Update car physical status accordingly
        $mobil = $transaksi->mobil;
        if ($mobil) {
            if ($newStatus === 'berjalan') {
                $mobil->status = 'disewa';
                $mobil->save();
            } elseif ($newStatus === 'selesai' || $newStatus === 'dibatalkan') {
                // Return to available if not in maintenance
                if ($mobil->status !== 'maintenance') {
                    $mobil->status = 'tersedia';
                    $mobil->save();
                }
            }
        }

        AuditLog::record(
            'UBAH_STATUS_PESANAN',
            "Mengubah status pesanan {$transaksi->nomor_booking} dari [{$oldStatus}] menjadi [{$newStatus}]. Unit {$mobil?->nama_mobil}.",
            'Transaksi',
            $transaksi->id
        );

        return back()->with('success', "Status pesanan {$transaksi->nomor_booking} berhasil diubah menjadi {$newStatus}.");
    }

    /**
     * 5. Audit Logs
     */
    public function auditLogsIndex()
    {
        $logs = AuditLog::with('user:id,name,email')
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return Inertia::render('Admin/AuditLogs/Index', [
            'logs' => $logs,
        ]);
    }

    /**
     * Point 4: Resend WhatsApp / Email Confirmation Notifications
     */
    public function resendNotification(Request $request, $id)
    {
        $transaksi = Transaksi::with(['mobil', 'user'])->findOrFail($id);
        $channel = $request->input('channel', 'all'); // 'all', 'whatsapp', 'email'

        $waStatus = null;
        if (in_array($channel, ['all', 'whatsapp'])) {
            $waStatus = \App\Services\WhatsAppService::sendBookingConfirmation($transaksi);
        }

        $emailStatus = false;
        if (in_array($channel, ['all', 'email'])) {
            $customerEmail = $transaksi->user->email ?? null;
            if ($customerEmail) {
                try {
                    \Illuminate\Support\Facades\Mail::to($customerEmail)->send(new \App\Mail\BookingConfirmationMail($transaksi));
                    $transaksi->update(['email_notified_at' => now()]);
                    $emailStatus = true;
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning("Resend email failed: " . $e->getMessage());
                }
            }
        }

        AuditLog::record(
            'RESEND_NOTIFICATION',
            "Mengirim ulang notifikasi E-Voucher [Channel: {$channel}] untuk pesanan {$transaksi->nomor_booking} kepada " . ($transaksi->user->name ?? $transaksi->nama_pelanggan_offline ?? 'Pelanggan'),
            'Transaksi',
            $transaksi->id
        );

        return back()->with('success', "Notifikasi resmi E-Voucher telah berhasil dikirimkan ulang ke pelanggan.");
    }

    /**
     * Point 4: Assign VIP Driver & Notify Customer via WhatsApp
     */
    public function assignDriver(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_sopir' => 'required|string|max:100',
            'no_hp_sopir' => 'required|string|max:20',
            'notify_customer' => 'nullable|boolean',
        ]);

        $transaksi = Transaksi::with(['mobil', 'user'])->findOrFail($id);
        $transaksi->update([
            'nama_sopir_assigned' => $validated['nama_sopir'],
            'no_hp_sopir_assigned' => $validated['no_hp_sopir'],
        ]);

        if (!empty($validated['notify_customer'])) {
            \App\Services\WhatsAppService::sendDriverAssigned(
                $transaksi,
                $validated['nama_sopir'],
                $validated['no_hp_sopir']
            );
        }

        AuditLog::record(
            'ASSIGN_DRIVER',
            "Menugaskan driver '{$validated['nama_sopir']}' ({$validated['no_hp_sopir']}) untuk pesanan {$transaksi->nomor_booking}" . (!empty($validated['notify_customer']) ? " & dikirim ke WA tamu" : ""),
            'Transaksi',
            $transaksi->id
        );

        return back()->with('success', "Driver {$validated['nama_sopir']} berhasil ditugaskan" . (!empty($validated['notify_customer']) ? " dan rincian kontak telah dikirim ke WhatsApp tamu." : "."));
    }
}

