<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah detail pengiriman RajaOngkir & jejak waktu pembayaran / pengiriman.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedInteger('destination_id')->nullable()->after('alamat');
            $table->string('destination_label', 255)->nullable()->after('destination_id');
            $table->string('kurir_kode', 20)->nullable()->after('kurir');
            $table->string('layanan_kurir', 50)->nullable()->after('kurir_kode');
            $table->string('estimasi', 50)->nullable()->after('layanan_kurir');
            $table->unsignedInteger('berat')->default(0)->after('estimasi');
            $table->string('payment_type', 50)->nullable()->after('snap_token');
            $table->timestamp('paid_at')->nullable()->after('payment_type');
            $table->timestamp('shipped_at')->nullable()->after('no_resi');
            $table->timestamp('completed_at')->nullable()->after('shipped_at');
            $table->timestamp('cancelled_at')->nullable()->after('completed_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'destination_id', 'destination_label', 'kurir_kode', 'layanan_kurir', 'estimasi', 'berat',
                'payment_type', 'paid_at', 'shipped_at', 'completed_at', 'cancelled_at',
            ]);
        });
    }
};

