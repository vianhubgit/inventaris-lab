<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    /**
     * Catat satu aktivitas ke tabel activity_logs.
     */
    public static function log(string $action, string $description, ?Model $subject = null): ActivityLog
    {
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'subject_type' => $subject ? $subject::class : null,
            'subject_id' => $subject?->getKey(),
            'description' => $description,
            'ip_address' => Request::ip(),
        ]);
    }

    public static function created(Model $subject, string $description): ActivityLog
    {
        return self::log('created', $description, $subject);
    }

    public static function updated(Model $subject, string $description): ActivityLog
    {
        return self::log('updated', $description, $subject);
    }

    public static function deleted(Model $subject, string $description): ActivityLog
    {
        return self::log('deleted', $description, $subject);
    }
}
