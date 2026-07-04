<?php

namespace App\Models;

use App\Enums\RepairStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Repair extends Model
{
    /** @use HasFactory<\Database\Factories\RepairFactory> */
    use HasFactory;

    protected $fillable = [
        'item_id',
        'report_id',
        'user_id',
        'tanggal',
        'deskripsi',
        'biaya',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => RepairStatus::class,
            'tanggal' => 'date',
            'biaya' => 'decimal:2',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
