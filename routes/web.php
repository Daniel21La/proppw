<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RentalMobilController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (Auth::check() && Auth::user()->role === 'admin') {
        return redirect()->route('admin.transaksi.index');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard', function () {
    if (Auth::check() && Auth::user()->role === 'user') {
        return redirect()->route('Transaksi.create');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth', \App\Http\Middleware\IsAdmin::class])->prefix('admin')->group(function () {

    Route::get('/transaksi', [RentalMobilController::class, 'adminIndex'])->name('admin.transaksi.index');
    Route::get('/mobil/form/{id?}', [RentalMobilController::class, 'form'])->name('admin.rentalmobil.form');
    Route::post('/mobil/form/{id?}', [RentalMobilController::class, 'save'])->name('admin.rentalmobil.save');
    Route::get('/mobil', [RentalMobilController::class, 'index'])->name('admin.rentalmobil.index');
    Route::get('/laporan', [RentalMobilController::class, 'laporan'])->name('admin.laporan.index');

    Route::post('/transaksi/{id}/setujui', [App\Http\Controllers\RentalMobilController::class, 'setujuiTransaksi'])->name('admin.transaksi.setujui');
    Route::post('/transaksi/{id}/tolak', [App\Http\Controllers\RentalMobilController::class, 'tolakTransaksi'])->name('admin.transaksi.tolak');
});

Route::middleware(['auth', \App\Http\Middleware\IsUser::class])->group(function () {
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('Transaksi.index');
    Route::get('/transaksi/create', [TransaksiController::class, 'create'])->name('Transaksi.create');

    Route::post('/transaksi/store', [TransaksiController::class, 'store'])->name('Transaksi.store');
});

require __DIR__ . '/auth.php';
