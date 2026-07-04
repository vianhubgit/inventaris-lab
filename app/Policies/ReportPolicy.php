<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

class ReportPolicy
{
    // Admin di-handle Gate::before(). Aturan di sini untuk sekretaris.

    public function viewAny(User $user): bool
    {
        return true;
    }

    /** Sekretaris hanya boleh melihat laporannya sendiri. */
    public function view(User $user, Report $report): bool
    {
        return $report->user_id === $user->id;
    }

    /** Sekretaris boleh membuat laporan. */
    public function create(User $user): bool
    {
        return $user->isSekretaris();
    }

    /** Sekretaris boleh memperbarui laporannya sendiri (mis. sebelum diproses). */
    public function update(User $user, Report $report): bool
    {
        return $report->user_id === $user->id
            && $report->status === \App\Enums\ReportStatus::DILAPORKAN;
    }

    public function delete(User $user, Report $report): bool
    {
        return $report->user_id === $user->id
            && $report->status === \App\Enums\ReportStatus::DILAPORKAN;
    }
}
