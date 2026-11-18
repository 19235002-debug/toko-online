<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReviewAutoVerifyTest extends TestCase
{
    use DatabaseTransactions;

    public function test_logged_in_buyer_with_completed_order_can_review_without_manual_order_ref_and_with_compressed_photo(): void
    {
        Storage::fake('public');

        $user = User::create([
            'nama' => 'Pelanggan Ulasan',
            'username' => 'pelangganulasan',
            'email' => 'pelangganulasan@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'no_hp' => '081299998888',
            'alamat' => 'Jl. Melati No. 5',
        ]);

        $barang = Barang::create([
            'nama' => 'Kaos Ulasan Test',
            'kategori' => 'Pria',
            'supplier' => 'Viola Official',
            'stok' => 10,
            'harga' => 100000,
        ]);

        $order = Order::create([
            'user_id' => $user->id_user,
            'kode_order' => 'ORD-TEST-REV-001',
            'nama_customer' => $user->nama,
            'no_hp' => $user->no_hp,
            'alamat' => $user->alamat,
            'metode_pembayaran' => 'cod',
            'total_harga' => 100000,
            'status' => 'selesai',
        ]);

        OrderItem::create([
            'order_id' => $order->id_order,
            'barang_id' => $barang->id_barang,
            'nama_barang' => $barang->nama,
            'harga' => 100000,
            'qty' => 1,
            'subtotal' => 100000,
        ]);

        // Cek detail produk: otomatis terverifikasi boleh mengulas
        $detail = $this->actingAs($user)->getJson('/frontend/product/' . $barang->id_barang);
        $detail->assertStatus(200)->assertJson(['can_review' => true]);

        // Kirim ulasan tanpa order_ref manual + lampiran foto
        $photo = UploadedFile::fake()->image('ulasan.jpg', 1200, 1200);
        $res = $this->actingAs($user)->post('/review/store', [
            'barang_id' => $barang->id_barang,
            'nama_reviewer' => 'Pelanggan Ulasan',
            'rating' => 5,
            'komentar' => 'Bahannya sangat nyaman dan jahitannya rapi!',
            'foto' => $photo,
        ]);

        $res->assertStatus(200)->assertJson([
            'status' => 'success',
            'review' => [
                'nama' => 'Pelanggan Ulasan',
                'rating' => 5,
                'komentar' => 'Bahannya sangat nyaman dan jahitannya rapi!',
            ],
        ]);

        $detailAfter = $this->actingAs($user)->getJson('/frontend/product/' . $barang->id_barang);
        $detailAfter->assertStatus(200)->assertJson(['can_review' => false]);
        $this->assertNotEmpty($detailAfter->json('reviews.0.foto'));
    }

    public function test_user_without_completed_order_cannot_submit_review(): void
    {
        $user = User::create([
            'nama' => 'Belum Beli',
            'username' => 'belumbeli',
            'email' => 'belumbeli@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $barang = Barang::create([
            'nama' => 'Produk Tanpa Beli',
            'kategori' => 'Pria',
            'supplier' => 'Viola Official',
            'stok' => 5,
            'harga' => 90000,
        ]);

        $res = $this->actingAs($user)->postJson('/review/store', [
            'barang_id' => $barang->id_barang,
            'nama_reviewer' => 'Belum Beli',
            'rating' => 5,
            'komentar' => 'Coba ulas tanpa beli',
        ]);

        $res->assertStatus(422)->assertJson(['status' => 'error']);
    }
}

