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
