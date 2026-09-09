<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // Point 5D: Extra hours add-on
            $table->integer('extra_hours')->default(0)->after('jam_selesai');
            $table->integer('biaya_extra_hours')->default(0)->after('extra_hours');

            // Point 5C: Google Maps Distance Matrix & Delivery Fee
            $table->decimal('jarak_pengantaran_km', 8, 2)->default(0)->after('lokasi_jemput');
            $table->integer('biaya_pengantaran')->default(0)->after('jarak_pengantaran_km');

            // Point 5A: Late return calculation & fines
            $table->integer('menit_terlambat')->default(0)->after('tanggal_kembali_aktual');
            $table->integer('denda_keterlambatan')->default(0)->after('menit_terlambat');
            $table->string('status_denda')->default('none')->after('denda_keterlambatan'); // none, belum_dibayar, lunas

            // Point 5B: Extension tracking
            $table->boolean('is_extended')->default(false)->after('status_denda');
            $table->unsignedBigInteger('extended_from_transaksi_id')->nullable()->after('is_extended');

            // Point 5E: Explicit Terms & Conditions Consent
            $table->boolean('terms_agreed')->default(false)->after('sim_path');
            $table->timestamp('terms_agreed_at')->nullable()->after('terms_agreed');
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn([
                'extra_hours',
                'biaya_extra_hours',
                'jarak_pengantaran_km',
                'biaya_pengantaran',
                'menit_terlambat',
                'denda_keterlambatan',
                'status_denda',
                'is_extended',
                'extended_from_transaksi_id',
                'terms_agreed',
                'terms_agreed_at',
            ]);
        });
    }
};
