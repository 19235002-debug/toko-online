<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Voucher;

class VoucherTest extends TestCase
{
    public function test_admin_can_access_voucher_index()
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/voucher');
        $response->assertStatus(200);
        $response->assertSee('HEMAT20');
    }

    public function test_regular_user_cannot_access_admin_voucher()
    {
        $user = User::where('role', 'user')->first();
        $response = $this->actingAs($user)->get('/voucher');
        $response->assertRedirect('/');
    }

    public function test_user_can_check_valid_voucher()
    {
        $user = User::where('role', 'user')->first();

        $response = $this->actingAs($user)->postJson('/voucher/check', [
            'kode_voucher' => 'HEMAT20',
            'subtotal' => 200000, // 20% of 200k = 40k (under 50k cap)
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'kode_voucher' => 'HEMAT20',
                'diskon' => 40000,
                'total_akhir' => 160000,
            ]);
    }

    public function test_user_voucher_caps_at_maksimal_diskon()
    {
        $user = User::where('role', 'user')->first();

        $response = $this->actingAs($user)->postJson('/voucher/check', [
            'kode_voucher' => 'HEMAT20',
            'subtotal' => 500000, // 20% of 500k = 100k -> capped at 50k
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'diskon' => 50000,
                'total_akhir' => 450000,
            ]);
    }

    public function test_user_voucher_fails_below_minimal_belanja()
    {
        $user = User::where('role', 'user')->first();

        $response = $this->actingAs($user)->postJson('/voucher/check', [
            'kode_voucher' => 'HEMAT20',
            'subtotal' => 50000, // min is 100k
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_user_voucher_fails_if_not_found()
    {
        $user = User::where('role', 'user')->first();

        $response = $this->actingAs($user)->postJson('/voucher/check', [
            'kode_voucher' => 'TIDAKADA999',
            'subtotal' => 200000,
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }
}

