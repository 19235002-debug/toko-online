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
        Schema::table('barang', function (Blueprint $table) {
            $table->boolean('promo_aktif')->default(false)->after('harga');
            $table->unsignedTinyInteger('promo_persen')->nullable()->after('promo_aktif');
            $table->integer('harga_promo')->nullable()->after('promo_persen');
            $table->dateTime('promo_mulai')->nullable()->after('harga_promo');
            $table->dateTime('promo_selesai')->nullable()->after('promo_mulai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn(['promo_aktif', 'promo_persen', 'harga_promo', 'promo_mulai', 'promo_selesai']);
        });
    }
};
