<?php

namespace App\Observers;

use App\Models\Procurement;
use App\Services\ActivityLogger;

class ProcurementObserver
{
    public function creating(Procurement $procurement): void
    {
        $procurement->requested_at ??= now();
    }

    public function created(Procurement $procurement): void
    {
        ActivityLogger::created(
            $procurement,
            "Mengajukan barang \"{$procurement->nama_barang}\" sebanyak {$procurement->jumlah}."
        );
    }

    public function updated(Procurement $procurement): void
    {
        if ($procurement->wasChanged('status')) {
            ActivityLogger::updated(
                $procurement,
                "Mengubah status pengajuan #{$procurement->id} menjadi {$procurement->status->label()}."
            );
        }
    }
}
