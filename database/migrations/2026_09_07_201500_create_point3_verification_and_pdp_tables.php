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
        // 1. Add verification, PDP & document fields to transaksis
        Schema::table('transaksis', function (Blueprint $table) {
            $table->string('no_hp_pelanggan', 30)->nullable()->after('user_id');
            $table->string('ktp_path')->nullable()->after('status_pembayaran');
            $table->string('sim_path')->nullable()->after('ktp_path');
            $table->boolean('pdp_consent')->default(false)->after('sim_path');
            $table->timestamp('pdp_consent_at')->nullable()->after('pdp_consent');
            $table->string('pdp_consent_ip', 45)->nullable()->after('pdp_consent_at');
            $table->boolean('phone_verified')->default(false)->after('pdp_consent_ip');
            $table->timestamp('phone_verified_at')->nullable()->after('phone_verified');
            
            // Dispute & Auto-Purge Protection (UU PDP 30-day retention)
            $table->boolean('is_disputed')->default(false)->after('phone_verified_at');
            $table->text('dispute_reason')->nullable()->after('is_disputed');
            $table->timestamp('tanggal_kembali_aktual')->nullable()->after('dispute_reason');
            $table->timestamp('dokumen_purged_at')->nullable()->after('tanggal_kembali_aktual');
        });

        // 2. Create otp_verifications table
        if (!Schema::hasTable('otp_verifications')) {
            Schema::create('otp_verifications', function (Blueprint $table) {
                $table->id();
                $table->string('phone_number', 30);
                $table->string('otp_code', 10);
                $table->timestamp('expires_at');
                $table->boolean('is_verified')->default(false);
                $table->timestamp('verified_at')->nullable();
                $table->integer('attempts')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_verifications');

        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn([
                'no_hp_pelanggan',
                'ktp_path',
                'sim_path',
                'pdp_consent',
                'pdp_consent_at',
                'pdp_consent_ip',
                'phone_verified',
                'phone_verified_at',
                'is_disputed',
                'dispute_reason',
                'tanggal_kembali_aktual',
                'dokumen_purged_at',
            ]);
        });
    }
};
