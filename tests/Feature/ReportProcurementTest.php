<?php

namespace Tests\Feature;

use App\Enums\ProcurementStatus;
use App\Models\Item;
use App\Models\Procurement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReportProcurementTest extends TestCase
{
    use RefreshDatabase;

    public function test_sekretaris_can_create_damage_report_with_photo(): void
    {
        Storage::fake('public');
        $sekretaris = User::factory()->sekretaris()->create();
        $item = Item::factory()->create();

        $this->actingAs($sekretaris)->post(route('sekretaris.reports.store'), [
            'type' => 'rusak',
            'lab_id' => $item->lab_id,
            'item_id' => $item->id,
            'jumlah' => 1,
            'keterangan' => 'Mouse tidak berfungsi.',
            'foto' => UploadedFile::fake()->image('rusak.jpg'),
        ])->assertRedirect();

        $this->assertDatabaseHas('reports', [
            'item_id' => $item->id,
            'type' => 'rusak',
            'user_id' => $sekretaris->id,
        ]);
    }

    public function test_report_must_match_lab_of_item(): void
    {
        $sekretaris = User::factory()->sekretaris()->create();
        $item = Item::factory()->create();
        $otherLabId = \App\Models\Lab::factory()->create()->id;

        $this->actingAs($sekretaris)->post(route('sekretaris.reports.store'), [
            'type' => 'rusak',
            'lab_id' => $otherLabId,
            'item_id' => $item->id,
            'jumlah' => 1,
        ])->assertSessionHasErrors('item_id');
    }

    public function test_sekretaris_can_submit_new_item_procurement(): void
    {
        $sekretaris = User::factory()->sekretaris()->create();

        $this->actingAs($sekretaris)->post(route('sekretaris.procurements.store'), [
            'is_new_item' => '1',
            'nama_barang_baru' => 'Switch 48 Port',
            'jumlah' => 2,
            'alasan' => 'Kebutuhan lab baru.',
        ])->assertRedirect(route('sekretaris.procurements.index'));

        $this->assertDatabaseHas('procurements', [
            'nama_barang_baru' => 'Switch 48 Port',
            'is_new_item' => true,
            'status' => ProcurementStatus::MENUNGGU->value,
        ]);
    }

    public function test_admin_can_change_procurement_status(): void
    {
        $admin = User::factory()->admin()->create();
        $procurement = Procurement::factory()->create();

        $this->actingAs($admin)->patch(route('admin.procurements.status', $procurement), [
            'status' => 'disetujui',
            'catatan_admin' => 'OK.',
        ])->assertRedirect();

        $this->assertSame(ProcurementStatus::DISETUJUI, $procurement->fresh()->status);
    }
}
