<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\BarangVariasi;
use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProductDummySeeder extends Seeder
{
    /**
     * Isi atau perbarui produk dummy untuk setiap kategori utama dan sub-kategori
     * dengan judul dan foto studio yang sesuai dengan kategorinya.
     */
    public function run(): void
    {
        $suppliers = [
            'Wanita' => 'Zara Studio ID',
            'Pria' => 'Erigo Apparel',
            'Anak' => 'Cotton On Kids',
            'Bayi' => 'Mothercare Essentials',
        ];

        // Pastikan seluruh gambar contoh tersedia di disk public/barang_images
        for ($i = 1; $i <= 16; $i++) {
            $num = str_pad((string) $i, 2, '0', STR_PAD_LEFT);
            $this->ensurePublicImage("images/product-{$num}.jpg", "barang_images/dummy-{$num}.jpg");
        }
        foreach ([
            'dummy-pria-luaran.jpg',
            'dummy-pria-tshirt.jpg',
            'dummy-pria-celana.jpg',
            'dummy-anak-atasan.jpg',
            'dummy-anak-celana.jpg',
            'dummy-bayi-setelan.jpg',
        ] as $genImg) {
            $this->ensurePublicImage("images/{$genImg}", "barang_images/{$genImg}");
        }

        $catalogMap = [
            'Wanita' => [
                'Luaran' => ['nama' => 'Mantel Trench Coat Olive Wanita', 'img' => 'barang_images/dummy-04.jpg'],
                'T-Shirt, Sweat, & Fleece' => ['nama' => 'Kaos Katun Grafis Kasual Wanita', 'img' => 'barang_images/dummy-08.jpg'],
                'Fleece' => ['nama' => 'Jaket Blazer Hangat Wanita', 'img' => 'barang_images/dummy-07.jpg'],
                'Kemeja, Blus, & Polo' => ['nama' => 'Kemeja Putih Klasik Wanita', 'img' => 'barang_images/dummy-02.jpg'],
                'Sweater & Cardigan' => ['nama' => 'Atasan Kasual Bordir Wanita', 'img' => 'barang_images/dummy-14.jpg'],
                'Celana' => ['nama' => 'Celana Bahan High-Waist Wanita', 'img' => 'barang_images/dummy-02.jpg'],
                'Jeans' => ['nama' => 'Celana Jeans High-Waist Wanita', 'img' => 'barang_images/dummy-13.jpg'],
                'Kulot & Celana Pendek' => ['nama' => 'Celana Pendek Denim Wanita', 'img' => 'barang_images/dummy-10.jpg'],
                'Loungewear' => ['nama' => 'Setelan Loungewear Katun Wanita', 'img' => 'barang_images/dummy-16.jpg'],
                'Rok & Gaun' => ['nama' => 'Rok Mini Denim Kancing Wanita', 'img' => 'barang_images/dummy-05.jpg'],
                'Dalaman' => ['nama' => 'Kaos Basic Dalaman Katun Wanita', 'img' => 'barang_images/dummy-01.jpg'],
                'Aksesoris' => ['nama' => 'Jam Tangan Minimalis Mesh Wanita', 'img' => 'barang_images/dummy-15.jpg'],
            ],
            'Pria' => [
                'Luaran' => ['nama' => 'Jaket Bomber Kasual Navy Pria', 'img' => 'barang_images/dummy-pria-luaran.jpg'],
                'T-Shirt, Sweat, & Fleece' => ['nama' => 'Kaos Crewneck Katun Pria', 'img' => 'barang_images/dummy-pria-tshirt.jpg'],
                'Fleece' => ['nama' => 'Jaket Fleece Hangat Pria', 'img' => 'barang_images/dummy-pria-luaran.jpg'],
                'Kemeja & Polo' => ['nama' => 'Kemeja Kotak Lengan Pendek Pria', 'img' => 'barang_images/dummy-03.jpg'],
                'Sweater & Cardigan' => ['nama' => 'Sweatshirt Crewneck Basic Pria', 'img' => 'barang_images/dummy-pria-tshirt.jpg'],
                'Celana' => ['nama' => 'Celana Chino Slim-Fit Pria', 'img' => 'barang_images/dummy-pria-celana.jpg'],
                'Jeans' => ['nama' => 'Celana Jeans Denim Indigo Pria', 'img' => 'barang_images/dummy-pria-celana.jpg'],
                'Celana Pendek' => ['nama' => 'Celana Kasual Santai Pria', 'img' => 'barang_images/dummy-pria-celana.jpg'],
                'Loungewear' => ['nama' => 'Setelan Kaos Loungewear Pria', 'img' => 'barang_images/dummy-pria-tshirt.jpg'],
                'Dalaman' => ['nama' => 'Kaos Dalaman Katun Lembut Pria', 'img' => 'barang_images/dummy-pria-tshirt.jpg'],
                'Aksesoris' => ['nama' => 'Ikat Pinggang Kulit Klasik Pria', 'img' => 'barang_images/dummy-12.jpg'],
            ],
            'Anak' => [
                'Luaran' => ['nama' => 'Jaket Hoodie Ringan Anak', 'img' => 'barang_images/dummy-anak-atasan.jpg'],
                'T-Shirt & Sweat' => ['nama' => 'Kaos Garis Katun Lembut Anak', 'img' => 'barang_images/dummy-anak-atasan.jpg'],
                'Kemeja & Gaun' => ['nama' => 'Setelan Atasan Kasual Anak', 'img' => 'barang_images/dummy-anak-atasan.jpg'],
                'Celana & Legging' => ['nama' => 'Celana Jogger Katun Anak', 'img' => 'barang_images/dummy-anak-celana.jpg'],
                'Piyama & Loungewear' => ['nama' => 'Setelan Piyama Katun Anak', 'img' => 'barang_images/dummy-anak-celana.jpg'],
                'Dalaman & Kaus Kaki' => ['nama' => 'Kaos Dalaman Katun Anak', 'img' => 'barang_images/dummy-anak-atasan.jpg'],
                'Aksesoris' => ['nama' => 'Sepatu Sneakers Kanvas Anak', 'img' => 'barang_images/dummy-09.jpg'],
            ],
            'Bayi' => [
                'Newborn (0-6 Bulan)' => ['nama' => 'Setelan Newborn Katun Organik', 'img' => 'barang_images/dummy-bayi-setelan.jpg'],
                'Bodysuit & Setelan' => ['nama' => 'Bodysuit Bayi Katun Lembut', 'img' => 'barang_images/dummy-bayi-setelan.jpg'],
                'Atasan Bayi' => ['nama' => 'Atasan Katun Lembut Bayi', 'img' => 'barang_images/dummy-bayi-setelan.jpg'],
                'Celana & Legging Bayi' => ['nama' => 'Celana & Legging Katun Bayi', 'img' => 'barang_images/dummy-bayi-setelan.jpg'],
                'Piyama Bayi' => ['nama' => 'Piyama Tidur Katun Bayi', 'img' => 'barang_images/dummy-bayi-setelan.jpg'],
                'Aksesoris Bayi' => ['nama' => 'Paket Perlengkapan Katun Bayi', 'img' => 'barang_images/dummy-bayi-setelan.jpg'],
            ],
        ];

        $mainCategories = Kategori::whereNull('parent_id')
            ->with('children')
            ->orderBy('urutan')
            ->get();

        $index = 0;

        foreach ($mainCategories as $main) {
            $subCategories = $main->children;
            if ($subCategories->isEmpty()) {
                $this->seedOrUpdateProduct($main->nama_kategori, null, $suppliers, $catalogMap, $index);
                $index++;
                continue;
            }

            foreach ($subCategories as $sub) {
                $this->seedOrUpdateProduct($main->nama_kategori, $sub->nama_kategori, $suppliers, $catalogMap, $index);
                $index++;
            }
        }
    }

    private function ensurePublicImage(string $publicRelPath, string $storageDest): void
    {
        $src = public_path($publicRelPath);
        if (file_exists($src) && !Storage::disk('public')->exists($storageDest)) {
            Storage::disk('public')->put($storageDest, file_get_contents($src));
        }
    }

    private function seedOrUpdateProduct(string $mainName, ?string $subName, array $suppliers, array $catalogMap, int $index): void
    {
        $mapped = ($subName && isset($catalogMap[$mainName][$subName]))
            ? $catalogMap[$mainName][$subName]
            : [
                'nama' => $subName ? "{$subName} {$mainName}" : "Koleksi Spesial {$mainName}",
                'img' => 'barang_images/dummy-01.jpg',
            ];

        // Jika sudah ada produk dummy di kategori & sub-kategori ini, perbarui judul & fotonya agar akurat
        $existingDummy = Barang::where('kategori', $mainName)
            ->when($subName !== null, fn($q) => $q->where('sub_kategori', $subName))
            ->when($subName === null, fn($q) => $q->whereNull('sub_kategori'))
            ->where('gambar', 'like', 'barang_images/dummy-%')
            ->first();

        if ($existingDummy) {
            $existingDummy->update([
                'nama' => mb_substr($mapped['nama'], 0, 100),
                'gambar' => $mapped['img'],
            ]);
            return;
        }

        // Jika sudah ada produk asli non-dummy di sub-kategori ini, jangan timpa
        $hasRealProduct = Barang::where('kategori', $mainName)
            ->when($subName !== null, fn($q) => $q->where('sub_kategori', $subName))
            ->when($subName === null, fn($q) => $q->whereNull('sub_kategori'))
            ->exists();

        if ($hasRealProduct) {
            return;
        }

        $basePrice = 119000 + (($index % 8) * 35000);
        $isPromo = ($index % 3 === 0);
        $promoPersen = $isPromo ? ([15, 20, 25][$index % 3]) : null;
        $hargaPromo = $isPromo ? (int) round($basePrice * (1 - ($promoPersen / 100))) : null;

        $barang = Barang::create([
            'nama' => mb_substr($mapped['nama'], 0, 100),
            'kategori' => $mainName,
            'sub_kategori' => $subName,
            'supplier' => $suppliers[$mainName] ?? 'Viola Official',
            'stok' => 30,
            'harga' => $basePrice,
            'promo_aktif' => $isPromo,
            'promo_persen' => $promoPersen,
            'harga_promo' => $hargaPromo,
            'promo_mulai' => $isPromo ? now()->subHours(2) : null,
            'promo_selesai' => $isPromo ? now()->addDays(3 + ($index % 4)) : null,
            'gambar' => $mapped['img'],
        ]);

        $sizes = $mainName === 'Bayi' ? ['0-3M', '3-6M', '6-12M'] : ($mainName === 'Anak' ? ['S', 'M', 'L'] : ['M', 'L', 'XL']);
        $colors = ['Hitam', 'Putih', 'Navy'];
        $totalVariantStock = 0;

        foreach ($sizes as $idx => $size) {
            $stokVar = 8 + (($index + $idx) % 7);
            $totalVariantStock += $stokVar;
            BarangVariasi::create([
                'barang_id' => $barang->id_barang,
                'warna' => $colors[$idx % count($colors)],
                'ukuran' => $size,
                'stok' => $stokVar,
                'harga' => null,
            ]);
        }

        $barang->update(['stok' => $totalVariantStock]);
    }
}
