<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom integrasi Biteship ke tabel orders (order id, status biteship, tracking link, area id).
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('destination_area_id', 100)->nullable()->after('destination_id');
            $table->string('destination_postal_code', 10)->nullable()->after('destination_area_id');
            $table->string('biteship_order_id', 100)->nullable()->after('snap_token');
            $table->string('biteship_status', 50)->nullable()->after('biteship_order_id');
            $table->text('biteship_tracking_url')->nullable()->after('biteship_status');
            $table->text('biteship_label_url')->nullable()->after('biteship_tracking_url');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'destination_area_id',
                'destination_postal_code',
                'biteship_order_id',
                'biteship_status',
                'biteship_tracking_url',
                'biteship_label_url',
            ]);
        });
    }
};
