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
        Schema::create('kategoris', function (Blueprint $table) {
            $table->increments('id_kategori');
            $table->string('nama_kategori', 100)->unique();
            $table->string('slug', 100)->unique();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Insert initial categories based on existing products
        $defaultCategories = [
            ['nama_kategori' => 'Women', 'slug' => 'women', 'keterangan' => 'Koleksi busana dan perlengkapan wanita.'],
            ['nama_kategori' => 'Men', 'slug' => 'men', 'keterangan' => 'Koleksi pakaian dan aksesoris pria.'],
            ['nama_kategori' => 'Bag', 'slug' => 'bag', 'keterangan' => 'Tas, ransel, dan clutch trendi.'],
            ['nama_kategori' => 'Shoes', 'slug' => 'shoes', 'keterangan' => 'Sepatu kasual, formal, dan sneakers.'],
            ['nama_kategori' => 'Watches', 'slug' => 'watches', 'keterangan' => 'Jam tangan elegan pria dan wanita.'],
        ];

        foreach ($defaultCategories as $cat) {
            DB::table('kategoris')->insert(array_merge($cat, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
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
