<?php

namespace App\Services;

use App\Enums\ItemStatus;
use App\Enums\ProcurementStatus;
use App\Enums\ReportStatus;
use App\Enums\ReportType;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Item;
use App\Models\Lab;
use App\Models\Procurement;
use App\Models\Report;
use Illuminate\Support\Carbon;

class DashboardService
{
    /** Ringkasan kartu statistik untuk dashboard admin. */
    public function adminStats(): array
    {
        return [
            'total_barang' => (int) Item::sum('jumlah_total'),
            'total_jenis' => Item::count(),
            'total_kategori' => Category::count(),
            'total_lab' => Lab::count(),
            'barang_rusak' => Item::where('status', ItemStatus::RUSAK)->count(),
            'barang_hilang' => Item::where('status', ItemStatus::HILANG)->count(),
            'laporan_rusak' => Report::rusak()->count(),
            'laporan_hilang' => Report::hilang()->count(),
            'pengajuan_menunggu' => Procurement::where('status', ProcurementStatus::MENUNGGU)->count(),
            'laporan_belum_selesai' => Report::where('status', '!=', ReportStatus::SELESAI)->count(),
        ];
    }

    /** Data chart: jumlah barang per kategori. */
    public function itemsPerCategory(): array
    {
        $rows = Category::withSum('items as total', 'jumlah_total')
            ->orderByDesc('total')
            ->take(8)
            ->get();

        return [
            'labels' => $rows->pluck('nama')->all(),
            'data' => $rows->pluck('total')->map(fn ($v) => (int) $v)->all(),
        ];
    }

    /** Data chart: jumlah barang per lab. */
    public function itemsPerLab(): array
    {
        $rows = Lab::withSum('items as total', 'jumlah_total')->get();

        return [
            'labels' => $rows->pluck('nama')->all(),
            'data' => $rows->pluck('total')->map(fn ($v) => (int) $v)->all(),
        ];
    }

    /** Barang yang paling sering dilaporkan (rusak / hilang). */
    public function mostReported(ReportType $type, int $limit = 5): array
    {
        return Report::type($type)
            ->selectRaw('item_id, SUM(jumlah) as total')
            ->with('item:id,nama')
            ->groupBy('item_id')
            ->orderByDesc('total')
            ->take($limit)
            ->get()
            ->map(fn (Report $r) => [
                'nama' => $r->item?->nama ?? 'Tidak diketahui',
                'total' => (int) $r->total,
            ])
            ->all();
    }

    /** Tren laporan 6 bulan terakhir (rusak vs hilang). */
    public function reportTrend(int $months = 6): array
    {
        $labels = [];
        $rusak = [];
        $hilang = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[] = $month->translatedFormat('M Y');

            $base = Report::whereYear('reported_at', $month->year)
                ->whereMonth('reported_at', $month->month);

            $rusak[] = (clone $base)->where('type', ReportType::RUSAK)->count();
            $hilang[] = (clone $base)->where('type', ReportType::HILANG)->count();
        }

        return [
            'labels' => $labels,
            'datasets' => [
                ['label' => 'Rusak', 'data' => $rusak],
                ['label' => 'Hilang', 'data' => $hilang],
            ],
        ];
    }

    /** Pengajuan terbaru. */
    public function latestProcurements(int $limit = 5)
    {
        return Procurement::with(['user:id,name', 'item:id,nama', 'category:id,nama'])
            ->latest()
            ->take($limit)
            ->get();
    }

    /** Log aktivitas terbaru. */
    public function latestActivities(int $limit = 8)
    {
        return ActivityLog::with('user:id,name')
            ->latest()
            ->take($limit)
            ->get();
    }

    /** Statistik ringkas untuk dashboard sekretaris (berdasarkan user). */
    public function sekretarisStats(int $userId): array
    {
        return [
            'total_barang' => (int) Item::sum('jumlah_total'),
            'total_jenis' => Item::count(),
            'laporan_saya' => Report::where('user_id', $userId)->count(),
            'laporan_rusak' => Report::where('user_id', $userId)->rusak()->count(),
            'laporan_hilang' => Report::where('user_id', $userId)->hilang()->count(),
            'pengajuan_saya' => Procurement::where('user_id', $userId)->count(),
            'pengajuan_menunggu' => Procurement::where('user_id', $userId)
                ->where('status', ProcurementStatus::MENUNGGU)->count(),
            'pengajuan_disetujui' => Procurement::where('user_id', $userId)
                ->where('status', ProcurementStatus::DISETUJUI)->count(),
        ];
    }
}
