<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $items = Barang::take(2)->get();
        if ($items->count() > 0) {
            Review::firstOrCreate([
                'barang_id' => $items[0]->id_barang,
                'nama_reviewer' => 'Budi Santoso'
            ], [
                'email_reviewer' => 'budi@gmail.com',
                'rating' => 5,
                'komentar' => 'Bahannya sangat bagus, tebal dan nyaman dipakai. Pengiriman cepat!',
                'status' => 'approved'
            ]);

            Review::firstOrCreate([
                'barang_id' => $items[0]->id_barang,
                'nama_reviewer' => 'Siti Rahma'
            ], [
                'email_reviewer' => 'siti@gmail.com',
                'rating' => 5,
                'komentar' => 'Sesuai dengan foto dan deskripsi, packing rapih dan aman.',
                'status' => 'approved'
            ]);

            if (isset($items[1])) {
                Review::firstOrCreate([
                    'barang_id' => $items[1]->id_barang,
                    'nama_reviewer' => 'Dimas Pratama'
                ], [
                    'email_reviewer' => 'dimas@gmail.com',
                    'rating' => 4,
                    'komentar' => 'Kualitas mantap, jahitan rapih. Recommended seller!',
                    'status' => 'approved'
                ]);
            }
        }
    }
}

