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
        Schema::create('barang', function (Blueprint $table) {
            $table->increments('id_barang');
            $table->string('nama', 100);
            $table->string('kategori', 50);
            $table->string('sub_kategori', 100)->nullable();
            $table->string('supplier', 100);
            $table->string('stok', 5);
            $table->string('harga', 50);
            $table->boolean('promo_aktif')->default(false);
            $table->unsignedTinyInteger('promo_persen')->nullable();
            $table->integer('harga_promo')->nullable();
            $table->dateTime('promo_mulai')->nullable();
            $table->dateTime('promo_selesai')->nullable();
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
