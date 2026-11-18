<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kategoris', function (Blueprint $table) {
            $table->increments('id_kategori');
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('nama_kategori', 100);
            $table->string('slug', 100)->unique();
            $table->string('gambar')->nullable();
            $table->integer('urutan')->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')
                ->references('id_kategori')
                ->on('kategoris')
                ->onDelete('cascade');
        });

        // Isi struktur kategori utama & sub-kategori awal
        $hierarchy = [
            'Wanita' => [
                'Luaran',
                'T-Shirt, Sweat, & Fleece',
                'Fleece',
                'Kemeja, Blus, & Polo',
                'Sweater & Cardigan',
                'Celana',
                'Jeans',
                'Kulot & Celana Pendek',
                'Loungewear',
                'Rok & Gaun',
                'Dalaman',
                'Aksesoris',
            ],
            'Pria' => [
                'Luaran',
                'T-Shirt, Sweat, & Fleece',
                'Fleece',
                'Kemeja & Polo',
                'Sweater & Cardigan',
                'Celana',
                'Jeans',
                'Celana Pendek',
                'Loungewear',
                'Dalaman',
                'Aksesoris',
            ],
            'Anak' => [
                'Luaran',
                'T-Shirt & Sweat',
                'Kemeja & Gaun',
                'Celana & Legging',
                'Piyama & Loungewear',
                'Dalaman & Kaus Kaki',
                'Aksesoris',
            ],
            'Bayi' => [
                'Newborn (0-6 Bulan)',
                'Bodysuit & Setelan',
                'Atasan Bayi',
                'Celana & Legging Bayi',
                'Piyama Bayi',
                'Aksesoris Bayi',
            ],
        ];

        $mainOrder = 1;
        foreach ($hierarchy as $mainName => $subs) {
            $parentId = DB::table('kategoris')->insertGetId([
                'parent_id' => null,
                'nama_kategori' => $mainName,
                'slug' => Str::slug($mainName),
                'urutan' => $mainOrder++,
                'keterangan' => 'Koleksi lengkap kategori ' . $mainName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $subOrder = 1;
            foreach ($subs as $subName) {
                DB::table('kategoris')->insert([
                    'parent_id' => $parentId,
                    'nama_kategori' => $subName,
                    'slug' => Str::slug($mainName . '-' . $subName),
                    'urutan' => $subOrder++,
                    'keterangan' => 'Sub-kategori ' . $subName . ' untuk ' . $mainName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategoris');
    }
};
