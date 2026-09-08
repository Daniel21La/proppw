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
        // 1. Create seasonal_prices table
        if (!Schema::hasTable('seasonal_prices')) {
            Schema::create('seasonal_prices', function (Blueprint $table) {
                $table->id();
                $table->foreignId('rental_mobil_id')->nullable()->constrained('rental_mobils')->onDelete('cascade');
                $table->string('nama_event'); // misal: "Lebaran Peak Season 2026", "Tahun Baru"
                $table->decimal('tarif_per_hari', 12, 2);
                $table->date('tanggal_mulai');
                $table->date('tanggal_selesai');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 2. Create audit_logs table
        if (!Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
                $table->string('action'); // misal: UPDATE_STATUS_MOBIL, INPUT_OFFLINE_BOOKING, SET_HARGA_MUSIMAN
                $table->string('entity_type')->nullable(); // misal: RentalMobil, Transaksi, SeasonalPrice
                $table->unsignedBigInteger('entity_id')->nullable();
                $table->text('description');
                $table->string('ip_address', 45)->nullable();
                $table->timestamps();
            });
        }

        // 3. Add offline booking & admin note columns to transaksis if not present
        Schema::table('transaksis', function (Blueprint $table) {
            if (!Schema::hasColumn('transaksis', 'sumber_pesanan')) {
                $table->string('sumber_pesanan')->default('online'); // 'online' | 'offline'
            }
            if (!Schema::hasColumn('transaksis', 'nama_pelanggan_offline')) {
                $table->string('nama_pelanggan_offline')->nullable();
            }
            if (!Schema::hasColumn('transaksis', 'no_hp_offline')) {
                $table->string('no_hp_offline')->nullable();
            }
            if (!Schema::hasColumn('transaksis', 'catatan_admin')) {
                $table->text('catatan_admin')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seasonal_prices');
        Schema::dropIfExists('audit_logs');
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['sumber_pesanan', 'nama_pelanggan_offline', 'no_hp_offline', 'catatan_admin']);
        });
    }
};
