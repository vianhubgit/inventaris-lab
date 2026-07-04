<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\Lab;
use App\Models\Procurement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_is_notified_when_sekretaris_creates_report(): void
    {
        $admin = User::factory()->admin()->create();
        $sekretaris = User::factory()->sekretaris()->create();
        $item = Item::factory()->create();

        $this->actingAs($sekretaris)->post(route('sekretaris.reports.store'), [
            'type' => 'rusak',
            'lab_id' => $item->lab_id,
            'item_id' => $item->id,
            'jumlah' => 1,
            'keterangan' => 'Rusak.',
        ])->assertRedirect();

        $this->assertSame(1, $admin->fresh()->unreadNotifications()->count());
        $this->assertDatabaseHas('notifications', ['notifiable_id' => $admin->id]);
    }

    public function test_sekretaris_is_notified_when_admin_adds_item(): void
    {
        $admin = User::factory()->admin()->create();
        $sekretaris = User::factory()->sekretaris()->create();

        $this->actingAs($admin)->post(route('admin.items.store'), [
            'nama' => 'Switch Baru',
            'category_id' => Category::factory()->create()->id,
            'lab_id' => Lab::factory()->create()->id,
            'jumlah_total' => 2,
            'status' => 'baik',
        ])->assertRedirect();

        $this->assertSame(1, $sekretaris->fresh()->unreadNotifications()->count());
    }

    public function test_sekretaris_is_notified_when_procurement_status_changes(): void
    {
        $admin = User::factory()->admin()->create();
        $sekretaris = User::factory()->sekretaris()->create();
        $procurement = Procurement::factory()->create(['user_id' => $sekretaris->id]);

        $this->actingAs($admin)->patch(route('admin.procurements.status', $procurement), [
            'status' => 'disetujui',
        ])->assertRedirect();

        $this->assertSame(1, $sekretaris->fresh()->unreadNotifications()->count());
    }

    public function test_notification_feed_returns_unread_count_and_items(): void
    {
        $admin = User::factory()->admin()->create();
        $sekretaris = User::factory()->sekretaris()->create();
        $item = Item::factory()->create();

        $this->actingAs($sekretaris)->post(route('sekretaris.reports.store'), [
            'type' => 'rusak',
            'lab_id' => $item->lab_id,
            'item_id' => $item->id,
            'jumlah' => 1,
        ]);

        $this->actingAs($admin)->getJson(route('notifications.feed'))
            ->assertOk()
            ->assertJsonPath('count', 1)
            ->assertJsonStructure(['count', 'items' => [['id', 'title', 'message', 'url', 'read', 'time']]]);
    }

    public function test_user_can_mark_all_notifications_read(): void
    {
        $admin = User::factory()->admin()->create();
        $sekretaris = User::factory()->sekretaris()->create();
        $item = Item::factory()->create();

        // Hasilkan satu notifikasi untuk admin.
        $this->actingAs($sekretaris)->post(route('sekretaris.reports.store'), [
            'type' => 'hilang',
            'lab_id' => $item->lab_id,
            'item_id' => $item->id,
            'jumlah' => 1,
        ]);

        $this->assertSame(1, $admin->fresh()->unreadNotifications()->count());

        $this->actingAs($admin)->post(route('notifications.readAll'))->assertRedirect();

        $this->assertSame(0, $admin->fresh()->unreadNotifications()->count());
    }
}
