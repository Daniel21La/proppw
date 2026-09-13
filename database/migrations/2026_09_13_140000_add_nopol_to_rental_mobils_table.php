<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('rental_mobils', 'nopol')) {
            Schema::table('rental_mobils', function (Blueprint $table) {
                $table->string('nopol', 30)->nullable()->after('nama_mobil');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('rental_mobils', 'nopol')) {
            Schema::table('rental_mobils', function (Blueprint $table) {
                $table->dropColumn('nopol');
            });
        }
    }
};
