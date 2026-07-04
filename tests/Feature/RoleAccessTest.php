<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\Lab;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_sekretaris_cannot_access_admin_dashboard(): void
    {
        $sekretaris = User::factory()->sekretaris()->create();

        $this->actingAs($sekretaris)->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
    }

    public function test_admin_cannot_access_sekretaris_area(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('sekretaris.dashboard'))->assertForbidden();
    }

    public function test_sekretaris_cannot_create_item(): void
    {
        $sekretaris = User::factory()->sekretaris()->create();

        $this->actingAs($sekretaris)
            ->post(route('admin.items.store'), [
                'nama' => 'Barang Ilegal',
                'category_id' => Category::factory()->create()->id,
                'lab_id' => Lab::factory()->create()->id,
                'jumlah_total' => 1,
                'status' => 'baik',
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('items', ['nama' => 'Barang Ilegal']);
    }

    public function test_sekretaris_can_view_inventory(): void
    {
        $sekretaris = User::factory()->sekretaris()->create();
        Item::factory()->create(['nama' => 'PC Lab']);

        $this->actingAs($sekretaris)
            ->get(route('sekretaris.inventory.index'))
            ->assertOk()
            ->assertSee('PC Lab');
    }
}
