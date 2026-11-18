<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class RoleAccessTest extends TestCase
{
    public function test_guest_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_user_cannot_access_admin_dashboard_and_redirects_home()
    {
        $user = User::where('role', 'user')->first();
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertRedirect('/');
    }

    public function test_user_cannot_access_barang_and_redirects_home()
    {
        $user = User::where('role', 'user')->first();
        $response = $this->actingAs($user)->get('/barang');
        $response->assertRedirect('/');
    }

    public function test_user_can_access_pesanan_saya()
    {
        $user = User::where('role', 'user')->first();
        $response = $this->actingAs($user)->get('/pesanan-saya');
        $response->assertStatus(200);
    }

    public function test_user_can_access_profile_frontend()
    {
        $user = User::where('role', 'user')->first();
        $response = $this->actingAs($user)->get('/profile');
        $response->assertStatus(200);
        $response->assertSee('Profil Saya');
    }

    public function test_admin_can_access_dashboard()
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_admin_can_access_barang()
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/barang');
        $response->assertStatus(200);
    }

    public function test_login_as_user_redirects_to_home()
    {
        $user = User::create([
            'nama' => 'Test User Temp',
            'email' => 'temp_user_' . uniqid() . '@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'role' => 'user'
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
        $user->delete();
    }

    public function test_login_as_admin_redirects_to_dashboard()
    {
        $admin = User::create([
            'nama' => 'Test Admin Temp',
            'email' => 'temp_admin_' . uniqid() . '@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'role' => 'admin'
        ]);

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($admin);
        $admin->delete();
    }

    public function test_guest_cannot_checkout_without_login()
    {
        $response = $this->postJson('/checkout', []);
        $response->assertStatus(401);

        $webResponse = $this->post('/checkout', []);
        $webResponse->assertRedirect('/login');
    }

    public function test_register_requires_no_hp_and_alamat()
    {
        $response = $this->post('/register', [
            'nama' => 'Baru Tanpa Alamat',
            'email' => 'no_alamat_' . uniqid() . '@example.com',
            'password' => 'password123',
            'passwordConfirm' => 'password123',
        ]);

        $response->assertSessionHasErrors(['no_hp', 'alamat']);
    }

    public function test_register_with_complete_data_succeeds()
    {
        $email = 'lengkap_' . uniqid() . '@example.com';
        $response = $this->post('/register', [
            'nama' => 'Budi Lengkap',
            'email' => $email,
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Merdeka No. 123, Jakarta Selatan',
            'password' => 'password123',
            'passwordConfirm' => 'password123',
        ]);

        $response->assertRedirect('/');
        $user = User::where('email', $email)->first();
        $this->assertNotNull($user);
        $this->assertEquals('081234567890', $user->no_hp);
        $this->assertEquals('Jl. Merdeka No. 123, Jakarta Selatan', $user->alamat);
        $user->delete();
    }

    public function test_shop_catalog_page_loads_with_filters()
    {
        $response = $this->get('/shop');
        $response->assertStatus(200);
        $response->assertSee('Katalog Produk');

        $filtered = $this->get('/shop?sort=termurah&search=Shirt');
        $filtered->assertStatus(200);
    }
}
