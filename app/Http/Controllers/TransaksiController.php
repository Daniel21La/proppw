<?php

namespace App\Http\Controllers;

use App\Models\RentalMobil;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function index()
    {
        $Transaksi = Transaksi::where('user_id', Auth::id())->with('mobil')->get();
        return view('Transaksi.index', compact('Transaksi'));
    }

    public function create()
    {
        $mobils = RentalMobil::where('status', 'tersedia')->get();
        return view('Transaksi.create', compact('mobils'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mobil_id' => 'required|exists:rental_mobils,id',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $start = new \DateTime($validated['tanggal_mulai']);
        $end = new \DateTime($validated['tanggal_selesai']);
        $days = $start->diff($end)->days + 1;

        $mobil = RentalMobil::findOrFail($validated['mobil_id']);

        Transaksi::create([
            'user_id' => Auth::id(),
            'mobil_id' => $mobil->id,
            'tanggal_mulai' => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'total_harga' => $mobil->harga_per_hari * $days,
            'status' => 'pending',
        ]);

        return redirect()->route('Transaksi.index')->with('success', 'Transaksi berhasil diajukan.');
    }
}
