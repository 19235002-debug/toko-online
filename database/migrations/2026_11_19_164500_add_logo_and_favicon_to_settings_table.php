<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('logo_toko', 255)->nullable()->after('nama_toko');
            $table->string('favicon', 255)->nullable()->after('logo_toko');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['logo_toko', 'favicon']);
        });
    }
};

