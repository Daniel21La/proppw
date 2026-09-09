<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('vehicle_inspections')) {
            Schema::create('vehicle_inspections', function (Blueprint $table) {
                $table->id();
                $table->foreignId('transaksi_id')->constrained('transaksis')->onDelete('cascade');
                $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('set null');
                $table->enum('tipe_inspeksi', ['pre_trip', 'post_trip'])->default('pre_trip');
                $table->integer('odometer')->default(0);
                $table->integer('level_bbm')->default(100); // Percentage 0 - 100%
                $table->enum('kondisi_fisik', ['mulus', 'goresan_ringan', 'goresan_sedang', 'kerusakan_perlu_perhatian'])->default('mulus');
                $table->boolean('kebersihan_interior')->default(true);
                $table->boolean('ban_serap_dan_jack')->default(true);
                $table->text('catatan')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_inspections');
    }
};
