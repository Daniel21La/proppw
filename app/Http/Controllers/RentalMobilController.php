<?php

namespace App\Http\Controllers;

use App\Models\RentalMobil;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RentalMobilController extends Controller
{
    /**
     * Display car list for admin
     */
    public function index()
    {
        $mobils = RentalMobil::orderBy('created_at', 'desc')->get();
        return Inertia::render('Admin/Cars/Index', [
            'mobils' => $mobils,
        ]);
    }

    /**
     * Display transactions list for admin
     */
    public function adminIndex()
    {
        $transaksis = Transaksi::with(['mobil', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Admin/Transactions/Index', [
            'transaksis' => $transaksis,
        ]);
    }

    /**
     * Show the form for creating / editing a car
     */
    public function form($id = null)
    {
        $mobil = $id ? RentalMobil::findOrFail($id) : null;
        return Inertia::render('Admin/Cars/Form', [
            'mobil' => $mobil,
        ]);
    }

    /**
     * Save (create or update) car
     */
    public function save(Request $request, $id = null)
    {
        $validated = $request->validate([
            'merk' => 'required|string|max:100',
            'nama_mobil' => 'required|string|max:150',
            'nopol' => 'nullable|string|max:20',
            'tipe_kendaraan' => 'nullable|string|max:50',
            'transmisi' => 'nullable|string|max:50',
            'kapasitas_penumpang' => 'nullable|integer|min:1|max:20',
            'biaya_sopir_per_hari' => 'nullable|numeric|min:0',
            'harga_per_hari' => 'required|numeric|min:0',
            'gambar' => 'nullable',
            'status' => 'nullable|in:tersedia,disewa',
        ]);

        if ($request->hasFile('gambar')) {
            $request->validate([
                'gambar' => 'image|mimes:jpg,jpeg,png,webp|max:3072',
            ]);
            $file = $request->file('gambar');
            $filename = $file->hashName();
            $file->move(public_path('gambar_mobil'), $filename);
            $validated['gambar'] = $filename;
        } else {
            // Keep existing image if not uploading new one
            unset($validated['gambar']);
        }

        if (empty($validated['status'])) {
            $validated['status'] = 'tersedia';
        }

        if ($id) {
            $mobil = RentalMobil::findOrFail($id);
            $mobil->update($validated);
            $msg = 'Mobil berhasil diperbarui.';
        } else {
            RentalMobil::create($validated);
            $msg = 'Mobil baru berhasil ditambahkan.';
        }

        return redirect()->route('admin.rentalmobil.index')->with('success', $msg);
    }

    /**
     * Delete car
     */
    public function destroy($id)
    {
        $mobil = RentalMobil::findOrFail($id);
        $mobil->delete();

        return redirect()->route('admin.rentalmobil.index')->with('success', 'Mobil berhasil dihapus.');
    }

    /**
     * Approve transaction
     */
    public function setujuiTransaksi($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->status = 'disetujui';
        $transaksi->save();

        $mobil = $transaksi->mobil;
        if ($mobil) {
            $mobil->status = 'disewa';
            $mobil->save();
        }

        return back()->with('success', 'Transaksi berhasil disetujui.');
    }

    /**
     * Reject transaction
     */
    public function tolakTransaksi($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->status = 'ditolak';
        $transaksi->save();

        $mobil = $transaksi->mobil;
        if ($mobil) {
            $activeCount = Transaksi::where('mobil_id', $mobil->id)
                ->where('status', 'disetujui')
                ->count();
            if ($activeCount === 0) {
                $mobil->status = 'tersedia';
                $mobil->save();
            }
        }

        return back()->with('success', 'Transaksi telah ditolak.');
    }

    /**
     * Reports page
     */
    public function laporan(Request $request)
    {
        $transaksi = Transaksi::with(['mobil', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        $keuangan = $transaksi->where('status', 'disetujui')->sum('total_harga');
        $stok_tersedia = RentalMobil::where('status', 'tersedia')->count();
        $stok_disewa = RentalMobil::where('status', 'disewa')->count();
        $total_mobil = RentalMobil::count();
        $total_transaksi = $transaksi->count();

        return Inertia::render('Admin/Reports/Index', [
            'transaksi' => $transaksi,
            'stats' => [
                'keuangan' => (int) $keuangan,
                'stok_tersedia' => $stok_tersedia,
                'stok_disewa' => $stok_disewa,
                'total_mobil' => $total_mobil,
                'total_transaksi' => $total_transaksi,
            ],
        ]);
    }

    /**
     * Public Car Detail Page for SEO, Social Sharing & Rich Snippets
     */
    public function publicShow($id)
    {
        $mobil = RentalMobil::where('id', $id)
            ->where('status', '!=', 'maintenance')
            ->firstOrFail();

        $activeSeasonal = \App\Models\SeasonalPrice::getSeasonalPriceForCar($mobil->id, now()->toDateString(), now()->addDay()->toDateString());

        $relatedCars = RentalMobil::where('id', '!=', $mobil->id)
            ->where('status', 'tersedia')
            ->take(4)
            ->get();

        return Inertia::render('Cars/Show', [
            'mobil' => $mobil,
            'activeSeasonal' => $activeSeasonal,
            'relatedCars' => $relatedCars,
        ]);
    }
}
