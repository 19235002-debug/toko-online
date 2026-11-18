<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kategoris', function (Blueprint $table) {
            $table->dropUnique('kategoris_nama_kategori_unique');
            $table->unsignedInteger('parent_id')->nullable()->after('id_kategori');
            $table->string('gambar')->nullable()->after('slug');
            $table->integer('urutan')->default(0)->after('gambar');

            $table->foreign('parent_id')
                ->references('id_kategori')
                ->on('kategoris')
                ->onDelete('cascade');
        });

        Schema::table('barang', function (Blueprint $table) {
            $table->string('sub_kategori', 100)->nullable()->after('kategori');
        });

        // Reset kategori lama dan isi struktur kategori UNIQLO-style (Wanita, Pria, Anak, Bayi) beserta sub-kategorinya
        DB::table('kategoris')->delete();

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

        // Petakan produk lama agar langsung masuk ke kategori baru
        DB::table('barang')->whereIn('kategori', ['Men', 'men'])->update([
            'kategori' => 'Pria',
            'sub_kategori' => 'Kemeja & Polo',
        ]);
        DB::table('barang')->whereIn('kategori', ['Woman', 'Women', 'woman', 'women'])->update([
            'kategori' => 'Wanita',
            'sub_kategori' => 'Sweater & Cardigan',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn('sub_kategori');
        });

        Schema::table('kategoris', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'gambar', 'urutan']);
            $table->unique('nama_kategori');
        });
    }
};
