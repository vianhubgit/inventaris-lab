<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Notification as NotificationFacade;

class Notifier
{
    /** Kirim notifikasi ke seluruh admin aktif. */
    public static function toAdmins(Notification $notification): void
    {
        self::send(UserRole::ADMIN, $notification);
    }

    /** Kirim notifikasi ke seluruh sekretaris aktif. */
    public static function toSekretaris(Notification $notification): void
    {
        self::send(UserRole::SEKRETARIS, $notification);
    }

    private static function send(UserRole $role, Notification $notification): void
    {
        $users = User::query()->where('role', $role)->where('is_active', true)->get();

        if ($users->isNotEmpty()) {
            NotificationFacade::send($users, $notification);
        }
    }
}
