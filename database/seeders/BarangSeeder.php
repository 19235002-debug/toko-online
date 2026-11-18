<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Barang::create([
            'nama' => 'Whitey Shirt Exclusive',
            'kategori' => 'Men',
            'supplier' => 'Viola Official',
            'stok' => '25',
            'harga' => '150000',
            'gambar' => null,
        ]);

        Barang::create([
            'nama' => 'Sonaya Winter Sweater',
            'kategori' => 'Woman',
            'supplier' => 'Viola Official',
            'stok' => '15',
            'harga' => '220000',
            'gambar' => null,
        ]);

        Barang::create([
            'nama' => 'Simple Casual Shirt',
            'kategori' => 'Men',
            'supplier' => 'Viola Official',
            'stok' => '30',
            'harga' => '125000',
            'gambar' => null,
        ]);

        Barang::create([
            'nama' => 'Tomi Modern Shirt',
            'kategori' => 'Men',
            'supplier' => 'Viola Official',
            'stok' => '18',
            'harga' => '175000',
            'gambar' => null,
        ]);
    }
}

