<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Kategori;

class CategoryHierarchyTest extends TestCase
{
    public function test_admin_can_view_hierarchical_categories()
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/kategori');
        $response->assertStatus(200);
        $response->assertSee('Wanita');
        $response->assertSee('Pria');
        $response->assertSee('Anak');
        $response->assertSee('Bayi');
        $response->assertSee('Luaran');
    }

    public function test_admin_can_create_subcategory_with_same_name_under_different_parent()
    {
        $admin = User::where('role', 'admin')->first();
        $bayi = Kategori::whereNull('parent_id')->where('nama_kategori', 'Bayi')->first();
        $this->assertNotNull($bayi);

        $response = $this->actingAs($admin)->post('/kategori', [
            'parent_id' => $bayi->id_kategori,
            'nama_kategori' => 'Luaran',
            'urutan' => 10,
        ]);

        $response->assertRedirect('/kategori');
        $created = Kategori::where('parent_id', $bayi->id_kategori)->where('nama_kategori', 'Luaran')->first();
        $this->assertNotNull($created);

        // Cleanup
        $created->delete();
    }

    public function test_shop_page_displays_uniqlo_style_categories_and_filters_by_subcategory()
    {
        $response = $this->get('/shop?kategori=Pria&sub_kategori=' . urlencode('Kemeja & Polo'));
        $response->assertStatus(200);
        $response->assertSee('Wanita');
        $response->assertSee('Pria');
        $response->assertSee('Anak');
        $response->assertSee('Bayi');
        $response->assertSee('Kemeja &amp; Polo', false);
    }
}

