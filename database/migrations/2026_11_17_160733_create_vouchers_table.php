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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->increments('id_voucher');
            $table->string('kode_voucher', 50)->unique();
            $table->string('nama_voucher', 100);
            $table->enum('tipe', ['persen', 'nominal'])->default('nominal');
            $table->bigInteger('nilai');
            $table->bigInteger('maksimal_diskon')->nullable();
            $table->bigInteger('minimal_belanja')->default(0);
            $table->integer('kuota')->nullable();
            $table->integer('digunakan')->default(0);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
