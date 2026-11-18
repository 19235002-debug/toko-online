<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Barang;
use App\Models\BarangVariasi;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Voucher;

class OrderCancellationTest extends TestCase
{
    public function test_checkout_rejects_manual_transfer_method()
    {
        $user = User::where('role', 'user')->first();

        $response = $this->actingAs($user)->postJson('/checkout', [
            'nama_customer' => 'Test Buyer',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Test No. 123, Jakarta',
            'metode_pembayaran' => 'transfer',
            'cart_items' => json_encode([['id' => 3, 'qty' => 1]]),
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['metode_pembayaran']);
    }

    public function test_customer_can_cancel_pending_order_and_restore_variant_stock_and_voucher()
    {
        $user = User::where('role', 'user')->first();
        $barang = Barang::find(3) ?: Barang::first();
        $variasi = BarangVariasi::where('barang_id', $barang->id_barang)->first()
            ?: BarangVariasi::create([
                'barang_id' => $barang->id_barang,
                'warna' => 'Hitam',
                'ukuran' => 'L',
                'stok' => 10,
            ]);
        $voucher = Voucher::where('kode_voucher', 'HEMAT20')->first();

        $origBarangStok = intval($barang->stok);
        $origVarStok = intval($variasi->stok);
        $origVoucherUsed = intval($voucher->digunakan);

        // Simulasikan stok & voucher terpakai saat order dibuat
        $barang->stok = $origBarangStok - 2;
        $barang->save();
        $variasi->stok = $origVarStok - 2;
        $variasi->save();
        $voucher->digunakan = $origVoucherUsed + 1;
        $voucher->save();

        $order = Order::create([
            'user_id' => $user->id_user,
            'kode_order' => 'ORD-TEST-' . uniqid(),
            'nama_customer' => $user->nama,
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Test No. 1',
            'kode_voucher' => $voucher->kode_voucher,
            'diskon' => 20000,
            'metode_pembayaran' => 'cod',
            'total_harga' => 180000,
            'status' => 'pending',
        ]);

        OrderItem::create([
            'order_id' => $order->id_order,
            'barang_id' => $barang->id_barang,
            'variasi_id' => $variasi->id_variasi,
            'nama_barang' => $barang->nama,
            'variasi' => $variasi->label,
            'harga' => 100000,
            'qty' => 2,
            'subtotal' => 200000,
        ]);

        $response = $this->actingAs($user)->post('/pesanan-saya/' . $order->kode_order . '/cancel');
        $response->assertRedirect(route('frontend.my_orders'));

        $order->refresh();
        $barang->refresh();
        $variasi->refresh();
        $voucher->refresh();

        $this->assertEquals('dibatalkan', $order->status);
        $this->assertEquals($origBarangStok, intval($barang->stok));
        $this->assertEquals($origVarStok, intval($variasi->stok));
        $this->assertEquals($origVoucherUsed, intval($voucher->digunakan));

        // Cleanup
        $order->delete();
    }

    public function test_customer_can_view_own_invoice()
    {
        $user = User::where('role', 'user')->first();
        $order = Order::create([
            'user_id' => $user->id_user,
            'kode_order' => 'ORD-INV-' . uniqid(),
            'nama_customer' => $user->nama,
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Test Invoice',
            'metode_pembayaran' => 'cod',
            'total_harga' => 150000,
            'status' => 'diproses',
        ]);

        $response = $this->actingAs($user)->get('/pesanan-saya/' . $order->kode_order . '/invoice');
        $response->assertStatus(200);
        $response->assertSee($order->kode_order);

        $order->delete();
    }
}

