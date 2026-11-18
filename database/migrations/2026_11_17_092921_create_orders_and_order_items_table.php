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
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id_order');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('kode_order', 50)->unique();
            $table->string('nama_customer', 100);
            $table->string('no_hp', 30);
            $table->text('alamat');
            $table->text('catatan')->nullable();
            $table->string('kode_voucher', 50)->nullable();
            $table->bigInteger('diskon')->default(0);
            $table->integer('ongkir')->default(0);
            $table->string('metode_pembayaran', 50)->default('transfer');
            $table->string('snap_token')->nullable();
            $table->string('bukti_transfer')->nullable();
            $table->bigInteger('total_harga');
            $table->enum('status', ['pending', 'diproses', 'dikirim', 'selesai', 'dibatalkan'])->default('pending');
            $table->string('ekspedisi', 50)->nullable();
            $table->string('kurir', 50)->nullable();
            $table->string('no_resi', 100)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id_user')->on('users')->onDelete('set null');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id_order_item');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('barang_id')->nullable();
            $table->unsignedInteger('variasi_id')->nullable();
            $table->string('nama_barang', 100);
            $table->string('variasi', 100)->nullable();
            $table->bigInteger('harga');
            $table->integer('qty');
            $table->bigInteger('subtotal');
            $table->timestamps();

            $table->foreign('order_id')->references('id_order')->on('orders')->onDelete('cascade');
            $table->foreign('barang_id')->references('id_barang')->on('barang')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
