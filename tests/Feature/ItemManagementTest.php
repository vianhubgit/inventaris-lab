<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\Lab;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_item(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();
        $lab = Lab::factory()->create();

        $this->actingAs($admin)->post(route('admin.items.store'), [
            'nama' => 'Monitor LED 24"',
            'category_id' => $category->id,
            'lab_id' => $lab->id,
            'jumlah_total' => 10,
            'status' => 'baik',
        ])->assertRedirect(route('admin.items.index'));

        $this->assertDatabaseHas('items', ['nama' => 'Monitor LED 24"', 'jumlah_total' => 10]);
    }

    public function test_admin_can_update_item(): void
    {
        $admin = User::factory()->admin()->create();
        $item = Item::factory()->create(['jumlah_total' => 5]);

        $this->actingAs($admin)->put(route('admin.items.update', $item), [
            'nama' => $item->nama,
            'category_id' => $item->category_id,
            'lab_id' => $item->lab_id,
            'jumlah_total' => 15,
            'status' => 'baik',
        ])->assertRedirect(route('admin.items.index'));

        $this->assertSame(15, $item->fresh()->jumlah_total);
    }

    public function test_admin_can_adjust_stock(): void
    {
        $admin = User::factory()->admin()->create();
        $item = Item::factory()->create(['jumlah_total' => 5]);

        $this->actingAs($admin)->post(route('admin.items.adjust', $item), [
            'mode' => 'tambah',
            'jumlah' => 3,
        ]);

        $this->assertSame(8, $item->fresh()->jumlah_total);
    }

    public function test_admin_can_soft_delete_item(): void
    {
        $admin = User::factory()->admin()->create();
        $item = Item::factory()->create();

        $this->actingAs($admin)->delete(route('admin.items.destroy', $item))
            ->assertRedirect(route('admin.items.index'));

        $this->assertSoftDeleted($item);
    }

    public function test_creating_item_writes_activity_log(): void
    {
        $admin = User::factory()->admin()->create();
        $item = Item::factory()->create();

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'created',
            'subject_type' => Item::class,
            'subject_id' => $item->id,
        ]);
    }
}
