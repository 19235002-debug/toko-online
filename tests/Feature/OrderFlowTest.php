<?php

namespace Tests\Feature;

use App\Http\Controllers\OrderController;
use App\Models\Barang;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\RajaOngkirService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OrderFlowTest extends TestCase
{
    use DatabaseTransactions;

    public function test_checkout_requires_rajaongkir_destination_and_courier_and_computes_cost_server_side(): void
    {
        $user = User::create([
            'nama' => 'Flow Buyer',
            'username' => 'flow_buyer_' . uniqid(),
            'email' => 'flow_' . uniqid() . '@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Sudirman No. 1',
        ]);

        $barang = Barang::create([
            'nama' => 'Jaket Flow',
            'kategori' => 'Pria',
            'supplier' => 'Erigo',
            'stok' => 10,
            'harga' => 120000,
        ]);

        // Tanpa destination/courier -> 422
        $this->actingAs($user)->postJson('/checkout', [
            'nama_customer' => $user->nama,
            'no_hp' => $user->no_hp,
            'alamat' => $user->alamat,
            'metode_pembayaran' => 'cod',
            'cart_items' => json_encode([['id' => $barang->id_barang, 'qty' => 1]]),
        ])->assertStatus(422)->assertJsonValidationErrors([
            'destination_id', 'destination_label', 'courier_code', 'courier_service',
        ]);

        // Pilih layanan resmi, kirim ongkir_custom palsu (0) -> server tetap pakai tarif resmi
        $opt = RajaOngkirService::calculateCost(17480, 350)[0];
        $expectedOngkir = intval($opt['cost']);

        $res = $this->actingAs($user)->postJson('/checkout', [
            'nama_customer' => $user->nama,
            'no_hp' => $user->no_hp,
            'alamat' => $user->alamat,
            'metode_pembayaran' => 'cod',
            'destination_id' => 17480,
            'destination_label' => 'GAMBIR, JAKARTA PUSAT, DKI JAKARTA',
            'courier_code' => $opt['code'],
            'courier_service' => $opt['service'],
            'ongkir_custom' => 0,
            'cart_items' => json_encode([['id' => $barang->id_barang, 'qty' => 1]]),
        ]);

        $res->assertStatus(200)->assertJsonPath('success', true);

        $order = Order::latest('id_order')->first();
        $this->assertEquals($expectedOngkir, $order->ongkir);
        $this->assertEquals(120000 + $expectedOngkir, $order->total_harga);
        $this->assertEquals(strtolower($opt['code']), $order->kurir_kode);
        $this->assertEquals(strtoupper($opt['service']), $order->layanan_kurir);
        $this->assertEquals(350, $order->berat);
    }

    public function test_midtrans_settlement_and_expiry_update_order_and_stock(): void
    {
        $barang = Barang::create([
            'nama' => 'Kemeja Midtrans',
            'kategori' => 'Pria',
            'supplier' => 'Viola',
            'stok' => 8,
            'harga' => 100000,
        ]);

        $order = Order::create([
            'kode_order' => 'ORD-MID-' . uniqid(),
            'nama_customer' => 'Midtrans Buyer',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Merdeka',
            'metode_pembayaran' => 'midtrans',
            'total_harga' => 112000,
            'ongkir' => 12000,
            'status' => 'pending',
        ]);

        $changed = OrderController::applyMidtransStatus($order, 'settlement', 'accept', 'qris');
        $this->assertTrue($changed);
        $order->refresh();
        $this->assertEquals('diproses', $order->status);
        $this->assertNotNull($order->paid_at);
        $this->assertEquals('qris', $order->payment_type);

        // Pesanan kedua: expire -> dibatalkan & stok kembali
        $expiredOrder = Order::create([
            'kode_order' => 'ORD-EXP-' . uniqid(),
            'nama_customer' => 'Expired Buyer',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Merdeka',
            'metode_pembayaran' => 'midtrans',
            'total_harga' => 112000,
            'ongkir' => 12000,
            'status' => 'pending',
        ]);
        OrderItem::create([
            'order_id' => $expiredOrder->id_order,
            'barang_id' => $barang->id_barang,
            'nama_barang' => $barang->nama,
            'harga' => 100000,
            'qty' => 2,
            'subtotal' => 200000,
        ]);

        OrderController::applyMidtransStatus($expiredOrder, 'expire');
        $expiredOrder->refresh();
        $barang->refresh();

        $this->assertEquals('dibatalkan', $expiredOrder->status);
        $this->assertNotNull($expiredOrder->cancelled_at);
        $this->assertEquals(10, $barang->stok);
    }

    public function test_admin_order_status_transitions_enforce_flow_payment_and_resi(): void
    {
        $admin = User::where('role', 'admin')->first();

        $order = Order::create([
            'kode_order' => 'ORD-FLOW-' . uniqid(),
            'nama_customer' => 'Flow Customer',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Mawar',
            'ekspedisi' => 'JNE',
            'kurir' => 'JNE REG',
            'kurir_kode' => 'jne',
            'metode_pembayaran' => 'cod',
            'total_harga' => 115000,
            'ongkir' => 15000,
            'status' => 'pending',
        ]);

        // Tidak boleh lompat dari pending ke dikirim
        $this->actingAs($admin)->put(route('order.updateStatus', $order->id_order), [
            'status' => 'dikirim',
            'ekspedisi' => 'JNE',
            'no_resi' => 'JNE12345',
        ])->assertRedirect(route('order.show', $order->id_order));
        $this->assertEquals('pending', $order->fresh()->status);

        // Pending -> Diproses (COD boleh dikonfirmasi admin)
        $this->actingAs($admin)->put(route('order.updateStatus', $order->id_order), [
            'status' => 'diproses',
            'ekspedisi' => 'JNE',
        ])->assertRedirect(route('order.show', $order->id_order));
        $this->assertEquals('diproses', $order->fresh()->status);

        // Diproses -> Dikirim tanpa resi ditolak
        $this->actingAs($admin)->put(route('order.updateStatus', $order->id_order), [
            'status' => 'dikirim',
            'ekspedisi' => 'JNE',
            'no_resi' => '',
        ])->assertRedirect(route('order.show', $order->id_order));
        $this->assertEquals('diproses', $order->fresh()->status);

        // Diproses -> Dikirim dengan resi berhasil
        $this->actingAs($admin)->put(route('order.updateStatus', $order->id_order), [
            'status' => 'dikirim',
            'ekspedisi' => 'JNE',
            'no_resi' => 'JNE99887766',
        ])->assertRedirect(route('order.show', $order->id_order));
        $order->refresh();
        $this->assertEquals('dikirim', $order->status);
        $this->assertEquals('JNE99887766', $order->no_resi);
        $this->assertNotNull($order->shipped_at);

        // Pembeli konfirmasi diterima -> Selesai + COD paid_at tercatat
        $this->post(route('frontend.track.confirm', $order->kode_order))
            ->assertRedirect(route('frontend.track', ['order' => $order->kode_order]));
        $order->refresh();
        $this->assertEquals('selesai', $order->status);
        $this->assertNotNull($order->completed_at);
        $this->assertNotNull($order->paid_at);
    }
}

