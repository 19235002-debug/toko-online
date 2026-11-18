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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id('id_review');
            $table->unsignedInteger('barang_id');
            $table->string('nama_reviewer', 100);
            $table->string('email_reviewer', 150)->nullable();
            $table->unsignedTinyInteger('rating')->default(5); // 1 to 5
            $table->text('komentar');
            $table->enum('status', ['approved', 'pending'])->default('approved');
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
        Schema::dropIfExists('reviews');
    }
};
