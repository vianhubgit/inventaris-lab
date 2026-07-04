<?php

namespace App\Observers;

use App\Models\Item;
use App\Services\ActivityLogger;

class ItemObserver
{
    public function created(Item $item): void
    {
        ActivityLogger::created($item, "Menambah barang \"{$item->nama}\" (jumlah: {$item->jumlah_total}).");
    }

    public function updated(Item $item): void
    {
        $changes = collect($item->getChanges())
            ->except(['updated_at'])
            ->keys()
            ->implode(', ');

        if ($changes !== '') {
            ActivityLogger::updated($item, "Mengubah barang \"{$item->nama}\" (field: {$changes}).");
        }
    }

    public function deleted(Item $item): void
    {
        ActivityLogger::deleted($item, "Menghapus barang \"{$item->nama}\".");
    }
}
