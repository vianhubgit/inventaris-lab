<?php

namespace App\Policies;

use App\Enums\ProcurementStatus;
use App\Models\Procurement;
use App\Models\User;

class ProcurementPolicy
{
    // Admin di-handle Gate::before(). Aturan di sini untuk sekretaris.

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Procurement $procurement): bool
    {
        return $procurement->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isSekretaris();
    }

    /** Sekretaris boleh mengubah pengajuannya selama masih MENUNGGU. */
    public function update(User $user, Procurement $procurement): bool
    {
        return $procurement->user_id === $user->id
            && $procurement->status === ProcurementStatus::MENUNGGU;
    }

    public function delete(User $user, Procurement $procurement): bool
    {
        return $procurement->user_id === $user->id
            && $procurement->status === ProcurementStatus::MENUNGGU;
    }

    /** Hanya admin (Gate::before) yang boleh mengubah status pengajuan. */
    public function updateStatus(User $user, Procurement $procurement): bool
    {
        return false;
    }
}
