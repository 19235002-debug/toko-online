<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Banner::truncate();

        Banner::create([
            'judul' => 'NEW SEASON',
            'subjudul' => 'Women Collection',
            'link' => '/frontend#product-grid',
            'gambar' => 'banners/slide-01.jpg',
            'urutan' => 1,
            'status' => true,
        ]);

        Banner::create([
            'judul' => 'Jackets & Coats',
            'subjudul' => 'Men New-Season',
            'link' => '/frontend#product-grid',
            'gambar' => 'banners/slide-02.jpg',
            'urutan' => 2,
            'status' => true,
        ]);

        Banner::create([
            'judul' => 'New Arrivals',
            'subjudul' => 'Men Collection',
            'link' => '/frontend#product-grid',
            'gambar' => 'banners/slide-03.jpg',
            'urutan' => 3,
            'status' => true,
        ]);
    }
}

