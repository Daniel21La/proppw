<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rental_mobils', function (Blueprint $table) {
            $table->string('tipe_kendaraan')->default('Sedan Eksekutif')->after('nama_mobil');
            $table->integer('kapasitas_penumpang')->default(5)->after('tipe_kendaraan');
            $table->string('transmisi')->default('Automatic')->after('kapasitas_penumpang');
            $table->integer('biaya_sopir_per_hari')->default(150000)->after('harga_per_hari');
        });

        Schema::table('transaksis', function (Blueprint $table) {
            $table->string('nomor_booking')->nullable()->after('id');
            $table->string('layanan')->default('lepas_kunci')->after('mobil_id');
            $table->string('lokasi_jemput')->nullable()->after('layanan');
            $table->string('jam_mulai')->default('09:00')->after('tanggal_mulai');
            $table->string('jam_selesai')->default('09:00')->after('tanggal_selesai');
            $table->integer('biaya_sopir')->default(0)->after('total_harga');
            $table->boolean('asuransi_tambahan')->default(false)->after('biaya_sopir');
            $table->integer('biaya_asuransi')->default(0)->after('asuransi_tambahan');
            $table->string('metode_pembayaran')->nullable()->after('biaya_asuransi');
            $table->string('status_pembayaran')->default('lunas')->after('metode_pembayaran');
            $table->text('catatan_sopir')->nullable()->after('status_pembayaran');
        });
    }

    public function down(): void
    {
        Schema::table('rental_mobils', function (Blueprint $table) {
            $table->dropColumn(['tipe_kendaraan', 'kapasitas_penumpang', 'transmisi', 'biaya_sopir_per_hari']);
        });

        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn([
                'nomor_booking', 'layanan', 'lokasi_jemput', 'jam_mulai', 'jam_selesai',
                'biaya_sopir', 'asuransi_tambahan', 'biaya_asuransi', 'metode_pembayaran',
                'status_pembayaran', 'catatan_sopir'
            ]);
        });
    }
};
