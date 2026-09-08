<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rental_mobils', function (Blueprint $table) {
            $table->string('status', 50)->default('tersedia')->change();
        });

        Schema::table('transaksis', function (Blueprint $table) {
            $table->string('status', 50)->default('baru')->change();
        });
    }

    public function down(): void
    {
        // No-op rollback
    }
};
