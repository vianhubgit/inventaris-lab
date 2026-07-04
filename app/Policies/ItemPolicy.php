<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;

class ItemPolicy
{
    // Admin di-handle oleh Gate::before(). Aturan di sini berlaku untuk sekretaris.

    /** Sekretaris boleh melihat inventaris (read-only). */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Item $item): bool
    {
        return true;
    }

    /** Sekretaris TIDAK boleh menambah inventaris. */
    public function create(User $user): bool
    {
        return false;
    }

    /** Sekretaris TIDAK boleh mengubah jumlah / data inventaris. */
    public function update(User $user, Item $item): bool
    {
        return false;
    }

    /** Sekretaris TIDAK boleh menghapus inventaris. */
    public function delete(User $user, Item $item): bool
    {
        return false;
    }
}
