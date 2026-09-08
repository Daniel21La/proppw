<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RentalMobilController;
use App\Http\Controllers\TransaksiController;
use App\Models\RentalMobil;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    $mobils = RentalMobil::orderBy('created_at', 'desc')->get();
    return Inertia::render('Welcome', [
        'mobils' => $mobils,
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('home');

// Point 2: Public Single Car Detail Page (SEO, JSON-LD, OpenGraph)
Route::get('/mobil/{id}', [RentalMobilController::class, 'publicShow'])->name('mobil.show');

// Point 2: Dynamic XML Sitemap for Search Engines
Route::get('/sitemap.xml', [\App\Http\Controllers\SeoController::class, 'sitemap'])->name('seo.sitemap');

Route::get('/dashboard', function () {
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.transaksi.index');
        } elseif (Auth::user()->role === 'user') {
            return redirect()->route('Transaksi.create');
        }
    }
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Dedicated Login / Logout Routes
Route::get('/admin/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'create'])->name('admin.login');
Route::post('/admin/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'store'])->name('admin.login.store');
Route::post('/admin/logout', [\App\Http\Controllers\Admin\AdminAuthController::class, 'destroy'])->name('admin.logout');

Route::middleware(['auth', \App\Http\Middleware\IsAdmin::class])->prefix('admin')->group(function () {
    Route::get('/transaksi', [RentalMobilController::class, 'adminIndex'])->name('admin.transaksi.index');
    Route::post('/transaksi/{id}/update-status', [\App\Http\Controllers\Admin\AdminInventoryController::class, 'updateOrderStatus'])->name('admin.transaksi.update-status');
    Route::post('/transaksi/{id}/setujui', [RentalMobilController::class, 'setujuiTransaksi'])->name('admin.transaksi.setujui');
    Route::post('/transaksi/{id}/tolak', [RentalMobilController::class, 'tolakTransaksi'])->name('admin.transaksi.tolak');
    Route::post('/transaksi/{id}/resend-notification', [\App\Http\Controllers\Admin\AdminInventoryController::class, 'resendNotification'])->name('admin.transaksi.resend-notification');
    Route::post('/transaksi/{id}/assign-driver', [\App\Http\Controllers\Admin\AdminInventoryController::class, 'assignDriver'])->name('admin.transaksi.assign-driver');

    Route::get('/mobil', [RentalMobilController::class, 'index'])->name('admin.rentalmobil.index');
    Route::post('/mobil/{id}/status', [\App\Http\Controllers\Admin\AdminInventoryController::class, 'updateCarStatus'])->name('admin.rentalmobil.status');
    Route::get('/mobil/form/{id?}', [RentalMobilController::class, 'form'])->name('admin.rentalmobil.form');
    Route::post('/mobil/form/{id?}', [RentalMobilController::class, 'save'])->name('admin.rentalmobil.save');
    Route::delete('/mobil/{id}', [RentalMobilController::class, 'destroy'])->name('admin.rentalmobil.destroy');

    // Point 1: Calendar & Offline Reservation Anti Double-Booking
    Route::get('/kalender', [\App\Http\Controllers\Admin\AdminInventoryController::class, 'calendarIndex'])->name('admin.calendar.index');
    Route::post('/reservasi-offline', [\App\Http\Controllers\Admin\AdminInventoryController::class, 'storeOfflineBooking'])->name('admin.calendar.offline-store');

    // Point 1: Seasonal Pricing
    Route::get('/harga-musiman', [\App\Http\Controllers\Admin\AdminInventoryController::class, 'seasonalPricesIndex'])->name('admin.seasonal-prices.index');
    Route::post('/harga-musiman', [\App\Http\Controllers\Admin\AdminInventoryController::class, 'storeSeasonalPrice'])->name('admin.seasonal-prices.store');
    Route::delete('/harga-musiman/{id}', [\App\Http\Controllers\Admin\AdminInventoryController::class, 'deleteSeasonalPrice'])->name('admin.seasonal-prices.destroy');

    // Point 1: Audit Trail
    Route::get('/audit-logs', [\App\Http\Controllers\Admin\AdminInventoryController::class, 'auditLogsIndex'])->name('admin.audit-logs.index');

    // Point 3: Secure Document Review & Dispute Freeze (UU PDP)
    Route::get('/dokumen/{id}/{type}', [\App\Http\Controllers\DocumentController::class, 'show'])->name('admin.dokumen.show');
    Route::post('/transaksi/{id}/toggle-dispute', [\App\Http\Controllers\DocumentController::class, 'toggleDispute'])->name('admin.transaksi.dispute');

    Route::get('/laporan', [RentalMobilController::class, 'laporan'])->name('admin.laporan.index');
});

// Point 3: WhatsApp OTP Verification Endpoints
Route::post('/otp/send', [\App\Http\Controllers\OtpController::class, 'send'])->name('otp.send');
Route::post('/otp/verify', [\App\Http\Controllers\OtpController::class, 'verify'])->name('otp.verify');

Route::middleware(['auth'])->group(function () {
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('Transaksi.index');
    Route::get('/transaksi/create', [TransaksiController::class, 'create'])->name('Transaksi.create');
    Route::post('/transaksi/store', [TransaksiController::class, 'store'])->name('Transaksi.store');
    Route::get('/transaksi/{id}', [TransaksiController::class, 'show'])->name('Transaksi.show');
    Route::post('/transaksi/{id}/cancel', [TransaksiController::class, 'cancel'])->name('Transaksi.cancel');

    // Route aliases for lowercase compatibility
    Route::get('/user/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/user/transaksi/create', [TransaksiController::class, 'create'])->name('transaksi.create');
    Route::post('/user/transaksi/store', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('/user/transaksi/{id}', [TransaksiController::class, 'show'])->name('transaksi.show');
});

require __DIR__ . '/auth.php';
