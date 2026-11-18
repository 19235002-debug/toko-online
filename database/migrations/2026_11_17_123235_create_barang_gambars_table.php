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
        Schema::create('barang_gambars', function (Blueprint $table) {
            $table->increments('id_gambar');
            $table->unsignedInteger('barang_id');
            $table->string('gambar');
            $table->integer('urutan')->default(0);
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
        Schema::dropIfExists('barang_gambars');
    }
};
