<?php

namespace Database\Seeders;

use App\Enums\ItemStatus;
use App\Enums\ProcurementStatus;
use App\Enums\ReportStatus;
use App\Enums\ReportType;
use App\Models\Category;
use App\Models\Item;
use App\Models\Procurement;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $sekretaris = User::where('username', 'sekretaris')->first();
        if (! $sekretaris) {
            return;
        }

        $items = Item::with('labTable.group')->inRandomOrder()->take(25)->get();
        if ($items->isEmpty()) {
            return;
        }

        // Laporan rusak & hilang tersebar 6 bulan terakhir.
        foreach ($items->take(18) as $i => $item) {
            $type = $i % 3 === 0 ? ReportType::HILANG : ReportType::RUSAK;
            $when = Carbon::now()->subDays(random_int(0, 170));

            Report::create([
                'user_id' => $sekretaris->id,
                'type' => $type,
                'lab_id' => $item->lab_id,
                'lab_group_id' => $item->labTable?->lab_group_id,
                'lab_table_id' => $item->lab_table_id,
                'item_id' => $item->id,
                'jumlah' => random_int(1, 2),
                'keterangan' => $type === ReportType::RUSAK ? 'Tidak menyala / bermasalah saat praktik.' : 'Tidak ditemukan saat pengecekan.',
                'status' => collect(ReportStatus::cases())->random(),
                'reported_at' => $when,
                'created_at' => $when,
                'updated_at' => $when,
            ]);
        }

        // Tandai beberapa barang sesuai laporan.
        $items->take(4)->each(fn (Item $it) => $it->update(['status' => ItemStatus::RUSAK]));

        // Pengajuan barang (existing & barang baru).
        $categories = Category::pluck('id', 'nama');

        Procurement::create([
            'user_id' => $sekretaris->id,
            'item_id' => $items->first()->id,
            'is_new_item' => false,
            'jumlah' => 5,
            'alasan' => 'Penambahan stok untuk praktik rutin.',
            'status' => ProcurementStatus::MENUNGGU,
            'requested_at' => Carbon::now()->subDays(3),
        ]);

        Procurement::create([
            'user_id' => $sekretaris->id,
            'category_id' => $categories['Switch'] ?? null,
            'is_new_item' => true,
            'nama_barang_baru' => 'Switch Manageable 48 Port',
            'jumlah' => 2,
            'alasan' => 'Kebutuhan lab jaringan baru.',
            'status' => ProcurementStatus::DISETUJUI,
            'catatan_admin' => 'Disetujui, menunggu pembelian.',
            'requested_at' => Carbon::now()->subDays(10),
        ]);

        Procurement::create([
            'user_id' => $sekretaris->id,
            'category_id' => $categories['Access Point'] ?? null,
            'is_new_item' => true,
            'nama_barang_baru' => 'Access Point Outdoor',
            'jumlah' => 3,
            'alasan' => 'Perluasan jangkauan WiFi.',
            'status' => ProcurementStatus::SUDAH_DIBELI,
            'catatan_admin' => 'Sudah dibeli dan diterima.',
            'requested_at' => Carbon::now()->subDays(20),
        ]);
    }
}
