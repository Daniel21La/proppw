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
        Schema::table('transaksis', function (Blueprint $table) {
            $table->timestamp('whatsapp_notified_at')->nullable()->after('dokumen_purged_at');
            $table->timestamp('email_notified_at')->nullable()->after('whatsapp_notified_at');
            $table->string('nama_sopir_assigned')->nullable()->after('catatan_sopir');
            $table->string('no_hp_sopir_assigned', 30)->nullable()->after('nama_sopir_assigned');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn([
                'whatsapp_notified_at',
                'email_notified_at',
                'nama_sopir_assigned',
                'no_hp_sopir_assigned',
            ]);
        });
    }
};
