<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Services\ImageCompressor;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PasswordResetAndAdminOrderTest extends TestCase
{
    use DatabaseTransactions;

    public function test_forgot_and_reset_password_flow_works(): void
    {
        $user = User::create([
            'nama' => 'Test Reset User',
            'username' => 'reset_user_' . uniqid(),
            'email' => 'reset_' . uniqid() . '@example.com',
            'password' => Hash::make('oldpassword'),
            'role' => 'user',
        ]);

        $this->get('/forgot-password')->assertStatus(200);

        $response = $this->post('/forgot-password', [
            'email' => $user->email,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('reset_link_demo');

        $demoLink = session('reset_link_demo');
        $parsedPath = parse_url($demoLink, PHP_URL_PATH);
        $token = basename($parsedPath);

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $user->email,
        ]);

        $this->get('/reset-password/' . $token . '?email=' . urlencode($user->email))
            ->assertStatus(200);

        $resetResponse = $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $resetResponse->assertRedirect(route('login'));

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => $user->email,
        ]);
    }

    public function test_admin_order_search_and_metode_filter(): void
    {
        $admin = User::create([
            'nama' => 'Admin Filter Test',
            'username' => 'admin_filter_' . uniqid(),
            'email' => 'admin_filter_' . uniqid() . '@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $uniqueTag = 'ORDTEST' . rand(1000, 9999);

        Order::create([
            'kode_order' => $uniqueTag . '-COD',
            'nama_customer' => 'Budi Santoso',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Mawar No. 1',
            'metode_pembayaran' => 'cod',
            'total_harga' => 150000,
            'ongkir' => 15000,
            'status' => 'diproses',
        ]);

        Order::create([
            'kode_order' => $uniqueTag . '-MID',
            'nama_customer' => 'Siti Aminah',
            'no_hp' => '089876543210',
            'alamat' => 'Jl. Melati No. 2',
            'metode_pembayaran' => 'midtrans',
            'total_harga' => 250000,
            'ongkir' => 15000,
            'status' => 'selesai',
        ]);

        $resSearch = $this->actingAs($admin)->get('/order?search=' . urlencode('Budi Santoso'));
        $resSearch->assertStatus(200);
        $resSearch->assertViewHas('orders', function ($orders) use ($uniqueTag) {
            return $orders->contains('kode_order', $uniqueTag . '-COD')
                && !$orders->contains('kode_order', $uniqueTag . '-MID');
        });

        $resMetode = $this->actingAs($admin)->get('/order?search=' . urlencode($uniqueTag) . '&metode=midtrans');
        $resMetode->assertStatus(200);
        $resMetode->assertViewHas('orders', function ($orders) use ($uniqueTag) {
            return $orders->contains('kode_order', $uniqueTag . '-MID')
                && !$orders->contains('kode_order', $uniqueTag . '-COD');
        });
    }

    public function test_admin_order_period_filtering_and_defaults(): void
    {
        $admin = User::create([
            'nama' => 'Admin Period Test',
            'username' => 'admin_period_' . uniqid(),
            'email' => 'admin_period_' . uniqid() . '@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $orderToday = Order::create([
            'kode_order' => 'ORD-TEST-TODAY-' . uniqid(),
            'nama_customer' => 'Customer Hari Ini',
            'no_hp' => '081111111111',
            'alamat' => 'Alamat Hari Ini',
            'metode_pembayaran' => 'midtrans',
            'total_harga' => 100000,
            'ongkir' => 10000,
            'status' => 'diproses',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $orderOld = Order::create([
            'kode_order' => 'ORD-TEST-OLD-' . uniqid(),
            'nama_customer' => 'Customer 5 Hari Lalu',
            'no_hp' => '082222222222',
            'alamat' => 'Alamat Lama',
            'metode_pembayaran' => 'cod',
            'total_harga' => 120000,
            'ongkir' => 10000,
            'status' => 'selesai',
            'created_at' => now()->subDays(5),
            'updated_at' => now()->subDays(5),
        ]);

        // Default: Periode Hari Ini
        $resDefault = $this->actingAs($admin)->get('/order');
        $resDefault->assertStatus(200);
        $resDefault->assertViewHas('periode', 'today');
        $resDefault->assertViewHas('orders', function ($orders) use ($orderToday, $orderOld) {
            return $orders->contains('kode_order', $orderToday->kode_order)
                && !$orders->contains('kode_order', $orderOld->kode_order);
        });

        // Periode Semua Waktu: both orders appear
        $resAll = $this->actingAs($admin)->get('/order?periode=all');
        $resAll->assertStatus(200);
        $resAll->assertViewHas('periode', 'all');
        $resAll->assertViewHas('orders', function ($orders) use ($orderToday, $orderOld) {
            return $orders->contains('kode_order', $orderToday->kode_order)
                && $orders->contains('kode_order', $orderOld->kode_order);
        });

        // Periode 2 Hari Terakhir
        $res2Days = $this->actingAs($admin)->get('/order?periode=2days');
        $res2Days->assertStatus(200);
        $res2Days->assertViewHas('periode', '2days');
        $res2Days->assertViewHas('orders', function ($orders) use ($orderToday, $orderOld) {
            return $orders->contains('kode_order', $orderToday->kode_order)
                && !$orders->contains('kode_order', $orderOld->kode_order);
        });
    }

    public function test_admin_order_prioritizes_unfulfilled_and_unpaid_orders_first(): void
    {
        $admin = User::create([
            'nama' => 'Admin Priority Test',
            'username' => 'admin_priority_' . uniqid(),
            'email' => 'admin_priority_' . uniqid() . '@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $prefix = 'ORD-PRIO-' . rand(100, 999) . '-';

        $orderSelesai = Order::create([
            'kode_order' => $prefix . 'SELESAI',
            'nama_customer' => 'Customer Selesai',
            'no_hp' => '081200000001',
            'alamat' => 'Alamat Selesai',
            'metode_pembayaran' => 'midtrans',
            'total_harga' => 100000,
            'ongkir' => 10000,
            'status' => 'selesai',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $orderDikirim = Order::create([
            'kode_order' => $prefix . 'DIKIRIM',
            'nama_customer' => 'Customer Dikirim',
            'no_hp' => '081200000002',
            'alamat' => 'Alamat Dikirim',
            'metode_pembayaran' => 'midtrans',
            'total_harga' => 100000,
            'ongkir' => 10000,
            'status' => 'dikirim',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $orderPending = Order::create([
            'kode_order' => $prefix . 'PENDING',
            'nama_customer' => 'Customer Belum Bayar',
            'no_hp' => '081200000003',
            'alamat' => 'Alamat Pending',
            'metode_pembayaran' => 'midtrans',
            'total_harga' => 100000,
            'ongkir' => 10000,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $orderDiproses = Order::create([
            'kode_order' => $prefix . 'DIPROSES',
            'nama_customer' => 'Customer Belum Dikirim',
            'no_hp' => '081200000004',
            'alamat' => 'Alamat Diproses',
            'metode_pembayaran' => 'midtrans',
            'total_harga' => 100000,
            'ongkir' => 10000,
            'status' => 'diproses',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $res = $this->actingAs($admin)->get('/order?search=' . urlencode($prefix));
        $res->assertStatus(200);

        $res->assertViewHas('orders', function ($orders) use ($orderDiproses, $orderPending, $orderDikirim, $orderSelesai) {
            $codes = $orders->pluck('kode_order')->values()->all();
            $posDiproses = array_search($orderDiproses->kode_order, $codes, true);
            $posPending  = array_search($orderPending->kode_order, $codes, true);
            $posDikirim  = array_search($orderDikirim->kode_order, $codes, true);
            $posSelesai  = array_search($orderSelesai->kode_order, $codes, true);

            // Verifikasi status 'diproses' (belum kirim) & 'pending' (belum bayar) tampil lebih dulu dari 'dikirim' & 'selesai'
            return $posDiproses !== false
                && $posPending !== false
                && $posDikirim !== false
                && $posSelesai !== false
                && $posDiproses < $posDikirim
                && $posDiproses < $posSelesai
                && $posPending < $posDikirim
                && $posPending < $posSelesai;
        });
    }

    public function test_image_compressor_stores_compressed_jpg(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('sample.png', 1600, 1200);
        $storedPath = ImageCompressor::compressAndStore($file, 'test_compressed', 800, 75);

        $this->assertStringStartsWith('test_compressed/', $storedPath);
        $this->assertStringEndsWith('.jpg', $storedPath);
        Storage::disk('public')->assertExists($storedPath);
    }
}
