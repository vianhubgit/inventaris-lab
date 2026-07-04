<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    // Catatan: Gate::before() memberi admin akses penuh, jadi method di bawah
    // efektif mengatur akses untuk peran non-admin (sekretaris) → semua false.

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, User $model): bool
    {
        // Admin tidak boleh menghapus dirinya sendiri.
        return $user->isAdmin() && $user->id !== $model->id;
    }
}
