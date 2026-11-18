<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id_setting');
            $table->string('nama_toko', 100)->default('Coza Store');
            $table->string('no_whatsapp', 30)->default('081234567890');
            $table->string('email_toko', 100)->default('admin@cozastore.com');
            $table->text('alamat_toko')->nullable();
            $table->text('rekening_bank')->nullable();
            $table->text('deskripsi_toko')->nullable();
            $table->integer('ongkir_reguler')->default(15000);
            $table->integer('ongkir_express')->default(30000);
            $table->integer('min_gratis_ongkir')->default(300000);
            $table->timestamps();
        });

        // Insert initial default store configuration
        DB::table('settings')->insert([
            'nama_toko' => 'Coza Store',
            'no_whatsapp' => '081234567890',
            'email_toko' => 'admin@cozastore.com',
            'alamat_toko' => 'Jl. Jendral Sudirman No. 123, Jakarta Pusat',
            'rekening_bank' => "BCA: 1234567890 a.n Toko Coza\nMandiri: 9876543210 a.n Toko Coza",
            'deskripsi_toko' => 'Toko online busana dan aksesoris modern dengan produk berkualitas tinggi.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
