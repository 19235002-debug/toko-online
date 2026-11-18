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
        Schema::create('barang_variasis', function (Blueprint $table) {
            $table->increments('id_variasi');
            $table->unsignedInteger('barang_id');
            $table->string('warna', 50)->nullable();
            $table->string('ukuran', 50)->nullable();
            $table->integer('stok')->default(0);
            $table->unsignedInteger('harga')->nullable();
            $table->timestamps();

            $table->foreign('barang_id')
                  ->references('id_barang')
                  ->on('barang')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_variasis');
    }
};
