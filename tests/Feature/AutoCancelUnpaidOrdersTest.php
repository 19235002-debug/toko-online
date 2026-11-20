<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Barang;
use App\Models\BarangVariasi;
use App\Models\Voucher;
use Illuminate\Support\Facades\Artisan;

class AutoCancelUnpaidOrdersTest extends TestCase
{
    public function test_artisan_command_cancels_unpaid_orders_older_than_3_days(): void
    {
        $barang = Barang::create([
            'nama' => 'Baju Tes Kadaluarsa',
            'harga' => 100000,
            'stok' => 8,
            'kategori' => 'Pria',
            'kategori_id' => 1,
            'supplier' => 'PT Fashion Mandiri',
            'berat' => 200,
        ]);

        $variasi = BarangVariasi::create([
            'barang_id' => $barang->id_barang,
            'warna' => 'Hitam',
            'ukuran' => 'L',
            'stok' => 3,
            'harga_tambahan' => 0,
        ]);

        $voucherCode = 'DISKON3HARI_' . uniqid();
        $voucher = Voucher::create([
            'kode_voucher' => $voucherCode,
            'nama_voucher' => 'Voucher 3 Hari',
            'tipe' => 'nominal',
            'nilai' => 10000,
            'minimal_belanja' => 50000,
            'kuota' => 10,
            'digunakan' => 1,
            'status' => true,
        ]);

        // 1. Pesanan kadaluarsa (dibuat 4 hari lalu, belum dibayar)
        $expiredOrder = Order::create([
            'kode_order' => 'ORD-EXP-' . uniqid(),
            'nama_customer' => 'Pembeli Telat',
            'no_hp' => '081299990001',
            'alamat' => 'Jl. Telat Bayar No. 1',
            'total_harga' => 90000,
            'ongkir' => 10000,
            'diskon' => 10000,
            'kode_voucher' => $voucherCode,
            'metode_pembayaran' => 'midtrans',
            'snap_token' => 'snap-exp-123',
            'status' => 'pending',
            'paid_at' => null,
            'created_at' => now()->subDays(4),
            'updated_at' => now()->subDays(4),
        ]);

        OrderItem::create([
            'order_id' => $expiredOrder->id_order,
            'barang_id' => $barang->id_barang,
            'variasi_id' => $variasi->id_variasi,
            'nama_barang' => $barang->nama,
            'variasi' => 'Hitam / L',
            'harga' => 100000,
            'qty' => 2,
            'subtotal' => 200000,
        ]);

        // 2. Pesanan baru (dibuat 1 hari lalu, belum lewat batas 3 hari)
        $freshOrder = Order::create([
            'kode_order' => 'ORD-FRESH-' . uniqid(),
            'nama_customer' => 'Pembeli Baru',
            'no_hp' => '081299990002',
            'alamat' => 'Jl. Baru Pesan No. 2',
            'total_harga' => 100000,
            'ongkir' => 10000,
            'metode_pembayaran' => 'midtrans',
            'status' => 'pending',
            'paid_at' => null,
            'created_at' => now()->subDays(1),
            'updated_at' => now()->subDays(1),
        ]);

        // Jalankan artisan command pembatalan pesanan kadaluarsa 3 hari
        $exitCode = Artisan::call('orders:cancel-unpaid', ['--days' => 3]);
        $this->assertEquals(0, $exitCode);

        // Verifikasi pesanan kadaluarsa otomatis dibatalkan
        $expiredOrder->refresh();
        $this->assertEquals('dibatalkan', $expiredOrder->status);
        $this->assertNotNull($expiredOrder->cancelled_at);
        $this->assertStringContainsString('3 hari', $expiredOrder->catatan);

        // Verifikasi stok produk & variasi dikembalikan
        $barang->refresh();
        $this->assertEquals(10, $barang->stok); // 8 + 2
        $variasi->refresh();
        $this->assertEquals(5, $variasi->stok);  // 3 + 2

        // Verifikasi voucher dikembalikan
        $voucher->refresh();
        $this->assertEquals(0, $voucher->digunakan); // 1 - 1

        // Verifikasi pesanan yang baru 1 hari TIDAK dibatalkan
        $freshOrder->refresh();
        $this->assertEquals('pending', $freshOrder->status);
        $this->assertNull($freshOrder->cancelled_at);
    }

    public function test_customer_my_orders_page_auto_cancels_expired_unpaid_order(): void
    {
        $user = User::where('role', 'user')->first();
        if (!$user) {
            $user = User::factory()->create(['role' => 'user', 'nama' => 'User Tester']);
        }

        $barang = Barang::first();

        // Buat pesanan pending 4 hari lalu milik user
        $order = Order::create([
            'user_id' => $user->id_user,
            'kode_order' => 'ORD-AUTO-' . uniqid(),
            'nama_customer' => $user->nama ?: 'Customer Test',
            'no_hp' => $user->no_hp ?: '081299998888',
            'alamat' => 'Alamat Uji',
            'total_harga' => 150000,
            'ongkir' => 10000,
            'metode_pembayaran' => 'midtrans',
            'status' => 'pending',
            'paid_at' => null,
            'created_at' => now()->subDays(4),
            'updated_at' => now()->subDays(4),
        ]);

        OrderItem::create([
            'order_id' => $order->id_order,
            'barang_id' => $barang->id_barang,
            'nama_barang' => $barang->nama,
            'harga' => 150000,
            'qty' => 1,
            'subtotal' => 150000,
        ]);

        $initialStock = $barang->fresh()->stok;

        // User membuka halaman /pesanan-saya
        $response = $this->actingAs($user)->get('/pesanan-saya');
        $response->assertStatus(200);

        // Pesanan sudah otomatis dibatalkan dan stok dikembalikan
        $order->refresh();
        $this->assertEquals('dibatalkan', $order->status);
        $this->assertNotNull($order->cancelled_at);
        $this->assertEquals($initialStock + 1, $barang->fresh()->stok);
    }
}
