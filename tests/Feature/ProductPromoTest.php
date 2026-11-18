<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Order;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProductPromoTest extends TestCase
{
    use DatabaseTransactions;

    public function test_active_promo_within_duration_applies_discounted_price_and_stacks_with_voucher(): void
    {
        $user = User::create([
            'nama' => 'Pembeli Promo',
            'username' => 'pembelipromo',
            'email' => 'pembelipromo@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Merdeka No. 1',
        ]);

        $barang = Barang::create([
            'nama' => 'Jaket Promo Test',
            'kategori' => 'Pria',
            'supplier' => 'Viola Official',
            'stok' => 10,
            'harga' => 200000,
            'promo_aktif' => true,
            'promo_persen' => 25,
            'harga_promo' => 150000,
            'promo_mulai' => now()->subHour(),
            'promo_selesai' => now()->addDay(),
        ]);

        Voucher::create([
            'kode_voucher' => 'EXTRA10K',
            'nama_voucher' => 'Extra Diskon 10K',
            'tipe' => 'nominal',
            'nilai' => 10000,
            'minimal_belanja' => 100000,
            'kuota' => 5,
            'digunakan' => 0,
            'aktif' => true,
        ]);

        $this->assertTrue($barang->is_promo_active);
        $this->assertEquals(150000, $barang->harga_final);

        $shopRes = $this->get('/shop');
        $shopRes->assertStatus(200);
        $shopRes->assertSee('PROMO -25%');

        $checkoutRes = $this->actingAs($user)->postJson('/checkout', [
            'nama_customer' => 'Pembeli Promo',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Merdeka No. 1',
            'metode_pembayaran' => 'cod',
            'kode_voucher' => 'EXTRA10K',
            'cart_items' => json_encode([
                [
                    'id' => $barang->id_barang,
                    'nama' => $barang->nama,
                    'qty' => 1,
                ],
            ]),
        ]);

        $checkoutRes->assertStatus(200)->assertJson(['success' => true]);

        $order = Order::latest('id_order')->first();
        $this->assertNotNull($order);
        $this->assertEquals(140000, $order->total_harga); // 150.000 promo - 10.000 voucher
    }

    public function test_expired_or_inactive_promo_reverts_to_regular_price(): void
    {
        $expired = Barang::create([
            'nama' => 'Kemeja Expired',
            'kategori' => 'Pria',
            'supplier' => 'Viola Official',
            'stok' => 5,
            'harga' => 100000,
            'promo_aktif' => true,
            'promo_persen' => 20,
            'harga_promo' => 80000,
            'promo_mulai' => now()->subDays(3),
            'promo_selesai' => now()->subMinute(),
        ]);

        $this->assertFalse($expired->is_promo_active);
        $this->assertEquals('Berakhir', $expired->promo_status_label);
        $this->assertEquals(100000, $expired->harga_final);
    }

    public function test_admin_can_toggle_promo_status(): void
    {
        $admin = User::create([
            'nama' => 'Admin Promo',
            'username' => 'adminpromo',
            'email' => 'adminpromo@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $barang = Barang::create([
            'nama' => 'Celana Toggle',
            'kategori' => 'Pria',
            'supplier' => 'Viola Official',
            'stok' => 5,
            'harga' => 120000,
            'promo_aktif' => false,
            'promo_persen' => 10,
            'harga_promo' => 108000,
        ]);

        $res = $this->actingAs($admin)->patch('/barang/' . $barang->id_barang . '/toggle-promo');
        $res->assertRedirect('/barang');

        $barang->refresh();
        $this->assertTrue($barang->promo_aktif);
        $this->assertTrue($barang->is_promo_active);
    }
}
