<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    /** @use HasFactory<\Database\Factories\ActivityLogFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'subject_type',
        'subject_id',
        'description',
        'ip_address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Subjek polimorfik (Item, Report, dll.) bila ada. */
    public function subject()
    {
        if (! $this->subject_type || ! class_exists($this->subject_type)) {
            return null;
        }

        $query = $this->subject_type::query();

        if (in_array(\Illuminate\Database\Eloquent\SoftDeletes::class, class_uses_recursive($this->subject_type), true)) {
            $query->withTrashed();
        }

        return $query->find($this->subject_id);
    }
}
