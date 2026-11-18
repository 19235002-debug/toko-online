<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class StorefrontEnhancementsTest extends TestCase
{
    use DatabaseTransactions;

    public function test_shop_promo_filter_and_home_promo_showcase(): void
    {
        $promoBarang = Barang::create([
            'nama' => 'Produk Promo Spesial',
            'kategori' => 'Pria',
            'supplier' => 'Viola Official',
            'stok' => 10,
            'harga' => 100000,
            'promo_aktif' => true,
            'promo_persen' => 20,
            'harga_promo' => 80000,
        ]);

        $normalBarang = Barang::create([
            'nama' => 'Produk Harga Normal',
            'kategori' => 'Pria',
            'supplier' => 'Viola Official',
            'stok' => 10,
            'harga' => 100000,
            'promo_aktif' => false,
        ]);

        $homeRes = $this->get('/');
        $homeRes->assertStatus(200);
        $homeRes->assertSee('Promo Spesial Hari Ini');
        $homeRes->assertSee($promoBarang->nama);

        $shopPromoRes = $this->get('/shop?promo=1');
        $shopPromoRes->assertStatus(200);
        $shopPromoRes->assertSee($promoBarang->nama);
        $shopPromoRes->assertDontSee($normalBarang->nama);
    }

    public function test_user_cart_and_wishlist_sync_to_database_and_checkout_with_shipping(): void
    {
        $user = User::create([
            'nama' => 'Tester Sync',
            'username' => 'testersync',
            'email' => 'testersync@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'no_hp' => '081234567891',
            'alamat' => 'Jl. Sudirman No. 10',
        ]);

        $barang = Barang::create([
            'nama' => 'Kaos Sync DB',
            'kategori' => 'Pria',
            'supplier' => 'Viola Official',
            'stok' => 10,
            'harga' => 100000,
        ]);

        $this->actingAs($user)->postJson('/user/cart/sync', [
            'cart_items' => json_encode([
                ['id' => $barang->id_barang, 'qty' => 2],
            ]),
        ])->assertStatus(200)->assertJson(['success' => true]);

        $this->actingAs($user)->postJson('/user/wishlist/sync', [
            'wishlist_items' => json_encode([
                ['id' => $barang->id_barang],
            ]),
        ])->assertStatus(200)->assertJson(['success' => true]);

        $syncState = $this->actingAs($user)->getJson('/user/sync-state');
        $syncState->assertStatus(200)
            ->assertJsonCount(1, 'cart')
            ->assertJsonCount(1, 'wishlist')
            ->assertJsonPath('cart.0.qty', 2);

        // Checkout with Express courier (Rp 30.000) -> 200.000 + 30.000 = 230.000
        $checkoutRes = $this->actingAs($user)->postJson('/checkout', [
            'nama_customer' => $user->nama,
            'no_hp' => $user->no_hp,
            'alamat' => $user->alamat,
            'metode_pembayaran' => 'cod',
            'kurir' => 'express',
            'cart_items' => json_encode([
                ['id' => $barang->id_barang, 'qty' => 2],
            ]),
        ]);

        $checkoutRes->assertStatus(200)->assertJson(['success' => true]);

        $order = Order::latest('id_order')->first();
        $this->assertEquals(30000, $order->ongkir);
        $this->assertEquals(230000, $order->total_harga);
        $this->assertEquals(0, CartItem::where('user_id', $user->id_user)->count());
    }

    public function test_admin_can_export_laporan_csv(): void
    {
        $admin = User::where('role', 'admin')->first();
        $res = $this->actingAs($admin)->get('/laporan/export-csv');
        $res->assertStatus(200);
        $this->assertStringContainsString('text/csv', $res->headers->get('content-type'));
    }
}

