<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->integer('ongkir_reguler')->default(15000)->after('deskripsi_toko');
            $table->integer('ongkir_express')->default(30000)->after('ongkir_reguler');
            $table->integer('min_gratis_ongkir')->default(300000)->after('ongkir_express');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['ongkir_reguler', 'ongkir_express', 'min_gratis_ongkir']);
        });
    }
};
