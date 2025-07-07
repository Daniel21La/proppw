<?php

namespace App\Http\Controllers;

use App\Models\RentalMobil;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class RentalMobilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     // Untuk daftar mobil
public function index()
{
    $mobils = RentalMobil::all(); 
    return view('admin.rentalmobil.index', compact('mobils'));
}

// Untuk daftar transaksi
public function adminIndex()
{
    $transaksis = Transaksi::with('mobil', 'user')->get();
    return view('admin.transaksi.index', compact('transaksis'));
}
    /**
     * Show the form for creating a new resource.
     */
    public function form($id = null)
    {
        $mobil = $id ? RentalMobil::findOrFail($id) : null;
        return view('admin.rentalmobil.form', compact('mobil'));
    }

   public function save(Request $request, $id = null)
{
    $validated = $request->validate([
        'merk' => 'required',
        'nama_mobil' => 'required',
        'harga_per_hari' => 'required|integer',
        'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'status' => 'nullable|in:tersedia,disewa'
    ]);

    if ($request->hasFile('gambar')) {
        $file = $request->file('gambar');
        $filename = $file->hashName();
        $file->move(public_path('/gambar_mobil'), $filename);
        $validated['gambar'] = $filename;
    }

    if ($id) {
        $mobil = RentalMobil::findOrFail($id);
        $mobil->update($validated);
    } else {
        RentalMobil::create($validated);
    }

    return redirect()->route('admin.rentalmobil.index')->with('success', 'Mobil berhasil disimpan.');

}
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

    return back()->with('success', 'Transaksi disetujui.');
}

public function tolakTransaksi($id)
{
    $transaksi = Transaksi::findOrFail($id);
    $transaksi->status = 'ditolak'; 
    $transaksi->save();

    return back()->with('success', 'Transaksi ditolak.');
}

public function laporan(Request $request)
{
    $transaksi = Transaksi::with('mobil', 'user')->get();

    $keuangan = $transaksi->where('status', 'disetujui')->sum('total_harga');

    $stok_tersedia = RentalMobil::where('status', 'tersedia')->count();
    $stok_disewa = RentalMobil::where('status', 'disewa')->count();

    return view('admin.laporan.index', compact(
        'transaksi',
        'keuangan',
        'stok_tersedia',
        'stok_disewa'
    ));
}

}
