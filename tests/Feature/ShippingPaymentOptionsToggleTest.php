<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Setting;
use App\Models\Barang;
use App\Models\BarangVariasi;

class ShippingPaymentOptionsToggleTest extends TestCase
{
    private function getOrCreateAdmin(): User
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin Test',
                'email' => 'admin_test_' . uniqid() . '@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]);
        }
        return $admin;
    }

    private function getOrCreateCustomer(): User
    {
        $user = User::where('role', 'user')->first();
        if (!$user) {
            $user = User::create([
                'name' => 'Customer Test',
                'email' => 'cust_' . uniqid() . '@example.com',
                'password' => bcrypt('password'),
                'role' => 'user',
            ]);
        }
        return $user;
    }

    protected function tearDown(): void
    {
        // Pulihkan konfigurasi setting default agar tidak mempengaruhi tes lain
        $setting = Setting::first();
        if ($setting) {
            $setting->update([
                'active_couriers' => array_keys(Setting::ALL_COURIERS),
                'active_payment_methods' => array_keys(Setting::ALL_PAYMENTS),
            ]);
        }

        parent::tearDown();
    }

    public function test_guest_cannot_access_shipping_and_payment_settings(): void
    {
        $response = $this->get('/setting/metode');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_shipping_and_payment_settings_page(): void
    {
        $admin = $this->getOrCreateAdmin();

        $response = $this->actingAs($admin)->get('/setting/metode');
        $response->assertStatus(200);
        $response->assertSee('Opsi Kurir &amp; Pembayaran', false);
        $response->assertSee('JNE');
        $response->assertSee('COD (Cash on Delivery)');
    }

    public function test_admin_can_toggle_courier_via_ajax(): void
    {
        $admin = $this->getOrCreateAdmin();
        $setting = Setting::getSettings();

        // Pastikan ada setidaknya 2 kurir aktif agar toggle off diizinkan
        $setting->update(['active_couriers' => ['jne', 'jnt']]);

        // Matikan JNE
        $response = $this->actingAs($admin)->postJson('/setting/toggle-courier', [
            'code' => 'jne',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'is_active' => false,
            'code' => 'jne',
        ]);

        $setting->refresh();
        $this->assertFalse($setting->isCourierActive('jne'));

        // Nyalakan kembali JNE
        $response2 = $this->actingAs($admin)->postJson('/setting/toggle-courier', [
            'code' => 'jne',
        ]);

        $response2->assertStatus(200);
        $response2->assertJson([
            'success' => true,
            'is_active' => true,
            'code' => 'jne',
        ]);

        $setting->refresh();
        $this->assertTrue($setting->isCourierActive('jne'));
    }

    public function test_admin_cannot_disable_all_couriers(): void
    {
        $admin = $this->getOrCreateAdmin();
        $setting = Setting::getSettings();

        // Sisakan hanya 1 kurir aktif
        $setting->update(['active_couriers' => ['jne']]);

        // Coba matikan kurir terakhir (jne)
        $response = $this->actingAs($admin)->postJson('/setting/toggle-courier', [
            'code' => 'jne',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);

        $setting->refresh();
        $this->assertTrue($setting->isCourierActive('jne'), 'Kurir terakhir tidak boleh dinonaktifkan');
    }

    public function test_admin_can_toggle_payment_method_via_ajax(): void
    {
        $admin = $this->getOrCreateAdmin();
        $setting = Setting::getSettings();

        // Pastikan midtrans dan cod aktif
        $setting->update(['active_payment_methods' => ['midtrans', 'cod']]);

        // Nonaktifkan COD
        $response = $this->actingAs($admin)->postJson('/setting/toggle-payment', [
            'method' => 'cod',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'is_active' => false,
            'method' => 'cod',
        ]);

        $setting->refresh();
        $this->assertFalse($setting->isPaymentActive('cod'));

        // Coba nonaktifkan midtrans saat cod sudah nonaktif (harus ditolak karena midtrans adalah metode terakhir)
        $failResponse = $this->actingAs($admin)->postJson('/setting/toggle-payment', [
            'method' => 'midtrans',
        ]);

        $failResponse->assertStatus(422);
        $failResponse->assertJson(['success' => false]);

        $setting->refresh();
        $this->assertTrue($setting->isPaymentActive('midtrans'));

        // Aktifkan kembali COD
        $enableResponse = $this->actingAs($admin)->postJson('/setting/toggle-payment', [
            'method' => 'cod',
        ]);

        $enableResponse->assertStatus(200);
        $setting->refresh();
        $this->assertTrue($setting->isPaymentActive('cod'));
    }

    public function test_checkout_rejects_inactive_payment_method(): void
    {
        $customer = $this->getOrCreateCustomer();
        $setting = Setting::getSettings();

        // Nonaktifkan COD di setting
        $setting->update([
            'active_payment_methods' => ['midtrans'],
            'active_couriers' => ['jne', 'jnt'],
        ]);

        $barang = Barang::create([
            'nama' => 'Barang Uji Checkout ' . uniqid(),
            'harga' => 50000,
            'stok' => 10,
            'kategori' => 'Pria',
            'kategori_id' => 1,
            'supplier' => 'PT Fashion Mandiri',
            'berat' => 200,
        ]);

        $variasi = BarangVariasi::create([
            'barang_id' => $barang->id_barang,
            'warna' => 'Hitam',
            'ukuran' => 'M',
            'stok' => 5,
            'harga_tambahan' => 0,
        ]);

        // Coba checkout dengan COD yang dinonaktifkan
        $response = $this->actingAs($customer)->postJson('/checkout', [
            'nama_customer' => 'Budi Pembeli',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Merdeka No. 10',
            'destination_id' => 'ID_123',
            'destination_label' => 'Jakarta Barat, DKI Jakarta',
            'destination_postal_code' => '11460',
            'courier_code' => 'jne',
            'courier_service' => 'REG',
            'metode_pembayaran' => 'cod', // COD sedang dinonaktifkan
            'cart_items' => json_encode([
                [
                    'id_barang' => $barang->id_barang,
                    'id_variasi' => $variasi->id,
                    'qty' => 1,
                    'harga' => 50000,
                    'nama' => $barang->nama,
                ]
            ]),
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['metode_pembayaran']);
        $response->assertSee('Metode pembayaran yang dipilih sedang tidak aktif atau dinonaktifkan oleh toko.');
    }

    public function test_checkout_rejects_inactive_courier(): void
    {
        $customer = $this->getOrCreateCustomer();
        $setting = Setting::getSettings();

        // JNE dinonaktifkan, hanya JNT yang aktif
        $setting->update([
            'active_payment_methods' => ['midtrans', 'cod'],
            'active_couriers' => ['jnt'],
        ]);

        $barang = Barang::create([
            'nama' => 'Barang Uji Kurir ' . uniqid(),
            'harga' => 50000,
            'stok' => 10,
            'kategori' => 'Pria',
            'kategori_id' => 1,
            'supplier' => 'PT Fashion Mandiri',
            'berat' => 200,
        ]);

        $variasi = BarangVariasi::create([
            'barang_id' => $barang->id_barang,
            'warna' => 'Navy',
            'ukuran' => 'L',
            'stok' => 5,
            'harga_tambahan' => 0,
        ]);

        // Coba checkout dengan kurir JNE yang dinonaktifkan
        $response = $this->actingAs($customer)->postJson('/checkout', [
            'nama_customer' => 'Budi Pembeli',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Merdeka No. 10',
            'destination_id' => 'ID_123',
            'destination_label' => 'Jakarta Barat, DKI Jakarta',
            'destination_postal_code' => '11460',
            'courier_code' => 'jne', // JNE sedang dinonaktifkan
            'courier_service' => 'REG',
            'metode_pembayaran' => 'midtrans',
            'cart_items' => json_encode([
                [
                    'id_barang' => $barang->id_barang,
                    'id_variasi' => $variasi->id,
                    'qty' => 1,
                    'harga' => 50000,
                    'nama' => $barang->nama,
                ]
            ]),
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
        $response->assertSee('Layanan kurir JNE sedang dinonaktifkan oleh toko. Silakan pilih kurir lain.');
    }
}
