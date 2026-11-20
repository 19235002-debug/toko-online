<?php

namespace Tests\Feature;

use App\Http\Controllers\OrderController;
use App\Models\Barang;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\BiteshipService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BiteshipIntegrationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_biteship_search_area_returns_locations_with_area_id(): void
    {
        $user = User::create([
            'nama' => 'Buyer Test',
            'username' => 'buyer_test_' . uniqid(),
            'email' => 'buyer_test_' . uniqid() . '@toko.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
        ]);

        $areas = BiteshipService::searchArea('Pesanggrahan', 5);
        $this->assertNotEmpty($areas);
        $this->assertArrayHasKey('id', $areas[0]);
        $this->assertArrayHasKey('label', $areas[0]);
        $this->assertStringContainsString('Pesanggrahan', $areas[0]['label']);

        // Pastikan pencarian Grogol Petamburan mencakup lokasi terkait
        $grogol = BiteshipService::searchArea('Grogol', 5);
        $this->assertNotEmpty($grogol);
        $this->assertStringContainsString('Grogol', $grogol[0]['label']);

        $res = $this->actingAs($user)->getJson('/shipping/destinations?q=Pesanggrahan');
        $res->assertStatus(200)->assertJsonPath('success', true);
        $this->assertNotEmpty($res->json('destinations'));
    }

    public function test_biteship_calculate_cost_returns_standard_courier_rates(): void
    {
        $user = User::create([
            'nama' => 'Buyer Test 2',
            'username' => 'buyer_test2_' . uniqid(),
            'email' => 'buyer_test2_' . uniqid() . '@toko.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
        ]);

        $options = BiteshipService::calculateCost('IDNP6IDNC148IDND843IDZ12250', 500);
        $this->assertNotEmpty($options);

        $courierCodes = array_column($options, 'code');
        $this->assertContains('jne', $courierCodes);
        $this->assertContains('jnt', $courierCodes);

        $res = $this->actingAs($user)->postJson('/shipping/cost', [
            'destination_id' => 'IDNP6IDNC148IDND843IDZ12250',
            'total_qty' => 1,
        ]);
        $res->assertStatus(200)->assertJsonPath('success', true);
        $this->assertNotEmpty($res->json('options'));
    }

    public function test_admin_can_request_biteship_pickup_and_receive_instant_waybill(): void
    {
        $admin = User::create([
            'nama' => 'Admin Biteship',
            'username' => 'admin_bs_' . uniqid(),
            'email' => 'admin_bs_' . uniqid() . '@toko.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        $user = User::create([
            'nama' => 'Buyer Biteship',
            'username' => 'buyer_bs_' . uniqid(),
            'email' => 'buyer_bs_' . uniqid() . '@toko.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
            'no_hp' => '081298765432',
            'alamat' => 'Jl. Kemang Raya No. 10',
        ]);

        $barang = Barang::create([
            'nama' => 'Kemeja Linen Pria',
            'kategori' => 'Pria',
            'supplier' => 'Zara Studio ID',
            'stok' => 15,
            'harga' => 179000,
        ]);

        $order = Order::create([
            'user_id' => $user->id_user,
            'kode_order' => Order::generateKodeOrder(),
            'nama_customer' => $user->nama,
            'no_hp' => $user->no_hp,
            'alamat' => $user->alamat,
            'destination_area_id' => 'IDNP6IDNC148IDND843IDZ12250',
            'destination_label' => 'Pesanggrahan, Jakarta Selatan',
            'kurir_kode' => 'jnt',
            'layanan_kurir' => 'EZ',
            'ekspedisi' => 'J&T',
            'berat' => 350,
            'ongkir' => 10000,
            'metode_pembayaran' => 'cod',
            'total_harga' => 189000,
            'status' => 'diproses',
        ]);

        OrderItem::create([
            'order_id' => $order->id_order,
            'barang_id' => $barang->id_barang,
            'nama_barang' => $barang->nama,
            'qty' => 1,
            'harga' => 179000,
            'subtotal' => 179000,
        ]);

        // Request pick-up via Biteship API
        $res = $this->actingAs($admin)->post(route('order.requestPickup', $order->id_order));
        $res->assertStatus(302);

        $order->refresh();
        $this->assertNotEmpty($order->no_resi);
        $this->assertTrue(str_starts_with($order->no_resi, 'BS-') || str_starts_with($order->no_resi, 'WYB-') || strlen($order->no_resi) >= 8);
        $this->assertNotEmpty($order->biteship_order_id);
        $this->assertContains($order->biteship_status, ['allocated', 'placed', 'confirmed', 'picked']);

        // Pastikan halaman shipping label bisa dibuka & siap cetak
        $labelRes = $this->actingAs($admin)->get(route('order.shippingLabel', $order->id_order));
        $labelRes->assertStatus(200);
        $labelRes->assertSee($order->no_resi);
        $labelRes->assertSee('PENERIMA:');
        $labelRes->assertSee('Buyer Biteship');
    }

    public function test_biteship_webhook_updates_order_status_to_dikirim_and_selesai(): void
    {
        $order = Order::create([
            'kode_order' => Order::generateKodeOrder(),
            'nama_customer' => 'Customer Webhook',
            'no_hp' => '081233344455',
            'alamat' => 'Jl. Gatot Subroto No. 5',
            'biteship_order_id' => 'bs_order_' . uniqid(),
            'no_resi' => 'BS-JNT-TEST9999',
            'total_harga' => 150000,
            'metode_pembayaran' => 'midtrans',
            'status' => 'diproses',
        ]);

        // 1. Webhook paket dijemput kurir (picked) -> status 'dikirim'
        $webhookPicked = $this->postJson('/api/biteship/webhook', [
            'event' => 'order.status',
            'data' => [
                'id' => $order->biteship_order_id,
                'waybill_id' => $order->no_resi,
                'status' => 'picked',
            ],
        ]);
        $webhookPicked->assertStatus(200)->assertJson(['status' => 'success']);

        $order->refresh();
        $this->assertEquals('dikirim', $order->status);
        $this->assertNotNull($order->shipped_at);

        // 2. Webhook paket sampai (delivered) -> status 'selesai'
        $webhookDelivered = $this->postJson('/api/biteship/webhook', [
            'event' => 'order.status',
            'data' => [
                'id' => $order->biteship_order_id,
                'waybill_id' => $order->no_resi,
                'status' => 'delivered',
            ],
        ]);
        $webhookDelivered->assertStatus(200)->assertJson(['status' => 'success']);

        $order->refresh();
        $this->assertEquals('selesai', $order->status);
        $this->assertNotNull($order->completed_at);
    }
}
