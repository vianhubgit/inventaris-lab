<?php

namespace App\Observers;

use App\Models\Report;
use App\Services\ActivityLogger;

class ReportObserver
{
    public function creating(Report $report): void
    {
        $report->reported_at ??= now();
    }

    public function created(Report $report): void
    {
        ActivityLogger::created(
            $report,
            "Membuat laporan {$report->type->label()} untuk barang #{$report->item_id} (jumlah: {$report->jumlah})."
        );
    }

    public function updated(Report $report): void
    {
        if ($report->wasChanged('status')) {
            ActivityLogger::updated(
                $report,
                "Memperbarui status laporan #{$report->id} menjadi {$report->status->label()}."
            );
        }
    }
}
